<?php
/* WP GDPR Compliance support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('pizzahouse_wp_gdpr_compliance_theme_setup')) {
    add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_wp_gdpr_compliance_theme_setup', 1 );
    function pizzahouse_wp_gdpr_compliance_theme_setup() {
        if (is_admin()) {
            add_filter( 'pizzahouse_filter_required_plugins', 'pizzahouse_wp_gdpr_compliance_required_plugins' );
        }
    }
}

// Check if Instagram Widget installed and activated
if ( !function_exists( 'pizzahouse_exists_wp_gdpr_compliance' ) ) {
    function pizzahouse_exists_wp_gdpr_compliance() {
        return defined( 'WP_GDPR_Compliance_VERSION' );
    }
}

// Filter to add in the required plugins list
if ( !function_exists( 'pizzahouse_wp_gdpr_compliance_required_plugins' ) ) {
    
    function pizzahouse_wp_gdpr_compliance_required_plugins($list=array()) {
        if (in_array('wp_gdpr_compliance', (array)pizzahouse_storage_get('required_plugins')))
            $list[] = array(
                'name'         => esc_html__('WP GDPR Compliance', 'pizzahouse'),
                'slug'         => 'wp-gdpr-compliance',
                'required'     => false
            );
        return $list;
    }
}