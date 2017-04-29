<?php

class bt_bb_raw_content extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts', array(
			'raw_content' => ''
		) ), $atts, $this->shortcode ) );
		
		return base64_decode( $raw_content );

	}
	
	function add_params() {
		// removes default params from BT_BB_Element
	}

	function map_shortcode() {
		bt_bb_map( $this->shortcode, array( 'name' => __( 'Raw Content', 'bold-builder' ), 'description' => __( 'Raw HTML/JS content', 'bold-builder' ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				array( 'param_name' => 'raw_content', 'type' => 'textarea_object', 'heading' => __( 'Raw content', 'bold-builder' ) )
			)
		) );
	}
}