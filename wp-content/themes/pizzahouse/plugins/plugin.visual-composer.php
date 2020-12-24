<?php
/* WPBakery PageBuilder support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('pizzahouse_vc_theme_setup')) {
	add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_vc_theme_setup', 1 );
	function pizzahouse_vc_theme_setup() {
		if (pizzahouse_exists_visual_composer()) {
			add_action('pizzahouse_action_add_styles',		 				'pizzahouse_vc_frontend_scripts' );
		}
		if (is_admin()) {
			add_filter( 'pizzahouse_filter_importer_required_plugins',		'pizzahouse_vc_importer_required_plugins', 10, 2 );
			add_filter( 'pizzahouse_filter_required_plugins',					'pizzahouse_vc_required_plugins' );
		}
	}
}

// Check if WPBakery PageBuilder installed and activated
if ( !function_exists( 'pizzahouse_exists_visual_composer' ) ) {
	function pizzahouse_exists_visual_composer() {
		return class_exists('Vc_Manager');
	}
}

// Check if WPBakery PageBuilder in frontend editor mode
if ( !function_exists( 'pizzahouse_vc_is_frontend' ) ) {
	function pizzahouse_vc_is_frontend() {
		return (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true')
			|| (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'pizzahouse_vc_required_plugins' ) ) {
	
	function pizzahouse_vc_required_plugins($list=array()) {
		if (in_array('visual_composer', (array)pizzahouse_storage_get('required_plugins'))) {
			$path = pizzahouse_get_file_dir('plugins/install/js_composer.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> esc_html__('WPBakery PageBuilder', 'pizzahouse'),
					'slug' 		=> 'js_composer',
					'source'	=> $path,
                    'version'   =>  '6.1',
					'required' 	=> false
				);
			}
		}
		return $list;
	}
}

// Enqueue VC custom styles
if ( !function_exists( 'pizzahouse_vc_frontend_scripts' ) ) {
	
	function pizzahouse_vc_frontend_scripts() {
		if (file_exists(pizzahouse_get_file_dir('css/plugin.visual-composer.css')))
            wp_enqueue_style( 'pizzahouse-plugin-visual-composer-style',  pizzahouse_get_file_url('css/plugin.visual-composer.css'), array(), null );
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check VC in the required plugins
if ( !function_exists( 'pizzahouse_vc_importer_required_plugins' ) ) {
	
	function pizzahouse_vc_importer_required_plugins($not_installed='', $list='') {
		if (!pizzahouse_exists_visual_composer() )		
			$not_installed .= '<br>' . esc_html__('WPBakery PageBuilder', 'pizzahouse');
		return $not_installed;
	}
}
?>