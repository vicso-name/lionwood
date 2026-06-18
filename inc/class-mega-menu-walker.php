<?php
/**
 * Lionwood Mega Menu Walker
 *
 * Layout variants (detected from depth-1 item counts inside each dropdown):
 *
 *   one-group          1 group (has depth-2 children) + any flat items
 *                      → group LEFT with bg-panel, flat items RIGHT in 2-col grid
 *
 *   two-groups-side    2 groups + fewer than 3 flat items
 *                      → 2 groups LEFT each with bg-panel, flat items RIGHT in 1-col
 *
 *   two-groups-stacked 2 groups + 3 or more flat items
 *                      → groups TOP in 2-col (no bg), flat pills BOTTOM
 *
 *   flat-only          0 groups, only flat depth-1 items
 *                      → flat pills in a row (no groups zone rendered)
 */

defined( 'ABSPATH' ) || exit;

class Lionwood_Mega_Menu_Walker extends Walker_Nav_Menu {

    private $svg_chevron_down = '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true"><path d="M2.25 6.5L6 10.25L9.75 6.5" stroke="#848588" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';

    private $svg_arrow = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M3.70123 10.2991L10.3009 3.69948M5.35115 3.69948L10.3009 3.69948L10.3009 8.64923" stroke="#848588" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>';

    // Pre-computed per top-level parent ID: ['groups' => int, 'flats' => int, 'layout' => string]
    private $layout_map = [];

    // State for the currently-open dropdown
    private $current_parent_id = 0;
    private $current_layout    = 'default';
    private $has_groups        = false;
    private $flat_links        = [];

    // ── Pre-scan: run once before the main walk ────────────────────────────────

    public function walk( $elements, $max_depth, ...$args ) {
        // Build children-of map: parent_id → [child_id, ...]
        $children_of = [];
        foreach ( $elements as $el ) {
            $pid = (int) $el->menu_item_parent;
            $children_of[ $pid ][] = $el->ID;
        }

        // For each top-level item that triggers a dropdown, count groups vs flats
        foreach ( $elements as $el ) {
            if ( (int) $el->menu_item_parent !== 0 ) continue;
            if ( ! isset( $children_of[ $el->ID ] ) ) continue;

            $groups = 0;
            $flats  = 0;
            foreach ( $children_of[ $el->ID ] as $child_id ) {
                if ( isset( $children_of[ $child_id ] ) ) {
                    $groups++;
                } else {
                    $flats++;
                }
            }

            if ( $groups === 0 ) {
                $layout = 'flat-only';
            } elseif ( $groups === 1 ) {
                $layout = 'one-group';
            } elseif ( $flats >= 3 ) {
                $layout = 'two-groups-stacked';
            } else {
                $layout = 'two-groups-side';
            }

            $this->layout_map[ $el->ID ] = [
                'groups' => $groups,
                'flats'  => $flats,
                'layout' => $layout,
            ];
        }

        return parent::walk( $elements, $max_depth, ...$args );
    }

    // ── start_lvl ─────────────────────────────────────────────────────────────

    public function start_lvl( &$output, $depth = 0, $args = null ) {
        if ( $depth === 0 ) {
            $data = $this->layout_map[ $this->current_parent_id ] ?? [ 'groups' => 0, 'layout' => 'flat-only' ];
            $this->current_layout = $data['layout'];
            $this->has_groups     = $data['groups'] > 0;
            $this->flat_links     = [];

            $output .= '<div class="hdr__drop" role="region" aria-hidden="true">';
            $output .= '<div class="hdr__drop-inner" data-mega-layout="' . esc_attr( $this->current_layout ) . '">';

            if ( $this->has_groups ) {
                $output .= '<div class="hdr__drop-groups">';
            }
        } elseif ( $depth === 1 ) {
            $output .= '<ul class="hdr__drop-children">';
        }
    }

    // ── end_lvl ───────────────────────────────────────────────────────────────

