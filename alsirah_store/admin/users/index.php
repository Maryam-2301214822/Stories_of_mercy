<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include "../../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

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


<body class="flex min-h-screen overflow-auto bg-[#fffbf4]">

<?php include "../sidebar.php"; ?>


<div class="flex-1 mr-64 p-8 overflow-y-auto flex flex-col gap-6 w-full text-right" dir="rtl">


    <!-- العنوان -->
    <div class="w-full flex justify-between items-center bg-[#fffbf4] p-5 rounded-2xl border border-[#ffb84d]/30 shadow-sm mt-2">

        <div>

            <h2 class="text-2xl font-bold text-[#004d26] flex items-center gap-2">
                <span>👥</span>
                إدارة المستخدمين
            </h2>

            <p class="text-slate-500 text-sm mt-1">
                عرض ومراقبة جميع الحسابات المسجلة بالنظام (أدمن + زبائن)
            </p>

        </div>

    </div>



    <!-- البطاقة -->
    <div class="flex justify-start w-full">

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-[#ffb84d]/30 flex flex-row-reverse items-center justify-between w-64">


            <div class="w-12 h-12 bg-[#ffeed6] border border-[#ffb84d]/40 rounded-xl flex items-center justify-center text-xl text-[#004d26] shadow-sm">

                <i class="bi bi-people-fill"></i>

            </div>


            <div class="text-right">

                <p class="text-xs text-[#004d26] font-bold">
                    إجمالي المستخدمين
                </p>


                <p class="text-2xl font-extrabold text-slate-800 mt-0.5">
                    <?= $count ?>
                </p>


            </div>


        </div>

    </div>




    <!-- الجدول -->

    <div class="w-full bg-[#ffeed6]/40 rounded-3xl shadow-sm border border-[#ffb84d]/30 overflow-hidden mb-8 p-6">


        <div class="overflow-x-auto">


        <table class="w-full text-sm text-right border-collapse">


            <thead>

                <tr class="border-b-2 border-[#ffb84d]/20 text-[#004d26] text-base font-bold">

                    <th class="pb-3 text-right w-20">
                        ID
                    </th>


                    <th class="pb-3 text-right">
                        الاسم الكامل
                    </th>


                    <th class="pb-3 text-center">
                        البريد الإلكتروني
                    </th>


                    <th class="pb-3 text-center">
                        صلاحية الحساب
                    </th>


                    <th class="pb-3 text-center">
                        تاريخ التسجيل
                    </th>


                    <th class="pb-3 text-center w-32">
                        العمليات
                    </th>


                </tr>

            </thead>



            <tbody class="text-slate-700 text-sm divide-y divide-[#ffb84d]/10">



            <?php if($count > 0){ ?>


                <?php while($row = mysqli_fetch_assoc($result)){ ?>


                <tr class="hover:bg-white/40 transition-colors">


                    <td class="py-4 text-right font-medium text-slate-400 font-mono">

                        #<?= $row['id'] ?>

                    </td>



                    <td class="py-4 font-bold text-slate-900">

                        <div class="flex items-center gap-3">


                            <div class="w-9 h-9 rounded-full bg-[#ffb84d]/30 border border-[#ffb84d]/40 flex items-center justify-center text-sm font-bold text-[#004d26]">

                                <?= mb_substr($row['name'] ?? 'U',0,1,'UTF-8') ?>

                            </div>


                            <span>

                                <?= htmlspecialchars($row['name'] ?? 'بدون اسم') ?>

                            </span>


                        </div>

                    </td>




                    <td class="py-4 text-center text-slate-600">

                        <?= htmlspecialchars($row['email']) ?>

                    </td>




                    <td class="py-4 text-center">


                        <?php if(isset($row['role']) && ($row['role']=='admin' || $row['role']==1)){ ?>


                            <span class="bg-red-50 text-red-700 border border-red-200 px-3 py-1 rounded-full text-xs font-bold">

                                <i class="bi bi-shield-lock-fill"></i>
                                أدمن

                            </span>


                        <?php } else { ?>


                            <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 px-3 py-1 rounded-full text-xs font-bold">

                                <i class="bi bi-person-fill"></i>
                                زبون

                            </span>


                        <?php } ?>


                    </td>




                    <td class="py-4 text-center text-slate-500">

                        <?= !empty($row['created_at']) ? date('Y-m-d',strtotime($row['created_at'])) : '—' ?>

                    </td>





                    <td class="py-4 text-center">


                        <?php if($row['role']!='admin' && $row['role']!=1){ ?>


                        <a href="delete.php?id=<?= $row['id'] ?>"
                           onclick="return confirm('هل أنت متأكد من حذف هذا الحساب؟')"
                           class="text-red-600 bg-red-50 border border-red-100 px-3 py-1 rounded-md text-xs font-bold">

                            <i class="bi bi-trash3"></i>
                            حذف

                        </a>


                        <?php } else { ?>


                            <span class="text-slate-400 bg-slate-100 border px-3 py-1 rounded-md text-xs font-bold">

                                <i class="bi bi-lock-fill"></i>
                                محمي

                            </span>


                        <?php } ?>


                    </td>



                </tr>


                <?php } ?>


            <?php } else { ?>


                <tr>

                    <td colspan="6" class="p-12 text-center text-slate-400">

                        لا يوجد مستخدمين مسجلين حالياً.

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
