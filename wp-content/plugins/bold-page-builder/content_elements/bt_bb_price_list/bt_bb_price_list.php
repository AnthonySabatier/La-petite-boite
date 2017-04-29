<?php

class bt_bb_price_list extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts', array(
			'title'        => '',
			'subtitle'     => '',
			'currency'     => '',
			'price'        => '',			
			'items'        => '',
			'color_scheme' => ''
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
		
		$output = '<div class="' . $this->shortcode . '_title">' . $title . '</div>';
		$output .= '<div class="' . $this->shortcode . '_subtitle">' . $subtitle . '</div>';
		$output .= '<div class="' . $this->shortcode . '_price"><span class="' . $this->shortcode . '_currency">' . $currency . '</span><span class="' . $this->shortcode . '_amount">' . $price . '</span></div>';

		$items_arr = preg_split( '/$\R?^/m', $items );

		$items = '<ul>';
			foreach ( $items_arr as $item ) {
				$items .= '<li>' . $item . '</li>';
			}
		$items .= '</ul>';
		
		$output .= $items;

		if ( $color_scheme != '' ) {
			$class[] = $this->prefix . 'color_scheme_' . bt_bb_get_color_scheme_id( $color_scheme );
		}

		$output = '<div' . $id_attr . ' class="' . implode( ' ', $class ) . '"' . $style_attr . '>' . $output . '</div>';

		return $output;

	}

	function map_shortcode() {
		
		require_once( dirname(__FILE__) . '/../../content_elements_misc/misc.php' );
		$color_scheme_arr = bt_bb_get_color_scheme_param_array();			
		
		bt_bb_map( $this->shortcode, array( 'name' => __( 'Price List', 'bold-builder' ), 'description' => __( 'List of items with total price', 'bold-builder' ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				array( 'param_name' => 'title', 'type' => 'textfield', 'heading' => __( 'Title', 'bold-builder' ), 'preview' => true ),
				array( 'param_name' => 'subtitle', 'type' => 'textfield', 'heading' => __( 'Subtitle', 'bold-builder' ) ),
				array( 'param_name' => 'currency', 'type' => 'textfield', 'heading' => __( 'Currency', 'bold-builder' ) ),
				array( 'param_name' => 'price', 'type' => 'textfield', 'heading' => __( 'Price', 'bold-builder' ) ),				
				array( 'param_name' => 'items', 'type' => 'textarea', 'heading' => __( 'Items', 'bold-builder' ) ),
				array( 'param_name' => 'color_scheme', 'type' => 'dropdown', 'heading' => __( 'Color scheme', 'bold-builder' ), 'value' => $color_scheme_arr, 'preview' => true ),			
			)
		) );
	}
}