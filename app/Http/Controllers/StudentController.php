<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Services\MatchmakingService;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    protected $matchmakingService;

    public function __construct(MatchmakingService $matchmakingService)
    {
        $this->matchmakingService = $matchmakingService;
    }

    /**
     * Affiche la liste des élèves
     */
    public function index()
    {
        $students = Student::paginate(10);
        return view('students.index', compact('students'));
    }

    /**
     * Affiche le formulaire de création d'un élève
     */
    public function create()
    {
        $subjects = ['Mathématiques', 'Physique', 'Chimie', 'Français', 'Anglais', 'Histoire', 'Géographie', 'SVT', 'Philosophie'];
        $levels = ['Collège', 'Lycée', 'Terminale', 'Supérieur'];
        $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

        return view('students.create', compact('subjects', 'levels', 'days'));
    }

    /**
     * Sauvegarde un nouvel élève
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'requested_subjects' => 'required|array|min:1',
            'level' => 'required|string',
            'description' => 'nullable|string',
            'budget_max' => 'nullable|numeric|min:0'
        ]);

        // Traitement des disponibilités
        $availability = [];
        $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

        foreach ($days as $day) {
            if ($request->has("availability_{$day}_start") && $request->has("availability_{$day}_end")) {
                $startTime = $request->input("availability_{$day}_start");
                $endTime = $request->input("availability_{$day}_end");

                if ($startTime && $endTime) {
                    $availability[] = [
                        'day' => $day,
                        'start_time' => $startTime,
                        'end_time' => $endTime
                    ];
                }
            }
        }

        $validated['availability'] = $availability;

        $student = Student::create($validated);

        // Générer automatiquement les matchs
        $this->matchmakingService->saveMatchesForStudent($student);

        return redirect()->route('students.index')
                        ->with('success', 'Élève créé avec succès et matchs générés.');
    }

    /**
     * Affiche les détails d'un élève
     */
    public function show(Student $student)
    {
        return view('students.show', compact('student'));
    }

    /**
     * Affiche le formulaire d'édition d'un élève
     */
    public function edit(Student $student)
    {
        $subjects = ['Mathématiques', 'Physique', 'Chimie', 'Français', 'Anglais', 'Histoire', 'Géographie', 'SVT', 'Philosophie'];
        $levels = ['Collège', 'Lycée', 'Terminale', 'Supérieur'];
        $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

        return view('students.edit', compact('student', 'subjects', 'levels', 'days'));
    }

    /**
     * Met à jour un élève
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'requested_subjects' => 'required|array|min:1',
            'level' => 'required|string',
            'description' => 'nullable|string',
            'budget_max' => 'nullable|numeric|min:0'
        ]);

        // Traitement des disponibilités
        $availability = [];
        $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

        foreach ($days as $day) {
            if ($request->has("availability_{$day}_start") && $request->has("availability_{$day}_end")) {
                $startTime = $request->input("availability_{$day}_start");
                $endTime = $request->input("availability_{$day}_end");

                if ($startTime && $endTime) {
                    $availability[] = [
                        'day' => $day,
                        'start_time' => $startTime,
                        'end_time' => $endTime
                    ];
                }
            }
        }

        $validated['availability'] = $availability;

        $student->update($validated);

        // Régénérer les matchs
        $this->matchmakingService->saveMatchesForStudent($student);

        return redirect()->route('students.index')
                        ->with('success', 'Élève mis à jour avec succès et matchs régénérés.');
    }

    /**
     * Supprime un élève
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('students.index')
                        ->with('success', 'Élève supprimé avec succès.');
    }

    /**
     * Affiche les tuteurs suggérés pour un élève
     */
    public function matches(Student $student)
    {
        $matches = $student->matches()
                          ->with('tutor')
                          ->where('status', 'suggested')
                          ->orderBy('compatibility_score', 'desc')
                          ->get();

        return view('students.matches', compact('student', 'matches'));
    }
}
