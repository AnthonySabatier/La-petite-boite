<?php

class bt_bb_accordion_item extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts', array(
			'title' => ''
		) ), $atts, $this->shortcode ) );

		$output = '<div class="bt_bb_accordion_item">';
			$output .= '<div class="bt_bb_accordion_item_title">' . $title . '</div>';
			$output .= '<div class="bt_bb_accordion_item_content">' . wpautop( wptexturize( do_shortcode( $content ) ) ) . '</div>';
		$output .= '</div>';
		
		return $output;

	}
	
	function add_params() {
		// removes default params from BT_BB_Element
	}

	function map_shortcode() {
		bt_bb_map( $this->shortcode, array( 'name' => __( 'Accordion Item', 'bold-builder' ), 'description' => __( 'Single accordion element', 'bold-builder' ), 'container' => 'vertical', 'accept' => array( 'bt_bb_headline' => true, 'bt_bb_text' => true, 'bt_bb_image' => true, 'bt_bb_separator' => true ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				array( 'param_name' => 'title', 'type' => 'textfield', 'heading' => __( 'Title', 'bold-builder' ), 'preview' => true )			
			)
		) );
	}
}