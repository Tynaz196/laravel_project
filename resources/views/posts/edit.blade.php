@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Chỉnh sửa bài viết</h1>


    <form id="post-form" action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        
        <div class="mb-3">
            <label for="title" class="form-label">Tiêu đề <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $post->title) }}" required>
            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Mô tả ngắn</label>
            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $post->description) }}</textarea>
            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="editor" class="form-label">Nội dung <span class="text-danger">*</span></label>
            <div id="editor" style="height: 300px;">{!! old('content', $post->content) !!}</div>
            <input type="hidden" name="content" id="content">
            @error('content')<div class="text-danger mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="thumbnail" class="form-label">Ảnh thumbnail</label>
            @if ($post->thumbnail_url)
                <div class="mb-2">
                    <img src="{{ $post->thumbnail_url }}" class="img-fluid rounded" alt="Current thumbnail" style="max-width: 200px;">
                    <p class="text-muted mt-1">Ảnh hiện tại</p>
                </div>
            @endif
            <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" id="thumbnail" name="thumbnail" accept="image/*">
            <small class="form-text text-muted">Chọn ảnh mới để thay thế ảnh hiện tại (tùy chọn)</small>
            @error('thumbnail')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Quill
        var quill = new Quill('#editor', {
            theme: 'snow',
            placeholder: 'Nhập nội dung bài viết...',
            modules: { toolbar: [['bold','italic','underline'], ['link','image']] }
        });
        // On form submit, transfer content to hidden input
        var editForm = document.getElementById('post-form');
        editForm.addEventListener('submit', function() {
            document.getElementById('content').value = quill.root.innerHTML;
        });
    });
</script>
@endpush
