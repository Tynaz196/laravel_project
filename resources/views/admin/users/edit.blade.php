@extends('layouts.admin')

@section('title', 'Chỉnh sửa thông tin tài khoản')
@section('page-title', 'Chỉnh sửa thông tin tài khoản')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.posts.index') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Quản lý tài khoản</a></li>
    <li class="breadcrumb-item active">Chỉnh sửa</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-edit mr-2"></i>
                        Chỉnh sửa thông tin: {{ $user->name }}
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-secondary">ID: {{ $user->id }}</span>
                        <span class="badge badge-info">Email: {{ $user->email }}</span>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name">Tên <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                           id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}">
                                    @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="last_name">Họ <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                           id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}">
                                    @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address">Địa chỉ</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3" placeholder="Nhập địa chỉ của người dùng">{{ old('address', $user->address) }}</textarea>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="status">Trạng thái tài khoản <span class="text-danger">*</span></label>
                            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="0" {{ old('status', $user->status->value) == 0 ? 'selected' : '' }}>
                                    <i class="fas fa-clock"></i> Chờ phê duyệt
                                </option>
                                <option value="1" {{ old('status', $user->status->value) == 1 ? 'selected' : '' }}>
                                    <i class="fas fa-check-circle"></i> Đã phê duyệt
                                </option>
                                <option value="2" {{ old('status', $user->status->value) == 2 ? 'selected' : '' }}>
                                    <i class="fas fa-times-circle"></i> Bị từ chối
                                </option>
                                <option value="3" {{ old('status', $user->status->value) == 3 ? 'selected' : '' }}>
                                    <i class="fas fa-ban"></i> Bị khoá
                                </option>
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="callout callout-info">
                                    <h5><i class="fas fa-info-circle"></i> Thông tin tài khoản</h5>
                                    <p><strong>Email:</strong> {{ $user->email }} <em>(không thể thay đổi)</em></p>
                                    <p><strong>Vai trò:</strong> 
                                        @if($user->role === \App\Enums\UserRole::ADMIN)
                                            <span class="badge badge-danger">Admin</span>
                                        @else
                                            <span class="badge badge-primary">User</span>
                                        @endif
                                        <em>(không thể thay đổi)</em>
                                    </p>
                                    <p><strong>Ngày đăng ký:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
                                    <p><strong>Cập nhật lần cuối:</strong> {{ $user->updated_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Cập nhật thông tin
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
