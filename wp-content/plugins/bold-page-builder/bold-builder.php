<?php

/**
 * Plugin Name: Bold Builder
 * Description: WordPress content builder.
 * Version:  1.2.0
 * Author: BoldThemes
 * Author URI: http://www.bold-themes.com
 */
 
 /**
 * Enqueue scripts and styles
 */

if ( file_exists( plugin_dir_path( __FILE__ ) . 'css-crush/CssCrush.php' ) ) {
	require_once( 'css-crush/CssCrush.php' );
}

if ( file_exists( get_template_directory() . '/bt_bb_config.php' ) ) {
	require_once( get_template_directory() . '/bt_bb_config.php' );
}
	
add_filter( 'the_content', 'bt_bb_parse_content', 20 );
function bt_bb_parse_content( $content ) {
	return '<div class="bt_bb_wrapper">' . $content . '</div>';
}

if ( ! class_exists( 'BoldThemes_BB_Settings' ) || ! BoldThemes_BB_Settings::$custom_content_elements ) {
	require_once( 'export_import/export_import.php' );
}
	
function bt_bb_parse_content_admin( $content ) {
	
	global $bt_bb_map;
	
	foreach( BT_BB_Root::$elements as $base => $params ) {
		$proxy = new BT_BB_FE_Map_Proxy( $base, $params );
		$proxy->js_map();
	}
	
	global $bt_bb_array;
	$bt_bb_array = array();

	bt_bb_do_shortcode( $content );

	global $bt_bb_fe_array;
	$bt_bb_fe_array = array();
	
	global $bt_bb_fe_array_depth;
	$bt_bb_fe_array_depth = -1;
	
	bt_bb_fe_do_shortcode( $bt_bb_array );

	$wrap_arr = array();
	
	$count = 0;

	foreach( $bt_bb_fe_array as $item ) {

		if ( isset( $bt_bb_map[ $item['base'] ] ) && isset( $bt_bb_map[ $item['base'] ]['root'] ) && $bt_bb_map[ $item['base'] ]['root'] === true && $item['depth'] == 0 ) {
			$count++;
			$fe_wrap_open = '<div class="bt_bb_fe_wrap"><span class="bt_bb_fe_count"><span class="bt_bb_fe_count_inner" data-edit_url="' . get_edit_post_link( get_the_ID(), '' ) . '">' . $count . '</span></span>';
			$fe_wrap_close = '</div>';
		} else {
			$fe_wrap_open = '';
			$fe_wrap_close = '';
		}

		$depth = $item['depth'];

		if ( isset( $wrap_arr[ 'd' . ( $depth + 1 ) ] ) ) {
			if ( isset( $wrap_arr[ 'd' . $depth  ] ) ) {
				$wrap_arr[ 'd' . $depth  ] = $wrap_arr[ 'd' . $depth  ] . $fe_wrap_open . $item['sc'] . $wrap_arr[ 'd' . ( $depth + 1 ) ] . $item['sc_close'] . $fe_wrap_close;
			} else {
				$wrap_arr[ 'd' . $depth  ] = $fe_wrap_open . $item['sc'] . $wrap_arr[ 'd' . ( $depth + 1 ) ] . $item['sc_close'] . $fe_wrap_close;
			}
			unset( $wrap_arr[ 'd' . ( $depth + 1 ) ] );
		} else {
			if ( isset( $wrap_arr[ 'd' . $depth  ] ) ) {
				$wrap_arr[ 'd' . $depth  ] = $wrap_arr[ 'd' . $depth  ] . $fe_wrap_open . $item['sc'] . $item['sc_close'] . $fe_wrap_close;
			} else {
				$wrap_arr[ 'd' . $depth  ] = $fe_wrap_open . $item['sc'] . $item['sc_close'] . $fe_wrap_close;
			}
		}

	}		

	if ( isset( $wrap_arr['d0'] ) ) {
		return $wrap_arr['d0'];
	} else {
		return $content;
	}
	
}

function bt_bb_fe_do_shortcode( $bt_bb_array ) {
	
	global $bt_bb_fe_array;
	global $bt_bb_fe_array_depth;
	
	$bt_bb_fe_array_depth++;
	
	foreach( $bt_bb_array as $item ) {
		
		$sc = '';
		
		if ( $item['base'] == '_content' ) {
			
			$sc = $item['content'];
			
			$sc_close = '';
			
		} else {
		
			$sc = '[';
			
			$sc .= $item['base'];
			
			if ( isset( $item['attr'] ) ) {
				$attr_obj = bt_bb_json_decode( $item['attr'] );
				foreach( $attr_obj as $key => $value ) {
					$sc .= ' ' . $key . '="' . $value . '"';
				}
			}
			
			$sc .= ']';
			
			if ( isset( $item['children'] ) ) {
				bt_bb_fe_do_shortcode( $item['children'] );
				$bt_bb_fe_array_depth--;
			}
			
			$sc_close = '[/' . $item['base'] . ']';
		
		}
		
		$bt_bb_fe_array[] = array( 
			'base' => $item['base'],
			'sc' => $sc,
			'sc_close' => $sc_close,
			'container' => count( $item['children'] ) > 0 ? 'yes' : 'no',
			'depth' => $bt_bb_fe_array_depth
		);
		
	}
}
	
