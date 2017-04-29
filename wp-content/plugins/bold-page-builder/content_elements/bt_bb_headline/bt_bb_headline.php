<?php

class bt_bb_headline extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts', array(
			'headline'      => '',
			'html_tag'      => '',
			'font'          => '',
			'font_subset'   => '',
			'size'     		=> '',
			'font_size'     => '',
			'font_weight'   => '',
			'color_scheme'  => '',
			'color'         => '',
			'dash'          => '',
			'align'         => '',
			'url'           => '',
			'target'        => '',
			'superheadline' => '',
			'subheadline' => ''
		) ), $atts, $this->shortcode ) );

		if ( $font != '' && $font != 'inherit' ) {
			require_once( dirname(__FILE__) . '/../../content_elements_misc/misc.php' );
			bt_bb_enqueue_google_font( $font, $font_subset );
		}

		$class = array( $this->shortcode );
		
		if ( $el_class != '' ) {
			$class[] = $el_class;
		}

		$id_attr = '';
		if ( $el_id != '' ) {
			$id_attr = ' ' . 'id="' . $el_id . '"';
		}

		if ( $font != '' && $font != 'inherit' ) {
			$el_style = $el_style . ';' . 'font-family:\'' . urldecode( $font ) . '\'';
		}

		$html_tag_style = '';
		if ( $font_size != '' ) {
			$html_tag_style = ' ' . 'style="font-size:' . $font_size . '"';
		}
		
		if ( $font_weight != '' ) {
			$class[] = $this->prefix . 'font_weight_' . $font_weight ;
		}
		
		if ( $color_scheme != '' ) {
			$class[] = $this->prefix . 'color_scheme_' . bt_bb_get_color_scheme_id( $color_scheme );
		}

		if ( $color != '' ) {
			$el_style = $el_style . ';' . 'color:' . $color . ';border-color:' . $color . ';';
		}

		if ( $dash != '' ) {
			$class[] = $this->prefix . 'dash' . '_' . $dash;
		}
		
		if ( $size != '' ) {
			$class[] = $this->prefix . 'size' . '_' . $size;
		}

		if ( $superheadline != '' ) {
			$class[] = $this->prefix . 'superheadline';
			$superheadline = '<div class="' . $this->shortcode . '_superheadline">' . $superheadline . '</div>';
		}
		
		if ( $subheadline != '' ) {
			$class[] = $this->prefix . 'subheadline';
			$subheadline = '<div class="' . $this->shortcode . '_subheadline">' . $subheadline . '</div>';
		}

		$style_attr = '';
		if ( $el_style != '' ) {
			$style_attr = ' ' . 'style="' . $el_style . '"';
		}

		if ( $align != '' ) {
			$class[] = $this->prefix . 'align' . '_' . $align;
		}
		
		$headline = nl2br( $headline );

		if ( $url != '' ) {
			$headline = '<a href="' . $url . '" target="' . $target . '">' . $headline . '</a>';
		}		

		$headline = '<span class="' . $this->shortcode . '_content"><span>' . $headline . '</span></span>';

		$output = '<header' . $id_attr . ' class="' . implode( ' ', $class ) . '"' . $style_attr . '><' . $html_tag . $html_tag_style . '>' . $superheadline . $headline . '</' . $html_tag . '>' . $subheadline . '</header>';

		return $output;

	}

	function map_shortcode() {

		require_once( dirname(__FILE__) . '/../../content_elements_misc/fonts.php' );
		$color_scheme_arr = bt_bb_get_color_scheme_param_array();	

		bt_bb_map( $this->shortcode, array( 'name' => __( 'Headline', 'bold-builder' ), 'description' => __( 'Headline with custom Google fonts', 'bold-builder' ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode, 'highlight' => true,
			'params' => array(
				array( 'param_name' => 'superheadline', 'type' => 'textfield', 'heading' => __( 'Superheadline', 'bold-builder' ) ),
				array( 'param_name' => 'headline', 'type' => 'textarea', 'heading' => __( 'Headline', 'bold-builder' ), 'preview' => true, 'preview_strong' => true ),
				array( 'param_name' => 'subheadline', 'type' => 'textarea', 'heading' => __( 'Subheadline', 'bold-builder' ) ),
				array( 'param_name' => 'html_tag', 'type' => 'dropdown', 'heading' => __( 'HTML tag', 'bold-builder' ), 'preview' => true,
					'value' => array(
						__( 'h1', 'bold-builder' ) => 'h1',
						__( 'h2', 'bold-builder' ) => 'h2',
						__( 'h3', 'bold-builder' ) => 'h3',
						__( 'h4', 'bold-builder' ) => 'h4',
						__( 'h5', 'bold-builder' ) => 'h5',
						__( 'h6', 'bold-builder' ) => 'h6'
				) ),
				array( 'param_name' => 'size', 'type' => 'dropdown', 'heading' => __( 'Size', 'bold-builder' ), 'description' => 'Predefined heading sizes, independent of html tag',
					'value' => array(
						__( 'Inherit', 'bold-builder' ) => 'inherit',
						__( 'Extra Small', 'bold-builder' ) => 'extrasmall',
						__( 'Small', 'bold-builder' ) => 'small',
						__( 'Medium', 'bold-builder' ) => 'medium',
						__( 'Normal', 'bold-builder' ) => 'normal',
						__( 'Large', 'bold-builder' ) => 'large',
						__( 'Extra large', 'bold-builder' ) => 'extralarge'
					)
				),				
				array( 'param_name' => 'align', 'type' => 'dropdown', 'heading' => __( 'Alignment', 'bold-builder' ),
					'value' => array(
						__( 'Inherit', 'bold-builder' ) => 'inherit',
						__( 'Center', 'bold-builder' ) => 'center',
						__( 'Left', 'bold-builder' ) => 'left',
						__( 'Right', 'bold-builder' ) => 'right'
					)
				),
				array( 'param_name' => 'dash', 'type' => 'dropdown', 'heading' => __( 'Dash', 'bold-builder' ), 'group' => __( 'Design', 'bold-builder' ),
					'value' => array(
						__( 'None', 'bold-builder' ) => 'none',
						__( 'Top', 'bold-builder' ) => 'top',
						__( 'Bottom', 'bold-builder' ) => 'bottom',
						__( 'Top and bottom', 'bold-builder' ) => 'top_bottom'
					)
				),
				array( 'param_name' => 'color_scheme', 'type' => 'dropdown', 'heading' => __( 'Color scheme', 'bold-builder' ), 'group' => __( 'Design', 'bold-builder' ), 'value' => $color_scheme_arr, 'preview' => true ),
				array( 'param_name' => 'color', 'type' => 'colorpicker', 'heading' => __( 'Color', 'bold-builder' ), 'group' => __( 'Design', 'bold-builder' ), 'preview' => true ),
				array( 'param_name' => 'font', 'type' => 'dropdown', 'heading' => __( 'Font', 'bold-builder' ), 'group' => __( 'Font', 'bold-builder' ), 'preview' => true,
					'value' => array( __( 'Inherit', 'bold-builder' ) => 'inherit' ) + $font_arr
				),
				array( 'param_name' => 'font_subset', 'type' => 'textfield', 'heading' => __( 'Font subset', 'bold-builder' ), 'group' => __( 'Font', 'bold-builder' ), 'value' => 'latin,latin-ext', 'description' => 'E.g. latin,latin-ext,cyrillic,cyrillic-ext' ),
				array( 'param_name' => 'font_size', 'type' => 'textfield', 'heading' => __( 'Custom font size', 'bold-builder' ), 'group' => __( 'Font', 'bold-builder' ), 'description' => 'E.g. 20px or 1.5rem' ),
				array( 'param_name' => 'font_weight', 'type' => 'dropdown', 'heading' => __( 'Font weight', 'bold-builder' ), 'group' => __( 'Font', 'bold-builder' ),
					'value' => array(
						__( 'Default', 'bold-builder' ) => '',
						__( 'Normal', 'bold-builder' ) => 'normal',
						__( 'Bold', 'bold-builder' ) => 'bold',
						__( 'Bolder', 'bold-builder' ) => 'bolder',
						__( 'Lighter', 'bold-builder' ) => 'lighter'
					)
				),
				array( 'param_name' => 'url', 'type' => 'textfield', 'heading' => __( 'URL', 'bold-builder' ), 'group' => __( 'URL', 'bold-builder' ) ),
				array( 'param_name' => 'target', 'type' => 'dropdown', 'heading' => __( 'Target', 'bold-builder' ), 'group' => __( 'URL', 'bold-builder' ),
					'value' => array(
						__( 'Self (open in same tab)', 'bold-builder' ) => '_self',
						__( 'Blank (open in new tab)', 'bold-builder' ) => '_blank'
					)
				)
			)
		) );
	}
}