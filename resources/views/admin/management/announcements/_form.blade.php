@csrf

<div class="form-section">
    <div class="row g-4">
        <!-- Left column: Text details -->
        <div class="col-12 col-md-8">
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <label for="headline" class="form-label">Headline <span class="text-danger">*</span></label>
                    <input type="text" id="headline" name="headline"
                           value="{{ old('headline', $announcement->headline) }}"
                           class="form-control @error('headline') is-invalid @enderror"
                           placeholder="e.g. Barangay Clean-Up Drive 2026">
                    @error('headline') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12 col-md-6">
                    <label for="event_date" class="form-label">Event Date <span class="text-danger">*</span></label>
                    <input type="date" id="event_date" name="event_date"
                           value="{{ old('event_date', $announcement->event_date?->format('Y-m-d')) }}"
                           class="form-control @error('event_date') is-invalid @enderror">
                    @error('event_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                    <textarea id="description" name="description" rows="6" class="form-control @error('description') is-invalid @enderror" placeholder="Provide complete details about the event or news announcement here...">{{ old('description', $announcement->description) }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <!-- Right column: Caption Picture Upload & Preview Card -->
        <div class="col-12 col-md-4">
            <div class="card h-100 p-3 d-flex flex-column align-items-stretch" style="border: 2px dashed #bfd7f5; border-radius: 12px; background: #f8fafc; box-shadow: none;">
                <label for="caption" class="form-label fw-bold text-primary">Caption Picture</label>
                <input type="file" id="caption" name="caption" accept="image/*" class="form-control @error('caption') is-invalid @enderror mb-3">
                @error('caption') <div class="invalid-feedback mb-2">{{ $message }}</div> @enderror

                <div class="announcement-preview-panel flex-grow-1 d-flex flex-column align-items-center justify-content-center border rounded p-2 bg-white" style="min-height: 180px; position: relative;">
                    <div class="text-muted small fw-semibold mb-2 text-center" id="caption_preview_label" style="position: absolute; top: 10px; left: 10px; background: rgba(255,255,255,0.85); padding: 2px 6px; border-radius: 4px; font-size: 0.72rem; border: 1px solid #e2e8f0; z-index: 10;">
                        {{ $announcement->caption_path ? 'Current Picture' : 'Picture Preview' }}
                    </div>

                    @if ($announcement->caption_path)
                        <img id="caption_preview"
                             src="{{ asset('storage/' . $announcement->caption_path) }}"
                             alt="{{ $announcement->headline }}"
                             class="announcement-preview img-fluid rounded"
                             style="max-height: 200px; object-fit: contain;">
                    @else
                        <div id="caption_preview_empty" class="announcement-preview-empty text-muted text-center py-4">
                            <i class="fas fa-image fa-3x mb-2 d-block text-secondary"></i>
                            No picture selected
                        </div>
                        <img id="caption_preview" src="" alt="Caption preview" class="announcement-preview img-fluid rounded d-none" style="max-height: 200px; object-fit: contain;">
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const captionInput = document.getElementById('caption');
    const captionPreview = document.getElementById('caption_preview');
    const captionPreviewEmpty = document.getElementById('caption_preview_empty');
    const captionPreviewLabel = document.getElementById('caption_preview_label');

    if (captionInput && captionPreview) {
        captionInput.addEventListener('change', function () {
            const file = captionInput.files && captionInput.files[0];

            if (!file) {
                return;
            }

            captionPreview.src = URL.createObjectURL(file);
            captionPreview.classList.remove('d-none');
            captionPreviewLabel.textContent = 'New Picture Preview';

            if (captionPreviewEmpty) {
                captionPreviewEmpty.classList.add('d-none');
            }
        });
    }
</script>
@endpush
