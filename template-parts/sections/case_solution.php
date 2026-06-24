<?php
/**
 * Block: Case Solution Section
 *
 * ACF block slug : acf/case-solution
 * bg #E9E9E9, heading + description, red subtitle badge, solution cards
 */

defined( 'ABSPATH' ) || exit;

$pt            = absint( get_field( 'padding_top' )        ?: 100 );
$pb            = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob        = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob        = absint( get_field( 'padding_bottom_mob' ) ?: 70 );
$title         = esc_html( get_field( 'title' ) ?: __( 'Solution', 'lionwood' ) );
$desc_raw      = get_field( 'description' );
$description   = $desc_raw ? wp_kses( $desc_raw, [ 'br' => [] ] ) : '';
$subtitle      = esc_html( get_field( 'subtitle' ) ?: __( 'Key decisions:', 'lionwood' ) );
$items         = get_field( 'items' ) ?: [];
$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#ffffff';

// Default check icon
$default_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19 19" fill="none" aria-hidden="true">
    <path d="M15.7031 4.77086C15.8453 4.91307 15.9252 5.10592 15.9252 5.307C15.9252 5.50808 15.8453 5.70093 15.7031 5.84314L8.11979 13.4265C7.97758 13.5686 7.78473 13.6485 7.58365 13.6485C7.38257 13.6485 7.18972 13.5686 7.04751 13.4265L3.25584 9.63481C3.11771 9.49178 3.04127 9.30023 3.043 9.1014C3.04473 8.90256 3.12448 8.71236 3.26508 8.57176C3.40568 8.43116 3.59588 8.35141 3.79471 8.34968C3.99355 8.34795 4.1851 8.42439 4.32813 8.56252L7.58365 11.818L14.6308 4.77086C14.7731 4.62869 14.9659 4.54883 15.167 4.54883C15.3681 4.54883 15.5609 4.62869 15.7031 4.77086Z" fill="#F7F7F7"/>
</svg>';
?>

<section
    class="csol-section"
    style="
        --csol-pt: <?php echo $pt; ?>px;
        --csol-pb: <?php echo $pb; ?>px;
        --csol-pt-mob: <?php echo $pt_mob; ?>px;
        --csol-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="csol-section__container">

        <?php /* ── Header: heading + description ────────────────────────── */ ?>
        <div class="csol-header">
            <h2 class="csol-title"><?php echo $title; ?></h2>
            <?php if ( $description ) : ?>
                <p class="csol-description"><?php echo $description; ?></p>
            <?php endif; ?>
        </div>

        <?php /* ── Subtitle badge ───────────────────────────────────────── */ ?>
        <?php if ( $subtitle ) : ?>
            <span class="csol-subtitle"><?php echo $subtitle; ?></span>
        <?php endif; ?>

        <?php /* ── Solution cards ──────────────────────────────────────── */ ?>
        <?php if ( ! empty( $items ) ) : ?>
            <div class="csol-cards">
                <?php foreach ( $items as $item ) :
                    $icon     = $item['icon'] ?? null;
                    $text_raw = $item['text'] ?? '';
                    $text     = $text_raw ? wp_kses( $text_raw, [ 'br' => [] ] ) : '';
                ?>
                    <div class="csol-card">
                        <div class="csol-card__icon-wrap">
                            <?php if ( $icon ) : ?>
                                <img
                                    class="csol-card__icon-img"
                                    src="<?php echo esc_url( $icon['url'] ); ?>"
                                    alt="<?php echo esc_attr( $icon['alt'] ?: '' ); ?>"
                                    width="26"
                                    height="26"
                                    loading="lazy"
                                >
                            <?php else : ?>
                                <span class="csol-card__icon-default" aria-hidden="true">
                                    <?php echo $default_icon; ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <?php if ( $text ) : ?>
                            <p class="csol-card__text"><?php echo $text; ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div><!-- .csol-section__container -->

    <?php if ( $decor_enabled ) : ?>
        <?php get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] ); ?>
    <?php endif; ?>

</section>
