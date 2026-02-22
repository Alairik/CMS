<?php
/**
 * ZveleCMS — Demo Seed
 * Naplní databázi demo obsahem pro showcase web "Starter Studio".
 * Spusťte po install.php: navštivte /seed.php v prohlížeči.
 * PO SEEDOVÁNÍ SOUBOR SMAŽTE.
 */

define('ZVELE_CMS', true);
require_once __DIR__ . '/config.php';
require_once CORE_PATH . '/helpers.php';
require_once CORE_PATH . '/Database.php';

$db = Database::getInstance();

// ============================================================
// SETTINGS
// ============================================================
$settings = [
    'site_name' => 'Starter Studio',
    'site_url' => SITE_URL,
    'site_description' => 'Tvoříme digitální zážitky, které prodávají. Moderní weby, branding a digitální strategie pro ambiciózní firmy.',
    'admin_email' => 'info@starterstudio.cz',
    'language' => 'cs',
    'timezone' => 'Europe/Prague',
    'date_format' => 'j. n. Y',
    'meta_title_separator' => '—',
    'meta_title_pattern' => '{page_title} {separator} {site_name}',
    'schema_organization_name' => 'Starter Studio s.r.o.',
    'cookie_banner_title' => 'Respektujeme vaše soukromí',
    'cookie_banner_text' => 'Používáme cookies pro zajištění funkčnosti webu a analýzu návštěvnosti. Marketingové cookies nám pomáhají cílit reklamy.',
    'cookie_banner_accept' => 'Přijmout vše',
    'cookie_banner_reject' => 'Jen nezbytné',
    'cookie_banner_settings' => 'Upravit předvolby',
    'blog_posts_per_page' => '9',
];

foreach ($settings as $key => $value) {
    $db->query(
        "UPDATE zvele_settings SET `value` = ? WHERE `key` = ?",
        [$value, $key]
    );
}

echo "✓ Settings aktualizovány<br>";

// ============================================================
// CONTACT FORM
// ============================================================
$formFields = [
    ['name' => 'name', 'label' => 'Jméno a příjmení', 'type' => 'text', 'placeholder' => 'Jan Novák', 'required' => true],
    ['name' => 'email', 'label' => 'E-mail', 'type' => 'email', 'placeholder' => 'jan@firma.cz', 'required' => true],
    ['name' => 'phone', 'label' => 'Telefon', 'type' => 'tel', 'placeholder' => '+420 777 888 999', 'required' => false],
    ['name' => 'service', 'label' => 'Mám zájem o', 'type' => 'select', 'placeholder' => '', 'required' => false, 'options' => ['Webdesign', 'Branding', 'SEO', 'Digitální strategie', 'Jiné']],
    ['name' => 'message', 'label' => 'Zpráva', 'type' => 'textarea', 'placeholder' => 'Popište váš projekt...', 'required' => true],
];

$formId = $db->insert('zvele_forms', [
    'name' => 'Kontaktní formulář',
    'slug' => 'kontakt',
    'fields' => json_encode($formFields, JSON_UNESCAPED_UNICODE),
    'email_to' => 'info@starterstudio.cz',
    'email_subject' => 'Nová poptávka ze Starter Studio',
    'success_message' => 'Děkujeme za zprávu! Ozveme se vám do 24 hodin.',
    'honeypot_field' => 'website_url',
]);

echo "✓ Kontaktní formulář vytvořen (ID: {$formId})<br>";

// ============================================================
// PAGES
// ============================================================

