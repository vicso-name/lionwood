<?php
/**
 * Block: Product Hero
 * Slug: acf/product-hero
 *
 * Colored bg (default #384AD6), border-radius top, title + content col left + image right.
 */

defined( 'ABSPATH' ) || exit;

$pt     = absint( get_field('padding_top')        ?: 80 );
$pb     = absint( get_field('padding_bottom')     ?: 200 );
$pt_mob = absint( get_field('padding_top_mob')    ?: 60 );
$pb_mob = absint( get_field('padding_bottom_mob') ?: 140 );

$bg_color    = get_field('bg_color')     ?: '#384AD6';
$title       = get_field('title')        ?: '';
$label       = get_field('label')        ?: '';
$description = get_field('description')  ?: '';
$items       = get_field('items')        ?: [];
$logo        = get_field('logo');
$image       = get_field('image');
$title_link  = get_field('title_link');
$title_url   = ! empty( $title_link['url'] )    ? esc_url( $title_link['url'] )    : '';
$title_tgt   = ! empty( $title_link['target'] ) ? $title_link['target']             : '_self';

$decor_enabled = get_field('decor_bottom_enabled');
$decor_color   = get_field('decor_bottom_color') ?: '#111319';

$check_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
    <path d="M12.0784 3.67079C12.1878 3.78018 12.2492 3.92853 12.2492 4.08321C12.2492 4.23789 12.1878 4.38623 12.0784 4.49563L6.24509 10.329C6.1357 10.4383 5.98735 10.4998 5.83268 10.4998C5.678 10.4998 5.52965 10.4383 5.42026 10.329L2.50359 7.41229C2.39733 7.30227 2.33854 7.15492 2.33987 7.00197C2.34119 6.84903 2.40254 6.70272 2.5107 6.59456C2.61885 6.48641 2.76516 6.42506 2.91811 6.42373C3.07106 6.4224 3.21841 6.4812 3.32843 6.58746L5.83268 9.09171L11.2536 3.67079C11.363 3.56143 11.5113 3.5 11.666 3.5C11.8207 3.5 11.969 3.56143 12.0784 3.67079Z" fill="#111319"/>
    </svg>';

$arrow_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
    <path d="M7 17L17 7M17 7H7M17 7V17" stroke="#F7F7F7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>';
?>

<section
    class="ph2-section"
    style="
        --ph2-pt: <?php echo $pt; ?>px;
        --ph2-pb: <?php echo $pb; ?>px;
        --ph2-pt-mob: <?php echo $pt_mob; ?>px;
        --ph2-pb-mob: <?php echo $pb_mob; ?>px;
        --ph2-bg: <?php echo esc_attr( $bg_color ); ?>;
        --ph2-image-bottom: <?php echo $decor_enabled ? '60px' : '0px'; ?>;
    "
>
    <div class="ph2-section__container">

        <?php /* ── Title ── */ ?>
        <?php if ( $title ) : ?>
            <div class="ph2-title-row ph2-anim" data-delay="0">
                <?php if ( $title_url ) : ?>
                    <a class="ph2-title" href="<?php echo $title_url; ?>" target="<?php echo esc_attr( $title_tgt ); ?>" <?php echo $title_tgt === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>>
                        <?php echo esc_html( $title ); ?>
                        <span class="ph2-title__arrow"><?php echo $arrow_svg; ?></span>
                    </a>
                <?php else : ?>
                    <h1 class="ph2-title"><?php echo esc_html( $title ); ?></h1>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php /* ── Body row: content left + image right ── */ ?>
        <div class="ph2-body">

            <?php /* ── Left: content block ── */ ?>
            <div class="ph2-content">

                <?php if ( $label ) : ?>
                    <span class="ph2-label ph2-anim" data-delay="150"><?php echo esc_html( $label ); ?></span>
                <?php endif; ?>

                <?php if ( $description ) : ?>
                    <p class="ph2-description ph2-anim" data-delay="300"><?php echo wp_kses( $description, [ 'br' => [] ] ); ?></p>
                <?php endif; ?>

                <?php if ( ! empty( $items ) ) : ?>
                    <ul class="ph2-list ph2-anim" data-delay="450">
                        <?php foreach ( $items as $item ) :
                            $text = esc_html( $item['text'] ?? '' );
                            if ( ! $text ) continue;
                        ?>
                            <li class="ph2-list__item">
                                <span class="ph2-list__icon"><?php echo $check_svg; ?></span>
                                <span class="ph2-list__text"><?php echo $text; ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <?php if ( $logo ) : ?>
                    <div class="ph2-logo ph2-anim" data-delay="600">
                        <img
                            src="<?php echo esc_url( $logo['url'] ); ?>"
                            alt="<?php echo esc_attr( $logo['alt'] ?: $title ); ?>"
                            height="60"
                            loading="lazy"
                        >
                    </div>
                <?php endif; ?>

            </div><!-- .ph2-content -->

            <?php /* ── Right: product image ── */ ?>
            <?php if ( $image ) : ?>
                <div class="ph2-image-wrap ph2-anim ph2-anim--from-right" data-delay="200">
                    <img
                        class="ph2-image"
                        src="<?php echo esc_url( $image['url'] ); ?>"
                        alt="<?php echo esc_attr( $image['alt'] ?: $title ); ?>"
                        width="760"
                        height="570"
                        loading="eager"
                    >
                </div>
            <?php endif; ?>

        </div><!-- .ph2-body -->

    </div><!-- .ph2-section__container -->

    <?php if ( $decor_enabled ) :
        get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] );
    endif; ?>
</section>
