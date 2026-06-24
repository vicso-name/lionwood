<?php
/**
 * Block: Contact Hero Section
 * Slug: acf/contact-hero
 *
 * Based on case-hero-section pattern.
 * Left col: H1 title + social icons (2 visible, expand on "…") + description
 * Right col: image (no taxonomy tags)
 */

defined( 'ABSPATH' ) || exit;

$pt     = absint( get_field( 'padding_top' )        ?: 100 );
$pb     = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob = absint( get_field( 'padding_bottom_mob' ) ?: 70 );

$title_line1   = get_field( 'title_line1' ) ?: 'Our';
$title_line2   = get_field( 'title_line2' ) ?: 'Career';
$description   = get_field( 'description' );
$desc_out      = $description ? wp_kses( $description, [ 'br' => [] ] ) : '';
$socials       = get_field( 'socials' ) ?: [];
$image         = get_field( 'image' );

$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#F7F7F7';

?>

<section
    class="coh-section"
    style="
        --coh-pt: <?php echo $pt; ?>px;
        --coh-pb: <?php echo $pb; ?>px;
        --coh-pt-mob: <?php echo $pt_mob; ?>px;
        --coh-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="coh-section__container">
        <div class="coh-row">

            <?php /* ── Left column ── */ ?>
            <div class="coh-col coh-col--left">

                <?php /* Title — single line, two colored spans */ ?>
                <h1 class="coh-title coh-anim" data-delay="0">
                    <span class="coh-title__part1"><?php echo esc_html( $title_line1 ); ?></span>
                    <span class="coh-title__part2"><?php echo esc_html( $title_line2 ); ?>
                    </span>
                </h1>

                <?php /* Bottom row: socials left + description right (under last letter of title) */ ?>
                <div class="coh-bottom coh-anim" data-delay="160">
                    <?php if ( ! empty( $socials ) ) : ?>
                        <div class="coh-socials" data-coh-socials>
                            <?php foreach ( $socials as $i => $social ) :
                                $link  = $social['link']  ?? null;
                                $icon  = $social['icon']  ?? null;
                                $url   = ! empty( $link['url'] )    ? esc_url( $link['url'] )    : '';
                                $label = ! empty( $link['title'] )  ? esc_attr( $link['title'] ) : '';
                                $tgt   = ! empty( $link['target'] ) ? $link['target']             : '_blank';
                                if ( ! $url || ! $icon ) continue;
                                $hidden_class = $i >= 2 ? ' coh-socials__item--hidden' : '';
                            ?>
                                <a
                                    class="coh-socials__item<?php echo $hidden_class; ?>"
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
                                <button class="coh-socials__more" aria-label="<?php esc_attr_e( 'Show more social links', 'lionwood' ); ?>" data-coh-more>
                                    <span class="coh-socials__dot"></span>
                                    <span class="coh-socials__dot"></span>
                                    <span class="coh-socials__dot"></span>
                                </button>
                                <button class="coh-socials__close" aria-label="<?php esc_attr_e( 'Hide social links', 'lionwood' ); ?>" data-coh-close>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none" aria-hidden="true">
                                        <path d="M6.41797 6.41602L15.5846 15.5827M6.41797 15.5827L15.5846 6.41602" stroke="#C83030" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( $desc_out ) : ?>
                        <p class="coh-description"><?php echo $desc_out; ?></p>
                    <?php endif; ?>
                </div>

            </div><!-- .coh-col--left -->

            <?php /* ── Right column: image ── */ ?>
            <?php if ( $image ) : ?>
                <div class="coh-col coh-col--right coh-anim coh-anim--image" data-delay="80">
                    <div
                        class="coh-image"
                        style="background-image: url('<?php echo esc_url( $image['url'] ); ?>');"
                        role="img"
                        aria-label="<?php echo esc_attr( $image['alt'] ?: $title_line1 . ' ' . $title_line2 ); ?>"
                    ></div>
                </div>
            <?php endif; ?>

        </div><!-- .coh-row -->
    </div><!-- .coh-section__container -->

    <?php if ( $decor_enabled ) :
        get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] );
    endif; ?>
</section>
