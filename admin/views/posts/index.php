<?php defined('ZVELE_CMS') or die(); ?>

<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500"><?= count($posts) ?> postů celkem</p>
    <a href="<?= url('admin/posts/create') ?>"
       class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nový post
    </a>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <?php if (empty($posts)): ?>
        <div class="px-6 py-12 text-center">
            <p class="text-gray-500 mb-4">Zatím nemáte žádné posty.</p>
            <a href="<?= url('admin/posts/create') ?>" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Vytvořit první post &rarr;</a>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="text-left px-5 py-3 font-medium text-gray-500">Název</th>
                        <th class="text-left px-5 py-3 font-medium text-gray-500 hidden md:table-cell">Autor</th>
                        <th class="text-left px-5 py-3 font-medium text-gray-500 hidden md:table-cell">Kategorie</th>
                        <th class="text-left px-5 py-3 font-medium text-gray-500">Status</th>
                        <th class="text-left px-5 py-3 font-medium text-gray-500 hidden lg:table-cell">Datum</th>
                        <th class="text-right px-5 py-3 font-medium text-gray-500">Akce</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach ($posts as $p): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3">
                            <a href="<?= url('admin/posts/edit/' . $p['id']) ?>" class="font-medium text-gray-900 hover:text-blue-600">
                                <?= e($p['title']) ?>
                            </a>
                            <?php if ($p['featured']): ?>
                                <span class="ml-1 text-xs text-amber-600">&#9733;</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-5 py-3 text-gray-500 hidden md:table-cell"><?= e($p['author_name'] ?? '') ?></td>
                        <td class="px-5 py-3 text-gray-500 hidden md:table-cell"><?= e($p['category'] ?? '—') ?></td>
                        <td class="px-5 py-3">
                            <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full
                                <?= $p['status'] === 'published' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' ?>">
                                <?= $p['status'] === 'published' ? 'Publikováno' : 'Koncept' ?>
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-500 text-xs hidden lg:table-cell">
                            <?= e(format_date($p['published_at'] ?? $p['created_at'])) ?>
                        </td>
                        <td class="px-5 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <?php if ($p['status'] === 'published'): ?>
                                <a href="<?= url('blog/' . $p['slug']) ?>" target="_blank" class="text-gray-400 hover:text-gray-600" title="Zobrazit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                                <?php endif; ?>
                                <a href="<?= url('admin/posts/edit/' . $p['id']) ?>" class="text-gray-400 hover:text-blue-600" title="Upravit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form method="POST" action="<?= url('admin/posts/delete/' . $p['id']) ?>" class="inline">
                                    <?= csrf_field() ?>
                                    <button type="submit" data-confirm="Opravdu chcete smazat post &quot;<?= e($p['title']) ?>&quot;?" class="text-gray-400 hover:text-red-600" title="Smazat">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
