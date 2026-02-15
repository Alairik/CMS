<?php defined('ZVELE_CMS') or die(); ?>

<div class="cookie-banner" id="cookieBanner" role="dialog" aria-label="Cookie consent" aria-hidden="true">
    <div class="cookie-banner__content">
        <h2 class="cookie-banner__title"><?= e(setting('cookie_banner_title', 'Tato stránka používá cookies')) ?></h2>
        <p class="cookie-banner__text"><?= e(setting('cookie_banner_text', 'Používáme cookies pro analýzu návštěvnosti a zlepšení funkčnosti webu.')) ?></p>

        <div class="cookie-banner__categories" id="cookieCategories" hidden>
            <div class="cookie-category">
                <label class="cookie-category__label">
                    <input type="checkbox" checked disabled>
                    <strong>Nezbytné cookies</strong>
                </label>
                <p class="cookie-category__text">Vždy aktivní. Nutné pro fungování webu.</p>
            </div>
            <div class="cookie-category">
                <label class="cookie-category__label">
                    <input type="checkbox" id="cookieAnalytics" value="analytics">
                    <strong><?= e(setting('cookie_category_analytics_label', 'Analytické cookies')) ?></strong>
                </label>
                <p class="cookie-category__text"><?= e(setting('cookie_category_analytics_text', 'Pomáhají nám pochopit, jak web používáte.')) ?></p>
            </div>
            <div class="cookie-category">
                <label class="cookie-category__label">
                    <input type="checkbox" id="cookieMarketing" value="marketing">
                    <strong><?= e(setting('cookie_category_marketing_label', 'Marketingové cookies')) ?></strong>
                </label>
                <p class="cookie-category__text"><?= e(setting('cookie_category_marketing_text', 'Slouží k personalizaci reklam.')) ?></p>
            </div>
        </div>

        <div class="cookie-banner__actions">
            <button type="button" class="btn btn--primary" data-cookie-accept-all>
                <?= e(setting('cookie_banner_accept', 'Přijmout vše')) ?>
            </button>
            <button type="button" class="btn btn--outline" data-cookie-reject-all>
                <?= e(setting('cookie_banner_reject', 'Odmítnout vše')) ?>
            </button>
            <button type="button" class="btn btn--text" data-cookie-show-settings>
                <?= e(setting('cookie_banner_settings', 'Nastavit předvolby')) ?>
            </button>
            <button type="button" class="btn btn--primary" data-cookie-save-settings hidden>
                Uložit předvolby
            </button>
        </div>
    </div>
</div>
