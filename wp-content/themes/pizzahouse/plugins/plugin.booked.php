<?php
/* Booked Appointments support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('pizzahouse_booked_theme_setup')) {
	add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_booked_theme_setup', 1 );
	function pizzahouse_booked_theme_setup() {
		// Register shortcode in the shortcodes list
		if (pizzahouse_exists_booked()) {
			add_action('pizzahouse_action_add_styles', 					'pizzahouse_booked_frontend_scripts');
			add_action('pizzahouse_action_shortcodes_list',				'pizzahouse_booked_reg_shortcodes');
			if (function_exists('pizzahouse_exists_visual_composer') && pizzahouse_exists_visual_composer())
				add_action('pizzahouse_action_shortcodes_list_vc',		'pizzahouse_booked_reg_shortcodes_vc');

		}
		if (is_admin()) {
			add_filter( 'pizzahouse_filter_importer_required_plugins',	'pizzahouse_booked_importer_required_plugins', 10, 2);
			add_filter( 'pizzahouse_filter_required_plugins',				'pizzahouse_booked_required_plugins' );
		}
	}
}


// Check if plugin installed and activated
if ( !function_exists( 'pizzahouse_exists_booked' ) ) {
	function pizzahouse_exists_booked() {
		return class_exists('booked_plugin');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'pizzahouse_booked_required_plugins' ) ) {
	
	function pizzahouse_booked_required_plugins($list=array()) {
		if (in_array('booked', (array)pizzahouse_storage_get('required_plugins'))) {
			$path = pizzahouse_get_file_dir('plugins/install/booked.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> esc_html__('Booked', 'pizzahouse'),
					'slug' 		=> 'booked',
					'source'	=> $path,
                    'version'   =>  '2.2.6',
					'required' 	=> false
					);
			}
		}
		return $list;
	}
}

// Enqueue custom styles
if ( !function_exists( 'pizzahouse_booked_frontend_scripts' ) ) {
	
	function pizzahouse_booked_frontend_scripts() {
		if (file_exists(pizzahouse_get_file_dir('css/plugin.booked.css')))
            wp_enqueue_style( 'pizzahouse-plugin-booked-style',  pizzahouse_get_file_url('css/plugin.booked.css'), array(), null );
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check in the required plugins
if ( !function_exists( 'pizzahouse_booked_importer_required_plugins' ) ) {
	
	function pizzahouse_booked_importer_required_plugins($not_installed='', $list='') {
		
		if (pizzahouse_strpos($list, 'booked')!==false && !pizzahouse_exists_booked() )
			$not_installed .= '<br>' . esc_html__('Booked Appointments', 'pizzahouse');
		return $not_installed;
	}
}



// Lists
//------------------------------------------------------------------------

// Return booked calendars list, prepended inherit (if need)
if ( !function_exists( 'pizzahouse_get_list_booked_calendars' ) ) {
	function pizzahouse_get_list_booked_calendars($prepend_inherit=false) {
		return pizzahouse_exists_booked() ? pizzahouse_get_list_terms($prepend_inherit, 'booked_custom_calendars') : array();
	}
}



// Register plugin's shortcodes
//------------------------------------------------------------------------

// Register shortcode in the shortcodes list
if (!function_exists('pizzahouse_booked_reg_shortcodes')) {
	
	function pizzahouse_booked_reg_shortcodes() {
		if (pizzahouse_storage_isset('shortcodes')) {

			$booked_cals = pizzahouse_get_list_booked_calendars();

			pizzahouse_sc_map('booked-appointments', array(
				"title" => esc_html__("Booked Appointments", "pizzahouse"),
				"desc" => esc_html__("Display the currently logged in user's upcoming appointments", "pizzahouse"),
				"decorate" => true,
				"container" => false,
				"params" => array()
				)
			);

			pizzahouse_sc_map('booked-calendar', array(
				"title" => esc_html__("Booked Calendar", "pizzahouse"),
				"desc" => esc_html__("Insert booked calendar", "pizzahouse"),
				"decorate" => true,
				"container" => false,
				"params" => array(
					"calendar" => array(
						"title" => esc_html__("Calendar", "pizzahouse"),
						"desc" => esc_html__("Select booked calendar to display", "pizzahouse"),
						"value" => "0",
						"type" => "select",
						"options" => pizzahouse_array_merge(array(0 => esc_html__('- Select calendar -', 'pizzahouse')), $booked_cals)
					),
					"year" => array(
						"title" => esc_html__("Year", "pizzahouse"),
						"desc" => esc_html__("Year to display on calendar by default", "pizzahouse"),
						"value" => date("Y"),
						"min" => date("Y"),
						"max" => date("Y")+10,
						"type" => "spinner"
					),
					"month" => array(
						"title" => esc_html__("Month", "pizzahouse"),
						"desc" => esc_html__("Month to display on calendar by default", "pizzahouse"),
						"value" => date("m"),
						"min" => 1,
						"max" => 12,
						"type" => "spinner"
					)
				)
			));
		}
	}
}


// Register shortcode in the VC shortcodes list
if (!function_exists('pizzahouse_booked_reg_shortcodes_vc')) {
	
	function pizzahouse_booked_reg_shortcodes_vc() {

		$booked_cals = pizzahouse_get_list_booked_calendars();

		// Booked Appointments
		vc_map( array(
				"base" => "booked-appointments",
				"name" => esc_html__("Booked Appointments", "pizzahouse"),
				"description" => esc_html__("Display the currently logged in user's upcoming appointments", "pizzahouse"),
				"category" => esc_html__('Content', 'pizzahouse'),
				'icon' => 'icon_trx_booked',
				"class" => "trx_sc_single trx_sc_booked_appointments",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array()
			) );
			
		class WPBakeryShortCode_Booked_Appointments extends Pizzahouse_VC_ShortCodeSingle {}

		// Booked Calendar
		vc_map( array(
				"base" => "booked-calendar",
				"name" => esc_html__("Booked Calendar", "pizzahouse"),
				"description" => esc_html__("Insert booked calendar", "pizzahouse"),
				"category" => esc_html__('Content', 'pizzahouse'),
				'icon' => 'icon_trx_booked',
				"class" => "trx_sc_single trx_sc_booked_calendar",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "calendar",
						"heading" => esc_html__("Calendar", "pizzahouse"),
						"description" => esc_html__("Select booked calendar to display", "pizzahouse"),
						"admin_label" => true,
						"class" => "",
						"std" => "0",
						"value" => array_flip((array)pizzahouse_array_merge(array(0 => esc_html__('- Select calendar -', 'pizzahouse')), $booked_cals)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "year",
						"heading" => esc_html__("Year", "pizzahouse"),
						"description" => esc_html__("Year to display on calendar by default", "pizzahouse"),
						"admin_label" => true,
						"class" => "",
						"std" => date("Y"),
						"value" => date("Y"),
						"type" => "textfield"
					),
					array(
						"param_name" => "month",
						"heading" => esc_html__("Month", "pizzahouse"),
						"description" => esc_html__("Month to display on calendar by default", "pizzahouse"),
						"admin_label" => true,
						"class" => "",
						"std" => date("m"),
						"value" => date("m"),
						"type" => "textfield"
					)
				)
			) );
			
		class WPBakeryShortCode_Booked_Calendar extends Pizzahouse_VC_ShortCodeSingle {}

	}
}
?>