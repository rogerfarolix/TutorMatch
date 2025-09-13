@extends('layouts.app')

@section('title', 'Dashboard - TutorMatch')
@section('page-title', 'Dashboard')

@section('page-actions')
    <form action="{{ route('matches.generate-all') }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-sync-alt me-2"></i>
            Générer tous les matchs
        </button>
    </form>
@endsection

@section('content')
<!-- Statistiques -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Élèves
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_students'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-graduate fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Tuteurs
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_tutors'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chalkboard-teacher fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Matchs totaux
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_matches'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-heart fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Matchs acceptés
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['accepted_matches'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Meilleurs Matchs -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-star text-warning me-2"></i>
                    Meilleurs Matchs Suggérés
                </h5>
            </div>
            <div class="card-body">
                @if($best_matches->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Élève</th>
                                    <th>Tuteur</th>
                                    <th>Compatibilité</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($best_matches as $match)
                                <tr>
                                    <td>
                                        <strong>{{ $match->student->full_name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $match->student->level }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $match->tutor->full_name }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            {{ implode(', ', array_slice($match->tutor->subjects, 0, 2)) }}
                                            @if(count($match->tutor->subjects) > 2)
                                                <span class="text-primary">+{{ count($match->tutor->subjects) - 2 }}</span>
                                            @endif
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge badge-score
                                            @if($match->compatibility_score >= 80) bg-success
                                            @elseif($match->compatibility_score >= 60) bg-warning
                                            @else bg-danger
                                            @endif">
                                            {{ $match->compatibility_percentage }}
                                        </span>
                                        <div class="progress mt-1" style="height: 4px;">
                                            <div class="progress-bar
                                                @if($match->compatibility_score >= 80) bg-success
                                                @elseif($match->compatibility_score >= 60) bg-warning
                                                @else bg-danger
                                                @endif"
                                                style="width: {{ $match->compatibility_score }}%">
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('matches.show', $match) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Aucun match trouvé. Ajoutez des tuteurs et des élèves pour commencer.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Activité Récente -->
    <div class="col-lg-4">
        <!-- Derniers Élèves -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-user-graduate text-primary me-2"></i>
                    Derniers Élèves
                </h6>
            </div>
            <div class="card-body">
                @forelse($recent_students as $student)
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $student->full_name }}</h6>
                            <small class="text-muted">{{ $student->level }}</small>
                        </div>
                        <small class="text-muted">{{ $student->created_at->diffForHumans() }}</small>
                    </div>
                @empty
                    <p class="text-muted text-center">Aucun élève enregistré</p>
                @endforelse

                <div class="text-center">
                    <a href="{{ route('students.index') }}" class="btn btn-sm btn-outline-primary">
                        Voir tous les élèves
                    </a>
                </div>
            </div>
        </div>

        <!-- Derniers Tuteurs -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-chalkboard-teacher text-success me-2"></i>
                    Derniers Tuteurs
                </h6>
            </div>
            <div class="card-body">
                @forelse($recent_tutors as $tutor)
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-sm bg-success rounded-circle d-flex align-items-center justify-content-center me-3">
                            <i class="fas fa-chalkboard-teacher text-white"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $tutor->full_name }}</h6>
                            <small class="text-muted">{{ $tutor->experience_years }} ans d'exp.</small>
                        </div>
                        <small class="text-muted">{{ $tutor->created_at->diffForHumans() }}</small>
                    </div>
                @empty
                    <p class="text-muted text-center">Aucun tuteur enregistré</p>
                @endforelse

                <div class="text-center">
                    <a href="{{ route('tutors.index') }}" class="btn btn-sm btn-outline-success">
                        Voir tous les tuteurs
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
.border-left-primary {
    border-left: 4px solid #007bff !important;
}
.border-left-success {
    border-left: 4px solid #28a745 !important;
}
.border-left-info {
    border-left: 4px solid #17a2b8 !important;
}
.border-left-warning {
    border-left: 4px solid #ffc107 !important;
}
.avatar-sm {
    width: 40px;
    height: 40px;
}
</style>
@endsection
