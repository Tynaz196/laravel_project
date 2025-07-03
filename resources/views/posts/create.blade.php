@extends('layouts.app')

@section('head')
    <!-- Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    <h2 class="mb-4">Tạo bài viết mới</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">Tiêu đề</label>
            <input type="text" name="title" value="{{ old('title') }}" class="form-control" >
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Mô tả ngắn</label>
            <input type="text" name="description" value="{{ old('description') }}" class="form-control">
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Nội dung</label>
            <textarea name="content" class="form-control" id="summernote" rows="6" >{{ old('content') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="thumbnail" class="form-label">Ảnh thumbnail</label>
            <input type="file" name="thumbnail" class="form-control" accept="image/*" >
        </div>

        <button type="submit" class="btn btn-success">Đăng bài</button>
        <a href="{{ route('posts.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection

@section('scripts')
    <!-- Summernote JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#summernote').summernote({
                height: 250
            });
        });
    </script>
@endsection
