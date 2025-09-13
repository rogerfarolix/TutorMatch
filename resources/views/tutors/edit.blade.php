@extends('layouts.app')

@section('title', 'Modifier un tuteur - TutorMatch')
@section('page-title', 'Modifier le Tuteur')

@section('page-actions')
    <a href="{{ route('tutors.show', $tutor) }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>
        Retour aux détails
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-edit text-primary me-2"></i>
            Modifier {{ $tutor->full_name }}
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('tutors.update', $tutor) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <!-- Nom complet -->
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Nom complet *</label>
                        <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                               id="full_name" name="full_name"
                               value="{{ old('full_name', $tutor->full_name) }}" required>
                        @error('full_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Expérience -->
                    <div class="mb-3">
                        <label for="experience_years" class="form-label">Années d'expérience</label>
                        <input type="number" class="form-control @error('experience_years') is-invalid @enderror"
                               id="experience_years" name="experience_years" min="0"
                               value="{{ old('experience_years', $tutor->experience_years) }}">
                        @error('experience_years')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tarif horaire -->
                    <div class="mb-3">
                        <label for="hourly_rate" class="form-label">Tarif horaire (€)</label>
                        <input type="number" class="form-control @error('hourly_rate') is-invalid @enderror"
                               id="hourly_rate" name="hourly_rate" min="0" step="0.01"
                               value="{{ old('hourly_rate', $tutor->hourly_rate) }}">
                        @error('hourly_rate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <!-- Matières -->
                    <div class="mb-3">
                        <label class="form-label">Matières enseignées *</label>
                        <div class="row">
                            @foreach($subjects as $subject)
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="subjects[]"
                                               value="{{ $subject }}" id="subject_{{ $loop->index }}"
                                               {{ in_array($subject, old('subjects', $tutor->subjects)) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="subject_{{ $loop->index }}">
                                            {{ $subject }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('subjects')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Niveaux -->
                    <div class="mb-3">
                        <label class="form-label">Niveaux enseignés *</label>
                        <div class="row">
                            @foreach($levels as $level)
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="levels[]"
                                               value="{{ $level }}" id="level_{{ $loop->index }}"
                                               {{ in_array($level, old('levels', $tutor->levels)) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="level_{{ $loop->index }}">
                                            {{ $level }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('levels')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror"
                          id="description" name="description" rows="4"
                          placeholder="Décrivez votre profil, vos méthodes d'enseignement, vos spécialités...">{{ old('description', $tutor->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Disponibilités -->
            <div class="mb-4">
                <label class="form-label">Disponibilités</label>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            @foreach($days as $day)
                                @php
                                    $daySlot = collect($tutor->availability)->firstWhere('day', $day);
                                    $startTime = $daySlot['start_time'] ?? '';
                                    $endTime = $daySlot['end_time'] ?? '';
                                @endphp
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">{{ $day }}</label>
                                    <div class="row">
                                        <div class="col-6">
                                            <input type="time" class="form-control form-control-sm"
                                                   name="availability_{{ $day }}_start"
                                                   value="{{ old('availability_'.$day.'_start', $startTime) }}"
                                                   placeholder="Début">
                                        </div>
                                        <div class="col-6">
                                            <input type="time" class="form-control form-control-sm"
                                                   name="availability_{{ $day }}_end"
                                                   value="{{ old('availability_'.$day.'_end', $endTime) }}"
                                                   placeholder="Fin">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Laissez vide les créneaux où vous n'êtes pas disponible
                        </small>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="d-flex justify-content-between">
                <div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>
                        Mettre à jour
                    </button>
                    <a href="{{ route('tutors.show', $tutor) }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>
                        Annuler
                    </a>
                </div>

                <form action="{{ route('tutors.destroy', $tutor) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce tuteur ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="fas fa-trash me-2"></i>
                        Supprimer ce tuteur
                    </button>
                </form>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Script pour synchroniser les heures de début/fin
document.addEventListener('DOMContentLoaded', function() {
    const timeInputs = document.querySelectorAll('input[type="time"]');

    timeInputs.forEach(input => {
        input.addEventListener('change', function() {
            const dayMatch = this.name.match(/availability_(.+)_(start|end)/);
            if (dayMatch) {
                const day = dayMatch[1];
                const type = dayMatch[2];

                const startInput = document.querySelector(`input[name="availability_${day}_start"]`);
                const endInput = document.querySelector(`input[name="availability_${day}_end"]`);

                if (type === 'start' && endInput.value && this.value >= endInput.value) {
                    alert('L\'heure de début doit être antérieure à l\'heure de fin');
                    this.value = '';
                }

                if (type === 'end' && startInput.value && this.value <= startInput.value) {
                    alert('L\'heure de fin doit être postérieure à l\'heure de début');
                    this.value = '';
                }
            }
        });
    });
});
</script>
@endpush
@endsection
