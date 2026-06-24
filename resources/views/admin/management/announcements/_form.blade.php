@csrf

<div class="form-section">
    <h2 class="form-section-title">Event Details</h2>
    <div class="row g-3">
        <div class="col-12 col-md-4">
            <label for="caption" class="form-label">Caption Picture</label>
            <input type="file" id="caption" name="caption" accept="image/*" class="form-control @error('caption') is-invalid @enderror">
            @error('caption') <div class="invalid-feedback">{{ $message }}</div> @enderror

            <div class="announcement-preview-panel mt-2">
                <div class="text-muted small fw-semibold mb-2" id="caption_preview_label">
                    {{ $announcement->caption_path ? 'Current Picture' : 'Picture Preview' }}
                </div>

                @if ($announcement->caption_path)
                    <img id="caption_preview"
                         src="{{ asset('storage/' . $announcement->caption_path) }}"
                         alt="{{ $announcement->headline }}"
                         class="announcement-preview">
                @else
                    <div id="caption_preview_empty" class="announcement-preview-empty">
                        No picture selected
                    </div>
                    <img id="caption_preview" src="" alt="Caption preview" class="announcement-preview d-none">
                @endif
            </div>
        </div>

        <div class="col-12 col-md-4">
            <label for="event_date" class="form-label">Event Date <span class="text-danger">*</span></label>
            <input type="date" id="event_date" name="event_date"
                   value="{{ old('event_date', $announcement->event_date?->format('Y-m-d')) }}"
                   class="form-control @error('event_date') is-invalid @enderror">
            @error('event_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-12 col-md-4">
            <label for="headline" class="form-label">Headline <span class="text-danger">*</span></label>
            <input type="text" id="headline" name="headline"
                   value="{{ old('headline', $announcement->headline) }}"
                   class="form-control @error('headline') is-invalid @enderror">
            @error('headline') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-12">
            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
            <textarea id="description" name="description" rows="5" class="form-control @error('description') is-invalid @enderror">{{ old('description', $announcement->description) }}</textarea>
            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
