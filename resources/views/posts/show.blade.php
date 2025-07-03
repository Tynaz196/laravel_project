@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('posts.index') }}" class="btn btn-secondary mb-3">← Quay lại danh sách</a>

    <div class="card">
        @if ($post->thumbnail_url)
            <img src="{{ $post->thumbnail_url }}" class="card-img-top" alt="Thumbnail">
        @endif

        <div class="card-body">
            <h2 class="card-title">{{ $post->title }}</h2>
            <p class="text-muted">Đăng lúc: {{ $post->publish_date?->format('d/m/Y H:i') }}</p>
            <div class="mb-3">
                <strong>Mô tả:</strong>
                <p>{{ $post->description }}</p>
            </div>

            <div class="mb-3">
                <strong>Nội dung:</strong>
                <div class="border rounded p-3" style="background-color: #fdfdfd;">
                    {!! $post->content !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
