<?php

class bt_bb_slider extends BT_BB_Element {
	
	public $auto_play = '';

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts', array(
			'images'    	=> '',
			'height'    	=> '',
			'show_dots'     => '',
			'auto_play' 	=> ''
		) ), $atts, $this->shortcode ) );
		
		$class = array( $this->shortcode );
		// $class[] = 'slick-slider';
		
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
		
		if ( $auto_play != '' ) {
			$data_slick .= ',"autoplay": true, "autoplaySpeed": ' . intval( $auto_play );
		}
		
		if ( is_rtl() ) {
			$data_slick .= ', "rtl": true' ;
		}
		
		$data_slick = $data_slick . '}\' ';
		
		$output = '';

		if ( $images != '' ) {
			$image_array = explode( ',', $images );
			foreach( $image_array as $image ) {
				$post_image = get_post( $image );
				$caption = get_post( $image )->post_excerpt;
				$image = wp_get_attachment_image_src( $image, 'full' );
				$image = $image[0];
				if ( $height == 'auto' || $height == 'keep-height' ) {
					$output .= '<div class="bt_bb_slider_item"><img src="' . $image . '"></div>';
				} else {
					$output .= '<div class="bt_bb_slider_item" style="background-image:url(\'' . $image . '\')"></div>';
				}
				
			}
		}

		$output = '<div' . $id_attr . ' class="' . implode( ' ', $class ) . '"' . $style_attr . '><div class="slick-slider" ' . $data_slick . '>' . $output . '</div></div>';
		
		return $output;

	}

	function map_shortcode() {
		bt_bb_map( $this->shortcode, array( 'name' => __( 'Image Slider', 'bold-builder' ), 'description' => __( 'Slider with images', 'bold-builder' ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				array( 'param_name' => 'images', 'type' => 'attach_images', 'heading' => __( 'Images', 'bold-builder' ) ),
				array( 'param_name' => 'height', 'type' => 'dropdown', 'heading' => __( 'Size', 'bold-builder' ),
					'value' => array(
						__( 'Auto', 'bold-builder' ) => 'auto',
						__( 'Keep height', 'bold-builder' ) => 'keep-height',
						__( 'Half screen', 'bold-builder' ) => 'half_screen',
						__( 'Full screen', 'bold-builder' ) => 'full_screen'
					)
				),
				array( 'param_name' => 'show_dots', 'type' => 'dropdown', 'heading' => __( 'Dots navigation', 'bold-builder' ),
					'value' => array(
						__( 'Bottom', 'bold-builder' ) => 'bottom',
						__( 'Hide', 'bold-builder' ) => 'hide'
					)
				),
				array( 'param_name' => 'auto_play', 'type' => 'textfield', 'heading' => __( 'Autoplay interval (ms)', 'bold-builder' ), 'description' => __( 'e.g. 2000' ) )
			)
		) );
	}
}