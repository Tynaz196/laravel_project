@extends('layouts.admin')

@section('title', 'Quản lý tài khoản')
@section('page-title', 'Quản lý tài khoản')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.posts.index') }}">Admin</a></li>
    <li class="breadcrumb-item active">Quản lý tài khoản</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">
                            <i class="fas fa-users mr-2"></i>
                            Danh sách tài khoản
                        </h3>
                        <div class="card-tools">
                            <span class="badge badge-info">Tổng cộng: <span id="total-users">0</span></span>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table id="adminUsersTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="60">STT</th>
                                    <th>Họ tên</th>
                                    <th>Email</th>
                                    <th>Địa chỉ</th>
                                    <th width="120">Trạng thái</th>
                                    <th width="150">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                              
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#adminUsersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.users.data') }}",
            type: 'GET',
            error: function (xhr, error, code) {
                console.error('DataTables AJAX error:', error, code);
                console.error('Response:', xhr.responseText);
                alert('Lỗi khi tải dữ liệu: ' + xhr.responseText);
            }
        },
        columns: [
            {
                data: null,
                searchable: false,
                orderable: false,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'name',
                name: 'last_name',
                render: function(data, type, row) {
                    return '<strong>' + data + '</strong>';
                }
            },
            {
                data: 'email',
                name: 'email'
            },
            {
                data: 'address',
                name: 'address'
            },
            {
                data: 'status_label',
                name: 'status',
                render: function(data, type, row) {
                    return '<span class="badge ' + row.status_badge_class + '">' + data + '</span>';
                }
            },
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    // Kiểm tra role: 'admin' = admin, 'user' = user
                    if (row.role === 'admin' || row.is_admin === true) {
                        return '<span class="text-muted"><i class="fas fa-shield-alt"></i> Admin</span>';
                    } else {
                        return `
                            <a href="/admin/users/${row.id}/edit" class="btn btn-sm btn-info" title="Chỉnh sửa">
                                <i class="fas fa-edit"></i> Sửa
                            </a>
                        `;
                    }
                }
            }
        ],
        language: {
            "sProcessing": "Đang xử lý...",
            "sLengthMenu": "Hiển thị _MENU_ mục",
            "sZeroRecords": "Không tìm thấy dòng nào phù hợp",
            "sInfo": "Đang hiển thị _START_ đến _END_ trong tổng số _TOTAL_ mục",
            "sInfoEmpty": "Đang hiển thị 0 đến 0 trong tổng số 0 mục",
            "sInfoFiltered": "(được lọc từ _MAX_ mục)",
            "sInfoPostFix": "",
            "sSearch": "Tìm kiếm:",
            "sUrl": "",
            "oPaginate": {
                "sFirst": "Đầu",
                "sPrevious": "Trước",
                "sNext": "Tiếp",
                "sLast": "Cuối"
            }
        },
        responsive: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        order: [[1, 'asc']]
    });
});
</script>
@endpush
