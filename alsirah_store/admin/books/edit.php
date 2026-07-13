 
<?php
session_start();
include "../../config/db.php";

// حماية الصفحة
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}


// جلب ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = (int) $_GET['id'];


// جلب بيانات الكتاب
$stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$book = $result->fetch_assoc();

if (!$book) {
    header("Location: index.php");
    exit();
}


// جلب التصنيفات
$categories_result = mysqli_query($conn, "SELECT * FROM categories");


// تحديث البيانات
$message = "";

if (isset($_POST['update'])) {


    $title = $_POST['title'];
    $author = $_POST['author'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category_id = $_POST['category_id'];



    // إذا تم رفع صورة جديدة
    if (!empty($_FILES['image']['name'])) {


        $image = $_FILES['image']['name'];
        $tmp = $_FILES['image']['tmp_name'];


        $image_ext = pathinfo($image, PATHINFO_EXTENSION);
        $image_name = time() . '_' . rand(100,999) . '.' . $image_ext;


        if (!file_exists("../../uploads")) {
            mkdir("../../uploads", 0777, true);
        }


        move_uploaded_file($tmp, "../../uploads/" . $image_name);



        $stmt = $conn->prepare(
            "UPDATE books SET
            title=?,
            author=?,
            description=?,
            price=?,
            stock=?,
            image=?,
            category_id=?
            WHERE id=?"
        );


        $stmt->bind_param(
            "sssdissi",
            $title,
            $author,
            $description,
            $price,
            $stock,
            $image_name,
            $category_id,
            $id
        );


    } else {


        $stmt = $conn->prepare(
            "UPDATE books SET
            title=?,
            author=?,
            description=?,
            price=?,
            stock=?,
            category_id=?
            WHERE id=?"
        );


        $stmt->bind_param(
            "sssdiii",
            $title,
            $author,
            $description,
            $price,
            $stock,
            $category_id,
            $id
        );

    }



    if ($stmt->execute()) {


        $message = "✅ تم تعديل الكتاب بنجاح";


        // إعادة جلب البيانات بعد التعديل
        $stmt = $conn->prepare("SELECT * FROM books WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $book = $result->fetch_assoc();


    } else {


        $message = "❌ خطأ في تعديل الكتاب";

    }

}

?>
```


<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل كتاب - قصص الرحمة</title>
    <!-- استدعاء Bootstrap و Tailwind لتطابق التصميم الموحد -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #fdfbf7; /* اللون الخلفي الكريمي للموقع */
        }
        .sidebar {
            background-color: #004d34; /* اللون الأخضر الداكن للهوية البصرية */
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
         <div class="bg-white rounded-2xl p-4 mb-6 shadow-sm flex justify-between items-center border border-orange-100">
            <div>
                <h1 class="text-2xl font-bold text-emerald-900 flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-amber-500"></i> تعديل بيانات الكتاب
                </h1>
                <p class="text-sm text-gray-500 mt-1">تعديل وتحديث بيانات الكتاب الحالي بالمخزن</p>
            </div>
            <a href="index.php" class="btn btn-outline-warning rounded-pill px-4 text-xs">
                <i class="fa-solid fa-arrow-right me-1"></i> العودة للقائمة
            </a>
        </div>

        <!-- رسالة النجاح أو الخطأ -->
        <?php if ($message != "") { ?>
            <div class="alert alert-success alert-dismissible fade show rounded-xl shadow-sm mb-4" role="alert">
                <?= $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php } ?>

        <!-- صندوق النموذج (Form Container) بنفس ستايل إضافة كتاب -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-orange-500/10 max-w-4xl mx-auto">
            <form method="POST" enctype="multipart/form-data" class="space-y-4">
                
                <div>
                    <label class="form-label font-semibold text-emerald-900">عنوان الكتاب <span class="text-red-500">*</span></label>
                    <input type="text" name="title" class="form-control rounded-lg p-2.5 border-gray-200 focus:border-emerald-500 focus:ring-emerald-500"
                           value="<?= htmlspecialchars($book['title']); ?>" required>
                </div>

                <div>
                    <label class="form-label font-semibold text-emerald-900">المؤلف أو الكاتب <span class="text-red-500">*</span></label>
                    <input type="text" name="author" class="form-control rounded-lg p-2.5 border-gray-200 focus:border-emerald-500 focus:ring-emerald-500"
                           value="<?= htmlspecialchars($book['author']); ?>" required>
                </div>

                <div>
                    <label class="form-label font-semibold text-emerald-900">نبذة أو وصف عن الكتاب</label>
                    <textarea name="description" rows="4" class="form-control rounded-lg p-2.5 border-gray-200 focus:border-emerald-500 focus:ring-emerald-500"><?= htmlspecialchars($book['description']); ?></textarea>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label font-semibold text-emerald-900">السعر (ل.س) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="price" class="form-control rounded-lg p-2.5 border-gray-200 focus:border-emerald-500 focus:ring-emerald-500"
                               value="<?= $book['price']; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label font-semibold text-emerald-900">الكمية المتوفرة بالمخزن <span class="text-red-500">*</span></label>
                        <input type="number" name="stock" class="form-control rounded-lg p-2.5 border-gray-200 focus:border-emerald-500 focus:ring-emerald-500"
                               value="<?= $book['stock']; ?>" required>
                    </div>
                </div>

                <div>
                    <label class="form-label font-semibold text-emerald-900">تصنيف الكتاب الرئيسي <span class="text-red-500">*</span></label>
                    <select name="category_id" class="form-select rounded-lg p-2.5 border-gray-200 focus:border-emerald-500 focus:ring-emerald-500" required>
                        <option value="">اختر التصنيف المناسب...</option>
                        <?php while ($cat = mysqli_fetch_assoc($categories_result)) { ?>
                            <option value="<?= $cat['id']; ?>" <?= ($cat['id'] == $book['category_id']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <!-- عرض الغلاف الحالي بشكل جميل لترتيب المساحة -->
                <div class="bg-amber-50/50 p-3 rounded-xl border border-dashed border-amber-200 flex items-center gap-4">
                    <div class="text-sm">
                        <p class="font-semibold text-emerald-900 flex items-center gap-1 mb-1">
                            <i class="fa-solid fa-image text-amber-600"></i> غلاف الكتاب الحالي:
                        </p>
                        <span class="text-xs text-gray-500 block mb-1">اسم الملف: <?= $book['image'] ? $book['image'] : 'لا يوجد غلاف'; ?></span>
                    </div>
                    <?php if ($book['image']) { ?>
                        <img src="../../uploads/<?= $book['image']; ?>" class="rounded-lg shadow-sm border bg-white" width="70" alt="Book Cover">
                    <?php } ?>
                </div>

                <div>
                    <label class="form-label font-semibold text-emerald-900">تحديث الغلاف (صورة جديدة)</label>
                    <input type="file" name="image" class="form-control rounded-lg p-2 border-gray-200 focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div class="pt-2">
                    <button type="submit" name="update" class="btn btn-custom-orange w-100 py-2.5 shadow-sm rounded-xl flex items-center justify-center gap-2">
                        <i class="fa-solid fa-check"></i> حفظ وإدراج التعديلات بالنظام
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>