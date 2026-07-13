<?php
session_start();
 ?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل مسؤول جديد</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/alsirah_store/css/login.css">
</head>
<body class="login-body">

<div class="login-container single-card-layout">
    
    <div class="login-card">
        <h2 class="form-title">تسجيل مسؤول جديد</h2>

       <!-- عرض رسالة الخطأ إن وجدت -->
<?php if (isset($_SESSION['error'])) { ?>
    <div class="alert-error">
        <?php 
        echo htmlspecialchars($_SESSION['error']); 
        unset($_SESSION['error']); 
        ?>
    </div>
<?php } ?>

<!-- عرض رسالة النجاح إن وجدت -->
<?php if (isset($_SESSION['success'])) { ?>
    <div class="alert-success">
        <?php 
        echo htmlspecialchars($_SESSION['success']); 
        unset($_SESSION['success']); 
        ?>
    </div>
<?php } ?>

        <form method="POST" action="process_login.php"> 
            <div class="input-group">
                <label for="admin_name">اسم المشرف</label>
                <div class="input-wrapper">
                    <i class="fa-regular fa-user input-icon"></i>
                    <input type="text" id="admin_name" name="admin_name" placeholder="اسم المشرف" required>
                </div>
            </div>

            <div class="input-group">
                <label for="email">البريد الإلكتروني</label>
                <div class="input-wrapper">
                    <i class="fa-regular fa-envelope input-icon"></i>
                    <input type="email" id="email" name="email" placeholder="البريد الإلكتروني" required>
                </div>
            </div>

            <div class="input-group">
                <label for="password">كلمة المرور</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-key input-icon"></i>
                    <input type="password" id="password" name="password" placeholder="كلمة المرور" required>
                </div>
            </div>

            <!-- تم تعديل اسم الزر هنا وحذف المسافة الزائدة لتطابق كود المعالجة -->
            <button type="submit" name="regis" class="btn-submit">تسجيل الحساب</button>
        </form>
    </div>

</div>

</body>
</html>