<?php
session_start();
include "../../config/db.php";

// حماية الصفحة
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

// جلب ID والتحقق منه
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}
$id = $_GET['id'];

// جلب بيانات التصنيف الحالية لتعرض داخل الخانات
$result = mysqli_query($conn, "SELECT * FROM categories WHERE id=$id");
$row = mysqli_fetch_assoc($result);

$message = "";

if (isset($_POST['update'])) {
    // تنظيف البيانات المدخلة لحماية الاستعلام
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $query = "UPDATE categories SET 
                name='$name',
                description='$description'
              WHERE id=$id";

    if (mysqli_query($conn, $query)) {
        // تحديث البيانات المعروضة في الصفحة فوراً بعد الحفظ ناجح
        $result = mysqli_query($conn, "SELECT * FROM categories WHERE id=$id");
        $row = mysqli_fetch_assoc($result);
        $message = "✅ تم تحديث التصنيف بنجاح";
    } else {
        $message = "❌ خطأ في تحديث التصنيف: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل تصنيف - قصص الرحمة</title>
    <!-- استدعاء أحدث المكتبات المتوافقة مع مشروعك -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #fdfbf7; /* اللون الكريمي الهادئ للموقع */
        }
        .sidebar {
            background-color: #004d34; /* لون الهوية الأخضر الداكن */
        }
        .btn-custom-orange {
            background-color: #ffb03b;
            color: #004d34;
            font-weight: bold;
        }
        .btn-custom-orange:hover {
            background-color: #e6992a;
        }
    </style>
</head>
<body class="flex min-h-screen">

     <div class="flex-1 p-8 overflow-y-auto">
        
        <!-- بطاقة رأس الصفحة وزر العودة السريعة -->
        <div class="bg-white rounded-2xl p-4 mb-6 shadow-sm flex justify-between items-center border border-orange-100">
            <div>
                <h1 class="text-2xl font-bold text-emerald-900 flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-amber-500"></i> تعديل بيانات التصنيف
                </h1>
                <p class="text-sm text-gray-500 mt-1">تحديث اسم وقسم التصنيف الحالي المعتمد بالمكتبة</p>
            </div>
            <a href="index.php" class="btn btn-outline-warning rounded-pill px-4 text-xs">
                <i class="fa-solid fa-arrow-right me-1"></i> العودة للتصنيفات
            </a>
        </div>

        <!-- رسائل تنبيهات النظام في حال النجاح أو الخطأ -->
        <?php if ($message != "") { ?>
            <div class="alert alert-success alert-dismissible fade show rounded-xl shadow-sm mb-4" role="alert">
                <?= $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php } ?>

        <!-- صندوق النموذج (Form Container) المنسق والمطابق للهوية -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-orange-500/10 max-w-3xl mx-auto">
            <form method="POST" class="space-y-4">
                
                <div>
                    <label class="form-label font-semibold text-emerald-900">اسم التصنيف <span class="text-red-500">*</span></label>
                    <input type="text" name="name" class="form-control rounded-lg p-2.5 border-gray-200 focus:border-emerald-500 focus:ring-emerald-500"
                           value="<?= $row['name']; ?>" required>
                </div>

                <div>
                    <label class="form-label font-semibold text-emerald-900">الوصف التوضيحي للتصنيف <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="5" class="form-control rounded-lg p-2.5 border-gray-200 focus:border-emerald-500 focus:ring-emerald-500" required><?= htmlspecialchars($row['description']); ?></textarea>
                </div>

                <div class="pt-3">
                    <button type="submit" name="update" class="btn btn-custom-orange w-100 py-2.5 shadow-sm rounded-xl flex items-center justify-center gap-2">
                        <i class="fa-solid fa-rotate"></i> تحديث وحفظ البيانات بالنظام
                    </button>
                </div>

            </form>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>