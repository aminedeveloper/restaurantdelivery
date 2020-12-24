<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('pizzahouse_sc_hide_theme_setup')) {
	add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_sc_hide_theme_setup' );
	function pizzahouse_sc_hide_theme_setup() {
		add_action('pizzahouse_action_shortcodes_list', 		'pizzahouse_sc_hide_reg_shortcodes');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_hide selector="unique_id"]
*/

if (!function_exists('pizzahouse_sc_hide')) {
	function pizzahouse_sc_hide($atts, $content=null){
		if (pizzahouse_in_shortcode_blogger()) return '';
		extract(pizzahouse_html_decode(shortcode_atts(array(
			// Individual params
			"selector" => "",
			"hide" => "on",
			"delay" => 0
		), $atts)));
		$selector = trim(chop($selector));
		if (!empty($selector)) {
			pizzahouse_storage_concat('js_code', '
				'.($delay>0 ? 'setTimeout(function() {' : '').'
					jQuery("'.esc_attr($selector).'").' . ($hide=='on' ? 'hide' : 'show') . '();
				'.($delay>0 ? '},'.($delay).');' : '').'
			');
		}
		return apply_filters('pizzahouse_shortcode_output', $output, 'trx_hide', $atts, $content);
	}
	add_shortcode('trx_hide', 'pizzahouse_sc_hide');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pizzahouse_sc_hide_reg_shortcodes' ) ) {
	//add_action('pizzahouse_action_shortcodes_list', 'pizzahouse_sc_hide_reg_shortcodes');
	function pizzahouse_sc_hide_reg_shortcodes() {
	
		pizzahouse_sc_map("trx_hide", array(
			"title" => esc_html__("Hide/Show any block", "pizzahouse"),
			"desc" => wp_kses_data( __("Hide or Show any block with desired CSS-selector", "pizzahouse") ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"selector" => array(
					"title" => esc_html__("Selector", "pizzahouse"),
					"desc" => wp_kses_data( __("Any block's CSS-selector", "pizzahouse") ),
					"value" => "",
					"type" => "text"
				),
				"hide" => array(
					"title" => esc_html__("Hide or Show", "pizzahouse"),
					"desc" => wp_kses_data( __("New state for the block: hide or show", "pizzahouse") ),
					"value" => "yes",
					"size" => "small",
					"options" => pizzahouse_get_sc_param('yes_no'),
					"type" => "switch"
				)
			)
		));
	}
}
?>