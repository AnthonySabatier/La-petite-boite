<?php

class bt_bb_video extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts', array(
			'video'            => '',
			'disable_controls' => ''
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
		
		if ( $disable_controls != '' ) {
			$class[] = $this->prefix . 'disable_controls' . '_' . $disable_controls;
		}		
		
		$output = '[video src="' . $video . '"]';
		
		$output = '<div' . $id_attr . ' class="' . implode( ' ', $class ) . '"' . $style_attr . '>' . do_shortcode( $output ) . '</div>';
		
		return $output;

	}

	function map_shortcode() {
		bt_bb_map( $this->shortcode, array( 'name' => __( 'Video', 'bold-builder' ), 'description' => __( 'Video player', 'bold-builder' ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				array( 'param_name' => 'video', 'type' => 'textfield', 'heading' => __( 'Video', 'bold-builder' ) ),
				array( 'param_name' => 'disable_controls', 'type' => 'dropdown', 'heading' => __( 'Disable player controls', 'bold-builder' ),
				'value' => array(
					__( 'No', 'bold-builder' ) => 'no',
					__( 'Yes', 'bold-builder' ) => 'yes'
				),
				'description' => __( 'Useful when embedded video has its own controls, e.g. Vimeo', 'bold-builder' ) )
			)
		) );
	}
}