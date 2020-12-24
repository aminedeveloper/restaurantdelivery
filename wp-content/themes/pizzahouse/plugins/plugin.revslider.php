<?php
/* Revolution Slider support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('pizzahouse_revslider_theme_setup')) {
	add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_revslider_theme_setup', 1 );
	function pizzahouse_revslider_theme_setup() {
		if (pizzahouse_exists_revslider()) {
			add_filter( 'pizzahouse_filter_list_sliders',					'pizzahouse_revslider_list_sliders' );
			add_filter( 'pizzahouse_filter_shortcodes_params',			'pizzahouse_revslider_shortcodes_params' );
			add_filter( 'pizzahouse_filter_theme_options_params',			'pizzahouse_revslider_theme_options_params' );
			if (is_admin()) {




				add_action( 'pizzahouse_action_importer_export',			'pizzahouse_revslider_importer_export', 10, 1 );
				add_action( 'pizzahouse_action_importer_export_fields',	'pizzahouse_revslider_importer_export_fields', 10, 1 );
			}
		}
		if (is_admin()) {
			add_filter( 'pizzahouse_filter_importer_required_plugins',	'pizzahouse_revslider_importer_required_plugins', 10, 2 );
			add_filter( 'pizzahouse_filter_required_plugins',				'pizzahouse_revslider_required_plugins' );
		}
	}
}

if ( !function_exists( 'pizzahouse_revslider_settings_theme_setup2' ) ) {
	add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_revslider_settings_theme_setup2', 3 );
	function pizzahouse_revslider_settings_theme_setup2() {
		if (pizzahouse_exists_revslider()) {

			// Add Revslider specific options in the Theme Options
			pizzahouse_storage_set_array_after('options', 'slider_engine', "slider_alias", array(
				"title" => esc_html__('Revolution Slider: Select slider',  'pizzahouse'),
				"desc" => wp_kses_data( __("Select slider to show (if engine=revo in the field above)", 'pizzahouse') ),
				"override" => "category,services_group,page",
				"dependency" => array(
					'show_slider' => array('yes'),
					'slider_engine' => array('revo')
				),
				"std" => "",
				"options" => pizzahouse_get_options_param('list_revo_sliders'),
				"type" => "select"
				)
			);

		}
	}
}

// Check if RevSlider installed and activated
if ( !function_exists( 'pizzahouse_exists_revslider' ) ) {
	function pizzahouse_exists_revslider() {
		return function_exists('rev_slider_shortcode');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'pizzahouse_revslider_required_plugins' ) ) {
	
	function pizzahouse_revslider_required_plugins($list=array()) {
		if (in_array('revslider', (array)pizzahouse_storage_get('required_plugins'))) {
			$path = pizzahouse_get_file_dir('plugins/install/revslider.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> esc_html__('Revolution Slider', 'pizzahouse'),
					'slug' 		=> 'revslider',
					'source'	=> $path,
                    'version'   =>  '6.2.2',
					'required' 	=> false
					);
			}
		}
		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check RevSlider in the required plugins
if ( !function_exists( 'pizzahouse_revslider_importer_required_plugins' ) ) {
	
	function pizzahouse_revslider_importer_required_plugins($not_installed='', $list='') {
		
		if (pizzahouse_strpos($list, 'revslider')!==false && !pizzahouse_exists_revslider() )
			$not_installed .= '<br>' . esc_html__('Revolution Slider', 'pizzahouse');
		return $not_installed;
	}
}


// Export posts
if ( !function_exists( 'pizzahouse_revslider_importer_export' ) ) {
	
	function pizzahouse_revslider_importer_export($importer) {
		// Sliders list
		pizzahouse_fpc(pizzahouse_get_file_dir('core/core.importer/export/revslider.txt'), join("\n", array_keys(pizzahouse_get_list_revo_sliders())));
	}
}

// Display exported data in the fields
if ( !function_exists( 'pizzahouse_revslider_importer_export_fields' ) ) {
	
	function pizzahouse_revslider_importer_export_fields($importer) {
		$importer->show_exporter_fields(array(
			'slug' => 'revslider',
			'title' => esc_html__('Revolution Sliders', 'pizzahouse')
			));
	}
}


// Lists
//------------------------------------------------------------------------

// Add RevSlider in the sliders list, prepended inherit (if need)
if ( !function_exists( 'pizzahouse_revslider_list_sliders' ) ) {
	
	function pizzahouse_revslider_list_sliders($list=array()) {
		$list = is_array($list) ? $list : array();
		$list["revo"] = esc_html__("Layer slider (Revolution)", 'pizzahouse');
		return $list;
	}
}

// Return Revo Sliders list, prepended inherit (if need)
if ( !function_exists( 'pizzahouse_get_list_revo_sliders' ) ) {
	function pizzahouse_get_list_revo_sliders($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_revo_sliders'))=='') {
			$list = array();
			if (pizzahouse_exists_revslider()) {
				global $wpdb;
				$rows = $wpdb->get_results( "SELECT alias, title FROM " . esc_sql($wpdb->prefix) . "revslider_sliders" );
				if (is_array($rows) && count($rows) > 0) {
					foreach ($rows as $row) {
						$list[$row->alias] = $row->title;
					}
				}
			}
			$list = apply_filters('pizzahouse_filter_list_revo_sliders', $list);
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_revo_sliders', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}

// Add RevSlider in the shortcodes params
if ( !function_exists( 'pizzahouse_revslider_shortcodes_params' ) ) {
	
	function pizzahouse_revslider_shortcodes_params($list=array()) {
		$list["revo_sliders"] = pizzahouse_get_list_revo_sliders();
		return $list;
	}
}

// Add RevSlider in the Theme Options params
if ( !function_exists( 'pizzahouse_revslider_theme_options_params' ) ) {
	
	function pizzahouse_revslider_theme_options_params($list=array()) {
		$list["list_revo_sliders"] = array('$pizzahouse_get_list_revo_sliders' => '');
		return $list;
	}
}
?>