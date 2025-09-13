<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Tutor;
use App\Models\MatchModel;

class MatchmakingService
{
    /**
     * Trouve les tuteurs compatibles pour un élève donné
     */
    public function findMatches(Student $student)
    {
        $tutors = Tutor::all();
        $matches = [];

        foreach ($tutors as $tutor) {
            $score = $this->calculateCompatibilityScore($student, $tutor);

            if ($score > 0) {
                $matchingDetails = $this->getMatchingDetails($student, $tutor);

                $matches[] = [
                    'tutor' => $tutor,
                    'score' => $score,
                    'details' => $matchingDetails
                ];
            }
        }

        // Trier par score décroissant
        usort($matches, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        return $matches;
    }

    /**
     * Calcule le score de compatibilité entre un élève et un tuteur
     */
    private function calculateCompatibilityScore(Student $student, Tutor $tutor)
    {
        $score = 0;
        $maxScore = 100;

        // Score pour les matières (40% du score total)
        $subjectScore = $this->calculateSubjectScore($student, $tutor);
        $score += $subjectScore * 0.4;

        // Score pour le niveau (30% du score total)
        $levelScore = $this->calculateLevelScore($student, $tutor);
        $score += $levelScore * 0.3;

        // Score pour la disponibilité (30% du score total)
        $availabilityScore = $this->calculateAvailabilityScore($student, $tutor);
        $score += $availabilityScore * 0.3;

        return round($score, 2);
    }

    /**
     * Calcule le score pour les matières
     */
    private function calculateSubjectScore(Student $student, Tutor $tutor)
    {
        $commonSubjects = array_intersect($student->requested_subjects, $tutor->subjects);

        if (empty($commonSubjects)) {
            return 0;
        }

        // Score basé sur le pourcentage de matières demandées que le tuteur peut enseigner
        return (count($commonSubjects) / count($student->requested_subjects)) * 100;
    }

    /**
     * Calcule le score pour le niveau
     */
    private function calculateLevelScore(Student $student, Tutor $tutor)
    {
        return in_array($student->level, $tutor->levels) ? 100 : 0;
    }

    /**
     * Calcule le score pour la disponibilité
     */
    private function calculateAvailabilityScore(Student $student, Tutor $tutor)
    {
        $commonSlots = $tutor->getCommonAvailability($student->availability);

        if (empty($commonSlots)) {
            return 0;
        }

        // Calculer le total d'heures en commun
        $totalOverlapHours = 0;
        foreach ($commonSlots as $slot) {
            $totalOverlapHours += $slot['overlap']['duration'];
        }

        // Score basé sur le nombre d'heures disponibles en commun
        // Maximum réaliste : 10 heures par semaine = score 100
        return min(($totalOverlapHours / 10) * 100, 100);
    }

    /**
     * Obtient les détails du match
     */
    private function getMatchingDetails(Student $student, Tutor $tutor)
    {
        return [
            'common_subjects' => array_intersect($student->requested_subjects, $tutor->subjects),
            'level_match' => in_array($student->level, $tutor->levels),
            'common_availability' => $tutor->getCommonAvailability($student->availability),
            'budget_compatible' => $student->budget_max ? ($student->budget_max >= $tutor->hourly_rate) : null
        ];
    }

    /**
     * Sauvegarde les matchs pour un élève
     */
    public function saveMatchesForStudent(Student $student)
    {
        // Supprimer les anciens matchs suggérés
        $student->matches()->where('status', 'suggested')->delete();

        $matches = $this->findMatches($student);

        foreach ($matches as $matchData) {
            MatchModel::create([
                'student_id' => $student->id,
                'tutor_id' => $matchData['tutor']->id,
                'compatibility_score' => $matchData['score'],
                'matching_details' => $matchData['details'],
                'status' => 'suggested'
            ]);
        }

        return $matches;
    }

    /**
     * Trouve les matchs pour tous les élèves
     */
    public function generateAllMatches()
    {
        $students = Student::all();
        $results = [];

        foreach ($students as $student) {
            $results[$student->id] = $this->saveMatchesForStudent($student);
        }

        return $results;
    }
}
