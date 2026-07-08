<?php
session_start();
include "../config/db.php";

// التصنيفات
$categories = mysqli_query($conn, "SELECT * FROM categories");

// البحث + الفلترة
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

$query = "
    SELECT books.*, categories.name AS category_name
    FROM books
    LEFT JOIN categories ON books.category_id = categories.id
    WHERE 1=1
";

if (!empty($search)) {
    // تأمين النص ضد أي رموز غريبة
    $search_safe = mysqli_real_escape_string($conn, $search);
    $query .= " AND books.title LIKE '%$search_safe%'";
}

if (!empty($category)) {
    $category_safe = intval($category);
    $query .= " AND books.category_id = '$category_safe'";
}

$query .= " ORDER BY books.id DESC";

$books = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>كل الكتب - قصص الرحمة</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/alsirah_store/css/client.css">

    <style>
        /* التطابق اللوني الكامل مع الواجهة */
        

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

<div class="category-container">
    <a href="books.php" class="category-box <?= empty($category) ? 'active' : '' ?>">كل الكتب</a>
    <?php 
    // إعادة جلب التصنيفات لعرضها كأزرار علوية ثابتة
    mysqli_data_seek($categories, 0);
    while($c = mysqli_fetch_assoc($categories)) { 
    ?>
        <a href="books.php?category=<?= $c['id'] ?>&search=<?= htmlspecialchars($search) ?>" class="category-box <?= ($category == $c['id']) ? 'active' : '' ?>">
            <?= htmlspecialchars($c['name']) ?>
        </a>
    <?php } ?>
</div>

<div class="container mb-5">

    <form method="GET" action="books.php" class="row g-3 justify-content-center mb-5">
        <input type="hidden" name="category" value="<?= htmlspecialchars($category) ?>">
        
        <div class="col-12 col-md-8 col-lg-7">
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
                   class="form-control search-input-custom" placeholder="ابحث عن كتاب...">
        </div>
        <div class="col-12 col-md-3 col-lg-2">
            <button type="submit" class="btn btn-search-custom w-100">بحث</button>
        </div>
    </form>

    <div class="row g-4 justify-content-center">
        <?php 
        if(mysqli_num_rows($books) > 0) {
            while($b = mysqli_fetch_assoc($books)) { 
        ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="book-card-custom">
                    
                    <div>
                        <img src="../uploads/<?= htmlspecialchars($b['image']) ?>" class="img-fluid" alt="<?= htmlspecialchars($b['title']) ?>">
                    </div>

                    <div>
                        <h3 class="book-card-title"><?= htmlspecialchars($b['title']) ?></h3>
                        <p class="book-card-author"><?= htmlspecialchars($b['author']) ?></p>
                        
                        <div>
                            <span class="badge badge-custom">
                                <?= htmlspecialchars($b['category_name'] ?? 'عام') ?>
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
        <?php 
            } 
        } else {
            echo "<div class='text-center my-5'><h4 class='text-muted'>لا توجد كتب تطابق خيارات البحث الحالية.</h4></div>";
        }
        ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>