<?php

class bt_bb_google_maps extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts', array(
			'api_key'         => '',
			'zoom'            => '',
			'height'          => '',
			'custom_style'    => ''
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

		if ( $api_key != '' ) {
			wp_enqueue_script( 
				'gmaps_api',
				'https://maps.googleapis.com/maps/api/js?key=' . $api_key
			);
		} else {
			wp_enqueue_script( 
				'gmaps_api',
				'https://maps.googleapis.com/maps/api/js?v=&sensor=false'
			);
		}
		
		if ( $zoom == '' ) {
			$zoom = 14;
		}

		$style_height = '';
		if ( $height != '' ) {
			$style_height = ' ' . 'style="height:' . $height . '"';
		}
		
		$map_id = uniqid( 'map_canvas' );

		$content_html = wptexturize( do_shortcode( $content ) );

		$locations = substr_count( $content_html, '"bt_bb_google_maps_location' );
		$locations_without_content = substr_count( $content_html, 'bt_bb_google_maps_location_without_content' );
	
		if ( $content != '' && $locations != $locations_without_content ) {
			$content = '<span class="' . $this->shortcode . '_content_toggler"></span><div class="' . $this->shortcode . '_content"><div class="' . $this->shortcode . '_content_wrapper">' . $content_html . '</div></div>';

			$class[] = $this->shortcode . '_with_content';
		} else {
			$content = $content_html;
		}

		$output = '<div class="' . $this->shortcode . '_map" id="' . $map_id . '"' . $style_height . '></div>';

		$output .= $content;

		$output = '<div' . $id_attr . ' class="' . implode( ' ', $class ) . '"' . $style_attr . '>' . $output . '</div>';

		$output .= '<script type="text/javascript">jQuery( document ).ready(function() { bt_bb_gmap_init( "' . $map_id . '", ' . $zoom . ', "' . $custom_style . '" ); }); </script>';

		return $output;

	}

	function map_shortcode() {
		bt_bb_map( $this->shortcode, array( 'name' => __( 'Google Maps', 'bold-builder' ), 'description' => __( 'Google Maps with custom content', 'bold-builder' ), 'container' => 'vertical', 'accept' => array( 'bt_bb_google_maps_location' => true ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				array( 'param_name' => 'api_key', 'type' => 'textfield', 'heading' => __( 'API key', 'bold-builder' ) ),
				array( 'param_name' => 'zoom', 'type' => 'textfield', 'heading' => __( 'Zoom (e.g. 14)', 'bold-builder' ) ),
				array( 'param_name' => 'height', 'type' => 'textfield', 'heading' => __( 'Height (e.g. 250px)', 'bold-builder' ), 'description' => __( 'Used when there is no content', 'bold-builder' ) ),
				array( 'param_name' => 'custom_style', 'type' => 'textarea_object', 'heading' => __( 'Custom map style array', 'bold-builder' ), 'description' => 'Find more custom styles at https://snazzymaps.com/' )
			)
		) );
	}
}