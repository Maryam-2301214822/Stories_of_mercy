<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الكتب</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/alsirah_store/css/style.css">
    <style> body { font-family: 'Cairo', sans-serif; } </style>
</head>

<aside class="w-60 bg-gradient-to-b from-[#004D40] to-[#00332c] text-emerald-100 fixed right-0 top-0 h-full p-4 flex flex-col justify-between shadow-2xl border-l border-emerald-900/40 z-50">
    <div>
        <div class="flex items-center gap-2 mb-6 border-b border-emerald-800 pb-4">
            <span class="text-xl">📚</span>
            <h1 class="text-white font-bold text-lg">قصص الرحمة</h1>
        </div>

        <nav class="space-y-2">

            <a href="/alsirah_store/admin/dashboard.php" class="block p-3 hover:bg-emerald-800/60 rounded-xl transition-all duration-200 font-medium hover:text-white flex items-center gap-2">
                <i class="bi bi-speedometer2"></i> لوحة التحكم
            </a>

            <a href="/alsirah_store/admin/books/index.php" class="block p-3 hover:bg-emerald-800/60 rounded-xl transition-all duration-200 font-medium hover:text-white flex items-center gap-2">
                <i class="bi bi-book"></i> الكتب
            </a>

            <a href="/alsirah_store/admin/users/index.php" class="block p-3 hover:bg-emerald-800/60 rounded-xl transition-all duration-200 font-medium hover:text-white flex items-center gap-2">
                <i class="bi bi-people"></i> المستخدمين
            </a>

            <a href="/alsirah_store/admin/categories/index.php" class="block p-3 hover:bg-emerald-800/60 rounded-xl transition-all duration-200 font-medium hover:text-white flex items-center gap-2">
                <i class="bi bi-grid"></i> التصنيفات
            </a>

        </nav>
    </div>

    <a href="/alsirah_store/login.php" class="text-rose-300 p-3 hover:bg-rose-950/40 rounded-xl font-medium transition-all duration-200 flex items-center gap-2 mt-auto border border-dashed border-rose-900/30">
        <i class="bi bi-box-arrow-right"></i> تسجيل خروج
    </a>

</aside>