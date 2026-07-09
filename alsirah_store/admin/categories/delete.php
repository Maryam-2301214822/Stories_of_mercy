<?php
session_start();
include "../../config/db.php";

// حماية الصفحة والتأكد أن المستخدم مدير النظام
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "غير مصرح لك بالقيام بهذا الإجراء";
    header("Location: ../../login.php");
    exit();
}

// التأكد من وجود رقم التصنيف
if (isset($_GET['id'])) {

    $id = $_GET['id'];

    // استخدام Prepared Statement للحماية من SQL Injection
    $stmt = mysqli_prepare($conn, "DELETE FROM categories WHERE id = ?");

    // ربط المتغير كرقم صحيح
    mysqli_stmt_bind_param($stmt, "i", $id);

    // تنفيذ الحذف
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "تم حذف التصنيف بنجاح";
    } else {
        $_SESSION['error'] = "فشل في حذف التصنيف";
    }

    mysqli_stmt_close($stmt);
}

// العودة إلى صفحة التصنيفات
header("Location: index.php");
exit();
?>
