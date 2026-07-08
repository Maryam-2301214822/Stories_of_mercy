<?php
session_start();
include "../config/db.php";

/* إنشاء السلة */
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/* إضافة للسلة */
if (isset($_GET['add_to_cart'])) {

    $book_id = (int) $_GET['add_to_cart'];

    if (!in_array($book_id, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $book_id;
    }

    header("Location: cart.php");
    exit();
}

// حماية الصفحة: التأكد من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// معالجة حذف كتاب من السلة
if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    if (($key = array_search($remove_id, $_SESSION['cart'])) !== false) {
        unset($_SESSION['cart'][$key]);
        // إعادة ترتيب المصفوفة
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
    header("Location: cart.php");
    exit();
}

// جلب تفاصيل الكتب الموجودة في السلة من قاعدة البيانات
$cart_books = [];
$total_price = 0;

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $ids = implode(',', array_map('intval', $_SESSION['cart']));
    $query = "SELECT books.*, categories.name AS category_name 
              FROM books 
              LEFT JOIN categories ON books.category_id = categories.id 
              WHERE books.id IN ($ids)";
    $result = mysqli_query($conn, $query);
    
    while ($row = mysqli_fetch_assoc($result)) {
        $cart_books[] = $row;
        $total_price += $row['price'];
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سلة المشتريات - قصص الرحمة</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/alsirah_store/css/client.css">
    <style>
        body { background-color: #f7f1e9; }
        .cart-card { background: #fff; border-radius: 15px; border: none; }
        .btn-checkout { background-color: #003e21; color: #cf9e4f; font-weight: bold; }
        .btn-checkout:hover { background-color: #002514; color: #fff; }
        .book-img { width: 70px; height: 95px; object-fit: cover; border-radius: 5px; }
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
    <h2 class="mb-4 text-center" style="color: #003e21;">🛒 سلة مشترياتكِ</h2>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card cart-card shadow p-4">
                <?php if (empty($cart_books)): ?>
                    <div class="text-center py-5">
                        <h4 class="text-muted">سلة المشتريات فارغة حالياً 🧐</h4>
                        <a href="books.php" class="btn btn-success mt-3" style="background-color: #003e21;">تصفح الكتب المتاحة</a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table align-middle text-center">
                            <thead>
                                <tr>
                                    <th>الكتاب</th>
                                    <th>العنوان</th>
                                    <th>السعر</th>
                                    <th>إجراء</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart_books as $book): ?>
                                    <tr>
                                        <td>
                                            <img src="../uploads/<?= htmlspecialchars($book['image']) ?>" class="book-img shadow-sm" alt="">
                                        </td>
                                        <td class="text-start fw-bold">
                                            <div><?= htmlspecialchars($book['title']) ?></div>
                                            <small class="text-muted">تأليف: <?= htmlspecialchars($book['author']) ?></small>
                                        </td>
                                        <td class="text-success fw-bold"><?= number_format($book['price'], 2) ?> $</td>
                                        <td>
                                            <a href="cart.php?remove=<?= $book['id'] ?>" class="btn btn-sm btn-outline-danger">حذف 🗑️</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!empty($cart_books)): ?>
            <div class="col-lg-4">
                <div class="card cart-card shadow p-4" style="background-color: #fdfbf7; border: 1px solid #eadecc;">
                    <h4 class="mb-4 text-secondary">📊 ملخص السلة</h4>
                    <div class="d-flex justify-content-between mb-3 fs-5">
                        <span>عدد الكتب:</span>
                        <span class="fw-bold"><?= count($cart_books) ?></span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center fw-bold fs-4 text-success mb-4">
                        <span>الإجمالي:</span>
                        <span><?= number_format($total_price, 2) ?> $</span>
                    </div>
                    
                    <a href="checkout.php?book_id=<?= $cart_books[0]['id'] ?>" class="btn btn-checkout w-100 py-3 fs-5">الانتقال لإتمام الشراء 💳</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>