// --- HOMEPAGE ---
$homepageBlocks = [
    [
        'id' => 'block_hero_1',
        'type' => 'hero',
        'data' => [
            'title' => 'Tvoříme weby, které nezapadnou',
            'subtitle' => 'Jsme studio zaměřené na design, výkon a výsledky. Pomáháme firmám růst skrze digitální přítomnost, která zaujme na první pohled.',
            'cta_text' => 'Začněme spolu',
            'cta_url' => '/kontakt',
            'image_id' => null,
            'overlay_opacity' => 60,
        ],
    ],
    [
        'id' => 'block_features_1',
        'type' => 'features',
        'data' => [
            'title' => 'Co pro vás můžeme udělat',
            'items' => [
                ['icon' => '🎨', 'title' => 'Webdesign na míru', 'text' => 'Žádné šablony. Každý web navrhujeme od nuly podle vašich potřeb, cílové skupiny a obchodních cílů.'],
                ['icon' => '🚀', 'title' => 'Výkon a rychlost', 'text' => 'Weby, které se načtou pod 2 sekundy. Optimalizované pro Core Web Vitals a maximální konverzní poměr.'],
                ['icon' => '🔍', 'title' => 'SEO & viditelnost', 'text' => 'Strukturovaná data, technické SEO a obsahová strategie. Budeme tam, kde vás zákazníci hledají.'],
                ['icon' => '📱', 'title' => 'Responzivní design', 'text' => 'Perfektní zobrazení na všech zařízeních. Od mobilu po ultrawide monitor — bez kompromisů.'],
                ['icon' => '🛡️', 'title' => 'Bezpečnost & GDPR', 'text' => 'SSL, CSRF ochrana, prepared statements, cookie consent. Bezpečnost není volitelná — je součást DNA.'],
                ['icon' => '📊', 'title' => 'Analytika & měření', 'text' => 'Google Analytics, Tag Manager, konverzní tracking. Měříme, co funguje, a optimalizujeme, co nefunguje.'],
            ],
        ],
    ],
    [
        'id' => 'block_text_about',
        'type' => 'text',
        'data' => [
            'content' => '<h2>Proč si nás klienti vybírají</h2><p>Za posledních 5 let jsme pomohli více než 80 firmám vybudovat silnou digitální přítomnost. Naším cílem není jen hezký web — je to web, který <strong>pracuje pro váš byznys</strong> 24 hodin denně, 7 dní v týdnu.</p><p>Kombinujeme kreativní design s technickou precizností. Každý řádek kódu píšeme ručně, každý pixel má svůj účel. Výsledkem jsou weby, které nejen skvěle vypadají, ale hlavně <strong>přinášejí výsledky</strong>.</p>',
            'width' => 'narrow',
        ],
    ],
    [
        'id' => 'block_testimonials_1',
        'type' => 'testimonials',
        'data' => [
            'title' => 'Co říkají naši klienti',
            'items' => [
                ['quote' => 'Starter Studio nám kompletně předělali web a výsledky přišly okamžitě. Konverzní poměr vzrostl o 340 % během prvních tří měsíců. Nejlepší investice roku.', 'author' => 'Petra Králová', 'role' => 'CEO, TechVision s.r.o.', 'image_id' => null],
                ['quote' => 'Profesionální přístup od prvního setkání. Pochopili naši vizi a přetvořili ji do webu, který přesně odráží naši značku. Spolupráce jako ze snu.', 'author' => 'Martin Dvořák', 'role' => 'Zakladatel, GreenLeaf', 'image_id' => null],
                ['quote' => 'Konečně máme web, na který jsme hrdí. Rychlý, přehledný a krásný. Naši zákazníci nám pravidelně říkají, jak se jim líbí. Děkujeme!', 'author' => 'Lucie Černá', 'role' => 'Marketing Director, Bloom & Co.', 'image_id' => null],
            ],
        ],
    ],
    [
        'id' => 'block_cta_1',
        'type' => 'cta',
        'data' => [
            'title' => 'Připraveni na nový web?',
            'text' => 'Napište nám a společně probereme, jak můžeme váš byznys posunout na další level. Konzultace je zdarma.',
            'button_text' => 'Nezávazná konzultace',
            'button_url' => '/kontakt',
            'style' => 'primary',
        ],
    ],
];

$db->insert('zvele_pages', [
    'slug' => 'homepage',
    'title' => 'Starter Studio — Tvoříme digitální zážitky',
    'meta_title' => 'Webdesign & digitální strategie',
    'meta_description' => 'Tvoříme moderní weby, které prodávají. Webdesign na míru, SEO, branding a digitální strategie pro ambiciózní firmy.',
    'blocks' => json_encode($homepageBlocks, JSON_UNESCAPED_UNICODE),
    'schema_type' => 'WebPage',
    'status' => 'published',
    'sort_order' => 0,
    'template' => 'page',
    'published_at' => date('Y-m-d H:i:s'),
]);

echo "✓ Homepage vytvořena<br>";

