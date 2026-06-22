<?php
/**
 * Block: Insights & Articles
 *
 * ACF block slug : acf/insights-articles
 * Template file  : blocks/insights-articles/insights-articles.php
 *
 * Fields:
 *   title_top     (text)         — first heading line
 *   title_bottom  (text)         — second heading line, uppercase
 *   articles      (relationship) — up to 3 WP_Post objects
 *   explore_link  (link)         — optional CTA; falls back to /blog/
 */

defined( 'ABSPATH' ) || exit;

// ── Field values ────────────────────────────────────────────────────────────
$title_top    = get_field( 'title_top' )    ?: __( 'Latest', 'lionwood' );
$title_bottom = get_field( 'title_bottom' ) ?: __( 'Insights & Articles', 'lionwood' );
$posts        = get_field( 'articles' )     ?: [];
$explore_raw  = get_field( 'explore_link' );
$bg_style     = get_field( 'background_style' ) ?: 'light';
$is_dark      = 'dark' === $bg_style;

$explore_url    = ! empty( $explore_raw['url'] )    ? esc_url( $explore_raw['url'] )       : esc_url( home_url( '/blog/' ) );
$explore_label  = ! empty( $explore_raw['title'] )  ? esc_html( $explore_raw['title'] )    : __( 'Explore More', 'theme' );
$explore_target = ! empty( $explore_raw['target'] ) ? $explore_raw['target']               : '_self';

// ── Helpers ──────────────────────────────────────────────────────────────────

if ( ! function_exists( 'ia_get_post_category' ) ) {
	/**
	 * Returns the first category name for a post, or an empty string.
	 *
	 * @param int $post_id
	 * @return string
	 */
	function ia_get_post_category( int $post_id ): string {
		$cats = get_the_category( $post_id );
		return ! empty( $cats ) ? esc_html( $cats[0]->name ) : '';
	}
}

if ( ! function_exists( 'ia_get_excerpt' ) ) {
	/**
	 * Returns a trimmed excerpt; falls back to wp_trim_words on post content.
	 *
	 * @param WP_Post $post
	 * @param int     $length Number of words.
	 * @return string
	 */
	function ia_get_excerpt( $post, int $length = 20 ): string {
		if ( ! $post instanceof WP_Post ) {
			$post = get_post( (int) $post );
		}
		if ( ! $post instanceof WP_Post ) return '';
		if ( $post->post_excerpt ) {
			return esc_html( wp_trim_words( $post->post_excerpt, $length, '…' ) );
		}
		return esc_html( wp_trim_words( strip_shortcodes( $post->post_content ), $length, '…' ) );
	}
}
?>

<section class="ia-section<?php echo $is_dark ? ' ia-section--dark' : ''; ?>">
	<div class="ia-section__container">

		<?php /* ── Heading ──────────────────────────────────────────────────── */ ?>
		<div class="ia-section__heading" aria-label="<?php echo esc_attr( $title_top . ' ' . $title_bottom ); ?>">
			<span class="ia-section__heading-top" aria-hidden="true">
				<?php echo esc_html( $title_top ); ?>
			</span>
			<span class="ia-section__heading-bottom" aria-hidden="true">
				<?php echo esc_html( $title_bottom ); ?>
			</span>
		</div>

		<?php /* ── Cards grid ───────────────────────────────────────────────── */ ?>
		<?php if ( ! empty( $posts ) ) : ?>
			<div class="ia-section__grid">
				<?php foreach ( $posts as $index => $post ) :
					// Normalise: relationship field may return WP_Post objects or raw IDs.
					if ( ! $post instanceof WP_Post ) {
						$post = get_post( (int) $post );
					}
					if ( ! $post instanceof WP_Post ) continue;

					$is_featured = ( 0 === $index );
					$post_id     = $post->ID;
					$permalink   = esc_url( get_permalink( $post_id ) );
					$title       = esc_html( get_the_title( $post_id ) );
					$excerpt     = ia_get_excerpt( $post );
					$category    = ia_get_post_category( $post_id );
					$thumb_id    = get_post_thumbnail_id( $post_id );
					$thumb_src   = $thumb_id
						? wp_get_attachment_image_src( $thumb_id, $is_featured ? 'large' : 'medium_large' )
						: null;
					$thumb_alt   = $thumb_id ? esc_attr( get_post_meta( $thumb_id, '_wp_attachment_image_alt', true ) ) : esc_attr( $title );
					$card_class  = 'ia-card' . ( $is_featured ? ' ia-card--featured' : '' );
				?>
					<article class="<?php echo esc_attr( $card_class ); ?>">
						<a class="ia-card__link" href="<?php echo $permalink; ?>" aria-label="<?php echo esc_attr( $title ); ?>">

							<?php /* Image wrapper with zoom-on-hover */ ?>
							<div class="ia-card__image-wrap">
								<?php if ( $thumb_src ) : ?>
									<img
										class="ia-card__image"
										src="<?php echo esc_url( $thumb_src[0] ); ?>"
										width="<?php echo esc_attr( $thumb_src[1] ); ?>"
										height="<?php echo esc_attr( $thumb_src[2] ); ?>"
										alt="<?php echo $thumb_alt; ?>"
										loading="<?php echo $is_featured ? 'eager' : 'lazy'; ?>"
									>
								<?php else : ?>
									<div class="ia-card__image-placeholder" aria-hidden="true"></div>
								<?php endif; ?>

								<?php if ( $category ) : ?>
									<span class="ia-card__tag" aria-label="<?php esc_attr_e( 'Category', 'theme' ); ?>: <?php echo esc_attr( $category ); ?>">
										#<?php echo $category; ?>
									</span>
								<?php endif; ?>
							</div>

							<div class="ia-card__body">
								<h3 class="ia-card__title"><?php echo $title; ?></h3>
								<?php if ( $excerpt ) : ?>
									<p class="ia-card__excerpt"><?php echo $excerpt; ?></p>
								<?php endif; ?>
							</div>

						</a>
					</article>

				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php /* ── CTA ──────────────────────────────────────────────────────── */ ?>
		<div class="ia-section__cta">
			<a
				class="ia-section__btn btn btn--primary"
				href="<?php echo $explore_url; ?>"
				target="<?php echo esc_attr( $explore_target ); ?>"
				<?php echo '_blank' === $explore_target ? 'rel="noopener noreferrer"' : ''; ?>
			>
				<?php echo $explore_label; ?>
			</a>
		</div>

	</div>
</section>
