@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Chỉnh sửa hồ sơ</h2>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="first_name">Họ:</label>
            <input type="text" name="first_name" class="form-control"
                   value="{{ old('first_name', $user->first_name) }}">
            @error('first_name') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="last_name">Tên:</label>
            <input type="text" name="last_name" class="form-control"
                   value="{{ old('last_name', $user->last_name) }}">
            @error('last_name') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="address">Địa chỉ:</label>
            <input type="text" name="address" class="form-control"
                   value="{{ old('address', $user->address) }}">
            @error('address') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
</div>
@endsection
