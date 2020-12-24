<?php
/* Elegro Crypto Payment support functions
------------------------------------------------------------------------------- */

// Check if plugin installed and activated
if ( !function_exists( 'pizzahouse_exists_elegro_payment' ) ) {
    function pizzahouse_exists_elegro_payment() {
        return class_exists( 'WC_Elegro_Payment' );
    }
}

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('pizzahouse_elegro_payment_theme_setup')) {
    add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_elegro_payment_theme_setup', 1 );
    function pizzahouse_elegro_payment_theme_setup() {
        if (pizzahouse_exists_elegro_payment()) {
            add_action('pizzahouse_action_add_styles',	'pizzahouse_elegro_payment_frontend_scripts' );
        }
        if (is_admin()) {
            add_filter( 'pizzahouse_filter_required_plugins',		'pizzahouse_elegro_payment_required_plugins' );
        }
    }
}

// Filter to add in the required plugins list
if ( !function_exists( 'pizzahouse_elegro_payment_required_plugins' ) ) {
    function pizzahouse_elegro_payment_required_plugins($list=array()) {
        if (in_array('elegro-payment', (array)pizzahouse_storage_get('required_plugins'))) {
            $list[] = array(
                'name' 		=> esc_html__('Elegro Crypto Payment', 'pizzahouse'),
                'slug' 		=> 'elegro-payment',
                'required' 	=> false
            );
        }
        return $list;
    }
}


// Enqueue Elegro Payment custom styles
if ( !function_exists( 'pizzahouse_elegro_payment_frontend_scripts' ) ) {
    function pizzahouse_elegro_payment_frontend_scripts() {
        if (file_exists(pizzahouse_get_file_dir('css/plugin.elegro-payment.css')))
            wp_enqueue_style( 'pizzahouse-plugin-elegro-payment-style',  pizzahouse_get_file_url('css/plugin.elegro-payment.css'), array(), null );
    }
}
?>