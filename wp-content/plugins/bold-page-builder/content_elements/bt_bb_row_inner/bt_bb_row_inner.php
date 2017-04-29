<?php

class bt_bb_row_inner extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts', array(
			'column_gap'     	=> ''
		) ), $atts, $this->shortcode ) );

		$class = array( $this->shortcode );

		if ( $el_class != '' ) {
			$class[] = $el_class;
		}

		$id_attr = '';
		if ( $el_id != '' ) {
			$id_attr = 'id="' . $el_id . '"';
		}

		$style_attr = '';
		if ( $el_style != '' ) {
			$style_attr = 'style="' . $el_style . '"';
		}

		if ( $column_gap != '' ) {
			$class[] = $this->prefix . 'column_inner_gap' . '_' . $column_gap;
		}		
	
		$output = '<div ' . $id_attr . ' class="' . implode( ' ', $class ) . '" ' . $style_attr . '>';
			$output .= wptexturize( do_shortcode( $content ) );
		$output .= '</div>';

		return $output;

	}

	function map_shortcode() {		
		
			bt_bb_map( $this->shortcode, array( 'name' => __( 'Inner Row', 'bold-builder' ), 'description' => __( 'Inner Row element', 'bold-builder' ), 'container' => 'horizontal', 
				'accept' => array( 'bt_bb_column_inner' => true ), 'toggle' => true,  'show_settings_on_create' => false, 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
				'params' => array(
					array( 'param_name' => 'column_gap', 'type' => 'dropdown', 'heading' => __( 'Column gap', 'bold-builder' ), 'preview' => true,
						'value' => array(
							__( 'Default', 'bold-builder' ) => '',
							__( '0px', 'bold-builder' ) => '0',
							__( '5px', 'bold-builder' ) => '5',
							__( '10px', 'bold-builder' ) => '10',
							__( '15px', 'bold-builder' ) => '15',
							__( '20px', 'bold-builder' ) => '20',
							__( '25px', 'bold-builder' ) => '25',
							__( '30px', 'bold-builder' ) => '30',
							__( '35px', 'bold-builder' ) => '35',
							__( '40px', 'bold-builder' ) => '40',
							__( '45px', 'bold-builder' ) => '45',
							__( '50px', 'bold-builder' ) => '50',
							__( '60px', 'bold-builder' ) => '60',
							__( '70px', 'bold-builder' ) => '70',
							__( '80px', 'bold-builder' ) => '80',
							__( '90px', 'bold-builder' ) => '90',
							__( '100px', 'bold-builder' ) => '100'			
					) )
				)
			) );
		
	}

}