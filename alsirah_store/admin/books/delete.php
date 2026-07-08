<?php
session_start();
include "../../config/db.php";  

// 1. حماية الصفحة: التأكد من أنه مدير نظام
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "غير مصرح لك بالقيام بهذا الإجراء";
    header("Location: ../../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // 2. استخدام Prepared Statements لحماية الـ SQL Injection
    $stmt = mysqli_prepare($conn, "DELETE FROM books WHERE id = ?");
    
    // ربط المتغير كـ Integer ('i') لحماية أكبر
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "تم حذف الكتاب بنجاح";
    } else {
        $_SESSION['error'] = "فشل في حذف الكتاب";
    }
    
    header("Location: index.php");
    exit();
}
?>