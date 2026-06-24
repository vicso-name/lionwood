<?php
/**
 * Partial: Contact Section
 *
 * Reusable contact section — accepts $args, renders the full section.
 * Called from: template-parts/sections/contact_section.php (ACF block)
 *              author.php (author archive)
 *
 * $args keys:
 *   padding_top        int     Top padding px, default 100
 *   padding_bottom     int     Bottom padding px, default 200
 *   padding_top_mob    int     Mobile top padding px, default 70
 *   padding_bottom_mob int     Mobile bottom padding px, default 140
 *   title_top          string  Dimmed first heading line (esc_html'd inside)
 *   title_bottom       string  Main heading line(s), may contain \n → <br>
 *   description        string  Short text below heading, may contain <br>
 *   grid_labels        array   Indexed 1–5; empty strings = normal cells
 *   form_shortcode     string  CF7 shortcode; empty = fallback static form
 *   terms_link         array   ACF link array ['url','target'] or null
 *   decor_enabled      bool    Show decorative arc at section bottom
 *   decor_color        string  CSS color for the arc, default '#F7F7F7'
 */

defined( 'ABSPATH' ) || exit;

// ── Extract + sanitize ────────────────────────────────────────────────────────
$padding_top        = absint( $args['padding_top']        ?? 100 );
$padding_bottom     = absint( $args['padding_bottom']     ?? 200 );
$padding_top_mob    = absint( $args['padding_top_mob']    ?? 70  );
$padding_bottom_mob = absint( $args['padding_bottom_mob'] ?? 140 );

$title_top    = esc_html( $args['title_top']   ?? '' );
$title_bottom = wp_kses( $args['title_bottom'] ?? '', [ 'br' => [] ] );
$description  = wp_kses( $args['description']  ?? '', [ 'br' => [], 'a' => [ 'href' => [], 'target' => [], 'rel' => [] ] ] );

$grid_labels    = (array) ( $args['grid_labels']    ?? [] );
$form_shortcode =          $args['form_shortcode']  ?? '';

$terms_raw    = $args['terms_link'] ?? [];
$terms_url    = ! empty( $terms_raw['url'] )    ? esc_url( $terms_raw['url'] )     : esc_url( home_url( '/terms-and-conditions/' ) );
$terms_target = ! empty( $terms_raw['target'] ) ? esc_attr( $terms_raw['target'] ) : '_self';

$decor_enabled = ! empty( $args['decor_enabled'] );
$decor_color   = sanitize_hex_color( $args['decor_color'] ?? '' ) ?: '#F7F7F7';

