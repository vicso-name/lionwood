<?php
/**
 * Block: Privacy Hero Section
 * Slug: acf/privacy-hero
 */

defined( 'ABSPATH' ) || exit;

$pt     = absint( get_field( 'padding_top' )        ?: 100 );
$pb     = absint( get_field( 'padding_bottom' )     ?: 200 );
$pt_mob = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob = absint( get_field( 'padding_bottom_mob' ) ?: 140 );

$title_word1   = get_field( 'title_word1' ) ?: 'Privacy';
$title_word2   = get_field( 'title_word2' ) ?: 'Policy';
$content       = get_field( 'content' );

$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#E9E9E9';
?>

<section
    class="prh-section"
    style="
        --prh-pt: <?php echo $pt; ?>px;
        --prh-pb: <?php echo $pb; ?>px;
        --prh-pt-mob: <?php echo $pt_mob; ?>px;
        --prh-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="prh-section__container">

        <h1 class="prh-title">
            <span class="prh-title__word1"><?php echo esc_html( $title_word1 ); ?></span>
            <span class="prh-title__word2"><?php echo esc_html( $title_word2 ); ?></span>
        </h1>

        <?php if ( $content ) : ?>
            <div class="prh-content">
                <?php echo wp_kses_post( $content ); ?>
            </div>
        <?php endif; ?>

    </div>

    <?php if ( $decor_enabled ) :
        get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] );
    endif; ?>
</section>
