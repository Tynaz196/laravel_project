@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Danh sách bài viết</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table id="postsTable" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Thumbnail</th>
                <th>Title</th>
                <th>Publish Date</th>
                <th>Description</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($posts as $post)
                <tr>
                    <td>
                        @if ($post->thumbnail_url)
                            <img src="{{ $post->thumbnail_url }}" class="img-fluid rounded" alt="Thumbnail" style="max-width: 100px;">
                        @else
                            <span class="text-muted">No Image</span>
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
                    <td>
                        <a href="{{ route('posts.show', $post->id) }}" class="btn btn-info btn-sm" title="Show">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning btn-sm" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
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