class BT_BB_Root {
	public static $elements = array();
}
 
function bt_bb_enqueue() {
	$screen = get_current_screen();
	if ( $screen->base != 'post' ) {
		return;
	}

	if ( function_exists( 'csscrush_file' ) ) {
		csscrush_file( plugin_dir_path( __FILE__ ) . 'css/style.css', array( 'source_map' => true, 'minify' => false, 'output_file' => 'style.crush', 'formatter' => 'block', 'boilerplate' => false, 'plugins' => array( 'loop', 'ease' ) ) );
	}

	wp_enqueue_style( 'bt_bb_font-awesome.min', plugins_url( 'css/font-awesome.min.css', __FILE__ ) );
	wp_enqueue_style( 'bt_bb', plugins_url( 'css/style.crush.css', __FILE__ ) );
	wp_enqueue_script( 'bt_bb_react', plugins_url( 'react.min.js', __FILE__ ) );
	wp_enqueue_script( 'bt_bb', plugins_url( 'script.min.js', __FILE__ ), array( 'jquery' ), true );
	wp_enqueue_script( 'bt_bb_jsx', plugins_url( 'build/jsx.min.js', __FILE__ ), array( 'jquery' ), true );
	wp_enqueue_script( 'bt_bb_autosize', plugins_url( 'autosize.min.js', __FILE__ ) );
	wp_enqueue_script( 'bt_bb_imagesloaded', plugins_url( 'imagesloaded.pkgd.min.js', __FILE__ ), array( 'jquery' ), false );
	wp_enqueue_script( 'wp-color-picker' );
	wp_enqueue_style( 'wp-color-picker' );
}
add_action( 'admin_enqueue_scripts', 'bt_bb_enqueue' );

function bt_bb_enqueue_fe_always() {
	wp_enqueue_style( 'bt_bb_dummy', plugins_url( 'css/front_end/dummy.css', __FILE__ ) );
	$post_id = get_the_ID();
	$opt_arr = get_option( 'bt_bb_custom_css' );
	if ( isset( $opt_arr[ $post_id ] ) ) {
		wp_add_inline_style( 'bt_bb_dummy', $opt_arr[ $post_id ] );
	}
	if ( function_exists( 'bt_bb_add_color_schemes' ) ) {
		bt_bb_add_color_schemes();
	}
	wp_enqueue_script( 'bt_bb_fe', plugins_url( 'script_fe.js', __FILE__ ), array( 'jquery' ), true );
}

function bt_bb_enqueue_fe() {
	if ( function_exists( 'csscrush_file' ) ) {
		csscrush_file( plugin_dir_path( __FILE__ ) . 'css/front_end/style.css', array( 'source_map' => true, 'minify' => false, 'output_file' => 'style.crush', 'formatter' => 'block', 'boilerplate' => false, 'plugins' => array( 'loop', 'ease' ) ) );
	}
	wp_enqueue_style( 'bt_bb', plugins_url( 'css/front_end/style.crush.css', __FILE__ ) );
}

function bt_bb_enqueue_content_elements() {
	if ( function_exists( 'csscrush_file' ) ) {
		csscrush_file( plugin_dir_path( __FILE__ ) . 'css/front_end/content_elements.css', array( 'source_map' => true, 'minify' => false, 'output_file' => 'content_elements.crush', 'formatter' => 'block', 'boilerplate' => false, 'plugins' => array( 'loop', 'ease' ) ) );
	}
	wp_enqueue_style( 'bt_bb_content_elements', plugins_url( 'css/front_end/content_elements.crush.css', __FILE__ ) );
	
	wp_enqueue_script( 
		'bt_bb_slick',
		plugins_url( 'slick/slick.min.js', __FILE__ ),
		array( 'jquery' ),
		'',
		true
	);
	wp_enqueue_style( 'bt_bb_slick', plugins_url( 'slick/slick.css', __FILE__ ) );

	wp_enqueue_script(
		'bt_bb_magnific',
		plugins_url( 'content_elements_misc/js/jquery.magnific-popup.min.js', __FILE__ ),
		array( 'jquery' )
	);

	wp_enqueue_script( 'bt_bb', plugins_url( 'content_elements_misc/js/content_elements.js', __FILE__ ), array( 'jquery' ), true );
	
	$post_id = get_the_ID();

}

