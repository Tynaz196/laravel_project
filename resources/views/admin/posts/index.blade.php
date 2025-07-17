@extends('layouts.admin')

@section('title', 'Quản lý bài viết')
@section('page-title', 'Quản lý bài viết')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.posts.index') }}">Admin</a></li>
    <li class="breadcrumb-item active">Quản lý bài viết</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">
                            <i class="fas fa-newspaper mr-2"></i>
                            Danh sách tất cả bài viết
                        </h3>
                        <div class="card-tools">
                            <span class="badge badge-info">Tổng cộng: <span id="total-posts">0</span></span>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table id="adminPostsTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="60">STT</th>
                                    <th width="80">Ảnh bìa</th>
                                    <th>Tiêu đề</th>
                                    <th width="150">Tác giả</th>
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

@include('partials.admin-posts-datatable-script')
