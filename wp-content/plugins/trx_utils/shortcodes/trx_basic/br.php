<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('pizzahouse_sc_br_theme_setup')) {
	add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_sc_br_theme_setup' );
	function pizzahouse_sc_br_theme_setup() {
		add_action('pizzahouse_action_shortcodes_list', 		'pizzahouse_sc_br_reg_shortcodes');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_br clear="left|right|both"]
*/

if (!function_exists('pizzahouse_sc_br')) {	
	function pizzahouse_sc_br($atts, $content = null) {
		if (pizzahouse_in_shortcode_blogger()) return '';
		extract(pizzahouse_html_decode(shortcode_atts(array(
			"clear" => ""
		), $atts)));
		$output = in_array($clear, array('left', 'right', 'both', 'all')) 
			? '<div class="clearfix" style="clear:' . str_replace('all', 'both', $clear) . '"></div>'
			: '<br />';
		return apply_filters('pizzahouse_shortcode_output', $output, 'trx_br', $atts, $content);
	}
	add_shortcode("trx_br", "pizzahouse_sc_br");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pizzahouse_sc_br_reg_shortcodes' ) ) {
	//add_action('pizzahouse_action_shortcodes_list', 'pizzahouse_sc_br_reg_shortcodes');
	function pizzahouse_sc_br_reg_shortcodes() {
	
		pizzahouse_sc_map("trx_br", array(
			"title" => esc_html__("Break", 'pizzahouse'),
			"desc" => wp_kses_data( __("Line break with clear floating (if need)", 'pizzahouse') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"clear" => 	array(
					"title" => esc_html__("Clear floating", 'pizzahouse'),
					"desc" => wp_kses_data( __("Clear floating (if need)", 'pizzahouse') ),
					"value" => "",
					"type" => "checklist",
					"options" => array(
						'none' => esc_html__('None', 'pizzahouse'),
						'left' => esc_html__('Left', 'pizzahouse'),
						'right' => esc_html__('Right', 'pizzahouse'),
						'both' => esc_html__('Both', 'pizzahouse')
					)
				)
			)
		));
	}
}
?>