</main><!-- main -->

<?php
$footer_logo        = get_field('logo', 'option');
$footer_support     = get_field('support_text', 'option');
$footer_emails      = get_field('emails', 'option') ?: [];
$footer_menu_cols   = get_field('menu_columns', 'option') ?: [];
$footer_partners    = get_field('partner_logos', 'option') ?: [];
$footer_offices     = get_field('offices', 'option') ?: [];
$footer_social      = get_field('social_links', 'option') ?: [];
$footer_copyright   = get_field('copyright', 'option');
?>

<footer class="footer-section" id="footer">
    <div class="footer-section__container">

        <?php /* ── Row 1: Logo + support text ── */ ?>
        <div class="footer-section__top">
            <?php if (!empty($footer_logo['url'])) : ?>
                <a href="<?= esc_url(home_url('/')); ?>" class="footer-section__logo-link">
                    <img
                        class="footer-section__logo"
                        src="<?= esc_url($footer_logo['url']); ?>"
                        alt="<?= esc_attr($footer_logo['alt'] ?: $footer_logo['title']); ?>"
                        width="120"
                        height="36"
                    >
                </a>
            <?php endif; ?>

            <?php if ($footer_support) : ?>
                <span class="footer-section__support"><?= esc_html($footer_support); ?></span>
            <?php endif; ?>
        </div><!-- .footer-section__top -->

        <?php /* ── Row 2: Emails + Menu columns ── */ ?>
        <div class="footer-section__nav">

            <?php if ($footer_emails) : ?>
                <div class="footer-section__emails">
                    <?php foreach ($footer_emails as $item) :
                        if (empty($item['email'])) continue;
                        $email = antispambot($item['email']);
                    ?>
                        <a class="footer-section__email-link" href="<?= esc_url('mailto:' . $email); ?>">
                            <?= esc_html($email); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ($footer_menu_cols) : ?>
                <div class="footer-section__menus">
                    <?php foreach ($footer_menu_cols as $col) : ?>
                        <div class="footer-section__menu-col">
                            <?php if (!empty($col['title'])) : ?>
                                <span class="footer-section__menu-title">
                                    <?= esc_html($col['title']); ?>
                                </span>
                            <?php endif; ?>

                            <?php if (!empty($col['links'])) : ?>
                                <ul class="footer-section__menu-list">
                                    <?php foreach ($col['links'] as $row) :
                                        $link = $row['link'] ?? null;
                                        if (empty($link['url'])) continue;
                                    ?>
                                        <li class="footer-section__menu-item">
                                            <a
                                                class="footer-section__menu-link"
                                                href="<?= esc_url($link['url']); ?>"
                                                <?= $link['target'] ? 'target="_blank" rel="noopener"' : ''; ?>
                                            ><?= esc_html($link['title']); ?></a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div><!-- .footer-section__nav -->

        <?php /* ── Row 3: Partner logos ── */ ?>
        <?php if ($footer_partners) : ?>
            <div class="footer-section__partners">
                <?php foreach ($footer_partners as $partner) :
                    if (empty($partner['logo']['url'])) continue;
                    $partner_link = $partner['url'] ?? null;
                    $img = '<img'
                        . ' class="footer-section__partner-logo"'
                        . ' src="' . esc_url($partner['logo']['url']) . '"'
                        . ' alt="' . esc_attr($partner['logo']['alt'] ?: $partner['logo']['title']) . '"'
                        . ' loading="lazy"'
                        . '>';
                ?>
                    <?php if (!empty($partner_link['url'])) : ?>
                        <a
                            class="footer-section__partner-link"
                            href="<?= esc_url($partner_link['url']); ?>"
                            <?= $partner_link['target'] ? 'target="_blank" rel="noopener"' : ''; ?>
                        ><?= $img; ?></a>
                    <?php else : ?>
                        <div class="footer-section__partner-item"><?= $img; ?></div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php /* ── Row 4: Office cards ── */ ?>
        <?php if ($footer_offices) : ?>
            <div class="footer-section__offices">
                <?php foreach ($footer_offices as $office) : ?>
                    <div class="footer-section__office">

                        <div class="footer-section__office-header">
                            <?php if (!empty($office['flag']['url'])) : ?>
                                <div class="footer-section__office-flag-wrap">
                                    <img
                                        class="footer-section__office-flag"
                                        src="<?= esc_url($office['flag']['url']); ?>"
                                        alt="<?= esc_attr($office['flag']['alt'] ?: $office['flag']['title']); ?>"
                                        width="24"
                                        height="24"
                                        loading="lazy"
                                    >
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($office['name'])) : ?>
                                <span class="footer-section__office-name">
                                    <?= esc_html($office['name']); ?>
                                </span>
                            <?php endif; ?>

                            <span class="footer-section__office-toggle footer-section__office-toggle--open" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                    <path d="M1.33317 8L7.99984 8M7.99984 8L14.6665 8M7.99984 8L7.99984 14.6667M7.99984 8L7.99984 1.33333" stroke="#F7F7F7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            <span class="footer-section__office-toggle footer-section__office-toggle--close" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                    <path d="M3.28578 3.28578L7.99982 7.99982M7.99982 7.99982L12.7139 12.7139M7.99982 7.99982L3.28578 12.7139M7.99982 7.99982L12.7139 3.28578" stroke="#F7F7F7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                        </div><!-- .footer-section__office-header -->

                        <div class="footer-section__office-body">

                            <?php if (!empty($office['address'])) : ?>
                                <p class="footer-section__office-address">
                                    <?= nl2br(esc_html($office['address'])); ?>
                                </p>
                            <?php endif; ?>

                            <?php if (!empty($office['phone'])) : ?>
                                <a
                                    class="footer-section__office-phone"
                                    href="tel:<?= esc_attr(preg_replace('/[^+\d]/', '', $office['phone'])); ?>"
                                ><?= esc_html($office['phone']); ?></a>
                            <?php endif; ?>

                            <?php
                            $lm = $office['learn_more'] ?? null;
                            if (!empty($lm['url'])) : ?>
                                <a
                                    class="footer-section__office-learn-more"
                                    href="<?= esc_url($lm['url']); ?>"
                                    <?= $lm['target'] ? 'target="_blank" rel="noopener"' : ''; ?>
                                ><?= esc_html($lm['title'] ?: 'Learn More'); ?></a>
                            <?php endif; ?>

                        </div><!-- .footer-section__office-body -->

                    </div><!-- .footer-section__office -->
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php /* ── Row 5: Social links ── */ ?>
        <?php if ($footer_social) : ?>
            <div class="footer-section__social">
                <?php foreach ($footer_social as $item) :
                    $sl = $item['link'] ?? null;
                    if (empty($sl['url'])) continue;
                    $sl_icon = $item['icon'] ?? null;
                ?>
                    <a
                        class="footer-section__social-btn"
                        href="<?= esc_url($sl['url']); ?>"
                        <?= $sl['target'] ? 'target="_blank" rel="noopener"' : ''; ?>
                        aria-label="<?= esc_attr($sl['title']); ?>"
                    >
                        <?php if (!empty($sl_icon['url'])) : ?>
                            <img
                                class="footer-section__social-icon"
                                src="<?= esc_url($sl_icon['url']); ?>"
                                alt=""
                                width="18"
                                height="18"
                                aria-hidden="true"
                                loading="lazy"
                            >
                        <?php endif; ?>
                        <span class="footer-section__social-label">
                            <?= esc_html($sl['title']); ?>
                        </span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php /* ── Row 6: Copyright ── */ ?>
        <?php if ($footer_copyright) : ?>
            <p class="footer-section__copyright">
                <?= esc_html($footer_copyright); ?>
            </p>
        <?php endif; ?>

    </div><!-- .footer-section__container -->
</footer><!-- .footer-section -->

</div><!-- wrapper -->
<?php wp_footer(); ?>
    </body>
</html>
