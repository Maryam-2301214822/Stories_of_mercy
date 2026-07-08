<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include "../../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

// جلب كل المستخدمين (أدمن + زبائن)
$result = mysqli_query($conn, "
    SELECT *
    FROM users
    ORDER BY id DESC
");

$count = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المستخدمين - قصص الرحمة</title>

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

    <div class="flex-1 mr-64 p-8 overflow-y-auto flex flex-col gap-6 w-full text-right" dir="rtl">

        <div class="w-full flex justify-between items-center bg-[#fffbf4] p-5 rounded-2xl border border-[#ffb84d]/30 shadow-sm mt-2">
            <div>
                <h2 class="text-2xl font-bold text-[#004d26] flex items-center gap-2">
                    <span>👥</span> 
                    <span>إدارة المستخدمين</span>
                </h2>
                <p class="text-slate-500 text-sm mt-1">عرض ومراقبة جميع الحسابات المسجلة بالنظام (أدمن + زبائن)</p>
            </div>

            <div class="bg-[#ffeed6] border border-[#ffb84d]/30 px-5 py-2.5 rounded-full text-sm font-bold text-[#004d26] shadow-sm">
                إجمالي المستخدمين:
                <span class="font-extrabold text-slate-900 mr-1"><?= $count ?></span>
            </div>
        </div>

        <div class="w-full bg-[#ffeed6]/40 rounded-3xl shadow-sm border border-[#ffb84d]/30 overflow-hidden mb-8 p-6">
            <table class="w-full text-sm text-right border-collapse">
                <thead>
                    <tr class="border-b-2 border-[#ffb84d]/20 text-[#004d26] text-base font-bold">
                        <th class="pb-3 font-bold text-right w-20">ID</th>
                        <th class="pb-3 font-bold text-right">الاسم الكامل</th>
                        <th class="pb-3 font-bold text-center">البريد الإلكتروني</th>
                        <th class="pb-3 font-bold text-center">صلاحية الحساب</th>
                        <th class="pb-3 font-bold text-center">تاريخ التسجيل</th>
                        <th class="pb-3 font-bold text-center w-32">العمليات</th>
                    </tr>
                </thead>

                <tbody class="text-slate-700 text-sm divide-y divide-[#ffb84d]/10">

                <?php if ($count > 0) { ?>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr class="hover:bg-white/40 transition-colors">

                            <td class="py-4 text-right font-medium text-slate-400 font-mono">
                                #<?= (int)$row['id'] ?>
                            </td>

                            <td class="py-4 font-bold text-slate-900">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-[#ffb84d]/30 border border-[#ffb84d]/40 flex items-center justify-center text-sm font-bold text-[#004d26]">
                                        <?= mb_substr($row['name'] ?? 'U', 0, 1, 'utf-8') ?>
                                    </div>
                                    <span class="text-slate-800"><?= htmlspecialchars($row['name'] ?? 'بدون اسم') ?></span>
                                </div>
                            </td>

                            <td class="py-4 text-center text-slate-600 font-medium">
                                <?= htmlspecialchars($row['email']) ?>
                            </td>

                            <td class="py-4 text-center">
                                <?php if ($row['role'] == 'admin' || (isset($row['role']) && $row['role'] == 1)) { ?>
                                    <span class="bg-red-50 text-red-700 border border-red-200 px-3 py-1 rounded-full text-xs font-bold inline-block">
                                        <i class="bi bi-shield-lock-fill ml-0.5"></i> أدمن
                                    </span>
                                <?php } else { ?>
                                    <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 px-3 py-1 rounded-full text-xs font-bold inline-block">
                                        <i class="bi bi-person-fill ml-0.5"></i> زبون
                                    </span>
                                <?php } ?>
                            </td>

                            <td class="py-4 text-center text-slate-500 font-medium">
                                <?= !empty($row['created_at']) ? date('Y-m-d', strtotime($row['created_at'])) : '—' ?>
                            </td>

                            <td class="py-4 text-center">
                                <?php if ($row['role'] != 'admin' && $row['role'] != 1) { ?>
                                    <a href="delete.php?id=<?= (int)$row['id'] ?>"
                                       onclick="return confirm('هل أنت متأكد من رغبتك في حذف هذا الحساب نهائياً؟')"
                                       class="text-red-600 hover:text-red-800 font-semibold transition-colors flex items-center justify-center gap-1 text-xs bg-red-50 px-2.5 py-1 rounded-md border border-red-100 mx-auto w-fit"
                                       title="حذف حساب">
                                        <i class="bi bi-trash3"></i> حذف
                                    </a>
                                <?php } else { ?>
                                    <span class="text-slate-400 text-xs font-bold bg-slate-100 border border-slate-200 px-2.5 py-1 rounded-md inline-flex items-center gap-1">
                                        <i class="bi bi-lock-fill text-slate-400"></i> محمي
                                    </span>
                                <?php } ?>
                            </td>

                        </tr>
                    <?php } ?>
                <?php } else { ?>

                    <tr>
                        <td colspan="6" class="p-12 text-center text-slate-400 font-medium">
                            <i class="bi bi-people text-3xl block mb-2 text-slate-300"></i>
                            لا يوجد مستخدمين مسجلين في النظام حالياً.
                        </td>
                    </tr>

                <?php } ?>

                </tbody>
            </table>
        </div>

    </div>

</body>
</html>