// --- O NÁS ---
$aboutBlocks = [
    [
        'id' => 'block_hero_about',
        'type' => 'hero',
        'data' => [
            'title' => 'Jsme Starter Studio',
            'subtitle' => 'Malý tým s velkými ambicemi. Děláme weby, které mění pravidla hry.',
            'cta_text' => '',
            'cta_url' => '',
            'image_id' => null,
            'overlay_opacity' => 70,
        ],
    ],
    [
        'id' => 'block_about_text',
        'type' => 'text',
        'data' => [
            'content' => '<h2>Náš příběh</h2><p>Starter Studio vzniklo v roce 2021 z jednoduché myšlenky: české firmy si zaslouží weby světové kvality bez korporátních cenovek. Začínali jsme ve dvou, dnes je nás šest — a každý z nás sdílí stejnou vášeň pro dokonalý kód a krásný design.</p><p>Věříme, že web není jen vizitka na internetu. Je to váš <strong>nejdůležitější obchodní nástroj</strong> — pracuje nonstop, nechodí na dovolenou a nikdy nemá špatný den. Proto mu věnujeme takovou péči.</p><h2>Naše hodnoty</h2><p>Transparentnost, kvalita a partnerský přístup. Žádné skryté poplatky, žádné buzzwordy. Říkáme věci na rovinu a děláme je pořádně. Každý projekt bereme osobně — vaše úspěch je náš úspěch.</p>',
            'width' => 'narrow',
        ],
    ],
    [
        'id' => 'block_team',
        'type' => 'features',
        'data' => [
            'title' => 'Náš tým',
            'items' => [
                ['icon' => '👨‍💻', 'title' => 'Jakub Starý', 'text' => 'Zakladatel & Lead Developer. 12 let zkušeností s PHP, TypeScript a systémovou architekturou.'],
                ['icon' => '🎨', 'title' => 'Anna Svobodová', 'text' => 'Creative Director. Návrhy, které kombinují estetiku s funkcí. Figma je její druhý domov.'],
                ['icon' => '📈', 'title' => 'Tomáš Kratochvíl', 'text' => 'SEO & Performance. Datově řízený přístup k viditelnosti. Žádné triky — jen výsledky.'],
            ],
        ],
    ],
];

$db->insert('zvele_pages', [
    'slug' => 'o-nas',
    'title' => 'O nás',
    'meta_title' => 'O nás',
    'meta_description' => 'Poznejte tým Starter Studio. Jsme malé studio s velkými ambicemi — děláme weby, branding a digitální strategie pro firmy, které chtějí růst.',
    'blocks' => json_encode($aboutBlocks, JSON_UNESCAPED_UNICODE),
    'schema_type' => 'AboutPage',
    'status' => 'published',
    'sort_order' => 1,
    'template' => 'page',
    'published_at' => date('Y-m-d H:i:s'),
]);

echo "✓ Stránka O nás vytvořena<br>";

// --- SLUŽBY ---
$serviceBlocks = [
    [
        'id' => 'block_hero_services',
        'type' => 'hero',
        'data' => [
            'title' => 'Naše služby',
            'subtitle' => 'Komplexní digitální řešení od strategie po realizaci.',
            'cta_text' => 'Poptat službu',
            'cta_url' => '/kontakt',
            'image_id' => null,
            'overlay_opacity' => 65,
        ],
    ],
    [
        'id' => 'block_services_list',
        'type' => 'features',
        'data' => [
            'title' => 'Jak vám pomůžeme',
            'items' => [
                ['icon' => '💻', 'title' => 'Webdesign & vývoj', 'text' => 'Custom weby na míru. Žádné šablony, žádné page buildery. Čistý kód, rychlé načítání, perfektní SEO. Od landing page po komplexní portály.'],
                ['icon' => '✏️', 'title' => 'Branding & identita', 'text' => 'Logo, vizuální identita, brand guidelines. Vytvoříme značku, která rezonuje s vaší cílovou skupinou a odliší vás od konkurence.'],
                ['icon' => '📊', 'title' => 'SEO & obsahová strategie', 'text' => 'Technické SEO, keyword research, link building a obsahový plán. Organický traffic, který trvale roste.'],
                ['icon' => '📱', 'title' => 'Webové aplikace', 'text' => 'CRM systémy, dashboardy, interní nástroje. Vyvíjíme webové aplikace, které zefektivní vaše procesy.'],
                ['icon' => '🔧', 'title' => 'Správa & údržba', 'text' => 'Monitoring, aktualizace, bezpečnostní záplaty, zálohování. Postaráme se o váš web, abyste se mohli soustředit na byznys.'],
                ['icon' => '📈', 'title' => 'Digitální strategie', 'text' => 'Audit, roadmapa, implementace. Pomůžeme vám definovat cíle a cestu k nim. Data-driven přístup ke každému rozhodnutí.'],
            ],
        ],
    ],
    [
        'id' => 'block_process',
        'type' => 'text',
        'data' => [
            'content' => '<h2>Náš proces</h2><p><strong>1. Discovery</strong> — Porozumíme vašemu byznysu, cílům a cílové skupině. Analyzujeme konkurenci a definujeme strategii.</p><p><strong>2. Design</strong> — Navrhneme wireframy a vizuální koncept. Iterujeme, dokud není vše přesně podle vašich představ.</p><p><strong>3. Vývoj</strong> — Píšeme čistý, efektivní kód. Testujeme na všech zařízeních a prohlížečích. Optimalizujeme rychlost.</p><p><strong>4. Launch & růst</strong> — Spustíme web a nastavíme analytiku. Sledujeme výkon a průběžně optimalizujeme.</p>',
            'width' => 'narrow',
        ],
    ],
    [
        'id' => 'block_cta_services',
        'type' => 'cta',
        'data' => [
            'title' => 'Máte projekt na mysli?',
            'text' => 'Rádi si o něm popovídáme. Napište nám a do 24 hodin se vám ozveme s návrhem řešení.',
            'button_text' => 'Kontaktujte nás',
            'button_url' => '/kontakt',
            'style' => 'primary',
        ],
    ],
];

