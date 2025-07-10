@extends('layouts.app')

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
            <label for="title" class="form-label">Tiêu đề<span class="text-danger">*</span></label>
            <input type="text" name="title" value="{{ old('title') }}" class="form-control" >
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Mô tả ngắn</label>
            <input type="text" name="description" value="{{ old('description') }}" class="form-control">
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Nội dung<span class="text-danger">*</span></label>
            <textarea name="content" class="form-control" id="content" rows="6" >{{ old('content') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="thumbnail" class="form-label">Ảnh thumbnail<span class="text-danger">*</span></label>
            <input type="file" name="thumbnail" class="form-control" accept="image/*" >
        </div>

        <button type="submit" class="btn btn-success">Đăng bài</button>
        <a href="{{ route('posts.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        console.log('Document ready');
        console.log('jQuery version:', $.fn.jquery);
        console.log('Summernote available:', typeof $.fn.summernote !== 'undefined');
        
        // Fix Bootstrap tooltip conflict
        $.fn.tooltip.Constructor.Default.sanitize = false;
        
        // Đợi một chút để đảm bảo tất cả CSS đã load
        setTimeout(function() {
            if (typeof $.fn.summernote !== 'undefined') {
                console.log('Initializing Summernote...');
                
                // Destroy tất cả instance cũ
                $('.note-editor').remove();
                $('#content').removeClass('note-editable');
                $('#content').show();
                
                $('#content').summernote({
                    height: 300,
                    placeholder: 'Nhập nội dung bài viết...',
                    disableResizeEditor: true,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['fontname', ['fontname']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture']],
                        ['view', ['fullscreen', 'codeview']]
                    ],
                    callbacks: {
                        onInit: function() {
                            console.log('Summernote initialized successfully');
                            // Ẩn textarea gốc sau khi init
                            $('#content').hide();
                        },
                        onImageUpload: function(files) {
                            // Tắt upload ảnh qua Summernote để tránh conflict
                        }
                    }
                });
            } else {
                console.error('Summernote not loaded');
            }
        }, 800); // Tăng thời gian đợi
    });
</script>
@endpush
