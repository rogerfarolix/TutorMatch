<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Tutor extends Model
{
    use HasFactory;


    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'full_name',
        'subjects',
        'levels',
        'availability',
        'description',
        'hourly_rate',
        'experience_years'
    ];

    protected $casts = [
        'subjects' => 'array',
        'levels' => 'array',
        'availability' => 'array',
        'hourly_rate' => 'decimal:2'
    ];

    /**
     * Relation avec les matchs
     */
    public function matches()
    {
        return $this->hasMany(MatchModel::class);
    }

    /**
     * Vérifie si le tuteur enseigne une matière donnée
     */
    public function teachesSubject($subject)
    {
        return in_array($subject, $this->subjects);
    }

    /**
     * Vérifie si le tuteur enseigne à un niveau donné
     */
    public function teachesLevel($level)
    {
        return in_array($level, $this->levels);
    }

    /**
     * Trouve les créneaux communs avec un élève
     */
    public function getCommonAvailability($studentAvailability)
    {
        $commonSlots = [];

        foreach ($this->availability as $tutorSlot) {
            foreach ($studentAvailability as $studentSlot) {
                if ($this->slotsOverlap($tutorSlot, $studentSlot)) {
                    $commonSlots[] = [
                        'tutor_slot' => $tutorSlot,
                        'student_slot' => $studentSlot,
                        'overlap' => $this->calculateOverlap($tutorSlot, $studentSlot)
                    ];
                }
            }
        }

        return $commonSlots;
    }

    protected static function boot()
{
    parent::boot();

    static::creating(function ($model) {
        if (empty($model->{$model->getKeyName()})) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        }
    });
}

    /**
     * Vérifie si deux créneaux se chevauchent
     */
    private function slotsOverlap($slot1, $slot2)
    {
        return $slot1['day'] === $slot2['day'] &&
               $slot1['start_time'] < $slot2['end_time'] &&
               $slot1['end_time'] > $slot2['start_time'];
    }

    /**
     * Calcule le temps de chevauchement entre deux créneaux
     */
    private function calculateOverlap($slot1, $slot2)
    {
        $start = max($slot1['start_time'], $slot2['start_time']);
        $end = min($slot1['end_time'], $slot2['end_time']);

        return [
            'day' => $slot1['day'],
            'start_time' => $start,
            'end_time' => $end,
            'duration' => (strtotime($end) - strtotime($start)) / 3600 // en heures
        ];
    }
}
