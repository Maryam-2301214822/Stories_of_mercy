<?php
session_start();//بداية الجلسة 
include "config/db.php";//الاتصال بقاعة البيانات 

/* ================= LOGIN  فحص التسجيل المستخدم ================= */
if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: cust/index.php");
            }
            exit();
        } else {
            $_SESSION['error'] = "كلمة المرور غير صحيحة";
        }
    } else {
        $_SESSION['error'] = "المستخدم غير موجود";
    }

    header("Location: login.php");
    exit();
}

/* ================= REGISTER  تسجيل الزبون فقط ================= */
if (isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, $_POST['user_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");

    if (mysqli_num_rows($check) > 0) {
        $_SESSION['error'] = "الإيميل مستخدم بالفعل";
    } else {
        $insert = mysqli_query($conn, "
            INSERT INTO users (name,email,password,role)
            VALUES ('$name','$email','$password','user')
        ");

        if ($insert) {
            $_SESSION['success'] = "تم إنشاء الحساب بنجاح، يمكنك تسجيل الدخول الآن";
        } else {
            $_SESSION['error'] = "فشل في إنشاء الحساب";
        }
    }

    header("Location: login.php");
    exit();
}

/* ================= REGISTER  تسجيل المسؤول فقط ================= */
if (isset($_POST['regis'])) {
    $admin_name = mysqli_real_escape_string($conn, $_POST['admin_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $check_query = "SELECT * FROM users WHERE email = '$email'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $_SESSION['error'] = "هذا البريد الإلكتروني مسجل بالفعل لمستخدم آخر!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $role = 'admin';
        $insert_query = "INSERT INTO users (name, email, password, role) VALUES ('$admin_name', '$email', '$hashed_password', '$role')";
        
        if (mysqli_query($conn, $insert_query)) {
            $_SESSION['success'] = "تم تسجيل المسؤول الجديد بنجاح!";
        } else {
            $_SESSION['error'] = "حدث خطأ أثناء التسجيل: " . mysqli_error($conn);
        }
    }
    header("Location: register_admin.php");
    exit();
}
?>