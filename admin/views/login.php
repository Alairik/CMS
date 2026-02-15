<?php defined('ZVELE_CMS') or die(); ?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Přihlášení — ZveleCMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-sm">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900">ZveleCMS</h1>
            <p class="text-gray-500 text-sm mt-1">Přihlaste se do administrace</p>
        </div>

        <?php $flash = flash(); if ($flash): ?>
        <div class="mb-4 rounded-lg px-4 py-3 text-sm
            <?= $flash['type'] === 'error' ? 'bg-red-50 text-red-800 border border-red-200' : '' ?>
            <?= $flash['type'] === 'success' ? 'bg-green-50 text-green-800 border border-green-200' : '' ?>">
            <?= e($flash['message']) ?>
        </div>
        <?php endif; ?>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <form method="POST" action="<?= url('admin/login') ?>">
                <?= csrf_field() ?>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
                    <input type="email" id="email" name="email" required autofocus
                           value="<?= e($_POST['email'] ?? '') ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Heslo</label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                </div>

                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4
                               rounded-lg text-sm transition-colors focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Přihlásit se
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-gray-400 mt-6">
            <a href="<?= url('/') ?>" class="hover:text-gray-600 transition-colors">&larr; Zpět na web</a>
        </p>
    </div>
</body>
</html>