function bt_bb_activate() {
	$options = get_option( 'bt_bb_settings' );
	if ( ! $options ) {
		update_option( 'bt_bb_settings', array( 'tag_as_name' => '0', 'color_schemes' => "Black/White;#000;#fff\r\nWhite/Black;#fff;#000" ) );
	}
}
register_activation_hook( __FILE__, 'bt_bb_activate' );

function bt_bb_deactivate() {
	//delete_option( 'bt_bb_settings' );
}
register_deactivation_hook( __FILE__, 'bt_bb_deactivate' );

function bt_bb_admin_init() {
    register_setting( 'bt_bb_settings', 'bt_bb_settings' );
}
add_action( 'admin_init', 'bt_bb_admin_init' );

function bt_bb_load_plugin_textdomain() {

	$domain = 'bold-builder';
	$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

	load_plugin_textdomain( $domain, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

}
add_action( 'init', 'bt_bb_load_plugin_textdomain' );

add_action( 'wp_ajax_bt_bb_delete_mapping', 'bt_bb_delete_mapping' );
add_action( 'wp_ajax_nopriv_bt_bb_delete_mapping', 'bt_bb_delete_mapping' );

/**
 * Save custom css
 */
 
 function bt_bb_save_custom_css() {
	$post_id = intval( $_POST['post_id'] );
	$css = wp_kses_post( $_POST['css'] );
	$opt_arr = get_option( 'bt_bb_custom_css' );
	if ( ! is_array( $opt_arr ) ) {
		$opt_arr = array();
	}
	if ( $css != '' ) {
		$opt_arr[ $post_id ] = $css;
	} else {
		unset( $opt_arr[ $post_id ] );
	}
	update_option( 'bt_bb_custom_css', $opt_arr );
	echo 'ok';
	die();
}

add_action( 'wp_ajax_bt_bb_save_custom_css', 'bt_bb_save_custom_css' );
add_action( 'wp_ajax_nopriv_bt_bb_save_custom_css', 'bt_bb_save_custom_css' );

/**
 * Get custom css
 */
 
 function bt_bb_get_custom_css() {
	$post_id = intval( $_POST['post_id'] );
	$opt_arr = get_option( 'bt_bb_custom_css' );
	if ( isset( $opt_arr[ $post_id ] ) ) {
		echo $opt_arr[ $post_id ];
	}
	die();
}

add_action( 'wp_ajax_bt_bb_get_custom_css', 'bt_bb_get_custom_css' );
add_action( 'wp_ajax_nopriv_bt_bb_get_custom_css', 'bt_bb_get_custom_css' );

/**
 * Settings menu
 */

function bt_bb_menu() {
	add_options_page( __( 'Bold Builder Settings', 'bold-builder' ), __( 'Bold Builder', 'bold-builder' ), 'manage_options', 'bt_bb_settings', 'bt_bb_settings' );
}
add_action( 'admin_menu', 'bt_bb_menu' );

/**
 * Settings page
 */
function bt_bb_settings() {
	
	$options = get_option( 'bt_bb_settings' );
	$tag_as_name = $options['tag_as_name'];
	
	$color_schemes = $options['color_schemes'];
	
	$post_types = get_post_types( array( 'public' => true, 'show_in_nav_menus' => true ) );

	?>
		<div class="wrap">
			<h2><?php _e( 'Bold Builder Settings', 'bold-builder' ); ?></h2>
			<form method="post" action="options.php">
				<?php settings_fields( 'bt_bb_settings' ); ?>
				<table class="form-table">
					<tbody>
					<tr>
						<th scope="row"><?php _e( 'Show shortcode tag instead of mapped name', 'bold-builder' ); ?></th>
						<td><fieldset><legend class="screen-reader-text"><span><?php _e( 'Show shortcode tags instead of mapped names', 'bold-builder' ); ?></span></legend>
						<p><label><input name="bt_bb_settings[tag_as_name]" type="radio" value="0" <?php echo $tag_as_name != '1' ? 'checked="checked"' : ''; ?>> <?php _e( 'No', 'bold-builder' ); ?></label><br>
						<label><input name="bt_bb_settings[tag_as_name]" type="radio" value="1" <?php echo $tag_as_name == '1' ? 'checked="checked"' : ''; ?>> <?php _e( 'Yes', 'bold-builder' ); ?></label></p>
						</fieldset></td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Post Types', 'bold-builder' ); ?></th>
						<td><fieldset><legend class="screen-reader-text"><span><?php _e( 'Post Types', 'bold-builder' ); ?></span></legend>
						<p>
						<?php 
						$n = 0;
						foreach( $post_types as $pt ) {
							$n++;
							$checked = '';
							if ( ! $options || ( ! array_key_exists( $pt, $options ) || ( array_key_exists( $pt, $options ) && $options[ $pt ] == '1' ) ) ) {
								$checked = ' ' . 'checked="checked"';
							}
							echo '<input type="hidden" name="bt_bb_settings[' . $pt . ']" value="0">';
							echo '<label><input name="bt_bb_settings[' . $pt . ']" type="checkbox" value="1"' . $checked . '> ' . $pt . '</label>';
							if ( $n < count( $post_types ) ) echo '<br>';
						} ?>
						</p>
						</fieldset></td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Color Schemes', 'bold-builder' ); ?></th>
						<td><fieldset><legend class="screen-reader-text"><span><?php _e( 'Color Schemes', 'bold-builder' ); ?></span></legend>
						<p>
						<textarea name="bt_bb_settings[color_schemes]" rows="10" cols="50"><?php echo $color_schemes; ?></textarea>
						</p>
						</fieldset></td>
					</tr>					
					</tbody>
				</table>

				<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Save', 'bold-builder' ); ?>"></p>
			</form>
		</div>
	<?php

}

/**
 * Settings
 */

function bt_bb_js_settings() {
	$screen = get_current_screen();
	if ( $screen->base != 'post' ) {
        return;
    }
	
	$options = get_option( 'bt_bb_settings' );
	$tag_as_name = $options['tag_as_name'];
	
	echo '<script>';
	echo 'window.bt_bb_plugins_url = "' . plugins_url() . '";';
	echo 'window.bt_bb_loading_gif_url = "' . plugins_url( 'img/ajax-loader.gif', __FILE__ ) . '";';
	echo 'window.bt_bb_settings = [];';
	echo 'window.bt_bb_settings.tag_as_name = "' . esc_js( $tag_as_name ) . '";';
	
	echo 'window.BTAJAXURL = "' . esc_js( admin_url( 'admin-ajax.php' ) ) . '";';
	
	global $shortcode_tags;
	$all_sc = $shortcode_tags;
	ksort( $all_sc );
	
	echo 'window.bt_bb.all_sc = ' . bt_bb_json_encode( array_keys( $all_sc ) ) . ';';

	global $bt_bb_is_bb_content;
	if ( $bt_bb_is_bb_content === 'true' || $bt_bb_is_bb_content === 'false' ) {
		echo 'window.bt_bb.is_bb_content = ' . $bt_bb_is_bb_content . ';';
	}
	
	echo '</script>';
}
add_action( 'admin_footer', 'bt_bb_js_settings' );

/**
 * Translate
 */

function bt_bb_translate() {
	echo '<script>';
	echo 'window.bt_bb_text = [];';
	echo 'window.bt_bb_text.toggle = "' . __( 'Toggle', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.add = "' . __( 'Add', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.edit = "' . __( 'Edit', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.edit_content = "' . __( 'Edit Content', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.clone = "' . __( 'Clone', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.delete = "' . __( 'Delete', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.layout_error = "' . __( 'Layout error!', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.add_element = "' . __( 'Add Element', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.select_layout = "' . __( 'Select Layout', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.select = "' . __( 'Select', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.submit = "' . __( 'Submit', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.copy = "' . __( 'Copy', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.copy_plus = "' . __( 'Copy +', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.paste = "' . __( 'Paste', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.export = "' . __( 'Export', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.import = "' . __( 'Import', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.not_allowed = "' . __( 'Not allowed!', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.manage_cb = "' . __( 'Manage Clipboard', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.filter = "' . __( 'Filter...', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.sc_mapper = "' . __( 'Shortcode Mapper', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.insert_mapping = "' . __( 'Insert Mapping', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.save = "' . __( 'Save', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.switch_editor = "' . __( 'Switch Editor', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.custom_css = "' . __( 'Custom CSS', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.switch_editor_confirm = "' . __( 'Are you sure you want to switch editor?', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.general = "' . __( 'General', 'bold-builder' ) . '";';
	
	echo '</script>';
}
add_action( 'admin_footer', 'bt_bb_translate' );
 
/**
 * Map shortcodes
 */
 
global $bt_bb_map;
$bt_bb_map = array();

function bt_bb_map_js() {
	
	if ( is_admin() ) { // back end
		$screen = get_current_screen();
		if ( ! is_object( $screen ) || $screen->base != 'post' ) {
			return;
		}
	}
	
	global $bt_bb_map;
	echo '<script>';
		BT_BB_Root::$elements = apply_filters( 'bt_bb_elements', BT_BB_Root::$elements );
		foreach( BT_BB_Root::$elements as $base => $params ) {
			$proxy = new BT_BB_Map_Proxy( $base, $params );
			$proxy->js_map();
		}
	echo '</script>';
	echo '<script>';
		$opt_arr = get_option( 'bt_bb_mapping_secondary' );
		if ( is_array( $opt_arr ) ) {
			foreach( $opt_arr as $k => $v ) {
				if ( shortcode_exists( $k ) ) {
					echo 'window.bt_bb_map["' . $k . '"] = ' . stripslashes( $v ) . ';';
					$bt_bb_map[ $k ] = json_decode( stripslashes( $v ), true );
					echo 'window.bt_bb_map_secondary["' . $k . '"] = true;';
				}
			}
		}
	echo '</script>';
}
add_action( 'admin_head', 'bt_bb_map_js' );

function bt_bb_fe_action() {
	add_action( 'wp_enqueue_scripts', 'bt_bb_enqueue_fe' );
	add_filter( 'the_content', 'bt_bb_parse_content_admin' );
}
add_action( 'admin_bar_init', 'bt_bb_fe_action' );

if ( ! class_exists( 'BoldThemes_BB_Settings' ) || ! BoldThemes_BB_Settings::$custom_content_elements ) {
	add_action( 'wp_enqueue_scripts', 'bt_bb_enqueue_content_elements' );
}

add_action( 'wp_enqueue_scripts', 'bt_bb_enqueue_fe_always' );

/**
 * Map shortcode elements
 */
//function bt_bb_map( $base, $params, $priority = 10 ) {
function bt_bb_map( $base, $params ) {
	$i = 0;
	if ( isset( $params['params'] ) ) {
		foreach( $params['params'] as $param ) {
			if ( ! isset( $param['weight'] ) ) {
				$params['params'][ $i ]['weight'] = $i;
			}
			$i++;
		}
	}
	BT_BB_Root::$elements[ $base ] = $params;
}

add_action( 'plugins_loaded', 'bt_rc_map_plugins_loaded', 5 );
function bt_rc_map_plugins_loaded() {
	if ( ! function_exists( 'bt_rc_map' ) ) {
		function bt_rc_map( $base, $params ) {
			bt_bb_map( $base, $params );
		}
	}
}

/* Deactivate RC */
register_activation_hook( __FILE__, 'bt_deactivate_rc' );
function bt_deactivate_rc () {
	deactivate_plugins( 'rapid_composer/rapid_composer.php' );
}

/**
 * Modify map
 */
function bt_bb_map_modify( $base, $params ) {
	$i = 0;
	if ( isset( $params['params'] ) ) {
		foreach( $params['params'] as $param ) {
			if ( ! isset( $param['weight'] ) ) {
				$params['params'][ $i ]['weight'] = $i;
			}
			$i++;
		}
	}
	foreach( $params as $key => $value ) {
		BT_BB_Root::$elements[ $base ][ $key ] = $value;
	}
}

/**
 * Add element param(s)
 */
function bt_bb_add_params( $base, $params ) {
	$proxy = new BT_BB_Add_Params_Proxy( $base, $params );
	add_filter( 'bt_bb_elements', array( $proxy, 'add_params' ) );
}

/**
 * Remove element param(s)
 */
function bt_bb_remove_params( $base, $params ) {
	$proxy = new BT_BB_Remove_Params_Proxy( $base, $params );
	add_filter( 'bt_bb_elements', array( $proxy, 'remove_params' ) );
}

global $bt_bb_root_base;
$bt_bb_root_base = array();

class BT_BB_Map_Proxy {
	function __construct( $base, $params ) {
		$this->base = $base;
		$params['base'] = $base;
		$this->params = $params;
	}

	public function js_map() {
		global $bt_bb_map;
		if ( shortcode_exists( $this->base ) ) {
			if ( isset( $this->params['admin_enqueue_css'] ) ) {
				foreach( $this->params['admin_enqueue_css'] as $item ) {
					wp_enqueue_style( 'bt_bb_admin_' . uniqid(), $item );
				}
			}
			echo 'window.bt_bb_map["' . $this->base . '"] = window.bt_bb_map_primary.' . $this->base . ' = ' . bt_bb_json_encode( $this->params ) . ';';
			$bt_bb_map[ $this->base ] = $this->params;
		}
	}
}

class BT_BB_FE_Map_Proxy {
	function __construct( $base, $params ) {
		$this->base = $base;
		$params['base'] = $base;
		$this->params = $params;

		global $bt_bb_root_base;
		if ( isset( $params['root'] ) && $params['root'] === true && isset( $params['base'] ) ) {
			$bt_bb_root_base[] = $params['base'];
		}
	}

	public function js_map() {
		global $bt_bb_map;
		if ( shortcode_exists( $this->base ) ) {
			$bt_bb_map[ $this->base ] = $this->params;
		}
	}
}

class BT_BB_Add_Params_Proxy {
	function __construct( $base, $params ) {
		$this->base = $base;
		$this->params = $params;
	}
	public function add_params( $elements ) {
		foreach( $this->params as $param ) {
			$last = end( $elements[ $this->base ]['params'] );
			if ( ! is_array( $param ) ) {
				if ( ! isset( $this->params['weight'] ) ) {
					if ( isset( $last['weight'] ) ) {
						$this->params['weight'] = $last['weight'] + 1;
					} else {
						$this->params['weight'] = 0;
					}
				}
				$elements[ $this->base ]['params'][] = $this->params;
				break;
			} else {
				if ( ! isset( $param['weight'] ) ) {
					if ( isset( $last['weight'] ) ) {
						$param['weight'] = $last['weight'] + 1;
					} else {
						$param['weight'] = 0;
					}
				}
				$elements[ $this->base ]['params'][] = $param;
			}
		}

		usort( $elements[ $this->base ]['params'], array( $this, 'sort_by_weight' ) );

		return $elements;
	}
	private function sort_by_weight( $a, $b ) {
		return $a['weight'] > $b['weight'];
	}
}

class BT_BB_Remove_Params_Proxy {
	function __construct( $base, $params ) {
		$this->base = $base;
		$this->params = $params;
	}
	public function remove_params( $elements ) {
		if ( is_array( $this->params ) ) {
			foreach( $this->params as $param ) {
				$i = 0;
				foreach( $elements[ $this->base ]['params'] as $element_param ) {
					if ( $element_param['param_name'] == $param ) {
						unset( $elements[ $this->base ]['params'][ $i ] );
					}
					$i++;
				}
				$elements[ $this->base ]['params'] = array_values( $elements[ $this->base ]['params'] );
			}
		} else {
			$i = 0;
			foreach( $elements[ $this->base ]['params'] as $element_param ) {
				if ( $element_param['param_name'] == $this->params ) {
					unset( $elements[ $this->base ]['params'][ $i ] );
				}
				$i++;
			}
			$elements[ $this->base ]['params'] = array_values( $elements[ $this->base ]['params'] );
		}
		return $elements;
	}
}

/**
 * Remove wpautop
 */
function bt_bb_wpautop( $content ) {
	foreach( BT_BB_Root::$elements as $base => $params ) {
		$proxy = new BT_BB_FE_Map_Proxy( $base, $params );
	}	
	global $bt_bb_root_base;
	$bt_bb_content = false;
	foreach( $bt_bb_root_base as $item ) {
		if ( strpos( $content, '[' . $item ) === 0 ) {
			$bt_bb_content = true;
			break;
		}
	}
	if ( $bt_bb_content ) {
		remove_filter( 'the_content', 'wpautop' );
	} else if ( ! has_filter( 'the_content', 'wpautop' ) ) {
		add_filter( 'the_content', 'wpautop' );
	}
	return $content;
}
add_filter( 'the_content', 'bt_bb_wpautop', 1 );

/**
 * Adds a box to the main column on the Post and Page edit screens.
 */
function bt_bb_add_meta_box() {

	$options = get_option( 'bt_bb_settings' );
	$post_types = get_post_types( array( 'public' => true, 'show_in_nav_menus' => true ) );
	$active_pt = array();
	foreach( $post_types as $pt ) {
		if ( ! $options || ( ! array_key_exists( $pt, $options ) || ( array_key_exists( $pt, $options ) && $options[ $pt ] == '1' ) ) ) {
			$active_pt[] = $pt;
		}
	}

	$screens = $active_pt;

	foreach( $screens as $screen ) {
		add_meta_box(
			'bt_bb_sectionid',
			__( 'Bold Builder', 'bold-builder' ),
			'bt_bb_meta_box_callback',
			$screen,
			'normal',
			'high'
		);
	}
}
add_action( 'add_meta_boxes', 'bt_bb_add_meta_box' );

/**
 * Get size information for all currently-registered image sizes.
 *
 * @global $_wp_additional_image_sizes
 * @uses   get_intermediate_image_sizes()
 * @return array $sizes Data for all currently-registered image sizes.
 */
function bt_bb_get_image_sizes() {
	global $_wp_additional_image_sizes;

	foreach ( get_intermediate_image_sizes() as $_size ) {
		$sizes[ $_size ] = $_size;
		/*if ( in_array( $_size, array('thumbnail', 'medium', 'medium_large', 'large') ) ) {
			$sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
			$sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
			$sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );
		} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
			$sizes[ $_size ] = array(
				'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
				'height' => $_wp_additional_image_sizes[ $_size ]['height'],
				'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
			);
		}*/
	}

	$sizes[ 'full' ] = 'full';

	return array_reverse( $sizes );
}

/**
 * Initial data.
 */
class BT_BB_Data_Proxy {
	function __construct( $data ) {
		$this->data = $data;
	}

	public function js() {
		echo '<script>
			var bt_bb_data = {	
				title: "_root",
				base: "_root",
				key: "' . uniqid( 'bt_bb_' ) . '",
				children: ' . $this->data . '
			}
		</script>';
	}
}

function bt_bb_force_tinymce() {
    return 'tinymce';
}
add_filter( 'wp_default_editor', 'bt_bb_force_tinymce' );

/**
 * Prints the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */
function bt_bb_meta_box_callback( $post ) {

	// Add an nonce field so we can check for it later.
	wp_nonce_field( 'bt_bb_meta_box', 'bt_bb_meta_box_nonce' );

	/*
	 * Use get_post_meta() to retrieve an existing value
	 * from the database and use the value for the form.
	 */
	$value = get_post_meta( $post->ID, '_my_meta_value_key', true );

	$content = $post->post_content;

	global $bt_bb_array;
	$bt_bb_array = array();

	bt_bb_do_shortcode( $content );

	$json_content = bt_bb_json_encode( $bt_bb_array );
	
	global $bt_bb_is_bb_content;
	if ( $json_content == '[]' ) {
		$bt_bb_is_bb_content = 'false';
	} else {
		$bt_bb_is_bb_content = 'true';
	}
	
	echo '<div id="bt_bb"></div><div id="bt_bb_add_root"><i></i></div>';
	
	bt_bb_edit_dialog();
	
	echo '<div id="bt_bb_front_end_preview" class="bt_bb_dialog"><span class="bt_bb_front_end_preview_close bt_bb_dialog_close"></span><iframe name="bt_bb_front_end_preview_iframe"></iframe></div>';
	
	echo '<div id="bt_bb_main_toolbar">';
	echo '<i class="bt_bb_undo" title="' . __( 'Undo', 'bold-builder' ) . '"></i>';
	echo '<i class="bt_bb_redo" title="' . __( 'Redo', 'bold-builder' ) . '"></i>';
		echo '<span class="bt_bb_separator">|</span>';
	echo '<i class="bt_bb_paste_root" title="' . __( 'Paste', 'bold-builder' ) . '"></i>';
	echo '<span class="bt_bb_cb_items"></span>';
	echo '<i class="bt_bb_manage_clipboard" title="' . __( 'Clipboard Manager', 'bold-builder' ) . '"></i>';
		echo '<span class="bt_bb_separator">|</span>';
	echo '<i class="bt_bb_preview" title="' . __( 'Preview', 'bold-builder' ) . '"></i>';
	echo '<i class="bt_bb_save" title="' . __( 'Save', 'bold-builder' ) . '"></i>';
	echo '</div>';

	add_action( 'admin_footer', array( new BT_BB_Data_Proxy( $json_content ), 'js' ) );

}

function bt_bb_do_shortcode( $content ) {
	global $shortcode_tags;
	if ( ! ( ( empty( $shortcode_tags ) || ! is_array( $shortcode_tags ) ) ) ) {
		$pattern = get_shortcode_regex();
		
		global $bt_bb_array;
		
		$callback = new BT_BB_Callback( $bt_bb_array );
		
		$preg_cb = preg_replace_callback( "/$pattern/s", array( $callback, 'bt_bb_do_shortcode_tag' ), $content );
	}
}

class BT_BB_Callback {

	private $bt_bb_array;

    function __construct( &$bt_bb_array ) {
        $this->bt_bb_array = &$bt_bb_array;
    }

	function bt_bb_do_shortcode_tag( $m ) {
		global $shortcode_tags;
		
		global $bt_bb_map;

		// allow [[foo]] syntax for escaping a tag
		if ( $m[1] == '[' && $m[6] == ']' ) {
			return $m[0];
		}

		$tag = $m[2];
		$attr = shortcode_parse_atts( $m[3] );

		if ( is_array( $attr ) ) {
			$this->bt_bb_array[] = array( 'title' => $tag, 'base' => $tag, 'key' => str_replace( '.', '', uniqid( 'bt_bb_', true ) ), 'attr' => bt_bb_json_encode( $attr ), 'children' => array() );
		} else {
			$this->bt_bb_array[] = array( 'title' => $tag, 'base' => $tag, 'key' => str_replace( '.', '', uniqid( 'bt_bb_', true ) ), 'children' => array() );
		}

		if ( isset( $m[5] ) && $m[5] != '' ) {
			// enclosing tag - extra parameter
			$pattern = get_shortcode_regex();
			
			if ( isset( $bt_bb_map[ $m[2] ]['accept']['_content'] ) && $bt_bb_map[ $m[2] ]['accept']['_content'] ) {
				$r = $m[5];
			} else {
				$callback = new BT_BB_Callback( $this->bt_bb_array[ count( $this->bt_bb_array ) - 1 ]['children'] );
				$r = preg_replace_callback( "/$pattern/s", array( $callback, 'bt_bb_do_shortcode_tag' ), $m[5] );
				$r = trim( $r );
			}
		
			if ( $r != '' ) {
				$this->bt_bb_array[ count( $this->bt_bb_array ) - 1 ]['children'][0] = array( 'title' => '_content', 'base' => '_content', 'content' => $r, 'children' => array() );
			}
		}
	}
}

function bt_bb_edit_dialog() {
	echo '<div id="bt_bb_dialog" class="bt_bb_dialog">';
		echo '<div class="bt_bb_dialog_header"><div class="bt_bb_dialog_close"></div><span></span></div>';
		echo '<div class="bt_bb_dialog_header_tools"></div>';
		echo '<div class="bt_bb_dialog_content">';
		echo '</div>';
		echo '<div class="bt_bb_dialog_tinymce">';
			echo '<div class="bt_bb_dialog_tinymce_editor_container">';
				wp_editor( '' , 'bt_bb_tinymce', array( 'textarea_rows' => 12 ) );
			echo '</div>';
			echo '<input type="button" class="bt_bb_dialog_button bt_bb_edit button button-small" value="' . __( 'Submit', 'bold-builder' ) . '">';
		echo '</div>';
	echo '</div>';
}

require_once 'JSON.php';

function bt_bb_json_encode( $arg ) {
	global $services_json;
	if ( ! isset( $services_json ) ) {
		$services_json = new BT_BB_Services_JSON();
	}
	return $services_json->encode( $arg );
}

function bt_bb_json_decode( $arg ) {
	global $services_json;
	if ( ! isset( $services_json ) ) {
		$services_json = new BT_BB_Services_JSON();
	}
	return $services_json->decode( $arg );
}

class BT_BB_Basic_Element {
	
	protected $shortcode;
	protected $prefix;
	protected $prefix_backend;
	
	function __construct() {
		$this->init();
	}

	function init() {
		$this->prefix = 'bt_bb_';
		$this->prefix_backend = 'bt_bb_';
		$this->shortcode = get_class( $this );
		add_shortcode( $this->shortcode, array( $this, 'handle_shortcode' ) );
		add_action( 'wp_loaded', array( $this, 'map_shortcode' ) );
		//$this->map_shortcode();
		$this->add_params();
		add_filter( 'bt_bb_extract_atts', array( $this, 'atts_callback' ) );
	}
	
	function add_params() {
		
	}
	
	function atts_callback( $atts ) {
		
	}
	
	function to_uc( $str ) {
		$str = str_replace( '_', ' ', $str );
		$str = ucwords( $str );
		$str = str_replace( ' ', '', $str );
		return $str;
	}		
	
	function to_camel( $str ) {
		$str = $this->to_uc( $str );
		$str = lcfirst( $str );
		return $str;
	}	

}

class BT_BB_Element extends BT_BB_Basic_Element {

	function add_params() {
		bt_bb_add_params( $this->shortcode, array(
			array( 'param_name' => 'el_id', 'type' => 'textfield', 'heading' => __( 'Element ID', 'bold-builder' ), 'group' => __( 'Custom', 'bold-builder' ), 'weight' => 1000 ),
			array( 'param_name' => 'el_class', 'type' => 'textfield', 'heading' => __( 'Extra class name(s)', 'bold-builder' ), 'group' => __( 'Custom', 'bold-builder' ), 'weight' => 1001 ),
			array( 'param_name' => 'el_style', 'type' => 'textfield', 'heading' => __( 'Inline style', 'bold-builder' ), 'group' => __( 'Custom', 'bold-builder' ), 'weight' => 1002 )
		) );
	}
	
	function atts_callback( $atts ) {
		return array(
			'el_id'    => '',
			'el_class' => '',
			'el_style' => ''
		) + $atts;
	}

}

if ( ! class_exists( 'BoldThemes_BB_Settings' ) || ! BoldThemes_BB_Settings::$custom_content_elements ) {
	$elements = array();

	$glob_match = glob( plugin_dir_path( __FILE__ ) . 'content_elements/*/*.php' );
	if ( $glob_match ) {
		foreach( $glob_match as $file ) {
			if ( preg_match( '/(\w+)\/\1.php$/', $file, $match ) ) {
				$elements[ $match[1] ] = $file;
			}
		}
	}

	$glob_match = glob( WP_PLUGIN_DIR . '/boldthemes_plugin/*/*.php' );
	if ( $glob_match ) {
		foreach( $glob_match as $file ) {
			if ( preg_match( '/(\w+)\/\1.php$/', $file, $match ) ) {
				$elements[ $match[1] ] = $file;
			}
		}
	}

	$glob_match = glob( get_stylesheet_directory() . '/bold-page-builder/content_elements/*/*.php' );
	if ( $glob_match ) {
		foreach( $glob_match as $file ) {
			if ( preg_match( '/(\w+)\/\1.php$/', $file, $match ) ) {
				$elements[ $match[1] ] = $file;
			}
		}
	}

	foreach( $elements as $key => $value ) {
		require( $value );
		new $key();
	}
}