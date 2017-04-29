<?php

class bt_bb_accordion extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts', array(
			'color_scheme' => '',
			'style'        => '',
			'shape'        => ''
		) ), $atts, $this->shortcode ) );
		
		wp_enqueue_script( 
			'bt_bb_accordion',
			plugin_dir_url( __FILE__ ) . 'bt_bb_accordion.js',
			array( 'jquery' ),
			'',
			true
		);

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

		if ( $color_scheme != '' ) {
			$class[] = $this->prefix . 'color_scheme_' . bt_bb_get_color_scheme_id( $color_scheme );
		}

		if ( $style != '' ) {
			$class[] = $this->prefix . 'style' . '_' . $style;
		}		

		if ( $shape != '' ) {
			$class[] = $this->prefix . 'shape' . '_' . $shape;
		}

		$content = do_shortcode( $content );

		$output = '';

		$output .= '<div' . $id_attr . ' class="' . implode( ' ', $class ) . '"' . $style_attr . '>' . $content . '</div>';

		return $output;

	}

	function map_shortcode() {
		
		require_once( dirname(__FILE__) . '/../../content_elements_misc/misc.php' );
		$color_scheme_arr = bt_bb_get_color_scheme_param_array();			
		
		bt_bb_map( $this->shortcode, array( 'name' => __( 'Accordion', 'bold-builder' ), 'description' => __( 'Accordion container', 'bold-builder' ), 'container' => 'vertical', 'accept' => array( 'bt_bb_accordion_item' => true ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				array( 'param_name' => 'color_scheme', 'type' => 'dropdown', 'heading' => __( 'Color scheme', 'bold-builder' ), 'value' => $color_scheme_arr, 'preview' => true ),
				array( 'param_name' => 'style', 'type' => 'dropdown', 'heading' => __( 'Style', 'bold-builder' ), 'preview' => true,
					'value' => array(
						__( 'Outline', 'bold-builder' ) => 'outline',
						__( 'Filled', 'bold-builder' ) => 'filled',
						__( 'Simple', 'bold-builder' ) => 'simple'
					)
				),
				array( 'param_name' => 'shape', 'type' => 'dropdown', 'heading' => __( 'Shape', 'bold-builder' ),
					'value' => array(
						__( 'Square', 'bold-builder' ) => 'square',
						__( 'Rounded', 'bold-builder' ) => 'rounded',
						__( 'Round', 'bold-builder' ) => 'round'
					)
				)				
			)
		) );
	}
}