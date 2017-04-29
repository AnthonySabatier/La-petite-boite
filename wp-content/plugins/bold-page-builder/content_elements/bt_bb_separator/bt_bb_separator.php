<?php

class bt_bb_separator extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts', array(
			'top_spacing'    => '',
			'bottom_spacing' => '',
			'border_style'   => '',
			'border_width'   => '',
		) ), $atts, $this->shortcode ) );
		
		$class = array( $this->shortcode );
		
		if ( $el_class != '' ) {
			$class[] = $el_class;
		}
		
		$id_attr = '';
		if ( $el_id != '' ) {
			$id_attr = ' ' . 'id="' . $el_id . '"';
		}
		
		if ( $top_spacing != '' ) {
			$class[] = $this->prefix . 'top_spacing' . '_' . $top_spacing;
		}
		
		if ( $bottom_spacing != '' ) {
			$class[] = $this->prefix . 'bottom_spacing' . '_' . $bottom_spacing;
		}
		
		if ( $border_style != '' ) {
			$class[] = $this->prefix . 'border_style' . '_' . $border_style;
		}

		if ( $border_width != '' ) {
			$el_style = $el_style . ';' . $border_width;
		}
		
		$style_attr = '';
		if ( $el_style != '' ) {
			$style_attr = ' ' . 'style="' . $el_style . '"';
		}
		
		$output = '<div' . $id_attr . ' class="' . implode( ' ', $class ) . '"' . $style_attr . '></div>';
		
		return $output;

	}

	function map_shortcode() {
		bt_bb_map( $this->shortcode, array( 'name' => __( 'Separator', 'bold-builder' ), 'description' => __( 'Separator line', 'bold-builder' ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array( 
				array( 'param_name' => 'top_spacing', 'type' => 'dropdown', 'heading' => __( 'Top spacing', 'bold-builder' ), 'preview' => true,
					'value' => array(
						__( 'No spacing', 'bold-builder' ) => '',
						__( 'Extra small', 'bold-builder' ) => 'extra_small',
						__( 'Small', 'bold-builder' ) => 'small',		
						__( 'Normal', 'bold-builder' ) => 'normal',
						__( 'Medium', 'bold-builder' ) => 'medium',
						__( 'Large', 'bold-builder' ) => 'large',
						__( 'Extra large', 'bold-builder' ) => 'extra_large'
					)
				),
				array( 'param_name' => 'bottom_spacing', 'type' => 'dropdown', 'heading' => __( 'Bottom spacing', 'bold-builder' ), 'preview' => true,
					'value' => array(
						__( 'No spacing', 'bold-builder' ) => '',
						__( 'Extra small', 'bold-builder' ) => 'extra_small',
						__( 'Small', 'bold-builder' ) => 'small',		
						__( 'Normal', 'bold-builder' ) => 'normal',
						__( 'Medium', 'bold-builder' ) => 'medium',
						__( 'Large', 'bold-builder' ) => 'large',
						__( 'Extra large', 'bold-builder' ) => 'extra_large'
					)
				),				
				array( 'param_name' => 'border_style', 'type' => 'dropdown', 'heading' => __( 'Border style', 'bold-builder' ), 'preview' => true,
					'value' => array(
						__( 'None', 'bold-builder' ) => 'none',
						__( 'Solid', 'bold-builder' ) => 'solid',
						__( 'Dotted', 'bold-builder' ) => 'dotted',
						__( 'Dashed', 'bold-builder' ) => 'dashed'
					)
				),
				array( 'param_name' => 'border_width', 'type' => 'textfield', 'heading' => __( 'Border width', 'bold-builder' ), 'description' => __( 'E.g. 5px or 1em', 'bold-builder' ) )
			)
		) );
	}
}