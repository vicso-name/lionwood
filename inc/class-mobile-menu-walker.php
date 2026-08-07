<?php
/**
 * Lionwood Mobile Menu Walker
 * Renders the primary nav as a nested accordion list for the mobile drawer.
 *
 * depth 0 + children   → <li> button toggle + .mob__sub accordion panel
 * depth 0, no children → <li> direct link
 * depth 1 + children   → sub-section: title link + chevron toggle + collapsible children
 * depth 1, no children → flat link
 * depth 2              → child link with icon placeholder
 */

defined( 'ABSPATH' ) || exit;

class Lionwood_Mobile_Menu_Walker extends Walker_Nav_Menu {

    // stroke="currentColor" so the chevron inherits the parent button's text color
    private $svg_chevron = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 12 12" fill="none" aria-hidden="true"><path d="M2.25 4.5L6 8.25L9.75 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';

    // ── start_lvl ─────────────────────────────────────────────────────────────

    public function start_lvl( &$output, $depth = 0, $args = null ) {
        if ( $depth === 0 ) {
            $output .= '<div class="mob__sub">';
        } elseif ( $depth === 1 ) {
            $output .= '<ul class="mob__sub-children">';
        }
    }

    // ── end_lvl ───────────────────────────────────────────────────────────────

    public function end_lvl( &$output, $depth = 0, $args = null ) {
        if ( $depth === 0 ) {
            $output .= '</div>'; // .mob__sub
        } elseif ( $depth === 1 ) {
            $output .= '</ul>';
            $output .= '</div>'; // .mob__sub-item (opened in start_el depth=1)
        }
    }

    // ── start_el ─────────────────────────────────────────────────────────────

    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $has_children = in_array( 'menu-item-has-children', $item->classes );
        $url          = esc_url( $item->url );
        $title        = esc_html( $item->title );
        $attr_target  = ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
        $attr_rel     = $item->xfn ? ' rel="' . esc_attr( $item->xfn ) . '"' : '';
        $is_active    = in_array( 'current-menu-item', $item->classes )
                     || in_array( 'current-menu-ancestor', $item->classes );

        // ── Depth 0 ───────────────────────────────────────────────────────────
        if ( $depth === 0 ) {
            $li_class = 'mob__item';
            if ( $has_children ) $li_class .= ' mob__item--has-sub';
            if ( $is_active )    $li_class .= ' is-active';

            $output .= '<li class="' . $li_class . '">';

            if ( $has_children ) {
                $output .= '<button class="mob__toggle" aria-expanded="false" data-mob-toggle>';
                $output .= $title;
                $output .= $this->svg_chevron;
                $output .= '</button>';
            } else {
                $output .= '<a class="mob__link" href="' . $url . '"' . $attr_target . $attr_rel . '>';
                $output .= $title;
                $output .= '</a>';
            }
            return;
        }

        // ── Depth 1 ───────────────────────────────────────────────────────────
        if ( $depth === 1 ) {
            if ( $has_children ) {
                // Sub-section: link title on left, chevron toggle on right
                $output .= '<div class="mob__sub-item">';
                $output .= '<div class="mob__sub-item-hdr">';
                $output .= '<a class="mob__sub-group-link" href="' . $url . '"' . $attr_target . $attr_rel . '>' . $title . '</a>';
                $output .= '<button class="mob__sub-toggle" aria-expanded="false" aria-label="' . esc_attr( sprintf( __( 'Toggle %s submenu', 'lionwood' ), $title ) ) . '" data-mob-sub-toggle>';
                $output .= $this->svg_chevron;
                $output .= '</button>';
                $output .= '</div>';
                // .mob__sub-children opened in start_lvl(depth=1)
            } else {
                // Flat link — no arrow icon in mobile
                $output .= '<a class="mob__sub-link" href="' . $url . '"' . $attr_target . $attr_rel . '>';
                $output .= $title;
                $output .= '</a>';
            }
            return;
        }

        // ── Depth 2 ───────────────────────────────────────────────────────────
        if ( $depth === 2 ) {
            $output .= '<li class="mob__sub-child">';
            $output .= '<a class="mob__sub-child-link" href="' . $url . '"' . $attr_target . $attr_rel . '>';
            $output .= '<span class="mob__sub-child-icon" aria-hidden="true"></span>';
            $output .= '<span class="mob__sub-child-text">' . $title . '</span>';
            $output .= '</a>';
        }
    }

    // ── end_el ────────────────────────────────────────────────────────────────

    public function end_el( &$output, $item, $depth = 0, $args = null ) {
        if ( $depth === 0 ) {
            $output .= '</li>';
        } elseif ( $depth === 2 ) {
            $output .= '</li>';
        }
        // depth 1: items with children are closed in end_lvl
        //          flat links are self-contained <a> tags with no wrapper
    }
}
