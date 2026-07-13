<?php
session_start();
include "../config/db.php"; // الاتصال بقاعدة البيانات

// حماية الصفحة: التأكد من أن المستخدم مسجل دخوله بالفعل
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php"); // التوجيه لصفحة الدخول إذا لم يكن مسجلاً
    exit();
}

$user_id = $_SESSION['user_id'];
$error = "";
$success = "";

// جلب بيانات المستخدم الحالية من قاعدة البيانات
$query = "SELECT * FROM users WHERE id = '$user_id' LIMIT 1";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// معالجة تحديث البيانات عند إرسال النموذج
if (isset($_POST['update_profile'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    if (empty($name) || empty($email)) {
        $error = "الاسم والبريد الإلكتروني مطلوبان!";
    } else {
        // التحقق من أن البريد الإلكتروني ليس مستخدماً لحساب آخر
        $check_email = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email' AND id != '$user_id'");
        if (mysqli_num_rows($check_email) > 0) {
            $error = "هذا البريد الإلكتروني مستخدم بالفعل من قبل حساب آخر!";
        } else {
            // تحديث الاسم والإيميل بشكل أساسي
            $update_query = "UPDATE users SET name = '$name', email = '$email' WHERE id = '$user_id'";
            
            if (mysqli_query($conn, $update_query)) {
                // تحديث قيم الجلسة الحالية ليعكس الاسم الجديد في الهيدر فوراً
                $_SESSION['user_name'] = $name;
                $success = "تم تحديث البيانات الشخصية بنجاح!";
                
                // إذا قام المستخدم بكتابة كلمة مرور جديدة، نقوم بتشفيرها وتحديثها
                if (!empty($password)) {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    mysqli_query($conn, "UPDATE users SET password = '$hashed_password' WHERE id = '$user_id'");
                    $success = "تم تحديث البيانات وكلمة المرور بنجاح!";
                }
                
                // إعادة جلب البيانات المحدثة للعرض
                $result = mysqli_query($conn, $query);
                $user = mysqli_fetch_assoc($result);
            } else {
                $error = "حدث خطأ أثناء التحديث: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>حسابي - قصص الرحمة</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/alsirah_store/css/client.css">
    <style>
        body { background-color: #f7f1e9; }
        .account-card { background: #fff; border-radius: 15px; border: none; }
        .btn-save { background-color: #003e21; color: #cf9e4f; font-weight: bold; }
        .btn-save:hover { background-color: #002514; color: #fff; }
    </style>
</head>
<body>

 <nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
            <img src="../imag/2.png" alt="" width="40" onerror="this.style.display='none'">
            <span class="navbar-brand-text">قصص الرحمة</span>
        </a>

        <!-- الـ Button المضاف هنا لإظهار القائمة في الشاشات الصغيرة -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <div class="navbar-nav">
               <a class="nav-link" href="index.php">الرئيسية</a>
<a class="nav-link" href="books.php">الكتب</a>
<a class="nav-link" href="favorites.php">المفضلة</a>
<a class="nav-link" href="account.php">حسابي</a>
<a class="nav-link" href="cart.php">السلة</a>
<a class="nav-link" href="../login.php">تسجيل الدخول</a>
            </div>
        </div>
    </div>
</nav>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card account-card shadow p-4">
                <h2 class="text-center mb-4" style="color: #003e21;">👤 إعدادات حسابي</h2>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger text-center"><?= $error ?></div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success text-center"><?= $success ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">اسم المستخدم</label>
                        <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">البريد الإلكتروني</label>
                        <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label fw-bold">كلمة مرور جديدة (اتركها فارغة إذا لم ترد تغييرها)</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="********">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" name="update_profile" class="btn btn-save w-100 py-2">تحديث البيانات</button>
                        <a href="index.php" class="btn btn-secondary w-100 py-2">العودة للرئيسية</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>