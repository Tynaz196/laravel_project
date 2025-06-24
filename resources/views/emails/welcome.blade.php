<!DOCTYPE html>
<html>
<head>
    <title>Welcome Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            color: #2d3748;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 10px;
        }
        .timestamp {
            color: #718096;
            font-size: 0.9em;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Chào mừng từ ứng dụng Laravel</h1>
    <p>Email này được gửi qua Mailpit SMTP server.</p>
    <p>Nếu bạn nhận được email này, có nghĩa là hệ thống email đang hoạt động tốt!</p>
    <p class="timestamp">Thời gian gửi: {{ $timestamp }}</p>
</body>
</html>
