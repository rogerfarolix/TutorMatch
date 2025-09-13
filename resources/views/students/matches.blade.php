@extends('layouts.app')

@section('title', 'Matchs pour ' . $student->full_name . ' - TutorMatch')
@section('page-title', 'Matchs suggérés')

@section('page-actions')
    <a href="{{ route('students.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>
        Retour aux élèves
    </a>
@endsection

@section('content')
<!-- Informations de l'élève -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-user-graduate text-primary me-2"></i>
            {{ $student->full_name }}
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <strong>Niveau :</strong>
                <br><span class="badge bg-info">{{ $student->level }}</span>
            </div>
            <div class="col-md-4">
                <strong>Matières demandées :</strong>
                <br>
                @foreach($student->requested_subjects as $subject)
                    <span class="badge bg-primary me-1 mb-1">{{ $subject }}</span>
                @endforeach
            </div>
            <div class="col-md-3">
                <strong>Budget maximum :</strong>
                <br>
                @if($student->budget_max)
                    <span class="badge bg-success">{{ $student->budget_max }}€/h</span>
                @else
                    <span class="text-muted">Non spécifié</span>
                @endif
            </div>
            <div class="col-md-2">
                <strong>Disponibilités :</strong>
                <br>{{ count($student->availability) }} créneaux
            </div>
        </div>

        @if($student->description)
            <div class="mt-3">
                <strong>Description :</strong>
                <p class="text-muted mb-0">{{ $student->description }}</p>
            </div>
        @endif
    </div>
</div>

<!-- Liste des matchs -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-heart text-danger me-2"></i>
            Tuteurs Suggérés ({{ $matches->count() }})
        </h5>
        @if($matches->count() > 0)
            <small class="text-muted">Classés par compatibilité</small>
        @endif
    </div>
    <div class="card-body">
        @if($matches->count() > 0)
            <div class="row">
                @foreach($matches as $match)
                <div class="col-lg-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold">{{ $match->tutor->full_name }}</h6>
                                <div>
                                    <span class="badge badge-score
                                        @if($match->compatibility_score >= 80) bg-success
                                        @elseif($match->compatibility_score >= 60) bg-warning
                                        @else bg-danger
                                        @endif">
                                        {{ $match->compatibility_percentage }}
                                    </span>
                                </div>
                            </div>
                            <div class="progress mt-2" style="height: 4px;">
                                <div class="progress-bar
                                    @if($match->compatibility_score >= 80) bg-success
                                    @elseif($match->compatibility_score >= 60) bg-warning
                                    @else bg-danger
                                    @endif"
                                    style="width: {{ $match->compatibility_score }}%">
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Informations du tuteur -->
                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <small class="text-muted">Expérience</small>
                                    <br>
                                    @if($match->tutor->experience_years > 0)
                                        <span class="badge bg-warning">{{ $match->tutor->experience_years }} ans</span>
                                    @else
                                        <span class="text-muted">Débutant</span>
                                    @endif
                                </div>
                                <div class="col-sm-6">
                                    <small class="text-muted">Tarif</small>
                                    <br>
                                    @if($match->tutor->hourly_rate)
                                        <strong class="
                                            @if($student->budget_max && $match->tutor->hourly_rate <= $student->budget_max) text-success
                                            @elseif($student->budget_max) text-danger
                                            @endif">
                                            {{ $match->tutor->hourly_rate }}€/h
                                        </strong>
                                    @else
                                        <span class="text-muted">À négocier</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Matières communes -->
                            <div class="mb-3">
                                <small class="text-muted">Matières communes</small>
                                <br>
                                @foreach($match->matching_details['common_subjects'] as $subject)
                                    <span class="badge bg-primary me-1 mb-1">{{ $subject }}</span>
                                @endforeach
                            </div>

                            <!-- Créneaux communs -->
                            @if(count($match->matching_details['common_availability']) > 0)
                                <div class="mb-3">
                                    <small class="text-muted">Créneaux communs</small>
                                    <br>
                                    @foreach(array_slice($match->matching_details['common_availability'], 0, 3) as $slot)
                                        <small class="d-block">
                                            <i class="fas fa-clock text-success me-1"></i>
                                            {{ $slot['overlap']['day'] }}
                                            {{ $slot['overlap']['start_time'] }}-{{ $slot['overlap']['end_time'] }}
                                            <span class="text-muted">({{ round($slot['overlap']['duration'], 1) }}h)</span>
                                        </small>
                                    @endforeach
                                    @if(count($match->matching_details['common_availability']) > 3)
                                        <small class="text-primary">
                                            +{{ count($match->matching_details['common_availability']) - 3 }} autres créneaux
                                        </small>
                                    @endif
                                </div>
                            @endif

                            <!-- Description du tuteur -->
                            @if($match->tutor->description)
                                <div class="mb-3">
                                    <small class="text-muted">À propos</small>
                                    <p class="small mb-0">{{ Str::limit($match->tutor->description, 100) }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('tutors.show', $match->tutor) }}" class="btn btn-outline-info btn-sm">
                                    <i class="fas fa-eye me-1"></i>
                                    Voir profil
                                </a>
                                <div class="btn-group btn-group-sm">
                                    <form action="{{ route('matches.accept', $match) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm"
                                                onclick="return confirm('Accepter ce match ?')">
                                            <i class="fas fa-check me-1"></i>
                                            Accepter
                                        </button>
                                    </form>
                                    <form action="{{ route('matches.reject', $match) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                                onclick="return confirm('Rejeter ce match ?')">
                                            <i class="fas fa-times me-1"></i>
                                            Rejeter
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-search fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">Aucun match trouvé</h5>
                <p class="text-muted">
                    Aucun tuteur ne correspond actuellement aux critères de cet élève.
                    <br>Essayez d'ajouter plus de tuteurs ou de modifier les critères de l'élève.
                </p>
                <div class="mt-4">
                    <a href="{{ route('tutors.create') }}" class="btn btn-primary me-2">
                        <i class="fas fa-plus me-2"></i>
                        Ajouter un tuteur
                    </a>
                    <a href="{{ route('students.edit', $student) }}" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-2"></i>
                        Modifier l'élève
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
