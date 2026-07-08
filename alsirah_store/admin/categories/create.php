<?php
session_start();
include "../../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

$message = "";

if (isset($_POST['save'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $query = "INSERT INTO categories (name, description)
              VALUES ('$name', '$description')";

    if (mysqli_query($conn, $query)) {
        header("Location: index.php");
        exit();
    } else {
        $message = "حدث خطأ أثناء الإضافة";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl" translate="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google" content="notranslate">
    <title>إضافة تصنيف - قصص الرحمة</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/alsirah_store/css/style.css">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }
    </style>
</head>

<body class="flex h-screen overflow-hidden bg-[#fffbf4]">

<?php include "../sidebar.php"; ?>

<main class="flex-1 mr-64 p-8 overflow-y-auto flex flex-col gap-6 w-full text-right" dir="rtl">

    <div class="w-full flex justify-between items-center bg-[#fffbf4] p-5 rounded-2xl border border-[#ffb84d]/30 shadow-sm mt-2">
        <div>
            <h2 class="text-2xl font-bold text-[#004d26] flex items-center gap-2">
                <span>➕</span> 
                <span>إضافة تصنيف جديد</span>
            </h2>
            <p class="text-slate-500 text-sm mt-1">إنشاء قسم وتصنيف جديد لترتيب كتب المتجر</p>
        </div>

        <a href="index.php"
           class="bg-[#ffb84d] hover:opacity-90 text-[#004d26] px-6 py-3 rounded-full font-bold transition shadow-sm flex items-center gap-2 text-sm">
            <i class="bi bi-arrow-right text-base"></i>
            <span>رجوع للقائمة</span>
        </a>
    </div>

    <?php if ($message) { ?>
        <div class="bg-red-50 text-red-600 border border-red-200 p-4 rounded-xl font-semibold max-w-2xl text-sm flex items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <?= $message ?>
        </div>
    <?php } ?>

    <div class="w-full bg-[#ffeed6]/40 rounded-3xl shadow-sm border border-[#ffb84d]/30 p-8 max-w-2xl">
        <form method="POST" class="flex flex-col gap-6">

            <div class="flex flex-col gap-1.5">
                <label class="text-[#004d26] font-bold text-sm">اسم التصنيف <span class="text-red-500">*</span></label>
                <input type="text" name="name" placeholder="مثال: السيرة النبوية، الغزوات..."
                       class="w-full p-3.5 bg-white border border-[#ffb84d]/40 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#ffb84d] text-sm font-semibold shadow-sm placeholder:text-slate-400"
                       required>
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="text-[#004d26] font-bold text-sm">الوصف التوضيحي</label>
                <textarea name="description" rows="4" placeholder="اكتب وصفاً مختصراً للكتب التي يضمها هذا القسم..."
                          class="w-full p-3.5 bg-white border border-[#ffb84d]/40 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#ffb84d] text-sm font-medium shadow-sm placeholder:text-slate-400"></textarea>
            </div>

            <button type="submit" name="save"
                    class="w-full bg-[#004d26] text-[#ffeed6] py-3.5 rounded-xl font-bold hover:bg-[#003319] transition shadow-sm flex items-center justify-center gap-2 mt-2 text-sm cursor-pointer">
                <i class="bi bi-check-circle-fill"></i>
                <span>حفظ التصنيف الجديد</span>
            </button>

        </form>
    </div>

</main>

</body>
</html>