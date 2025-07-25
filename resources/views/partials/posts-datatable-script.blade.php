@push('scripts')
<script>
    $(document).ready(function() {
        console.log('Initializing DataTable with AJAX...');
        
        if (typeof $.fn.DataTable !== 'undefined') {
            var table = $('#postsTable').DataTable({
                serverSide: true,
                ajax: {
                    url: "{{ route('posts.data') }}",
                    type: 'GET'
                },
                columns: [
                    {
                        data: null,
                        name: 'stt',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'thumbnail',
                        name: 'thumbnail',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            if (data) {
                                return '<img src="' + data + '" class="post-thumbnail img-fluid rounded" alt="Thumbnail" style="max-width: 60px; height: auto;">';
                            } else {
                                return '<span class="text-muted">Không có ảnh</span>';
                            }
                        }
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'publish_date',
                        name: 'publish_date'
                    },
                    {
                        data: 'description',
                        name: 'description',
                        orderable: false
                    },
                    {
                        data: 'status_badge',
                        name: 'status',
                        orderable: false,
                        render: function(data, type, row) {
                            var badgeClass = '';
                            var label = '';
                            
                            switch(data) {
                                case 0:
                                case '0':
                                    badgeClass = 'bg-warning text-dark';
                                    label = 'Chờ phê duyệt';
                                    break;
                                case 1:
                                case '1':
                                    badgeClass = 'bg-success';
                                    label = 'Đã phê duyệt';
                                    break;
                                case 2:
                                case '2':
                                    badgeClass = 'bg-danger';
                                    label = 'Từ chối';
                                    break;
                                default:
                                    badgeClass = 'bg-secondary';
                                    label = 'Không xác định';
                            }
                            
                            return '<span class="badge ' + badgeClass + '">' + label + '</span>';
                        }
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            // Tạo URL bằng cách thay thế placeholder
                            var showUrl = "{{ route('posts.show', ':slug') }}".replace(':slug', row.slug);
                            var editUrl = "{{ route('posts.edit', ':id') }}".replace(':id', row.id);
                            
                            return `
                                <div class="d-flex flex-wrap gap-2 align-items-center">
                                    <a class="btn btn-info btn-sm" href="${showUrl}" title="Xem bài viết">
                                        <i class="fas fa-eye"></i> Xem
                                    </a>
                                    <a class="btn btn-warning btn-sm" href="${editUrl}" title="Chỉnh sửa bài viết">
                                        <i class="fas fa-edit"></i> Sửa
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm delete-post" 
                                        data-slug="${row.slug}"  data-id="${row.id}" data-title="${row.title}" title="Xóa bài viết">
                                        <i class="fas fa-trash"></i> Xóa
                                    </button>
                                </div>
                            `;
                        }
                    }
                ],
                order: [[3, 'desc']], // Sắp xếp theo ngày tạo mới nhất
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: false,
                responsive: true,
                pageLength: 5,
                lengthChange: false,
                language: {
                    processing: "Đang xử lý...",
                    loadingRecords: "Đang tải...",
                    zeroRecords: "Không có dữ liệu",
                    emptyTable: "Bạn chưa có bài viết nào",
                    info: "Hiển thị trang _PAGE_ của _PAGES_",
                    infoEmpty: "Không có bản ghi",
                    infoFiltered: "(lọc từ _MAX_ bản ghi)",
                    lengthMenu: "Hiển thị _MENU_ bản ghi",
                    search: "Tìm kiếm:",
                    paginate: {
                        first: "Đầu",
                        last: "Cuối",
                        next: "Tiếp",
                        previous: "Trước"
                    }
                },
                // ẩn bài viết
                drawCallback: function(settings) {
                    var api = this.api();
                    var count = api.data().count();
                    if (count === 0) {
                        $('#delete-all-btn').hide();
                    } else {
                        $('#delete-all-btn').show();
                    }
                }
            });

            // Xử lý sự kiện xóa bài viết với AJAX
            $('#postsTable').on('click', '.delete-post', function(e) {
                e.preventDefault();
                
                var postId = $(this).data('id');
                var title = $(this).data('title');
                var deleteUrl = "{{ route('posts.destroy', ':id') }}".replace(':id', postId);
                
                if (confirm('Bạn có chắc chắn muốn xóa bài viết "' + title + '"?')) {
                    $.ajax({
                        url: deleteUrl,
                        type: 'DELETE',
                        data: {
                            _token: @json(csrf_token())
                        },
                        success: function(response) {
                            // Hiển thị thông báo thành công
                            showAlert('success', response.success);
                            // Reload table
                            table.ajax.reload();
                        },
                        error: function(xhr) {
                            var errorMessage = 'Đã xảy ra lỗi khi xóa bài viết.';
                            if (xhr.responseJSON && xhr.responseJSON.error) {
                                errorMessage = xhr.responseJSON.error;
                            }
                            showAlert('error', errorMessage);
                        }
                    });
                }
            });

            // Xử lý sự kiện xóa tất cả bài viết với AJAX
            $('form[action="{{ route('posts.destroyAll') }}"]').on('submit', function(e) {
                e.preventDefault();
                
                if (confirm('Bạn có chắc chắn muốn xóa TẤT CẢ bài viết của mình? Hành động này không thể hoàn tác!')) {
                    $.ajax({
                        url: '{{ route('posts.destroyAll') }}',
                        type: 'DELETE',
                        data: {
                            _token: @json(csrf_token())
                        },
                        success: function(response) {
                            // Hiển thị thông báo thành công
                            showAlert('success', response.success);
                            // Reload table
                            table.ajax.reload();
                        },
                        error: function(xhr) {
                            var errorMessage = 'Đã xảy ra lỗi khi xóa tất cả bài viết.';
                            if (xhr.responseJSON && xhr.responseJSON.error) {
                                errorMessage = xhr.responseJSON.error;
                            }
                            showAlert('error', errorMessage);
                        }
                    });
                }
            });

            console.log('DataTable with AJAX initialized successfully');
        } else {
            console.error('DataTable not available');
        }
    });

    // Hàm hiển thị thông báo
    function showAlert(type, message) {
        var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        var alertHtml = `
            <div class="alert ${alertClass} alert-dismissible" role="alert">
                ${message}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        `;
        
        // Xóa alert cũ trước khi thêm mới
        $('.container-fluid .alert').remove();
        $('.content-header').after(alertHtml);
        
        // Tự động ẩn sau 5 giây
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 5000);
    }
</script>

<style>
/* Tối ưu responsive cho action buttons */
.action-buttons {
    min-width: 200px;
}

@media (max-width: 768px) {
    .action-buttons .d-flex {
        flex-direction: column;
        gap: 0.5rem !important;
    }
    
    .action-buttons .btn {
        width: 100%;
        justify-content: center;
    }
}

/* Tối ưu thumbnail */
.post-thumbnail {
    max-width: 60px;
    height: auto;
    object-fit: cover;
}

/* DataTable processing indicator */
.dataTables_processing {
    background: rgba(255, 255, 255, 0.9);
    border: 1px solid #ddd;
    border-radius: 5px;
    color: #666;
    font-weight: bold;
    padding: 10px;
}

/* Responsive table wrapper */
.table-responsive {
    border-radius: 0.375rem;
    overflow: hidden;
}

/* AdminLTE Post Show Styles */
.thumbnail-show {
    max-width: 400px;
    max-height: 300px;
    object-fit: cover;
    border: 1px solid #dee2e6;
}

/* Card content spacing */
.content .card .card-body {
    padding: 1.25rem;
}

/* Badge styles for AdminLTE */
.badge {
    font-size: 0.75em;
    padding: 0.375rem 0.5rem;
}
</style>
@endpush
