@extends('layouts.admin')

@section('title', 'Chỉnh sửa bài viết')
@section('page-title', 'Chỉnh sửa bài viết')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Trang chủ</a></li>
    <li class="breadcrumb-item"><a href="{{ route('posts.index') }}">Quản lý bài viết</a></li>
    <li class="breadcrumb-item active">Chỉnh sửa</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Chỉnh sửa bài viết: {{ $post->title }}</h3>
                </div>

                <div class="card-body">
                    <form id="post-form" action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        
                        <div class="form-group">
                            <label for="title">Tiêu đề <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $post->title) }}" >
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Mô tả ngắn</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $post->description) }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="editor">Nội dung <span class="text-danger">*</span></label>
                            <div id="editor" style="height: 300px;">{!! old('content', $post->content) !!}</div>
                            <input type="hidden" name="content" id="content">
                            @error('content')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="thumbnail">Ảnh thumbnail</label>
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

                        <div class="form-group">
                            <label for="publish_date">Ngày đăng </label>
                            <input type="datetime-local" class="form-control @error('publish_date') is-invalid @enderror" id="publish_date" name="publish_date" value="{{ old('publish_date', $post->publish_date ? $post->publish_date->format('Y-m-d\TH:i') : '') }}" >
                            <small class="form-text text-muted">Chọn thời điểm muốn đăng bài viết</small>
                            @error('publish_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Cập nhật bài viết
                            </button>
                            <a href="{{ route('posts.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
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
