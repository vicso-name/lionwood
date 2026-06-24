<?php
/**
 * Block: Our Awards
 *
 * ACF block slug : acf/our-awards
 * Template file  : blocks/our-awards/our-awards.php
 *
 * Technique: pure-CSS infinite marquee.
 * The item list is duplicated once in HTML — CSS animation translates
 * the track by exactly -50% (= one copy width) creating a seamless loop.
 * No JS, no Swiper, no flicker.
 */

defined( 'ABSPATH' ) || exit;

// ── Fields ───────────────────────────────────────────────────────────────────
$pt        = absint( get_field( 'padding_top' )        ?: 80 );
$pb        = absint( get_field( 'padding_bottom' )     ?: 80 );
$pt_mob    = absint( get_field( 'padding_top_mob' )    ?: 30 );
$pb_mob    = absint( get_field( 'padding_bottom_mob' ) ?: 30 );
$speed     = absint( get_field( 'speed' )              ?: 30 );
$title_top    = get_field( 'title_top' )    ?: '';
$title_bottom = get_field( 'title_bottom' ) ?: '';
$bg_color     = get_field( 'bg_color' )     ?: '';
$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#C83030';
$items_raw = get_field( 'items' ) ?: [];

if ( empty( $items_raw ) ) {
	return; // nothing to render
}

// Unique ID so multiple instances on the same page don't share CSS vars
$uid = 'oa-' . uniqid();
// Dark mode: detect dark bg
$is_dark = $bg_color && in_array( strtolower( $bg_color ), [ '#111319', '#000000', '#000', '#111', '#1a1a1a' ] );
$section_classes = 'oa-section' . ( $is_dark ? ' oa-section--dark' : '' );
?>

<section
	class="<?php echo esc_attr( $section_classes ); ?>"
	id="<?php echo esc_attr( $uid ); ?>"
	style="
		<?php if ( $bg_color ) : ?>--oa-bg: <?php echo esc_attr( $bg_color ); ?>;<?php endif; ?>
		--oa-pt: <?php echo $pt; ?>px;
		--oa-pb: <?php echo $pb; ?>px;
		--oa-pt-mob: <?php echo $pt_mob; ?>px;
		--oa-pb-mob: <?php echo $pb_mob; ?>px;
		--oa-speed: <?php echo $speed; ?>s;
	"
	aria-label="<?php esc_attr_e( 'Our Awards', 'lionwood' ); ?>"
>
	<?php if ( $title_top || $title_bottom ) : ?>
	<div class="oa-section__container">
		<h2 class="oa-heading">
			<?php if ( $title_top ) : ?>
				<span class="oa-heading__top"><?php echo esc_html( $title_top ); ?></span>
			<?php endif; ?>
			<?php if ( $title_bottom ) : ?>
				<span class="oa-heading__bottom"><?php echo esc_html( $title_bottom ); ?></span>
			<?php endif; ?>
		</h2>
	</div>
	<?php endif; ?>

	<?php /* Fade masks on left and right edges */ ?>
	<div class="oa-section__mask oa-section__mask--left"  aria-hidden="true"></div>
	<div class="oa-section__mask oa-section__mask--right" aria-hidden="true"></div>

	<div class="oa-track" aria-hidden="true">
		<?php
		// Render items twice — second copy creates the seamless loop
		for ( $pass = 0; $pass < 2; $pass++ ) :
			foreach ( $items_raw as $item ) :
				$logo     = $item['logo']  ?? null;
				$text_raw = $item['text']  ?? '';
				$text     = $text_raw ? wp_kses( $text_raw, [ 'br' => [] ] ) : '';
				if ( ! $text ) continue;
			?>
			<div class="oa-item">
				<?php if ( $logo ) : ?>
					<div class="oa-item__logo">
						<img
							src="<?php echo esc_url( $logo['url'] ); ?>"
							alt="<?php echo esc_attr( $logo['alt'] ?: $text_raw ); ?>"
							width="<?php echo esc_attr( $logo['width'] ); ?>"
							height="<?php echo esc_attr( $logo['height'] ); ?>"
							loading="lazy"
						>
					</div>
				<?php endif; ?>

				<?php if ( $text ) : ?>
					<p class="oa-item__text"><?php echo $text; ?></p>
				<?php endif; ?>
			</div>

			<div class="oa-divider" aria-hidden="true"></div>

		<?php
			endforeach;
		endfor;
		?>
	</div>
<?php if ( $decor_enabled ) :
	get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] );
endif; ?>
</section>
