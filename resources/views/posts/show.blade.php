@extends('layouts.admin')

@section('title', 'Chi tiết bài viết')
@section('page-title', 'Chi tiết bài viết')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Trang chủ</a></li>
    <li class="breadcrumb-item"><a href="{{ route('posts.index') }}">Quản lý bài viết</a></li>
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
                        <a href="{{ route('posts.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Quay lại danh sách
                        </a>
                        <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning btn-sm">
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
                                        <th width="120">Ngày đăng:</th>
                                        <td>{{ $post->publish_date?->format('d/m/Y H:i') }}</td>
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
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5><i class="fas fa-file-alt text-primary"></i> Mô tả</h5>
                        <div class="callout callout-info">
                            {{ $post->description }}
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
    
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    .card-body {
        padding: 2rem;
    }
    
    .border.rounded.p-3 {
        line-height: 1.6;
    }
</style>
@endpush
