<?php
/**
 * Block: Contact Directly
 * Slug: acf/contact-directly
 *
 * Red bg, two-line heading, repeater of contact cards (2 per row).
 */

defined( 'ABSPATH' ) || exit;

$pt     = absint( get_field( 'padding_top' )        ?: 100 );
$pb     = absint( get_field( 'padding_bottom' )     ?: 200 );
$pt_mob = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob = absint( get_field( 'padding_bottom_mob' ) ?: 140 );

$title_top    = get_field( 'title_top' )    ?: 'Get in Touch';
$title_bottom = get_field( 'title_bottom' ) ?: 'Contact Us Directly';
$cards        = get_field( 'cards' ) ?: [];

$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#E9E9E9';

$arrow_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
    <path d="M6.3469 17.656L17.6606 6.34225M9.17533 6.34225L17.6606 6.34225L17.6606 14.8275" stroke="#111319" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>';
?>

<section
    class="cd-section"
    style="
        --cd-pt: <?php echo $pt; ?>px;
        --cd-pb: <?php echo $pb; ?>px;
        --cd-pt-mob: <?php echo $pt_mob; ?>px;
        --cd-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="cd-section__container">

        <?php /* ── Heading ── */ ?>
        <div class="cd-heading">
            <span class="cd-heading__top"><?php echo esc_html( $title_top ); ?></span>
            <span class="cd-heading__bottom"><?php echo esc_html( $title_bottom ); ?></span>
        </div>

        <?php /* ── Cards grid ── */ ?>
        <?php if ( ! empty( $cards ) ) : ?>
            <div class="cd-grid">
                <?php foreach ( $cards as $card ) :
                    $photo       = $card['contact_photo']    ?? null;
                    $name        = esc_html( $card['contact_name']       ?? '' );
                    $position    = esc_html( $card['contact_position']   ?? '' );
                    $description = $card['description'] ? wp_kses( $card['description'], [ 'br' => [] ] ) : '';
                    $email       = esc_attr( $card['email'] ?? '' );
                    $has_contact = $photo || $name || $position;
                ?>
                    <div class="cd-card">

                        <?php /* ── Row 1: contact person + description ── */ ?>
                        <div class="cd-card__top">

                            <?php if ( $has_contact ) : ?>
                                <div class="cd-contact">
                                    <?php if ( $photo ) : ?>
                                        <div
                                            class="cd-contact__photo"
                                            style="background-image: url('<?php echo esc_url( $photo['url'] ); ?>');"
                                            role="img"
                                            aria-label="<?php echo esc_attr( $photo['alt'] ?: $name ); ?>"
                                        ></div>
                                    <?php endif; ?>
                                    <div class="cd-contact__info">
                                        <?php if ( $name ) : ?>
                                            <span class="cd-contact__name"><?php echo $name; ?></span>
                                        <?php endif; ?>
                                        <?php if ( $position ) : ?>
                                            <span class="cd-contact__position"><?php echo $position; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ( $description ) : ?>
                                <p class="cd-card__desc<?php echo ! $has_contact ? ' cd-card__desc--alone' : ''; ?>">
                                    <?php echo $description; ?>
                                </p>
                            <?php endif; ?>

                        </div><!-- .cd-card__top -->

                        <?php /* ── Row 2: email ── */ ?>
                        <?php if ( $email ) : ?>
                            <a class="cd-card__email" href="mailto:<?php echo $email; ?>">
                                <span><?php echo $email; ?></span>
                                <?php echo $arrow_svg; ?>
                            </a>
                        <?php endif; ?>

                    </div><!-- .cd-card -->
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div><!-- .cd-section__container -->

    <?php if ( $decor_enabled ) :
        get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] );
    endif; ?>
</section>
