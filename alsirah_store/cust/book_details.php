<?php
session_start();
include "../config/db.php";

$id = $_GET['id'] ?? 0;

// جلب الكتاب
$query = "
    SELECT books.*, categories.name AS category_name
    FROM books
    LEFT JOIN categories ON books.category_id = categories.id
    WHERE books.id = '$id'
    LIMIT 1
";

$result = mysqli_query($conn, $query);
$book = mysqli_fetch_assoc($result);

if (!$book) {
    echo "الكتاب غير موجود";
    exit();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($book['title']) ?></title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/alsirah_store/css/client.css">

   
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

<div class="container">
    <div class="details-wrapper shadow-sm">
        <div class="row g-5 align-items-center">
            
            <div class="col-md-7 col-lg-8 order-2 order-md-1">
                <h1 class="book-title-detail"><?= htmlspecialchars($book['title']) ?></h1>
                
                <p class="book-author-detail">المؤلف: <?= htmlspecialchars($book['author']) ?></p>
                
                <div>
                    <span class="badge badge-custom">
                         <?= htmlspecialchars($book['category_name'] ?? 'عام') ?>
                    </span>
                </div>

                <div class="d-flex gap-5 my-3 info-row">
                    <div>السعر: <?= number_format($book['price'], 2) ?> $</div>
                    <div>المخزون: <?= (int)$book['stock'] ?></div>
                </div>

                <div class="book-description">
                    <?= nl2br(htmlspecialchars($book['description'])) ?>
                </div>

                <div class="btn-action-container">
   
                <a class="btn btn-custom-green" href="favorites.php?add_to_fav=<?= $book['id'] ?>">إضافة للمفضلة</a>
<a class="btn btn-custom-green" href="cart.php?add_to_cart=<?= $book['id'] ?>">
    إضافة للسلة
</a>                </div>
            </div>

            <div class="col-md-5 col-lg-4 order-1 order-md-2 text-center">
                <img src="../uploads/<?= htmlspecialchars($book['image']) ?>" class="book-detail-img img-fluid" alt="<?= htmlspecialchars($book['title']) ?>">
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script></body>
</html>