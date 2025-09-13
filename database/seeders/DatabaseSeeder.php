<?php

namespace Database\Seeders;

use App\Models\MatchModel;
use App\Models\Tutor;
use App\Models\Student;
use Illuminate\Database\Seeder;
use App\Services\MatchmakingService;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
        ]);

        // Création des tuteurs
        $tutors = [
            [
                'id' => (string) Str::uuid(),
                'full_name' => 'Ahmed Ben Ali',
                'subjects' => ['Mathématiques', 'Physique'],
                'levels' => ['Lycée', 'Terminale'],
                'availability' => [
                    ['day' => 'Lundi', 'start_time' => '18:00', 'end_time' => '20:00'],
                    ['day' => 'Mercredi', 'start_time' => '16:00', 'end_time' => '20:00'],
                    ['day' => 'Samedi', 'start_time' => '10:00', 'end_time' => '19:00']
                ],
                'description' => 'Professeur de mathématiques expérimenté, spécialisé dans la préparation au baccalauréat.',
                'hourly_rate' => 25.00,
                'experience_years' => 8
            ],
            [
                'id' => (string) Str::uuid(),
                'full_name' => 'Sarah Dubois',
                'subjects' => ['Physique', 'Chimie'],
                'levels' => ['Collège', 'Lycée'],
                'availability' => [
                    ['day' => 'Mercredi', 'start_time' => '14:00', 'end_time' => '16:00'],
                    ['day' => 'Samedi', 'start_time' => '10:00', 'end_time' => '22:00'],
                    ['day' => 'Dimanche', 'start_time' => '14:00', 'end_time' => '18:00']
                ],
                'description' => 'Étudiante en école d\'ingénieur, passionnée par les sciences physiques.',
                'hourly_rate' => 20.00,
                'experience_years' => 3
            ],
            [
                'id' => (string) Str::uuid(),
                'full_name' => 'Karim Meziane',
                'subjects' => ['Français', 'Philosophie'],
                'levels' => ['Terminale', 'Supérieur'],
                'availability' => [
                    ['day' => 'Lundi', 'start_time' => '18:00', 'end_time' => '20:00'],
                    ['day' => 'Mardi', 'start_time' => '17:00', 'end_time' => '19:00'],
                    ['day' => 'Jeudi', 'start_time' => '18:00', 'end_time' => '21:00']
                ],
                'description' => 'Professeur agrégé de lettres modernes, spécialisé dans la dissertation.',
                'hourly_rate' => 30.00,
                'experience_years' => 12
            ],
            [
                'id' => (string) Str::uuid(),
                'full_name' => 'Marie Leroy',
                'subjects' => ['Mathématiques', 'SVT'],
                'levels' => ['Collège', 'Lycée'],
                'availability' => [
                    ['day' => 'Mercredi', 'start_time' => '13:00', 'end_time' => '17:00'],
                    ['day' => 'Samedi', 'start_time' => '09:00', 'end_time' => '12:00'],
                    ['day' => 'Dimanche', 'start_time' => '15:00', 'end_time' => '18:00']
                ],
                'description' => 'Étudiante en médecine, excellente pédagogue pour les matières scientifiques.',
                'hourly_rate' => 18.00,
                'experience_years' => 2
            ],
            [
                'id' => (string) Str::uuid(),
                'full_name' => 'Julien Martin',
                'subjects' => ['Anglais', 'Histoire'],
                'levels' => ['Collège', 'Lycée', 'Terminale'],
                'availability' => [
                    ['day' => 'Mardi', 'start_time' => '16:00', 'end_time' => '19:00'],
                    ['day' => 'Jeudi', 'start_time' => '16:00', 'end_time' => '19:00'],
                    ['day' => 'Vendredi', 'start_time' => '17:00', 'end_time' => '20:00']
                ],
                'description' => 'Professeur d\'anglais bilingue, expérience internationale.',
                'hourly_rate' => 22.00,
                'experience_years' => 6
            ]
        ];

        foreach ($tutors as $tutorData) {
            Tutor::create($tutorData);
        }

        // Création des élèves
        $students = [
            [
                'id' => (string) Str::uuid(),
                'full_name' => 'Ali Mansouri',
                'requested_subjects' => ['Mathématiques'],
                'level' => 'Lycée',
                'availability' => [
                    ['day' => 'Lundi', 'start_time' => '18:00', 'end_time' => '20:00'],
                    ['day' => 'Samedi', 'start_time' => '14:00', 'end_time' => '16:00']
                ],
                'description' => 'Élève de 1ère S ayant besoin d\'aide en mathématiques pour améliorer ses notes.',
                'budget_max' => 25.00
            ],
            [
                'id' => (string) Str::uuid(),
                'full_name' => 'Yasmine Benali',
                'requested_subjects' => ['Physique'],
                'level' => 'Collège',
                'availability' => [
                    ['day' => 'Mercredi', 'start_time' => '14:00', 'end_time' => '16:00'],
                    ['day' => 'Samedi', 'start_time' => '10:00', 'end_time' => '12:00']
                ],
                'description' => 'Élève de 3ème qui souhaite se perfectionner en physique.',
                'budget_max' => 20.00
            ],
            [
                'id' => (string) Str::uuid(),
                'full_name' => 'Lucas Durand',
                'requested_subjects' => ['Français', 'Philosophie'],
                'level' => 'Terminale',
                'availability' => [
                    ['day' => 'Lundi', 'start_time' => '18:00', 'end_time' => '20:00'],
                    ['day' => 'Jeudi', 'start_time' => '18:00', 'end_time' => '20:00']
                ],
                'description' => 'Élève de Terminale L préparant le baccalauréat, difficultés en dissertation.',
                'budget_max' => 35.00
            ],
            [
                'id' => (string) Str::uuid(),
                'full_name' => 'Emma Rousseau',
                'requested_subjects' => ['Mathématiques', 'Physique'],
                'level' => 'Lycée',
                'availability' => [
                    ['day' => 'Mercredi', 'start_time' => '14:00', 'end_time' => '18:00'],
                    ['day' => 'Samedi', 'start_time' => '10:00', 'end_time' => '15:00']
                ],
                'description' => 'Élève de 1ère S motivée, souhaite exceller en sciences.',
                'budget_max' => 28.00
            ],
            [
                'id' => (string) Str::uuid(),
                'full_name' => 'Maxime Lefebvre',
                'requested_subjects' => ['Anglais'],
                'level' => 'Collège',
                'availability' => [
                    ['day' => 'Mardi', 'start_time' => '17:00', 'end_time' => '19:00'],
                    ['day' => 'Jeudi', 'start_time' => '17:00', 'end_time' => '19:00']
                ],
                'description' => 'Élève de 4ème ayant des lacunes importantes en anglais.',
                'budget_max' => 22.00
            ],
            [
                'id' => (string) Str::uuid(),
                'full_name' => 'Léa Moreau',
                'requested_subjects' => ['SVT', 'Mathématiques'],
                'level' => 'Lycée',
                'availability' => [
                    ['day' => 'Mercredi', 'start_time' => '15:00', 'end_time' => '17:00'],
                    ['day' => 'Samedi', 'start_time' => '09:00', 'end_time' => '11:00']
                ],
                'description' => 'Élève de 2nde intéressée par la médecine, souhaite consolider ses bases scientifiques.',
                'budget_max' => 20.00
            ]
        ];

        foreach ($students as $studentData) {
            Student::create($studentData);
        }

        // Génération automatique des matchs
        $matchmakingService = app(MatchmakingService::class);
        $matchmakingService->generateAllMatches();

        echo "Base de données peuplée avec succès !\n";
        echo "- " . Tutor::count() . " tuteurs créés\n";
        echo "- " . Student::count() . " élèves créés\n";
        echo "- " . MatchModel::count() . " matchs générés\n";
    }
}
