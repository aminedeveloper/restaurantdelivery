<?php

// Check if shortcodes settings are now used
if ( !function_exists( 'pizzahouse_shortcodes_is_used' ) ) {
	function pizzahouse_shortcodes_is_used() {
		return pizzahouse_options_is_used() 															// All modes when Theme Options are used
			|| (is_admin() && isset($_POST['action']) 
					&& in_array($_POST['action'], array('vc_edit_form', 'wpb_show_edit_form')))		// AJAX query when save post/page
			|| (is_admin() && !empty($_REQUEST['page']) && $_REQUEST['page']=='vc-roles')			// VC Role Manager
			|| (function_exists('pizzahouse_vc_is_frontend') && pizzahouse_vc_is_frontend());			// VC Frontend editor mode
	}
}

// Width and height params
if ( !function_exists( 'pizzahouse_shortcodes_width' ) ) {
	function pizzahouse_shortcodes_width($w="") {
		return array(
			"title" => esc_html__("Width", 'pizzahouse'),
			"divider" => true,
			"value" => $w,
			"type" => "text"
		);
	}
}
if ( !function_exists( 'pizzahouse_shortcodes_height' ) ) {
	function pizzahouse_shortcodes_height($h='') {
		return array(
			"title" => esc_html__("Height", 'pizzahouse'),
			"desc" => wp_kses_data( __("Width and height of the element", 'pizzahouse') ),
			"value" => $h,
			"type" => "text"
		);
	}
}

// Return sc_param value
if ( !function_exists( 'pizzahouse_get_sc_param' ) ) {
	function pizzahouse_get_sc_param($prm) {
		return pizzahouse_storage_get_array('sc_params', $prm);
	}
}

// Set sc_param value
if ( !function_exists( 'pizzahouse_set_sc_param' ) ) {
	function pizzahouse_set_sc_param($prm, $val) {
		pizzahouse_storage_set_array('sc_params', $prm, $val);
	}
}

// Add sc settings in the sc list
if ( !function_exists( 'pizzahouse_sc_map' ) ) {
	function pizzahouse_sc_map($sc_name, $sc_settings) {
		pizzahouse_storage_set_array('shortcodes', $sc_name, $sc_settings);
	}
}

// Add sc settings in the sc list after the key
if ( !function_exists( 'pizzahouse_sc_map_after' ) ) {
	function pizzahouse_sc_map_after($after, $sc_name, $sc_settings='') {
		pizzahouse_storage_set_array_after('shortcodes', $after, $sc_name, $sc_settings);
	}
}

// Add sc settings in the sc list before the key
if ( !function_exists( 'pizzahouse_sc_map_before' ) ) {
	function pizzahouse_sc_map_before($before, $sc_name, $sc_settings='') {
		pizzahouse_storage_set_array_before('shortcodes', $before, $sc_name, $sc_settings);
	}
}

// Compare two shortcodes by title
if ( !function_exists( 'pizzahouse_compare_sc_title' ) ) {
	function pizzahouse_compare_sc_title($a, $b) {
		return strcmp($a['title'], $b['title']);
	}
}



