@extends('layouts.app')

@section('title', 'Matchs - TutorMatch')
@section('page-title', 'Gestion des Matchs')

@section('page-actions')
    <form action="{{ route('matches.generate-all') }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-primary"
                onclick="return confirm('Générer tous les matchs ? Cette action va recalculer tous les matchs existants.')">
            <i class="fas fa-sync-alt me-2"></i>
            Générer tous les matchs
        </button>
    </form>
@endsection

@section('content')
<!-- Filtres -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('matches.index') }}" class="row g-3">
            <div class="col-md-4">
                <label for="status" class="form-label">Statut</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Tous les statuts</option>
                    <option value="suggested" {{ request('status') == 'suggested' ? 'selected' : '' }}>Suggérés</option>
                    <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Acceptés</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejetés</option>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-outline-primary">
                    <i class="fas fa-filter me-2"></i>
                    Filtrer
                </button>
                @if(request()->hasAny(['status']))
                    <a href="{{ route('matches.index') }}" class="btn btn-outline-secondary ms-2">
                        <i class="fas fa-times me-2"></i>
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Liste des matchs -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-heart text-danger me-2"></i>
            Liste des Matchs ({{ $matches->total() }})
        </h5>
    </div>
    <div class="card-body">
        @if($matches->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Élève</th>
                            <th>Tuteur</th>
                            <th>Compatibilité</th>
                            <th>Détails du match</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th width="120">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($matches as $match)
                        <tr>
                            <td>
                                <strong>{{ $match->student->full_name }}</strong>
                                <br>
                                <small class="text-muted">{{ $match->student->level }}</small>
                                <br>
                                @foreach(array_slice($match->student->requested_subjects, 0, 2) as $subject)
                                    <span class="badge bg-primary badge-sm">{{ $subject }}</span>
                                @endforeach
                                @if(count($match->student->requested_subjects) > 2)
                                    <span class="badge bg-secondary badge-sm">+{{ count($match->student->requested_subjects) - 2 }}</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $match->tutor->full_name }}</strong>
                                <br>
                                <small class="text-muted">
                                    @if($match->tutor->experience_years > 0)
                                        {{ $match->tutor->experience_years }} ans d'exp.
                                    @else
                                        Débutant
                                    @endif
                                    @if($match->tutor->hourly_rate)
                                        • {{ $match->tutor->hourly_rate }}€/h
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
                                <small>
                                    <strong>Matières communes :</strong>
                                    @if(count($match->matching_details['common_subjects']) > 0)
                                        {{ implode(', ', array_slice($match->matching_details['common_subjects'], 0, 2)) }}
                                        @if(count($match->matching_details['common_subjects']) > 2)
                                            <span class="text-primary">+{{ count($match->matching_details['common_subjects']) - 2 }}</span>
                                        @endif
                                    @endif
                                    <br>
                                    <strong>Niveau :</strong>
                                    @if($match->matching_details['level_match'])
                                        <span class="text-success"><i class="fas fa-check"></i> Compatible</span>
                                    @else
                                        <span class="text-danger"><i class="fas fa-times"></i> Incompatible</span>
                                    @endif
                                    <br>
                                    <strong>Créneaux :</strong>
                                    {{ count($match->matching_details['common_availability']) }} en commun
                                </small>
                            </td>
                            <td>
                                @switch($match->status)
                                    @case('suggested')
                                        <span class="badge bg-warning">Suggéré</span>
                                        @break
                                    @case('accepted')
                                        <span class="badge bg-success">Accepté</span>
                                        @break
                                    @case('rejected')
                                        <span class="badge bg-danger">Rejeté</span>
                                        @break
                                @endswitch
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $match->created_at->format('d/m/Y') }}
                                    <br>
                                    {{ $match->created_at->diffForHumans() }}
                                </small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('matches.show', $match) }}" class="btn btn-outline-info" title="Voir détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($match->status == 'suggested')
                                        <div class="btn-group btn-group-sm">
                                            <form action="{{ route('matches.accept', $match) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-outline-success" title="Accepter"
                                                        onclick="return confirm('Accepter ce match ?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('matches.reject', $match) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-outline-danger" title="Rejeter"
                                                        onclick="return confirm('Rejeter ce match ?')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $matches->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-heart fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">Aucun match trouvé</h5>
                <p class="text-muted">
                    @if(request('status'))
                        Aucun match avec le statut "{{ request('status') }}" trouvé.
                    @else
                        Aucun match n'a été généré. Ajoutez des tuteurs et des élèves puis générez les matchs.
                    @endif
                </p>
                <div class="mt-4">
                    @if(!request('status'))
                        <form action="{{ route('matches.generate-all') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-sync-alt me-2"></i>
                                Générer tous les matchs
                            </button>
                        </form>
                    @else
                        <a href="{{ route('matches.index') }}" class="btn btn-primary">
                            <i class="fas fa-eye me-2"></i>
                            Voir tous les matchs
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

@endsection

@section('scripts')
<style>
.badge-sm {
    font-size: 0.7em;
}
</style>
@endsection
