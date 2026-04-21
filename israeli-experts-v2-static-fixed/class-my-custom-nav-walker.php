<?php
/**
 * Custom Nav Walker to match the theme's specific HTML structure
 * This class builds your custom menu HTML.
 */
class My_Custom_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * Starts the list before the elements are added.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '<div class="dropdown-content"><ul class="m-t-5 dropdowncon">';
	}

	/**
	 * Ends the list of after the elements are added.
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '</ul></div>';
	}

	/**
	 * Starts the element output.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$li_classes   = '';
		$item_classes = empty( $item->classes ) ? array() : (array) $item->classes;

		$is_dropdown = in_array( 'menu-item-has-children', $item_classes );

		if ( $is_dropdown ) {
			$li_classes = 'dropdown dropdown-toggle navv';
		} else {
			$li_classes = implode( ' ', $item_classes );
		}

		$output .= '<li class="' . esc_attr( $li_classes ) . '">';

		if ( $is_dropdown ) {
			$output .= ' '; // This is a non-breaking space
		}

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
		$atts['href']   = ! empty( $item->url ) ? $item->url : '';

		if ( $atts['href'] === '#nowhere' ) {
			$output .= '<a name="nowhere" style="cursor:default"></a>';
		
		} else {
			if ( $is_dropdown && $depth === 0 ) {
				$atts['href'] = '#';
			}
			
			$atts_str = '';
			foreach ( $atts as $k => $v ) {
				if ( ! empty( $v ) ) {
					$atts_str .= $k . '="' . esc_attr( $v ) . '" ';
				}
			}
			
			$output .= '<a ' . $atts_str . '>';

			$link_before = '';
			$link_after = '';

			if ( is_object( $args ) ) {
				$link_before = $args->link_before;
				$link_after  = $args->link_after;
			} elseif ( is_array( $args ) ) {
				$link_before = isset( $args['link_before'] ) ? $args['link_before'] : '';
				$link_after  = isset( $args['link_after'] ) ? $args['link_after'] : '';
			}

			$output .= $link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $link_after;
			$output .= '</a>';
		}
	}
} // End Class