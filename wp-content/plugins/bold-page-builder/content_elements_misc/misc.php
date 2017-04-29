<?php

class BT_BB_State {
	static $fonts_added = array();
	static $font_subsets_added = array();
}

if ( ! function_exists( 'bt_bb_get_permalink_by_slug' ) ) {
	function bt_bb_get_permalink_by_slug( $page_slug, $post_type = 'page' ) {
		global $wpdb;
		$page = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type= %s", $page_slug, $post_type ) );
		if ( $page ) {
			return get_permalink( $page );
		}
		return $page_slug;
	}
}

if ( ! function_exists( 'bt_bb_get_color_scheme_param_array' ) ) {
	function bt_bb_get_color_scheme_param_array() {
		$color_scheme_arr = array( __( 'Inherit', 'bold-builder' ) => '' );

		$color_scheme_arr_temp = bt_bb_get_color_scheme_array();

		if ( $color_scheme_arr_temp[0] != '' ) {
			$i = 0;
			foreach( $color_scheme_arr_temp as $item ) {
				$i++;
				$item_arr = explode( ';', $item, 4 );
				if ( count( $item_arr ) == 4 ) {
					$color_scheme_arr[ $item_arr[1] ] = $item_arr[0];
				} else {
					$color_scheme_arr[ $item_arr[0] ] = $item_arr[0];
				}
			}
		}
		return $color_scheme_arr;
	}
}

if ( ! function_exists( 'bt_bb_add_color_schemes' ) ) {
	function bt_bb_add_color_schemes() {
		
		/*$content_post = get_post();
		$content = $content_post->post_content;
		
		$bt_bb_content = false;
		if ( strpos( $content, '[bt_bb_' ) === 0 ) {
			$bt_bb_content = true;
		}

		if ( ! $bt_bb_content ) {
			return;
		}
		
		$pattern = '/color_scheme="(.*?)"/';
		preg_match_all( $pattern, $content, $matches );
	
		$color_schemes_to_use = array_unique( $matches[1] );*/

		$color_scheme_arr = bt_bb_get_color_scheme_array();

		if ( $color_scheme_arr[0] != '' ) {
			$scheme_id = 1;
			$i = 0;
			foreach( $color_scheme_arr as $item ) {
	
				$scheme_id = $i + 1;

				$color_scheme = explode( ';', $color_scheme_arr[ $i ] );
				
				$this_scheme = $color_scheme[0];
				
				if ( count( $color_scheme ) == 4 ) {
					array_shift( $color_scheme );
					//$scheme_id = $this_scheme;
				}

				//if ( in_array( $this_scheme, $color_schemes_to_use ) ) {

					if ( file_exists( get_stylesheet_directory() . '/bold-page-builder/content_elements_misc/color_scheme_template.php' ) ) {

						require( get_stylesheet_directory() . '/bold-page-builder/content_elements_misc/color_scheme_template.php' );

					} else {

						require( 'color_scheme_template.php' );

					}

					$custom_css = str_replace( ': ', ':', $custom_css );
					$custom_css = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $custom_css);
	
					wp_add_inline_style( 'bt_bb_dummy', $custom_css );
				
				//}
				
				$i++;
			}
		}
	}
}

if ( ! function_exists( 'bt_bb_get_color_scheme_id' ) ) {
	function bt_bb_get_color_scheme_id( $scheme_name ) {

		$color_scheme_arr = bt_bb_get_color_scheme_array();

		$scheme_id = 1;
		$i = 0;
		foreach( $color_scheme_arr as $item ) {
			$i++;
			$item_arr = explode( ';', $item, 4 );
			if ( $item_arr[0] == $scheme_name ) {
				$scheme_id = $i;
				break;
			}
		}
		return $scheme_id;
	}
}

if ( ! function_exists( 'bt_bb_get_color_scheme_array' ) ) {
	function bt_bb_get_color_scheme_array() {

		$options = get_option( 'bt_bb_settings' );
		$color_schemes = $options['color_schemes'];
		$color_scheme_arr = explode( PHP_EOL, $color_schemes );

		$color_scheme_arr = apply_filters( 'bt_bb_color_scheme_arr', $color_scheme_arr );

		return $color_scheme_arr;
	}
}

if ( ! function_exists( 'bt_bb_enqueue_google_font' ) ) {
	function bt_bb_enqueue_google_font( $font, $subset ) {

		if ( ! in_array( $font, BT_BB_State::$fonts_added ) ) {

			BT_BB_State::$fonts_added[] = $font;

			$subset = preg_replace( '/\s+/', '', $subset );
			$subset_arr = explode( ',', $subset );

			BT_BB_State::$font_subsets_added = BT_BB_State::$font_subsets_added + $subset_arr;

			add_action( 'wp_footer', 'bt_bb_enqueue_google_fonts' );

		}
	}
}

