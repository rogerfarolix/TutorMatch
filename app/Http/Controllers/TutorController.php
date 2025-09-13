<?php

namespace App\Http\Controllers;

use App\Models\Tutor;
use Illuminate\Http\Request;

class TutorController extends Controller
{
    /**
     * Affiche la liste des tuteurs
     */
    public function index()
    {
        $tutors = Tutor::paginate(10);
        return view('tutors.index', compact('tutors'));
    }

    /**
     * Affiche le formulaire de création d'un tuteur
     */
    public function create()
    {
        $subjects = ['Mathématiques', 'Physique', 'Chimie', 'Français', 'Anglais', 'Histoire', 'Géographie', 'SVT', 'Philosophie'];
        $levels = ['Collège', 'Lycée', 'Terminale', 'Supérieur'];
        $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

        return view('tutors.create', compact('subjects', 'levels', 'days'));
    }

    /**
     * Sauvegarde un nouveau tuteur
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'subjects' => 'required|array|min:1',
            'levels' => 'required|array|min:1',
            'description' => 'nullable|string',
            'hourly_rate' => 'nullable|numeric|min:0',
            'experience_years' => 'nullable|integer|min:0'
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

        Tutor::create($validated);

        return redirect()->route('tutors.index')
                        ->with('success', 'Tuteur créé avec succès.');
    }

    /**
     * Affiche les détails d'un tuteur
     */
    public function show(Tutor $tutor)
    {
        return view('tutors.show', compact('tutor'));
    }

    /**
     * Affiche le formulaire d'édition d'un tuteur
     */
    public function edit(Tutor $tutor)
    {
        $subjects = ['Mathématiques', 'Physique', 'Chimie', 'Français', 'Anglais', 'Histoire', 'Géographie', 'SVT', 'Philosophie'];
        $levels = ['Collège', 'Lycée', 'Terminale', 'Supérieur'];
        $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

        return view('tutors.edit', compact('tutor', 'subjects', 'levels', 'days'));
    }

    /**
     * Met à jour un tuteur
     */
    public function update(Request $request, Tutor $tutor)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'subjects' => 'required|array|min:1',
            'levels' => 'required|array|min:1',
            'description' => 'nullable|string',
            'hourly_rate' => 'nullable|numeric|min:0',
            'experience_years' => 'nullable|integer|min:0'
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

        $tutor->update($validated);

        return redirect()->route('tutors.index')
                        ->with('success', 'Tuteur mis à jour avec succès.');
    }

    /**
     * Supprime un tuteur
     */
    public function destroy(Tutor $tutor)
    {
        $tutor->delete();

        return redirect()->route('tutors.index')
                        ->with('success', 'Tuteur supprimé avec succès.');
    }
}
