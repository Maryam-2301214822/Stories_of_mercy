<?php
session_start();
include "../config/db.php";

// حماية الصفحة
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$book_id = $_GET['book_id'] ?? 0;

$error = "";
$success = "";

// جلب الكتاب
$book_query = mysqli_query($conn, "SELECT * FROM books WHERE id = '$book_id' LIMIT 1");
$book = mysqli_fetch_assoc($book_query);

if (!$book) {
    header("Location: index.php");
    exit();
}

// معالجة الطلب
if (isset($_POST['place_order'])) {

    $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);

    $total_price = $book['price'];

    if (empty($customer_name) || empty($phone) || empty($address)) {
        $error = "يرجى ملء جميع الحقول!";
    } else {

        // 1️⃣ إنشاء الطلب
        $order_query = "
        INSERT INTO orders (user_id, customer_name, phone, address, total_price, payment_method, status)
        VALUES ('$user_id', '$customer_name', '$phone', '$address', '$total_price', '$payment_method', 'pending')
        ";

        if (mysqli_query($conn, $order_query)) {

            // 2️⃣ رقم الطلب
            $order_id = mysqli_insert_id($conn);

            // 3️⃣ إضافة الكتاب داخل الطلب
            mysqli_query($conn, "
                INSERT INTO order_items (order_id, book_id, price)
                VALUES ('$order_id', '$book_id', '$total_price')
            ");

            $success = "تم تسجيل طلبك بنجاح 🎉 سيتم التواصل معك قريباً";

        } else {
            $error = "حدث خطأ أثناء إتمام الطلب: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إتمام الشراء - قصص الرحمة</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/alsirah_store/css/client.css">
    <style>
        body { background-color: #f7f1e9; }
        .checkout-card { background: #fff; border-radius: 15px; border: none; }
        .order-summary { background: #fdfbf7; border: 1px solid #eadecc; border-radius: 10px; }
        .btn-order { background-color: #003e21; color: #cf9e4f; font-weight: bold; }
        .btn-order:hover { background-color: #002514; color: #fff; }
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
    <div class="row g-4 justify-content-center">
        
        <div class="col-md-7">
            <div class="card checkout-card shadow p-4">
                <h3 class="mb-4" style="color: #003e21;">🚚 بيانات الشحن والتوصيل</h3>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger text-center"><?= $error ?></div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success text-center"><?= $success ?></div>
                <?php exit(); endif; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label fw-bold">الاسم الكامل للمستلم</label>
                        <input type="text" name="customer_name" class="form-control" placeholder="اكتبي اسمكِ الثلاثي" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">رقم الهاتف</label>
                        <input type="tel" name="phone" class="form-control" placeholder="05xxxxxxxx" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">العنوان بالتفصيل</label>
                        <input type="text" name="address" class="form-control" placeholder="المدينة، الحي، اسم الشارع" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">طريقة الدفع</label>
                        <select name="payment_method" class="form-select">
                            <option value="الدفع عند الاستلام">الدفع عند الاستلام (Cash on Delivery)</option>
                            <option value="محفظة إلكترونية">محفظة إلكترونية / بطاقة ائتمان</option>
                        </select>
                    </div>

                    <button type="submit" name="place_order" class="btn btn-order w-100 py-3 fs-5">تأكيد شراء الكتاب وتوصيله نقدًا 🛍️</button>
                </form>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card checkout-card shadow p-4 order-summary">
                <h4 class="mb-4 text-secondary">📊 ملخص الطلب</h4>
                <div class="text-center mb-3">
                    <img src="../uploads/<?= htmlspecialchars($book['image']) ?>" class="img-fluid rounded shadow-sm mb-3" style="max-height: 200px;" alt="">
                    <h5><?= htmlspecialchars($book['title']) ?></h5>
                    <p class="text-muted">تأليف: <?= htmlspecialchars($book['author']) ?></p>
                </div>
                <hr>
                <div class="d-flex justify-content-between align-items-center fw-bold fs-5 text-success">
                    <span>إجمالي الحساب:</span>
                    <span><?= number_format($book['price'], 2) ?> $</span>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>