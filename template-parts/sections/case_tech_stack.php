<?php
/**
 * Block: Case Tech Stack Section
 *
 * ACF block slug : acf/case-tech-stack
 * bg #111319, heading + description, team table (2 cols), tech grid (no tabs)
 */

defined( 'ABSPATH' ) || exit;

$pt            = absint( get_field( 'padding_top' )        ?: 100 );
$pb            = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob        = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob        = absint( get_field( 'padding_bottom_mob' ) ?: 70 );
$title_top     = esc_html( get_field( 'title_top' )    ?: __( 'Expert Team &', 'theme' ) );
$title_bottom  = esc_html( get_field( 'title_bottom' ) ?: __( 'Tech Stack', 'theme' ) );
$desc_raw      = get_field( 'description' );
$description   = $desc_raw ? wp_kses( $desc_raw, [ 'br' => [] ] ) : '';
$team          = get_field( 'team' ) ?: [];
$technologies  = get_field( 'technologies' ) ?: [];
$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#ffffff';

// Build tech data for JS grid
$techs_data = [];
foreach ( $technologies as $tech ) {
    $icon = $tech['icon'] ?? null;
    $techs_data[] = [
        'name'     => esc_html( $tech['name'] ?? '' ),
        'icon_url' => $icon ? esc_url( $icon['url'] ) : '',
        'icon_alt' => $icon ? esc_attr( $icon['alt'] ?: $tech['name'] ) : '',
    ];
}

// Arrow icon for team table
$arrow_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
    <path d="M6 12.4446L11 8.00011L6 3.55566" stroke="#F7F7F7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>';

// Split team into two equal columns
$team_left  = array_slice( $team, 0, (int) ceil( count( $team ) / 2 ) );
$team_right = array_slice( $team, (int) ceil( count( $team ) / 2 ) );
?>

<section
    class="cts-section"
    style="
        --cts-pt: <?php echo $pt; ?>px;
        --cts-pb: <?php echo $pb; ?>px;
        --cts-pt-mob: <?php echo $pt_mob; ?>px;
        --cts-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="cts-section__container">

        <?php /* ── Row 1: Heading + Description ───────────────────────── */ ?>
        <div class="cts-header">
            <div class="cts-heading">
                <span class="cts-heading__top"><?php echo $title_top; ?></span>
                <span class="cts-heading__bottom"><?php echo $title_bottom; ?></span>
            </div>
            <?php if ( $description ) : ?>
                <p class="cts-description"><?php echo $description; ?></p>
            <?php endif; ?>
        </div>

        <?php /* ── Row 2: Team table — two columns ────────────────────── */ ?>
        <?php if ( ! empty( $team ) ) : ?>
            <div class="cts-team">
                <?php /* Left column */ ?>
                <div class="cts-team__col">
                    <?php foreach ( $team_left as $member ) : ?>
                        <div class="cts-team__row">
                            <span class="cts-team__role">
                                <?php echo $arrow_svg; ?>
                                <?php echo esc_html( $member['role'] ); ?>
                            </span>
                            <?php if ( ! empty( $member['count'] ) ) : ?>
                                <span class="cts-team__count"><?php echo esc_html( $member['count'] ); ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php /* Right column */ ?>
                <?php if ( ! empty( $team_right ) ) : ?>
                    <div class="cts-team__col">
                        <?php foreach ( $team_right as $member ) : ?>
                            <div class="cts-team__row">
                                <span class="cts-team__role">
                                    <?php echo $arrow_svg; ?>
                                    <?php echo esc_html( $member['role'] ); ?>
                                </span>
                                <?php if ( ! empty( $member['count'] ) ) : ?>
                                    <span class="cts-team__count"><?php echo esc_html( $member['count'] ); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php /* ── Row 3: Technology grid (no tabs, JS-driven) ─────────── */ ?>
        <?php if ( ! empty( $techs_data ) ) : ?>
            <div
                class="cts-grid"
                data-technologies="<?php echo esc_attr( wp_json_encode( $techs_data ) ); ?>"
                aria-live="polite"
            ></div>
        <?php endif; ?>

    </div><!-- .cts-section__container -->

    <?php if ( $decor_enabled ) : ?>
        <?php get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] ); ?>
    <?php endif; ?>

</section>
