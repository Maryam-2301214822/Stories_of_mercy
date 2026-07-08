<?php
// تشغيل نظام مراقبة الأخطاء وعرضها على الشاشة لتسهيل تصليح الكود أثناء البرمجة
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start(); 
// بدء أو استعادة الجلسة (الـ Session) لتمكين الموقع من تذكر بيانات المستخدم المتنقل بين الصفحات
include "../../config/db.php";
// حماية الصفحة: إذا لم يقم المستخدم بتسجيل الدخول، يتم طرده وتحويله لصفحة تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

$message = "";

// جلب التصنيفات
$categories = mysqli_query($conn, "SELECT * FROM categories");

if (isset($_POST['save'])) {
             //تغيير أو تعطيل أي رموز خاصة
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $stock = mysqli_real_escape_string($conn, $_POST['stock']);
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // =========================
    // رفع الصورة
    // =========================
$image = $_FILES['image']['name']; // جلب الاسم الأصلي للملف/الصورة المرفوعة من جهاز المستخدم    
$tmp = $_FILES['image']['tmp_name']; // جلب المسار المؤقت للصورة على السيرفر لتجهيزها للنقل
    $image_name = "";
// التحقق مما إذا كان المستخدم قد اختار صورة للرفع ولم يترك الخانة فارغة
    if (!empty($image)) {
        
        // 1. استخراج امتداد الصورة الأصلي (مثل: png أو jpg)
        $image_ext = pathinfo($image, PATHINFO_EXTENSION);
        
        // 2. توليد اسم جديد وفريد للصورة بدمج (الوقت الحالي + رقم عشوائي + الامتداد) لمنع تداخل الأسماء
        $image_name = time() . '_' . rand(100, 999) . '.' . $image_ext;

        // 3. التأكد من وجود مجلد الرفع (uploads)، وإذا لم يكن موجوداً يتم إنشاؤه تلقائياً بصلاحيات كاملة
        if (!file_exists("../../uploads")) {
            mkdir("../../uploads", 0777, true);
        }

        // 4. نقل الصورة من مسارها المؤقت على السيرفر إلى مجلد الرفع الدائم بالاسم الجديد
        move_uploaded_file($tmp, "../../uploads/" . $image_name);
    }
    // =========================
    // INSERT (بدون PDF)
    // =========================
// 1. كتابة أمر الـ SQL لإدخال بيانات الكتاب الجديد في جدول الكتب (books)
    $query = "INSERT INTO books 
    (title, author, description, price, stock, image, category_id)
    VALUES 
    ('$title', '$author', '$description', '$price', '$stock', '$image_name', '$category_id')";

    // 2. إرسال الأمر وتنفيذه داخل قاعدة البيانات وفحص النتيجة
    if (mysqli_query($conn, $query)) {
        // إذا نجح الحفظ: يتم تحويل المستخدم فوراً لصفحة استعراض الكتب (index.php) وتوقف الكود
        header("Location: index.php");
        exit();
    } else {
        // إذا فشل الحفظ: يتم تخزين نص الخطأ القادم من الداتابيز في متغير $message لعرضه للمستخدم
        $message = "❌ خطأ في إضافة الكتاب: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة كتاب جديد - قصص الرحمة</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/alsirah_store/css/style.css">
   
</head>

<body class="flex h-screen overflow-hidden bg-[#fffbf4]">

<?php include "../sidebar.php"; ?>

<div class="flex-1 mr-64 p-8 overflow-y-auto flex flex-col gap-6 w-full text-right" dir="rtl">

    <div class="w-full flex justify-between items-center bg-[#fffbf4] p-5 rounded-2xl border border-[#ffb84d]/30 shadow-sm mt-2">
        <div>
            <h2 class="text-2xl font-bold text-[#004d26] flex items-center gap-2">
                <span>➕</span> 
                <span>إضافة كتاب جديد</span>
            </h2>
            <p class="text-slate-500 text-sm mt-1">تعبئة وإدراج بيانات الكتاب الجديد بالمخزن</p>
        </div>
        
        <a href="index.php" class="bg-[#ffeed6] border border-[#ffb84d]/30 text-[#004d26] px-5 py-2 rounded-full font-bold text-sm shadow-sm hover:opacity-90 transition">
            <i class="bi bi-arrow-right ml-1"></i> العودة للقائمة
        </a>
    </div>

    <?php if ($message) { ?>
        <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl font-bold text-sm shadow-sm">
            <?= $message ?>
        </div>
    <?php } ?>

    <div class="bg-white p-8 rounded-3xl shadow-sm border border-[#ffb84d]/30 max-w-4xl">

        <form method="POST" enctype="multipart/form-data" class="flex flex-col gap-5">

            <div class="flex flex-col gap-1.5">
                <label class="text-sm font-bold text-[#004d26] mr-1">عنوان الكتاب <span class="text-red-500">*</span></label>
                <input type="text" name="title" placeholder="مثال: السيرة النبوية العطرة" required 
                       class="p-3 bg-[#fffbf4]/50 border border-slate-200 rounded-xl focus:outline-none focus:border-[#ffb84d] focus:bg-white text-slate-800 transition font-medium">
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="text-sm font-bold text-[#004d26] mr-1">المؤلف أو الكاتب <span class="text-red-500">*</span></label>
                <input type="text" name="author" placeholder="مثال: ابن كثير" required 
                       class="p-3 bg-[#fffbf4]/50 border border-slate-200 rounded-xl focus:outline-none focus:border-[#ffb84d] focus:bg-white text-slate-800 transition font-medium">
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="text-sm font-bold text-[#004d26] mr-1">نبذة أو وصف عن الكتاب</label>
                <textarea name="description" placeholder="اكتب وصفاً مختصراً لمحتوى الكتاب..." rows="4"
                          class="p-3 bg-[#fffbf4]/50 border border-slate-200 rounded-xl focus:outline-none focus:border-[#ffb84d] focus:bg-white text-slate-800 transition font-medium resize-none"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex flex-col gap-1.5">
                    <label class="text-sm font-bold text-[#004d26] mr-1">السعر (ل.س) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" placeholder="0.00" required 
                           class="p-3 bg-[#fffbf4]/50 border border-slate-200 rounded-xl focus:outline-none focus:border-[#ffb84d] focus:bg-white text-slate-800 transition font-bold">
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-sm font-bold text-[#004d26] mr-1">الكمية المتوفرة بالمخزن <span class="text-red-500">*</span></label>
                    <input type="number" name="stock" placeholder="مثال: 50" required 
                           class="p-3 bg-[#fffbf4]/50 border border-slate-200 rounded-xl focus:outline-none focus:border-[#ffb84d] focus:bg-white text-slate-800 transition font-bold">
                </div>
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="text-sm font-bold text-[#004d26] mr-1">تصنيف الكتاب الرئيسي <span class="text-red-500">*</span></label>
                <select name="category_id" required 
                        class="p-3 bg-[#fffbf4]/50 border border-slate-200 rounded-xl focus:outline-none focus:border-[#ffb84d] focus:bg-white text-slate-700 font-semibold transition cursor-pointer">
                    <option value="" class="text-slate-400">اختر التصنيف المناسب...</option>
                    <?php while($cat = mysqli_fetch_assoc($categories)) { ?>
                        <option value="<?= $cat['id'] ?>"><?= ($cat['name']) ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="text-sm font-bold text-[#004d26] mr-1">غلاف الكتاب (صورة)</label>
                <input type="file" name="image" 
                       class="p-2.5 bg-[#fffbf4]/50 border border-slate-200 rounded-xl focus:outline-none focus:border-[#ffb84d] text-slate-600 font-medium transition text-sm cursor-pointer file:ml-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#ffb84d]/20 file:text-[#004d26]">
            </div>

            <button type="submit" name="save"
                class="bg-[#ffb84d] text-[#004d26] p-3.5 rounded-xl font-bold hover:opacity-95 transition-all shadow-md text-base mt-2 flex items-center justify-center gap-2">
                <i class="bi bi-check-lg text-lg"></i> حفظ وإدراج الكتاب بالنظام
            </button>

        </form>
    </div>

</div>

</body>
</html>