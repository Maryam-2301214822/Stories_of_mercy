<?php
include 'config/db.php';
$userHash = password_hash("123456", PASSWORD_DEFAULT);
$adminHash = password_hash("admin123", PASSWORD_DEFAULT);

mysqli_query($conn, "UPDATE users SET password='$userHash' WHERE email='user@example.com'");
mysqli_query($conn, "UPDATE users SET password='$adminHash' WHERE email='admin@example.com'");

echo "تم تشفير كلمات المرور بنجاح";