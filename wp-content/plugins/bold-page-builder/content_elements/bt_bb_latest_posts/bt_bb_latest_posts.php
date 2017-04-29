<?php

class bt_bb_latest_posts extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts', array(
			'rows'        => '',
			'columns'     => '',
			'gap'         => '',
			'category'    => '',
			'target'      => '',
			'image_shape' => ''
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
		
		if ( $columns != '' ) {
			$class[] = $this->prefix . 'columns' . '_' . $columns;
		}
		
		if ( $gap != '' ) {
			$class[] = $this->prefix . 'gap' . '_' . $gap;
		}
		
		if ( $image_shape != '' ) {
			$class[] = $this->prefix . 'image_shape' . '_' . $image_shape;
		}
		
		$number = $rows * $columns;
		
		$posts = bt_bb_get_posts( $number, 0, $category );
		
		$output = '';

		foreach( $posts as $post_item ) {

			$output .= '<div class="' . $this->shortcode . '_item">';
				$post_thumbnail_id = get_post_thumbnail_id( $post_item['ID'] );
				if ( $post_thumbnail_id != '' ) {
					$img = wp_get_attachment_image_src( $post_thumbnail_id, $size = 'medium' );
					$img_src = $img[0];
					$output .= '<div class="' . $this->shortcode . '_item_image"><img src="' . $img_src . '"></div>';
				}
				
				$output .= '<div class="' . $this->shortcode . '_item_date">';
					$output .= $post_item['date'];
				$output .= '</div>';
				$output .= '<h5 class="' . $this->shortcode . '_item_title">';
					$output .= '<a href="' . $post_item['permalink'] . '" target="' . $target . '">' . $post_item['title'] . '</a>';
				$output .= '</h5>';

				$output .= '<div class="' . $this->shortcode . '_item_excerpt">';
					$output .= $post_item['excerpt'];
				$output .= '</div>';
				
			$output .= '</div>';
		}

		$output = '<div' . $id_attr . ' class="' . implode( ' ', $class ) . '"' . $style_attr . '>' . $output . '</div>';

		return $output;

	}

	function map_shortcode() {

		bt_bb_map( $this->shortcode, array( 'name' => __( 'Latest Posts', 'bold-builder' ), 'description' => __( 'List of latest posts', 'bold-builder' ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				array( 'param_name' => 'rows', 'type' => 'textfield', 'value' => '1', 'heading' => __( 'Rows', 'bold-builder' ), 'preview' => true ),
				array( 'param_name' => 'columns', 'type' => 'dropdown', 'value' => '3', 'heading' => __( 'Columns', 'bold-builder' ), 'preview' => true,
					'value' => array(
						__( '1', 'bold-builder' ) => '1',
						__( '2', 'bold-builder' ) => '2',
						__( '3', 'bold-builder' ) => '3',
						__( '4', 'bold-builder' ) => '4',
						__( '6', 'bold-builder' ) => '6'
					)
				),
				array( 'param_name' => 'gap', 'type' => 'dropdown', 'heading' => __( 'Gap', 'bold-builder' ), 'preview' => true,
					'value' => array(
						__( 'Normal', 'bold-builder' ) => 'normal',
						__( 'No gap', 'bold-builder' ) => 'no_gap',
						__( 'Small', 'bold-builder' ) => 'small',
						__( 'Large', 'bold-builder' ) => 'large'
					)
				),				
				array( 'param_name' => 'category', 'type' => 'textfield', 'heading' => __( 'Category', 'bold-builder' ), 'description' => __( 'Enter category slug or leave empty to show all', 'bold-builder' ), 'preview' => true ),
				array( 'param_name' => 'target', 'type' => 'dropdown', 'heading' => __( 'Target', 'bold-builder' ),
					'value' => array(
						__( 'Self (open in same tab)', 'bold-builder' ) => '_self',
						__( 'Blank (open in new tab)', 'bold-builder' ) => '_blank',
					)
				),
				array( 'param_name' => 'image_shape', 'type' => 'dropdown', 'heading' => __( 'Image shape', 'bold-builder' ),
					'value' => array(
						__( 'Square', 'bold-builder' ) => 'square',
						__( 'Rounded', 'bold-builder' ) => 'rounded',
						__( 'Round', 'bold-builder' ) => 'round'
					)
				)
			)
		) );
	} 
}