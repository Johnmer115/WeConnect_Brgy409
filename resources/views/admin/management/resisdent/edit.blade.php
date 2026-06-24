@extends('admin.layouts.layout')

@section('title', 'Edit Resident - WeConnect Brgy 409')
@section('page-title', 'Edit Resident')

@section('content')
<div class="container-fluid admin-page">
    <form method="POST" action="{{ route('admin.residents.update', $resident) }}" class="admin-form-panel">
        @method('PUT')

        <div class="form-panel-header">
            <div>
                <h1 class="form-panel-title">
                    <i class="fas fa-pen fa-fw"></i>
                    Edit Resident
                </h1>
                <p class="form-panel-subtitle">Update the resident's barangay profile.</p>
            </div>
            <div class="form-panel-actions">
                <a href="{{ route('admin.residents.index') }}" class="btn btn-outline-secondary" title="Cancel">
                    <i class="fas fa-xmark fa-fw"></i>
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-floppy-disk fa-fw me-1"></i> Save Changes
                </button>
            </div>
        </div>

        @include('admin.management.resisdent._form')
    </form>
</div>
@endsection
