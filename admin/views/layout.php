<?php defined('ZVELE_CMS') or die(); ?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?= e($pageTitle ?? 'Admin') ?> — ZveleCMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="<?= asset('admin/assets/admin.css') ?>">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        sidebar: '#1e293b',
                        accent: '#2563eb',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen" x-data="{ sidebarOpen: window.innerWidth >= 1024 }">

    <!-- Mobile sidebar overlay -->
    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-200"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-200"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/50 z-30 lg:hidden" @click="sidebarOpen = false"></div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed top-0 left-0 z-40 w-64 h-screen bg-sidebar transition-transform duration-200 lg:translate-x-0">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-700">
            <a href="<?= url('admin') ?>" class="text-white font-bold text-lg">ZveleCMS</a>
            <button @click="sidebarOpen = false" class="text-slate-400 hover:text-white lg:hidden">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <nav class="px-3 py-4 space-y-1">
            <?php
            $navItems = [
                ['dashboard', 'Nástěnka', 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                ['pages', 'Stránky', 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                ['posts', 'Blog', 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z'],
                ['media', 'Média', 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
                ['forms', 'Formuláře', 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                ['menus', 'Navigace', 'M4 6h16M4 12h16M4 18h16'],
                ['settings', 'Nastavení', 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
                ['redirects', 'Přesměrování', 'M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1'],
                ['users', 'Uživatelé', 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
            ];
            $currentSection = $section ?? 'dashboard';
            foreach ($navItems as [$href, $label, $icon]):
                $isActive = $currentSection === $href;
            ?>
            <a href="<?= url('admin/' . $href) ?>"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors <?= $isActive ? 'bg-accent text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' ?>">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="<?= $icon ?>"/>
                </svg>
                <?= e($label) ?>
            </a>
            <?php endforeach; ?>
        </nav>

        <div class="absolute bottom-0 left-0 right-0 px-3 py-4 border-t border-slate-700">
            <a href="<?= url('/') ?>" target="_blank"
               class="flex items-center gap-2 px-3 py-2 text-sm text-slate-400 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                Zobrazit web
            </a>
        </div>
    </aside>

    <!-- Main content -->
    <div class="lg:ml-64">
        <!-- Top bar -->
        <header class="sticky top-0 z-20 bg-white border-b border-gray-200 px-4 sm:px-6 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-700 lg:hidden">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <h1 class="text-lg font-semibold text-gray-900"><?= e($pageTitle ?? 'Admin') ?></h1>
                </div>

                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-500 hidden sm:inline"><?= e(Auth::name()) ?></span>
                    <a href="<?= url('admin/logout') ?>"
                       class="text-sm text-gray-500 hover:text-red-600 transition-colors">Odhlásit</a>
                </div>
            </div>
        </header>

        <!-- Flash messages -->
        <?php $flash = flash(); if ($flash): ?>
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             class="mx-4 sm:mx-6 mt-4">
            <div class="rounded-lg px-4 py-3 text-sm flex items-center justify-between
                <?= $flash['type'] === 'success' ? 'bg-green-50 text-green-800 border border-green-200' : '' ?>
                <?= $flash['type'] === 'error' ? 'bg-red-50 text-red-800 border border-red-200' : '' ?>
                <?= $flash['type'] === 'warning' ? 'bg-yellow-50 text-yellow-800 border border-yellow-200' : '' ?>">
                <span><?= e($flash['message']) ?></span>
                <button @click="show = false" class="ml-4 opacity-50 hover:opacity-100">&times;</button>
            </div>
        </div>
        <?php endif; ?>

        <!-- Page content -->
        <main class="p-4 sm:p-6">
            <?= $content ?? '' ?>
        </main>
    </div>

    <script src="<?= asset('admin/assets/admin.js') ?>"></script>
</body>
</html>
