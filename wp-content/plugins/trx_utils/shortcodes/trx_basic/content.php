<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('pizzahouse_sc_content_theme_setup')) {
	add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_sc_content_theme_setup' );
	function pizzahouse_sc_content_theme_setup() {
		add_action('pizzahouse_action_shortcodes_list', 		'pizzahouse_sc_content_reg_shortcodes');
		if (function_exists('pizzahouse_exists_visual_composer') && pizzahouse_exists_visual_composer())
			add_action('pizzahouse_action_shortcodes_list_vc','pizzahouse_sc_content_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_content id="unique_id" class="class_name" style="css-styles"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_content]
*/

if (!function_exists('pizzahouse_sc_content')) {
	function pizzahouse_sc_content($atts, $content=null){
		if (pizzahouse_in_shortcode_blogger()) return '';
		extract(pizzahouse_html_decode(shortcode_atts(array(
			"scheme" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"top" => "",
			"bottom" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . pizzahouse_get_css_position_as_classes($top, '', $bottom);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ' class="sc_content content_wrap' 
				. ($scheme && !pizzahouse_param_is_off($scheme) && !pizzahouse_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '')
				. ($class ? ' '.esc_attr($class) : '') 
				. '"'
			. (!pizzahouse_param_is_off($animation) ? ' data-animation="'.esc_attr(pizzahouse_get_animation_classes($animation)).'"' : '')
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '').'>' 
			. do_shortcode($content) 
			. '</div>';
		return apply_filters('pizzahouse_shortcode_output', $output, 'trx_content', $atts, $content);
	}
	add_shortcode('trx_content', 'pizzahouse_sc_content');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pizzahouse_sc_content_reg_shortcodes' ) ) {
	//add_action('pizzahouse_action_shortcodes_list', 'pizzahouse_sc_content_reg_shortcodes');
	function pizzahouse_sc_content_reg_shortcodes() {
	
		pizzahouse_sc_map("trx_content", array(
			"title" => esc_html__("Content block", "pizzahouse"),
			"desc" => wp_kses_data( __("Container for main content block with desired class and style (use it only on fullscreen pages)", "pizzahouse") ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"scheme" => array(
					"title" => esc_html__("Color scheme", "pizzahouse"),
					"desc" => wp_kses_data( __("Select color scheme for this block", "pizzahouse") ),
					"value" => "",
					"type" => "checklist",
					"options" => pizzahouse_get_sc_param('schemes')
				),
				"_content_" => array(
					"title" => esc_html__("Container content", "pizzahouse"),
					"desc" => wp_kses_data( __("Content for section container", "pizzahouse") ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"top" => pizzahouse_get_sc_param('top'),
				"bottom" => pizzahouse_get_sc_param('bottom'),
				"id" => pizzahouse_get_sc_param('id'),
				"class" => pizzahouse_get_sc_param('class'),
				"animation" => pizzahouse_get_sc_param('animation'),
				"css" => pizzahouse_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pizzahouse_sc_content_reg_shortcodes_vc' ) ) {
	//add_action('pizzahouse_action_shortcodes_list_vc', 'pizzahouse_sc_content_reg_shortcodes_vc');
	function pizzahouse_sc_content_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_content",
			"name" => esc_html__("Content block", "pizzahouse"),
			"description" => wp_kses_data( __("Container for main content block (use it only on fullscreen pages)", "pizzahouse") ),
			"category" => esc_html__('Content', 'pizzahouse'),
			'icon' => 'icon_trx_content',
			"class" => "trx_sc_collection trx_sc_content",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", "pizzahouse"),
					"description" => wp_kses_data( __("Select color scheme for this block", "pizzahouse") ),
					"group" => esc_html__('Colors and Images', 'pizzahouse'),
					"class" => "",
					"value" => array_flip((array)pizzahouse_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				pizzahouse_get_vc_param('id'),
				pizzahouse_get_vc_param('class'),
				pizzahouse_get_vc_param('animation'),
				pizzahouse_get_vc_param('css'),
				pizzahouse_get_vc_param('margin_top'),
				pizzahouse_get_vc_param('margin_bottom')
			)
		) );
		
		class WPBakeryShortCode_Trx_Content extends PIZZAHOUSE_VC_ShortCodeCollection {}
	}
}
?>