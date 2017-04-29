<?php

class bt_bb_content_slider_item extends BT_BB_Element {
	
	public $auto_play = '';

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts', array(
			'image'      => '',
			'image_size' => ''
		) ), $atts, $this->shortcode ) );
		
		$class = array( $this->shortcode );
		
		if ( $el_class != '' ) {
			$class[] = $el_class;
		}
		
		$id_attr = '';
		if ( $el_id != '' ) {
			$id_attr = ' ' . 'id="' . $el_id . '"';
		}

		$style_attr = ' ' . 'style="' . $el_style;

		if ( $image != '' ) {
			$image_src = wp_get_attachment_image_src( $image, $image_size );
			$style_attr .= ';background-image:url(' . $image_src[0] . ')';
		}

		$style_attr .= '"';

		$output = '<div' . $id_attr . ' class="' . implode( ' ', $class ) . '"' . $style_attr . '><div class="bt_bb_content_slider_item_content content">' . wpautop( wptexturize( do_shortcode( $content ) ) ) . '</div></div>';
		
		return $output;

	}

	function map_shortcode() {
		bt_bb_map( $this->shortcode, array( 'name' => __( 'Slider Item', 'bold-builder' ), 'description' => __( 'Individual slide element', 'bold-builder' ), 'container' => 'vertical', 'accept' => array( 'bt_bb_section' => false, 'bt_bb_row' => false, 'bt_bb_column' => false, 'bt_bb_column_inner' => false, 'bt_bb_tab_item' => false, 'bt_bb_accordion_item' => false, 'bt_bb_cost_calculator_item' => false, 'bt_cc_group' => false, 'bt_cc_multiply' => false, 'bt_cc_item' => false, 'bt_bb_content_slider_item' => false, 'bt_bb_google_maps_location' => false, '_content' => false ), 'accept_all' => true, 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				array( 'param_name' => 'image', 'type' => 'attach_image', 'heading' => __( 'Image', 'bold-builder' ), 'preview' => true ),
				array( 'param_name' => 'image_size', 'type' => 'dropdown', 'heading' => __( 'Image size', 'bold-builder' ), 'preview' => true,
					'value' => bt_bb_get_image_sizes()
				)
			)
		) );
	}
}