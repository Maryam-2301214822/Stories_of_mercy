<?php
session_start();
include "../config/db.php";

// لازم يكون المستخدم مسجل دخول
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ==================== الجزء المضاف حديثاً: إضافة كتاب إلى المفضلة ====================
if (isset($_GET['add_to_fav'])) {
    $book_id = intval($_GET['add_to_fav']); // تأمين الرقم الممرر

    if ($book_id > 0) {
        // التأكد أولاً أن الكتاب غير مضاف مسبقاً لنفس المستخدم منعاً للتكرار
        $check_query = mysqli_query($conn, "SELECT id FROM favorites WHERE user_id = '$user_id' AND book_id = '$book_id'");
        
        if (mysqli_num_rows($check_query) == 0) {
            // إدخال الكتاب إلى جدول المفضلة
            mysqli_query($conn, "INSERT INTO favorites (user_id, book_id) VALUES ('$user_id', '$book_id')");
        }
    }
    
    // إعادة توجيه لنفس الصفحة لتنظيف الرابط من المتغيرات بعد الإضافة
    header("Location: favorites.php");
    exit();
}
// ============================================================================

// حذف من المفضلة
if (isset($_GET['delete'])) {
    $book_id = intval($_GET['delete']); // تأمين البيانات

    mysqli_query($conn, "
        DELETE FROM favorites 
        WHERE user_id = '$user_id' AND book_id = '$book_id'
    ");

    header("Location: favorites.php");
    exit();
}

// جلب المفضلة مع جلب اسم التصنيف أيضاً للتناسق
$query = "
    SELECT books.*, favorites.id AS fav_id, categories.name AS category_name
    FROM favorites
    JOIN books ON favorites.book_id = books.id
    LEFT JOIN categories ON books.category_id = categories.id
    WHERE favorites.user_id = '$user_id'
";

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المفضلة - قصص الرحمة</title>

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

<div class="container mb-5">
    
    <h3 class="page-title">❤️ الكتب المفضلة</h3>

    <div class="row g-4 justify-content-center">
        <?php if (mysqli_num_rows($result) > 0) { ?>
            <?php while($book = mysqli_fetch_assoc($result)) { ?>
                
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="book-card-custom">
                        
                        <div>
                            <img src="../uploads/<?= htmlspecialchars($book['image']) ?>" class="img-fluid" alt="<?= htmlspecialchars($book['title']) ?>">
                        </div>

                        <div>
                            <h3 class="book-card-title"><?= htmlspecialchars($book['title']) ?></h3>
                            <p class="book-card-author"><?= htmlspecialchars($book['author']) ?></p>
                            
                            <div>
                                <span class="badge badge-custom">
                                    <?= htmlspecialchars($book['category_name'] ?? 'عام') ?>
                                </span>
                            </div>
                            
                            <div class="book-card-price">
                                <?= number_format($book['price'], 2) ?> $
                            </div>
                        </div>

                           <div>
                <a href="book_details.php?id=<?= $book['id'] ?>" class="btn-view-custom">
    عرض التفاصيل
</a>
                   

                            <a href="favorites.php?delete=<?= $book['id'] ?>" class="btn-delete-custom" onclick="return confirm('هل أنت متأكد من حذف هذا الكتاب من المفضلة؟')">
                                حذف من المفضلة 🗑️
                            </a>
                        </div>

                    </div>
                </div>

            <?php } ?>
        <?php } else { ?>
            
            <div class="col-12 col-md-8 text-center my-5">
                <div class="alert alert-custom shadow-sm">
                     لا توجد كتب مضافة إلى المفضلة حالياً ❤️
                     <br><br>
                     <a href="books.php" class="btn btn-sm px-4 py-2 mt-2" style="background-color: #003e21; color:#cf9e4f; border-radius:8px; font-weight:bold; text-decoration:none;">تصفح الكتب الآن</a>
                </div>
            </div>

        <?php } ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>