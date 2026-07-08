<?php
session_start();
include "../../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

$id = intval($_GET['id']);

// جلب المستخدم للتحقق
$result = mysqli_query($conn, "SELECT * FROM users WHERE id=$id");
$user = mysqli_fetch_assoc($result);

if (!$user) {
    header("Location: index.php");
    exit();
}


if ($id == $_SESSION['user_id']) {
    header("Location: index.php");
    exit();
}

// الحذف
mysqli_query($conn, "DELETE FROM users WHERE id=$id");

header("Location: index.php");
exit();
?>