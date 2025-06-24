@extends('layouts.master')

@section('title', 'Trang chủ')

@section('content')
    <div style="padding: 20px;">
        <h2>Chào mừng đến với Laravel!</h2>
        <p>Đây là nội dung trang chủ.</p>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif


    </div>
@endsection