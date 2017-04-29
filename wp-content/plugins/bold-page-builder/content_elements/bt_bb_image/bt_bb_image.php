<?php

class bt_bb_image extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts', array(
			'image'  => '',
			'size'   => '',
			'shape'  => '',
			'align'  => '',
			'url'    => '',
			'target' => '',
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
		
		if ( $shape != '' ) {
			$class[] = $this->prefix . 'shape' . '_' . $shape;
		}
		
		if ( $align != '' ) {
			$class[] = $this->prefix . 'align' . '_' . $align;
		}
		if ( $image != '' ) {
			$post_image = get_post( $image );
			if ( $post_image == '' ) return;
			$caption = get_post( $image )->post_excerpt;
			$title_attr = '';
			if ( $caption != '' ) {
				$title_attr = ' ' . 'title="' . $caption . '"';
			}
			$image = wp_get_attachment_image_src( $image, $size );
			$image = $image[0];
		}
		
		$output = '';
		
		if ( ! empty( $image ) ) {
			$output .= '<img src="' . $image . '"' . $title_attr . '>';
		}
		
		if ( ! empty( $url ) ) {
			$output = '<a href="' . $url . '"  target="' . $target . '" title="' . $caption . '">' . $output . '</a>';
		} else {
			$output = '<span>' . $output . '</span>';
		}
		
		$output = '<div' . $id_attr . ' class="' . implode( ' ', $class ) . '"' . $style_attr . '>' . $output . '</div>';
		
		return $output;

	}

	function map_shortcode() {
		bt_bb_map( $this->shortcode, array( 'name' => __( 'Image', 'bold-builder' ), 'description' => __( 'Single image', 'bold-builder' ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				array( 'param_name' => 'image', 'type' => 'attach_image', 'heading' => __( 'Image', 'bold-builder' ), 'preview' => true ),
				array( 'param_name' => 'size', 'type' => 'dropdown', 'heading' => __( 'Size', 'bold-builder' ), 'preview' => true,
					'value' => bt_bb_get_image_sizes()
				),
				array( 'param_name' => 'shape', 'type' => 'dropdown', 'heading' => __( 'Shape', 'bold-builder' ),
					'value' => array(
						__( 'Square', 'bold-builder' ) => 'square',
						__( 'Soft Rounded', 'bold-builder' ) => 'soft-rounded',
						__( 'Hard Rounded', 'bold-builder' ) => 'hard-rounded'
					)
				),
				array( 'param_name' => 'align', 'type' => 'dropdown', 'heading' => __( 'Alignment', 'bold-builder' ),
					'value' => array(
						__( 'Inherit', 'bold-builder' ) => 'inherit',
						__( 'Left', 'bold-builder' ) => 'left',
						__( 'Right', 'bold-builder' ) => 'right'
					)
				),
				array( 'param_name' => 'url', 'type' => 'textfield', 'heading' => __( 'URL', 'bold-builder' ) ),
				array( 'param_name' => 'target', 'type' => 'dropdown', 'heading' => __( 'Target', 'bold-builder' ),
					'value' => array(
						__( 'Self (open in same tab)', 'bold-builder' ) => '_self',
						__( 'Blank (open in new tab)', 'bold-builder' ) => '_blank'
					)
				)
			)
		) );
	}
}