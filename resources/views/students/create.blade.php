@extends('layouts.app')

@section('title', 'Ajouter un élève - TutorMatch')
@section('page-title', 'Ajouter un nouvel élève')

@section('page-actions')
    <a href="{{ route('students.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>
        Retour à la liste
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-user-plus text-primary me-2"></i>
            Informations de l'élève
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('students.store') }}" method="POST">
            @csrf

            <!-- Informations générales -->
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Nom complet <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                               id="full_name" name="full_name" value="{{ old('full_name') }}" required>
                        @error('full_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="level" class="form-label">Niveau scolaire <span class="text-danger">*</span></label>
                        <select class="form-select @error('level') is-invalid @enderror" id="level" name="level" required>
                            <option value="">Sélectionnez un niveau</option>
                            @foreach($levels as $level)
                                <option value="{{ $level }}" {{ old('level') == $level ? 'selected' : '' }}>
                                    {{ $level }}
                                </option>
                            @endforeach
                        </select>
                        @error('level')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Budget -->
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="budget_max" class="form-label">Budget maximum (€/heure)</label>
                        <input type="number" class="form-control @error('budget_max') is-invalid @enderror"
                               id="budget_max" name="budget_max" value="{{ old('budget_max') }}" min="0" step="0.01">
                        @error('budget_max')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Matières demandées -->
            <div class="mb-3">
                <label class="form-label">Matières demandées <span class="text-danger">*</span></label>
                <div class="row">
                    @foreach($subjects as $subject)
                        <div class="col-md-4 col-sm-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input @error('requested_subjects') is-invalid @enderror"
                                       type="checkbox" value="{{ $subject }}"
                                       id="subject_{{ $loop->index }}" name="requested_subjects[]"
                                       {{ in_array($subject, old('requested_subjects', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="subject_{{ $loop->index }}">
                                    {{ $subject }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
                @error('requested_subjects')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-3">
                <label for="description" class="form-label">Description (besoins spécifiques, objectifs...)</label>
                <textarea class="form-control @error('description') is-invalid @enderror"
                          id="description" name="description" rows="3">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Disponibilités -->
            <div class="mb-4">
                <label class="form-label">Disponibilités</label>
                <div class="row">
                    @foreach($days as $day)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card border-light">
                                <div class="card-body p-3">
                                    <h6 class="card-title mb-2">{{ $day }}</h6>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <label class="form-label text-muted small">De</label>
                                            <input type="time" class="form-control form-control-sm"
                                                   name="availability_{{ $day }}_start"
                                                   value="{{ old('availability_' . $day . '_start') }}">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label text-muted small">À</label>
                                            <input type="time" class="form-control form-control-sm"
                                                   name="availability_{{ $day }}_end"
                                                   value="{{ old('availability_' . $day . '_end') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <small class="form-text text-muted">
                    Laissez vide les jours où l'élève n'est pas disponible
                </small>
            </div>

            <!-- Boutons -->
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>
                    Créer l'élève
                </button>
                <a href="{{ route('students.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