$db->insert('zvele_pages', [
    'slug' => 'sluzby',
    'title' => 'Služby',
    'meta_title' => 'Služby',
    'meta_description' => 'Webdesign, branding, SEO a digitální strategie. Kompletní digitální řešení pro firmy, které chtějí růst online.',
    'blocks' => json_encode($serviceBlocks, JSON_UNESCAPED_UNICODE),
    'schema_type' => 'WebPage',
    'status' => 'published',
    'sort_order' => 2,
    'template' => 'page',
    'published_at' => date('Y-m-d H:i:s'),
]);

echo "✓ Stránka Služby vytvořena<br>";

// --- PORTFOLIO ---
$portfolioBlocks = [
    [
        'id' => 'block_hero_portfolio',
        'type' => 'hero',
        'data' => [
            'title' => 'Naše práce',
            'subtitle' => 'Výběr z projektů, na které jsme hrdí.',
            'cta_text' => '',
            'cta_url' => '',
            'image_id' => null,
            'overlay_opacity' => 65,
        ],
    ],
    [
        'id' => 'block_portfolio_text',
        'type' => 'text',
        'data' => [
            'content' => '<h2>Vybrané projekty</h2><p>Každý projekt je pro nás výzvou a příležitostí ukázat, co umíme. Zde je výběr těch, na které jsme obzvlášť pyšní. Pracujeme s firmami všech velikostí — od startupů po zavedené společnosti.</p><p><strong>TechVision s.r.o.</strong> — Kompletní redesign firemního webu. Nový vizuální styl, optimalizace konverzního trychtýře. Výsledek: +340 % konverzí.</p><p><strong>GreenLeaf</strong> — Branding a webová prezentace pro eko-startup. Minimalistický design, který odráží hodnoty udržitelnosti.</p><p><strong>Bloom & Co.</strong> — E-commerce řešení s custom CMS. 500+ produktů, pokročilé filtrování, mobilní optimalizace.</p><p><strong>Atlas Consulting</strong> — Korporátní web s 8jazyčnou mutací. Komplexní SEO strategie pro mezinárodní viditelnost.</p>',
            'width' => 'narrow',
        ],
    ],
    [
        'id' => 'block_cta_portfolio',
        'type' => 'cta',
        'data' => [
            'title' => 'Chcete být dalším úspěšným projektem?',
            'text' => 'Ukažte nám vaši vizi a my ji přetvoříme v realitu.',
            'button_text' => 'Zahájit projekt',
            'button_url' => '/kontakt',
            'style' => 'primary',
        ],
    ],
];

$db->insert('zvele_pages', [
    'slug' => 'portfolio',
    'title' => 'Portfolio',
    'meta_title' => 'Portfolio',
    'meta_description' => 'Podívejte se na naše realizace. Webdesign, branding a digitální řešení pro firmy, které chtějí uspět online.',
    'blocks' => json_encode($portfolioBlocks, JSON_UNESCAPED_UNICODE),
    'schema_type' => 'WebPage',
    'status' => 'published',
    'sort_order' => 3,
    'template' => 'page',
    'published_at' => date('Y-m-d H:i:s'),
]);

