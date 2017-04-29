<?php

class bt_bb_column_inner extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts', array(
			'width'    					=> '',
			'align'   					=> 'left',
			'vertical_align'			=> 'top',
			'padding'					=> '',
			'animation'					=> '',
			'background_color'			=> '',
			'inner_background_color' 	=> '',
			'opacity'					=> ''
		) ), $atts, $this->shortcode ) );

		$class = array( $this->shortcode );

		if ( $el_class != '' ) {
			$class[] = $el_class;
		}
		
		$id_attr = '';
		if ( $el_id != '' ) {
			$id_attr = 'id="' . $el_id . '"';
		}

		$array = explode( '/', $width );

		if ( empty( $array ) || $array[0] == 0 || $array[1] == 0 ) {
			$width = 12;
		} else {
			$top = $array[0];
			$bottom = $array[1];
			
			$width = 12 * $top / $bottom;
			
			if ( ! is_int( $width ) || $width < 1 || $width > 12 ) {
				$width = 12;
			}
		}

		if ( $width == 2 ) {
			$class[] = 'col-md-2 col-sm-4 col-ms-12';
		} else if ( $width == 3 ) {
			$class[] = 'col-md-3 col-sm-6 col-ms-12';
		} else if ( $width == 4 ) {
			$class[] = 'col-md-4 col-ms-12';
		} else if ( $width == 6 ) {
			$class[] = 'col-md-6 col-sm-12';	
		} else if ( $width == 8 ) {
			$class[] = 'col-md-8 col-ms-12';
		} else {
			$class[] = 'col-md-' . $width . ' ' . 'col-ms-12';
		}
		
		if ( $align != '' ) {
			$class[] = $this->prefix . 'align' . '_' . $align;
		}
		
		if ( $vertical_align != '' ) {
			$class[] = $this->prefix . 'vertical_align' . '_' . $vertical_align;
		}
		
		if ( $animation != 'no_animation' && $animation != '' ) {
			$class[] = $this->prefix . 'animation' . '_' . $animation;
			$class[] = 'animate';
		}

		if ( $padding != '' ) {
			$class[] = $this->prefix . 'padding' . '_' . $padding;
		}
		
		if ( $background_color != '' ) {
			$background_color = bt_bb_column::hex2rgb( $background_color );
			if ( $opacity == '' ) {
				$opacity = 1;
			}
			$el_style .= 'background-color: rgba(' . $background_color[0] . ', ' . $background_color[1] . ', ' . $background_color[2] . ', ' . $opacity . ');';
		}

		$el_inner_style = '';
		
		if ( $inner_background_color != '' ) {
			$inner_background_color = bt_bb_column::hex2rgb( $inner_background_color );
			if ( $opacity == '' ) {
				$opacity = 1;
			}
			$el_inner_style .= ' style="background-color: rgba(' . $inner_background_color[0] . ', ' . $inner_background_color[1] . ', ' . $inner_background_color[2] . ', ' . $opacity . ');" ';
		}
		
		$style_attr = '';

		if ( $el_style != '' ) {
			$style_attr = 'style="' . $el_style . '"';
		}		
	
		$output = '<div ' . $id_attr . ' class="' . implode( ' ', $class ) . '" ' . $style_attr . ' data-width="' . $width . '">';
			$output .= '<div class="' . $this->shortcode . '_content"' . $el_inner_style . '>';
				$output .= wptexturize( do_shortcode( $content ) );
			$output .= '</div>';
		$output .= '</div>';

		return $output;

	}

	function map_shortcode() {		
		
			bt_bb_map( $this->shortcode, array( 'name' => __( 'Inner Column', 'bold-builder' ), 'description' => __( 'Inner Column element', 'bold-builder' ), 'width_param' => 'width', 'container' => 'vertical', 
				'accept' => array( 'bt_bb_section' => false, 'bt_bb_row' => false, 'bt_bb_row_inner' => false, 'bt_bb_column' => false, 'bt_bb_column_inner' => false, 'bt_bb_tab_item' => false, 'bt_bb_accordion_item' => false, 'bt_bb_cost_calculator_item' => false, 'bt_cc_group' => false, 'bt_cc_multiply' => false, 'bt_cc_item' => false, 'bt_bb_content_slider_item' => false, 'bt_bb_google_maps_location' => false, '_content' => false ),
				'accept_all' => true, 'toggle' => true,  'show_settings_on_create' => false, 'icon' => 'bt_bb_icon_bt_bb_row_inner',
				'params' => array(
					array( 'param_name' => 'align', 'type' => 'dropdown', 'heading' => __( 'Align', 'bold-builder' ), 'preview' => true,
					'value' => array(
						__( 'Left', 'bold-builder' ) => 'left',
						__( 'Right', 'bold-builder' ) => 'right',
						__( 'Center', 'bold-builder' ) => 'center'					
					) ),
					array( 'param_name' => 'padding', 'type' => 'dropdown', 'heading' => __( 'Padding', 'bold-builder' ), 'preview' => true,
						'value' => array(
						__( 'Normal', 'bold-builder' ) => 'normal',
						__( 'Double', 'bold-builder' ) => 'double',
						__( 'Text Indent', 'bold-builder' ) => 'text_indent'					
					) ),				
					array( 'param_name' => 'vertical_align', 'type' => 'dropdown', 'heading' => __( 'Vertical Align', 'bold-builder' ), 'preview' => true,
						'value' => array(
							__( 'Top', 'bold-builder' )     => 'top',
							__( 'Middle', 'bold-builder' )  => 'middle',
							__( 'Bottom', 'bold-builder' )  => 'bottom'					
					) ),
					array( 'param_name' => 'animation', 'type' => 'dropdown', 'heading' => __( 'Animation', 'bold-builder' ), 'preview' => true,
						'value' => array(
							__( 'No Animation', 'bold-builder' ) => 'no_animation',
							__( 'Fade In', 'bold-builder' ) => 'fade_in',
							__( 'Move Up', 'bold-builder' ) => 'move_up',
							__( 'Move Left', 'bold-builder' ) => 'move_left',
							__( 'Move Right', 'bold-builder' ) => 'move_right',
							__( 'Move Down', 'bold-builder' ) => 'move_down',
							__( 'Fade In / Move Up', 'bold-builder' ) => 'fade_in move_up',
							__( 'Fade In / Move Left', 'bold-builder' ) => 'fade_in move_left',
							__( 'Fade In / Move Right', 'bold-builder' ) => 'fade_in move_right',
							__( 'Fade In / Move Down', 'bold-builder' ) => 'fade_in move_down'		
					) ),
					array( 'param_name' => 'background_color', 'type' => 'colorpicker', 'heading' => __( 'Background color', 'bold-builder' ), 'group' => __( 'Design', 'bold-builder' ) ),
					array( 'param_name' => 'inner_background_color', 'type' => 'colorpicker', 'heading' => __( 'Inner background color', 'bold-builder' ), 'group' => __( 'Design', 'bold-builder' ) ),
					array( 'param_name' => 'opacity', 'type' => 'textfield', 'heading' => __( 'Opacity (0 - 1, e.g. 0.4)', 'bold-builder' ), 'group' => __( 'Design', 'bold-builder' ) )
				)
			) );
		
	}

}