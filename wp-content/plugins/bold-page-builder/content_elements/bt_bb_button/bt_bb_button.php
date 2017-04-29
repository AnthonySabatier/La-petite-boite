<?php

class bt_bb_button extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts', array(
			'text'			=> '',
			'icon'			=> '',
			'icon_position'	=> '',
			'url'			=> '',
			'target'		=> '',
			'color_scheme'  => '',
			'style'			=> '',
			'size'			=> '',
			'width'			=> '',
			'shape'			=> '',
			'align'			=> ''
		) ), $atts, $this->shortcode ) );
		
		$class = array( $this->shortcode );
		
		if ( $el_class != '' ) {
			$class[] = $el_class;
		}	
		
		$id_attr = '';
		if ( $el_id != '' ) {
			$id_attr = ' ' . 'id="' . $el_id . '"';
		}

		$style_attr = '';
		if ( $el_style != '' ) {
			$style_attr = ' ' . 'style="' . $el_style . '"';
		}
		
		if ( $icon_position != '' ) {
			$class[] = $this->prefix . 'icon_position' . '_' . $icon_position;
		}
		
		if ( $color_scheme != '' ) {
			$class[] = $this->prefix . 'color_scheme_' . bt_bb_get_color_scheme_id( $color_scheme );
		}
		
		if ( $style != '' ) {
			$class[] = $this->prefix . 'style' . '_' . $style;
		}
		
		if ( $size != '' ) {
			$class[] = $this->prefix . 'size' . '_' . $size;
		}
		
		if ( $width != '' ) {
			$class[] = $this->prefix . 'width' . '_' . $width;
		}
		
		if ( $shape != '' ) {
			$class[] = $this->prefix . 'shape' . '_' . $shape;
		}
		
		if ( $align != '' ) {
			$class[] = $this->prefix . 'align' . '_' . $align;
		}
		
		$output = $this->get_html( $icon, $text, $url, $target );
		
		$output = '<div' . $id_attr . ' class="' . implode( ' ', $class ) . '"' . $style_attr . '>' . $output . '</div>';
		
		return $output;

	}
	
	function get_html( $icon, $text, $url, $target ) {
		
		require_once( dirname(__FILE__) . '/../../content_elements_misc/misc.php' );

		if ( $url == '' ) {
			$url = '#';
		}

		if ( $text != '' ) {
			$text = '<span class="bt_bb_button_text">' . $text . '</span>';
		}

		$link = '';

		if ( $url != '' && $url != '#' && substr( $url, 0, 4 ) != 'http' && substr( $url, 0, 5 ) != 'https' && substr( $url, 0, 6 ) != 'mailto' ) {
			$link = bt_bb_get_permalink_by_slug( $url );
		} else {
			$link = $url;
		}

		$output = '<a href="' . $link . '" target="' . $target . '" class="' . $this->prefix . 'link">';
			if ( $icon == '' || $icon == 'no_icon' ) {
				$output .= $text;
			} else {
				$output .= $text . bt_bb_icon::get_html( $icon, '', '', '' );
			}
		$output .= '</a>';
		
		return $output;
	}	

	function map_shortcode() {

		if ( function_exists('boldthemes_get_icon_fonts_bb_array') ) {
			$icon_arr = boldthemes_get_icon_fonts_bb_array();
		} else {
			require_once( dirname(__FILE__) . '/../../content_elements_misc/fa_icons.php' );
			require_once( dirname(__FILE__) . '/../../content_elements_misc/s7_icons.php' );
			$icon_arr = array( 'Font Awesome' => bt_bb_fa_icons(), 'S7' => bt_bb_s7_icons() );
		}

		require_once( dirname(__FILE__) . '/../../content_elements_misc/misc.php' );
		$color_scheme_arr = bt_bb_get_color_scheme_param_array();

		bt_bb_map( $this->shortcode, array( 'name' => __( 'Button', 'bold-builder' ), 'description' => __( 'Button with custom link', 'bold-builder' ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				array( 'param_name' => 'text', 'type' => 'textfield', 'heading' => __( 'Text', 'bold-builder' ), 'preview' => true ),
				array( 'param_name' => 'icon', 'type' => 'iconpicker', 'heading' => __( 'Icon', 'bold-builder' ), 'value' => $icon_arr, 'preview' => true ),
				array( 'param_name' => 'icon_position', 'type' => 'dropdown', 'heading' => __( 'Icon Position', 'bold-builder' ),
					'value' => array(
						__( 'Left', 'bold-builder' ) => 'left',
						__( 'Right', 'bold-builder' ) => 'right'
					)
				),
				array( 'param_name' => 'url', 'type' => 'textfield', 'heading' => __( 'URL', 'bold-builder' ), 'preview' => true ),
				array( 'param_name' => 'target', 'type' => 'dropdown', 'heading' => __( 'Target', 'bold-builder' ),
					'value' => array(
						__( 'Self (open in same tab)', 'bold-builder' ) => '_self',
						__( 'Blank (open in new tab)', 'bold-builder' ) => '_blank',
					)
				),
				array( 'param_name' => 'align', 'type' => 'dropdown', 'heading' => __( 'Alignment', 'bold-builder' ),
					'value' => array(
						__( 'Inherit', 'bold-builder' ) => 'inherit',
						__( 'Left', 'bold-builder' ) => 'left',
						__( 'Right', 'bold-builder' ) => 'right'
					)
				),
				array( 'param_name' => 'size', 'type' => 'dropdown', 'heading' => __( 'Size', 'bold-builder' ), 'preview' => true,
					'value' => array(
						__( 'Small', 'bold-builder' ) => 'small',
						__( 'Normal', 'bold-builder' ) => 'normal',
						__( 'Large', 'bold-builder' ) => 'large'
					)
				),				
				array( 'param_name' => 'color_scheme', 'type' => 'dropdown', 'heading' => __( 'Color scheme', 'bold-builder' ), 'value' => $color_scheme_arr, 'preview' => true, 'group' => __( 'Design', 'bold-builder' ) ),
				array( 'param_name' => 'style', 'type' => 'dropdown', 'heading' => __( 'Style', 'bold-builder' ), 'preview' => true, 'group' => __( 'Design', 'bold-builder' ),
					'value' => array(
						__( 'Outline', 'bold-builder' ) => 'outline',
						__( 'Filled', 'bold-builder' ) => 'filled',
						__( 'Clean', 'bold-builder' ) => 'clean'
					)
				),
				array( 'param_name' => 'width', 'type' => 'dropdown', 'heading' => __( 'Width', 'bold-builder' ), 'group' => __( 'Design', 'bold-builder' ),
					'value' => array(
						__( 'Inline', 'bold-builder' ) => 'inline',
						__( 'Full', 'bold-builder' ) => 'full'				
					)
				),
				array( 'param_name' => 'shape', 'type' => 'dropdown', 'heading' => __( 'Shape', 'bold-builder' ), 'group' => __( 'Design', 'bold-builder' ),
					'value' => array(
						__( 'Inherit', 'bold-builder' ) => 'inherit',
						__( 'Square', 'bold-builder' ) => 'square',
						__( 'Rounded', 'bold-builder' ) => 'rounded',
						__( 'Round', 'bold-builder' ) => 'round'
					)
				)
			)
		) );
	} 
}