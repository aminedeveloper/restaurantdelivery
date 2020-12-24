<?php
/* Mail Chimp support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('pizzahouse_mailchimp_theme_setup')) {
	add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_mailchimp_theme_setup', 1 );
	function pizzahouse_mailchimp_theme_setup() {
		if (pizzahouse_exists_mailchimp()) {
			if (is_admin()) {

				add_action( 'pizzahouse_action_importer_params',				'pizzahouse_mailchimp_importer_show_params', 10, 1 );
				add_filter( 'pizzahouse_filter_importer_import_row',			'pizzahouse_mailchimp_importer_check_row', 9, 4);
			}
		}
		if (is_admin()) {
			add_filter( 'pizzahouse_filter_importer_required_plugins',		'pizzahouse_mailchimp_importer_required_plugins', 10, 2 );
			add_filter( 'pizzahouse_filter_required_plugins',					'pizzahouse_mailchimp_required_plugins' );
		}
	}
}

// Check if Instagram Feed installed and activated
if ( !function_exists( 'pizzahouse_exists_mailchimp' ) ) {
	function pizzahouse_exists_mailchimp() {
		return function_exists('mc4wp_load_plugin');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'pizzahouse_mailchimp_required_plugins' ) ) {
	
	function pizzahouse_mailchimp_required_plugins($list=array()) {
		if (in_array('mailchimp', (array)pizzahouse_storage_get('required_plugins')))
			$list[] = array(
				'name' 		=> esc_html__('MailChimp for WP', 'pizzahouse'),
				'slug' 		=> 'mailchimp-for-wp',
				'required' 	=> false
			);
		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check Mail Chimp in the required plugins
if ( !function_exists( 'pizzahouse_mailchimp_importer_required_plugins' ) ) {
	
	function pizzahouse_mailchimp_importer_required_plugins($not_installed='', $list='') {
		if (pizzahouse_strpos($list, 'mailchimp')!==false && !pizzahouse_exists_mailchimp() )
			$not_installed .= '<br>' . esc_html__('Mail Chimp', 'pizzahouse');
		return $not_installed;
	}
}

// Add checkbox to the one-click importer
if ( !function_exists( 'pizzahouse_mailchimp_importer_show_params' ) ) {
	
	function pizzahouse_mailchimp_importer_show_params($importer) {
		if ( pizzahouse_exists_mailchimp() && in_array('mailchimp', (array)pizzahouse_storage_get('required_plugins')) ) {
			$importer->show_importer_params(array(
				'slug' => 'mailchimp',
				'title' => esc_html__('Import MailChimp for WP', 'pizzahouse'),
				'part' => 1
			));
		}
	}
}

// Check if the row will be imported
if ( !function_exists( 'pizzahouse_mailchimp_importer_check_row' ) ) {
	
	function pizzahouse_mailchimp_importer_check_row($flag, $table, $row, $list) {
		if ($flag || strpos($list, 'mailchimp')===false) return $flag;
		if ( pizzahouse_exists_mailchimp() ) {
			if ($table == 'posts')
				$flag = $row['post_type']=='mc4wp-form';
		}
		return $flag;
	}
}
?>