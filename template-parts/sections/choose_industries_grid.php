<?php
/**
 * Block: Choose Industries Grid
 *
 * ACF block slug : acf/choose-industries-grid
 * Template file  : template-parts/sections/choose_industries_grid.php
 */

defined( 'ABSPATH' ) || exit;

$pt          = absint( get_field( 'padding_top' )        ?: 100 );
$pb          = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob      = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob      = absint( get_field( 'padding_bottom_mob' ) ?: 70 );
$marquee_raw = get_field( 'marquee_text' ) ?: __( 'Choose Your Industry', 'theme' );
$marquee     = esc_html( $marquee_raw );
$mode        = get_field( 'mode' ) ?: 'auto';
$manual_ids  = get_field( 'industries' ) ?: [];
$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#ffffff';

$per_page = 9;

if ( 'manual' === $mode && ! empty( $manual_ids ) ) {
	$query_args = [
		'post_type'      => 'industry',
		'post__in'       => array_map( 'absint', $manual_ids ),
		'orderby'        => 'post__in',
		'posts_per_page' => $per_page,
		'post_status'    => 'publish',
	];
} else {
	$query_args = [
		'post_type'      => 'industry',
		'posts_per_page' => $per_page,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
	];
}

$industries_query = new WP_Query( $query_args );
$industries       = $industries_query->posts;

$total_args                   = $query_args;
$total_args['posts_per_page'] = -1;
$total_args['fields']         = 'ids';
$total_ids = 'manual' === $mode && ! empty( $manual_ids ) ? $manual_ids : get_posts( $total_args );
$total     = count( $total_ids );
$has_more  = $total > $per_page;

$block_id = 'cig-' . uniqid();
?>

<section
	class="cig-section"
	id="<?php echo esc_attr( $block_id ); ?>"
	style="
		--cig-pt: <?php echo $pt; ?>px;
		--cig-pb: <?php echo $pb; ?>px;
		--cig-pt-mob: <?php echo $pt_mob; ?>px;
		--cig-pb-mob: <?php echo $pb_mob; ?>px;
	"
	data-mode="<?php echo esc_attr( $mode ); ?>"
	data-manual-ids="<?php echo esc_attr( implode( ',', $manual_ids ) ); ?>"
	data-per-page="3"
	data-offset="<?php echo esc_attr( $per_page ); ?>"
	data-total="<?php echo esc_attr( $total ); ?>"
>
	<?php /* ── Marquee ─────────────────────────────────────────────────── */ ?>
	<div class="cig-marquee" aria-hidden="true">
		<div class="cig-marquee__track">
			<?php for ( $i = 0; $i < 8; $i++ ) : ?>
				<span class="cig-marquee__item"><?php echo $marquee; ?></span>
			<?php endfor; ?>
			<?php for ( $i = 0; $i < 8; $i++ ) : ?>
				<span class="cig-marquee__item"><?php echo $marquee; ?></span>
			<?php endfor; ?>
		</div>
	</div>

	<div class="cig-section__container">

		<?php /* ── Grid ────────────────────────────────────────────────── */ ?>
		<div class="cig-grid" data-grid>
			<?php foreach ( $industries as $i => $industry ) :
				$industry_id = $industry->ID;
				$title       = esc_html( get_the_title( $industry_id ) );
				$permalink   = esc_url( get_permalink( $industry_id ) );
				$num         = '/ ' . str_pad( $i + 1, 2, '0', STR_PAD_LEFT );
				$thumb_id    = get_post_thumbnail_id( $industry_id );
				$thumb_url   = $thumb_id ? esc_url( wp_get_attachment_image_url( $thumb_id, 'large' ) ) : '';
			?>
				<?php get_template_part( 'template-parts/partials/industry-card', null, [
					'industry_id' => $industry_id,
					'title'       => $title,
					'permalink'   => $permalink,
					'num'         => $num,
					'thumb_url'   => $thumb_url,
				] ); ?>
			<?php endforeach; ?>
		</div><!-- .cig-grid -->

		<?php /* ── Load More ───────────────────────────────────────────── */ ?>
		<?php if ( $has_more ) : ?>
			<div class="cig-loadmore-wrap">
				<button
					class="cig-loadmore-btn"
					data-loadmore
					data-nonce="<?php echo esc_attr( wp_create_nonce( 'cig_load_more' ) ); ?>"
				><?php esc_html_e( 'Load More', 'theme' ); ?></button>
			</div>
		<?php endif; ?>

	</div><!-- .cig-section__container -->

	<?php if ( $decor_enabled ) : ?>
		<?php get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] ); ?>
	<?php endif; ?>

</section>
