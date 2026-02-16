<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-col">
                <h4><?= h(SITE_NAME) ?></h4>
                <p>Moderní redakční systém postavený na PHP a MySQL. Spravujte obsah jednoduše a elegantně.</p>
            </div>
            <div class="footer-col">
                <h4>Kategorie</h4>
                <ul>
                    <?php foreach ($allCategories as $cat): ?>
                    <li><a href="<?= SITE_URL ?>/kategorie/<?= h($cat['slug']) ?>"><?= h($cat['name']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="footer-col">
                <h4>API</h4>
                <ul>
                    <li><a href="<?= SITE_URL ?>/api/articles.php" target="_blank">Články (JSON)</a></li>
                </ul>
                <h4 style="margin-top: var(--space-6);">Technologie</h4>
                <div class="tech-item">PHP <?= PHP_MAJOR_VERSION ?>.<?= PHP_MINOR_VERSION ?></div>
                <div class="tech-item">MySQL / MariaDB</div>
                <div class="tech-item">Vanilla CSS &amp; JS</div>
            </div>
        </div>
        <div class="footer-bottom">
            <span>&copy; <?= date('Y') ?> <?= h(SITE_NAME) ?></span>
            <span>Vytvořeno s vlastním CMS</span>
        </div>
    </div>
</footer>

<script src="<?= SITE_URL ?>/assets/js/main.js"></script>
</body>
</html>
