<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <p>Click the link below to reset your password:</p>
   
    
    <a href="{{ config('app.url') }}/reset-password/{{ $token }}" style="display: inline-block; background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
        Reset Password
    </a>
    
  
</body>
</html>
