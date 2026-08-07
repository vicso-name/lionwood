<?php
/**
 * Block: Contact Map Section
 * Slug: acf/contact-map
 *
 * Based 1-to-1 on map-section (ms-).
 * Left col: offices repeater (flag icon + name + address + phone)
 * Right col: map image flush to viewport right edge.
 */

defined( 'ABSPATH' ) || exit;

$pt     = absint( get_field( 'padding_top' )        ?: 100 );
$pb     = absint( get_field( 'padding_bottom' )     ?: 200 );
$pt_mob = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob = absint( get_field( 'padding_bottom_mob' ) ?: 140 );

$title   = get_field( 'title' );
$offices = get_field( 'offices' ) ?: [];
$map     = get_field( 'map' );

$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#F7F7F7';
?>

<section
    class="cms-section"
    style="
        --cms-pt: <?php echo $pt; ?>px;
        --cms-pb: <?php echo $pb; ?>px;
        --cms-pt-mob: <?php echo $pt_mob; ?>px;
        --cms-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="cms-section__container">

        <?php /* ── Title — identical to ms-title ── */ ?>
        <?php if ( $title ) : ?>
            <div class="cms-title">
                <?php echo wp_kses_post( $title ); ?>
            </div>
        <?php endif; ?>

        <?php /* ── Content row ── */ ?>
        <div class="cms-row">

            <?php /* ── Left: offices list ── */ ?>
            <?php if ( ! empty( $offices ) ) : ?>
                <div class="cms-offices">
                    <?php foreach ( $offices as $i => $office ) :
                        $flag    = $office['flag']    ?? null;
                        $name    = esc_html( $office['name']    ?? '' );
                        $address = esc_html( $office['address'] ?? '' );
                        $phone   = esc_html( $office['phone']   ?? '' );
                    ?>
                        <?php if ( $i > 0 ) : ?>
                            <div class="cms-offices__divider" aria-hidden="true"></div>
                        <?php endif; ?>

                        <div class="cms-office">
                            <div class="cms-office__head">
                                <?php if ( $flag ) : ?>
                                    <div class="cms-office__flag">
                                        <img
                                            src="<?php echo esc_url( $flag['url'] ); ?>"
                                            alt="<?php echo esc_attr( $flag['alt'] ?: $name ); ?>"
                                            width="19"
                                            height="19"
                                            loading="lazy"
                                        >
                                    </div>
                                <?php endif; ?>
                                <div class="cms-office__info">
                                    <?php if ( $name ) : ?>
                                        <span class="cms-office__name"><?php echo $name; ?></span>
                                    <?php endif; ?>
                                    <?php if ( $address ) : ?>
                                        <span class="cms-office__address"><?php echo $address; ?></span>
                                    <?php endif; ?>
                                    <?php if ( $phone ) : ?>
                                        <a class="cms-office__phone" href="tel:<?php echo preg_replace('/[^+0-9]/', '', $phone); ?>"><?php echo $phone; ?></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php /* ── Map — flush to right edge ── */ ?>
            <?php if ( $map ) : ?>
                <div class="cms-map">
                    <img
                        src="<?php echo esc_url( $map['url'] ); ?>"
                        alt="<?php echo esc_attr( $map['alt'] ?: __( 'World map', 'lionwood' ) ); ?>"
                        width="968"
                        height="502"
                        loading="lazy"
                    >
                </div>
            <?php endif; ?>

        </div><!-- .cms-row -->

    </div><!-- .cms-section__container -->

    <?php if ( $decor_enabled ) :
        get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] );
    endif; ?>
</section>
