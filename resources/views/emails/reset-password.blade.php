<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <p>Nhấn vào link để đặt lại mật khẩu</p>
   
    
    <a href="{{ config('app.url') }}/reset-password/{{ $token }}" style="display: inline-block; background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
        Đặt lại mật khẩu
    </a>
    
  
</body>
</html>
