@extends('layouts.admin')

@section('title', 'Chỉnh sửa bài viết')
@section('page-title', 'Chỉnh sửa bài viết')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.posts.index') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.posts.index') }}">Quản lý bài viết</a></li>
    <li class="breadcrumb-item active">Chỉnh sửa</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit mr-2"></i>
                        Chỉnh sửa bài viết: {{ $post->title }}
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-secondary">ID: {{ $post->id }}</span>
                        <span class="badge badge-info">Tác giả: {{ $post->user->name }}</span>
                    </div>
                </div>

                <div class="card-body">
                    <form id="admin-post-form" action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="title">Tiêu đề <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $post->title) }}" required>
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
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">Trạng thái bài viết <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="0" {{ old('status', $post->status->value) == 0 ? 'selected' : '' }}>
                                            <i class="fas fa-clock"></i> Chờ phê duyệt
                                        </option>
                                        <option value="1" {{ old('status', $post->status->value) == 1 ? 'selected' : '' }}>
                                            <i class="fas fa-check"></i> Đã phê duyệt
                                        </option>
                                        <option value="2" {{ old('status', $post->status->value) == 2 ? 'selected' : '' }}>
                                            <i class="fas fa-times"></i> Từ chối
                                        </option>
                                    </select>
                                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="form-group">
                                    <label for="publish_date">Ngày đăng</label>
                                    <input type="datetime-local" class="form-control @error('publish_date') is-invalid @enderror" id="publish_date" name="publish_date" value="{{ old('publish_date', $post->publish_date ? $post->publish_date->format('Y-m-d\TH:i') : '') }}">
                                    @error('publish_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
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

                                <div class="callout callout-info">
                                    <h5><i class="fas fa-info-circle"></i> Thông tin bài viết</h5>
                                    <p><strong>Tác giả:</strong> {{ $post->user->name }}</p>
                                    <p><strong>Email:</strong> {{ $post->user->email }}</p>
                                    <p><strong>Ngày tạo:</strong> {{ $post->created_at->format('d/m/Y H:i') }}</p>
                                    <p><strong>Cập nhật lần cuối:</strong> {{ $post->updated_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Cập nhật bài viết
                            </button>
                            <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                            <a href="{{ route('admin.posts.show', $post->id) }}" class="btn btn-info">
                                <i class="fas fa-eye"></i> Xem chi tiết
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
            modules: { 
                toolbar: [
                    ['bold','italic','underline', 'strike'],
                    ['blockquote', 'code-block'],
                    [{ 'header': 1 }, { 'header': 2 }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['link','image'],
                    ['clean']
                ] 
            }
        });
        
        // On form submit, transfer content to hidden input
        var form = document.getElementById('admin-post-form');
        form.addEventListener('submit', function() {
            document.getElementById('content').value = quill.root.innerHTML;
        });
    });
</script>
@endpush
