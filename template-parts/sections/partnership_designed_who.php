<?php
/**
 * Block: Partnership Designed Who
 * Slug: acf/partnership-designed-who
 *
 * Based on career-grow (cgw-) structure.
 * Changes:
 *  - 3-line heading (76px, line1 shifted 50% right, line3 opacity 0.5)
 *  - Left col: description text only (no checklist)
 *  - Right col: label badge + checklist (no image)
 *  - Light bg #F7F7F7
 */

defined( 'ABSPATH' ) || exit;

$pt     = absint( get_field('padding_top')        ?: 100 );
$pb     = absint( get_field('padding_bottom')     ?: 200 );
$pt_mob = absint( get_field('padding_top_mob')    ?: 70 );
$pb_mob = absint( get_field('padding_bottom_mob') ?: 140 );

$title_line1  = get_field('title_line1')  ?: 'Who the Referral';
$title_line2  = get_field('title_line2')  ?: 'Partner Program';
$title_line3  = get_field('title_line3')  ?: 'Is Designed For';
$description  = get_field('description');
$label_text   = get_field('label_text')   ?: '';
$items        = get_field('items') ?: [];

$decor_enabled = get_field('decor_bottom_enabled');
$decor_color   = get_field('decor_bottom_color') ?: '#C83030';

$check_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13" fill="none" aria-hidden="true">
    <path d="M10.8741 3.30313C10.9725 3.40158 11.0278 3.53509 11.0278 3.6743C11.0278 3.81351 10.9725 3.94702 10.8741 4.04548L5.6241 9.29548C5.52565 9.3939 5.39214 9.44919 5.25292 9.44919C5.11371 9.44919 4.9802 9.3939 4.88175 9.29548L2.25675 6.67048C2.16112 6.57146 2.1082 6.43884 2.10939 6.30119C2.11059 6.16354 2.1658 6.03186 2.26314 5.93452C2.36048 5.83718 2.49216 5.78197 2.62981 5.78077C2.76747 5.77958 2.90008 5.83249 2.9991 5.92813L5.25292 8.18195L10.1317 3.30313C10.2302 3.2047 10.3637 3.14941 10.5029 3.14941C10.6421 3.14941 10.7756 3.2047 10.8741 3.30313Z" fill="#F7F7F7"/>
</svg>';
?>

<section
    class="prd-section"
    style="
        --prd-pt: <?php echo $pt; ?>px;
        --prd-pb: <?php echo $pb; ?>px;
        --prd-pt-mob: <?php echo $pt_mob; ?>px;
        --prd-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <?php /* ── Three-line heading ── */ ?>
    <div class="prd-section__container">
        <div class="prd-heading">
            <span class="prd-heading__line1"><?php echo esc_html( $title_line1 ); ?></span>
            <span class="prd-heading__line2"><?php echo esc_html( $title_line2 ); ?></span>
            <span class="prd-heading__line3"><?php echo esc_html( $title_line3 ); ?></span>
        </div>
    </div>

    <div class="prd-section__container">

        <?php /* ── Top divider ── */ ?>
        <div class="prd-divider" aria-hidden="true"></div>

        <?php /* ── Body row ── */ ?>
        <div class="prd-body">

            <?php /* ── Left col: description text ── */ ?>
            <div class="prd-col--text">
                <?php if ( $description ) : ?>
                    <p class="prd-description"><?php echo nl2br( esc_html( $description ) ); ?></p>
                <?php endif; ?>
            </div>

            <?php /* ── Vertical divider ── */ ?>
            <div class="prd-body__divider" aria-hidden="true"></div>

            <?php /* ── Right col: label + checklist ── */ ?>
            <div class="prd-col--list">

                <?php if ( $label_text ) : ?>
                    <span class="prd-label"><?php echo esc_html( $label_text ); ?></span>
                <?php endif; ?>

                <?php if ( ! empty( $items ) ) : ?>
                    <ul class="prd-list">
                        <?php foreach ( $items as $item ) :
                            $text = esc_html( $item['text'] ?? '' );
                            if ( ! $text ) continue;
                        ?>
                            <li class="prd-list__item">
                                <span class="prd-list__icon"><?php echo $check_svg; ?></span>
                                <span class="prd-list__text"><?php echo $text; ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

            </div>

        </div>

    </div>

    <?php if ( $decor_enabled ) :
        get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] );
    endif; ?>
</section>
