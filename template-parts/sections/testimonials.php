<?php
/**
 * Block: Testimonials Section
 *
 * ACF block slug : acf/testimonials-section
 * Template file  : blocks/testimonials-section/testimonials-section.php
 *
 * Dependencies   : Swiper JS (loaded via wp_enqueue_script in functions.php)
 */

defined( 'ABSPATH' ) || exit;

// ── Block fields ─────────────────────────────────────────────────────────────
$pt     = absint( get_field( 'padding_top' )        ?: 140 );
$pb     = absint( get_field( 'padding_bottom' )     ?: 200 );
$pt_mob = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob = absint( get_field( 'padding_bottom_mob' ) ?: 140 );

$title_top    = get_field( 'title_top' )    ?: __( 'What Our', 'lionwood' );
$title_bottom = get_field( 'title_bottom' ) ?: __( 'Clients Say', 'lionwood' );

$rating_score = get_field( 'rating_score' ) ?: '4.9';
$rating_stars = (int) ( get_field( 'rating_stars' ) ?: 5 );
$rating_label = get_field( 'rating_label' ) ?: __( 'Rating on Clutch [ 31 reviews ]', 'lionwood' );

$testimonials = get_field( 'testimonials' ) ?: [];

// ── Allowed HTML for result text (supports <strong> only) ────────────────────
$allowed_result_html = [ 'strong' => [] ];

// ── Star SVG helper ──────────────────────────────────────────────────────────
if ( ! function_exists( 'ts_star_svg' ) ) {
	function ts_star_svg(): string {
		return '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
			<path d="M8.99648 13.1023L5.36648 15.2943C5.25982 15.3469 5.16082 15.3683 5.06948 15.3583C4.97882 15.3476 4.89048 15.3163 4.80448 15.2643C4.71782 15.2109 4.65248 15.1356 4.60848 15.0383C4.56448 14.9409 4.56048 14.8346 4.59648 14.7193L5.56248 10.6093L2.36748 7.83926C2.27748 7.76592 2.21815 7.67826 2.18948 7.57626C2.16082 7.47426 2.16715 7.37659 2.20848 7.28326C2.24982 7.18992 2.30482 7.11326 2.37348 7.05326C2.44282 6.99526 2.53615 6.95592 2.65348 6.93526L6.86948 6.56726L8.51348 2.67526C8.55882 2.56526 8.62415 2.48592 8.70949 2.43726C8.79482 2.38859 8.89048 2.36426 8.99648 2.36426C9.10248 2.36426 9.19848 2.38859 9.28448 2.43726C9.37048 2.48592 9.43548 2.56526 9.47948 2.67526L11.1235 6.56726L15.3385 6.93526C15.4565 6.95526 15.5502 6.99492 15.6195 7.05426C15.6888 7.11292 15.7442 7.18926 15.7855 7.28326C15.8262 7.37659 15.8322 7.47426 15.8035 7.57626C15.7748 7.67826 15.7155 7.76592 15.6255 7.83926L12.4305 10.6093L13.3965 14.7193C13.4338 14.8333 13.4302 14.9393 13.3855 15.0373C13.3408 15.1353 13.2752 15.2106 13.1885 15.2633C13.1032 15.3166 13.0148 15.3483 12.9235 15.3583C12.8328 15.3683 12.7342 15.3469 12.6275 15.2943L8.99648 13.1023Z" fill="#FC0000"/>
		</svg>';
	}
}
?>

<section
	class="ts-section"
	style="
		--ts-pt: <?php echo $pt; ?>px;
		--ts-pb: <?php echo $pb; ?>px;
		--ts-pt-mob: <?php echo $pt_mob; ?>px;
		--ts-pb-mob: <?php echo $pb_mob; ?>px;
	"
