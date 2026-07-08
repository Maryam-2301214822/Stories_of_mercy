<?php
session_start();
include "../../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    mysqli_query($conn, "DELETE FROM categories WHERE id=$id");
}

header("Location: index.php");
exit();
?>