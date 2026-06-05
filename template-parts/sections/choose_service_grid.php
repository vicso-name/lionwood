<?php
/**
 * Block: Choose Service Grid
 *
 * ACF block slug : acf/choose-service-grid
 * Template file  : blocks/choose-service-grid/choose-service-grid.php
 */

defined( 'ABSPATH' ) || exit;

$pt          = absint( get_field( 'padding_top' )        ?: 100 );
$pb          = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob      = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob      = absint( get_field( 'padding_bottom_mob' ) ?: 70 );
$marquee_raw    = get_field( 'marquee_text' ) ?: __( 'Choose Your Service', 'theme' );
$marquee        = esc_html( $marquee_raw );
$mode           = get_field( 'mode' ) ?: 'auto';
$manual_ids     = get_field( 'services' ) ?: [];
$decor_enabled  = get_field( 'decor_bottom_enabled' );
$decor_color    = get_field( 'decor_bottom_color' ) ?: '#ffffff';

// Initial page size
$per_page    = 9;

// Build query args
if ( 'manual' === $mode && ! empty( $manual_ids ) ) {
	$query_args = [
		'post_type'      => 'service',
		'post__in'       => array_map( 'absint', $manual_ids ),
		'orderby'        => 'post__in',
		'posts_per_page' => $per_page,
		'post_parent'    => 0, // top-level only
		'post_status'    => 'publish',
	];
} else {
	$query_args = [
		'post_type'      => 'service',
		'posts_per_page' => $per_page,
		'post_parent'    => 0,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
	];
}

$services_query = new WP_Query( $query_args );
$services       = $services_query->posts;

// Total count for Load More
$total_args = $query_args;
$total_args['posts_per_page'] = -1;
$total_args['fields'] = 'ids';
$total_ids  = 'manual' === $mode && ! empty( $manual_ids ) ? $manual_ids : get_posts( $total_args );
$total      = count( $total_ids );
$has_more   = $total > $per_page;

// Unique block ID for AJAX
$block_id = 'csg-' . uniqid();

?>

<section
	class="csg-section"
	id="<?php echo esc_attr( $block_id ); ?>"
	style="
		--csg-pt: <?php echo $pt; ?>px;
		--csg-pb: <?php echo $pb; ?>px;
		--csg-pt-mob: <?php echo $pt_mob; ?>px;
		--csg-pb-mob: <?php echo $pb_mob; ?>px;
	"
	data-mode="<?php echo esc_attr( $mode ); ?>"
	data-manual-ids="<?php echo esc_attr( implode( ',', $manual_ids ) ); ?>"
	data-per-page="3"
	data-offset="<?php echo esc_attr( $per_page ); ?>"
	data-total="<?php echo esc_attr( $total ); ?>"
>
	<?php /* ── Marquee ─────────────────────────────────────────────────── */ ?>
	<div class="csg-marquee" aria-hidden="true">
		<div class="csg-marquee__track">
			<?php for ( $i = 0; $i < 8; $i++ ) : ?>
				<span class="csg-marquee__item"><?php echo $marquee; ?></span>
			<?php endfor; ?>
			<?php for ( $i = 0; $i < 8; $i++ ) : ?>
				<span class="csg-marquee__item"><?php echo $marquee; ?></span>
			<?php endfor; ?>
		</div>
	</div>

	<div class="csg-section__container">

		<?php /* ── Grid ────────────────────────────────────────────────── */ ?>
		<div class="csg-grid" data-grid>
			<?php foreach ( $services as $i => $service ) :
				$service_id  = $service->ID;
				$title       = esc_html( get_the_title( $service_id ) );
				$permalink   = esc_url( get_permalink( $service_id ) );
				$num         = '/ ' . str_pad( $i + 1, 2, '0', STR_PAD_LEFT );
				$thumb_id    = get_post_thumbnail_id( $service_id );
				$thumb_url   = $thumb_id ? esc_url( wp_get_attachment_image_url( $thumb_id, 'large' ) ) : '';
				$sub_ids     = lionwood_get_subservices( $service_id );
				$has_subs    = ! empty( $sub_ids );
			?>
				<?php get_template_part( 'template-parts/partials/service-card', null, [
					'service_id' => $service_id,
					'title'      => $title,
					'permalink'  => $permalink,
					'num'        => $num,
					'thumb_url'  => $thumb_url,
					'sub_ids'    => $sub_ids,
					'has_subs'   => $has_subs,
				] ); ?>
			<?php endforeach; ?>
		</div><!-- .csg-grid -->

		<?php /* ── Load More ───────────────────────────────────────────── */ ?>
		<?php if ( $has_more ) : ?>
			<div class="csg-loadmore-wrap">
				<button
					class="csg-loadmore-btn"
					data-loadmore
					data-nonce="<?php echo esc_attr( wp_create_nonce( 'csg_load_more' ) ); ?>"
				><?php esc_html_e( 'Load More', 'theme' ); ?></button>
			</div>
		<?php endif; ?>

	</div><!-- .csg-section__container -->
    <?php if ( $decor_enabled ) : ?>
        <?php get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] ); ?>
    <?php endif; ?>

</section>