echo "✓ Stránka Portfolio vytvořena<br>";

// --- KONTAKT ---
$kontaktBlocks = [
    [
        'id' => 'block_hero_contact',
        'type' => 'hero',
        'data' => [
            'title' => 'Spojte se s námi',
            'subtitle' => 'Máte projekt, nápad nebo otázku? Rádi si popovídáme.',
            'cta_text' => '',
            'cta_url' => '',
            'image_id' => null,
            'overlay_opacity' => 65,
        ],
    ],
    [
        'id' => 'block_contact_info',
        'type' => 'text',
        'data' => [
            'content' => '<h2>Kontaktní údaje</h2><p><strong>Starter Studio s.r.o.</strong><br>Vinohradská 42, 120 00 Praha 2</p><p><strong>E-mail:</strong> info@starterstudio.cz<br><strong>Telefon:</strong> +420 777 123 456<br><strong>IČO:</strong> 12345678</p><p>Pracovní doba: Po—Pá, 9:00—18:00</p>',
            'width' => 'narrow',
        ],
    ],
    [
        'id' => 'block_contact_form',
        'type' => 'contact-form',
        'data' => [
            'form_id' => $formId,
        ],
    ],
    [
        'id' => 'block_faq_contact',
        'type' => 'faq',
        'data' => [
            'title' => 'Často kladené otázky',
            'items' => [
                ['question' => 'Kolik stojí nový web?', 'answer' => 'Cena závisí na rozsahu projektu. Jednoduchá firemní prezentace začíná od 45 000 Kč, komplexnější řešení od 90 000 Kč. Rádi vám připravíme cenovou nabídku na míru.'],
                ['question' => 'Jak dlouho trvá realizace webu?', 'answer' => 'Typická firemní prezentace zabere 4–6 týdnů. Složitější projekty 8–12 týdnů. Vždy vám předem sdělíme realistický časový harmonogram.'],
                ['question' => 'Můžu si web spravovat sám?', 'answer' => 'Samozřejmě! Všechny naše weby běží na vlastním CMS s intuitivním administračním rozhraním. Navíc vás zaškolíme a poskytneme dokumentaci.'],
                ['question' => 'Nabízíte i správu a údržbu webu?', 'answer' => 'Ano, nabízíme měsíční plány správy od 3 000 Kč/měsíc. Zahrnují monitoring, aktualizace, bezpečnostní záplaty a zálohování.'],
                ['question' => 'Pracujete i s klienty mimo Prahu?', 'answer' => 'Rozhodně! Většinu komunikace vedeme online. S klienty z celé ČR i Slovenska spolupracujeme pravidelně — vzdálenost není překážka.'],
            ],
        ],
    ],
];

$db->insert('zvele_pages', [
    'slug' => 'kontakt',
    'title' => 'Kontakt',
    'meta_title' => 'Kontakt',
    'meta_description' => 'Kontaktujte Starter Studio. Webdesign, branding a digitální strategie. Sídlíme v Praze, pracujeme s klienty z celé ČR.',
    'blocks' => json_encode($kontaktBlocks, JSON_UNESCAPED_UNICODE),
    'schema_type' => 'ContactPage',
    'status' => 'published',
    'sort_order' => 4,
    'template' => 'page',
    'published_at' => date('Y-m-d H:i:s'),
]);

echo "✓ Stránka Kontakt vytvořena<br>";

// --- GDPR ---
$gdprBlocks = [
    [
        'id' => 'block_gdpr',
        'type' => 'text',
        'data' => [
            'content' => '<h1>Ochrana osobních údajů</h1><h2>1. Správce osobních údajů</h2><p>Správcem osobních údajů je Starter Studio s.r.o., IČO: 12345678, se sídlem Vinohradská 42, 120 00 Praha 2 (dále jen „Správce").</p><h2>2. Rozsah zpracování</h2><p>Zpracováváme pouze osobní údaje, které nám poskytnete prostřednictvím kontaktního formuláře na našem webu: jméno, e-mailovou adresu, telefon a obsah zprávy.</p><h2>3. Účel zpracování</h2><p>Vaše údaje zpracováváme výhradně za účelem odpovědi na vaši poptávku a případné následné obchodní komunikace. Právním základem je oprávněný zájem Správce.</p><h2>4. Doba uchování</h2><p>Údaje z kontaktního formuláře uchováváme po dobu 2 let od posledního kontaktu, poté jsou automaticky smazány.</p><h2>5. Cookies</h2><p>Náš web používá cookies. Nezbytné cookies jsou aktivní vždy. Analytické a marketingové cookies vyžadují váš souhlas, který můžete kdykoliv odvolat kliknutím na „Upravit předvolby cookies" v patičce webu.</p><h2>6. Vaše práva</h2><p>Máte právo na přístup k údajům, jejich opravu, výmaz, omezení zpracování, přenositelnost a vznesení námitky. Pro uplatnění práv nás kontaktujte na info@starterstudio.cz.</p>',
            'width' => 'narrow',
        ],
    ],
];

