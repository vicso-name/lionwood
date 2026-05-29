<?php
/**
 * Block: Contact Section
 *
 * ACF block slug : acf/contact-section
 * Template file  : blocks/contact-section/contact-section.php
 */

defined( 'ABSPATH' ) || exit;

// ── Field values ────────────────────────────────────────────────────────────
$padding_top    = absint( get_field( 'padding_top' ) ?: 100 );
$padding_bottom = absint( get_field( 'padding_bottom' ) ?: 100 );

$title_top    = get_field( 'title_top' )    ?: __( 'Ready to Accelerate', 'theme' );
// ACF textarea with new_lines=br already converts \n → <br> — use wp_kses
$title_bottom_raw = get_field( 'title_bottom' );
$title_bottom     = $title_bottom_raw
    ? wp_kses( $title_bottom_raw, [ 'br' => [] ] )
    : 'Your Business Growth?<br>Contact Us.';

$description_raw = get_field( 'description' );
$description     = $description_raw
    ? wp_kses( $description_raw, [ 'br' => [] ] )
    : '';

// Grid labels — ACF textarea new_lines=br, so already has <br>
$grid_labels = [];
for ( $i = 1; $i <= 5; $i++ ) {
    $raw = get_field( 'grid_label_' . $i );
    $grid_labels[ $i ] = $raw ? wp_kses( $raw, [ 'br' => [] ] ) : '';
}

$form_shortcode = get_field( 'form_shortcode' ) ?: '';
$terms_raw      = get_field( 'terms_link' );
$terms_url      = ! empty( $terms_raw['url'] )    ? esc_url( $terms_raw['url'] )  : esc_url( home_url( '/terms-and-conditions/' ) );
$terms_target   = ! empty( $terms_raw['target'] ) ? $terms_raw['target']           : '_self';

// ── Grid map ─────────────────────────────────────────────────────────────────
$wide_cells = [
    '1-1'  => 1,   // row 1, cols 1-2 (top-left)
    '2-12' => 2,
    '4-2'  => 3,
    '6-13' => 4,
    '8-1'  => 5,
];

$skip_cells = [];
foreach ( $wide_cells as $key => $label_idx ) {
    [ $row, $col ] = explode( '-', $key );
    $skip_cells[] = $row . '-' . ( (int) $col + 1 );
}

// ── Inline style for custom padding ─────────────────────────────────────────
$section_style = sprintf(
    'padding-top:%dpx; padding-bottom:%dpx;',
    $padding_top,
    $padding_bottom
);
?>

