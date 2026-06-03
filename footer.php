</main><!-- main -->

<?php
$footer_logo        = get_field('logo', 'option');
$footer_support      = get_field('support_text', 'option');
$footer_support_flag = get_field('support_flag', 'option');
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

            <?php if ($footer_support || !empty($footer_support_flag['url'])) : ?>
                <div class="footer-section__support">
                    <?php if (!empty($footer_support_flag['url'])) : ?>
                        <img
                            class="footer-section__support-flag"
                            src="<?= esc_url($footer_support_flag['url']); ?>"
                            alt="<?= esc_attr($footer_support_flag['alt'] ?: ''); ?>"
                            loading="lazy"
                        >
                    <?php endif; ?>
                    <?php if ($footer_support) : ?>
                        <span><?= esc_html($footer_support); ?></span>
                    <?php endif; ?>
                </div>
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
                        <a class="footer-section__email-link" href="<?= esc_url('mailto:' . $email); ?>" target="_blank" rel="noopener">
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

            <?php /* ── Row 3: Partner logos (inside nav for ordering at 568px) ── */ ?>
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

        </div><!-- .footer-section__nav -->

        <?php /* ── Row 4: Office cards ── */ ?>
        <?php if ($footer_offices) : ?>
            <div class="footer-section__offices">
                <?php foreach ($footer_offices as $office) : ?>
                    <div class="footer-section__office">

                        <div class="footer-section__office-header">
                            <div class="footer-section__office-flag">
                                <?php if (!empty($office['flag']['url'])) : ?>
                                    <img
                                        src="<?= esc_url($office['flag']['url']); ?>"
                                        alt=""
                                        width="24"
                                        height="24"
                                        loading="lazy"
                                    >
                                <?php endif; ?>
                            </div>

                            <span class="footer-section__office-name">
                                <?= esc_html($office['name']); ?>
                            </span>

                            <button class="footer-section__office-toggle" aria-expanded="false">
                                <span class="footer-section__office-icon footer-section__office-icon--plus" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                        <path d="M1.33317 8L7.99984 8M7.99984 8L14.6665 8M7.99984 8L7.99984 14.6667M7.99984 8L7.99984 1.33333" stroke="#F7F7F7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                                <span class="footer-section__office-icon footer-section__office-icon--close" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                        <path d="M3.28578 3.28578L7.99982 7.99982M7.99982 7.99982L12.7139 12.7139M7.99982 7.99982L3.28578 12.7139M7.99982 7.99982L12.7139 3.28578" stroke="#F7F7F7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                            </button>
                        </div><!-- .footer-section__office-header -->

                        <div class="footer-section__office-body">
                            <p class="footer-section__office-address">
                                <?= nl2br(esc_html($office['address'])); ?>
                            </p>

                            <?php if (!empty($office['phone'])) : ?>
                                <a
                                    class="footer-section__office-phone"
                                    href="tel:<?= esc_attr(preg_replace('/[^+\d]/', '', $office['phone'])); ?>"
                                ><?= esc_html($office['phone']); ?></a>
                            <?php endif; ?>

                        </div><!-- .footer-section__office-body -->

                        <?php if (!empty($office['learn_more']['url'])) : ?>
                            <a
                                class="footer-section__office-learn"
                                href="<?= esc_url($office['learn_more']['url']); ?>"
                                <?= $office['learn_more']['target'] ? 'target="_blank" rel="noopener"' : ''; ?>
                            ><?= esc_html($office['learn_more']['title'] ?: 'Learn More'); ?></a>
                        <?php endif; ?>

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
                        target="<?= esc_attr($sl['target'] ?: '_blank'); ?>"
                        rel="noopener"
                        aria-label="<?= esc_attr($sl['title']); ?>"
                    >
                        <svg class="footer-section__social-arrow" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                            <g clip-path="url(#clip0_social_arrow)">
                                <path d="M13.2442 11.5835L13.2266 4.7705L6.41358 4.75282C6.3426 4.74321 6.27039 4.74897 6.20182 4.76969C6.13326 4.79042 6.06995 4.82563 6.01617 4.87296C5.9624 4.92028 5.91943 4.9786 5.89016 5.04397C5.86088 5.10935 5.846 5.18025 5.84651 5.25188C5.84702 5.3235 5.86292 5.39418 5.89312 5.45913C5.92332 5.52408 5.96713 5.58178 6.02157 5.62833C6.07601 5.67488 6.13982 5.70919 6.20867 5.72893C6.27753 5.74868 6.34982 5.7534 6.42065 5.74277L11.526 5.76399L4.76249 12.5275C4.66872 12.6212 4.61604 12.7484 4.61604 12.881C4.61604 13.0136 4.66872 13.1408 4.76249 13.2346C4.85626 13.3283 4.98343 13.381 5.11604 13.381C5.24865 13.381 5.37583 13.3283 5.4696 13.2346L12.2331 6.47109L12.2543 11.5764C12.2548 11.7091 12.3079 11.8361 12.4021 11.9296C12.4962 12.0231 12.6236 12.0754 12.7563 12.0749C12.889 12.0744 13.0161 12.0213 13.1096 11.9271C13.2031 11.833 13.2553 11.7056 13.2548 11.5729L13.2442 11.5835Z" fill="currentColor"/>
                            </g>
                            <defs>
                                <clipPath id="clip0_social_arrow">
                                    <rect width="18" height="18" fill="white"/>
                                </clipPath>
                            </defs>
                        </svg>

                        <span class="footer-section__social-label">
                            <?= esc_html($sl['title']); ?>
                        </span>

                        <?php if (!empty($sl_icon['url'])) : ?>
                            <img
                                class="footer-section__social-icon"
                                src="<?= esc_url($sl_icon['url']); ?>"
                                alt="<?= esc_attr($sl['title']); ?>"
                                width="18"
                                height="18"
                                loading="lazy"
                            >
                        <?php endif; ?>
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
