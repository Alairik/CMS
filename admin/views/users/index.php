<?php defined('ZVELE_CMS') or die(); ?>

<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500"><?= count($users) ?> uživatelů</p>
    <a href="<?= url('admin/users/create') ?>" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Nový uživatel
    </a>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead><tr class="bg-gray-50 border-b border-gray-200">
            <th class="text-left px-5 py-3 font-medium text-gray-500">Jméno</th>
            <th class="text-left px-5 py-3 font-medium text-gray-500">E-mail</th>
            <th class="text-left px-5 py-3 font-medium text-gray-500">Role</th>
            <th class="text-left px-5 py-3 font-medium text-gray-500 hidden md:table-cell">Poslední přihlášení</th>
            <th class="text-right px-5 py-3 font-medium text-gray-500">Akce</th>
        </tr></thead>
        <tbody class="divide-y divide-gray-100">
            <?php foreach ($users as $u): ?>
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3 font-medium text-gray-900"><?= e($u['name']) ?></td>
                <td class="px-5 py-3 text-gray-500"><?= e($u['email']) ?></td>
                <td class="px-5 py-3">
                    <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full <?= $u['role'] === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-600' ?>">
                        <?= e($u['role']) ?>
                    </span>
                </td>
                <td class="px-5 py-3 text-gray-500 text-xs hidden md:table-cell"><?= $u['last_login'] ? e(format_date($u['last_login'])) : '—' ?></td>
                <td class="px-5 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="<?= url('admin/users/edit/' . $u['id']) ?>" class="text-gray-400 hover:text-blue-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <?php if ((int)$u['id'] !== Auth::id()): ?>
                        <form method="POST" action="<?= url('admin/users/delete/' . $u['id']) ?>" class="inline">
                            <?= csrf_field() ?>
                            <button type="submit" data-confirm="Smazat uživatele?" class="text-gray-400 hover:text-red-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