if ( ! function_exists( 'bt_bb_enqueue_google_fonts' ) ) {
	function bt_bb_enqueue_google_fonts() {

		if ( count( BT_BB_State::$fonts_added ) > 0 ) {

			$font_families = array();

			foreach( BT_BB_State::$fonts_added as $item ) {
				$font_families[] = urldecode( $item ) . ':100,200,300,400,500,600,700,800,900,100italic,200italic,300italic,400italic,500italic,600italic,700italic,800italic,900italic';
			}

			$query_args = array(
				'family' => urlencode( implode( '|', $font_families ) ),
				'subset' => urlencode( implode( ',', BT_BB_State::$font_subsets_added ) ),
			);

			$font_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
			wp_enqueue_style( 'bt_bb_google_fonts', $font_url, array(), '1.0.0' );

		}
	}
}

/**
 * Get array of data for a range of posts, used in grid layout
 *
 * @param int $number
 * @param int $offset
 * @param string $cat_slug Category slug
 * @param string $post_type
 * @param string $related
 * @param string $sticky_in_grid
 * @return array Array of data for a range of posts
 */
if ( ! function_exists( 'bt_bb_get_posts' ) ) {
	function bt_bb_get_posts( $number, $offset, $cat_slug, $post_type = 'post' ) {

		$posts_data1 = array();
		$posts_data2 = array();

		$sticky = true;
		$sticky_array = get_option( 'sticky_posts' );

		if ( $offset == 0 && $sticky && count( $sticky_array ) > 0 ) {
			$recent_posts_q_sticky = new WP_Query( array( 'post__in' => $sticky_array, 'post_status' => 'publish', 'ignore_sticky_posts' => 1 ) );
			$posts_data1 = bt_bb_get_posts_array( $recent_posts_q_sticky, $post_type, array() );
		}

		if ( $number > 0 ) {
			if ( $post_type == 'portfolio' ) {
				if ( $cat_slug != '' ) {
					$recent_posts_q = new WP_Query( array( 'post_type' => 'portfolio', 'posts_per_page' => $number, 'offset' => $offset, 'tax_query' => array( array( 'taxonomy' => 'portfolio_category', 'field' => 'slug', 'terms' => array( $cat_slug ) ) ), 'post_status' => 'publish' ) );
				} else {
					$recent_posts_q = new WP_Query( array( 'post_type' => 'portfolio', 'posts_per_page' => $number, 'offset' => $offset, 'post_status' => 'publish' ) );
				}
			} else {
				if ( $cat_slug != '' ) {
					$recent_posts_q = new WP_Query( array( 'posts_per_page' => $number, 'offset' => $offset, 'category_name' => $cat_slug, 'post_status' => 'publish' ) );
				} else {
					$recent_posts_q = new WP_Query( array( 'posts_per_page' => $number, 'offset' => $offset, 'post_status' => 'publish' ) );
				}
			}
		}

		if ( $sticky ) {
			$posts_data2 = bt_bb_get_posts_array( $recent_posts_q, $post_type, $sticky_array );
		} else {
			$posts_data2 = bt_bb_get_posts_array( $recent_posts_q, $post_type, array() );
		}		

		return array_merge( $posts_data1, $posts_data2 );

	}
}

/**
 * bt_bb_get_posts_data helper function
 *
 * @param object
 * @param array 
 * @return array 
 */
if ( ! function_exists( 'bt_bb_get_posts_array' ) ) {
	function bt_bb_get_posts_array( $recent_posts_q, $post_type, $sticky_arr ) {
		
		$posts_data = array();

		while ( $recent_posts_q->have_posts() ) {
			$recent_posts_q->the_post();
			$post = get_post();
			$post_author = $post->post_author;
			$post_id = get_the_ID();
			if ( in_array( $post_id, $sticky_arr ) ) {
				continue;
			}
			$posts_data[] = bt_bb_get_posts_array_item( $post_type, $post_id, $post_author );
		}
		
		wp_reset_postdata();
		
		return $posts_data;
	}
}

/**
 * boldthemes_get_posts_array helper function
 *
 * @return array
 */
if ( ! function_exists( 'bt_bb_get_posts_array_item' ) ) {
	function bt_bb_get_posts_array_item( $post_type = 'post', $post_id, $post_author ) {

		$post_data = array();
		$post_data['permalink'] = get_permalink( $post_id );
		$post_data['format'] = get_post_format( $post_id );
		$post_data['title'] = get_the_title( $post_id );

		$post_data['excerpt'] = get_post_field( 'post_excerpt', $post_id );

		$post_data['date'] = date_i18n( get_option( 'date_format' ), strtotime( get_the_time( 'Y-m-d', $post_id ) ) );

		$user_data = get_userdata( $post_author );
		if ( $user_data ) {
			$author = $user_data->data->display_name;
			$author_url = get_author_posts_url( $post_author );
			$post_data['author'] = '<a href="' . esc_url_raw( $author_url ) . '">' . esc_html( $author ) . '</a>';
		} else {
			$post_data['author'] = '';
		}

		if ( $post_type == 'portfolio' ) {
			$post_data['category'] = wp_get_post_terms( $post_id, 'portfolio_category' );
		} else {
			$post_data['category'] = get_the_category( $post_id );
		}

		$comments_open = comments_open( $post_id );
		$comments_number = get_comments_number( $post_id );
		if ( ! $comments_open && $comments_number == 0 ) {
			$comments_number = false;
		}			

		$post_data['comments'] = $comments_number;
		$post_data['ID'] = $post_id;
		
		return $post_data;
	}
}