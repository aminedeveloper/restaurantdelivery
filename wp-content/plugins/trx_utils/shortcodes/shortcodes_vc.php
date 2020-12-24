<?php
if (is_admin() 
		|| (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true' )
		|| (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline')
	) {
	require_once trx_utils_get_file_dir ( 'shortcodes/shortcodes_vc_classes.php');
}

// Width and height params
if ( !function_exists( 'pizzahouse_vc_width' ) ) {
	function pizzahouse_vc_width($w='') {
		return array(
			"param_name" => "width",
			"heading" => esc_html__("Width", 'pizzahouse'),
			"description" => wp_kses_data( __("Width of the element", 'pizzahouse') ),
			"group" => esc_html__('Size &amp; Margins', 'pizzahouse'),
			"value" => $w,
			"type" => "textfield"
		);
	}
}
if ( !function_exists( 'pizzahouse_vc_height' ) ) {
	function pizzahouse_vc_height($h='') {
		return array(
			"param_name" => "height",
			"heading" => esc_html__("Height", 'pizzahouse'),
			"description" => wp_kses_data( __("Height of the element", 'pizzahouse') ),
			"group" => esc_html__('Size &amp; Margins', 'pizzahouse'),
			"value" => $h,
			"type" => "textfield"
		);
	}
}

// Load scripts and styles for VC support
if ( !function_exists( 'pizzahouse_shortcodes_vc_scripts_admin' ) ) {
	//add_action( 'admin_enqueue_scripts', 'pizzahouse_shortcodes_vc_scripts_admin' );
	function pizzahouse_shortcodes_vc_scripts_admin() {
		// Include CSS 
		wp_enqueue_style ( 'shortcodes_vc_admin-style', trx_utils_get_file_url('shortcodes/theme.shortcodes_vc_admin.css'), array(), null );
		// Include JS
		wp_enqueue_script( 'shortcodes_vc_admin-script', trx_utils_get_file_url('shortcodes/shortcodes_vc_admin.js'), array('jquery'), null, true );
	}
}

// Load scripts and styles for VC support
if ( !function_exists( 'pizzahouse_shortcodes_vc_scripts_front' ) ) {
	//add_action( 'wp_enqueue_scripts', 'pizzahouse_shortcodes_vc_scripts_front' );
	function pizzahouse_shortcodes_vc_scripts_front() {
		if (pizzahouse_vc_is_frontend()) {
			// Include CSS 
			wp_enqueue_style ( 'shortcodes_vc_front-style', trx_utils_get_file_url('shortcodes/theme.shortcodes_vc_front.css'), array(), null );
			// Include JS
			//wp_enqueue_script( 'shortcodes_vc_front-script', trx_utils_get_file_url('shortcodes/shortcodes_vc_front.js'), array('jquery'), null, true );
			wp_enqueue_script( 'shortcodes_vc_theme-script', trx_utils_get_file_url('shortcodes/theme.shortcodes_vc_front.js'), array('jquery'), null, true );
		}
	}
}

// Add init script into shortcodes output in VC frontend editor
if ( !function_exists( 'pizzahouse_shortcodes_vc_add_init_script' ) ) {
	//add_filter('pizzahouse_shortcode_output', 'pizzahouse_shortcodes_vc_add_init_script', 10, 4);
	function pizzahouse_shortcodes_vc_add_init_script($output, $tag='', $atts=array(), $content='') {
		if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') && (isset($_POST['action']) && $_POST['action']=='vc_load_shortcode')
				&& ( isset($_POST['shortcodes'][0]['tag']) && $_POST['shortcodes'][0]['tag']==$tag )
		) {
			if (pizzahouse_strpos($output, 'pizzahouse_vc_init_shortcodes')===false) {
				$id = "pizzahouse_vc_init_shortcodes_".str_replace('.', '', mt_rand());
				// Attention! This code will be appended in the shortcode's output
				// to init shortcode after it inserted in the page in the VC Frontend editor
				$holder = 'script';
				$output .= '<'.trim($holder).' id="'.esc_attr($id).'">
						try {
							pizzahouse_init_post_formats();
							pizzahouse_init_shortcodes(jQuery("body").eq(0));
							pizzahouse_scroll_actions();
						} catch (e) { };
					</'.trim($holder).'>';
			}
		}
		return $output;
	}
}

// Return vc_param value
if ( !function_exists( 'pizzahouse_get_vc_param' ) ) {
	function pizzahouse_get_vc_param($prm) {
		return pizzahouse_storage_get_array('vc_params', $prm);
	}
}

// Set vc_param value
if ( !function_exists( 'pizzahouse_set_vc_param' ) ) {
	function pizzahouse_set_vc_param($prm, $val) {
		pizzahouse_storage_set_array('vc_params', $prm, $val);
	}
}


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'pizzahouse_shortcodes_vc_theme_setup' ) ) {
	//if ( pizzahouse_vc_is_frontend() )
	if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline') )
		add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_shortcodes_vc_theme_setup', 20 );
	else
		add_action( 'pizzahouse_action_after_init_theme', 'pizzahouse_shortcodes_vc_theme_setup' );
	function pizzahouse_shortcodes_vc_theme_setup() {


		// Set dir with theme specific VC shortcodes
		if ( function_exists( 'vc_set_shortcodes_templates_dir' ) ) {
			vc_set_shortcodes_templates_dir( trx_utils_get_folder_dir('shortcodes/vc' ) );
		}
		
		// Add/Remove params in the standard VC shortcodes
		vc_add_param("vc_row", array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'pizzahouse'),
					"description" => wp_kses_data( __("Select color scheme for this block", 'pizzahouse') ),
					"group" => esc_html__('Color scheme', 'pizzahouse'),
					"class" => "",
					"value" => array_flip(pizzahouse_get_list_color_schemes(true)),
					"type" => "dropdown"
		));
		vc_add_param("vc_row", array(
					"param_name" => "inverse",
					"heading" => esc_html__("Inverse colors", 'pizzahouse'),
					"description" => wp_kses_data( __("Inverse all colors of this block", 'pizzahouse') ),
					"group" => esc_html__('Color scheme', 'pizzahouse'),
					"class" => "",
					"std" => "no",
					"value" => array(esc_html__('Inverse colors', 'pizzahouse') => 'yes'),
					"type" => "checkbox"
		));
		vc_add_param("vc_row", array(
			"param_name" => "style_line",
			"heading" => esc_html__("Style Line", 'pizzahouse'),
			"description" => wp_kses_data( __("Show line style", 'pizzahouse') ),
			"group" => esc_html__('Style Line', 'pizzahouse'),
			"class" => "",
			"std" => "no",
			"value" => array(esc_html__('Style Line', 'pizzahouse') => 'yes'),
			"type" => "checkbox"
		));


		if (pizzahouse_shortcodes_is_used() && class_exists('Pizzahouse_VC_ShortCodeSingle')) {

			// Set VC as main editor for the theme
			vc_set_as_theme( true );
			
			// Enable VC on follow post types
			vc_set_default_editor_post_types( array('page', 'team') );
			
			// Load scripts and styles for VC support
			add_action( 'wp_enqueue_scripts',		'pizzahouse_shortcodes_vc_scripts_front');
			add_action( 'admin_enqueue_scripts',	'pizzahouse_shortcodes_vc_scripts_admin' );

			// Add init script into shortcodes output in VC frontend editor
			add_filter('pizzahouse_shortcode_output', 'pizzahouse_shortcodes_vc_add_init_script', 10, 4);

			pizzahouse_storage_set('vc_params', array(
				
				// Common arrays and strings
				'category' => esc_html__("PizzaHouse shortcodes", 'pizzahouse'),
			
				// Current element id
				'id' => array(
					"param_name" => "id",
					"heading" => esc_html__("Element ID", 'pizzahouse'),
					"description" => wp_kses_data( __("ID for the element", 'pizzahouse') ),
					"group" => esc_html__('ID &amp; Class', 'pizzahouse'),
					"value" => "",
					"type" => "textfield"
				),
			
				// Current element class
				'class' => array(
					"param_name" => "class",
					"heading" => esc_html__("Element CSS class", 'pizzahouse'),
					"description" => wp_kses_data( __("CSS class for the element", 'pizzahouse') ),
					"group" => esc_html__('ID &amp; Class', 'pizzahouse'),
					"value" => "",
					"type" => "textfield"
				),

				// Current element animation
				'animation' => array(
					"param_name" => "animation",
					"heading" => esc_html__("Animation", 'pizzahouse'),
					"description" => wp_kses_data( __("Select animation while object enter in the visible area of page", 'pizzahouse') ),
					"group" => esc_html__('ID &amp; Class', 'pizzahouse'),
					"class" => "",
					"value" => array_flip(pizzahouse_get_sc_param('animations')),
					"type" => "dropdown"
				),
			
				// Current element style
				'css' => array(
					"param_name" => "css",
					"heading" => esc_html__("CSS styles", 'pizzahouse'),
					"description" => wp_kses_data( __("Any additional CSS rules (if need)", 'pizzahouse') ),
					"group" => esc_html__('ID &amp; Class', 'pizzahouse'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
			
				// Margins params
				'margin_top' => array(
					"param_name" => "top",
					"heading" => esc_html__("Top margin", 'pizzahouse'),
					"description" => wp_kses_data( __("Margin above this shortcode", 'pizzahouse') ),
					"group" => esc_html__('Size &amp; Margins', 'pizzahouse'),
					"std" => "inherit",
					"value" => array_flip(pizzahouse_get_sc_param('margins')),
					"type" => "dropdown"
				),
			
				'margin_bottom' => array(
					"param_name" => "bottom",
					"heading" => esc_html__("Bottom margin", 'pizzahouse'),
					"description" => wp_kses_data( __("Margin below this shortcode", 'pizzahouse') ),
					"group" => esc_html__('Size &amp; Margins', 'pizzahouse'),
					"std" => "inherit",
					"value" => array_flip(pizzahouse_get_sc_param('margins')),
					"type" => "dropdown"
				),
			
				'margin_left' => array(
					"param_name" => "left",
					"heading" => esc_html__("Left margin", 'pizzahouse'),
					"description" => wp_kses_data( __("Margin on the left side of this shortcode", 'pizzahouse') ),
					"group" => esc_html__('Size &amp; Margins', 'pizzahouse'),
					"std" => "inherit",
					"value" => array_flip(pizzahouse_get_sc_param('margins')),
					"type" => "dropdown"
				),
				
				'margin_right' => array(
					"param_name" => "right",
					"heading" => esc_html__("Right margin", 'pizzahouse'),
					"description" => wp_kses_data( __("Margin on the right side of this shortcode", 'pizzahouse') ),
					"group" => esc_html__('Size &amp; Margins', 'pizzahouse'),
					"std" => "inherit",
					"value" => array_flip(pizzahouse_get_sc_param('margins')),
					"type" => "dropdown"
				)
			) );
			
			// Add theme-specific shortcodes
			do_action('pizzahouse_action_shortcodes_list_vc');

		}
	}
}

