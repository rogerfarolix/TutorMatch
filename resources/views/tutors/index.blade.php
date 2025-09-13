@extends('layouts.app')

@section('title', 'Tuteurs - TutorMatch')
@section('page-title', 'Gestion des Tuteurs')

@section('page-actions')
    <a href="{{ route('tutors.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>
        Ajouter un tuteur
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-chalkboard-teacher text-success me-2"></i>
            Liste des Tuteurs ({{ $tutors->total() }})
        </h5>
    </div>
    <div class="card-body">
        @if($tutors->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Matières</th>
                            <th>Niveaux</th>
                            <th>Expérience</th>
                            <th>Tarif/h</th>
                            <th>Disponibilités</th>
                            <th width="120">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tutors as $tutor)
                        <tr>
                            <td>
                                <strong>{{ $tutor->full_name }}</strong>
                                @if($tutor->description)
                                    <br><small class="text-muted">{{ Str::limit($tutor->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                @foreach(array_slice($tutor->subjects, 0, 3) as $subject)
                                    <span class="badge bg-primary mb-1">{{ $subject }}</span>
                                @endforeach
                                @if(count($tutor->subjects) > 3)
                                    <span class="badge bg-secondary">+{{ count($tutor->subjects) - 3 }}</span>
                                @endif
                            </td>
                            <td>
                                @foreach($tutor->levels as $level)
                                    <span class="badge bg-info mb-1">{{ $level }}</span>
                                @endforeach
                            </td>
                            <td>
                                @if($tutor->experience_years > 0)
                                    <span class="badge bg-warning">{{ $tutor->experience_years }} ans</span>
                                @else
                                    <span class="text-muted">Débutant</span>
                                @endif
                            </td>
                            <td>
                                @if($tutor->hourly_rate)
                                    <strong>{{ $tutor->hourly_rate }}€</strong>
                                @else
                                    <span class="text-muted">Non spécifié</span>
                                @endif
                            </td>
                            <td>
                                @if(count($tutor->availability) > 0)
                                    <small>
                                        @foreach(array_slice($tutor->availability, 0, 2) as $slot)
                                            <div>{{ $slot['day'] }} {{ $slot['start_time'] }}-{{ $slot['end_time'] }}</div>
                                        @endforeach
                                        @if(count($tutor->availability) > 2)
                                            <span class="text-primary">+{{ count($tutor->availability) - 2 }} créneaux</span>
                                        @endif
                                    </small>
                                @else
                                    <span class="text-muted">Aucune</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('tutors.show', $tutor) }}" class="btn btn-outline-info" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('tutors.edit', $tutor) }}" class="btn btn-outline-primary" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('tutors.destroy', $tutor) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce tuteur ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $tutors->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-chalkboard-teacher fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">Aucun tuteur enregistré</h5>
                <p class="text-muted">Commencez par ajouter votre premier tuteur</p>
                <a href="{{ route('tutors.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Ajouter un tuteur
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
