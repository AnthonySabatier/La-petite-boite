<?php

class bt_bb_section extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {

		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts', array(
			'layout'                => '',
			'full_screen'           => '',
			'vertical_align'        => '',
			'top_spacing'           => '',
			'bottom_spacing'        => '',
			'color_scheme'          => '',
			'background_color'      => '',
			'background_image'      => '',
			'background_overlay'    => '',
			'background_video_yt'   => '',
			'yt_video_settings'     => '',
			'background_video_mp4'  => '',
			'background_video_ogg'  => '',
			'background_video_webm' => '',
			'parallax'              => '',
			'parallax_offset'       => '',
		) ), $atts, $this->shortcode ) );

		$class = array( $this->shortcode );

		wp_enqueue_script(
			'bt_bb_googlemaps-scrollprevent',
			plugin_dir_url( __FILE__ ) . 'googlemaps-scrollprevent.min.js',
			array( 'jquery' ),
			'',
			true
		);

		wp_enqueue_script(
			'bt_bb_elements',
			plugin_dir_url( __FILE__ ) . 'bt_bb_elements.js',
			array( 'jquery' ),
			'',
			true
		);

		if ( $top_spacing != '' ) {
			$class[] = 'top_spacing' . '_' . $top_spacing;
		}

		if ( $bottom_spacing != '' ) {
			$class[] = 'bottom_spacing' . '_' . $bottom_spacing;
		}

		if ( $color_scheme != '' ) {
			$class[] = 'color_scheme_' . bt_bb_get_color_scheme_id( $color_scheme );
		}
		
		if ( $background_color != '' ) {
			$el_style = $el_style . ';' . 'background-color:' . $background_color . ';';
		}

		if ( $layout != '' ) {
			$class[] = 'layout' . '_' . $layout;
		}

		if ( $full_screen == 'yes' ) {
			$class[] = 'full_screen';
		}

		if ( $vertical_align != '' ) {
			$class[] = 'vertical_align' . '_' . $vertical_align;
		}

		$data_parallax_attr = '';
		if ( $parallax != '' && ! wp_is_mobile() ) {
			$data_parallax_attr = 'data-parallax="' . $parallax . '" data-parallax-offset="' . intval( $parallax_offset ) . '"';
			$class[] = 'parallax';
		}

		if ( $background_image != '' ) {
			$background_image = wp_get_attachment_image_src( $background_image, 'full' );
			$background_image_url = $background_image[0];
			$background_image_style = 'background-image:url(\'' . $background_image_url . '\');';
			$el_style = $background_image_style . $el_style;	
			$class[] = 'background_image';
		}

		if ( $background_overlay != '' ) {
			$class[] = 'background_overlay' . '_' . $background_overlay;
		}

		$id_attr = '';
		if ( $el_id == '' ) {
			$el_id = uniqid( 'bt_bb_section' );
		}
		$id_attr = 'id="' . $el_id . '"';

		$background_video_attr = '';

		$video_html = '';

		if ( $background_video_yt != '' && ! wp_is_mobile() ) {
			wp_enqueue_style( 'bt_bb_style_yt', plugin_dir_url( __FILE__ ) . 'YTPlayer.css' );
			wp_enqueue_script( 
				'bt_bb_yt',
				plugin_dir_url( __FILE__ ) . 'jquery.mb.YTPlayer.min.js',
				array( 'jquery' ),
				'',
				true
			);

			$class[] = 'background_video_yt';

			if ( $video_settings == '' ) {
				$video_settings = 'showControls:false,showYTLogo:false,mute:true,stopMovieOnBlur:false,opacity:1';
			}

			$background_video_attr = ' ' . 'data-property="{videoURL:\'' . $background_video_yt . '\',containment:\'self\',' . $video_settings . '}"';
			$proxy = new BT_BB_YT_Video_Proxy( $this->prefix );
			add_action( 'wp_footer', array( $proxy, 'js_init' ) );

		} else if ( ( $background_video_mp4 != '' || $background_video_ogg != '' || $background_video_webm != '' ) && ! wp_is_mobile() ) {
			$class[] = 'video';
			$video_html = '<video autoplay loop muted onplay="bt_bb_video_callback( this )">';
			if ( $background_video_mp4 != '' ) {
				$video_html .= '<source src="' . $background_video_mp4 . '" type="video/mp4">';
			}
			if ( $background_video_ogg != '' ) {
				$video_html .= '<source src="' . $background_video_ogg . '" type="video/ogg">';
			}
			if ( $background_video_webm != '' ) {
				$video_html .= '<source src="' . $background_video_webm . '" type="video/webm">';
			}
			$video_html .= '</video>';
		}

		$class_attr = implode( ' ' . $this->prefix, $class );

		if ( $el_class != '' ) {
			$class_attr = $class_attr . ' ' . $el_class;
		}

		$style_attr = '';
		if ( $el_style != '' ) {
			$style_attr = 'style="' . $el_style . '"';
		}

		$output = '<section ' . $id_attr . ' ' . $data_parallax_attr . ' class="' . $class_attr . '" ' . $style_attr . $background_video_attr . '>';
		$output .= $video_html;
			$output .= '<div class="' . $this->prefix . 'port">';
				$output .= '<div class="' . $this->prefix . 'cell">';
					$output .= '<div class="' . $this->prefix . 'cell_inner">';
					$output .= wptexturize( do_shortcode( $content ) );
					$output .= '</div>';
				$output .= '</div>';
		$output .= '</div>';

		$output .= '</section>';

		return $output;

	}

	function map_shortcode() {
		
		require_once( dirname(__FILE__) . '/../../content_elements_misc/misc.php' );
		$color_scheme_arr = bt_bb_get_color_scheme_param_array();

		bt_bb_map( $this->shortcode, array( 'name' => __( 'Section', 'bold-builder' ), 'description' => __( 'Basic root element', 'bold-builder' ), 'root' => true, 'container' => 'vertical', 'accept' => array( 'bt_bb_row' => true ), 'toggle' => true, 'auto_add' => 'bt_bb_row', 'show_settings_on_create' => false,
			'params' => array( 
				array( 'param_name' => 'layout', 'type' => 'dropdown', 'default' => 'boxed_1200', 'heading' => __( 'Layout', 'bold-builder' ), 'group' => __( 'General', 'bold-builder' ), 'weight' => 0, 'preview' => true,
					'value' => array(
						__( 'Boxed (800px)', 'bold-builder' ) => 'boxed_800',
						__( 'Boxed (900px)', 'bold-builder' ) => 'boxed_900',
						__( 'Boxed (1000px)', 'bold-builder' ) => 'boxed_1000',
						__( 'Boxed (1100px)', 'bold-builder' ) => 'boxed_1100',
						__( 'Boxed (1200px)', 'bold-builder' ) => 'boxed_1200',
						__( 'Wide', 'bold-builder' ) => 'wide'
					)
				),
				array( 'param_name' => 'top_spacing', 'type' => 'dropdown', 'heading' => __( 'Top spacing', 'bold-builder' ), 'preview' => true,
					'value' => array(
						__( 'No spacing', 'bold-builder' ) => '',
						__( 'Extra small', 'bold-builder' ) => 'extra_small',
						__( 'Small', 'bold-builder' ) => 'small',		
						__( 'Normal', 'bold-builder' ) => 'normal',
						__( 'Medium', 'bold-builder' ) => 'medium',
						__( 'Large', 'bold-builder' ) => 'large',
						__( 'Extra large', 'bold-builder' ) => 'extra_large'
					)
				),
				array( 'param_name' => 'bottom_spacing', 'type' => 'dropdown', 'heading' => __( 'Bottom spacing', 'bold-builder' ), 'preview' => true,
					'value' => array(
						__( 'No spacing', 'bold-builder' ) => '',
						__( 'Extra small', 'bold-builder' ) => 'extra_small',
						__( 'Small', 'bold-builder' ) => 'small',		
						__( 'Normal', 'bold-builder' ) => 'normal',
						__( 'Medium', 'bold-builder' ) => 'medium',
						__( 'Large', 'bold-builder' ) => 'large',
						__( 'Extra large', 'bold-builder' ) => 'extra_large'
					)
				),
				array( 'param_name' => 'full_screen', 'type' => 'dropdown', 'heading' => __( 'Full screen', 'bold-builder' ), 
					'value' => array(
						__( 'No', 'bold-builder' ) => '',
						__( 'Yes', 'bold-builder' ) => 'yes'
					)
				),
				array( 'param_name' => 'vertical_align', 'type' => 'dropdown', 'heading' => __( 'Vertical align (for fullscreen section)', 'bold-builder' ), 'preview' => true,
					'value' => array(
						__( 'Top', 'bold-builder' )     => 'top',
						__( 'Middle', 'bold-builder' )  => 'middle',
						__( 'Bottom', 'bold-builder' )  => 'bottom'					
					)
				),
				array( 'param_name' => 'color_scheme', 'type' => 'dropdown', 'heading' => __( 'Color scheme', 'bold-builder' ), 'value' => $color_scheme_arr, 'preview' => true, 'group' => __( 'Design', 'bold-builder' )  ),
				array( 'param_name' => 'background_color', 'type' => 'colorpicker', 'heading' => __( 'Background color', 'bold-builder' ), 'group' => __( 'Design', 'bold-builder' ), 'preview' => true ),
				array( 'param_name' => 'background_image', 'type' => 'attach_image',  'preview' => true, 'heading' => __( 'Background image', 'bold-builder' ), 'group' => __( 'Design', 'bold-builder' ) ),
				array( 'param_name' => 'background_overlay', 'type' => 'dropdown', 'heading' => __( 'Background overlay', 'bold-builder' ), 'group' => __( 'Design', 'bold-builder' ), 
					'value' => array(
						__( 'No overlay', 'bold-builder' )    => '',
						__( 'Light stripes', 'bold-builder' ) => 'light_stripes',
						__( 'Dark stripes', 'bold-builder' )  => 'dark_stripes',
						__( 'Light solid', 'bold-builder' )	  => 'light_solid',
						__( 'Dark solid', 'bold-builder' )	  => 'dark_solid'
					)
				),
				array( 'param_name' => 'parallax', 'type' => 'textfield', 'heading' => __( 'Parallax (e.g. -.7)', 'bold-builder' ), 'group' => __( 'Design', 'bold-builder' ) ),
				array( 'param_name' => 'parallax_offset', 'type' => 'textfield', 'heading' => __( 'Parallax offset in px (e.g. -100)', 'bold-builder' ), 'group' => __( 'Design', 'bold-builder' ) ),
				array( 'param_name' => 'background_video_yt', 'type' => 'textfield', 'heading' => __( 'YouTube background video', 'bold-builder' ), 'group' => __( 'Video', 'bold-builder' ) ),
				array( 'param_name' => 'yt_video_settings', 'type' => 'textfield', 'heading' => __( 'Video settings (e.g. startAt:20, mute:true, stopMovieOnBlur:false)', 'bold-builder' ), 'group' => __( 'Video', 'bold-builder' ) ),
				array( 'param_name' => 'background_video_mp4', 'type' => 'textfield', 'heading' => __( 'MP4 background video', 'bold-builder' ), 'group' => __( 'Video', 'bold-builder' ) ),
				array( 'param_name' => 'background_video_ogg', 'type' => 'textfield', 'heading' => __( 'OGG background video', 'bold-builder' ), 'group' => __( 'Video', 'bold-builder' ) ),
				array( 'param_name' => 'background_video_webm', 'type' => 'textfield', 'heading' => __( 'WEBM background video', 'bold-builder' ), 'group' => __( 'Video', 'bold-builder' ) )
			)
		) );		

	} 

}

class BT_BB_YT_Video_Proxy {
	function __construct( $prefix ) {
		$this->prefix = $prefix;
	}
	public function js_init() { ?>
		<script>
			jQuery(function() {
				jQuery( '.<?php echo $this->prefix; ?>background_video_yt' ).YTPlayer();
			});
		</script>
	<?php }
}