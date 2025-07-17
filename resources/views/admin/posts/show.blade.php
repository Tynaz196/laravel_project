@extends('layouts.admin')

@section('title', 'Chi tiết bài viết')
@section('page-title', 'Chi tiết bài viết')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.posts.index') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.posts.index') }}">Quản lý bài viết</a></li>
    <li class="breadcrumb-item active">Chi tiết</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $post->title }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Quay lại danh sách
                        </a>
                        <a href="{{ route('admin.posts.edit', $post->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Chỉnh sửa
                        </a>
                    </div>
                </div>

                @if ($post->thumbnail_url)
                    <div class="card-img-top text-center p-3" style="background-color: #f8f9fa;">
                        <img src="{{ $post->thumbnail_url }}" class="img-fluid rounded thumbnail-show" alt="Thumbnail">
                    </div>
                @endif

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <h5><i class="fas fa-info-circle text-info"></i> Thông tin bài viết</h5>
                                <table class="table table-sm">
                                    <tr>
                                        <th width="150">ID bài viết:</th>
                                        <td>{{ $post->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tác giả:</th>
                                        <td>
                                            <strong>{{ $post->user->name }}</strong><br>
                                            <small class="text-muted">{{ $post->user->email }}</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Ngày tạo:</th>
                                        <td>{{ $post->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Ngày đăng:</th>
                                        <td>{{ $post->publish_date?->format('d/m/Y H:i') ?? 'Chưa đặt' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Trạng thái:</th>
                                        <td>
                                            @switch($post->status->value)
                                                @case(0)
                                                    <span class="badge badge-warning">Chờ phê duyệt</span>
                                                    @break
                                                @case(1)
                                                    <span class="badge badge-success">Đã phê duyệt</span>
                                                    @break
                                                @case(2)
                                                    <span class="badge badge-danger">Từ chối</span>
                                                    @break
                                            @endswitch
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Slug:</th>
                                        <td><code>{{ $post->slug }}</code></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="callout callout-info">
                                <h5><i class="fas fa-user text-primary"></i> Thông tin tác giả</h5>
                                <p><strong>Tên:</strong> {{ $post->user->name }}</p>
                                <p><strong>Email:</strong> {{ $post->user->email }}</p>
                                <p><strong>Vai trò:</strong> 
                                    @if($post->user->role->value == 1)
                                        <span class="badge badge-danger">Admin</span>
                                    @else
                                        <span class="badge badge-primary">User</span>
                                    @endif
                                </p>
                                <p><strong>Tham gia:</strong> {{ $post->user->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5><i class="fas fa-file-alt text-primary"></i> Mô tả</h5>
                        <div class="callout callout-info">
                            {{ $post->description ?: 'Không có mô tả' }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5><i class="fas fa-newspaper text-success"></i> Nội dung</h5>
                        <div class="card card-outline card-secondary">
                            <div class="card-body">
                                {!! $post->content !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.thumbnail-show {
    max-width: 400px;
    max-height: 300px;
    object-fit: cover;
    border: 1px solid #dee2e6;
}
</style>
@endpush
