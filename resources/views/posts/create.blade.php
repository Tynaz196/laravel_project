@extends('layouts.admin')

@section('title', 'Tạo bài viết mới')
@section('page-title', 'Tạo bài viết mới')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Trang chủ</a></li>
    <li class="breadcrumb-item"><a href="{{ route('posts.index') }}">Quản lý bài viết</a></li>
    <li class="breadcrumb-item active">Tạo mới</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tạo bài viết mới</h3>
                </div>

                <div class="card-body">
                    <form id="post-form" action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="title">Tiêu đề<span class="text-danger">*</span></label>
                            <input type="text" name="title" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror">
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Mô tả ngắn</label>
                            <input type="text" name="description" value="{{ old('description') }}" class="form-control @error('description') is-invalid @enderror">
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="editor">Nội dung<span class="text-danger">*</span></label>
                            <textarea name="content" id="editor" class="form-control @error('content') is-invalid @enderror" style="height: 300px;">{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                      

                        <div class="form-group">
                            <label for="thumbnail">Ảnh thumbnail<span class="text-danger">*</span></label>
                            <input type="file" name="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror" accept="image/*">
                            @error('thumbnail')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="publish_date">Ngày đăng</label>
                            <input type="datetime-local" name="publish_date" value="{{ old('publish_date') }}" class="form-control @error('publish_date') is-invalid @enderror">
                            @error('publish_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Đăng bài
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
        var form = document.getElementById('post-form');
        form.addEventListener('submit', function() {
            document.getElementById('content').value = quill.root.innerHTML;
        });
    });
</script>
@endpush
