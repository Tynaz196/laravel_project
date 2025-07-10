@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}" class="text-decoration-none">
                    <i class="fas fa-home"></i> Trang chủ
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ $post->title }}</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <!-- Main content -->
        <div class="col-lg-8">
            <article class="card border-0 shadow-sm">
                <!-- Post header -->
                <div class="card-header bg-white border-0 py-3">
                    <h1 class="card-title h2 mb-3">{{ $post->title }}</h1>
                    
                    <!-- Meta information -->
                    <div class="d-flex flex-wrap align-items-center text-muted mb-3">
                        <div class="me-4 mb-2">
                            <i class="fas fa-calendar"></i>
                            {{ $post->publish_date?->format('H:i d/m/Y') }}
                        </div>
                    </div>

                    <!-- Description -->
                    @if($post->description)
                        <p class="lead text-muted">{{ $post->description }}</p>
                    @endif
                </div>

                <!-- Thumbnail -->
                @if ($post->thumbnail_url)
                    <div class="position-relative">
                        <img src="{{ $post->thumbnail_url }}" 
                             class="card-img-top" 
                             alt="{{ $post->title }}"
                             style="max-height: 400px; object-fit: cover;">
                    </div>
                @endif

                <!-- Post content -->
                <div class="card-body">
                    <div class="post-content">
                        {!! $post->content !!}
                    </div>
                </div>

                <!-- Post footer -->
                <div class="card-footer bg-light border-0">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user-circle fa-2x text-primary me-3"></i>
                                <div>
                                    <h6 class="mb-0">{{ $post->user->name }}</h6>
                                    <small class="text-muted">Tác giả</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </div>
</div>

<style>
.post-content {
    line-height: 1.8;
    font-size: 1.1rem;
}

.post-content img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 1rem 0;
}

.post-content h1, .post-content h2, .post-content h3, 
.post-content h4, .post-content h5, .post-content h6 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    color: #2c3e50;
}

.post-content p {
    margin-bottom: 1.5rem;
    text-align: justify;
}

.post-content blockquote {
    border-left: 4px solid #007bff;
    padding-left: 1rem;
    margin: 1.5rem 0;
    font-style: italic;
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 0 8px 8px 0;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}
</style>
@endsection
