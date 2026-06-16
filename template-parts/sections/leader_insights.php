<?php
/**
 * Block: Leader Insights
 * Slug: acf/leader-insights
 *
 * Dark bg by default, author block + large quote text.
 */

defined( 'ABSPATH' ) || exit;

$pt     = absint( get_field( 'padding_top' )        ?: 70 );
$pb     = absint( get_field( 'padding_bottom' )     ?: 70 );
$pt_mob = absint( get_field( 'padding_top_mob' )    ?: 50 );
$pb_mob = absint( get_field( 'padding_bottom_mob' ) ?: 50 );

$bg_color    = get_field( 'bg_color' )    ?: '#111319';
$photo       = get_field( 'author_photo' );
$author_name = get_field( 'author_name' ) ?: '';
$author_role = get_field( 'author_role' ) ?: '';
$content     = get_field( 'content' );

$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#F7F7F7';
?>

<section
    class="li-section"
    style="
        --li-pt: <?php echo $pt; ?>px;
        --li-pb: <?php echo $pb; ?>px;
        --li-pt-mob: <?php echo $pt_mob; ?>px;
        --li-pb-mob: <?php echo $pb_mob; ?>px;
        --li-bg: <?php echo esc_attr( $bg_color ); ?>;
    "
>
    <div class="li-section__container">

        <?php /* ── Author block ── */ ?>
        <div class="li-author">
            <?php if ( $photo ) : ?>
                <div class="li-author__photo">
                    <img
                        src="<?php echo esc_url( $photo['url'] ); ?>"
                        alt="<?php echo esc_attr( $photo['alt'] ?: $author_name ); ?>"
                        width="80"
                        height="80"
                        loading="lazy"
                    >
                </div>
            <?php endif; ?>
            <div class="li-author__info">
                <?php if ( $author_name ) : ?>
                    <span class="li-author__name"><?php echo esc_html( $author_name ); ?></span>
                <?php endif; ?>
                <?php if ( $author_role ) : ?>
                    <span class="li-author__role"><?php echo esc_html( $author_role ); ?></span>
                <?php endif; ?>
            </div>
        </div>

        <?php /* ── Content ── */ ?>
        <?php if ( $content ) : ?>
            <div class="li-content">
                <?php echo wp_kses_post( $content ); ?>
            </div>
        <?php endif; ?>

    </div>

    <?php if ( $decor_enabled ) :
        get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] );
    endif; ?>
</section>
