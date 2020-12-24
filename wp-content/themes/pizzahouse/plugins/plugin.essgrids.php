<?php
/* Essential Grid support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('pizzahouse_essgrids_theme_setup')) {
	add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_essgrids_theme_setup', 1 );
	function pizzahouse_essgrids_theme_setup() {
		// Register shortcode in the shortcodes list
		if (pizzahouse_exists_essgrids()) {
			if (is_admin()) {
				add_filter( 'pizzahouse_filter_importer_import_row',		'pizzahouse_essgrids_importer_check_row', 9, 4);
			}
		}
		if (is_admin()) {
			add_filter( 'pizzahouse_filter_importer_required_plugins',	'pizzahouse_essgrids_importer_required_plugins', 10, 2 );
			add_filter( 'pizzahouse_filter_required_plugins',				'pizzahouse_essgrids_required_plugins' );
		}
	}
}


// Check if Ess. Grid installed and activated
if ( !function_exists( 'pizzahouse_exists_essgrids' ) ) {
	function pizzahouse_exists_essgrids() {
		return defined('EG_PLUGIN_PATH');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'pizzahouse_essgrids_required_plugins' ) ) {
	
	function pizzahouse_essgrids_required_plugins($list=array()) {
		if (in_array('essgrids', (array)pizzahouse_storage_get('required_plugins'))) {
			$path = pizzahouse_get_file_dir('plugins/install/essential-grid.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> esc_html__('Essential Grid', 'pizzahouse'),
					'slug' 		=> 'essential-grid',
					'source'	=> $path,
                    'version'   =>  '2.3.6',
					'required' 	=> false
					);
			}
		}
		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check in the required plugins
if ( !function_exists( 'pizzahouse_essgrids_importer_required_plugins' ) ) {
	
	function pizzahouse_essgrids_importer_required_plugins($not_installed='', $list='') {
		if (pizzahouse_strpos($list, 'essgrids')!==false && !pizzahouse_exists_essgrids() )
			$not_installed .= '<br>'.esc_html__('Essential Grids', 'pizzahouse');
		return $not_installed;
	}
}

// Check if the row will be imported
if ( !function_exists( 'pizzahouse_essgrids_importer_check_row' ) ) {
	
	function pizzahouse_essgrids_importer_check_row($flag, $table, $row, $list) {
		if ($flag || strpos($list, 'essgrids')===false) return $flag;
		if ( pizzahouse_exists_essgrids() ) {
			if ($table == 'posts')
				$flag = $row['post_type']==apply_filters('essgrid_PunchPost_custom_post_type', 'essential_grid');
		}
		return $flag;
	}
}
?>