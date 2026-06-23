<?php
/**
 * Block: Case Hero
 *
 * ACF block slug : acf/case-hero
 * Template file  : blocks/case-hero/case-hero.php
 *
 * Same as Simple Hero + auto case count badge shown next to heading.
 */

defined( 'ABSPATH' ) || exit;

// $args takes priority over ACF block fields — used by archive/taxonomy templates
// to pass values from the Options Page without an ACF block context.
$pt     = absint( $args['padding_top']        ?? get_field( 'padding_top' )        ?? 100 );
$pb     = absint( $args['padding_bottom']     ?? get_field( 'padding_bottom' )     ?? 100 );
$pt_mob = absint( $args['padding_top_mob']    ?? get_field( 'padding_top_mob' )    ?? 70 );
$pb_mob = absint( $args['padding_bottom_mob'] ?? get_field( 'padding_bottom_mob' ) ?? 70 );

$title_dark  = $args['title_dark']  ?? get_field( 'title_dark' )  ?? __( 'Case', 'theme' );
$title_gray  = $args['title_gray']  ?? get_field( 'title_gray' )  ?? __( 'Studies', 'theme' );
$desc_raw    = $args['description'] ?? get_field( 'description' ) ?? '';
$description = $desc_raw ? wp_kses( $desc_raw, [ 'br' => [] ] ) : '';

$decor_enabled = $args['decor_bottom_enabled'] ?? get_field( 'decor_bottom_enabled' ) ?? false;
$decor_color   = $args['decor_bottom_color']   ?? get_field( 'decor_bottom_color' )   ?? '#ffffff';

// Auto-count published case studies
$case_count = wp_count_posts( 'case_study' )->publish ?? 0;
?>

<section
    class="ch-section"
    style="
        --ch-pt: <?php echo $pt; ?>px;
        --ch-pb: <?php echo $pb; ?>px;
        --ch-pt-mob: <?php echo $pt_mob; ?>px;
        --ch-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="ch-section__container">

        <?php /* ── Heading ─────────────────────────────────────────────── */ ?>
        <h1 class="ch-heading">
            <span class="ch-heading__dark ch-anim" data-delay="0"><?php echo esc_html( $title_dark ); ?></span>
            <span class="ch-heading__gray ch-anim" data-delay="100"><?php echo esc_html( $title_gray ); ?></span>
            <?php if ( $case_count ) : ?>
                <span class="ch-heading__count ch-anim" data-delay="180" aria-label="<?php echo esc_attr( sprintf( __( '%d cases', 'theme' ), $case_count ) ); ?>">
                    (<?php echo esc_html( $case_count ); ?>)
                </span>
            <?php endif; ?>
        </h1>

        <?php /* ── Description — right-aligned, below heading ───────────── */ ?>
        <?php if ( $description ) : ?>
            <div class="ch-description-wrap ch-anim" data-delay="260">
                <p class="ch-description"><?php echo $description; ?></p>
            </div>
        <?php endif; ?>

    </div><!-- .ch-section__container -->

    <?php if ( $decor_enabled ) : ?>
        <?php get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] ); ?>
    <?php endif; ?>

</section>
