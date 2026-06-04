<?php
/**
 * Partial: Industry Card
 *
 * Used by choose-industries-grid block and AJAX handler.
 * Args passed via get_template_part $args or direct variables.
 */

defined( 'ABSPATH' ) || exit;

$industry_id = $args['industry_id'] ?? $industry_id ?? 0;
$title       = $args['title']       ?? $title       ?? '';
$permalink   = $args['permalink']   ?? $permalink   ?? '#';
$num         = $args['num']         ?? $num         ?? '/01';
$thumb_url   = $args['thumb_url']   ?? $thumb_url   ?? '';

if ( ! $industry_id ) return;
?>
<article
	class="cig-card"
	data-industry-id="<?php echo esc_attr( $industry_id ); ?>"
>
	<a class="cig-card__link" href="<?php echo $permalink; ?>" aria-label="<?php echo $title; ?>">

		<?php if ( $thumb_url ) : ?>
			<div
				class="cig-card__bg"
				style="background-image: url('<?php echo $thumb_url; ?>');"
				aria-hidden="true"
			></div>
		<?php endif; ?>
		<div class="cig-card__overlay" aria-hidden="true"></div>

		<div class="cig-card__top">
			<div class="cig-card__title-wrap">
				<span class="cig-card__arrow" aria-hidden="true">
					<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13" fill="none">
						<path d="M3.43644 9.56308L9.5647 3.43482M4.96851 3.43482L9.5647 3.43482L9.5647 8.03101" stroke="#F7F7F7" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</span>
				<h3 class="cig-card__title"><?php echo $title; ?></h3>
			</div>
			<span class="cig-card__num"><?php echo esc_html( $num ); ?></span>
		</div>

	</a>
</article>
