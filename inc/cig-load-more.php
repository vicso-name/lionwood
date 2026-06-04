<?php
/**
 * AJAX Handler: Load More Industries
 *
 * File: inc/cig-load-more.php
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'smplfy_cig_load_more' ) ) {
	function smplfy_cig_load_more(): void {
		check_ajax_referer( 'cig_load_more', 'nonce' );

		$mode       = sanitize_text_field( $_POST['mode']       ?? 'auto' );
		$per_page   = absint( $_POST['per_page']   ?? 3 );
		$offset     = absint( $_POST['offset']     ?? 9 );
		$manual_ids = sanitize_text_field( $_POST['manual_ids'] ?? '' );

		if ( 'manual' === $mode && $manual_ids ) {
			$ids        = array_filter( array_map( 'absint', explode( ',', $manual_ids ) ) );
			$offset_ids = array_slice( $ids, $offset, $per_page );

			if ( empty( $offset_ids ) ) {
				wp_send_json_success( [ 'html' => '', 'count' => 0 ] );
			}

			$query_args = [
				'post_type'      => 'industry',
				'post__in'       => $offset_ids,
				'orderby'        => 'post__in',
				'posts_per_page' => $per_page,
				'post_status'    => 'publish',
			];
		} else {
			$query_args = [
				'post_type'      => 'industry',
				'posts_per_page' => $per_page,
				'offset'         => $offset,
				'post_status'    => 'publish',
				'orderby'        => 'date',
				'order'          => 'DESC',
			];
		}

		$query      = new WP_Query( $query_args );
		$industries = $query->posts;

		if ( empty( $industries ) ) {
			wp_send_json_success( [ 'html' => '', 'count' => 0 ] );
		}

		ob_start();

		foreach ( $industries as $i => $industry ) {
			$industry_id = $industry->ID;
			$title       = esc_html( get_the_title( $industry_id ) );
			$permalink   = esc_url( get_permalink( $industry_id ) );
			$num         = '/ ' . str_pad( $offset + $i + 1, 2, '0', STR_PAD_LEFT );
			$thumb_id    = get_post_thumbnail_id( $industry_id );
			$thumb_url   = $thumb_id ? esc_url( wp_get_attachment_image_url( $thumb_id, 'large' ) ) : '';

			get_template_part( 'template-parts/partials/industry-card', null, [
				'industry_id' => $industry_id,
				'title'       => $title,
				'permalink'   => $permalink,
				'num'         => $num,
				'thumb_url'   => $thumb_url,
			] );
		}

		$html = ob_get_clean();

		wp_send_json_success( [
			'html'  => $html,
			'count' => count( $industries ),
		] );
	}
}

add_action( 'wp_ajax_cig_load_more',        'smplfy_cig_load_more' );
add_action( 'wp_ajax_nopriv_cig_load_more', 'smplfy_cig_load_more' );

if ( ! function_exists( 'smplfy_cig_localize' ) ) {
	function smplfy_cig_localize(): void {
		wp_add_inline_script(
			'theme-main',
			'window.cigAjax = ' . wp_json_encode( [ 'url' => admin_url( 'admin-ajax.php' ) ] ) . ';',
			'before'
		);
	}
}
add_action( 'wp_enqueue_scripts', 'smplfy_cig_localize' );
