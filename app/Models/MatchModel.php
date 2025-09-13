<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class MatchModel extends Model
{
    use HasFactory;

protected $table = 'matches';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'student_id',
        'tutor_id',
        'compatibility_score',
        'matching_details',
        'status'
    ];

    protected $casts = [
        'matching_details' => 'array',
        'compatibility_score' => 'decimal:2'
    ];

    /**
     * Relation avec l'élève
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Relation avec le tuteur
     */
    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    /**
     * Scope pour les matchs suggérés
     */
    public function scopeSuggested($query)
    {
        return $query->where('status', 'suggested');
    }

    /**
     * Scope pour les matchs acceptés
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }


    /**
     * Obtient le score de compatibilité en pourcentage
     */
    public function getCompatibilityPercentageAttribute()
    {
        return round($this->compatibility_score, 1) . '%';
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
