<?php

class bt_bb_masonry_post_grid extends BT_BB_Element {

	function __construct() {
		parent::__construct();
		add_action( 'wp_ajax_bt_bb_get_grid', array( __CLASS__, 'bt_bb_get_grid_callback' ) );
		add_action( 'wp_ajax_nopriv_bt_bb_get_grid', array( __CLASS__, 'bt_bb_get_grid_callback' ) );
	}

	static function bt_bb_get_grid_callback() {
		bt_bb_masonry_post_grid::dump_grid( intval( $_POST['number'] ), sanitize_text_field( $_POST['category'] ) );
		die();
	}
	
	static function dump_grid( $number, $category ) {
		
		$output = '';

		$posts = bt_bb_get_posts( $number, 0, $category );

		foreach( $posts as $item ) {
			$post_thumbnail_id = get_post_thumbnail_id( $item['ID'] ); 
			$img = wp_get_attachment_image_src( $post_thumbnail_id, 'large' );
			$img_src = $img[0];
			$hw = 0;
			if ( $img_src != '' ) {
				$hw = $img[2] / $img[1];
			}
			$output .= '<div class="bt_bb_grid_item" data-hw="' . $hw . '" data-src="' . $img_src . '">';
				$output .= '<div class="bt_bb_grid_item_post_thumbnail"><a href="' . $item['permalink'] . '" title="' . $item['title'] . '"></a></div>';
				$output .= '<div class="bt_bb_grid_item_post_content">';
					$output .= '<div class="bt_bb_grid_item_date">';
						$output .= $item['date'];
					$output .= '</div>';
					$output .= '<h5 class="bt_bb_grid_item_post_title"><a href="' . $item['permalink'] . '" title="' . $item['title'] . '">' . $item['title'] . '</a></h5>';
					$output .= '<div class="bt_bb_grid_item_post_excerpt">' . $item['excerpt'] . '</div>';
				$output .= '</div>';
			$output .= '</div>';
		}
		
		echo $output;
	}

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts', array(
			'number'          => '',
			'columns'         => '',
			'gap'             => '',
			'category'        => '',
			'category_filter' => ''
		) ), $atts, $this->shortcode ) );

		wp_enqueue_script( 'jquery-masonry' );

		wp_enqueue_script( 
			'bt_bb_post_grid',
			plugin_dir_url( __FILE__ ) . 'bt_bb_post_grid.js',
			array( 'jquery' )
		);
		
		wp_localize_script( 'bt_bb_post_grid', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

		$class = array( $this->shortcode, 'bt_bb_grid_container' );

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
		
		if ( $number > 1000 || $number == '' ) {
			$number = 1000;
		} else if ( $number < 1 ) {
			$number = 1;
		}

		$output = '';
		
		if ( $category_filter == 'yes' ) {
			$cats = get_categories();
			$output .= '<div class="bt_bb_post_grid_filter">';
				$output .= '<span class="bt_bb_post_grid_filter_item active" data-category="">' . __( 'All', 'bold-builder' ) . '</span>';
				foreach ( $cats as $cat ) {
					$output .= '<span class="bt_bb_post_grid_filter_item" data-category="' . $cat->slug . '">' . $cat->name . '</span>';
				}
			$output .= '</div>';
		}
		
		$output .= '<div class="bt_bb_post_grid_loader"></div>';

		$output .= '<div class="bt_bb_masonry_post_grid_content bt_bb_grid_hide" data-number="' . $number . '" data-category="' . $category . '"><div class="bt_bb_grid_sizer"></div></div>';

		$output = '<div' . $id_attr . ' class="' . implode( ' ', $class ) . '"' . $style_attr . ' data-columns="' . $columns . '">' . $output . '</div>';

		return $output;

	}

	function map_shortcode() {

		bt_bb_map( $this->shortcode, array( 'name' => __( 'Masonry Post Grid', 'bold-builder' ), 'description' => __( 'Masonry grid with posts', 'bold-builder' ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				array( 'param_name' => 'number', 'type' => 'textfield', 'heading' => __( 'Number of items', 'bold-builder' ), 'description' => __( 'Enter number of items or leave empty to show all (up to 1000)', 'bold-builder' ), 'preview' => true ),
				array( 'param_name' => 'columns', 'type' => 'dropdown', 'heading' => __( 'Columns', 'bold-builder' ), 'preview' => true,
					'value' => array(
						__( '1', 'bold-builder' ) => '1',
						__( '2', 'bold-builder' ) => '2',
						__( '3', 'bold-builder' ) => '3',
						__( '4', 'bold-builder' ) => '4',
						__( '5', 'bold-builder' ) => '5',
						__( '6', 'bold-builder' ) => '6'
					)
				),
				array( 'param_name' => 'gap', 'type' => 'dropdown', 'heading' => __( 'Gap', 'bold-builder' ),
					'value' => array(
						__( 'No gap', 'bold-builder' ) => 'no_gap',
						__( 'Small', 'bold-builder' ) => 'small',
						__( 'Normal', 'bold-builder' ) => 'normal',
						__( 'Large', 'bold-builder' ) => 'large'
					)
				),
				array( 'param_name' => 'category', 'type' => 'textfield', 'heading' => __( 'Category', 'bold-builder' ), 'description' => __( 'Enter category slug or leave empty to show all', 'bold-builder' ), 'preview' => true ),
				array( 'param_name' => 'category_filter', 'type' => 'dropdown', 'heading' => __( 'Category filter', 'bold-builder' ),
					'value' => array(
						__( 'No', 'bold-builder' ) => 'no',
						__( 'Yes', 'bold-builder' ) => 'yes'
					)
				)				
			)
		) );
	} 
}