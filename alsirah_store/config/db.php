<?php

$host = "localhost";
$user = "root";
$pass = "";
$db = "alsirah_store";
// إنشاء الاتصال
$conn =new mysqli ($host, $user, $pass, $db);

// التحقق من الاتصال
if ($conn->connect_error){
    
    die("connection faild".$conn->connect_error);
}

?>