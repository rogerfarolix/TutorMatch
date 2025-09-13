@extends('layouts.app')

@section('title', 'Élèves - TutorMatch')
@section('page-title', 'Gestion des Élèves')

@section('page-actions')
    <a href="{{ route('students.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>
        Ajouter un élève
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-user-graduate text-primary me-2"></i>
            Liste des Élèves ({{ $students->total() }})
        </h5>
    </div>
    <div class="card-body">
        @if($students->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Niveau</th>
                            <th>Matières demandées</th>
                            <th>Budget Max</th>
                            <th>Disponibilités</th>
                            <th>Matchs</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        <tr>
                            <td>
                                <strong>{{ $student->full_name }}</strong>
                                @if($student->description)
                                    <br><small class="text-muted">{{ Str::limit($student->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $student->level }}</span>
                            </td>
                            <td>
                                @foreach(array_slice($student->requested_subjects, 0, 3) as $subject)
                                    <span class="badge bg-primary mb-1">{{ $subject }}</span>
                                @endforeach
                                @if(count($student->requested_subjects) > 3)
                                    <span class="badge bg-secondary">+{{ count($student->requested_subjects) - 3 }}</span>
                                @endif
                            </td>
                            <td>
                                @if($student->budget_max)
                                    <strong>{{ $student->budget_max }}€/h</strong>
                                @else
                                    <span class="text-muted">Non spécifié</span>
                                @endif
                            </td>
                            <td>
                                @if(count($student->availability) > 0)
                                    <small>
                                        @foreach(array_slice($student->availability, 0, 2) as $slot)
                                            <div>{{ $slot['day'] }} {{ $slot['start_time'] }}-{{ $slot['end_time'] }}</div>
                                        @endforeach
                                        @if(count($student->availability) > 2)
                                            <span class="text-primary">+{{ count($student->availability) - 2 }} créneaux</span>
                                        @endif
                                    </small>
                                @else
                                    <span class="text-muted">Aucune</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $suggestedMatches = $student->matches()->where('status', 'suggested')->count();
                                    $acceptedMatches = $student->matches()->where('status', 'accepted')->count();
                                @endphp

                                @if($suggestedMatches > 0)
                                    <a href="{{ route('students.matches', $student) }}" class="badge bg-warning text-decoration-none">
                                        {{ $suggestedMatches }} suggérés
                                    </a>
                                @endif

                                @if($acceptedMatches > 0)
                                    <span class="badge bg-success">{{ $acceptedMatches }} acceptés</span>
                                @endif

                                @if($suggestedMatches == 0 && $acceptedMatches == 0)
                                    <span class="text-muted">Aucun</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('students.show', $student) }}" class="btn btn-outline-info" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('students.matches', $student) }}" class="btn btn-outline-warning" title="Matchs">
                                        <i class="fas fa-heart"></i>
                                    </a>
                                    <a href="{{ route('students.edit', $student) }}" class="btn btn-outline-primary" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('students.destroy', $student) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet élève ?')">
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
                {{ $students->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-user-graduate fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">Aucun élève enregistré</h5>
                <p class="text-muted">Commencez par ajouter votre premier élève</p>
                <a href="{{ route('students.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Ajouter un élève
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