$db->insert('zvele_pages', [
    'slug' => 'ochrana-osobnich-udaju',
    'title' => 'Ochrana osobních údajů',
    'meta_title' => 'Ochrana osobních údajů',
    'meta_description' => 'Informace o zpracování osobních údajů na webu Starter Studio.',
    'blocks' => json_encode($gdprBlocks, JSON_UNESCAPED_UNICODE),
    'schema_type' => 'WebPage',
    'status' => 'published',
    'sort_order' => 10,
    'no_index' => 0,
    'template' => 'page',
    'published_at' => date('Y-m-d H:i:s'),
]);

echo "✓ Stránka GDPR vytvořena<br>";

// ============================================================
// BLOG POSTS
// ============================================================

$authorId = $db->fetchColumn("SELECT id FROM zvele_users WHERE role = 'admin' LIMIT 1") ?: 1;

$posts = [
    [
        'title' => 'Jak vytvořit moderní web v roce 2026',
        'slug' => 'jak-vytvorit-moderni-web-2026',
        'excerpt' => 'Web v roce 2026 musí být rychlý, přístupný a optimalizovaný pro AI vyhledávače. Podívejte se na klíčové trendy, které byste neměli ignorovat.',
        'meta_description' => 'Průvodce tvorbou moderního webu v roce 2026. Rychlost, přístupnost, AI optimalizace a nejnovější trendy ve webdesignu.',
        'category' => 'Webdesign',
        'tags' => ['webdesign', 'trendy', '2026', 'performance'],
        'content' => '<p>Webový vývoj se v posledních letech dramaticky změnil. Zatímco dříve stačilo mít „hezkou stránku", dnes musí web splňovat desítky kritérií — od rychlosti načítání přes přístupnost až po optimalizaci pro AI crawlery.</p><h2>1. Performance first</h2><p>Google již několik let používá Core Web Vitals jako rankingový faktor. V roce 2026 je to ještě důležitější. Váš web by měl mít:</p><ul><li><strong>LCP pod 2,5 sekundy</strong> — Largest Contentful Paint měří, jak rychle se zobrazí hlavní obsah</li><li><strong>FID pod 100 ms</strong> — First Input Delay měří odezvu na první interakci uživatele</li><li><strong>CLS pod 0,1</strong> — Cumulative Layout Shift měří vizuální stabilitu stránky</li></ul><h2>2. Přístupnost není volitelná</h2><p>WCAG 2.2 AA by měl být minimum pro každý nový web. To znamená dostatečný kontrastní poměr, keyboard navigaci, screen reader kompatibilitu a respektování uživatelských preferencí jako <code>prefers-reduced-motion</code>.</p><h2>3. AI-ready obsah</h2><p>S nástupem AI vyhledávačů (Google AI Overview, ChatGPT Search, Perplexity) je důležité mít strukturovaná data, čitelný obsah a soubor <code>llms.txt</code> pro AI crawlery.</p><h2>4. Semantic HTML</h2><p>Správné použití HTML elementů jako <code>&lt;article&gt;</code>, <code>&lt;section&gt;</code>, <code>&lt;nav&gt;</code> a <code>&lt;aside&gt;</code> pomáhá nejen vyhledávačům, ale i asistivním technologiím lépe porozumět struktuře vašeho obsahu.</p><h2>Závěr</h2><p>Moderní web v roce 2026 je rychlý, přístupný, bezpečný a optimalizovaný pro lidi i stroje. Není to rocket science — je to řemeslo, které vyžaduje pozornost k detailům a disciplínu.</p>',
    ],
    [
        'title' => '5 tipů pro lepší UX design vašeho webu',
        'slug' => '5-tipu-pro-lepsi-ux-design',
        'excerpt' => 'Uživatelský zážitek rozhoduje o tom, jestli návštěvník zůstane nebo odejde. Zde je 5 praktických tipů, které můžete implementovat ještě dnes.',
        'meta_description' => '5 praktických tipů pro zlepšení UX designu vašeho webu. Od navigace přes formuláře po mobilní zobrazení.',
        'category' => 'UX Design',
        'tags' => ['UX', 'design', 'konverze', 'tipy'],
        'content' => '<p>Uživatelský zážitek (UX) je často tím rozhodujícím faktorem, který určuje, jestli návštěvník webu provede požadovanou akci — nebo odejde ke konkurenci. Zde je 5 tipů, které výrazně zlepší UX vašeho webu.</p><h2>1. Zjednodušte navigaci</h2><p>Pravidlo tří kliků je mýtus, ale princip za ním je správný: uživatel by měl najít to, co hledá, <strong>bez přemýšlení</strong>. Hlavní menu by nemělo mít víc než 7 položek. Používejte jasné, srozumitelné názvy.</p><h2>2. Formuláře — méně je více</h2><p>Každé dodatečné pole ve formuláři snižuje konverzní poměr o 5–10 %. Ptejte se jen na to, co opravdu potřebujete. Jméno, e-mail a zpráva — to často stačí pro první kontakt.</p><h2>3. Vizuální hierarchie</h2><p>Lidský mozek zpracovává vizuální informace shora dolů a zleva doprava (v západní kultuře). Využijte toho:</p><ul><li>Největší a nejkontrastnější prvek upoutá pozornost první</li><li>Call-to-action tlačítka by měla vizuálně vystupovat z okolí</li><li>Dostatek white space pomáhá mozku „dýchat" a lépe zpracovávat informace</li></ul><h2>4. Mobile-first myšlení</h2><p>Více než 60 % návštěv webů přichází z mobilních zařízení. Navrhujte nejdřív pro mobil a teprve potom rozšiřujte pro desktop. Ne naopak.</p><h2>5. Rychlost = UX</h2><p>Stránka, která se načítá 5 sekund, ztrácí 40 % návštěvníků. Optimalizujte obrázky (WebP), minimalizujte CSS/JS, používejte lazy loading a zvažte CDN pro statické soubory.</p><h2>Bonus: Testujte se skutečnými uživateli</h2><p>Žádné množství heuristických analýz nenahradí sledování skutečného uživatele, jak interaguje s vaším webem. Už 5 uživatelů odhalí 85 % problémů s použitelností.</p>',
    ],
    [
        'title' => 'SEO trendy 2026: Na co se zaměřit',
        'slug' => 'seo-trendy-2026',
        'excerpt' => 'SEO se neustále vyvíjí. V roce 2026 rozhoduje AI optimalizace, E-E-A-T a strukturovaná data. Přečtěte si, na co se zaměřit.',
        'meta_description' => 'SEO trendy pro rok 2026. AI optimalizace, E-E-A-T, strukturovaná data a technické SEO. Kompletní průvodce.',
        'category' => 'SEO',
        'tags' => ['SEO', 'trendy', 'AI', 'Google'],
        'content' => '<p>Svět SEO se mění rychleji než kdy dříve. AI přepsala pravidla hry a ti, kdo se nepřizpůsobí, ztratí viditelnost. Zde jsou klíčové trendy pro rok 2026.</p><h2>GEO — Generative Engine Optimization</h2><p>Nový pojem, který nahrazuje klasické SEO v kontextu AI vyhledávačů. GEO se zaměřuje na to, jak se váš obsah zobrazuje v AI-generovaných odpovědích (Google AI Overview, ChatGPT, Perplexity).</p><p>Klíčové strategie pro GEO:</p><ul><li><strong>Strukturovaná data</strong> — JSON-LD schema markup pro každý typ obsahu</li><li><strong>llms.txt</strong> — Speciální soubor pro AI crawlery s popisem vašeho webu</li><li><strong>Autoritativní obsah</strong> — AI preferuje obsah od ověřených expertů</li></ul><h2>E-E-A-T je důležitější než kdy dříve</h2><p>Experience, Expertise, Authoritativeness, Trustworthiness. Google klade stále větší důraz na to, <strong>kdo</strong> obsah píše a jaké má zkušenosti. Autorské profily, reference a dokazatelná expertíza jsou klíčové.</p><h2>Technické SEO základ</h2><p>Bez solidního technického základu nemá smysl investovat do obsahu:</p><ul><li>Core Web Vitals — stále klíčový rankingový faktor</li><li>Mobile-first indexing — Google indexuje primárně mobilní verzi</li><li>HTTPS — bez SSL certifikátu nemáte šanci</li><li>Structured data — BreadcrumbList, Article, FAQ, LocalBusiness</li><li>XML Sitemap — aktuální a správně strukturovaný</li></ul><h2>Obsahová kvalita nad kvantitou</h2><p>Éra masové produkce průměrného obsahu skončila. Jeden výjimečný článek porazí deset průměrných. Investujte do hloubky, originality a unikátních dat.</p><h2>Závěr</h2><p>SEO v roce 2026 je o kvalitě, autoritě a technické dokonalosti. Zaměřte se na to, co skutečně pomáhá uživatelům — vyhledávače (a AI) to ocení.</p>',
    ],
];

