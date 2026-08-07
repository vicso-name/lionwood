<?php
/**
 * 404 Page Template
 */

get_header();

$lang      = function_exists( 'pll_current_language' ) ? pll_current_language() : 'en';
$lang      = in_array( $lang, [ 'en', 'uk' ], true ) ? $lang : 'en';

$image_404 = get_field( '404_image', 'option' );
$btn_url   = get_field( '404_btn_url', 'option' ) ?: home_url( '/' );

$text_fallbacks = [
    'en' => __( "The page you're looking for isn't here.\nBut we can still help you build something great.", 'lionwood' ),
    'uk' => __( "Сторінку, яку ви шукаєте, не знайдено.\nАле ми все одно можемо допомогти вам побудувати щось чудове.", 'lionwood' ),
];
$btn_fallbacks = [
    'en' => __( 'Back to Home', 'lionwood' ),
    'uk' => __( 'На головну', 'lionwood' ),
];

$text_404  = get_field( '404_text_' . $lang, 'option' ) ?: $text_fallbacks[ $lang ];
$btn_label = get_field( '404_btn_label_' . $lang, 'option' ) ?: $btn_fallbacks[ $lang ];
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
            <p class="nf-text"><?php echo wp_kses( nl2br( esc_html( $text_404 ) ), [ 'br' => [] ] ); ?></p>
            <a class="nf-btn" href="<?php echo esc_url( $btn_url ); ?>">
                <?php echo esc_html( $btn_label ); ?>
            </a>
        </div>

    </div>
</main>

<?php wp_footer(); ?>
</body>
</html>
