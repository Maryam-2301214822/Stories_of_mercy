<?php


session_start();

include "../../config/db.php";


// حماية الصفحة
if (!isset($_SESSION['user_id'])) {

    header("Location: ../../login.php");
    exit();

}


$message = "";


// جلب التصنيفات
$categories = mysqli_query(
    $conn,
    "SELECT id, name FROM categories"
);



if (isset($_POST['save'])) {


    // =========================
    // استقبال وتنظيف البيانات
    // =========================


    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $description = trim($_POST['description']);

    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $category_id = intval($_POST['category_id']);



    // =========================
    // التحقق من البيانات
    // =========================


    if ($price < 0) {


        $message = "❌ السعر يجب أن يكون أكبر من صفر";


    } elseif ($stock < 0) {


        $message = "❌ المخزون لا يمكن أن يكون رقمًا سالبًا";


    } else {



        // =========================
        // رفع الصورة
        // =========================


        $image_name = "";


        if (!empty($_FILES['image']['name'])) {



            $image = $_FILES['image']['name'];
            $tmp = $_FILES['image']['tmp_name'];
            $image_size = $_FILES['image']['size'];



            // استخراج الامتداد

            $image_ext = strtolower(
                pathinfo($image, PATHINFO_EXTENSION)
            );



            // الامتدادات المسموحة

            $allowed = [
                "jpg",
                "jpeg",
                "png",
                "webp"
            ];



            if (!in_array($image_ext, $allowed)) {


                $message =
                "❌ يسمح فقط برفع صور JPG أو PNG أو WEBP";



            } elseif ($image_size > 2 * 1024 * 1024) {


                $message =
                "❌ حجم الصورة يجب ألا يتجاوز 2MB";



            } else {



                // اسم جديد للصورة

                $image_name =
                time() . "_" . rand(100,999) . "." . $image_ext;



                // إنشاء مجلد الصور

                if (!file_exists("../../uploads")) {


                    mkdir(
                        "../../uploads",
                        0755,
                        true
                    );


                }



                // نقل الصورة

                move_uploaded_file(
                    $tmp,
                    "../../uploads/" . $image_name
                );


            }


        }





        // =========================
        // إدخال الكتاب في قاعدة البيانات
        // =========================


        if ($message == "") {



            $query = "
            INSERT INTO books
            (
                title,
                author,
                description,
                price,
                stock,
                image,
                category_id
            )
            VALUES
            (?,?,?,?,?,?,?)
            ";



            $stmt = mysqli_prepare(
                $conn,
                $query
            );



            mysqli_stmt_bind_param(
                $stmt,
                "sssdisi",
                $title,
                $author,
                $description,
                $price,
                $stock,
                $image_name,
                $category_id
            );



            if (mysqli_stmt_execute($stmt)) {


                header("Location: index.php");
                exit();



            } else {


                $message =
                "❌ خطأ في إضافة الكتاب: "
                . mysqli_error($conn);


            }



            mysqli_stmt_close($stmt);



        }



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
