<?php

class bt_bb_google_maps_location extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts', array(
			'latitude'  => '',
			'longitude' => '',
			'icon'      => ''
		) ), $atts, $this->shortcode ) );
		
		$class = array( $this->shortcode );
		
		if ( $el_class != '' ) {
			$class[] = $el_class;
		}
		
		if ( $content == '' ) {
			$class[] = $this->shortcode . '_without_content';
		}
		
		$id_attr = '';
		if ( $el_id != '' ) {
			$id_attr = ' ' . 'id="' . $el_id . '"';
		}

		$style_attr = '';
		if ( $el_style != '' ) {
			$style_attr = ' ' . 'style="' . $el_style . '"';
		}

		if ( $icon != '' ) {
			$icon = wp_get_attachment_image_src( $icon, 'full' );
			$icon = $icon[0];
		}

		$output = '<div' . $id_attr . ' class="' . implode( ' ', $class ) . '"' . $style_attr . ' data-lat="' . $latitude . '" data-lng="' . $longitude . '" data-icon="' . $icon . '">' . wptexturize( do_shortcode( $content ) ) . '</div>';

		return $output;

	}

	function map_shortcode() {
		bt_bb_map( $this->shortcode, array( 'name' => __( 'Google Maps Location', 'bold-builder' ), 'description' => __( 'Google Maps location', 'bold-builder' ), 'container' => 'vertical', 'accept' => array( 'bt_bb_headline' => true, 'bt_bb_text' => true, 'bt_bb_icon' => true, 'bt_bb_service_icon' => true, 'bt_bb_image' => true, 'bt_bb_separator' => true ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				array( 'param_name' => 'latitude', 'type' => 'textfield', 'heading' => __( 'Latitude', 'bold-builder' ), 'preview' => true ),
				array( 'param_name' => 'longitude', 'type' => 'textfield', 'heading' => __( 'Longitude', 'bold-builder' ), 'preview' => true ),
				array( 'param_name' => 'icon', 'type' => 'attach_image', 'heading' => __( 'Icon', 'bold-builder' ), 'preview' => true )
			)
		) );
	}
}