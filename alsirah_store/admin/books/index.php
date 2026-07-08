<?php
session_start();
include "../../config/db.php";

// حماية الصفحة
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

// جلب الكتب مع التصنيفات
$result = mysqli_query($conn, " SELECT books.*, categories.name
 AS category_name  FROM books  LEFT JOIN categories
  ON books.category_id = categories.id  ORDER BY books.id DESC
");

$count = mysqli_num_rows($result);

// حساب إجمالي المخزون
$total_stock_query = mysqli_query($conn, "SELECT SUM(stock) as total FROM books");
$total_stock_row = mysqli_fetch_assoc($total_stock_query);
$total_stock = $total_stock_row['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الكتب - قصص الرحمة</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/alsirah_store/css/style.css">
  
</head>

<body class="bg-[#fffbf4] text-right overflow-x-hidden">

<?php include "../sidebar.php"; ?>

<div class="main-content pr-64 p-6 min-h-screen flex flex-col gap-6 text-right" dir="rtl">

    <div class="w-full flex flex-row justify-between items-center bg-[#fffbf4] p-5 rounded-2xl border border-[#ffb84d]/30 shadow-sm mt-2">
        <div>
            <h2 class="text-2xl font-bold text-[#004d26]">إدارة الكتب</h2>
            <p class="text-slate-500 text-sm mt-1">عرض وإدارة جميع كتب المتجر وتعديلها</p>
        </div>

        <a href="create.php"
           class="bg-[#ffb84d] hover:opacity-90 text-[#004d26] px-5 py-2.5 rounded-full font-bold transition shadow-sm flex items-center gap-2 text-sm">
            <i class="bi bi-plus-lg"></i> إضافة كتاب جديد
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full">
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-[#ffb84d]/30 flex flex-row-reverse items-center justify-between">
            <div class="w-14 h-14 bg-[#ffb84d] border border-[#ffb84d]/40 rounded-2xl flex items-center justify-center text-2xl text-[#004d26] shadow-sm">
                <i class="bi bi-book-half"></i>
            </div>
            <div>
                <p class="text-base text-[#004d26] font-bold text-right">عدد الكتب الحالي</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-1 text-right"><?= $count ?></h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-[#ffb84d]/30 flex flex-row-reverse items-center justify-between">
            <div class="w-14 h-14 bg-[#ffb84d] border border-[#ffb84d]/40 rounded-2xl flex items-center justify-center text-2xl text-[#004d26] shadow-sm">
                <i class="bi bi-archive-fill"></i>
            </div>
            <div>
                <p class="text-base text-[#004d26] font-bold text-right">إجمالي المخزون</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-1 text-right"><?= $total_stock ?></h3>
            </div>
        </div>
    </div>

    <div class="w-full bg-[#ffeed6]/40 rounded-3xl shadow-sm border border-[#ffb84d]/30 overflow-hidden mb-8 p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-right border-collapse">
                <thead>
                    <tr class="border-b-2 border-[#ffb84d]/20 text-[#004d26] text-base font-bold">
                        <th class="pb-3 font-bold text-right">اسم الكتاب</th>
                        <th class="pb-3 font-bold text-right">المؤلف</th>
                        <th class="pb-3 font-bold text-right">التصنيف</th>
                        <th class="pb-3 font-bold text-right">السعر</th>
                        <th class="pb-3 font-bold text-right">المخزون بالمستودع</th>
                        <th class="pb-3 font-bold text-center w-32">العمليات والإجراءات</th>
                    </tr>
                </thead>

                <tbody class="text-slate-700 text-sm divide-y divide-[#ffb84d]/10">
                    <?php if (mysqli_num_rows($result) > 0) { ?>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr class="hover:bg-white/40 transition-colors">
                            <td class="py-4 text-right font-bold text-slate-900">
                                <?= htmlspecialchars($row['title']) ?>
                            </td>

                            <td class="py-4 text-right text-slate-600">
                                <? ($row['author']) ?>
                            </td>

                            <td class="py-4 text-right">
                                <span class="bg-[#ffb84d]/20 text-[#004d26] border border-[#ffb84d]/30 px-3 py-1 rounded-md text-xs font-bold inline-block">
                                    <?=($row['category_name'] ?? 'عام') ?>
                                </span>
                            </td>

                            <td class="py-4 text-right font-bold text-slate-800">
                                <?= number_format($row['price'], 2) ?> ل.س
                            </td>

                            <td class="py-4 text-right">
                                <?php if ($row['stock'] > 0) { ?>
                                    <span class="text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1 rounded-full text-xs font-bold inline-flex items-center gap-1">
                                        <i class="bi bi-check-circle-fill"></i> <?= $row['stock'] ?> نسخة متاح
                                    </span>
                                <?php } else { ?>
                                    <span class="text-red-600 bg-red-50 border border-red-200 px-3 py-1 rounded-full text-xs font-bold inline-flex items-center gap-1">
                                        <i class="bi bi-x-circle-fill"></i> نفذت الكمية
                                    </span>
                                <?php } ?>
                            </td>

                            <td class="py-4 text-center">
                                <div class="flex justify-center items-center gap-2">
                                    <a href="edit.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:text-blue-800 font-semibold transition-colors flex items-center gap-1 text-xs bg-blue-50 px-2 py-1 rounded-md border border-blue-100" title="تعديل">
                                        <i class="bi bi-pencil-square"></i> تعديل
                                    </a>
                                    <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('هل أنتِ متأكدة من حذف هذا الكتاب نهائياً؟')" class="text-red-600 hover:text-red-800 font-semibold transition-colors flex items-center gap-1 text-xs bg-red-50 px-2 py-1 rounded-md border border-red-100" title="حذف">
                                        <i class="bi bi-trash3"></i> حذف
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="6" class="p-12 text-center text-slate-400 font-medium">
                                <i class="bi bi-inbox text-3xl block mb-2 text-slate-300"></i>
                                لا توجد كتب مضافة حالياً في قاعدة البيانات.
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

</body>
</html>