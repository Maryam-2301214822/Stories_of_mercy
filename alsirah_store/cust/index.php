<?php
session_start();
include "../config/db.php";
 
// جلب كل الكتب مرتبة من الأحدث إلى الأقدم
$latest = mysqli_query($conn, "
    SELECT books.*, categories.name AS category_name
    FROM books
    LEFT JOIN categories ON books.category_id = categories.id
    ORDER BY books.id DESC
");

// التصنيفات   
$categories = mysqli_query($conn, "SELECT * FROM categories");
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>قصص الرحمة - الرئيسية</title>

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

<div class="hero-section">
    <img src="../imag/3.png" alt="قصص الرحمة">
</div>

<div class="category-container">
    <?php while($c = mysqli_fetch_assoc($categories)) { ?>
        <a href="books.php?category=<?= $c['id'] ?>" class="category-box">
            <?= $c['name'] ?>
        </a>
    <?php } ?>
</div>

<div class="container mb-5">
    <div class="row g-4 justify-content-center">
        <?php while($b = mysqli_fetch_assoc($latest)) { ?>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="book-card-custom">
                    
                    <div>
                        <img src="../uploads/<?= $b['image'] ?>" class="img-fluid" alt="<?= $b['title'] ?>">
                    </div>

                    <div>
                        <h3 class="book-card-title"><?= $b['title'] ?></h3>
                        <p class="book-card-author"><?= $b['author'] ?></p>
                        
                        <div>
                            <span class="badge badge-custom">
                                <?= $b['category_name'] ?? 'عام' ?>
                            </span>
                        </div>
                        
                        <div class="book-card-price">
                            <?= number_format($b['price'], 2) ?> $
                        </div>
                    </div>

                    <div>
                      <a href="book_details.php?id=<?= $b['id'] ?>" class="btn-view-custom">
                        عرض التفاصيل
                      </a>
                    </div>

                </div>
            </div>
        <?php } ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>