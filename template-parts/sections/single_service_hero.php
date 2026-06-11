<?php
/**
 * Block: Single Service Hero
 *
 * ACF block slug : acf/single-service-hero
 * Template file  : blocks/single-service-hero/single-service-hero.php
 *
 * Layout:
 *   - Dark bg with overlay + optional bg image
 *   - Left col: prefix heading (gray) + title (white) + wysiwyg desc + CTA
 *   - Right col: decorative image — bottom flush with section, top overflows up
 *   - Optional decor-bottom partial
 */

defined( 'ABSPATH' ) || exit;

$pt     = absint( get_field( 'padding_top' )        ?: 80 );
$pb     = absint( get_field( 'padding_bottom' )     ?: 140 );
$pt_mob = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob = absint( get_field( 'padding_bottom_mob' ) ?: 70 );

$bg_image      = get_field( 'bg_image' );
$bg_url        = $bg_image ? esc_url( $bg_image['url'] ) : '';

$title_prefix  = get_field( 'title_prefix' ) ?: __( 'Service:', 'theme' );
// Title: ACF field or fallback to post title
$title_raw     = get_field( 'title' );
$title         = $title_raw ? esc_html( $title_raw ) : esc_html( get_the_title() );

$description   = get_field( 'description' ) ?: ''; // wysiwyg — safe HTML

$cta_raw       = get_field( 'cta_link' );
$cta_url       = ! empty( $cta_raw['url'] )    ? esc_url( $cta_raw['url'] )    : '';
$cta_label     = ! empty( $cta_raw['title'] )  ? esc_html( $cta_raw['title'] ) : __( "Let's Get Started", 'theme' );
$cta_target    = ! empty( $cta_raw['target'] ) ? $cta_raw['target']             : '_self';

$hero_image    = get_field( 'hero_image' );
$image_style   = get_field( 'image_style' ) ?: 'overflow';

$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#ffffff';

// Build inline bg style
$bg_style = '';
if ( $bg_url ) {
	$bg_style = "background-image: linear-gradient(0deg, rgba(0,0,0,0.40) 0%, rgba(0,0,0,0.40) 100%), url('{$bg_url}');";
} else {
	$bg_style = 'background-color: #111319;';
}
?>

<section
	class="ssh-section ssh-section--<?php echo esc_attr( $image_style ); ?>"
	style="
		--ssh-pt: <?php echo $pt; ?>px;
		--ssh-pb: <?php echo $pb; ?>px;
		--ssh-pt-mob: <?php echo $pt_mob; ?>px;
		--ssh-pb-mob: <?php echo $pb_mob; ?>px;
		<?php echo $bg_style; ?>
	"
>
	<div class="ssh-section__container">
		<div class="ssh-body ssh-body--<?php echo esc_attr( $image_style ); ?>">

			<?php /* ── Left column ──────────────────────────────────────── */ ?>
			<div class="ssh-content">

				<div class="ssh-heading">
					<span class="ssh-heading__prefix ssh-anim" data-delay="0"><?php echo esc_html( $title_prefix ); ?></span>
					<span class="ssh-heading__title ssh-anim" data-delay="120"><?php echo $title; ?></span>
				</div>

				<div class="ssh-content-inner ssh-anim" data-delay="260">
					<?php if ( $description ) : ?>
						<div class="ssh-description">
							<?php echo wp_kses_post( $description ); ?>
						</div>
					<?php endif; ?>

					<?php if ( $cta_url ) : ?>
						<a
							class="ssh-cta-btn"
							href="<?php echo $cta_url; ?>"
							target="<?php echo esc_attr( $cta_target ); ?>"
							<?php echo '_blank' === $cta_target ? 'rel="noopener noreferrer"' : ''; ?>
						><?php echo $cta_label; ?></a>
					<?php endif; ?>
				</div><!-- .ssh-content-inner -->

			</div><!-- .ssh-content -->

			<?php /* ── Right column: decorative image ─────────────────── */ ?>
			<?php if ( $hero_image ) : ?>
				<div class="ssh-image-wrap ssh-anim" data-delay="80" aria-hidden="true">
					<img
						class="ssh-image"
						src="<?php echo esc_url( $hero_image['url'] ); ?>"
						alt=""
						width="679"
						height="786"
						loading="eager"
					>
				</div>
			<?php endif; ?>

		</div><!-- .ssh-body -->
	</div><!-- .ssh-section__container -->

	<?php if ( $decor_enabled ) : ?>
		<?php get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] ); ?>
	<?php endif; ?>

</section>
