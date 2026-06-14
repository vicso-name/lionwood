<?php
/**
 * 404 Page Template
 */

get_header();

// ACF Options — supports WPML/Polylang out of the box
$image_404 = get_field( '404_image',   'option' );
$text_404  = get_field( '404_text',    'option' );
$btn_label = get_field( '404_btn_label', 'option' ) ?: __( 'Back to Home', 'theme' );
$btn_url   = home_url( '/' );
?>

<main class="nf-page">
    <div class="nf-inner">

        <?php if ( $image_404 ) : ?>
            <div class="nf-image">
                <img
                    src="<?php echo esc_url( $image_404['url'] ); ?>"
                    alt="404"
                    width="938"
                    height="365"
                    loading="eager"
                >
            </div>
        <?php else : ?>
            <div class="nf-number" aria-hidden="true">404</div>
        <?php endif; ?>

        <div class="nf-content">
            <?php if ( $text_404 ) : ?>
                <p class="nf-text"><?php echo wp_kses( $text_404, [ 'br' => [] ] ); ?></p>
            <?php else : ?>
                <p class="nf-text"><?php esc_html_e( "The page you're looking for isn't here.\nBut we can still help you build something great.", 'theme' ); ?></p>
            <?php endif; ?>
            <a class="nf-btn" href="<?php echo esc_url( $btn_url ); ?>">
                <?php echo esc_html( $btn_label ); ?>
            </a>
        </div>

    </div>
</main>

<?php wp_footer(); ?>
</body>
</html>
