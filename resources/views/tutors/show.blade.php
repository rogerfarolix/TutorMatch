@extends('layouts.app')

@section('title', 'Détails du tuteur - TutorMatch')
@section('page-title', 'Détails du Tuteur')

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('tutors.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Retour à la liste
        </a>
        <a href="{{ route('tutors.edit', $tutor) }}" class="btn btn-primary">
            <i class="fas fa-edit me-2"></i>
            Modifier
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user text-primary me-2"></i>
                    {{ $tutor->full_name }}
                </h5>
            </div>
            <div class="card-body">
                @if($tutor->description)
                    <div class="mb-4">
                        <h6><i class="fas fa-info-circle me-2"></i>Description</h6>
                        <p class="text-muted">{{ $tutor->description }}</p>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <h6><i class="fas fa-book me-2"></i>Matières enseignées</h6>
                        <div>
                            @foreach($tutor->subjects as $subject)
                                <span class="badge bg-primary me-1 mb-1">{{ $subject }}</span>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <h6><i class="fas fa-graduation-cap me-2"></i>Niveaux</h6>
                        <div>
                            @foreach($tutor->levels as $level)
                                <span class="badge bg-info me-1 mb-1">{{ $level }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <h6><i class="fas fa-clock me-2"></i>Expérience</h6>
                        @if($tutor->experience_years > 0)
                            <span class="badge bg-warning">{{ $tutor->experience_years }} année(s)</span>
                        @else
                            <span class="text-muted">Débutant</span>
                        @endif
                    </div>

                    <div class="col-md-6 mb-4">
                        <h6><i class="fas fa-euro-sign me-2"></i>Tarif horaire</h6>
                        @if($tutor->hourly_rate)
                            <strong class="text-success">{{ $tutor->hourly_rate }}€/h</strong>
                        @else
                            <span class="text-muted">Non spécifié</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Disponibilités
                </h6>
            </div>
            <div class="card-body">
                @if(count($tutor->availability) > 0)
                    @foreach($tutor->availability as $slot)
                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                            <strong>{{ $slot['day'] }}</strong>
                            <span class="badge bg-success">{{ $slot['start_time'] }} - {{ $slot['end_time'] }}</span>
                        </div>
                    @endforeach
                @else
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-calendar-times fa-2x mb-2"></i>
                        <p>Aucune disponibilité définie</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Actions -->
<div class="card mt-4">
    <div class="card-body text-center">
        <div class="btn-group">
            <a href="{{ route('tutors.edit', $tutor) }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>
                Modifier ce tuteur
            </a>
            <form action="{{ route('tutors.destroy', $tutor) }}" method="POST" class="d-inline"
                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce tuteur ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash me-2"></i>
                    Supprimer
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
