<?php
/**
 * Block: Career Hero Section
 * Slug: acf/career-hero
 *
 * Based on case-hero-section pattern.
 * Left col: H1 title + count badge + social icons (2 visible, expand on "…") + description
 * Right col: image (no taxonomy tags)
 */

defined( 'ABSPATH' ) || exit;

$pt     = absint( get_field( 'padding_top' )        ?: 100 );
$pb     = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob = absint( get_field( 'padding_bottom_mob' ) ?: 70 );

$title_line1   = get_field( 'title_line1' ) ?: 'Our';
$title_line2   = get_field( 'title_line2' ) ?: 'Career';
$show_count    = get_field( 'show_count' );
$description   = get_field( 'description' );
$desc_out      = $description ? wp_kses( $description, [ 'br' => [] ] ) : '';
$socials       = get_field( 'socials' ) ?: [];
$image         = get_field( 'image' );

$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#F7F7F7';

// Count of published careers
$career_count = 0;
if ( $show_count ) {
    $career_count = wp_count_posts( 'career' )->publish ?? 0;
}
?>

<section
    class="crh-section"
    style="
        --crh-pt: <?php echo $pt; ?>px;
        --crh-pb: <?php echo $pb; ?>px;
        --crh-pt-mob: <?php echo $pt_mob; ?>px;
        --crh-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="crh-section__container">
        <div class="crh-row">

            <?php /* ── Left column ── */ ?>
            <div class="crh-col crh-col--left">

                <?php /* Title — single line, two colored spans */ ?>
                <h1 class="crh-title crh-anim" data-delay="0">
                    <span class="crh-title__part1"><?php echo esc_html( $title_line1 ); ?></span>
                    <span class="crh-title__part2"><?php echo esc_html( $title_line2 ); ?>
                        <?php if ( $show_count && $career_count ) : ?>
                            <sup class="crh-title__count">(<?php echo $career_count; ?>)</sup>
                        <?php endif; ?>
                    </span>
                </h1>

                <?php /* Bottom row: socials left + description right (under last letter of title) */ ?>
                <div class="crh-bottom crh-anim" data-delay="160">
                    <?php if ( ! empty( $socials ) ) : ?>
                        <div class="crh-socials" data-crh-socials>
                            <?php foreach ( $socials as $i => $social ) :
                                $link  = $social['link']  ?? null;
                                $icon  = $social['icon']  ?? null;
                                $url   = ! empty( $link['url'] )    ? esc_url( $link['url'] )    : '';
                                $label = ! empty( $link['title'] )  ? esc_attr( $link['title'] ) : '';
                                $tgt   = ! empty( $link['target'] ) ? $link['target']             : '_blank';
                                if ( ! $url || ! $icon ) continue;
                                $hidden_class = $i >= 2 ? ' crh-socials__item--hidden' : '';
                            ?>
                                <a
                                    class="crh-socials__item<?php echo $hidden_class; ?>"
                                    href="<?php echo $url; ?>"
                                    target="<?php echo esc_attr( $tgt ); ?>"
                                    rel="noopener noreferrer"
                                    aria-label="<?php echo $label; ?>"
                                >
                                    <img
                                        src="<?php echo esc_url( $icon['url'] ); ?>"
                                        alt="<?php echo esc_attr( $icon['alt'] ?: $label ); ?>"
                                        width="46"
                                        height="46"
                                        loading="lazy"
                                    >
                                </a>
                            <?php endforeach; ?>

                            <?php if ( count( $socials ) > 2 ) : ?>
                                <button class="crh-socials__more" aria-label="<?php esc_attr_e( 'Show more social links', 'theme' ); ?>" data-crh-more>
                                    <span class="crh-socials__dot"></span>
                                    <span class="crh-socials__dot"></span>
                                    <span class="crh-socials__dot"></span>
                                </button>
                                <button class="crh-socials__close" aria-label="<?php esc_attr_e( 'Hide social links', 'theme' ); ?>" data-crh-close>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none" aria-hidden="true">
                                        <path d="M6.41797 6.41602L15.5846 15.5827M6.41797 15.5827L15.5846 6.41602" stroke="#C83030" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( $desc_out ) : ?>
                        <p class="crh-description"><?php echo $desc_out; ?></p>
                    <?php endif; ?>
                </div>

            </div><!-- .crh-col--left -->

            <?php /* ── Right column: image ── */ ?>
            <?php if ( $image ) : ?>
                <div class="crh-col crh-col--right crh-anim crh-anim--image" data-delay="80">
                    <div
                        class="crh-image"
                        style="background-image: url('<?php echo esc_url( $image['url'] ); ?>');"
                        role="img"
                        aria-label="<?php echo esc_attr( $image['alt'] ?: $title_line1 . ' ' . $title_line2 ); ?>"
                    ></div>
                </div>
            <?php endif; ?>

        </div><!-- .crh-row -->
    </div><!-- .crh-section__container -->

    <?php if ( $decor_enabled ) :
        get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] );
    endif; ?>
</section>
