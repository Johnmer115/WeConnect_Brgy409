@extends('admin.layouts.layout')

@section('title', 'Edit Account - WeConnect Brgy 409')
@section('page-title', 'Edit Account')

@section('content')
<div class="container-fluid admin-page">
    <form method="POST" action="{{ route('admin.accounts.update', $account) }}" class="admin-form-panel">
        @csrf
        @method('PUT')

        <div class="form-panel-header">
            <div>
                <h1 class="form-panel-title">
                    <i class="fas fa-user-pen fa-fw"></i>
                    Edit Account
                </h1>
                <p class="form-panel-subtitle">Update an existing barangay login account.</p>
            </div>
            <div class="form-panel-actions">
                <a href="{{ route('admin.accounts.index') }}" class="btn btn-outline-secondary" title="Cancel">
                    <i class="fas fa-xmark fa-fw"></i>
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-floppy-disk fa-fw me-1"></i> Save Changes
                </button>
            </div>
        </div>

        @include('admin.management.admin._form')
    </form>
</div>
@endsection
