<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('pizzahouse_sc_popup_theme_setup')) {
	add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_sc_popup_theme_setup' );
	function pizzahouse_sc_popup_theme_setup() {
		add_action('pizzahouse_action_shortcodes_list', 		'pizzahouse_sc_popup_reg_shortcodes');
		if (function_exists('pizzahouse_exists_visual_composer') && pizzahouse_exists_visual_composer())
			add_action('pizzahouse_action_shortcodes_list_vc','pizzahouse_sc_popup_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_popup id="unique_id" class="class_name" style="css_styles"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_popup]
*/

if (!function_exists('pizzahouse_sc_popup')) {	
	function pizzahouse_sc_popup($atts, $content=null){	
		if (pizzahouse_in_shortcode_blogger()) return '';
		extract(pizzahouse_html_decode(shortcode_atts(array(
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . pizzahouse_get_css_position_as_classes($top, $right, $bottom, $left);
		pizzahouse_enqueue_popup('magnific');
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_popup mfp-with-anim mfp-hide' . ($class ? ' '.esc_attr($class) : '') . '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>' 
				. do_shortcode($content) 
				. '</div>';
		return apply_filters('pizzahouse_shortcode_output', $output, 'trx_popup', $atts, $content);
	}
	add_shortcode('trx_popup', 'pizzahouse_sc_popup');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pizzahouse_sc_popup_reg_shortcodes' ) ) {
	//add_action('pizzahouse_action_shortcodes_list', 'pizzahouse_sc_popup_reg_shortcodes');
	function pizzahouse_sc_popup_reg_shortcodes() {
	
		pizzahouse_sc_map("trx_popup", array(
			"title" => esc_html__("Popup window", "pizzahouse"),
			"desc" => wp_kses_data( __("Container for any html-block with desired class and style for popup window", "pizzahouse") ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Container content", 'pizzahouse'),
					"desc" => wp_kses_data( __("Content for section container", 'pizzahouse') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"top" => pizzahouse_get_sc_param('top'),
				"bottom" => pizzahouse_get_sc_param('bottom'),
				"left" => pizzahouse_get_sc_param('left'),
				"right" => pizzahouse_get_sc_param('right'),
				"id" => pizzahouse_get_sc_param('id'),
				"class" => pizzahouse_get_sc_param('class'),
				"css" => pizzahouse_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pizzahouse_sc_popup_reg_shortcodes_vc' ) ) {
	//add_action('pizzahouse_action_shortcodes_list_vc', 'pizzahouse_sc_popup_reg_shortcodes_vc');
	function pizzahouse_sc_popup_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_popup",
			"name" => esc_html__("Popup window", "pizzahouse"),
			"description" => wp_kses_data( __("Container for any html-block with desired class and style for popup window", "pizzahouse") ),
			"category" => esc_html__('Content', 'pizzahouse'),
			'icon' => 'icon_trx_popup',
			"class" => "trx_sc_collection trx_sc_popup",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				pizzahouse_get_vc_param('id'),
				pizzahouse_get_vc_param('class'),
				pizzahouse_get_vc_param('css'),
				pizzahouse_get_vc_param('margin_top'),
				pizzahouse_get_vc_param('margin_bottom'),
				pizzahouse_get_vc_param('margin_left'),
				pizzahouse_get_vc_param('margin_right')
			)
		) );
		
		class WPBakeryShortCode_Trx_Popup extends PIZZAHOUSE_VC_ShortCodeCollection {}
	}
}
?>