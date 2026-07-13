```php
<?php
session_start(); // بداية الجلسة

include "config/db.php"; // الاتصال بقاعدة البيانات


/* ================= LOGIN فحص تسجيل المستخدم ================= */

if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];


    // استخدام Prepared Statement لمنع SQL Injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();


    if ($result && $result->num_rows > 0) {

        $user = $result->fetch_assoc();


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



/* ================= REGISTER تسجيل المستخدم الجديد ================= */

if (isset($_POST['register'])) {


    $name = $_POST['user_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = "user";


    // فحص وجود البريد مسبقاً
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();


    $check = $stmt->get_result();


    if ($check->num_rows > 0) {


        $_SESSION['error'] = "الإيميل مستخدم بالفعل";


    } else {


        // إضافة المستخدم باستخدام Prepared Statement
        $stmt = $conn->prepare(
            "INSERT INTO users (name, email, password, role)
             VALUES (?, ?, ?, ?)"
        );


        $stmt->bind_param(
            "ssss",
            $name,
            $email,
            $password,
            $role
        );


        if ($stmt->execute()) {


            $_SESSION['success'] =
            "تم إنشاء الحساب بنجاح، يمكنك تسجيل الدخول الآن";


        } else {


            $_SESSION['error'] =
            "فشل في إنشاء الحساب";


        }

    }


    header("Location: login.php");
    exit();

}



/* ================= REGISTER ADMIN تسجيل المسؤول ================= */

if (isset($_POST['regis'])) {


    $admin_name = $_POST['admin_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = "admin";


    // فحص البريد
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();


    $check_result = $stmt->get_result();



    if ($check_result->num_rows > 0) {


        $_SESSION['error'] =
        "هذا البريد الإلكتروني مسجل بالفعل لمستخدم آخر!";


    } else {


        // إضافة المسؤول
        $stmt = $conn->prepare(
            "INSERT INTO users (name, email, password, role)
             VALUES (?, ?, ?, ?)"
        );


        $stmt->bind_param(
            "ssss",
            $admin_name,
            $email,
            $password,
            $role
        );



        if ($stmt->execute()) {


            $_SESSION['success'] =
            "تم تسجيل المسؤول الجديد بنجاح!";


        } else {


            $_SESSION['error'] =
            "حدث خطأ أثناء التسجيل";

        }

    }


    header("Location: register_admin.php");
    exit();

}

?>
```