foreach ($posts as $postData) {
    $wordCount = str_word_count(strip_tags($postData['content']));
    $readingTime = max(1, (int)ceil($wordCount / 200));

    $db->insert('zvele_posts', [
        'slug' => $postData['slug'],
        'title' => $postData['title'],
        'excerpt' => $postData['excerpt'],
        'meta_title' => null,
        'meta_description' => $postData['meta_description'],
        'content' => $postData['content'],
        'category' => $postData['category'],
        'tags' => json_encode($postData['tags'], JSON_UNESCAPED_UNICODE),
        'author_id' => $authorId,
        'status' => 'published',
        'featured' => 0,
        'word_count' => $wordCount,
        'reading_time_min' => $readingTime,
        'published_at' => date('Y-m-d H:i:s', strtotime('-' . rand(1, 30) . ' days')),
    ]);

    echo "✓ Blog post: {$postData['title']}<br>";
}

// ============================================================
// MENUS
// ============================================================

$mainMenu = [
    ['label' => 'Služby', 'url' => '/sluzby', 'target' => ''],
    ['label' => 'Portfolio', 'url' => '/portfolio', 'target' => ''],
    ['label' => 'O nás', 'url' => '/o-nas', 'target' => ''],
    ['label' => 'Blog', 'url' => '/blog', 'target' => ''],
    ['label' => 'Kontakt', 'url' => '/kontakt', 'target' => ''],
];

