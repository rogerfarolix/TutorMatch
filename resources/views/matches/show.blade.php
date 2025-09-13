@extends('layouts.app')

@section('title', 'Détails du Match - TutorMatch')
@section('page-title', 'Détails du Match')

@section('page-actions')
    <a href="{{ route('matches.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>
        Retour à la liste
    </a>

    @if($match->status == 'suggested')
        <form action="{{ route('matches.accept', $match) }}" method="POST" class="d-inline ms-2">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-success"
                    onclick="return confirm('Accepter ce match ?')">
                <i class="fas fa-check me-2"></i>
                Accepter le match
            </button>
        </form>

        <form action="{{ route('matches.reject', $match) }}" method="POST" class="d-inline ms-2">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-danger"
                    onclick="return confirm('Rejeter ce match ?')">
                <i class="fas fa-times me-2"></i>
                Rejeter le match
            </button>
        </form>
    @endif
@endsection

@section('content')
@php
    // Student fields - Vérification et conversion sécurisée
    $studentSubjects = [];
    if (!empty($match->student->requested_subjects)) {
        if (is_array($match->student->requested_subjects)) {
            $studentSubjects = $match->student->requested_subjects;
        } elseif (is_string($match->student->requested_subjects)) {
            $decoded = json_decode($match->student->requested_subjects, true);
            $studentSubjects = is_array($decoded) ? $decoded : [];
        }
    }

    $studentAvailability = [];
    if (!empty($match->student->availability)) {
        if (is_array($match->student->availability)) {
            $studentAvailability = $match->student->availability;
        } elseif (is_string($match->student->availability)) {
            $decoded = json_decode($match->student->availability, true);
            $studentAvailability = is_array($decoded) ? $decoded : [];
        }
    }

    // Tutor fields - Vérification et conversion sécurisée
    $tutorSubjects = [];
    if (!empty($match->tutor->subjects)) {
        if (is_array($match->tutor->subjects)) {
            $tutorSubjects = $match->tutor->subjects;
        } elseif (is_string($match->tutor->subjects)) {
            $decoded = json_decode($match->tutor->subjects, true);
            $tutorSubjects = is_array($decoded) ? $decoded : [];
        }
    }

    $tutorAvailability = [];
    if (!empty($match->tutor->availability)) {
        if (is_array($match->tutor->availability)) {
            $tutorAvailability = $match->tutor->availability;
        } elseif (is_string($match->tutor->availability)) {
            $decoded = json_decode($match->tutor->availability, true);
            $tutorAvailability = is_array($decoded) ? $decoded : [];
        }
    }

    // Matching details - Vérification et conversion sécurisée
    $matchingDetails = [];
    if (!empty($match->matching_details)) {
        if (is_array($match->matching_details)) {
            $matchingDetails = $match->matching_details;
        } elseif (is_string($match->matching_details)) {
            $decoded = json_decode($match->matching_details, true);
            $matchingDetails = is_array($decoded) ? $decoded : [];
        }
    }

    // Calcul du pourcentage de compatibilité
    $compatibilityPercentage = $match->compatibility_score ?? 0;
    $compatibilityPercentage = is_numeric($compatibilityPercentage) ? round($compatibilityPercentage) : 0;
@endphp