// ── Grid cell map (positions stay constant across all usages) ─────────────────
$wide_cells = [
    '1-1'  => 1,
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

$section_style = sprintf(
    '--cs-pt:%dpx; --cs-pb:%dpx; --cs-pt-mob:%dpx; --cs-pb-mob:%dpx;',
    $padding_top, $padding_bottom, $padding_top_mob, $padding_bottom_mob
);
?>
<section class="cs-section" style="<?php echo esc_attr( $section_style ); ?>">
    <div class="cs-section__inner">

        <?php /* ── Heading + description ─────────────────────────────────── */ ?>
        <div class="cs-section__content">
            <div class="cs-section__heading">
                <?php if ( $title_top ) : ?>
                    <span class="cs-section__heading-top"><?php echo $title_top; ?></span>
                <?php endif; ?>
                <?php if ( $title_bottom ) : ?>
                    <span class="cs-section__heading-bottom"><?php echo $title_bottom; ?></span>
                <?php endif; ?>
            </div>
            <?php if ( $description ) : ?>
                <p class="cs-section__description"><?php echo $description; ?></p>
            <?php endif; ?>
        </div>

        <?php /* ── Stage: decorative grid + form ───────────────────────── */ ?>
        <div class="cs-section__stage">

            <div class="cs-section__grid-wrap" aria-hidden="true">
                <div class="cs-grid">
                    <?php
                    for ( $row = 1; $row <= 8; $row++ ) {
                        for ( $col = 1; $col <= 14; $col++ ) {
                            $cell_key  = $row . '-' . $col;
                            if ( in_array( $cell_key, $skip_cells, true ) ) continue;

                            $label_idx = $wide_cells[ $cell_key ] ?? null;

                            if ( $label_idx !== null ) {
                                $label_text = ! empty( $grid_labels[ $label_idx ] )
                                    ? wp_kses( (string) $grid_labels[ $label_idx ], [ 'br' => [] ] )
                                    : '';
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

            <div class="cs-section__form-wrap">
                <div class="cs-form">

                    <?php if ( $form_shortcode ) : ?>
                        <?php echo do_shortcode( $form_shortcode ); ?>
                    <?php else : ?>
                        <div class="cs-form__fields">

                            <div class="cs-form__field-group">
                                <input class="cs-form__input" type="text" name="your-name"
                                    placeholder="<?php esc_attr_e( 'Full Name *', 'lionwood' ); ?>"
                                    required aria-required="true">
                            </div>

                            <div class="cs-form__field-group">
                                <input class="cs-form__input" type="email" name="your-email"
                                    placeholder="<?php esc_attr_e( 'Business Email *', 'lionwood' ); ?>"
                                    required aria-required="true">
                            </div>

                            <div class="cs-form__field-group">
                                <input class="cs-form__input" type="text" name="your-company"
                                    placeholder="<?php esc_attr_e( 'Company / Business name', 'lionwood' ); ?>">
                            </div>

                            <div class="cs-form__field-group">
                                <p class="cs-form__label"><?php esc_html_e( 'I am interested in', 'lionwood' ); ?></p>
                                <div class="cs-form__select-wrap">
                                    <select class="cs-form__select" name="your-interest"
                                        aria-label="<?php esc_attr_e( 'I am interested in', 'lionwood' ); ?>">
                                        <option value="ai-solutions"><?php esc_html_e( 'AI-Driven Solutions', 'lionwood' ); ?></option>
                                        <option value="web-development"><?php esc_html_e( 'Web Development', 'lionwood' ); ?></option>
                                        <option value="mobile-development"><?php esc_html_e( 'Mobile Development', 'lionwood' ); ?></option>
                                        <option value="product-strategy"><?php esc_html_e( 'Product Strategy', 'lionwood' ); ?></option>
                                        <option value="other"><?php esc_html_e( 'Other', 'lionwood' ); ?></option>
                                    </select>
                                    <span class="cs-form__select-icon" aria-hidden="true">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="12" viewBox="0 0 24 12" fill="none">
                                            <g clip-path="url(#clip0_cs)"><path d="M6.56656 2.4139L5.50656 3.4749L11.2836 9.2539C11.3761 9.34706 11.4862 9.42098 11.6075 9.47143C11.7287 9.52188 11.8587 9.54785 11.9901 9.54785C12.1214 9.54785 12.2514 9.52188 12.3727 9.47143C12.4939 9.42098 12.604 9.34706 12.6966 9.2539L18.4766 3.4749L17.4166 2.4149L11.9916 7.8389L6.56656 2.4139Z" fill="black"/></g>
                                            <defs><clipPath id="clip0_cs"><rect width="12" height="24" fill="white" transform="matrix(-4.37114e-08 -1 -1 4.37114e-08 24 12)"/></clipPath></defs>
                                        </svg>
                                    </span>
                                </div>
                            </div>

                            <div class="cs-form__field-group">
                                <p class="cs-form__label"><?php esc_html_e( 'Project Details', 'lionwood' ); ?></p>
                                <textarea class="cs-form__textarea" name="your-message"
                                    placeholder="<?php esc_attr_e( 'Your message...', 'lionwood' ); ?>"
                                    rows="4"
                                    aria-label="<?php esc_attr_e( 'Project Details', 'lionwood' ); ?>"></textarea>
                            </div>

                            <div class="cs-form__footer">
                                <p class="cs-form__terms">
                                    <?php esc_html_e( 'By clicking the button, I agree to the', 'lionwood' ); ?>
                                    <a href="<?php echo $terms_url; ?>"
                                        target="<?php echo esc_attr( $terms_target ); ?>"
                                        <?php echo '_blank' === $terms_target ? 'rel="noopener noreferrer"' : ''; ?>
                                        class="cs-form__terms-link"
                                    ><?php esc_html_e( 'Terms & Conditions', 'lionwood' ); ?></a>
                                </p>
                                <button type="submit" class="cs-form__submit">
                                    <?php esc_html_e( 'Send Request', 'lionwood' ); ?>
                                </button>
                            </div>

                        </div>
                    <?php endif; ?>

                </div>
            </div>

        </div>

    </div>
    <?php if ( $decor_enabled ) :
        get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] );
    endif; ?>
</section>
