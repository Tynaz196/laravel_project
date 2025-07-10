@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Danh sách bài viết</h1>
        <div>
            <a href="{{ route('posts.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tạo bài viết
            </a>
            <form action="{{ route('posts.destroyAll') }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa TẤT CẢ bài viết của mình? Hành động này không thể hoàn tác!')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash-alt"></i> Xóa tất cả
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="table-responsive">
        <table id="postsTable" class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th width="80">Ảnh bìa</th>
                    <th>Tiêu đề</th>
                    <th width="150">Ngày tạo</th>
                    <th>Mô tả</th>
                    <th width="120">Trạng thái</th>
                    <th width="200">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <!-- DataTable sẽ render dữ liệu bằng AJAX -->
            </tbody>
        </table>
    </div>
</div>
@endsection

@include('partials.posts-datatable-script')
