@extends('layouts.app')

@section('title', $student->full_name . ' - TutorMatch')
@section('page-title', 'Détails de l\'élève')

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('students.matches', $student) }}" class="btn btn-warning">
            <i class="fas fa-heart me-2"></i>
            Voir les matchs
        </a>
        <a href="{{ route('students.edit', $student) }}" class="btn btn-primary">
            <i class="fas fa-edit me-2"></i>
            Modifier
        </a>
        <a href="{{ route('students.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Retour à la liste
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <!-- Informations principales -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user text-primary me-2"></i>
                    Informations générales
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Nom complet</h6>
                        <p class="text-muted mb-3">{{ $student->full_name }}</p>

                        <h6>Niveau scolaire</h6>
                        <p class="mb-3">
                            <span class="badge bg-info fs-6">{{ $student->level }}</span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6>Budget maximum</h6>
                        <p class="text-muted mb-3">
                            @if($student->budget_max)
                                <strong class="text-success">{{ $student->budget_max }}€/heure</strong>
                            @else
                                <span class="text-muted">Non spécifié</span>
                            @endif
                        </p>
                    </div>
                </div>

                @if($student->description)
                <div class="mt-3">
                    <h6>Description</h6>
                    <p class="text-muted">{{ $student->description }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Matières demandées -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-book text-primary me-2"></i>
                    Matières demandées ({{ count($student->requested_subjects) }})
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($student->requested_subjects as $subject)
                        <div class="col-md-4 col-sm-6 mb-2">
                            <span class="badge bg-primary">{{ $subject }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Disponibilités -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-calendar text-primary me-2"></i>
                    Disponibilités
                </h5>
            </div>
            <div class="card-body">
                @if(count($student->availability) > 0)
                    <div class="row">
                        @foreach($student->availability as $slot)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card border-primary">
                                    <div class="card-body text-center p-3">
                                        <h6 class="card-title text-primary mb-1">{{ $slot['day'] }}</h6>
                                        <p class="card-text mb-0">
                                            <strong>{{ $slot['start_time'] }} - {{ $slot['end_time'] }}</strong>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-calendar-times fa-2x text-muted mb-2"></i>
                        <p class="text-muted">Aucune disponibilité renseignée</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Statistiques des matchs -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar text-primary me-2"></i>
                    Statistiques des matchs
                </h5>
            </div>
            <div class="card-body">
                @php
                    $suggestedMatches = $student->matches()->where('status', 'suggested')->count();
                    $acceptedMatches = $student->matches()->where('status', 'accepted')->count();
                    $totalMatches = $student->matches()->count();
                @endphp

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Matchs suggérés</span>
                    <span class="badge bg-warning fs-6">{{ $suggestedMatches }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Matchs acceptés</span>
                    <span class="badge bg-success fs-6">{{ $acceptedMatches }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <span><strong>Total</strong></span>
                    <span class="badge bg-primary fs-6">{{ $totalMatches }}</span>
                </div>

                @if($suggestedMatches > 0)
                    <div class="mt-3">
                        <a href="{{ route('students.matches', $student) }}" class="btn btn-warning w-100">
                            <i class="fas fa-heart me-2"></i>
                            Voir les matchs suggérés
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-cogs text-primary me-2"></i>
                    Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('students.edit', $student) }}" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-2"></i>
                        Modifier l'élève
                    </a>

                    <a href="{{ route('students.matches', $student) }}" class="btn btn-outline-warning">
                        <i class="fas fa-heart me-2"></i>
                        Gérer les matchs
                    </a>

                    <hr>

                    <form action="{{ route('students.destroy', $student) }}" method="POST"
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet élève ? Cette action est irréversible.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="fas fa-trash me-2"></i>
                            Supprimer l'élève
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
