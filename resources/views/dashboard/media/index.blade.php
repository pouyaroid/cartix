@extends('layouts.dashboard')

@section('title', 'مدیر فایل')
@section('page-title', 'مدیر فایل')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">مدیر فایل</h5>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadModal">
            <i class="bi bi-upload ms-1"></i> آپلود فایل
        </button>
        <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#folderModal">
            <i class="bi bi-folder-plus ms-1"></i> پوشه جدید
        </button>
    </div>
</div>

{{-- Breadcrumb --}}
@if($currentFolder)
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.media.index') }}" class="text-decoration-none">خانه</a></li>
            <li class="breadcrumb-item active">{{ $currentFolder->name }}</li>
        </ol>
    </nav>
@endif

{{-- Folders --}}
@if($folders->count())
    <div class="row g-2 mb-4">
        @foreach($folders as $folder)
            <div class="col-6 col-md-4 col-lg-3">
                <a href="{{ route('dashboard.media.index', ['folder' => $folder->id]) }}" class="text-decoration-none">
                    <div class="card border h-100">
                        <div class="card-body text-center py-3">
                            <i class="bi bi-folder-fill text-warning fs-3"></i>
                            <div class="small fw-medium mt-1">{{ $folder->name }}</div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
@endif

{{-- Media Grid --}}
@if($media->count())
    <div class="row g-2">
        @foreach($media as $item)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card border h-100">
                    <div class="position-relative">
                        @if(str_starts_with($item->mime_type, 'image/'))
                            <img src="{{ asset('storage/' . $item->path) }}" class="card-img-top" style="height:120px;object-fit:cover;" alt="{{ $item->name }}" loading="lazy">
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-file-earmark fs-1 text-muted"></i>
                            </div>
                        @endif
                        <div class="position-absolute top-0 end-0 p-1">
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteMedia({{ $item->id }})" title="حذف">
                                <i class="bi bi-trash" style="font-size:0.7rem;"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-2">
                        <div class="small fw-medium text-truncate">{{ $item->name }}</div>
                        <div class="text-muted" style="font-size:0.7rem;">{{ number_format($item->size / 1024, 1) }} KB</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{ $media->withQueryString()->links() }}
@else
    <x-empty-state icon="fa-folder-open" title="هنوز فایلی ندارید" description="اولین فایل خود را آپلود کنید." />
@endif

{{-- Upload Modal --}}
<x-modal id="uploadModal" title="آپلود فایل">
    <form id="uploadForm" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="folder_id" value="{{ $currentFolder?->id }}">
        <div class="mb-3">
            <input type="file" class="form-control" name="file" required accept="jpg,jpeg,png,gif,webp,pdf,mp3,mp4,doc,docx">
        </div>
        <div id="uploadProgress" class="progress mb-3" style="display:none;">
            <div class="progress-bar" style="width:0%"></div>
        </div>
        <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-upload ms-1"></i> آپلود
        </button>
    </form>
</x-modal>

{{-- Folder Modal --}}
<x-modal id="folderModal" title="پوشه جدید">
    <form id="folderForm">
        @csrf
        <input type="hidden" name="parent_id" value="{{ $currentFolder?->id }}">
        <div class="mb-3">
            <input type="text" class="form-control" name="name" placeholder="نام پوشه" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-folder-plus ms-1"></i> ساخت پوشه
        </button>
    </form>
</x-modal>

@push('scripts')
<script>
$('#uploadForm').on('submit', function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        url: '{{ route("dashboard.media.upload") }}',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        xhr: function() {
            var xhr = new XMLHttpRequest();
            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    var pct = Math.round((e.loaded / e.total) * 100);
                    $('#uploadProgress').show().find('.progress-bar').css('width', pct + '%').text(pct + '%');
                }
            });
            return xhr;
        },
        success: function() { location.reload(); },
        error: function() { Swal.fire('خطا', 'آپلود فایل با خطا مواجه شد', 'error'); }
    });
});

$('#folderForm').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
        url: '{{ route("dashboard.media.folders.create") }}',
        type: 'POST',
        data: $(this).serialize(),
        success: function() { location.reload(); },
        error: function() { Swal.fire('خطا', 'ساخت پوشه با خطا مواجه شد', 'error'); }
    });
});

function deleteMedia(id) {
    Swal.fire({ title: 'آیا مطمئنید؟', text: 'فایل حذف خواهد شد', icon: 'warning', showCancelButton: true, confirmButtonText: 'بله', cancelButtonText: 'خیر' }).then((r) => {
        if (r.isConfirmed) {
            $.ajax({ url: '/dashboard/media/' + id, type: 'DELETE', data: { _token: '{{ csrf_token() }}' }, success: function() { location.reload(); } });
        }
    });
}
</script>
@endpush
@endsection
