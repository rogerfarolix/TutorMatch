<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Tutor;
use App\Models\MatchModel;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord principal
     */
    public function index()
    {
        $stats = [
            'total_students' => Student::count(),
            'total_tutors' => Tutor::count(),
            'total_matches' => MatchModel::count(),
            'accepted_matches' => MatchModel::where('status', 'accepted')->count(),
            'suggested_matches' => MatchModel::where('status', 'suggested')->count(),
            'rejected_matches' => MatchModel::where('status', 'rejected')->count()
        ];

        // Derniers éléments ajoutés
        $recent_students = Student::latest()->take(5)->get();
        $recent_tutors = Tutor::latest()->take(5)->get();
        $best_matches = MatchModel::with(['student', 'tutor'])
                            ->where('status', 'suggested')
                            ->orderBy('compatibility_score', 'desc')
                            ->take(10)
                            ->get();

        return view('dashboard', compact('stats', 'recent_students', 'recent_tutors', 'best_matches'));
    }
}
