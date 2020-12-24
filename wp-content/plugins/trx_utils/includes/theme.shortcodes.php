<?php
if (!function_exists('pizzahouse_theme_shortcodes_setup')) {
	add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_theme_shortcodes_setup', 1 );
	function pizzahouse_theme_shortcodes_setup() {
		add_filter('pizzahouse_filter_googlemap_styles', 'pizzahouse_theme_shortcodes_googlemap_styles');
	}
}


// Add theme-specific Google map styles
if ( !function_exists( 'pizzahouse_theme_shortcodes_googlemap_styles' ) ) {
	function pizzahouse_theme_shortcodes_googlemap_styles($list) {
		$list['simple']		= esc_html__('Simple', 'pizzahouse');
		$list['greyscale']	= esc_html__('Greyscale', 'pizzahouse');
		$list['inverse']	= esc_html__('Inverse', 'pizzahouse');
		$list['apple']		= esc_html__('Apple', 'pizzahouse');
		return $list;
	}
}
?>