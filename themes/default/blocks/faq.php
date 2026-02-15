<?php defined('ZVELE_CMS') or die(); ?>

<section class="section">
    <div class="container container--narrow">
        <?php if (!empty($title)): ?>
            <h2 class="section__title"><?= e($title) ?></h2>
        <?php endif; ?>

        <?php if (!empty($items)): ?>
        <div class="faq-list">
            <?php foreach ($items as $i => $item): ?>
            <details class="faq-item">
                <summary class="faq-item__question"><?= e($item['question'] ?? '') ?></summary>
                <div class="faq-item__answer">
                    <p><?= e($item['answer'] ?? '') ?></p>
                </div>
            </details>
            <?php endforeach; ?>
        </div>

        <!-- FAQPage JSON-LD -->
        <script type="application/ld+json">
        <?php
        $faqSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => array_map(fn($item) => [
                '@type' => 'Question',
                'name' => $item['question'] ?? '',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $item['answer'] ?? '',
                ],
            ], $items),
        ];
        echo json_encode($faqSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        ?>
        </script>
        <?php endif; ?>
    </div>
</section>
