@extends('admin.layouts.layout')

@section('title', 'Add Announcement - WeConnect Brgy 409')
@section('page-title', 'Add Announcement')

@section('content')
<div class="container-fluid admin-page">
    <form method="POST" action="{{ route('admin.announcements.store') }}" enctype="multipart/form-data" class="admin-form-panel">
        <div class="form-panel-header">
            <div>
                <h1 class="form-panel-title">
                    <i class="fas fa-plus fa-fw"></i>
                    New Announcement
                </h1>
                <p class="form-panel-subtitle">Post an event announcement for residents.</p>
            </div>
            <div class="form-panel-actions">
                <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline-secondary" title="Cancel">
                    <i class="fas fa-xmark fa-fw"></i>
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-floppy-disk fa-fw me-1"></i> Save Announcement
                </button>
            </div>
        </div>

        @include('admin.management.announcements._form')
    </form>
</div>
@endsection
