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

    <table id="postsTable" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Ảnh bìa</th>
                <th>Tiêu đề</th>
                <th>Ngày tạo</th>
                <th>Mô tả</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($posts as $post)
                <tr>
                    <td>
                        @if ($post->thumbnail_url)
                            <img src="{{ $post->thumbnail_url }}" class="post-thumbnail img-fluid rounded" alt="Thumbnail">
                        @else
                            <span class="text-muted">Không có ảnh</span>
                        @endif
                    </td>
                    <td>{{ $post->title }}</td>
                    <td>{{ $post->publish_date?->format('H:i d/m/Y') }}</td>
                    <td>{{ $post->description }}</td>
                    <td>
                        <span class="badge {{ $post->status->badgeClass() }}">
                            {{ $post->status->label() }}
                        </span>
                    </td>
                    <td class="action-buttons">
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            <a href="{{ route('posts.show', $post) }}" class="btn btn-info btn-sm" title="Xem bài viết">
                                <i class="fas fa-eye"></i> Xem
                            </a>
                            <a href="{{ route('posts.edit', $post) }}" class="btn btn-warning btn-sm" title="Chỉnh sửa bài viết">
                                <i class="fas fa-edit"></i> Sửa
                            </a>
                            <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết {{ $post->title }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Xóa bài viết">
                                    <i class="fas fa-trash"></i> Xóa
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        console.log('jQuery loaded:', typeof $ !== 'undefined');
        console.log('DataTable function:', typeof $.fn.DataTable !== 'undefined');
        
        if (typeof $.fn.DataTable !== 'undefined') {
            $('#postsTable').DataTable({
                "paging": true,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "pageLength": 5,
                "lengthChange": false,
                "language": {
                    "zeroRecords": "Không tìm thấy dữ liệu",
                    "info": "Hiển thị trang _PAGE_ của _PAGES_",
                    "infoEmpty": "Không có bản ghi",
                    "infoFiltered": "(lọc từ _MAX_ bản ghi)",
                    "paginate": {
                        "first": "Đầu",
                        "last": "Cuối",
                        "next": "Tiếp",
                        "previous": "Trước"
                    }
                }
            });
            console.log('DataTable initialized successfully');
        } else {
            console.error('DataTable not available');
        }
    });
</script>
@endpush
