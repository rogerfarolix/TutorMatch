<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Student extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'full_name',
        'requested_subjects',
        'level',
        'availability',
        'description',
        'budget_max'
    ];

    protected $casts = [
        'requested_subjects' => 'array',
        'availability' => 'array',
        'budget_max' => 'decimal:2'
    ];

    /**
     * Relation avec les matchs
     */
    public function matches()
    {
        return $this->hasMany(MatchModel::class);
    }

    /**
     * Obtient les tuteurs suggérés pour cet élève
     */
    public function getSuggestedTutors()
    {
        return $this->matches()
                   ->where('status', 'suggested')
                   ->with('tutor')
                   ->orderBy('compatibility_score', 'desc')
                   ->get();
    }

    /**
     * Vérifie si l'élève a besoin d'aide dans une matière donnée
     */
    public function needsSubject($subject)
    {
        return in_array($subject, $this->requested_subjects);
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
}
