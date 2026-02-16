<?php defined('ZVELE_CMS') or die(); ?>

<div class="flex gap-2 mb-6">
    <a href="<?= url('admin/menus/edit/main') ?>"
       class="px-4 py-2 text-sm font-medium rounded-lg <?= $location === 'main' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
        Hlavní menu
    </a>
    <a href="<?= url('admin/menus/edit/footer') ?>"
       class="px-4 py-2 text-sm font-medium rounded-lg <?= $location === 'footer' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
        Patička
    </a>
</div>

<div x-data="menuEditor(<?= e(json_encode($menuItems)) ?>)">
    <form method="POST" action="<?= url('admin/menus/edit/' . $location) ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="items_json" :value="JSON.stringify(items)">

        <div class="bg-white rounded-xl border border-gray-200 p-5 max-w-2xl">
            <div class="space-y-3 mb-4">
                <template x-for="(item, i) in items" :key="i">
                    <div class="flex items-center gap-2 border border-gray-200 rounded-lg p-3">
                        <div class="flex flex-col gap-1 mr-1">
                            <button type="button" @click="move(i, -1)" :disabled="i === 0" class="text-gray-400 hover:text-gray-600 disabled:opacity-30">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                            </button>
                            <button type="button" @click="move(i, 1)" :disabled="i === items.length - 1" class="text-gray-400 hover:text-gray-600 disabled:opacity-30">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                        </div>
                        <input type="text" x-model="item.label" placeholder="Název" class="flex-1 px-2 py-1.5 border border-gray-300 rounded text-sm">
                        <input type="text" x-model="item.url" placeholder="/url" class="flex-1 px-2 py-1.5 border border-gray-300 rounded text-sm">
                        <select x-model="item.target" class="px-2 py-1.5 border border-gray-300 rounded text-sm">
                            <option value="">Stejné okno</option>
                            <option value="_blank">Nové okno</option>
                        </select>
                        <button type="button" @click="items.splice(i, 1)" class="text-red-500 hover:text-red-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </template>
            </div>

            <div class="flex items-center gap-3 mb-4">
                <button type="button" @click="items.push({label:'', url:'/', target:''})"
                        class="text-sm text-blue-600 hover:text-blue-800">+ Přidat odkaz</button>

                <?php if (!empty($pages)): ?>
                <div class="flex items-center gap-1">
                    <select id="addPage" class="px-2 py-1.5 border border-gray-300 rounded text-sm">
                        <option value="">— Přidat stránku —</option>
                        <?php foreach ($pages as $p): ?>
                            <option value="<?= e($p['slug']) ?>" data-label="<?= e($p['title']) ?>"><?= e($p['title']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="button" @click="addPage()" class="text-sm text-blue-600 hover:text-blue-800">Přidat</button>
                </div>
                <?php endif; ?>
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-6 rounded-lg transition-colors">Uložit menu</button>
        </div>
    </form>
</div>

<script>
function menuEditor(initialItems) {
    return {
        items: initialItems || [],
        move(i, dir) {
            var ni = i + dir;
            if (ni < 0 || ni >= this.items.length) return;
            var t = this.items[i];
            this.items[i] = this.items[ni];
            this.items[ni] = t;
            this.items = [...this.items];
        },
        addPage() {
            var sel = document.getElementById('addPage');
            if (!sel.value) return;
            var opt = sel.options[sel.selectedIndex];
            this.items.push({ label: opt.dataset.label || opt.text, url: '/' + sel.value, target: '' });
            sel.value = '';
        }
    };
}
</script>
