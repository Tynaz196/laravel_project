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
    <h2>Chào mừng {{ $user->first_name }} {{ $user->last_name }}!</h2>
    
    <p>Cảm ơn bạn đã đăng ký tài khoản .</p>
    
    <p><strong>Thông tin tài khoản:</strong></p>
    <ul>
        <li>Họ tên: {{ $user->first_name }} {{ $user->last_name }}</li>
        <li>Email: {{ $user->email }}</li>
        <li>Trạng thái: Chờ phê duyệt</li>
    </ul>
    
    <p>Tài khoản của bạn đang được xem xét và sẽ được kích hoạt sớm nhất có thể.</p>
    
    <p>Trân trọng,<br>
 
    <p class="timestamp">Thời gian gửi: {{ $timestamp }}</p>
</body>
</html>
