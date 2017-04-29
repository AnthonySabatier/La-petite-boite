<?php

class bt_bb_progress_bar extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts', array(
			'text'        		=> '',
			'percentage'        => '',
			'color_scheme' 		=> '',
			'size'        		=> '',
			'align'        		=> '',
			'style'        		=> '',
			'shape'        		=> ''
		) ), $atts, $this->shortcode ) );	

		$class = array( $this->shortcode );

		if ( $text == '' ) {
			$text = $percentage . "%";
		}
		
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

		if ( $color_scheme != '' ) {
			$class[] = $this->prefix . 'color_scheme_' . bt_bb_get_color_scheme_id( $color_scheme );
		}
		
		if ( $align != '' ) {
			$class[] = $this->prefix . 'align' . '_' . $align;
		}
		
		if ( $size != '' ) {
			$class[] = $this->prefix . 'size' . '_' . $size;
		}

		if ( $style != '' ) {
			$class[] = $this->prefix . 'style' . '_' . $style;
		}		

		if ( $shape != '' ) {
			$class[] = $this->prefix . 'shape' . '_' . $shape;
		}

		$content = do_shortcode( $content );

		$output = '';

		$output .= '<div' . $id_attr . ' class="' . implode( ' ', $class ) . '"' . $style_attr . '><div class="bt_bb_progress_bar_bg"></div><div class="bt_bb_progress_bar_inner animate" style="width:' . $percentage . '%"><span class="bt_bb_progress_bar_text">' . $text . '</span></div></div>';

		return $output;

	}

	function map_shortcode() {
		
		require_once( dirname(__FILE__) . '/../../content_elements_misc/misc.php' );
		$color_scheme_arr = bt_bb_get_color_scheme_param_array();			
		
		bt_bb_map( $this->shortcode, array( 'name' => __( 'Progress bar', 'bold-builder' ), 'description' => __( 'Progress bar', 'bold-builder' ), 'container' => 'vertical', 'accept' => false, 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				array( 'param_name' => 'percentage', 'type' => 'textfield', 'heading' => __( 'Percentage', 'bold-builder' ), 'preview' => true ),
				array( 'param_name' => 'text', 'type' => 'textfield', 'heading' => __( 'Text', 'bold-builder' ), 'preview' => true ),
				array( 'param_name' => 'size', 'type' => 'dropdown', 'heading' => __( 'Size', 'bold-builder' ), 'preview' => true,
					'value' => array(
						__( 'Normal', 'bold-builder' ) => 'normal',
						__( 'Small', 'bold-builder' ) => 'small'
					)
				),
				array( 'param_name' => 'align', 'type' => 'dropdown', 'heading' => __( 'Align', 'bold-builder' ), 'preview' => true,
					'value' => array(
						__( 'Inherit', 'bold-builder' ) => 'inherit',
						__( 'Left', 'bold-builder' ) => 'left',
						__( 'Right', 'bold-builder' ) => 'right',
						__( 'Center', 'bold-builder' ) => 'center'					
					)
				),
				array( 'param_name' => 'color_scheme', 'type' => 'dropdown', 'heading' => __( 'Color scheme', 'bold-builder' ), 'value' => $color_scheme_arr, 'preview' => true, 'group' => __( 'Design', 'bold-builder' ) ),
				array( 'param_name' => 'style', 'type' => 'dropdown', 'heading' => __( 'Style', 'bold-builder' ), 'preview' => true, 'group' => __( 'Design', 'bold-builder' ),
					'value' => array(
						__( 'Filled', 'bold-builder' ) => 'filled',
						__( 'Line', 'bold-builder' ) => 'line',
						__( 'Outline', 'bold-builder' ) => 'outline'
					)
				),
				array( 'param_name' => 'shape', 'type' => 'dropdown', 'heading' => __( 'Shape', 'bold-builder' ), 'group' => __( 'Design', 'bold-builder' ),
					'value' => array(
						__( 'Square', 'bold-builder' ) => 'square',
						__( 'Rounded', 'bold-builder' ) => 'rounded',
					)
				)				
			)
		) );
	}
}