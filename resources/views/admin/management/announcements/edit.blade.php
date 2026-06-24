@extends('admin.layouts.layout')

@section('title', 'Edit Announcement - WeConnect Brgy 409')
@section('page-title', 'Edit Announcement')

@section('content')
<div class="container-fluid admin-page">
    <form method="POST" action="{{ route('admin.announcements.update', $announcement) }}" enctype="multipart/form-data" class="admin-form-panel">
        @method('PUT')

        <div class="form-panel-header">
            <div>
                <h1 class="form-panel-title">
                    <i class="fas fa-pen fa-fw"></i>
                    Edit Announcement
                </h1>
                <p class="form-panel-subtitle">Update the event announcement shown to residents.</p>
            </div>
            <div class="form-panel-actions">
                <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline-secondary" title="Cancel">
                    <i class="fas fa-xmark fa-fw"></i>
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-floppy-disk fa-fw me-1"></i> Save Changes
                </button>
            </div>
        </div>

        @include('admin.management.announcements._form')
    </form>
</div>
@endsection
