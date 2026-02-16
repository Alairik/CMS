<?php defined('ZVELE_CMS') or die(); ?>

<?php if (($totalPages ?? 1) > 1): ?>
<nav class="pagination" aria-label="Stránkování">
    <ul class="pagination__list">
        <?php if ($currentPage > 1): ?>
        <li>
            <a href="<?= e($baseUrl) ?>?page=<?= $currentPage - 1 ?>" class="pagination__link" aria-label="Předchozí stránka">&laquo; Předchozí</a>
        </li>
        <?php endif; ?>

        <?php
        $start = max(1, $currentPage - 2);
        $end = min($totalPages, $currentPage + 2);
        ?>

        <?php if ($start > 1): ?>
            <li><a href="<?= e($baseUrl) ?>?page=1" class="pagination__link">1</a></li>
            <?php if ($start > 2): ?><li><span class="pagination__dots">&hellip;</span></li><?php endif; ?>
        <?php endif; ?>

        <?php for ($i = $start; $i <= $end; $i++): ?>
        <li>
            <?php if ($i === $currentPage): ?>
                <span class="pagination__link pagination__link--active" aria-current="page"><?= $i ?></span>
            <?php else: ?>
                <a href="<?= e($baseUrl) ?>?page=<?= $i ?>" class="pagination__link"><?= $i ?></a>
            <?php endif; ?>
        </li>
        <?php endfor; ?>

        <?php if ($end < $totalPages): ?>
            <?php if ($end < $totalPages - 1): ?><li><span class="pagination__dots">&hellip;</span></li><?php endif; ?>
            <li><a href="<?= e($baseUrl) ?>?page=<?= $totalPages ?>" class="pagination__link"><?= $totalPages ?></a></li>
        <?php endif; ?>

        <?php if ($currentPage < $totalPages): ?>
        <li>
            <a href="<?= e($baseUrl) ?>?page=<?= $currentPage + 1 ?>" class="pagination__link" aria-label="Další stránka">Další &raquo;</a>
        </li>
        <?php endif; ?>
    </ul>
</nav>
<?php endif; ?>
