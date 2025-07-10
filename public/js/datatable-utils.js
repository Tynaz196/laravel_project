/**
 * Utility functions for DataTable and AJAX operations
 */

// Hàm hiển thị thông báo
function showAlert(type, message) {
    var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    var alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    // Xóa alert cũ trước khi thêm mới
    $('.container .alert').remove();
    $('.container h1').after(alertHtml);
    
    // Tự động ẩn sau 5 giây
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
}

// Hàm xác nhận xóa
function confirmDelete(title, callback) {
    if (confirm('Bạn có chắc chắn muốn xóa bài viết "' + title + '"?')) {
        callback();
    }
}

// Hàm xác nhận xóa tất cả
function confirmDeleteAll(callback) {
    if (confirm('Bạn có chắc chắn muốn xóa TẤT CẢ bài viết của mình? Hành động này không thể hoàn tác!')) {
        callback();
    }
}

// Hàm xử lý AJAX delete
function deletePost(slug, title, table, csrfToken) {
    var deleteUrl = "/posts/" + slug;
    
    confirmDelete(title, function() {
        $.ajax({
            url: deleteUrl,
            type: 'DELETE',
            data: {
                _token: csrfToken
            },
            success: function(response) {
                showAlert('success', response.success);
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
    });
}

// Hàm xử lý AJAX delete all
function deleteAllPosts(table, csrfToken) {
    confirmDeleteAll(function() {
        $.ajax({
            url: '/posts/destroy-all',
            type: 'DELETE',
            data: {
                _token: csrfToken
            },
            success: function(response) {
                showAlert('success', response.success);
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
    });
}
