<?php

session_start();

include "../../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}


// التحقق من ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}


$id = intval($_GET['id']);


// جلب بيانات التصنيف
$stmt = $conn->prepare("SELECT * FROM categories WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$category = $result->fetch_assoc();


if (!$category) {
    header("Location: index.php");
    exit();
}


$message = "";



// تحديث البيانات
if (isset($_POST['update'])) {


    $name = trim($_POST['name']);
    $description = trim($_POST['description']);


    $stmt = $conn->prepare(
        "UPDATE categories SET name=?, description=? WHERE id=?"
    );


    $stmt->bind_param(
        "ssi",
        $name,
        $description,
        $id
    );


    if ($stmt->execute()) {


        $message = "✅ تم تحديث التصنيف بنجاح";


        // تحديث البيانات المعروضة
        $stmt = $conn->prepare(
            "SELECT * FROM categories WHERE id=?"
        );

        $stmt->bind_param("i",$id);
        $stmt->execute();

        $result = $stmt->get_result();
        $category = $result->fetch_assoc();


    } else {

        $message = "❌ حدث خطأ أثناء التحديث";

    }

}

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>تعديل تصنيف - قصص الرحمة</title>


<script src="https://cdn.tailwindcss.com"></script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">


<style>

body{
font-family:'Cairo',sans-serif;
background:#fffbf4;
}


</style>

</head>


<body class="flex h-screen overflow-hidden">


<?php include "../sidebar.php"; ?>


<main class="flex-1 mr-64 p-8 overflow-y-auto">


<div class="bg-[#fffbf4] rounded-2xl p-5 mb-6 border border-[#ffb84d]/30 shadow-sm flex justify-between items-center">


<div>

<h1 class="text-2xl font-bold text-[#004d26] flex items-center gap-2">

<i class="bi bi-pencil-square text-[#ffb84d]"></i>

تعديل بيانات التصنيف

</h1>


<p class="text-sm text-gray-500 mt-1">
تحديث معلومات القسم وتصنيف الكتب
</p>


</div>



<a href="index.php"
class="bg-[#ffb84d] text-[#004d26] px-5 py-2 rounded-full font-bold text-sm">

<i class="bi bi-arrow-right"></i>

رجوع

</a>



</div>



<?php if($message){ ?>

<div class="bg-green-50 text-green-700 border border-green-200 p-4 rounded-xl mb-5 font-bold">

<?= $message ?>

</div>

<?php } ?>




<div class="bg-white rounded-3xl p-8 shadow-sm border border-[#ffb84d]/30 max-w-3xl">


<form method="POST" class="flex flex-col gap-5">


<div>

<label class="font-bold text-[#004d26]">

اسم التصنيف

</label>


<input 
type="text"
name="name"
value="<?= htmlspecialchars($category['name']) ?>"
required

class="w-full mt-2 p-3 rounded-xl border border-gray-200 focus:border-[#ffb84d] outline-none">


</div>



<div>

<label class="font-bold text-[#004d26]">

الوصف

</label>


<textarea
name="description"
rows="5"
required

class="w-full mt-2 p-3 rounded-xl border border-gray-200 focus:border-[#ffb84d] outline-none"
><?= htmlspecialchars($category['description']) ?></textarea>


</div>




<button 
name="update"
type="submit"

class="bg-[#ffb84d] text-[#004d26] py-3 rounded-xl font-bold hover:opacity-90">

<i class="bi bi-check-circle"></i>

حفظ التعديلات

</button>



</form>


</div>



</main>


</body>

</html>