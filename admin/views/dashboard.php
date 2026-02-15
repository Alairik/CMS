<?php defined('ZVELE_CMS') or die(); ?>

<!-- Stats grid -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="text-sm font-medium text-gray-500">Stránky</div>
        <div class="mt-1 text-2xl font-bold text-gray-900"><?= (int)$stats['published_pages'] ?></div>
        <div class="text-xs text-gray-400 mt-1"><?= (int)$stats['pages'] ?> celkem</div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="text-sm font-medium text-gray-500">Blog posty</div>
        <div class="mt-1 text-2xl font-bold text-gray-900"><?= (int)$stats['published_posts'] ?></div>
        <div class="text-xs text-gray-400 mt-1"><?= (int)$stats['posts'] ?> celkem</div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="text-sm font-medium text-gray-500">Média</div>
        <div class="mt-1 text-2xl font-bold text-gray-900"><?= (int)$stats['media'] ?></div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="text-sm font-medium text-gray-500">Nepřečtené zprávy</div>
        <div class="mt-1 text-2xl font-bold <?= $stats['unread_submissions'] > 0 ? 'text-blue-600' : 'text-gray-900' ?>">
            <?= (int)$stats['unread_submissions'] ?>
        </div>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <!-- Recent posts -->
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-900">Poslední posty</h2>
            <a href="<?= url('admin/posts') ?>" class="text-sm text-blue-600 hover:text-blue-800">Zobrazit vše</a>
        </div>
        <div class="divide-y divide-gray-100">
            <?php if (empty($recentPosts)): ?>
                <div class="px-5 py-8 text-center text-sm text-gray-400">Zatím žádné posty.</div>
            <?php else: ?>
                <?php foreach ($recentPosts as $post): ?>
                <a href="<?= url('admin/posts/edit/' . $post['id']) ?>" class="flex items-center justify-between px-5 py-3 hover:bg-gray-50 transition-colors">
                    <div>
                        <div class="text-sm font-medium text-gray-900"><?= e($post['title']) ?></div>
                        <div class="text-xs text-gray-400 mt-0.5">
                            <?= e($post['author_name'] ?? '') ?> &middot; <?= e(format_date($post['created_at'])) ?>
                        </div>
                    </div>
                    <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full
                        <?= $post['status'] === 'published' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' ?>">
                        <?= $post['status'] === 'published' ? 'Publikováno' : 'Koncept' ?>
                    </span>
                </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Recent form submissions -->
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-900">Poslední zprávy</h2>
            <a href="<?= url('admin/forms') ?>" class="text-sm text-blue-600 hover:text-blue-800">Zobrazit vše</a>
        </div>
        <div class="divide-y divide-gray-100">
            <?php if (empty($recentSubmissions)): ?>
                <div class="px-5 py-8 text-center text-sm text-gray-400">Zatím žádné zprávy.</div>
            <?php else: ?>
                <?php foreach ($recentSubmissions as $sub): ?>
                <div class="flex items-center justify-between px-5 py-3">
                    <div>
                        <div class="text-sm font-medium text-gray-900">
                            <?php if (!$sub['is_read']): ?>
                                <span class="inline-block w-2 h-2 bg-blue-500 rounded-full mr-1.5"></span>
                            <?php endif; ?>
                            <?= e($sub['form_name'] ?? 'Formulář') ?>
                        </div>
                        <div class="text-xs text-gray-400 mt-0.5"><?= e(format_date($sub['created_at'])) ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
