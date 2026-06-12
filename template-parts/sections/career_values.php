<?php
/**
 * Block: Career Values Section
 * Slug: acf/career-values
 *
 * Identical to value-section (vs-) but:
 *  - Icon (SVG/img, 36×36) instead of number
 *  - Description text (234px, white, opacity 0.8) instead of label
 */

defined( 'ABSPATH' ) || exit;

$pt       = absint( get_field( 'padding_top' )        ?: 100 );
$pb       = absint( get_field( 'padding_bottom' )     ?: 80 );
$pt_mob   = absint( get_field( 'padding_top_mob' )    ?: 60 );
$pb_mob   = absint( get_field( 'padding_bottom_mob' ) ?: 48 );
$items    = get_field( 'items' ) ?: [];
$bg_color = get_field( 'bg_color' ) ?: '#C83030';

// Default check icon SVG
$default_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none" aria-hidden="true">
    <path d="M21.7404 6.60625C21.9372 6.80316 22.0478 7.07018 22.0478 7.3486C22.0478 7.62703 21.9372 7.89405 21.7404 8.09095L11.2404 18.591C11.0435 18.7878 10.7765 18.8984 10.498 18.8984C10.2196 18.8984 9.95259 18.7878 9.75569 18.591L4.50569 13.341C4.31442 13.1429 4.20858 12.8777 4.21098 12.6024C4.21337 12.3271 4.3238 12.0637 4.51848 11.869C4.71315 11.6744 4.97651 11.5639 5.25181 11.5615C5.52712 11.5592 5.79235 11.665 5.99039 11.8563L10.498 16.3639L20.2557 6.60625C20.4526 6.40941 20.7196 6.29883 20.998 6.29883C21.2765 6.29883 21.5435 6.40941 21.7404 6.60625Z" fill="#C83030"/>
</svg>';
?>

<section
    class="cvs-section"
    style="
        --cvs-pt: <?php echo $pt; ?>px;
        --cvs-pb: <?php echo $pb; ?>px;
        --cvs-pt-mob: <?php echo $pt_mob; ?>px;
        --cvs-pb-mob: <?php echo $pb_mob; ?>px;
        --cvs-bg: <?php echo esc_attr( $bg_color ); ?>;
    "
    aria-label="<?php esc_attr_e( 'Career Values', 'theme' ); ?>"
>
    <?php if ( ! empty( $items ) ) : ?>
        <div class="cvs-row">
            <?php foreach ( $items as $i => $item ) :
                $icon_img  = $item['icon']        ?? null;
                $desc_raw  = $item['description'] ?? '';
                $desc      = $desc_raw ? wp_kses( $desc_raw, [ 'br' => [] ] ) : '';
                if ( ! $desc ) continue;
            ?>
                <?php if ( $i > 0 ) : ?>
                    <div class="cvs-divider" aria-hidden="true"></div>
                <?php endif; ?>

                <div class="cvs-item" data-index="<?php echo $i; ?>">
                    <div class="cvs-item__icon" aria-hidden="true">
                        <?php if ( $icon_img ) : ?>
                            <img
                                src="<?php echo esc_url( $icon_img['url'] ); ?>"
                                alt=""
                                width="36"
                                height="36"
                                loading="lazy"
                            >
                        <?php else : ?>
                            <?php echo $default_icon; ?>
                        <?php endif; ?>
                    </div>
                    <p class="cvs-item__desc"><?php echo $desc; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php /* ── Animated bars — identical to vs-bars ── */ ?>
    <div class="cvs-bars" aria-hidden="true">
        <span class="cvs-bar" data-base="51.8"></span>
        <span class="cvs-bar" data-base="70.1"></span>
        <span class="cvs-bar" data-base="83.2"></span>
        <span class="cvs-bar" data-base="92.6"></span>
        <span class="cvs-bar" data-base="97.9"></span>
    </div>

</section>