$footerMenu = [
    ['label' => 'O nás', 'url' => '/o-nas', 'target' => ''],
    ['label' => 'Služby', 'url' => '/sluzby', 'target' => ''],
    ['label' => 'Blog', 'url' => '/blog', 'target' => ''],
    ['label' => 'Kontakt', 'url' => '/kontakt', 'target' => ''],
    ['label' => 'Ochrana osobních údajů', 'url' => '/ochrana-osobnich-udaju', 'target' => ''],
];

$db->query("UPDATE zvele_menus SET items = ? WHERE location = 'main'", [json_encode($mainMenu, JSON_UNESCAPED_UNICODE)]);
$db->query("UPDATE zvele_menus SET items = ? WHERE location = 'footer'", [json_encode($footerMenu, JSON_UNESCAPED_UNICODE)]);

echo "✓ Menu nastavena<br>";

// ============================================================
// REDIRECTS (demo)
// ============================================================
$db->insert('zvele_redirects', ['from_url' => '/about', 'to_url' => '/o-nas', 'status_code' => 301]);
$db->insert('zvele_redirects', ['from_url' => '/services', 'to_url' => '/sluzby', 'status_code' => 301]);
$db->insert('zvele_redirects', ['from_url' => '/contact', 'to_url' => '/kontakt', 'status_code' => 301]);

echo "✓ Ukázková přesměrování vytvořena<br>";

// ============================================================
// REGENERATE SITEMAP
// ============================================================
require_once CORE_PATH . '/Sitemap.php';
require_once CORE_PATH . '/Template.php';
Sitemap::regenerateAll();

echo "✓ Sitemap, robots.txt a llms.txt vygenerovány<br>";

echo "<br><strong style='color:green;'>✅ Demo web „Starter Studio" je připraven!</strong><br>";
echo "<br><a href='/'>→ Zobrazit web</a> | <a href='/admin'>→ Administrace</a><br>";
echo "<br><em style='color:red;'>⚠️ Smažte soubor seed.php po seedování!</em>";