<!-- Statut et compatibilité -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">
                            <i class="fas fa-heart text-danger me-2"></i>
                            Match {{ $match->student->full_name ?? 'Élève' }} ↔ {{ $match->tutor->full_name ?? 'Tuteur' }}
                        </h4>
                        <p class="text-muted mb-0">
                            Créé le {{ $match->created_at->format('d/m/Y à H:i') }}
                            ({{ $match->created_at->diffForHumans() }})
                        </p>
                    </div>
                    <div class="text-end">
                        @switch($match->status)
                            @case('suggested')
                                <span class="badge bg-warning fs-6 p-2">
                                    <i class="fas fa-clock me-1"></i> Suggéré
                                </span>
                                @break
                            @case('accepted')
                                <span class="badge bg-success fs-6 p-2">
                                    <i class="fas fa-check me-1"></i> Accepté
                                </span>
                                @break
                            @case('rejected')
                                <span class="badge bg-danger fs-6 p-2">
                                    <i class="fas fa-times me-1"></i> Rejeté
                                </span>
                                @break
                        @endswitch
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <h5 class="card-title">Score de compatibilité</h5>
                <div class="mb-3">
                    <span class="display-4 fw-bold
                        @if($compatibilityPercentage >= 80) text-success
                        @elseif($compatibilityPercentage >= 60) text-warning
                        @else text-danger
                        @endif">
                        {{ $compatibilityPercentage }}%
                    </span>
                </div>
                <div class="progress" style="height: 10px;">
                    <div class="progress-bar
                        @if($compatibilityPercentage >= 80) bg-success
                        @elseif($compatibilityPercentage >= 60) bg-warning
                        @else bg-danger
                        @endif"
                        style="width: {{ $compatibilityPercentage }}%">
                    </div>
                </div>
                <small class="text-muted mt-2 d-block">
                    @if($compatibilityPercentage >= 80)
                        Excellente compatibilité
                    @elseif($compatibilityPercentage >= 60)
                        Bonne compatibilité
                    @else
                        Compatibilité faible
                    @endif
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Profils des utilisateurs -->
<div class="row mb-4">
    <!-- Profil de l'élève -->
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-user-graduate me-2"></i>
                    Élève
                </h5>
            </div>
            <div class="card-body">
                <h6 class="fw-bold">{{ $match->student->full_name ?? 'Nom non disponible' }}</h6>

                @if(!empty($match->student->level))
                    <div class="mb-3">
                        <strong>Niveau :</strong>
                        <span class="badge bg-info ms-1">{{ $match->student->level }}</span>
                    </div>
                @endif

                <div class="mb-3">
                    <strong>Matières demandées :</strong>
                    <div class="mt-1">
                        @if(!empty($studentSubjects))
                            @foreach($studentSubjects as $subject)
                                <span class="badge bg-primary me-1 mb-1">{{ $subject }}</span>
                            @endforeach
                        @else
                            <span class="text-muted small">Aucune matière spécifiée</span>
                        @endif
                    </div>
                </div>

                @if(!empty($match->student->goals))
                    <div class="mb-3">
                        <strong>Objectifs :</strong>
                        <p class="small text-muted mb-0">{{ $match->student->goals }}</p>
                    </div>
                @endif

                @if(!empty($studentAvailability))
                    <div class="mb-3">
                        <strong>Disponibilités :</strong>
                        <div class="mt-1">
                            @foreach($studentAvailability as $day => $slots)
                                @if(!empty($slots) && is_array($slots))
                                    <div class="small">
                                        <strong>{{ ucfirst($day) }} :</strong>
                                        {{ implode(', ', $slots) }}
                                    </div>
                                @elseif(!empty($slots) && is_string($slots))
                                    <div class="small">
                                        <strong>{{ ucfirst($day) }} :</strong>
                                        {{ $slots }}
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Profil du tuteur -->
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-chalkboard-teacher me-2"></i>
                    Tuteur
                </h5>
            </div>
            <div class="card-body">
                <h6 class="fw-bold">{{ $match->tutor->full_name ?? 'Nom non disponible' }}</h6>

                <div class="mb-3">
                    <strong>Expérience :</strong>
                    @if(!empty($match->tutor->experience_years) && $match->tutor->experience_years > 0)
                        {{ $match->tutor->experience_years }} ans
                    @else
                        Débutant
                    @endif

                    @if(!empty($match->tutor->hourly_rate))
                        <br><strong>Tarif :</strong> {{ $match->tutor->hourly_rate }}€/h
                    @endif
                </div>

                <div class="mb-3">
                    <strong>Matières enseignées :</strong>
                    <div class="mt-1">
                        @if(!empty($tutorSubjects))
                            @foreach($tutorSubjects as $subject)
                                <span class="badge bg-success me-1 mb-1">{{ $subject }}</span>
                            @endforeach
                        @else
                            <span class="text-muted small">Aucune matière spécifiée</span>
                        @endif
                    </div>
                </div>

                @if(!empty($match->tutor->bio))
                    <div class="mb-3">
                        <strong>Présentation :</strong>
                        <p class="small text-muted mb-0">{{ $match->tutor->bio }}</p>
                    </div>
                @endif

                @if(!empty($tutorAvailability))
                    <div class="mb-3">
                        <strong>Disponibilités :</strong>
                        <div class="mt-1">
                            @foreach($tutorAvailability as $day => $slots)
                                @if(!empty($slots) && is_array($slots))
                                    <div class="small">
                                        <strong>{{ ucfirst($day) }} :</strong>
                                        {{ implode(', ', $slots) }}
                                    </div>
                                @elseif(!empty($slots) && is_string($slots))
                                    <div class="small">
                                        <strong>{{ ucfirst($day) }} :</strong>
                                        {{ $slots }}
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Détails de compatibilité -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-puzzle-piece me-2"></i>
            Analyse de compatibilité
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Matières communes -->
            <div class="col-md-4 mb-3">
                <div class="border rounded p-3 h-100">
                    <h6 class="fw-bold text-primary">
                        <i class="fas fa-books me-2"></i>
                        Matières communes
                    </h6>
                    @php
                        $commonSubjects = $matchingDetails['common_subjects'] ?? [];
                        $commonSubjects = is_array($commonSubjects) ? $commonSubjects : [];
                    @endphp
                    @if(count($commonSubjects) > 0)
                        <div class="mt-2">
                            @foreach($commonSubjects as $subject)
                                <span class="badge bg-primary me-1 mb-1">{{ $subject }}</span>
                            @endforeach
                        </div>
                        <div class="mt-2">
                            <small class="text-success">
                                <i class="fas fa-check me-1"></i>
                                {{ count($commonSubjects) }} matière(s) en commun
                            </small>
                        </div>
                    @else
                        <div class="mt-2">
                            <small class="text-danger">
                                <i class="fas fa-times me-1"></i>
                                Aucune matière commune
                            </small>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Compatibilité de niveau -->
            <div class="col-md-4 mb-3">
                <div class="border rounded p-3 h-100">
                    <h6 class="fw-bold text-warning">
                        <i class="fas fa-layer-group me-2"></i>
                        Compatibilité de niveau
                    </h6>
                    <div class="mt-2">
                        @php $levelMatch = $matchingDetails['level_match'] ?? false; @endphp
                        @if($levelMatch)
                            <span class="badge bg-success mb-2">Compatible</span>
                            <div>
                                <small class="text-success">
                                    <i class="fas fa-check me-1"></i>
                                    Le tuteur peut enseigner à ce niveau
                                </small>
                            </div>
                        @else
                            <span class="badge bg-danger mb-2">Incompatible</span>
                            <div>
                                <small class="text-danger">
                                    <i class="fas fa-times me-1"></i>
                                    Niveau non adapté
                                </small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Créneaux communs -->
            <div class="col-md-4 mb-3">
                <div class="border rounded p-3 h-100">
                    <h6 class="fw-bold text-info">
                        <i class="fas fa-clock me-2"></i>
                        Créneaux disponibles
                    </h6>
                    @php
                        $commonAvailability = $matchingDetails['common_availability'] ?? [];
                        $commonAvailability = is_array($commonAvailability) ? $commonAvailability : [];
                    @endphp
                    @if(count($commonAvailability) > 0)
                        <span class="badge bg-success mb-2">
                            {{ count($commonAvailability) }} créneau(x) commun(s)
                        </span>
                        <div class="mt-2">
                            @foreach($commonAvailability as $availability)
                                <div class="small mb-1">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    @if(is_array($availability))
                                        @if(isset($availability['day']) && isset($availability['start_time']) && isset($availability['end_time']))
                                            {{ ucfirst($availability['day']) }} : {{ $availability['start_time'] }} - {{ $availability['end_time'] }}
                                        @elseif(isset($availability['overlap']))
                                            {{ ucfirst($availability['overlap']['day']) }} : {{ $availability['overlap']['start_time'] }} - {{ $availability['overlap']['end_time'] }}
                                        @else
                                            {{ implode(' ', array_filter($availability)) }}
                                        @endif
                                    @elseif(is_string($availability))
                                        {{ $availability }}
                                    @else
                                        Créneau disponible
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <span class="badge bg-danger mb-2">Aucun créneau commun</span>
                        <div>
                            <small class="text-danger">
                                <i class="fas fa-times me-1"></i>
                                Pas de disponibilités communes
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if(!empty($matchingDetails['score_breakdown']) && is_array($matchingDetails['score_breakdown']))
            <div class="mt-4 pt-3 border-top">
                <h6 class="fw-bold mb-3">Détail du score</h6>
                <div class="row">
                    @foreach($matchingDetails['score_breakdown'] as $criterion => $score)
                        <div class="col-md-3 mb-2">
                            <div class="d-flex justify-content-between">
                                <small>{{ ucfirst(str_replace('_', ' ', $criterion)) }}</small>
                                <small class="fw-bold">{{ is_numeric($score) ? round($score) : 0 }}%</small>
                            </div>
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar bg-info" style="width: {{ is_numeric($score) ? $score : 0 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<style>
.display-4 {
    font-size: 2.5rem;
}

.border {
    border: 1px solid #dee2e6 !important;
}

.card-header.bg-primary {
    background-color: #0d6efd !important;
}

.card-header.bg-success {
    background-color: #198754 !important;
}

.progress {
    background-color: #e9ecef;
}

.badge {
    font-size: 0.75em;
}
</style>
@endsection
