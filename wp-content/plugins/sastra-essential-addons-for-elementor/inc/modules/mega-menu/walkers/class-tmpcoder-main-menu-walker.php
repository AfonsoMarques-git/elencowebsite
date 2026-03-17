<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class TMPCODER_Main_Menu_Walker extends \Walker_Nav_Menu {
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		if ( 'mega-content' === $this->get_menu_item_type() ) {
			return;
		}

		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );

		// Default class.
		$classes = array( 'sub-menu', 'tmpcoder-sub-menu' );

		$class_names = join( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$output .= "{$n}{$indent}<ul $class_names>{$n}";
	}

	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		if ( 'mega-content' === $this->get_menu_item_type() ) {
			return;
		}


		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );
		$output .= "$indent</ul>{$n}";
	}

	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$this->set_menu_item_type( $item->ID, $depth );
		
		if ( 'mega-content' === $this->get_menu_item_type() && 0 < $depth ) {
			return;
		}

        // Get Settings
        $settings = $this->get_mega_menu_settings($item->ID);

		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}

		$indent   = ( $depth ) ? str_repeat( $t, $depth ) : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		if ( $this->has_mega_menu( $item->ID ) ) {
			$classes[] = 'menu-item-has-children';
		}

		$id_attr = '';
		$custom_width_attr = '';

		if ( $settings ) {
			if ( 'true' === $settings['tmpcoder_mm_enable'] ) {
				$classes[] = 'tmpcoder-mega-menu-'. $settings['tmpcoder_mm_enable'];
				$classes[] = 'tmpcoder-mega-menu-pos-'. $settings['tmpcoder_mm_position'];
				$classes[] = 'tmpcoder-mega-menu-width-'. $settings['tmpcoder_mm_width'];
				$id_attr = ' data-id="'. $item->ID .'"';
				$custom_width_attr = 'custom' === $settings['tmpcoder_mm_width'] ? ' data-custom-width="'. $settings['tmpcoder_mm_custom_width'] .'"' : '';
			}
		}

		$item_li_class = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$item_li_class = $item_li_class ? ' class="' . esc_attr( $item_li_class ) . '"' : '';

        $item_a_class = 'tmpcoder-menu-item tmpcoder-pointer-item';

		if ( in_array( 'current-menu-item', $item->classes ) ) {
			$item_a_class .= ' tmpcoder-active-menu-item';
		}
		
		$output .= $indent .'<li'. $id . $item_li_class . $custom_width_attr . $id_attr .'>';

			$item_output = $args->before;
				$submenu_icon = '';
				// Parent Item
				if ( in_array( 'menu-item-has-children', $item->classes ) || $this->has_mega_menu( $item->ID ) ) {
					if ( in_array( 'current-menu-item', $item->classes ) || in_array( 'current-menu-ancestor', $item->classes ) ) {
						$item_a_class .= ' tmpcoder-active-menu-item';
					}

					// Add Sub Menu Icon
					if ( $depth > 0 ) {
						$submenu_icon .='<i class="tmpcoder-sub-icon fas tmpcoder-sub-icon-rotate" aria-hidden="true"></i>';
					} else {
						$submenu_icon .='<i class="tmpcoder-sub-icon fas" aria-hidden="true"></i>';
					}
				}

				// Sub Menu Classes
				if ( $depth > 0 ) {
					$item_a_class = 'tmpcoder-sub-menu-item';

					if ( in_array( 'current-menu-item', $item->classes ) || in_array( 'current-menu-ancestor', $item->classes ) ) {
						$item_a_class .= ' tmpcoder-active-menu-item';
					}
				}

                $item_icon_html = '';
                $item_badge_html = '';

                // If has Settings
                if ( $settings ) {
                    // Icon
                    if ( tmpcoder_is_availble() && (!empty($settings['tmpcoder_mm_icon_picker']) && '' !== $settings['tmpcoder_mm_icon_picker']) ) {
                        $color = (!empty($settings['tmpcoder_mm_icon_color']) && '' !== $settings['tmpcoder_mm_icon_color']) ? ' color: '. $settings['tmpcoder_mm_icon_color'] : ';';
                        $font_size = (!empty($settings['tmpcoder_mm_icon_size']) && '' !== $settings['tmpcoder_mm_icon_size']) ? 'font-size: '. $settings['tmpcoder_mm_icon_size'] .'px;' : '';
                        $item_icon_style = ( '' !== $font_size || '' !== $color || '' !== $distance ) ? ' style="'. $font_size . $color .'"' : '';
                        $item_icon_html = '<i class="tmpcoder-mega-menu-icon '. $settings['tmpcoder_mm_icon_picker'] .'"'. $item_icon_style .'></i>';
                    }

                    // Badge
                    if ( tmpcoder_is_availble() && (!empty($settings['tmpcoder_mm_badge_text']) && '' !== $settings['tmpcoder_mm_badge_text']) ) {
						$item_badge_class = 'true' === $settings['tmpcoder_mm_badge_animation'] ? ' tmpcoder-mega-menu-badge-animation' : '';
                        $item_badge_style = 'color: '. $settings['tmpcoder_mm_badge_color'] .';';
                        $item_badge_style .= 'background-color: '. $settings['tmpcoder_mm_badge_bg_color'] .';';
                        $item_badge_style .= 'border-color: '. $settings['tmpcoder_mm_badge_bg_color'] .';';
                        $item_badge_html = '<span class="tmpcoder-mega-menu-badge'. $item_badge_class .'" style="'. $item_badge_style .'">'. $settings['tmpcoder_mm_badge_text'] .'</span>';
                    }
                }

                // Link Attributes
                $atts = [];
                $atts['title'] = ! empty( $item->attr_title ) ? $item->attr_title : '';
                $atts['target'] = ! empty( $item->target ) ? $item->target : '';
                $atts['rel'] = ! empty( $item->xfn ) ? $item->xfn : '';
                $atts['href'] = ! empty( $item->url ) ? $item->url : '#';
                $atts['class'] = $item_a_class;
        
                $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
        
                $attributes = '';
        
                foreach ( $atts as $attr => $value ) {
                    if ( ! empty( $value ) ) {
                        $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                        $attributes .= ' ' . $attr . '="' . $value . '"';
                    }
                }
                
				// Link Output
				$item_output .= '<a'. $attributes .'>';
                    $item_output .= $item_icon_html;
					$item_output .= $args->link_before;
					$item_output .= '<span>'. apply_filters( 'the_title', $item->title, $item->ID ) .'</span>';
					$item_output .= $args->link_after;
                    $item_output .= $item_badge_html;
					$item_output .= $submenu_icon;
				$item_output .= '</a>';
			$item_output .= $args->after;
		
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
		if ( 'mega-content' === $this->get_menu_item_type() && 0 < $depth ) {
			return;
		}
		
		if ( $depth === 0 ) {
			if ( $this->has_mega_menu($item->ID) ) {
				$elementor = \Elementor\Plugin::instance();
				$mega_id = get_post_meta( $item->ID, 'tmpcoder-mega-menu-item', true);
				$content = $elementor->frontend->get_builder_content_for_display($mega_id);
				$output .= '<div class="tmpcoder-sub-mega-menu">'. $content .'</div>';
			}

			$output .= "</li>\n";
		}
	}

	public function set_menu_item_type( $item_id = 0, $depth = 0 ) {
		if ( 0 < $depth ) {
			return;
		}

		$item_type = 'default-content';

		if ( $this->has_mega_menu( $item_id ) ) {
			$item_type = 'mega-content';
		}

		wp_cache_set( 'menu-item-type', $item_type, 'tmpcoder-mega-menu' );
	}

	public function get_menu_item_type() {
		return wp_cache_get( 'menu-item-type', 'tmpcoder-mega-menu' );
	}

	public function has_mega_menu( $item_id ) {
		$settings = get_post_meta( $item_id, 'tmpcoder-mega-menu-settings', true);

		return isset($settings['tmpcoder_mm_enable']) && 'true' === $settings['tmpcoder_mm_enable'] ? true : false;
	}

	public function get_mega_menu_settings( $item_id ) {
		$settings = get_post_meta( $item_id, 'tmpcoder-mega-menu-settings', true);

		return ! empty($settings) ? $settings : false;
	}
}