// Prevent simultaneous editing of posts for Gutenberg and other PageBuilders (VC, Elementor)
if ( ! function_exists( 'trx_utils_gutenberg_disable_cpt' ) ) {
    add_action( 'current_screen', 'trx_utils_gutenberg_disable_cpt' );
    function trx_utils_gutenberg_disable_cpt() {
        $safe_pb = array('vc');
        if ( !empty($safe_pb) && function_exists( 'the_gutenberg_project' ) && function_exists( 'register_block_type' ) ) {
            $current_post_type = get_current_screen()->post_type;
            $disable = false;
            if ( !$disable && in_array('vc', $safe_pb) && function_exists('vc_editor_post_types') ) {
                $post_types = vc_editor_post_types();
                $disable = is_array($post_types) && in_array($current_post_type, $post_types);
            }
            if ( $disable ) {
                remove_filter( 'replace_editor', 'gutenberg_init' );
                remove_action( 'load-post.php', 'gutenberg_intercept_edit_post' );
                remove_action( 'load-post-new.php', 'gutenberg_intercept_post_new' );
                remove_action( 'admin_init', 'gutenberg_add_edit_link_filters' );
                remove_filter( 'admin_url', 'gutenberg_modify_add_new_button_url' );
                remove_action( 'admin_print_scripts-edit.php', 'gutenberg_replace_default_add_new_button' );
                remove_action( 'admin_enqueue_scripts', 'gutenberg_editor_scripts_and_styles' );
                remove_filter( 'screen_options_show_screen', '__return_false' );
            }
        }
    }
}
?>