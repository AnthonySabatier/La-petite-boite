<?php

class bt_bb_content_slider extends BT_BB_Element {
	
	public $auto_play = '';

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts', array(
			'height'    			=> '',
			'show_dots' 			=> '',
			'gap' 					=> '',
			'arrows_size' 			=> '',
			'slides_to_show' 		=> '',
			'auto_play' 			=> ''
		) ), $atts, $this->shortcode ) );
		
		$class = array( $this->shortcode );
		//$class[] = 'slick-slider';
		
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
		
		if ( $gap != '' ) {
			$class[] = $this->prefix . 'gap' . '_' . $gap;
		}
		
		if ( $arrows_size != '' ) {
			$class[] = $this->prefix . 'arrows_size' . '_' . $arrows_size;
		}
		
		if ( $height != '' ) {
			$class[] = $this->prefix . 'height' . '_' . $height;
		}	
		
		$data_slick  = ' data-slick=\'{ "lazyLoad": "progressive", "prevArrow": "&lt;button type=\"button\" class=\"slick-prev\"&gt;", "nextArrow": "&lt;button type=\"button\" class=\"slick-next\"&gt;"';
		
		if ( $height != 'keep-height' ) {
			$data_slick .= ', "adaptiveHeight": true';
		}
		
		if ( $show_dots != 'hide' ) {
			$data_slick .= ', "dots": true' ;
		}
		
		if ( $slides_to_show != '' ) {
			$data_slick .= ',"slidesToShow": ' . intval( $slides_to_show );
			$class[] = $this->prefix . 'multiple_slides';
		}
		
		if ( $auto_play != '' ) {
			$data_slick .= ',"autoplay": true, "autoplaySpeed": ' . intval( $auto_play );
		}
		
		if ( is_rtl() ) {
			$data_slick .= ', "rtl": true' ;
		}
		
		$data_slick = $data_slick . '}\' ';

		$output = '<div' . $id_attr . ' class="' . implode( ' ', $class ) . '"' . $style_attr . '><div class="slick-slider" ' . $data_slick .  '>' . do_shortcode( $content ) . '</div></div>';
		
		return $output;

	}

	function map_shortcode() {
		bt_bb_map( $this->shortcode, array( 'name' => __( 'Slider', 'bold-builder' ), 'description' => __( 'Slider with custom content', 'bold-builder' ), 'container' => 'vertical', 'accept' => array( 'bt_bb_content_slider_item' => true ), 'toggle' => true, 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				array( 'param_name' => 'height', 'type' => 'dropdown', 'preview' => true, 'heading' => __( 'Size', 'bold-builder' ),
					'value' => array(
						__( 'Auto', 'bold-builder' ) => 'auto',
						__( 'Keep height', 'bold-builder' ) => 'keep-height',
						__( 'Half screen', 'bold-builder' ) => 'half_screen',
						__( 'Full screen', 'bold-builder' ) => 'full_screen'
					)
				),
				array( 'param_name' => 'arrows_size', 'type' => 'dropdown', 'preview' => true, 'default' => 'normal', 'heading' => __( 'Navigation arrows size', 'bold-builder' ),
					'value' => array(
						__( 'Small', 'bold-builder' ) => 'small',
						__( 'Normal', 'bold-builder' ) => 'normal',
						__( 'Large', 'bold-builder' ) => 'large'
					)
				),
				array( 'param_name' => 'show_dots', 'type' => 'dropdown', 'heading' => __( 'Dots navigation', 'bold-builder' ),
					'value' => array(
						__( 'Bottom', 'bold-builder' ) => 'bottom',
						__( 'Hide', 'bold-builder' ) => 'hide'
					)
				),
				array( 'param_name' => 'slides_to_show', 'type' => 'textfield', 'preview' => true, 'default' => 1, 'heading' => __( 'Number of slides to show', 'bold-builder' ), 'description' => __( 'e.g. 3' ) ),
				array( 'param_name' => 'gap', 'type' => 'dropdown', 'heading' => __( 'Gap', 'bold-builder' ),
					'value' => array(
						__( 'No gap', 'bold-builder' ) => 'no_gap',
						__( 'Small', 'bold-builder' ) => 'small',
						__( 'Normal', 'bold-builder' ) => 'normal',
						__( 'Large', 'bold-builder' ) => 'large'
					)
				),
				array( 'param_name' => 'auto_play', 'type' => 'textfield', 'heading' => __( 'Autoplay interval (ms)', 'bold-builder' ), 'description' => __( 'e.g. 2000' ) )
			)
		) );
	}
}