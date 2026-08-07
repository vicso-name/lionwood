<?php
/**
 * Block: About Team Section
 * Slug: acf/about-team
 *
 * Light bg (#E9E9E9), two-line H1 heading, team member cards grid.
 */

defined( 'ABSPATH' ) || exit;

$pt     = absint( get_field( 'padding_top' )        ?: 100 );
$pb     = absint( get_field( 'padding_bottom' )     ?: 100 );
$pt_mob = absint( get_field( 'padding_top_mob' )    ?: 70 );
$pb_mob = absint( get_field( 'padding_bottom_mob' ) ?: 70 );

$title_line1   = get_field( 'title_line1' ) ?: 'The People';
$title_line2   = get_field( 'title_line2' ) ?: 'Behind Your Product';
$members       = get_field( 'members' );

$decor_enabled = get_field( 'decor_bottom_enabled' );
$decor_color   = get_field( 'decor_bottom_color' ) ?: '#F7F7F7';

$linkedin_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none" aria-hidden="true">
  <path d="M6.42245 4.63012C6.42221 5.12126 6.22687 5.59219 5.8794 5.93931C5.53194 6.28642 5.06082 6.48129 4.56968 6.48105C4.07853 6.4808 3.60761 6.28546 3.26049 5.938C2.91337 5.59053 2.7185 5.11941 2.71875 4.62827C2.719 4.13713 2.91434 3.6662 3.2618 3.31908C3.60926 2.97197 4.08039 2.7771 4.57153 2.77734C5.06267 2.77759 5.5336 2.97293 5.88071 3.32039C6.22783 3.66786 6.4227 4.13898 6.42245 4.63012ZM6.47801 7.85234H2.77431V19.4449H6.47801V7.85234ZM12.3299 7.85234H8.64468V19.4449H12.2928V13.3616C12.2928 9.97271 16.7095 9.6579 16.7095 13.3616V19.4449H20.3669V12.1023C20.3669 6.38938 13.8299 6.60234 12.2928 9.4079L12.3299 7.85234Z" fill="#F7F7F7"/>
</svg>';
?>

<section
    class="atm-section"
    style="
        --atm-pt: <?php echo $pt; ?>px;
        --atm-pb: <?php echo $pb; ?>px;
        --atm-pt-mob: <?php echo $pt_mob; ?>px;
        --atm-pb-mob: <?php echo $pb_mob; ?>px;
    "
>
    <div class="atm-section__container">

        <?php /* ── Heading ── */ ?>
        <div class="atm-heading">
            <span class="atm-heading__line1"><?php echo esc_html( $title_line1 ); ?></span>
            <span class="atm-heading__line2"><?php echo esc_html( $title_line2 ); ?></span>
        </div>

        <?php /* ── Cards grid ── */ ?>
        <?php if ( $members ) : ?>
            <div class="atm-grid">
                <?php foreach ( $members as $member ) :
                    $photo      = $member['photo'];
                    $name       = $member['name'];
                    $position   = $member['position'];
                    $quote      = mb_substr( $member['quote'] ?? '', 0, 450 );
                    $linkedin   = $member['linkedin'];
                ?>
                <div class="atm-card">

                    <?php /* Photo */ ?>
                    <?php if ( $photo ) : ?>
                        <div
                            class="atm-card__photo"
                            style="background-image: url('<?php echo esc_url( $photo['url'] ); ?>');"
                            role="img"
                            aria-label="<?php echo esc_attr( $photo['alt'] ?: $name ); ?>"
                        ></div>
                    <?php endif; ?>

                    <?php /* Name row */ ?>
                    <div class="atm-card__body">

                        <div class="atm-card__name-row">
                            <h3 class="atm-card__name"><?php echo wp_kses_post( $name ); ?></h3>
                            <?php if ( $linkedin && $linkedin['url'] ) : ?>
                                <a
                                    class="atm-card__linkedin"
                                    href="<?php echo esc_url( $linkedin['url'] ); ?>"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    aria-label="<?php echo esc_attr( $name ); ?> on LinkedIn"
                                >
                                    <?php echo $linkedin_icon; ?>
                                </a>
                            <?php endif; ?>
                        </div>

                        <?php if ( $position ) : ?>
                            <p class="atm-card__position"><?php echo esc_html( $position ); ?></p>
                        <?php endif; ?>

                        <?php if ( $quote ) : ?>
                            <p class="atm-card__quote"><?php echo esc_html( $quote ); ?></p>
                        <?php endif; ?>

                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>

    <?php if ( $decor_enabled ) :
        get_template_part( 'template-parts/partials/decor-bottom', null, [ 'color' => $decor_color ] );
    endif; ?>
</section>
