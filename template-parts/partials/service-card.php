<?php
/**
 * Partial: Service Card
 *
 * Used by choose-service-grid block and AJAX handler.
 * Args passed via get_template_part $args or direct variables.
 *
 * File: blocks/choose-service-grid/service-card.php
 */

defined( 'ABSPATH' ) || exit;

// Support both get_template_part $args and direct variables
$service_id = $args['service_id'] ?? $service_id ?? 0;
$title      = $args['title']      ?? $title      ?? '';
$permalink  = $args['permalink']  ?? $permalink  ?? '#';
$num        = $args['num']        ?? $num        ?? '/01';
$thumb_url  = $args['thumb_url']  ?? $thumb_url  ?? '';
$sub_ids    = $args['sub_ids']    ?? $sub_ids    ?? [];
$has_subs   = $args['has_subs']   ?? $has_subs   ?? false;

if ( ! $service_id ) return;

// Get sub post data for subservices panel
$sub_posts = [];
if ( $has_subs ) {
	foreach ( $sub_ids as $sid ) {
		$sub_posts[] = [
			'id'        => $sid,
			'title'     => get_the_title( $sid ),
			'permalink' => get_permalink( $sid ),
		];
	}
}
?>
<article
	class="csg-card<?php echo $has_subs ? ' csg-card--has-subs' : ''; ?>"
	data-service-id="<?php echo esc_attr( $service_id ); ?>"
>
	<?php /* ── Card link (whole card is clickable except subservices panel) */ ?>
	<a class="csg-card__link" href="<?php echo $permalink; ?>" aria-label="<?php echo $title; ?>">

		<?php /* Background image */ ?>
		<?php if ( $thumb_url ) : ?>
			<div
				class="csg-card__bg"
				style="background-image: url('<?php echo $thumb_url; ?>');"
				aria-hidden="true"
			></div>
		<?php endif; ?>
		<div class="csg-card__overlay" aria-hidden="true"></div>

		<?php /* Top row: arrow + title (left) | number (right) */ ?>
		<div class="csg-card__top">
			<div class="csg-card__title-wrap">
				<span class="csg-card__arrow" aria-hidden="true">
					<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13" fill="none">
						<path d="M3.43644 9.56308L9.5647 3.43482M4.96851 3.43482L9.5647 3.43482L9.5647 8.03101" stroke="#F7F7F7" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</span>
				<h3 class="csg-card__title"><?php echo $title; ?></h3>
			</div>
			<span class="csg-card__num"><?php echo esc_html( $num ); ?></span>
		</div>

	</a><!-- .csg-card__link -->

	<?php /* ── Subservices plank ───────────────────────────────────────── */ ?>
	<?php if ( $has_subs ) : ?>
		<div class="csg-card__subs-plank" data-subs-toggle aria-expanded="false">
			<span class="csg-card__subs-label"><?php esc_html_e( 'Subservices', 'theme' ); ?></span>
			<span class="csg-card__subs-icon csg-card__subs-icon--plus" aria-hidden="true">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
					<path d="M15 8C15 8.55229 14.5523 9 14 9H9V14C9 14.5523 8.55228 15 8 15C7.44772 15 7 14.5523 7 14V9H2C1.44772 9 1 8.55229 1 8C1 7.44772 1.44772 7 2 7H7V2C7 1.44772 7.44772 1 8 1C8.55228 1 9 1.44772 9 2V7H14C14.5523 7 15 7.44772 15 8Z" fill="#848588"/>
				</svg>
			</span>
			<span class="csg-card__subs-icon csg-card__subs-icon--close" aria-hidden="true" style="display:none;">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
					<g clip-path="url(#clip0_1338_2599)">
						<path d="M12.9497 12.9489C12.5592 13.3394 11.9261 13.3394 11.5355 12.9489L8 9.41332L4.46447 12.9489C4.07394 13.3394 3.44078 13.3394 3.05025 12.9489C2.65973 12.5583 2.65973 11.9252 3.05025 11.5346L6.58579 7.9991L3.05025 4.46357C2.65973 4.07305 2.65973 3.43988 3.05025 3.04936C3.44078 2.65883 4.07394 2.65883 4.46447 3.04936L8 6.58489L11.5355 3.04936C11.9261 2.65883 12.5592 2.65883 12.9497 3.04936C13.3403 3.43988 13.3403 4.07305 12.9497 4.46357L9.41421 7.9991L12.9497 11.5346C13.3403 11.9252 13.3403 12.5583 12.9497 12.9489Z" fill="#848588"/>
					</g>
					<defs>
						<clipPath id="clip0_1338_2599">
							<rect width="16" height="16" fill="white"/>
						</clipPath>
					</defs>
				</svg>
			</span>
		</div>

		<?php /* Subservices panel */ ?>
		<div class="csg-card__subs-panel" data-subs-panel aria-hidden="true">
			<div class="csg-card__subs-panel-inner">
				<div class="csg-card__subs-panel-header">
					<span><?php esc_html_e( 'Subservices', 'theme' ); ?></span>
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
						<g clip-path="url(#clip0_panel_close)">
							<path d="M12.9497 12.9489C12.5592 13.3394 11.9261 13.3394 11.5355 12.9489L8 9.41332L4.46447 12.9489C4.07394 13.3394 3.44078 13.3394 3.05025 12.9489C2.65973 12.5583 2.65973 11.9252 3.05025 11.5346L6.58579 7.9991L3.05025 4.46357C2.65973 4.07305 2.65973 3.43988 3.05025 3.04936C3.44078 2.65883 4.07394 2.65883 4.46447 3.04936L8 6.58489L11.5355 3.04936C11.9261 2.65883 12.5592 2.65883 12.9497 3.04936C13.3403 3.43988 13.3403 4.07305 12.9497 4.46357L9.41421 7.9991L12.9497 11.5346C13.3403 11.9252 13.3403 12.5583 12.9497 12.9489Z" fill="#848588"/>
						</g>
						<defs><clipPath id="clip0_panel_close"><rect width="16" height="16" fill="white"/></clipPath></defs>
					</svg>
				</div>
				<div class="csg-card__subs-list">
					<?php foreach ( $sub_posts as $sub ) : ?>
						<a
							class="csg-card__sub-item"
							href="<?php echo esc_url( $sub['permalink'] ); ?>"
						>
							<span class="csg-card__sub-title"><?php echo esc_html( $sub['title'] ); ?></span>
							<span class="csg-card__sub-dot" aria-hidden="true">
								<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
									<path d="M3.55534 9.8927L9.89492 3.55312M5.14024 3.55312L9.89492 3.55312L9.89492 8.30781" stroke="#F7F7F7" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</span>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>

</article>