    public function end_lvl( &$output, $depth = 0, $args = null ) {
        if ( $depth === 0 ) {
            if ( $this->has_groups ) {
                $output .= '</div>'; // .hdr__drop-groups
            }

            if ( ! empty( $this->flat_links ) ) {
                $count   = count( $this->flat_links );
                $output .= '<div class="hdr__drop-flat" data-flat-count="' . $count . '">';
                foreach ( $this->flat_links as $link ) {
                    $output .= $link;
                }
                $output .= '</div>'; // .hdr__drop-flat
            }

            $output .= '</div>'; // .hdr__drop-inner
            $output .= '</div>'; // .hdr__drop

            // Reset state for next dropdown
            $this->current_layout    = 'default';
            $this->has_groups        = false;
            $this->flat_links        = [];
            $this->current_parent_id = 0;

        } elseif ( $depth === 1 ) {
            $output .= '</ul>';
            $output .= '</div>'; // .hdr__drop-group
        }
    }

    // ── start_el ─────────────────────────────────────────────────────────────

    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $has_children = in_array( 'menu-item-has-children', $item->classes );
        $url          = esc_url( $item->url );
        $title        = esc_html( $item->title );
        $attr_target  = ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
        $attr_rel     = $item->xfn ? ' rel="' . esc_attr( $item->xfn ) . '"' : '';

        // ── Depth 0: top-level nav items ─────────────────────────────────────
        if ( $depth === 0 ) {
            if ( $has_children ) {
                $this->current_parent_id = $item->ID;
            }

            $classes = [ 'hdr__menu-item' ];
            if ( $has_children ) $classes[] = 'hdr__menu-item--has-drop';
            if ( in_array( 'current-menu-item', $item->classes ) ) $classes[] = 'is-active';

            $output .= '<li class="' . implode( ' ', $classes ) . '" role="menuitem">';

            if ( $has_children ) {
                $output .= '<button class="hdr__menu-link hdr__menu-link--trigger" aria-expanded="false" aria-haspopup="true" data-hdr-trigger>';
                $output .= $title;
                $output .= $this->svg_chevron_down;
                $output .= '</button>';
            } else {
                $output .= '<a class="hdr__menu-link" href="' . $url . '"' . $attr_target . $attr_rel . '>';
                $output .= $title;
                $output .= '</a>';
            }
            return;
        }

        // ── Depth 1: items inside the mega dropdown ───────────────────────────
        if ( $depth === 1 ) {
            if ( $has_children ) {
                // Group column: gets bg-panel in one-group and two-groups-side layouts
                $group_classes = [ 'hdr__drop-group' ];
                if ( in_array( $this->current_layout, [ 'one-group', 'two-groups-side' ] ) ) {
                    $group_classes[] = 'hdr__drop-group--bg';
                }
                $output .= '<div class="' . implode( ' ', $group_classes ) . '">';
                $output .= '<a class="hdr__drop-parent" href="' . $url . '"' . $attr_target . $attr_rel . '>';
                $output .= $title;
                $output .= '</a>';
                // Child list opened in start_lvl(depth=1)
            } else {
                // Flat pill: buffer for rendering after the groups zone
                $pill  = '<a class="hdr__drop-pill" href="' . $url . '"' . $attr_target . $attr_rel . '>';
                $pill .= '<span>' . $title . '</span>';
                $pill .= $this->svg_arrow;
                $pill .= '</a>';
                $this->flat_links[] = $pill;
            }
            return;
        }

        // ── Depth 2: grandchildren (links inside a group column) ──────────────
        if ( $depth === 2 ) {
            $output .= '<li class="hdr__drop-child">';
            $output .= '<a class="hdr__drop-child-link" href="' . $url . '"' . $attr_target . $attr_rel . '>';
            $output .= '<span class="hdr__drop-child-icon" aria-hidden="true"></span>';
            $output .= '<span class="hdr__drop-child-text">' . $title . '</span>';
            $output .= $this->svg_arrow;
            $output .= '</a>';
        }
    }

    // ── end_el ────────────────────────────────────────────────────────────────

    public function end_el( &$output, $item, $depth = 0, $args = null ) {
        if ( $depth === 0 || $depth === 2 ) {
            $output .= '</li>';
        }
        // depth 1: group divs are closed by end_lvl; flat pills have no element wrapper
    }
}
