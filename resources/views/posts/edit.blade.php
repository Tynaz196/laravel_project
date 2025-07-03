@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Chỉnh sửa bài viết</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="mb-3">
            <label for="title" class="form-label">Tiêu đề <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                   id="title" name="title" value="{{ old('title', $post->title) }}" required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Mô tả ngắn</label>
            <textarea class="form-control @error('description') is-invalid @enderror" 
                      id="description" name="description" rows="3">{{ old('description', $post->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Nội dung <span class="text-danger">*</span></label>
            <textarea class="form-control @error('content') is-invalid @enderror" 
                      id="content" name="content" rows="10" required>{{ old('content', $post->content) }}</textarea>
            @error('content')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="thumbnail" class="form-label">Ảnh thumbnail</label>
            @if ($post->thumbnail_url)
                <div class="mb-2">
                    <img src="{{ $post->thumbnail_url }}" class="img-fluid rounded" alt="Current thumbnail" style="max-width: 200px;">
                    <p class="text-muted mt-1">Ảnh hiện tại</p>
                </div>
            @endif
            <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" 
                   id="thumbnail" name="thumbnail" accept="image/*">
            <small class="form-text text-muted">Chọn ảnh mới để thay thế ảnh hiện tại (nếu có)</small>
            @error('thumbnail')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Cập nhật bài viết</button>
            <a href="{{ route('posts.index') }}" class="btn btn-secondary">Hủy</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#content').summernote({
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });
</script>
@endpush