/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'pizzahouse_shortcodes_settings_theme_setup' ) ) {
//	if ( pizzahouse_vc_is_frontend() )
	if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline') )
		add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_shortcodes_settings_theme_setup', 20 );
	else
		add_action( 'pizzahouse_action_after_init_theme', 'pizzahouse_shortcodes_settings_theme_setup' );
	function pizzahouse_shortcodes_settings_theme_setup() {
		if (pizzahouse_shortcodes_is_used()) {

			// Sort templates alphabetically
			$tmp = pizzahouse_storage_get('registered_templates');
			ksort($tmp);
			pizzahouse_storage_set('registered_templates', $tmp);

			// Prepare arrays 
			pizzahouse_storage_set('sc_params', array(
			
				// Current element id
				'id' => array(
					"title" => esc_html__("Element ID", 'pizzahouse'),
					"desc" => wp_kses_data( __("ID for current element", 'pizzahouse') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
			
				// Current element class
				'class' => array(
					"title" => esc_html__("Element CSS class", 'pizzahouse'),
					"desc" => wp_kses_data( __("CSS class for current element (optional)", 'pizzahouse') ),
					"value" => "",
					"type" => "text"
				),
			
				// Current element style
				'css' => array(
					"title" => esc_html__("CSS styles", 'pizzahouse'),
					"desc" => wp_kses_data( __("Any additional CSS rules (if need)", 'pizzahouse') ),
					"value" => "",
					"type" => "text"
				),
			
			
				// Switcher choises
				'list_styles' => array(
					'ul'	=> esc_html__('Unordered', 'pizzahouse'),
					'ol'	=> esc_html__('Ordered', 'pizzahouse'),
					'iconed'=> esc_html__('Iconed', 'pizzahouse')
				),

				'yes_no'	=> pizzahouse_get_list_yesno(),
				'on_off'	=> pizzahouse_get_list_onoff(),
				'dir' 		=> pizzahouse_get_list_directions(),
				'align'		=> pizzahouse_get_list_alignments(),
				'float'		=> pizzahouse_get_list_floats(),
				'hpos'		=> pizzahouse_get_list_hpos(),
				'show_hide'	=> pizzahouse_get_list_showhide(),
				'sorting' 	=> pizzahouse_get_list_sortings(),
				'ordering' 	=> pizzahouse_get_list_orderings(),
				'shapes'	=> pizzahouse_get_list_shapes(),
				'sizes'		=> pizzahouse_get_list_sizes(),
				'sliders'	=> pizzahouse_get_list_sliders(),
				'controls'	=> pizzahouse_get_list_controls(),
                    'categories'=> is_admin() && pizzahouse_get_value_gp('action')=='vc_edit_form' && substr(pizzahouse_get_value_gp('tag'), 0, 4)=='trx_' && isset($_POST['params']['post_type']) && $_POST['params']['post_type']!='post'
                        ? pizzahouse_get_list_terms(false, pizzahouse_get_taxonomy_categories_by_post_type($_POST['params']['post_type']))
                        : pizzahouse_get_list_categories(),
				'columns'	=> pizzahouse_get_list_columns(),
				'images'	=> array_merge(array('none'=>"none"), pizzahouse_get_list_images("images/icons", "png")),
				'icons'		=> array_merge(array("inherit", "none"), pizzahouse_get_list_icons()),
				'locations'	=> pizzahouse_get_list_dedicated_locations(),
				'filters'	=> pizzahouse_get_list_portfolio_filters(),
				'formats'	=> pizzahouse_get_list_post_formats_filters(),
				'hovers'	=> pizzahouse_get_list_hovers(true),
				'hovers_dir'=> pizzahouse_get_list_hovers_directions(true),
				'schemes'	=> pizzahouse_get_list_color_schemes(true),
				'animations'		=> pizzahouse_get_list_animations_in(),
				'margins' 			=> pizzahouse_get_list_margins(true),
				'blogger_styles'	=> pizzahouse_get_list_templates_blogger(),
				'forms'				=> pizzahouse_get_list_templates_forms(),
				'posts_types'		=> pizzahouse_get_list_posts_types(),
				'googlemap_styles'	=> pizzahouse_get_list_googlemap_styles(),
				'field_types'		=> pizzahouse_get_list_field_types(),
				'label_positions'	=> pizzahouse_get_list_label_positions()
				)
			);

			// Common params
			pizzahouse_set_sc_param('animation', array(
				"title" => esc_html__("Animation",  'pizzahouse'),
				"desc" => wp_kses_data( __('Select animation while object enter in the visible area of page',  'pizzahouse') ),
				"value" => "none",
				"type" => "select",
				"options" => pizzahouse_get_sc_param('animations')
				)
			);
			pizzahouse_set_sc_param('top', array(
				"title" => esc_html__("Top margin",  'pizzahouse'),
				"divider" => true,
				"value" => "inherit",
				"type" => "select",
				"options" => pizzahouse_get_sc_param('margins')
				)
			);
			pizzahouse_set_sc_param('bottom', array(
				"title" => esc_html__("Bottom margin",  'pizzahouse'),
				"value" => "inherit",
				"type" => "select",
				"options" => pizzahouse_get_sc_param('margins')
				)
			);
			pizzahouse_set_sc_param('left', array(
				"title" => esc_html__("Left margin",  'pizzahouse'),
				"value" => "inherit",
				"type" => "select",
				"options" => pizzahouse_get_sc_param('margins')
				)
			);
			pizzahouse_set_sc_param('right', array(
				"title" => esc_html__("Right margin",  'pizzahouse'),
				"desc" => wp_kses_data( __("Margins around this shortcode", 'pizzahouse') ),
				"value" => "inherit",
				"type" => "select",
				"options" => pizzahouse_get_sc_param('margins')
				)
			);

			pizzahouse_storage_set('sc_params', apply_filters('pizzahouse_filter_shortcodes_params', pizzahouse_storage_get('sc_params')));

			// Shortcodes list
			//------------------------------------------------------------------
			pizzahouse_storage_set('shortcodes', array());
			
			// Register shortcodes
			do_action('pizzahouse_action_shortcodes_list');

			// Sort shortcodes list
			$tmp = pizzahouse_storage_get('shortcodes');
			uasort($tmp, 'pizzahouse_compare_sc_title');
			pizzahouse_storage_set('shortcodes', $tmp);
		}
	}
}
?>