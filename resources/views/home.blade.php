@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Danh sách bài viết -->
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-dark">
                    <i class="fas fa-newspaper"></i> TIN MỚI
                </h2>
            </div>

            @if($posts->count() > 0)
                <div class="list-group">
                    @foreach($posts as $post)
                        <div class="list-group-item border-0 mb-3 p-0">
                            <div class="card shadow-sm border-0" style="transition: transform 0.2s;">
                                <div class="row g-0">
                                    <!-- Thumbnail -->
                                    <div class="col-md-3">
                                        <div class="position-relative overflow-hidden h-100" style="min-height: 150px;">
                                            @if ($post->thumbnail_url)
                                                <img src="{{ $post->thumbnail_url }}" 
                                                     class="w-100 h-100" 
                                                     alt="{{ $post->title }}"
                                                     style="object-fit: cover;">
                                            @else
                                                <div class="w-100 h-100 bg-gradient-secondary d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-image fa-2x text-muted"></i>
                                                </div>
                                            @endif
                                            
                                            <!-- Status badge -->
                                            <span class="position-absolute top-0 end-0 m-2 badge {{ $post->status->badgeClass() }}">
                                                {{ $post->status->label() }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Content -->
                                    <div class="col-md-9">
                                        <div class="card-body">
                                            <!-- Title -->
                                            <h5 class="card-title mb-2">
                                                <a href="{{ route('public.post.show', $post->slug) }}" 
                                                   class="text-decoration-none text-dark">
                                                    {{ $post->title }}
                                                </a>
                                            </h5>

                                            <!-- Description -->
                                            <p class="card-text text-muted mb-2">
                                                {{ Str::limit($post->description ?? strip_tags($post->content), 150) }}
                                            </p>

                                            <!-- Meta info -->
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar"></i> {{ $post->publish_date?->format('H:i d/m/Y') }}
                                                </small>
                                                <a href="{{ route('public.post.show', $post->slug) }}" 
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye"></i> Đọc tiếp
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
            @else
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-newspaper fa-5x text-muted"></i>
                    </div>
                    <h4 class="text-muted">Chưa có bài viết nào được duyệt</h4>
                    <p class="text-muted">Hãy quay lại sau để xem những bài viết mới nhất!</p>
                    @auth
                        <a href="{{ route('posts.create') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-plus"></i> Viết bài viết đầu tiên
                        </a>
                    @endauth
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
}

.bg-gradient-secondary {
    background: linear-gradient(45deg, #f8f9fa, #e9ecef);
}

.list-group-item:hover .card {
    border-color: #007bff;
}

.card-title a:hover {
    color: #007bff !important;
}

@media (max-width: 768px) {
    .row.g-0 .col-md-3 {
        min-height: 120px;
    }
}
</style>
@endsection
