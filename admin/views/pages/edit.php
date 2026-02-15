<?php defined('ZVELE_CMS') or die(); ?>

<div x-data="blockEditor(<?= e(json_encode($page['blocks'])) ?>)">
    <form method="POST" action="<?= url('admin/pages/save' . ($page['id'] ? '/' . $page['id'] : '')) ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="blocks_json" :value="JSON.stringify(blocks)">

        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Main content -->
            <div class="flex-1 space-y-6">
                <!-- Title & Slug -->
                <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Název stránky</label>
                        <input type="text" id="title" name="title" value="<?= e($page['title']) ?>"
                               data-slug-source="slug" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">URL slug</label>
                        <div class="flex items-center">
                            <span class="text-sm text-gray-400 mr-1"><?= e(SITE_URL) ?>/</span>
                            <input type="text" id="slug" name="slug" value="<?= e($page['slug']) ?>"
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        </div>
                    </div>
                </div>

                <!-- Block Editor -->
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-semibold text-gray-900">Bloky obsahu</h2>
                    </div>

                    <!-- Blocks list -->
                    <div class="space-y-4">
                        <template x-for="(block, index) in blocks" :key="block.id">
                            <div class="border border-gray-200 rounded-lg overflow-hidden" :class="{ 'ring-2 ring-blue-500': activeBlock === block.id }">
                                <!-- Block header -->
                                <div class="flex items-center justify-between bg-gray-50 px-4 py-2 cursor-move"
                                     @click="activeBlock = activeBlock === block.id ? null : block.id">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                                        </svg>
                                        <span class="text-sm font-medium text-gray-700" x-text="blockLabels[block.type] || block.type"></span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <button type="button" @click.stop="moveBlock(index, -1)" :disabled="index === 0"
                                                class="p-1 text-gray-400 hover:text-gray-600 disabled:opacity-30">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                        </button>
                                        <button type="button" @click.stop="moveBlock(index, 1)" :disabled="index === blocks.length - 1"
                                                class="p-1 text-gray-400 hover:text-gray-600 disabled:opacity-30">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </button>
                                        <button type="button" @click.stop="removeBlock(index)"
                                                class="p-1 text-gray-400 hover:text-red-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Block content (collapsed by default) -->
                                <div x-show="activeBlock === block.id" x-transition class="p-4 space-y-3">
                                    <!-- Hero block -->
                                    <template x-if="block.type === 'hero'">
                                        <div class="space-y-3">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-500 mb-1">Nadpis</label>
                                                <input type="text" x-model="block.data.title" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-500 mb-1">Podnadpis</label>
                                                <input type="text" x-model="block.data.subtitle" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                            </div>
                                            <div class="grid grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-500 mb-1">CTA text</label>
                                                    <input type="text" x-model="block.data.cta_text" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-500 mb-1">CTA URL</label>
                                                    <input type="text" x-model="block.data.cta_url" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-500 mb-1">ID obrázku</label>
                                                <input type="number" x-model="block.data.image_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Text block -->
                                    <template x-if="block.type === 'text'">
                                        <div class="space-y-3">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-500 mb-1">Obsah (HTML)</label>
                                                <textarea x-model="block.data.content" rows="8" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm font-mono"></textarea>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-500 mb-1">Šířka</label>
                                                <select x-model="block.data.width" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                                    <option value="narrow">Úzká</option>
                                                    <option value="full">Plná</option>
                                                </select>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Image-text block -->
                                    <template x-if="block.type === 'image-text'">
                                        <div class="space-y-3">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-500 mb-1">ID obrázku</label>
                                                <input type="number" x-model="block.data.image_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-500 mb-1">Obsah (HTML)</label>
                                                <textarea x-model="block.data.content" rows="5" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm font-mono"></textarea>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-500 mb-1">Pozice obrázku</label>
                                                <select x-model="block.data.image_position" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                                    <option value="left">Vlevo</option>
                                                    <option value="right">Vpravo</option>
                                                </select>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- CTA block -->
                                    <template x-if="block.type === 'cta'">
                                        <div class="space-y-3">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-500 mb-1">Nadpis</label>
                                                <input type="text" x-model="block.data.title" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-500 mb-1">Text</label>
                                                <textarea x-model="block.data.text" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"></textarea>
                                            </div>
                                            <div class="grid grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-500 mb-1">Text tlačítka</label>
                                                    <input type="text" x-model="block.data.button_text" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-500 mb-1">URL tlačítka</label>
                                                    <input type="text" x-model="block.data.button_url" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-500 mb-1">Styl</label>
                                                <select x-model="block.data.style" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                                    <option value="primary">Primární</option>
                                                    <option value="secondary">Sekundární</option>
                                                </select>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Features block -->
                                    <template x-if="block.type === 'features'">
                                        <div class="space-y-3">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-500 mb-1">Nadpis sekce</label>
                                                <input type="text" x-model="block.data.title" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                            </div>
                                            <template x-for="(item, i) in block.data.items" :key="i">
                                                <div class="border border-gray-100 rounded-lg p-3 space-y-2">
                                                    <div class="flex items-center justify-between">
                                                        <span class="text-xs font-medium text-gray-400" x-text="'Položka ' + (i+1)"></span>
                                                        <button type="button" @click="block.data.items.splice(i, 1)" class="text-xs text-red-500 hover:text-red-700">Odebrat</button>
                                                    </div>
                                                    <input type="text" x-model="item.icon" placeholder="Ikona (emoji nebo text)" class="w-full px-3 py-1.5 border border-gray-300 rounded text-sm">
                                                    <input type="text" x-model="item.title" placeholder="Název" class="w-full px-3 py-1.5 border border-gray-300 rounded text-sm">
                                                    <textarea x-model="item.text" placeholder="Popis" rows="2" class="w-full px-3 py-1.5 border border-gray-300 rounded text-sm"></textarea>
                                                </div>
                                            </template>
                                            <button type="button" @click="block.data.items.push({icon:'', title:'', text:''})"
                                                    class="text-sm text-blue-600 hover:text-blue-800">+ Přidat položku</button>
                                        </div>
                                    </template>

                                    <!-- Testimonials block -->
                                    <template x-if="block.type === 'testimonials'">
                                        <div class="space-y-3">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-500 mb-1">Nadpis sekce</label>
                                                <input type="text" x-model="block.data.title" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                            </div>
                                            <template x-for="(item, i) in block.data.items" :key="i">
                                                <div class="border border-gray-100 rounded-lg p-3 space-y-2">
                                                    <div class="flex items-center justify-between">
                                                        <span class="text-xs font-medium text-gray-400" x-text="'Reference ' + (i+1)"></span>
                                                        <button type="button" @click="block.data.items.splice(i, 1)" class="text-xs text-red-500 hover:text-red-700">Odebrat</button>
                                                    </div>
                                                    <textarea x-model="item.quote" placeholder="Citát" rows="2" class="w-full px-3 py-1.5 border border-gray-300 rounded text-sm"></textarea>
                                                    <div class="grid grid-cols-2 gap-2">
                                                        <input type="text" x-model="item.author" placeholder="Autor" class="w-full px-3 py-1.5 border border-gray-300 rounded text-sm">
                                                        <input type="text" x-model="item.role" placeholder="Pozice" class="w-full px-3 py-1.5 border border-gray-300 rounded text-sm">
                                                    </div>
                                                </div>
                                            </template>
                                            <button type="button" @click="block.data.items.push({quote:'', author:'', role:'', image_id:null})"
                                                    class="text-sm text-blue-600 hover:text-blue-800">+ Přidat referenci</button>
                                        </div>
                                    </template>

                                    <!-- Contact form block -->
                                    <template x-if="block.type === 'contact-form'">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 mb-1">ID formuláře</label>
                                            <input type="number" x-model="block.data.form_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                        </div>
                                    </template>

                                    <!-- FAQ block -->
                                    <template x-if="block.type === 'faq'">
                                        <div class="space-y-3">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-500 mb-1">Nadpis sekce</label>
                                                <input type="text" x-model="block.data.title" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                            </div>
                                            <template x-for="(item, i) in block.data.items" :key="i">
                                                <div class="border border-gray-100 rounded-lg p-3 space-y-2">
                                                    <div class="flex items-center justify-between">
                                                        <span class="text-xs font-medium text-gray-400" x-text="'Otázka ' + (i+1)"></span>
                                                        <button type="button" @click="block.data.items.splice(i, 1)" class="text-xs text-red-500 hover:text-red-700">Odebrat</button>
                                                    </div>
                                                    <input type="text" x-model="item.question" placeholder="Otázka" class="w-full px-3 py-1.5 border border-gray-300 rounded text-sm">
                                                    <textarea x-model="item.answer" placeholder="Odpověď" rows="3" class="w-full px-3 py-1.5 border border-gray-300 rounded text-sm"></textarea>
                                                </div>
                                            </template>
                                            <button type="button" @click="block.data.items.push({question:'', answer:''})"
                                                    class="text-sm text-blue-600 hover:text-blue-800">+ Přidat otázku</button>
                                        </div>
                                    </template>

                                    <!-- Gallery block -->
                                    <template x-if="block.type === 'gallery'">
                                        <div class="space-y-3">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-500 mb-1">Nadpis</label>
                                                <input type="text" x-model="block.data.title" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-500 mb-1">Sloupce</label>
                                                <select x-model="block.data.columns" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                </select>
                                            </div>
                                            <template x-for="(img, i) in block.data.images" :key="i">
                                                <div class="flex items-center gap-2">
                                                    <input type="number" x-model="img.image_id" placeholder="ID obrázku" class="w-24 px-3 py-1.5 border border-gray-300 rounded text-sm">
                                                    <input type="text" x-model="img.caption" placeholder="Popisek" class="flex-1 px-3 py-1.5 border border-gray-300 rounded text-sm">
                                                    <button type="button" @click="block.data.images.splice(i, 1)" class="text-red-500 hover:text-red-700 text-xs">Odebrat</button>
                                                </div>
                                            </template>
                                            <button type="button" @click="block.data.images.push({image_id:null, caption:''})"
                                                    class="text-sm text-blue-600 hover:text-blue-800">+ Přidat obrázek</button>
                                        </div>
                                    </template>

                                    <!-- Video block -->
                                    <template x-if="block.type === 'video'">
                                        <div class="space-y-3">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-500 mb-1">Nadpis</label>
                                                <input type="text" x-model="block.data.title" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-500 mb-1">URL videa (YouTube/Vimeo)</label>
                                                <input type="url" x-model="block.data.url" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-500 mb-1">Poměr stran</label>
                                                <select x-model="block.data.aspect_ratio" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                                    <option value="16:9">16:9</option>
                                                    <option value="4:3">4:3</option>
                                                </select>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Add block buttons -->
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-xs font-medium text-gray-500 mb-2">Přidat blok:</p>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="(label, type) in blockLabels" :key="type">
                                <button type="button" @click="addBlock(type)"
                                        class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium rounded-lg transition-colors"
                                        x-text="label"></button>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="w-full lg:w-80 space-y-6">
                <!-- Publish box -->
                <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4">
                    <h3 class="font-semibold text-gray-900 text-sm">Publikování</h3>

                    <div>
                        <label for="status" class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                        <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            <option value="draft" <?= ($page['status'] ?? 'draft') === 'draft' ? 'selected' : '' ?>>Koncept</option>
                            <option value="published" <?= ($page['status'] ?? '') === 'published' ? 'selected' : '' ?>>Publikováno</option>
                        </select>
                    </div>

                    <div>
                        <label for="template" class="block text-xs font-medium text-gray-500 mb-1">Šablona</label>
                        <select id="template" name="template" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            <option value="page" <?= ($page['template'] ?? 'page') === 'page' ? 'selected' : '' ?>>Stránka</option>
                            <option value="blog" <?= ($page['template'] ?? '') === 'blog' ? 'selected' : '' ?>>Blog</option>
                        </select>
                    </div>

                    <div>
                        <label for="parent_id" class="block text-xs font-medium text-gray-500 mb-1">Nadřazená stránka</label>
                        <select id="parent_id" name="parent_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            <option value="">— Žádná —</option>
                            <?php foreach ($parents as $parent): ?>
                            <option value="<?= (int)$parent['id'] ?>" <?= ($page['parent_id'] ?? null) == $parent['id'] ? 'selected' : '' ?>>
                                <?= e($parent['title']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="sort_order" class="block text-xs font-medium text-gray-500 mb-1">Pořadí</label>
                        <input type="number" id="sort_order" name="sort_order" value="<?= (int)($page['sort_order'] ?? 0) ?>"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    </div>

                    <div class="flex gap-2">
                        <button type="submit"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-lg transition-colors">
                            Uložit
                        </button>
                        <a href="<?= url('admin/pages') ?>"
                           class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            Zrušit
                        </a>
                    </div>
                </div>

                <!-- SEO box -->
                <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4" x-data="{ open: false }">
                    <button type="button" @click="open = !open" class="flex items-center justify-between w-full">
                        <h3 class="font-semibold text-gray-900 text-sm">SEO nastavení</h3>
                        <svg class="w-4 h-4 text-gray-400 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="open" x-transition class="space-y-3">
                        <div>
                            <label for="meta_title" class="block text-xs font-medium text-gray-500 mb-1">Meta title (max 70)</label>
                            <input type="text" id="meta_title" name="meta_title" value="<?= e($page['meta_title'] ?? '') ?>" maxlength="70"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label for="meta_description" class="block text-xs font-medium text-gray-500 mb-1">Meta description (max 160)</label>
                            <textarea id="meta_description" name="meta_description" maxlength="160" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"><?= e($page['meta_description'] ?? '') ?></textarea>
                        </div>
                        <div>
                            <label for="canonical_url" class="block text-xs font-medium text-gray-500 mb-1">Canonical URL</label>
                            <input type="url" id="canonical_url" name="canonical_url" value="<?= e($page['canonical_url'] ?? '') ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label for="og_image_id" class="block text-xs font-medium text-gray-500 mb-1">OG Image ID</label>
                            <input type="number" id="og_image_id" name="og_image_id" value="<?= e($page['og_image_id'] ?? '') ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label for="schema_type" class="block text-xs font-medium text-gray-500 mb-1">Schema type</label>
                            <select id="schema_type" name="schema_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                <option value="WebPage" <?= ($page['schema_type'] ?? 'WebPage') === 'WebPage' ? 'selected' : '' ?>>WebPage</option>
                                <option value="AboutPage" <?= ($page['schema_type'] ?? '') === 'AboutPage' ? 'selected' : '' ?>>AboutPage</option>
                                <option value="ContactPage" <?= ($page['schema_type'] ?? '') === 'ContactPage' ? 'selected' : '' ?>>ContactPage</option>
                                <option value="FAQPage" <?= ($page['schema_type'] ?? '') === 'FAQPage' ? 'selected' : '' ?>>FAQPage</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="no_index" name="no_index" value="1" <?= !empty($page['no_index']) ? 'checked' : '' ?>
                                   class="rounded border-gray-300">
                            <label for="no_index" class="text-xs text-gray-600">Neindexovat (noindex)</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function blockEditor(initialBlocks) {
    return {
        blocks: initialBlocks || [],
        activeBlock: null,
        blockLabels: {
            'hero': 'Hero',
            'text': 'Text',
            'image-text': 'Obrázek + text',
            'cta': 'CTA',
            'features': 'Features',
            'testimonials': 'Reference',
            'contact-form': 'Kontaktní formulář',
            'faq': 'FAQ',
            'gallery': 'Galerie',
            'video': 'Video'
        },
        addBlock(type) {
            var defaults = {
                'hero': { title: '', subtitle: '', image_id: null, cta_text: '', cta_url: '', overlay_opacity: 50 },
                'text': { content: '', width: 'narrow' },
                'image-text': { image_id: null, content: '', image_position: 'left' },
                'cta': { title: '', text: '', button_text: '', button_url: '', style: 'primary' },
                'features': { title: '', items: [{ icon: '', title: '', text: '' }] },
                'testimonials': { title: '', items: [{ quote: '', author: '', role: '', image_id: null }] },
                'contact-form': { form_id: null },
                'faq': { title: '', items: [{ question: '', answer: '' }] },
                'gallery': { title: '', images: [], columns: 3 },
                'video': { title: '', url: '', aspect_ratio: '16:9' }
            };
            var block = {
                id: 'block_' + Date.now() + '_' + Math.random().toString(36).substr(2, 6),
                type: type,
                data: JSON.parse(JSON.stringify(defaults[type] || {}))
            };
            this.blocks.push(block);
            this.activeBlock = block.id;
        },
        removeBlock(index) {
            if (confirm('Opravdu chcete odebrat tento blok?')) {
                this.blocks.splice(index, 1);
            }
        },
        moveBlock(index, direction) {
            var newIndex = index + direction;
            if (newIndex < 0 || newIndex >= this.blocks.length) return;
            var temp = this.blocks[index];
            this.blocks[index] = this.blocks[newIndex];
            this.blocks[newIndex] = temp;
            // Force reactivity
            this.blocks = [...this.blocks];
        }
    };
}
</script>
