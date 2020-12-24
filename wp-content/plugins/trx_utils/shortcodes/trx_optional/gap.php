<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('pizzahouse_sc_gap_theme_setup')) {
	add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_sc_gap_theme_setup' );
	function pizzahouse_sc_gap_theme_setup() {
		add_action('pizzahouse_action_shortcodes_list', 		'pizzahouse_sc_gap_reg_shortcodes');
		if (function_exists('pizzahouse_exists_visual_composer') && pizzahouse_exists_visual_composer())
			add_action('pizzahouse_action_shortcodes_list_vc','pizzahouse_sc_gap_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_gap]Fullwidth content[/trx_gap]

if (!function_exists('pizzahouse_sc_gap')) {	
	function pizzahouse_sc_gap($atts, $content = null) {
		if (pizzahouse_in_shortcode_blogger()) return '';
		$output = pizzahouse_gap_start() . do_shortcode($content) . pizzahouse_gap_end();
		return apply_filters('pizzahouse_shortcode_output', $output, 'trx_gap', $atts, $content);
	}
	add_shortcode("trx_gap", "pizzahouse_sc_gap");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pizzahouse_sc_gap_reg_shortcodes' ) ) {
	//add_action('pizzahouse_action_shortcodes_list', 'pizzahouse_sc_gap_reg_shortcodes');
	function pizzahouse_sc_gap_reg_shortcodes() {
	
		pizzahouse_sc_map("trx_gap", array(
			"title" => esc_html__("Gap", 'pizzahouse'),
			"desc" => wp_kses_data( __("Insert gap (fullwidth area) in the post content. Attention! Use the gap only in the posts (pages) without left or right sidebar", 'pizzahouse') ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Gap content", 'pizzahouse'),
					"desc" => wp_kses_data( __("Gap inner content", 'pizzahouse') ),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pizzahouse_sc_gap_reg_shortcodes_vc' ) ) {
	//add_action('pizzahouse_action_shortcodes_list_vc', 'pizzahouse_sc_gap_reg_shortcodes_vc');
	function pizzahouse_sc_gap_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_gap",
			"name" => esc_html__("Gap", 'pizzahouse'),
			"description" => wp_kses_data( __("Insert gap (fullwidth area) in the post content", 'pizzahouse') ),
			"category" => esc_html__('Structure', 'pizzahouse'),
			'icon' => 'icon_trx_gap',
			"class" => "trx_sc_collection trx_sc_gap",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"params" => array(
			)
		) );
		
		class WPBakeryShortCode_Trx_Gap extends PIZZAHOUSE_VC_ShortCodeCollection {}
	}
}
?>