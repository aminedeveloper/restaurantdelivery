<?php
/* ThemeREX Updater support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('pizzahouse_trx_updater_theme_setup')) {
    add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_trx_updater_theme_setup' );
    function pizzahouse_trx_updater_theme_setup() {

        if (is_admin()) {
            add_filter( 'pizzahouse_filter_importer_required_plugins',	'pizzahouse_trx_updater_importer_required_plugins', 10, 2 );
            add_filter( 'pizzahouse_filter_required_plugins',				'pizzahouse_trx_updater_required_plugins' );
        }
    }
}

// Check if RevSlider installed and activated
if ( !function_exists( 'pizzahouse_exists_trx_updater' ) ) {
    function pizzahouse_exists_trx_updater() {
        return defined( 'TRX_UPDATER_VERSION' );
    }
}


// Filter to add in the required plugins list
if ( !function_exists( 'pizzahouse_trx_updater_required_plugins' ) ) {
    function pizzahouse_trx_updater_required_plugins($list=array()) {
        if (in_array('trx_updater', pizzahouse_storage_get('required_plugins'))) {
            $path = pizzahouse_get_file_dir('plugins/install/trx_updater.zip');
            if (file_exists($path)) {
                $list[] = array(
                    'name' 		=> esc_html__('ThemeREX Updater', 'pizzahouse'),
                    'slug' 		=> 'trx_updater',
                    'version'  => '1.4.1',
                    'source'	=> $path,
                    'required' 	=> false
                );
            }
        }
        return $list;
    }
}