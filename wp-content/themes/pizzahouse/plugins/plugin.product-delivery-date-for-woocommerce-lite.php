<?php
/* Product Delivery Date for WooCommerce - Lite support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('pizzahouse_product_delivery_date_for_woocommerce_lite_theme_setup')) {
    add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_product_delivery_date_for_woocommerce_lite_theme_setup', 1 );
    function pizzahouse_product_delivery_date_for_woocommerce_lite_theme_setup() {
        if (is_admin()) {
            add_filter( 'pizzahouse_filter_required_plugins', 'pizzahouse_product_delivery_date_for_woocommerce_lite_required_plugins' );
        }
    }
}


// Filter to add in the required plugins list
if ( !function_exists( 'pizzahouse_product_delivery_date_for_woocommerce_lite_required_plugins' ) ) {
    function pizzahouse_product_delivery_date_for_woocommerce_lite_required_plugins($list=array()) {
        if (in_array('product-delivery-date-for-woocommerce-lite', (array)pizzahouse_storage_get('required_plugins')))
            $list[] = array(
                'name'         => esc_html__('Product Delivery Date for WooCommerce - Lite', 'pizzahouse'),
                'slug'         => 'product-delivery-date-for-woocommerce-lite',
                'required'     => false
            );
        return $list;
    }
}


