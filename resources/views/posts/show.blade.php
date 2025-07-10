@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('posts.index') }}" class="btn btn-secondary mb-3">← Quay lại danh sách</a>

    <div class="card">
        @if ($post->thumbnail_url)
            <div class="text-center p-3">
                <img src="{{ $post->thumbnail_url }}" class="img-fluid rounded thumbnail-show" alt="Thumbnail">
            </div>
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
