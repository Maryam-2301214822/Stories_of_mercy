<?php
session_start();
include "../../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM categories ORDER BY id DESC");
$count = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl" translate="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google" content="notranslate">
    <title>إدارة التصنيفات - قصص الرحمة</title>

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

<body class="flex min-h-screen overflow-auto bg-[#fffbf4]">
    <?php include "../sidebar.php"; ?>

    <div class="flex-1 mr-64 p-8 overflow-y-auto flex flex-col gap-6 w-full text-right" dir="rtl">

        <div class="w-full flex justify-between items-center bg-[#fffbf4] p-5 rounded-2xl border border-[#ffb84d]/30 shadow-sm mt-2">
            <div>
                <h2 class="text-2xl font-bold text-[#004d26] flex items-center gap-2">
                    <span>🗂</span> 
                    <span>إدارة التصنيفات</span>
                </h2>
                <p class="text-slate-500 text-sm mt-1">عرض وتحديث تصنيفات ومجموعات الكتب المتاحة بالمخزن</p>
            </div>
            
            <a href="create.php" 
               class="bg-[#ffb84d] hover:opacity-90 text-[#004d26] px-6 py-3 rounded-full text-sm font-bold transition flex items-center gap-2 shadow-sm">
                <i class="bi bi-plus-lg text-base"></i>
                <span>إضافة تصنيف جديد</span>
            </a>
        </div>

              <div class="flex justify-start w-full">
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-[#ffb84d]/30 flex flex-row-reverse items-center justify-between w-64">
                <div class="w-12 h-12 bg-[#ffeed6] border border-[#ffb84d]/40 rounded-xl flex items-center justify-center text-xl text-[#004d26] shadow-sm">
                    <i class="bi bi-tags-fill"></i>
                </div>
                <div class="text-right">
                    <p class="text-xs text-[#004d26] font-bold">إجمالي التصنيفات</p>
                    <p class="text-2xl font-extrabold text-slate-800 mt-0.5"><?= $count ?></p>
                </div>
            </div>
        </div>

        <div class="w-full bg-[#ffeed6]/40 rounded-3xl shadow-sm border border-[#ffb84d]/30 overflow-hidden mb-8 p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-right border-collapse">
                    <thead>
                        <tr class="border-b-2 border-[#ffb84d]/20 text-[#004d26] text-base font-bold">
                            <th class="pb-3 font-bold text-right w-24">المعرف (ID)</th>
                            <th class="pb-3 font-bold text-right w-1/4">اسم التصنيف</th>
                            <th class="pb-3 font-bold text-right">الوصف التوضيحي</th>
                            <th class="pb-3 font-bold text-center w-48">العمليات والإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-700 text-sm divide-y divide-[#ffb84d]/10">
                        <?php if ($count > 0) { ?>
                            <?php while($row = mysqli_fetch_assoc($result)) { ?>
                                <tr class="hover:bg-white/40 transition-colors">
                                    
                                    <td class="py-4 text-right font-medium text-slate-400 font-mono">#<?= $row['id'] ?></td>
                                    
                                    <td class="py-4 text-right font-bold text-slate-800">
                                        <span class="bg-[#ffb84d]/20 text-[#004d26] border border-[#ffb84d]/30 px-3 py-1 rounded-md text-xs font-bold inline-block">
                                            <?= ($row['name']) ?>
                                        </span>
                                    </td>
                                    
                                    <td class="py-4 text-right text-slate-600 font-medium text-sm leading-relaxed">
                                        <?=  ($row['description']) ?>
                                    </td>

                                    <td class="py-4 text-center">
                                        <div class="flex justify-center items-center gap-2">
                                            <a href="edit.php?id=<?= $row['id'] ?>" 
                                               class="text-blue-600 hover:text-blue-800 font-bold transition-colors flex items-center gap-1 text-xs bg-blue-50 px-3 py-1.5 rounded-md border border-blue-100" 
                                               title="تعديل">
                                                <i class="bi bi-pencil-square text-sm"></i> تعديل
                                            </a>
                                            <a href="delete.php?id=<?= $row['id'] ?>" 
                                               onclick="return confirm('هل أنتِ متأكدة من رغبتك في حذف هذا التصنيف نهائياً؟')" 
                                               class="text-red-600 hover:text-red-800 font-bold transition-colors flex items-center gap-1 text-xs bg-red-50 px-3 py-1.5 rounded-md border border-red-100" 
                                               title="حذف">
                                                <i class="bi bi-trash3 text-sm"></i> حذف
                                            </a>
                                        </div>
                                    </td>

                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="4" class="p-12 text-center text-slate-400 font-medium">
                                    <i class="bi bi-folder-x text-3xl block mb-2 text-slate-300"></i>
                                    لا توجد تصنيفات مضافة حالياً في النظام.
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