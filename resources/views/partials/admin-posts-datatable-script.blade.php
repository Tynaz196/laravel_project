@push('scripts')
<script>
    $(document).ready(function() {
        console.log('Initializing Admin Posts DataTable with AJAX...');
        
        if (typeof $.fn.DataTable !== 'undefined') {
            var table = $('#adminPostsTable').DataTable({
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.posts.data') }}",
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
                        data: 'author',
                        name: 'user_id',
                        render: function(data, type, row) {
                            return `
                                <div>
                                    <strong>${data}</strong><br>
                                    <small class="text-muted">${row.author_email}</small>
                                </div>
                            `;
                        }
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
                                    badgeClass = 'badge-warning';
                                    label = 'Chờ phê duyệt';
                                    break;
                                case 1:
                                case '1':
                                    badgeClass = 'badge-success';
                                    label = 'Đã phê duyệt';
                                    break;
                                case 2:
                                case '2':
                                    badgeClass = 'badge-danger';
                                    label = 'Từ chối';
                                    break;
                                default:
                                    badgeClass = 'badge-secondary';
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
                          var showUrl = "{{ route('admin.posts.show', ':slug') }}".replace(':slug', row.slug);
                          var editUrl = "{{ route('admin.posts.edit', ':id') }}".replace(':id', row.id);
                            
                            return `
                                <div class="btn-group">
                                    <a class="btn btn-info btn-sm" href="${showUrl}" title="Xem bài viết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a class="btn btn-warning btn-sm" href="${editUrl}" title="Chỉnh sửa bài viết">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm delete-post" 
                                        data-id="${row.id}" data-title="${row.title}" title="Xóa bài viết">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            `;
                        }
                    }
                ],
                order: [[4, 'desc']], // Sắp xếp theo ngày đăng mới nhất
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: false,
                responsive: true,
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                language: {
                    processing: "Đang xử lý...",
                    loadingRecords: "Đang tải...",
                    zeroRecords: "Không có dữ liệu",
                    emptyTable: "Không có bài viết nào",
                    info: "Hiển thị _START_ đến _END_ của _TOTAL_ bài viết",
                    infoEmpty: "Không có bản ghi",
                    infoFiltered: "(lọc từ _MAX_ bản ghi)",
                    lengthMenu: "Hiển thị _MENU_ bản ghi",
                    search: "Tìm kiếm (theo tiêu đề, email tác giả):",
                    paginate: {
                        first: "Đầu",
                        last: "Cuối",
                        next: "Tiếp",
                        previous: "Trước"
                    }
                },
                drawCallback: function(settings) {
                    var api = this.api();
                    var recordsTotal = api.page.info().recordsTotal;
                    $('#total-posts').text(recordsTotal);
                }
            });

            // Xử lý sự kiện xóa bài viết với AJAX
            $('#adminPostsTable').on('click', '.delete-post', function(e) {
                e.preventDefault();
                
                var postId = $(this).data('id');
                var title = $(this).data('title');
                var deleteUrl = "{{ route('admin.posts.destroy', ':id') }}".replace(':id', postId);
                
                if (confirm('Bạn có chắc chắn muốn xóa bài viết "' + title + '"? Hành động này không thể hoàn tác!')) {
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

            console.log('Admin Posts DataTable initialized successfully');
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
.btn-group {
    min-width: 100px;
}

@media (max-width: 768px) {
    .btn-group {
        flex-direction: column;
    }
    
    .btn-group .btn {
        width: 100%;
        margin-bottom: 2px;
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

/* Badge styles for AdminLTE */
.badge {
    font-size: 0.75em;
    padding: 0.375rem 0.5rem;
}
</style>
@endpush
