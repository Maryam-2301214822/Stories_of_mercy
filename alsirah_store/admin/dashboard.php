<?php
session_start();

// الاتصال بقاعدة البيانات
include "../config/db.php";

// حماية الصفحة
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}


// إجمالي عدد الكتب
$total_books_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM books");
$total_books_row = mysqli_fetch_assoc($total_books_query);
$count = $total_books_row['total'] ?? 0;


// إجمالي المستخدمين
$total_users_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
$total_users_row = mysqli_fetch_assoc($total_users_query);
$total_users = $total_users_row['total'] ?? 0;


// إجمالي التصنيفات
$total_categories_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM categories");
$total_categories_row = mysqli_fetch_assoc($total_categories_query);
$total_categories = $total_categories_row['total'] ?? 0;


// إجمالي المخزون
$total_stock_query = mysqli_query($conn, "SELECT SUM(stock) as total FROM books");
$total_stock_row = mysqli_fetch_assoc($total_stock_query);
$total_stock = $total_stock_row['total'] ?? 0;


// آخر الكتب المضافة
$result_recent = mysqli_query($conn, "
    SELECT books.*, categories.name AS category_name
    FROM books
    LEFT JOIN categories 
    ON books.category_id = categories.id
    ORDER BY books.id DESC
    LIMIT 2
");

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>لوحة التحكم - قصص الرحمة</title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="/alsirah_store/css/style.css">

<style>
body{
    font-family:'Cairo',sans-serif;
}
</style>

</head>


<body class="bg-[#fffbf4]">


 <?php include "sidebar.php"; ?>


   <div class="main-content pr-64 p-6 min-h-screen flex flex-col gap-6 text-right">
      <div class="flex justify-between items-center bg-[#fffbf4] p-4 rounded-2xl border border-[#ffb84d]/30 shadow-sm">
        <h1 class="text-xl font-bold text-[#004d26]">
                  إحصائيات الإدارة والكتب الحالية
        </h1>
        <span class="bg-[#ffb84d] text-[#004d26] px-4 py-1.5 rounded-full text-sm font-bold">
             مدير النظام
        </span>

    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
           <!-- بطاقة المخزون / الكتب -->
         <a href="books/index.php" class="block hover:scale-105 transition-transform duration-200">
            <div class="bg-white border border-[#ffb84d]/30 p-5 rounded-2xl shadow-sm flex flex-row-reverse items-center justify-between cursor-pointer">
             <div class="w-14 h-14 bg-[#ffb84d] rounded-2xl flex items-center justify-center text-2xl text-[#004d26]">
             <i class="bi bi-book"></i>
             </div>
             <div>
             <p class="text-lg text-[#004d26] font-bold text-right">
              عدد الكتب الحالي
              <span class="mr-2 text-3xl text-slate-800"> <?= $count ?> </span>
                      </p>
                      <p class="text-sm text-[#004d26] font-bold mt-2 text-right">
                      إجمالي المخزون: <?= $total_stock ?> نسخة
                     </p>
            </div>
        </div>
</a>
 
<!-- بطاقة المستخدمين -->
 
   <a href="users/index.php" class="block hover:scale-105 transition-transform duration-200">
<div class="bg-white border border-[#ffb84d]/30 p-5 rounded-2xl shadow-sm flex flex-row-reverse items-center justify-between">


<div class="w-14 h-14 bg-[#ffb84d] rounded-2xl flex items-center justify-center text-2xl text-[#004d26]">

<i class="bi bi-people"></i>

</div>



<div>


<p class="text-lg text-[#004d26] font-bold text-right">

عدد المستخدمين

</p>


<h3 class="text-3xl font-bold text-slate-800 mt-1 text-right">

<?= $total_users ?>

</h3>


</div>


</div>


</a>



<!-- بطاقة التصنيفات -->

<a href="categories/index.php" class="block hover:scale-105 transition-transform duration-200">


<div class="bg-white border border-[#ffb84d]/30 p-5 rounded-2xl shadow-sm flex flex-row-reverse items-center justify-between">


<div class="w-14 ح14 bg-[#ffb84d] rounded-2xl flex items-center justify-center text-2xl text-[#004d26]">

<i class="bi bi-grid"></i>

</div>



<div>


<p class="text-lg text-[#004d26] font-bold text-right">

إجمالي التصنيفات

</p>


<h3 class="text-3xl font-bold text-slate-800 mt-1 text-right">

<?= $total_categories ?>

</h3>


</div>


</div>


</a>


</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">


<!-- جدول الكتب الحديثة -->

<div class="lg:col-span-2 bg-[#ffeed6]/40 border border-[#ffb84d]/30 rounded-3xl p-6 shadow-sm overflow-x-auto">


<div class="flex justify-between items-center mb-6">

<h2 class="text-xl font-bold text-[#004d26]">
الكتب المضافة حديثاً
</h2>


<a href="books/index.php"
class="text-sm bg-[#ffb84d] text-[#004d26] px-4 py-1.5 rounded-full font-bold hover:opacity-90">

عرض الكل

</a>

</div>



<table class="w-full text-right border-collapse">


<thead>

<tr class="border-b-2 border-[#ffb84d]/20 text-[#004d26]">

<th class="pb-3">
الكتاب
</th>

<th class="pb-3">
الكاتب
</th>

<th class="pb-3">
التصنيف
</th>

<th class="pb-3">
المخزون
</th>


</tr>

</thead>



<tbody class="text-slate-700 text-sm divide-y divide-[#ffb84d]/10">


<?php if(mysqli_num_rows($result_recent) > 0){ ?>


<?php while($row = mysqli_fetch_assoc($result_recent)){ ?>


<tr class="hover:bg-white/40">


<td class="py-4 font-bold text-slate-900">

<?= $row['title'] ?>

</td>



<td class="py-4">

<?= $row['author'] ?>

</td>



<td class="py-4">


<span class="bg-[#ffb84d]/20 text-[#004d26] px-3 py-1 rounded-md text-xs font-bold">

<?= $row['category_name'] ?? 'عام' ?>

</span>


</td>




<td class="py-4 font-bold text-emerald-700">

<?= $row['stock'] ?> نسخة

</td>



</tr>


<?php } ?>



<?php }else{ ?>


<tr>

<td colspan="4" class="text-center py-8 text-slate-400">

لا توجد كتب مضافة حالياً

</td>

</tr>


<?php } ?>


</tbody>


</table>


</div>






<!-- الإجراءات السريعة -->


<div class="flex flex-col gap-6">


<div class="bg-[#ffeed6]/40 border border-[#ffb84d]/30 rounded-3xl p-6 shadow-sm">


<h2 class="text-lg font-bold text-[#004d26] mb-4">

إجراءات سريعة

</h2>



<div class="grid grid-cols-2 gap-4">



<a href="books/create.php"
class="flex flex-col items-center justify-center p-4 border rounded-2xl bg-[#ffb84d]/10 hover:bg-[#004d26] transition">


<div class="w-12 h-12 bg-[#ffb84d] rounded-xl flex items-center justify-center text-xl text-[#004d26]">

<i class="bi bi-journal-plus"></i>

</div>


<span class="text-xs font-bold text-[#004d26] mt-2">

إضافة كتاب

</span>


</a>





<a href="categories/create.php"
class="flex flex-col items-center justify-center p-4 border rounded-2xl bg-[#ffb84d]/10 hover:bg-[#004d26] transition">


<div class="w-12 h-12 bg-[#ffb84d] rounded-xl flex items-center justify-center text-xl text-[#004d26]">

<i class="bi bi-folder-plus"></i>

</div>


<span class="text-xs font-bold text-[#004d26] mt-2">

إضافة تصنيف

</span>


</a>



</div>


</div>





<div class="bg-[#ffb84d] rounded-2xl p-6 text-center text-[#004d26] border border-[#ffb84d] shadow-sm">

    <p id="libraryText" class="text-sm font-bold leading-relaxed">
        📚 مكتبة المستقبل الرقمية <br>
        نظام متكامل لإدارة الكتب، التصنيفات، وصلاحيات المستخدمين بكل سهولة وسلاسة.
    </p>

</div>



</div>

</div>

</div>
<script>
const text = document.getElementById("libraryText");

text.style.opacity = "0";
text.style.transform = "translateY(20px)";
text.style.transition = "all 1s ease";

window.onload = function () {
    text.style.opacity = "1";
    text.style.transform = "translateY(0)";
};
</script>

</body>

</html>