<?php
/* Gutenberg support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('pizzahouse_gutenberg_theme_setup')) {
    add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_gutenberg_theme_setup', 1 );
    function pizzahouse_gutenberg_theme_setup() {
        if (is_admin()) {
            add_filter( 'pizzahouse_filter_required_plugins', 'pizzahouse_gutenberg_required_plugins' );
        }
    }
}

// Check if Instagram Widget installed and activated
if ( !function_exists( 'pizzahouse_exists_gutenberg' ) ) {
    function pizzahouse_exists_gutenberg() {
        return function_exists( 'the_gutenberg_project' ) && function_exists( 'register_block_type' );
    }
}

// Filter to add in the required plugins list
if ( !function_exists( 'pizzahouse_gutenberg_required_plugins' ) ) {
    function pizzahouse_gutenberg_required_plugins($list=array()) {
        if (in_array('gutenberg', (array)pizzahouse_storage_get('required_plugins')))
            $list[] = array(
                'name'         => esc_html__('Gutenberg', 'pizzahouse'),
                'slug'         => 'gutenberg',
                'required'     => false
            );
        return $list;
    }
}