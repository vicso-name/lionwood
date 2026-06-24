<?php
/**
 * Block: Whitepaper Download
 *
 * ACF block slug : acf/whitepaper-download
 *
 * Layout: image (left 325×448) + right column (staggered title + lead-gate form)
 * Form logic: validate → POST to form_endpoint → show success + PDF download link
 */

defined( 'ABSPATH' ) || exit;

$pt     = absint( get_field( 'padding_top' )        ?: 120 );
$pb     = absint( get_field( 'padding_bottom' )     ?: 200 );
$pt_mob = absint( get_field( 'padding_top_mob' )    ?: 80 );
$pb_mob = absint( get_field( 'padding_bottom_mob' ) ?: 140 );

$image         = get_field( 'image' );
$title_1       = esc_html( get_field( 'title_line_1' ) ?: '' );
$title_2       = esc_html( get_field( 'title_line_2' ) ?: '' );
$title_3       = esc_html( get_field( 'title_line_3' ) ?: '' );

// HubSpot credentials come exclusively from global options (Theme Settings).
$hs_portal_id = esc_attr( get_field( 'hs_portal_id', 'option' ) ?: '' );
$hs_form_id   = esc_attr( get_field( 'hs_default_form_id', 'option' ) ?: '' );

$pdf_file        = get_field( 'pdf_file' );
$pdf_url         = ! empty( $pdf_file['url'] ) ? esc_url( $pdf_file['url'] ) : '';
$success_message = esc_html( get_field( 'success_message' ) ?: __( 'Thank you! Your whitepaper is ready.', 'lionwood' ) );
$btn_label       = esc_html( get_field( 'button_label' ) ?: __( 'Get Whitepaper', 'lionwood' ) );

$decor_enabled = get_field( 'decor_bottom_enabled' ) ?? true;
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#C83030';

$download_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
    <path d="M15.75 11.25V12.15C15.75 13.4101 15.75 14.0402 15.5048 14.5215C15.289 14.9448 14.9448 15.289 14.5215 15.5048C14.0402 15.75 13.4101 15.75 12.15 15.75H5.85C4.58988 15.75 3.95982 15.75 3.47852 15.5048C3.05516 15.289 2.71095 14.9448 2.49524 14.5215C2.25 14.0402 2.25 13.4101 2.25 12.15V11.25M5.25 7.5L9 11.25L12.75 7.5M9 11.25V2.25" stroke="white" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>';
?>

<section
    id="wpd-section"
    class="wpd-section"
    style="
        --wpd-pt: <?php echo $pt; ?>px;
        --wpd-pb: <?php echo $pb; ?>px;
        --wpd-pt-mob: <?php echo $pt_mob; ?>px;
        --wpd-pb-mob: <?php echo $pb_mob; ?>px;
    "
    <?php if ( $pdf_url ) : ?>data-pdf-url="<?php echo esc_url( $pdf_url ); ?>"<?php endif; ?>
>
    <div class="wpd-section__container">
        <div class="wpd-row">

            <?php /* ── Left: book cover image ────────────────────────── */ ?>
            <div class="wpd-col wpd-col--image">
                <div
                    class="wpd-book"
                    <?php if ( $image ) : ?>
                        style="background-image: url('<?php echo esc_url( $image['url'] ); ?>');"
                        role="img"
                        aria-label="<?php echo esc_attr( $image['alt'] ?: $title_1 ); ?>"
                    <?php endif; ?>
                ></div>
            </div>

            <?php /* ── Right: title + form ───────────────────────────── */ ?>
            <div class="wpd-col wpd-col--content">

                <?php /* Staggered 3-line heading */ ?>
                <div class="wpd-heading">
                    <?php if ( $title_1 ) : ?>
                        <span class="wpd-heading__line wpd-heading__line--1"><?php echo $title_1; ?></span>
                    <?php endif; ?>
                    <?php if ( $title_2 ) : ?>
                        <span class="wpd-heading__line wpd-heading__line--2"><?php echo $title_2; ?></span>
                    <?php endif; ?>
                    <?php if ( $title_3 ) : ?>
                        <span class="wpd-heading__line wpd-heading__line--3"><?php echo $title_3; ?></span>
                    <?php endif; ?>
                </div>

                <?php /* Lead-gate form */ ?>
                <div class="wpd-gate" data-wpd-gate>

                    <form
                        class="wpd-form"
                        data-wpd-form
                        <?php if ( $hs_portal_id ) : ?>data-hs-portal="<?php echo esc_attr( $hs_portal_id ); ?>"<?php endif; ?>
                        <?php if ( $hs_form_id ) : ?>data-hs-form="<?php echo esc_attr( $hs_form_id ); ?>"<?php endif; ?>
                        novalidate
                    >
                        <input
                            class="wpd-input"
                            type="text"
                            name="full_name"
                            placeholder="<?php esc_attr_e( 'Full Name *', 'lionwood' ); ?>"
                            required
                            autocomplete="name"
                            maxlength="100"
                        >
                        <input
                            class="wpd-input wpd-input--last"
                            type="email"
                            name="email"
                            placeholder="<?php esc_attr_e( 'Business Email *', 'lionwood' ); ?>"
                            required
                            autocomplete="email"
                            maxlength="254"
                        >

                        <div class="wpd-form__row">
                            <p class="wpd-form__terms">
                                <?php esc_html_e( 'By clicking the button, I agree to the', 'lionwood' ); ?>
                                <a
                                    href="<?php echo esc_url( home_url( '/policy-policy/' ) ); ?>"
                                    class="wpd-form__terms-link"
                                ><?php esc_html_e( 'Terms & Conditions', 'lionwood' ); ?></a>
                            </p>

                            <button type="submit" class="wpd-submit" data-wpd-submit disabled>
                                <?php echo $download_svg; ?>
                                <span data-wpd-btn-label><?php echo esc_html( $btn_label ); ?></span>
                            </button>
                        </div>

                        <p class="wpd-form__error" data-wpd-error hidden></p>
                    </form>

                    <?php /* Success state — shown after HubSpot submit + auto-download */ ?>
                    <div class="wpd-success" data-wpd-success hidden>
                        <p class="wpd-success__message"><?php echo esc_html( $success_message ); ?></p>
                    </div>

                </div><!-- .wpd-gate -->

            </div><!-- .wpd-col--content -->

        </div><!-- .wpd-row -->
    </div><!-- .wpd-section__container -->

    <?php if ( $decor_enabled ) : ?>
        <?php get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] ); ?>
    <?php endif; ?>

</section>
