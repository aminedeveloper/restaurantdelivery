<?php
/* Contact Form 7 support functions
------------------------------------------------------------------------------- */

// Check if Contact Form 7 installed and activated
if ( !function_exists( 'pizzahouse_exists_cf7' ) ) {
    function pizzahouse_exists_cf7() {
        return class_exists('WPCF7') && class_exists('WPCF7_ContactForm');
    }
}

// Theme init
if (!function_exists('pizzahouse_contact_form_7_theme_setup')) {
    add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_contact_form_7_theme_setup', 1 );
    function pizzahouse_contact_form_7_theme_setup() {
        if (pizzahouse_exists_cf7()) {
            add_action('pizzahouse_action_add_styles',	'pizzahouse_contact_form_7_frontend_scripts' );
        }
        if (is_admin()) {
            add_filter( 'pizzahouse_filter_required_plugins', 'pizzahouse_contact_form_7_required_plugins' );
        }
    }
}

// Filter to add in the required plugins list
if ( !function_exists( 'pizzahouse_contact_form_7_required_plugins' ) ) {
    function pizzahouse_contact_form_7_required_plugins($list=array()) {
        if (in_array('contact-form-7', (array)pizzahouse_storage_get('required_plugins')))
            $list[] = array(
                'name'         => esc_html__('Contact Form 7', 'pizzahouse'),
                'slug'         => 'contact-form-7',
                'required'     => false
            );
        return $list;
    }
}
// Enqueue Contact Form 7 custom styles
if ( !function_exists( 'pizzahouse_contact_form_7_frontend_scripts' ) ) {
    function pizzahouse_contact_form_7_frontend_scripts() {
        if (file_exists(pizzahouse_get_file_dir('css/plugin.contact-form-7.css')))
            wp_enqueue_style( 'pizzahouse-plugin-contact-form-7-style',  pizzahouse_get_file_url('css/plugin.contact-form-7.css'), array(), null );
    }
}


