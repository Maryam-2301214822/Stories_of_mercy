<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول / إنشاء حساب جديد</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="/alsirah_store/css/login.css">
</head>
<body class="login-body">
<div class="main-container shadow" id="mainContainer">

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

    <div class="main-container shadow" id="mainContainer">
        
        <div class="form-container sign-in-container">
            <form method="POST" action="process_login.php" class="px-4 px-md-5">
                <h2 class="form-title mb-4 fs-2">تسجيل الدخول</h2>
                
                <div class="w-100 mb-3 text-start">
                    <label class="form-label fw-bold text-dark-green mb-2">البريد الإلكتروني</label>
                    <input type="email" name="email" class="form-control custom-input" required>
                </div>
                <div class="w-100 mb-4 text-start">
                    <label class="form-label fw-bold text-dark-green mb-2">كلمة المرور</label>
                    <input type="password" name="password" class="form-control custom-input" required>
                </div>
                <button type="submit" name="login" class="btn btn-submit text-white px-5 py-2 fw-bold">تسجيل الدخول</button>
            </form>
        </div>
/////////////////////////////////////////انشاء الحساب جديد للمستخدم ////////////////////////////////////////////
        <div class="form-container sign-up-container">
            <form method="POST" action="process_login.php" class="px-4 px-md-5">
                <h2 class="form-title mb-4 fs-2">إنشاء حساب جديد</h2>
                
                <div class="w-100 mb-3 text-start">
                    <label class="form-label fw-bold text-dark-green mb-2">الاسم المستخدم الجديد</label>
                    <input type="text" name="user_name" class="form-control custom-input" required>
                </div>
                <div class="w-100 mb-3 text-start">
                    <label class="form-label fw-bold text-dark-green mb-2">البريد الإلكتروني</label>
                    <input type="email" name="email" class="form-control custom-input" required>
                </div>
                <div class="w-100 mb-4 text-start">
                    <label class="form-label fw-bold text-dark-green mb-2">كلمة المرور</label>
                    <input type="password" name="password" class="form-control custom-input" required>
                </div>
                <button type="submit" name="register" class="btn btn-submit text-white px-5 py-2 fw-bold">إنشاء الحساب</button>
            </form>
        </div>

        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left px-4">
                    <h1 class="overlay-title mb-4 fs-4 fw-bold">خطوة واحدة تفصلك <br> عن الإبحار في أعظم سيرة عرفتها البشرية.</h1>
                    <button class="btn btn-ghost fw-bold px-5 py-2" id="signInBtn">تسجيل الدخول</button>
                </div>
                <div class="overlay-panel overlay-right px-4">
                    <h1 class="overlay-title mb-4 fs-4 fw-bold">خطوة واحدة تفصلك <br> عن الإبحار في أعظم سيرة عرفتها البشرية.</h1>
                    <button class="btn btn-ghost fw-bold px-5 py-2" id="signUpBtn">انشاء حساب</button>
                </div>
            </div>
        </div>

    </div>

<script>
    const signUpButton = document.getElementById('signUpBtn');
    const signInButton = document.getElementById('signInBtn');
    const mainContainer = document.getElementById('mainContainer');

    if (signUpButton && signInButton && mainContainer) {
        signUpButton.addEventListener('click', () => {
            mainContainer.classList.add("right-panel-active");
        });

        signInButton.addEventListener('click', () => {
            mainContainer.classList.remove("right-panel-active");
        });
    }
</script>

</body>
</html>