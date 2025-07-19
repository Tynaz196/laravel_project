<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bài viết đã được duyệt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            color: #28a745;
            border-bottom: 2px solid #28a745;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .content {
            margin-bottom: 20px;
        }
        .post-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
        .footer {
            text-align: center;
            color: #666;
            font-size: 12px;
            margin-top: 20px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>🎉 Chúc mừng! Bài viết của bạn đã được duyệt</h2>
        </div>

        <div class="content">
            <p>Xin chào <strong>{{ $userName }}</strong>,</p>
            
            <p>Chúng tôi vui mừng thông báo rằng bài viết của bạn đã được duyệt và hiện đã được xuất bản trên website!</p>

            <div class="post-info">
                <h3>📝 Thông tin bài viết:</h3>
                <p><strong>Tiêu đề:</strong> {{ $postTitle }}</p>
                <p><strong>Mô tả:</strong> {{ $post->description ?? 'Không có mô tả' }}</p>
                <p><strong>Ngày đăng:</strong> {{ $post->publish_date ? $post->publish_date->format('d/m/Y H:i') : 'Ngay lập tức' }}</p>
                <p><strong>Trạng thái:</strong> <span style="color: #28a745; font-weight: bold;">Đã duyệt</span></p>
            </div>

            <p>Bạn có thể xem bài viết đã xuất bản tại đây:</p>
            <a href="{{ $postUrl }}" class="btn">Xem bài viết</a>

            <p>Cảm ơn bạn đã đóng góp nội dung chất lượng cho cộng đồng!</p>
        </div>

        <div class="footer">
            <p>Email này được gửi tự động từ hệ thống. Vui lòng không trả lời email này.</p>
            <p>&copy; {{ date('Y') }} Website của chúng tôi. Tất cả quyền được bảo lưu.</p>
        </div>
    </div>
</body>
</html>