>
	<div class="ts-section__container">

		<?php /* ── Header row: title block + Clutch rating ─────────────────── */ ?>
		<div class="ts-section__header">

			<div class="ts-section__title-block">
				<span class="ts-section__title-top">
					<?php echo esc_html( $title_top ); ?>
				</span>
				<span class="ts-section__title-bottom">
					<?php echo esc_html( $title_bottom ); ?>
				</span>
			</div>

			<div class="ts-section__rating" aria-label="<?php echo esc_attr( $rating_score . ' — ' . $rating_label ); ?>">
				<span class="ts-section__rating-score" aria-hidden="true">
					<?php echo esc_html( $rating_score ); ?>
				</span>
				<div class="ts-section__rating-meta">
					<div class="ts-section__stars" aria-hidden="true">
						<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
							<span class="ts-section__star<?php echo $i <= $rating_stars ? ' ts-section__star--filled' : ''; ?>">
								<?php echo ts_star_svg(); ?>
							</span>
						<?php endfor; ?>
					</div>
					<p class="ts-section__rating-label">
						<?php echo esc_html( $rating_label ); ?>
					</p>
				</div>
			</div>

		</div><!-- .ts-section__header -->

		<?php /* ── Slider ───────────────────────────────────────────────────── */ ?>
		<?php if ( ! empty( $testimonials ) ) : ?>
			<div class="ts-section__slider-wrap">

				<div class="swiper ts-swiper">
					<div class="swiper-wrapper">

						<?php foreach ( $testimonials as $post ) :
							$post_id  = $post->ID;

							// Icons repeater
							$icons = get_field( 'icons', $post_id ) ?: [];

							// Reviewer info
							$reviewer_name     = esc_html( get_field( 'reviewer_name', $post_id ) ?: get_the_title( $post_id ) );
							$reviewer_position = esc_html( get_field( 'reviewer_position', $post_id ) ?: '' );

							// Case link
							$case_raw    = get_field( 'case_link', $post_id );
							$case_url    = ! empty( $case_raw['url'] )    ? esc_url( $case_raw['url'] )    : '';
							$case_label  = ! empty( $case_raw['title'] )  ? esc_html( $case_raw['title'] ) : __( 'View Case', 'lionwood' );
							$case_target = ! empty( $case_raw['target'] ) ? $case_raw['target']             : '_self';

							// Quote — trimmed to 283 chars at last word boundary (etalon length)
							$quote_raw  = get_field( 'quote', $post_id );
							$quote_text = $quote_raw ? strip_tags( $quote_raw ) : '';
							if ( mb_strlen( $quote_text ) > 283 ) {
								$quote_text = mb_substr( $quote_text, 0, 283 );
								$last_space = mb_strrpos( $quote_text, ' ' );
								$quote_text = mb_substr( $quote_text, 0, $last_space ) . '…';
							}
							$quote = esc_html( $quote_text );

							// About & Results
							$about_raw = get_field( 'about_description', $post_id );
							$about     = $about_raw ? wp_kses( $about_raw, [ 'br' => [], 'strong' => [] ] ) : '';
							$results   = get_field( 'results', $post_id ) ?: [];
						?>

						<div class="swiper-slide ts-card-wrap">
							<article class="ts-card">

								<?php /* Card header row */ ?>
								<div class="ts-card__header">

									<?php /* Name + position — first in DOM = left on mobile */ ?>
									<div class="ts-card__reviewer">
										<p class="ts-card__name"><?php echo $reviewer_name; ?></p>
										<?php if ( $reviewer_position ) : ?>
											<p class="ts-card__position"><?php echo $reviewer_position; ?></p>
										<?php endif; ?>
									</div>

									<?php /* Icons — second in DOM = right on mobile */ ?>
									<?php if ( ! empty( $icons ) ) : ?>
										<div class="ts-card__icons">
											<?php foreach ( $icons as $icon_item ) :
												$img = $icon_item['image'] ?? null;
												if ( ! $img ) continue;
											?>
												<div class="ts-card__icon">
													<img
														src="<?php echo esc_url( $img['url'] ); ?>"
														width="60"
														height="60"
														alt="<?php echo esc_attr( $img['alt'] ?: $reviewer_name ); ?>"
														loading="lazy"
													>
												</div>
											<?php endforeach; ?>
										</div>
									<?php endif; ?>

								</div><!-- .ts-card__header -->

								<?php /* Quote + case link wrapper */ ?>
								<div class="ts-card__quote-wrap">
									<?php if ( $quote ) : ?>
										<blockquote class="ts-card__quote">
											<?php echo $quote; ?>
										</blockquote>
									<?php endif; ?>

									<?php /* Case link — inside wrap on mobile, absolute top-right on desktop */ ?>
									<?php if ( $case_url ) : ?>
										<a
											class="ts-card__case-link"
											href="<?php echo $case_url; ?>"
											target="<?php echo esc_attr( $case_target ); ?>"
											<?php echo '_blank' === $case_target ? 'rel="noopener noreferrer"' : ''; ?>
										>
											<?php echo $case_label; ?>
										</a>
									<?php endif; ?>
								</div><!-- .ts-card__quote-wrap -->

								<?php /* About & Results accordion */ ?>
								<div class="ts-card__accordion">
									<button
										class="ts-card__accordion-trigger"
										aria-expanded="false"
										aria-controls="ts-accordion-<?php echo esc_attr( $post_id ); ?>"
									>
										<span class="ts-card__accordion-label">
											<?php esc_html_e( 'About & Results', 'lionwood' ); ?>
										</span>
										<span class="ts-card__accordion-icon" aria-hidden="true">
											<svg xmlns="http://www.w3.org/2000/svg" width="27" height="27" viewBox="0 0 27 27" fill="none">
												<path d="M20.0052 13.3337H13.3385M13.3385 13.3337H6.67188M13.3385 13.3337V6.66699M13.3385 13.3337V20.0003" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
											</svg>
										</span>
									</button>

									<div
										class="ts-card__accordion-body"
										id="ts-accordion-<?php echo esc_attr( $post_id ); ?>"
										hidden
									>
										<?php if ( $about ) : ?>
											<p class="ts-card__about-text">
												<?php echo $about; ?>
											</p>
										<?php endif; ?>

										<?php if ( ! empty( $results ) ) : ?>
											<ul class="ts-card__results">
												<?php foreach ( $results as $result ) :
													$result_text    = ! empty( $result['text'] ) ? wp_kses( $result['text'], $allowed_result_html ) : '';
													$result_icon    = $result['icon'] ?? null;
													if ( ! $result_text ) continue;
												?>
													<li class="ts-card__result-item">
														<span class="ts-card__result-icon" aria-hidden="true">
															<?php if ( $result_icon ) : ?>
																<img
																	src="<?php echo esc_url( $result_icon['url'] ); ?>"
																	width="16"
																	height="16"
																	alt=""
																>
															<?php else : ?>
																<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
																	<path d="M1 11.3418L5.44144 6.90035L8.77047 10.2294L15 3.99985" stroke="#688D4B" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
																	<path d="M14.9988 7.78027V4.00027H11.2188" stroke="#688D4B" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
																</svg>
															<?php endif; ?>
														</span>
														<span class="ts-card__result-text"><?php echo $result_text; ?></span>
													</li>
												<?php endforeach; ?>
											</ul>
										<?php endif; ?>
									</div><!-- .ts-card__accordion-body -->
								</div><!-- .ts-card__accordion -->

							</article>
						</div><!-- .swiper-slide -->

						<?php endforeach; ?>

					</div><!-- .swiper-wrapper -->
				</div><!-- .swiper -->

				<?php /* Navigation — outside slider, bottom right */ ?>
				<div class="ts-section__nav">
					<button class="ts-nav-btn ts-nav-btn--prev" aria-label="<?php esc_attr_e( 'Previous testimonial', 'lionwood' ); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true">
							<path d="M16.6615 9.99903L3.32813 9.99902M8.32812 14.999L3.32813 9.99902L8.32812 4.99902" stroke="black" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</button>
					<button class="ts-nav-btn ts-nav-btn--next" aria-label="<?php esc_attr_e( 'Next testimonial', 'lionwood' ); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true">
							<path d="M3.33854 10.001L16.6719 10.001M11.6719 5.00098L16.6719 10.001L11.6719 15.001" stroke="black" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</button>
				</div>

			</div><!-- .ts-section__slider-wrap -->
		<?php endif; ?>

	</div><!-- .ts-section__container -->

	<?php get_template_part('template-parts/partials/decor-bottom'); ?>

</section>
