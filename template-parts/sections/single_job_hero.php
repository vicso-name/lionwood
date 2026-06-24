<?php
/**
 * Block: Single Job Hero
 * Slug: acf/single-job-hero
 *
 * Dark rounded-top section for single career CPT.
 * Title auto-pulled from post_title, description + recruiter card from ACF.
 */

defined( 'ABSPATH' ) || exit;

$pt     = absint( get_field( 'padding_top' )        ?: 100 );
$pb     = absint( get_field( 'padding_bottom' )     ?: 200 );
$pt_mob = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob = absint( get_field( 'padding_bottom_mob' ) ?: 140 );

// Title — "POSITION:" top line, post title bottom line
$line1 = get_field( 'title_line1' ) ?: 'Position:';
$line2 = get_post()->post_title ?? get_the_title();

// Description
$description = get_field( 'description' );
$desc_out    = $description ? wp_kses( $description, [ 'br' => [] ] ) : '';

// Recruiter card
$recruiter_photo  = get_field( 'recruiter_photo' );
$recruiter_name   = get_field( 'recruiter_name' )  ?: '';
$recruiter_label  = get_field( 'recruiter_label' ) ?: 'Recruiter';
$apply_link       = get_field( 'apply_link' );
$apply_url        = ! empty( $apply_link['url'] )    ? esc_url( $apply_link['url'] )    : '';
$apply_text       = ! empty( $apply_link['title'] )  ? esc_html( $apply_link['title'] ) : __( 'Apply Now', 'lionwood' );
$apply_target     = ! empty( $apply_link['target'] ) ? $apply_link['target']             : '_self';

$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#C83030';
?>

<section
    class="sjh-section"
    style="
        --sjh-pt: <?php echo $pt; ?>px;
        --sjh-pb: <?php echo $pb; ?>px;
        --sjh-pt-mob: <?php echo $pt_mob; ?>px;
        --sjh-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="sjh-section__container">

        <?php /* ── Title block ── */ ?>
        <h1 class="sjh-heading">
            <span class="sjh-heading__line1"><?php echo esc_html( $line1 ); ?></span>
            <span class="sjh-heading__line2"><?php echo esc_html( $line2 ); ?></span>
        </h1>

        <?php /* ── Content row: description + recruiter card ── */ ?>
        <div class="sjh-row">

            <?php /* Description */ ?>
            <?php if ( $desc_out ) : ?>
                <p class="sjh-description"><?php echo $desc_out; ?></p>
            <?php endif; ?>

            <?php /* Recruiter card */ ?>
            <div class="sjh-card">
                <div class="sjh-card__top">
                    <?php if ( $recruiter_photo ) : ?>
                        <div
                            class="sjh-card__photo"
                            style="background-image: url('<?php echo esc_url( $recruiter_photo['url'] ); ?>');"
                            role="img"
                            aria-label="<?php echo esc_attr( $recruiter_photo['alt'] ?: $recruiter_name ); ?>"
                        ></div>
                    <?php endif; ?>
                    <div class="sjh-card__info">
                        <span class="sjh-card__label"><?php echo esc_html( $recruiter_label ); ?></span>
                        <?php if ( $recruiter_name ) : ?>
                            <p class="sjh-card__name"><?php echo esc_html( $recruiter_name ); ?></p>
                        <?php endif; ?>
                        <?php if ( $apply_url ) : ?>
                            <a class="sjh-card__btn sjh-card__btn--desktop"
                               href="<?php echo $apply_url; ?>"
                               target="<?php echo esc_attr( $apply_target ); ?>"
                               <?php echo $apply_target === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>
                            ><?php echo $apply_text; ?></a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if ( $apply_url ) : ?>
                    <a class="sjh-card__btn sjh-card__btn--mobile"
                       href="<?php echo $apply_url; ?>"
                       target="<?php echo esc_attr( $apply_target ); ?>"
                       <?php echo $apply_target === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>
                    ><?php echo $apply_text; ?></a>
                <?php endif; ?>
            </div>

        </div>

    </div>

    <?php if ( $decor_enabled ) :
        get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] );
    endif; ?>
</section>
