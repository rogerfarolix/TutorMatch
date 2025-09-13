<?php

namespace App\Http\Controllers;

use App\Models\MatchModel;
use App\Services\MatchmakingService;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    protected $matchmakingService;

    public function __construct(MatchmakingService $matchmakingService)
    {
        $this->matchmakingService = $matchmakingService;
    }

    /**
     * Affiche tous les matchs
     */
    public function index(Request $request)
    {
        $query = MatchModel::with(['student', 'tutor']);

        // Filtrer par statut si spécifié
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $matches = $query->orderBy('compatibility_score', 'desc')->paginate(15);

        return view('matches.index', compact('matches'));
    }

    /**
     * Génère tous les matchs
     */
    public function generateAll()
    {
        $this->matchmakingService->generateAllMatches();

        return redirect()->route('matches.index')
                        ->with('success', 'Tous les matchs ont été générés avec succès.');
    }

    /**
     * Accepte un match
     */
    public function accept(MatchModel $match)
    {
        $match->update(['status' => 'accepted']);

        return redirect()->back()
                        ->with('success', 'Match accepté avec succès.');
    }

    /**
     * Rejette un match
     */
    public function reject(MatchModel $match)
    {
        $match->update(['status' => 'rejected']);

        return redirect()->back()
                        ->with('success', 'Match rejeté.');
    }

    /**
     * Affiche les détails d'un match
     */
    public function show(MatchModel $match)
    {
        return view('matches.show', compact('match'));
    }
}
