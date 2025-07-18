@extends('layouts.admin')

@section('title', 'Quản lý bài viết')
@section('page-title', 'Danh sách bài viết')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Trang chủ</a></li>
    <li class="breadcrumb-item active">Quản lý bài viết</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Danh sách bài viết</h3>
                        <div>
                            <a href="{{ route('posts.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tạo bài viết
                            </a>
                            <form id="delete-all-btn" action="{{ route('posts.destroyAll') }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa TẤT CẢ bài viết của mình? Hành động này không thể hoàn tác!')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash-alt"></i> Xóa tất cả
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table id="postsTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="60">STT</th>
                                    <th width="80">Ảnh bìa</th>
                                    <th>Tiêu đề</th>
                                    <th width="150">Ngày đăng</th>
                                    <th>Mô tả</th>
                                    <th width="120">Trạng thái</th>
                                    <th width="200">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                              
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@include('partials.posts-datatable-script')
