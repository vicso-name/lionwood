<?php
/**
 * Block: Simple Hero Section
 *
 * ACF block slug : acf/simple-hero
 * Template file  : blocks/simple-hero/simple-hero.php
 *
 * Layout:
 *   - Full-width H1: [DARK PART] [GRAY PART] — one row, flex space-between
 *   - Description: 392px, right-aligned (pushed to right half), margin-top 100px
 *   - Optional decor-bottom partial
 */

defined( 'ABSPATH' ) || exit;

$pt     = absint( get_field( 'padding_top' )        ?: 100 );
$pb     = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob = absint( get_field( 'padding_bottom_mob' ) ?: 70 );

$title_dark  = get_field( 'title_dark' )  ?: __( 'Our', 'theme' );
$title_gray  = get_field( 'title_gray' )  ?: __( 'Services', 'theme' );
$desc_raw    = get_field( 'description' );
$description = $desc_raw ? wp_kses( $desc_raw, [ 'br' => [] ] ) : '';

$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#ffffff';
?>

<section
    class="sh-section"
    style="
        --sh-pt: <?php echo $pt; ?>px;
        --sh-pb: <?php echo $pb; ?>px;
        --sh-pt-mob: <?php echo $pt_mob; ?>px;
        --sh-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="sh-section__container">

        <?php /* ── Heading ─────────────────────────────────────────────── */ ?>
        <h1 class="sh-heading">
            <span class="sh-heading__dark sh-anim" data-delay="0"><?php echo esc_html( $title_dark ); ?></span>
            <span class="sh-heading__gray sh-anim" data-delay="100"><?php echo esc_html( $title_gray ); ?></span>
        </h1>

        <?php /* ── Description — right-aligned, below heading ───────────── */ ?>
        <?php if ( $description ) : ?>
            <div class="sh-description-wrap sh-anim" data-delay="220">
                <p class="sh-description"><?php echo $description; ?></p>
            </div>
        <?php endif; ?>

    </div><!-- .sh-section__container -->

    <?php if ( $decor_enabled ) : ?>
        <?php get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] ); ?>
    <?php endif; ?>

</section>
