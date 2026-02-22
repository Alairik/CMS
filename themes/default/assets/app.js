/**
 * ZveleCMS Default Theme — Frontend JS (~5KB)
 * Cookie consent + Mobile menu + Smooth scroll
 * Vanilla JS, zero dependencies.
 */

(function () {
    'use strict';

    /* === Header Scroll Effect === */
    var header = document.querySelector('.site-header');
    if (header) {
        var scrolled = false;
        window.addEventListener('scroll', function () {
            var isScrolled = window.scrollY > 10;
            if (isScrolled !== scrolled) {
                scrolled = isScrolled;
                header.classList.toggle('site-header--scrolled', scrolled);
            }
        }, { passive: true });
    }

    /* === Mobile Menu === */
    var menuToggle = document.querySelector('[data-menu-toggle]');
    var menu = document.querySelector('[data-menu]');

    if (menuToggle && menu) {
        menuToggle.addEventListener('click', function () {
            var isOpen = menu.classList.toggle('is-open');
            menuToggle.setAttribute('aria-expanded', isOpen);
        });

        // Close on Escape
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && menu.classList.contains('is-open')) {
                menu.classList.remove('is-open');
                menuToggle.setAttribute('aria-expanded', 'false');
                menuToggle.focus();
            }
        });
    }

    /* === Cookie Consent === */
    var COOKIE_NAME = 'cookie_consent';
    var COOKIE_DAYS = 365;

    var banner = document.getElementById('cookieBanner');
    var categories = document.getElementById('cookieCategories');
    var analyticsCheckbox = document.getElementById('cookieAnalytics');
    var marketingCheckbox = document.getElementById('cookieMarketing');

    if (!banner) return;

    function getCookie(name) {
        var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
        return match ? decodeURIComponent(match[2]) : null;
    }

    function setCookie(name, value, days) {
        var expires = new Date(Date.now() + days * 86400000).toUTCString();
        document.cookie = name + '=' + encodeURIComponent(value) + ';expires=' + expires + ';path=/;SameSite=Lax';
    }

    function getConsent() {
        var raw = getCookie(COOKIE_NAME);
        if (!raw) return null;
        try { return JSON.parse(raw); } catch (e) { return null; }
    }

    function saveConsent(consent) {
        setCookie(COOKIE_NAME, JSON.stringify(consent), COOKIE_DAYS);
        hideBanner();
        applyConsent(consent);

        // Log consent to backend
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/form/consent-log', true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.send(JSON.stringify(consent));
    }

    function showBanner() {
        banner.classList.add('is-visible');
        banner.setAttribute('aria-hidden', 'false');
    }

    function hideBanner() {
        banner.classList.remove('is-visible');
        banner.setAttribute('aria-hidden', 'true');
    }

    function applyConsent(consent) {
        if (consent.analytics) {
            loadAnalytics();
        }
        if (consent.marketing) {
            loadMarketing();
        }
    }

    function loadAnalytics() {
        // GTM
        var gtmId = document.querySelector('meta[name="gtm-id"]');
        if (gtmId && gtmId.content) {
            var s = document.createElement('script');
            s.textContent = "(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','" + gtmId.content + "');";
            document.head.appendChild(s);
        }

        // GA4
        var ga4Id = document.querySelector('meta[name="ga4-id"]');
        if (ga4Id && ga4Id.content) {
            var gs = document.createElement('script');
            gs.async = true;
            gs.src = 'https://www.googletagmanager.com/gtag/js?id=' + ga4Id.content;
            document.head.appendChild(gs);
            var gi = document.createElement('script');
            gi.textContent = "window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','" + ga4Id.content + "');";
            document.head.appendChild(gi);
        }
    }

    function loadMarketing() {
        // Meta Pixel
        var pixelId = document.querySelector('meta[name="meta-pixel-id"]');
        if (pixelId && pixelId.content) {
            var ps = document.createElement('script');
            ps.textContent = "!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init','" + pixelId.content + "');fbq('track','PageView');";
            document.head.appendChild(ps);
        }

        // Google Ads
        var gadsId = document.querySelector('meta[name="gads-id"]');
        if (gadsId && gadsId.content) {
            var as = document.createElement('script');
            as.async = true;
            as.src = 'https://www.googletagmanager.com/gtag/js?id=' + gadsId.content;
            document.head.appendChild(as);
            var ai = document.createElement('script');
            ai.textContent = "window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','" + gadsId.content + "');";
            document.head.appendChild(ai);
        }
    }

    // Accept all
    var acceptAll = banner.querySelector('[data-cookie-accept-all]');
    if (acceptAll) {
        acceptAll.addEventListener('click', function () {
            saveConsent({ necessary: true, analytics: true, marketing: true });
        });
    }

    // Reject all
    var rejectAll = banner.querySelector('[data-cookie-reject-all]');
    if (rejectAll) {
        rejectAll.addEventListener('click', function () {
            saveConsent({ necessary: true, analytics: false, marketing: false });
        });
    }

    // Show settings
    var showSettings = banner.querySelector('[data-cookie-show-settings]');
    var saveSettings = banner.querySelector('[data-cookie-save-settings]');
    if (showSettings && categories && saveSettings) {
        showSettings.addEventListener('click', function () {
            categories.hidden = false;
            showSettings.hidden = true;
            saveSettings.hidden = false;
        });

        saveSettings.addEventListener('click', function () {
            saveConsent({
                necessary: true,
                analytics: analyticsCheckbox ? analyticsCheckbox.checked : false,
                marketing: marketingCheckbox ? marketingCheckbox.checked : false
            });
        });
    }

    // Footer "edit preferences" button
    var editPrefs = document.querySelector('[data-cookie-settings]');
    if (editPrefs) {
        editPrefs.addEventListener('click', function () {
            var consent = getConsent();
            if (consent && analyticsCheckbox && marketingCheckbox) {
                analyticsCheckbox.checked = consent.analytics || false;
                marketingCheckbox.checked = consent.marketing || false;
            }
            if (categories) categories.hidden = false;
            if (showSettings) showSettings.hidden = true;
            if (saveSettings) saveSettings.hidden = false;
            showBanner();
        });
    }

    // Init: show banner or apply saved consent
    var existingConsent = getConsent();
    if (existingConsent) {
        applyConsent(existingConsent);
    } else {
        showBanner();
    }

})();
