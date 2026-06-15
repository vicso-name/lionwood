<?php
/**
 * Block: Partnership Quote
 * Slug: acf/partnership-quote
 */

defined( 'ABSPATH' ) || exit;

$pt     = absint( get_field( 'padding_top' )        ?: 100 );
$pb     = absint( get_field( 'padding_bottom' )     ?: 200 );
$pt_mob = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob = absint( get_field( 'padding_bottom_mob' ) ?: 140 );

$image   = get_field( 'image' );
$quote   = get_field( 'quote' ) ?: '';

$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#111319';

$quote_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none" aria-hidden="true">
    <path d="M2.70312 7.41699C2.70312 6.31242 3.59856 5.41699 4.70312 5.41699H9.91146C11.016 5.41699 11.9115 6.31242 11.9115 7.41699V13.0698C11.9115 13.4828 11.7836 13.8856 11.5454 14.2231L7.05596 20.5837H4.10604L6.99421 14.0837H4.70313C3.59856 14.0837 2.70312 13.1882 2.70312 12.0837V7.41699ZM14.0781 7.41699C14.0781 6.31242 14.9736 5.41699 16.0781 5.41699H21.2865C22.391 5.41699 23.2865 6.31242 23.2865 7.41699V13.0698C23.2865 13.4828 23.1586 13.8856 22.9204 14.2231L18.431 20.5837H15.481L18.3692 14.0837H16.0781C14.9736 14.0837 14.0781 13.1882 14.0781 12.0837V7.41699Z" fill="#F7F7F7"/>
</svg>';
?>

<section
    class="pq-section"
    style="
        --pq-pt: <?php echo $pt; ?>px;
        --pq-pb: <?php echo $pb; ?>px;
        --pq-pt-mob: <?php echo $pt_mob; ?>px;
        --pq-pb-mob: <?php echo $pb_mob; ?>px;
        <?php if ( $image ) : ?>--pq-bg: url('<?php echo esc_url( $image['url'] ); ?>');<?php endif; ?>
    "
>
    <?php /* ── Overlay ── */ ?>
    <div class="pq-overlay" aria-hidden="true"></div>

    <?php /* ── Content ── */ ?>
    <div class="pq-section__container">
        <div class="pq-content">
            <span class="pq-icon"><?php echo $quote_svg; ?></span>
            <?php if ( $quote ) : ?>
                <blockquote class="pq-quote"><?php echo wp_kses( $quote, [ 'br' => [], 'em' => [], 'strong' => [] ] ); ?></blockquote>
            <?php endif; ?>
        </div>
    </div>

    <?php if ( $decor_enabled ) :
        get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] );
    endif; ?>
</section>
