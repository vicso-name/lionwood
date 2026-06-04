<?php
/**
 * AJAX Handler: Load More Services
 *
 * File: inc/ajax/csg-load-more.php
 * Include in functions.php:
 *   require_once get_template_directory() . '/inc/ajax/csg-load-more.php';
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'smplfy_csg_load_more' ) ) {
	function smplfy_csg_load_more(): void {
		check_ajax_referer( 'csg_load_more', 'nonce' );

		$mode       = sanitize_text_field( $_POST['mode']       ?? 'auto' );
		$per_page   = absint( $_POST['per_page']   ?? 3 );
		$offset     = absint( $_POST['offset']     ?? 9 );
		$manual_ids = sanitize_text_field( $_POST['manual_ids'] ?? '' );

		if ( 'manual' === $mode && $manual_ids ) {
			$ids         = array_filter( array_map( 'absint', explode( ',', $manual_ids ) ) );
			$offset_ids  = array_slice( $ids, $offset, $per_page );

			if ( empty( $offset_ids ) ) {
				wp_send_json_success( [ 'html' => '', 'count' => 0 ] );
			}

			$query_args = [
				'post_type'      => 'service',
				'post__in'       => $offset_ids,
				'orderby'        => 'post__in',
				'posts_per_page' => $per_page,
				'post_status'    => 'publish',
				'post_parent'    => 0,
			];
		} else {
			$query_args = [
				'post_type'      => 'service',
				'posts_per_page' => $per_page,
				'offset'         => $offset,
				'post_parent'    => 0,
				'post_status'    => 'publish',
				'orderby'        => 'date',
				'order'          => 'DESC',
			];
		}

		$query    = new WP_Query( $query_args );
		$services = $query->posts;

		if ( empty( $services ) ) {
			wp_send_json_success( [ 'html' => '', 'count' => 0 ] );
		}

		// Ensure helper function is available
		if ( ! function_exists( 'smplfy_get_subservices' ) ) {
			function smplfy_get_subservices( int $parent_id, int $limit = 5 ): array {
				return get_posts( [
					'post_type'      => 'service',
					'post_parent'    => $parent_id,
					'posts_per_page' => $limit,
					'post_status'    => 'publish',
					'orderby'        => 'menu_order',
					'order'          => 'ASC',
					'fields'         => 'ids',
				] );
			}
		}

		ob_start();

		// Global offset for numbering
		$global_offset = $offset;

		foreach ( $services as $i => $service ) {
			$service_id = $service->ID;
			$title      = esc_html( get_the_title( $service_id ) );
			$permalink  = esc_url( get_permalink( $service_id ) );
			$num        = '/ ' . str_pad( $global_offset + $i + 1, 2, '0', STR_PAD_LEFT );
			$thumb_id   = get_post_thumbnail_id( $service_id );
			$thumb_url  = $thumb_id ? esc_url( wp_get_attachment_image_url( $thumb_id, 'large' ) ) : '';
			$sub_ids    = smplfy_get_subservices( $service_id );
			$has_subs   = ! empty( $sub_ids );

			get_template_part( 'template-parts/partials/service-card', null, [
				'service_id' => $service_id,
				'title'      => $title,
				'permalink'  => $permalink,
				'num'        => $num,
				'thumb_url'  => $thumb_url,
				'sub_ids'    => $sub_ids,
				'has_subs'   => $has_subs,
			] );
		}

		$html = ob_get_clean();

		wp_send_json_success( [
			'html'  => $html,
			'count' => count( $services ),
		] );
	}
}

add_action( 'wp_ajax_csg_load_more',        'smplfy_csg_load_more' );
add_action( 'wp_ajax_nopriv_csg_load_more', 'smplfy_csg_load_more' );

// Localize AJAX URL for JS
if ( ! function_exists( 'smplfy_csg_localize' ) ) {
	function smplfy_csg_localize(): void {
		wp_add_inline_script(
			'theme-main', // adjust to your main JS handle
			'window.csgAjax = ' . wp_json_encode( [ 'url' => admin_url( 'admin-ajax.php' ) ] ) . ';',
			'before'
		);
	}
}
add_action( 'wp_enqueue_scripts', 'smplfy_csg_localize' );
