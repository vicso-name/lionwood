<?php
/**
 * Partial: Case Card
 *
 * File: template-parts/partials/case-card.php
 * Used by choose-cases-grid block and AJAX handler.
 */

defined( 'ABSPATH' ) || exit;

$case_id = $args['case_id'] ?? 0;
if ( ! $case_id ) return;

$title      = esc_html( get_the_title( $case_id ) );
$permalink  = esc_url( get_permalink( $case_id ) );
$thumb_id   = get_post_thumbnail_id( $case_id );
$thumb_url  = $thumb_id ? esc_url( wp_get_attachment_image_url( $thumb_id, 'large' ) ) : '';

// ACF meta fields
$date_from  = esc_html( get_field( 'date_from', $case_id ) ?: '' );
$date_to    = esc_html( get_field( 'date_to',   $case_id ) ?: '' );
$country    = esc_html( get_field( 'country',   $case_id ) ?: '' );

// Date string: "2025 - 2026" or "December 2020 – ongoing"
$date_str = '';
if ( $date_from && $date_to ) {
    $date_str = $date_from . ' – ' . $date_to;
} elseif ( $date_from ) {
    $date_str = $date_from;
}

// Industry taxonomy tags (case_study_category)
$industries = get_the_terms( $case_id, 'case_study_category' );
$industries = ( $industries && ! is_wp_error( $industries ) ) ? $industries : [];

// Service taxonomy (case_study_service) — comma-joined
$services = get_the_terms( $case_id, 'case_study_service' );
$services = ( $services && ! is_wp_error( $services ) ) ? $services : [];
$services_str = implode( ', ', array_map( fn( $t ) => esc_html( $t->name ), $services ) );
?>

<article class="ccg-card">
    <a class="ccg-card__link" href="<?php echo esc_url( $permalink ); ?>" aria-label="<?php echo esc_attr( $title ); ?>">

        <?php /* ── Image area ─────────────────────────────────────────── */ ?>
        <div class="ccg-card__image">
            <?php if ( $thumb_url ) : ?>
                <div class="ccg-card__image-bg" style="background-image: url('<?php echo esc_url( $thumb_url ); ?>');"></div>
            <?php else : ?>
                <div class="ccg-card__image-bg"></div>
            <?php endif; ?>
            <?php /* Industry tags overlaid on image */ ?>
            <?php if ( ! empty( $industries ) ) : ?>
                <div class="ccg-card__tags">
                    <?php foreach ( $industries as $industry ) : ?>
                        <span class="ccg-card__tag">#<?php echo esc_html( $industry->name ); ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php /* ── Meta: date + country ────────────────────────────────── */ ?>
        <?php if ( $date_str || $country ) : ?>
            <div class="ccg-card__meta">
                <?php if ( $date_str ) : ?>
                    <span class="ccg-card__meta-date"><?php echo esc_html( $date_str ); ?></span>
                <?php endif; ?>
                <?php if ( $date_str && $country ) : ?>
                    <span class="ccg-card__meta-sep" aria-hidden="true"></span>
                <?php endif; ?>
                <?php if ( $country ) : ?>
                    <span class="ccg-card__meta-country"><?php echo esc_html( $country ); ?></span>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php /* ── Title ────────────────────────────────────────────────── */ ?>
        <h3 class="ccg-card__title"><?php echo esc_html( $title ); ?></h3>

        <?php /* ── Services ─────────────────────────────────────────────── */ ?>
        <?php if ( $services_str ) : ?>
            <p class="ccg-card__services"><?php echo esc_html( $services_str ); ?></p>
        <?php endif; ?>

    </a>
</article>
