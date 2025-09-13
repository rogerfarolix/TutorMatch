@extends('layouts.app')

@section('title', 'Ajouter un Tuteur - TutorMatch')
@section('page-title', 'Ajouter un Tuteur')

@section('page-actions')
    <a href="{{ route('tutors.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>
        Retour à la liste
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-plus text-primary me-2"></i>
                    Informations du Tuteur
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('tutors.store') }}" method="POST">
                    @csrf

                    <!-- Informations de base -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="full_name" class="form-label">Nom complet *</label>
                            <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                                   id="full_name" name="full_name" value="{{ old('full_name') }}" required>
                            @error('full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="experience_years" class="form-label">Années d'expérience</label>
                            <input type="number" class="form-control @error('experience_years') is-invalid @enderror"
                                   id="experience_years" name="experience_years" value="{{ old('experience_years', 0) }}" min="0">
                            @error('experience_years')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="hourly_rate" class="form-label">Tarif horaire (€)</label>
                            <input type="number" step="0.01" class="form-control @error('hourly_rate') is-invalid @enderror"
                                   id="hourly_rate" name="hourly_rate" value="{{ old('hourly_rate') }}" min="0">
                            @error('hourly_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="3"
                                  placeholder="Décrivez brièvement le profil du tuteur...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Matières enseignées -->
                    <div class="mb-4">
                        <label class="form-label">Matières enseignées *</label>
                        <div class="row">
                            @foreach($subjects as $subject)
                            <div class="col-md-4 col-sm-6 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input @error('subjects') is-invalid @enderror"
                                           type="checkbox" name="subjects[]" value="{{ $subject }}"
                                           id="subject_{{ $loop->index }}"
                                           {{ in_array($subject, old('subjects', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="subject_{{ $loop->index }}">
                                        {{ $subject }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @error('subjects')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Niveaux pris en charge -->
                    <div class="mb-4">
                        <label class="form-label">Niveaux pris en charge *</label>
                        <div class="row">
                            @foreach($levels as $level)
                            <div class="col-md-3 col-sm-6 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input @error('levels') is-invalid @enderror"
                                           type="checkbox" name="levels[]" value="{{ $level }}"
                                           id="level_{{ $loop->index }}"
                                           {{ in_array($level, old('levels', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="level_{{ $loop->index }}">
                                        {{ $level }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @error('levels')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Disponibilités -->
                    <div class="mb-4">
                        <label class="form-label">Disponibilités</label>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Jour</th>
                                        <th>Heure de début</th>
                                        <th>Heure de fin</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($days as $day)
                                    <tr>
                                        <td class="align-middle">
                                            <strong>{{ $day }}</strong>
                                        </td>
                                        <td>
                                            <input type="time" class="form-control form-control-sm"
                                                   name="availability_{{ $day }}_start"
                                                   value="{{ old('availability_' . $day . '_start') }}">
                                        </td>
                                        <td>
                                            <input type="time" class="form-control form-control-sm"
                                                   name="availability_{{ $day }}_end"
                                                   value="{{ old('availability_' . $day . '_end') }}">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Laissez vide les créneaux où le tuteur n'est pas disponible
                        </small>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('tutors.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>
                            Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validation côté client pour s'assurer qu'au moins une matière est sélectionnée
    const form = document.querySelector('form');
    const subjectCheckboxes = document.querySelectorAll('input[name="subjects[]"]');
    const levelCheckboxes = document.querySelectorAll('input[name="levels[]"]');

    form.addEventListener('submit', function(e) {
        const selectedSubjects = Array.from(subjectCheckboxes).some(cb => cb.checked);
        const selectedLevels = Array.from(levelCheckboxes).some(cb => cb.checked);

        if (!selectedSubjects) {
            e.preventDefault();
            alert('Veuillez sélectionner au moins une matière.');
            return;
        }

        if (!selectedLevels) {
            e.preventDefault();
            alert('Veuillez sélectionner au moins un niveau.');
            return;
        }
    });

    // Auto-validation des heures
    document.querySelectorAll('input[type="time"]').forEach(function(startInput) {
        if (startInput.name.includes('_start')) {
            const day = startInput.name.replace('availability_', '').replace('_start', '');
            const endInput = document.querySelector(`input[name="availability_${day}_end"]`);

            startInput.addEventListener('change', function() {
                if (startInput.value && endInput.value && startInput.value >= endInput.value) {
                    alert('L\'heure de début doit être antérieure à l\'heure de fin.');
                    startInput.value = '';
                }
            });

            endInput.addEventListener('change', function() {
                if (startInput.value && endInput.value && startInput.value >= endInput.value) {
                    alert('L\'heure de fin doit être postérieure à l\'heure de début.');
                    endInput.value = '';
                }
            });
        }
    });
});
</script>
@endsection
