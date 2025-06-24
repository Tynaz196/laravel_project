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
        .timestamp {
            color: #718096;
            font-size: 0.9em;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <p>Cảm ơn bạn đã đăng ký</p>
    <p class="timestamp">Thời gian gửi: {{ $timestamp }}</p>
</body>
</html>