<section class="cs-section" style="<?php echo esc_attr( $section_style ); ?>">
    <div class="cs-section__inner">

        <?php /* ── 1. Content: heading + description (above everything) ──── */ ?>
        <div class="cs-section__content">

            <div class="cs-section__heading">
                <span class="cs-section__heading-top">
                    <?php echo esc_html( $title_top ); ?>
                </span>
                <span class="cs-section__heading-bottom">
                    <?php echo $title_bottom; ?>
                </span>
            </div>

            <?php if ( $description ) : ?>
                <p class="cs-section__description">
                    <?php echo $description; ?>
                </p>
            <?php endif; ?>

        </div>

        <?php /* ── 2. Stage: grid + form layered together ──────────────────
                  The grid is a background decoration behind the form.
                  Both live inside .cs-section__stage which has a fixed height.
               */ ?>
        <div class="cs-section__stage">

            <?php /* Decorative grid — hidden on mobile */ ?>
            <div class="cs-section__grid-wrap" aria-hidden="true">
                <div class="cs-grid">
                    <?php
                    for ( $row = 1; $row <= 8; $row++ ) {
                        for ( $col = 1; $col <= 14; $col++ ) {
                            $cell_key  = $row . '-' . $col;

                            if ( in_array( $cell_key, $skip_cells, true ) ) {
                                continue;
                            }

                            $label_idx = $wide_cells[ $cell_key ] ?? null;

                            if ( $label_idx !== null ) {
                                $label_text = $grid_labels[ $label_idx ];
                                if ( $label_text ) {
                                    echo '<div class="cs-grid__cell cs-grid__cell--wide">';
                                    echo '<span class="cs-grid__label">' . $label_text . '</span>';
                                    echo '</div>';
                                } else {
                                    echo '<div class="cs-grid__cell"></div>';
                                    echo '<div class="cs-grid__cell"></div>';
                                }
                            } else {
                                echo '<div class="cs-grid__cell"></div>';
                            }
                        }
                    }
                    ?>
                </div>
            </div>

            <?php /* Form — centred over the grid */ ?>
            <div class="cs-section__form-wrap">
                <div class="cs-form">

                    <?php if ( $form_shortcode ) : ?>
                        <?php echo do_shortcode( wp_kses_post( $form_shortcode ) ); ?>
                    <?php else : ?>
                        <div class="cs-form__fields">

                            <div class="cs-form__field-group">
                                <input
                                    class="cs-form__input"
                                    type="text"
                                    name="your-name"
                                    placeholder="<?php esc_attr_e( 'Full Name *', 'theme' ); ?>"
                                    required
                                    aria-required="true"
                                >
                            </div>

                            <div class="cs-form__field-group">
                                <input
                                    class="cs-form__input"
                                    type="email"
                                    name="your-email"
                                    placeholder="<?php esc_attr_e( 'Business Email *', 'theme' ); ?>"
                                    required
                                    aria-required="true"
                                >
                            </div>

                            <div class="cs-form__field-group">
                                <input
                                    class="cs-form__input"
                                    type="text"
                                    name="your-company"
                                    placeholder="<?php esc_attr_e( 'Company / Business name', 'theme' ); ?>"
                                >
                            </div>

                            <div class="cs-form__field-group">
                                <p class="cs-form__label"><?php esc_html_e( 'I am interested in', 'theme' ); ?></p>
                                <div class="cs-form__select-wrap">
                                    <select class="cs-form__select" name="your-interest" aria-label="<?php esc_attr_e( 'I am interested in', 'theme' ); ?>">
                                        <option value="ai-solutions"><?php esc_html_e( 'AI-Driven Solutions', 'theme' ); ?></option>
                                        <option value="web-development"><?php esc_html_e( 'Web Development', 'theme' ); ?></option>
                                        <option value="mobile-development"><?php esc_html_e( 'Mobile Development', 'theme' ); ?></option>
                                        <option value="product-strategy"><?php esc_html_e( 'Product Strategy', 'theme' ); ?></option>
                                        <option value="other"><?php esc_html_e( 'Other', 'theme' ); ?></option>
                                    </select>
                                    <span class="cs-form__select-icon" aria-hidden="true">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="12" viewBox="0 0 24 12" fill="none">
                                            <g clip-path="url(#clip0_cs)">
                                                <path d="M6.56656 2.4139L5.50656 3.4749L11.2836 9.2539C11.3761 9.34706 11.4862 9.42098 11.6075 9.47143C11.7287 9.52188 11.8587 9.54785 11.9901 9.54785C12.1214 9.54785 12.2514 9.52188 12.3727 9.47143C12.4939 9.42098 12.604 9.34706 12.6966 9.2539L18.4766 3.4749L17.4166 2.4149L11.9916 7.8389L6.56656 2.4139Z" fill="black"/>
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_cs">
                                                    <rect width="12" height="24" fill="white" transform="matrix(-4.37114e-08 -1 -1 4.37114e-08 24 12)"/>
                                                </clipPath>
                                            </defs>
                                        </svg>
                                    </span>
                                </div>
                            </div>

                            <div class="cs-form__field-group">
                                <p class="cs-form__label"><?php esc_html_e( 'Project Details', 'theme' ); ?></p>
                                <textarea
                                    class="cs-form__textarea"
                                    name="your-message"
                                    placeholder="<?php esc_attr_e( 'Your message...', 'theme' ); ?>"
                                    rows="4"
                                    aria-label="<?php esc_attr_e( 'Project Details', 'theme' ); ?>"
                                ></textarea>
                            </div>

                            <div class="cs-form__footer">
                                <p class="cs-form__terms">
                                    <?php esc_html_e( 'By clicking the button, I agree to the', 'theme' ); ?>
                                    <a
                                        href="<?php echo $terms_url; ?>"
                                        target="<?php echo esc_attr( $terms_target ); ?>"
                                        <?php echo '_blank' === $terms_target ? 'rel="noopener noreferrer"' : ''; ?>
                                        class="cs-form__terms-link"
                                    ><?php esc_html_e( 'Terms & Conditions', 'theme' ); ?></a>
                                </p>
                                <button type="submit" class="cs-form__submit">
                                    <?php esc_html_e( 'Send Request', 'theme' ); ?>
                                </button>
                            </div>

                        </div>
                    <?php endif; ?>

                </div>
            </div>

        </div><!-- .cs-section__stage -->

    </div><!-- .cs-section__inner -->
</section>
