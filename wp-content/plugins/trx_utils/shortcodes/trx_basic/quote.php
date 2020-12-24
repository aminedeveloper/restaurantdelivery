<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('pizzahouse_sc_quote_theme_setup')) {
	add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_sc_quote_theme_setup' );
	function pizzahouse_sc_quote_theme_setup() {
		add_action('pizzahouse_action_shortcodes_list', 		'pizzahouse_sc_quote_reg_shortcodes');
		if (function_exists('pizzahouse_exists_visual_composer') && pizzahouse_exists_visual_composer())
			add_action('pizzahouse_action_shortcodes_list_vc','pizzahouse_sc_quote_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_quote id="unique_id" cite="url" title=""]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/quote]
*/

if (!function_exists('pizzahouse_sc_quote')) {
	function pizzahouse_sc_quote($atts, $content=null){
		if (pizzahouse_in_shortcode_blogger()) return '';
		extract(pizzahouse_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"cite" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . pizzahouse_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= pizzahouse_get_css_dimensions_from_values($width);
		$cite_param = $cite != '' ? ' cite="'.esc_attr($cite).'"' : '';
		$title = $title=='' ? $cite : $title;
		$content = do_shortcode($content);
		if (pizzahouse_substr($content, 0, 2)!='<p') $content = '<p>' . ($content) . '</p>';
		$output = '<blockquote' 
			. ($id ? ' id="'.esc_attr($id).'"' : '') . ($cite_param) 
			. ' class="sc_quote'. (!empty($class) ? ' '.esc_attr($class) : '').'"' 
			. (!pizzahouse_param_is_off($animation) ? ' data-animation="'.esc_attr(pizzahouse_get_animation_classes($animation)).'"' : '')
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
			. '>'
				. ($content)
				. ($title == '' ? '' : ('<p class="sc_quote_title">' . ($cite!='' ? '<a href="'.esc_url($cite).'">' : '') . ($title) . ($cite!='' ? '</a>' : '') . '</p>'))
			.'</blockquote>';
		return apply_filters('pizzahouse_shortcode_output', $output, 'trx_quote', $atts, $content);
	}
	add_shortcode('trx_quote', 'pizzahouse_sc_quote');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pizzahouse_sc_quote_reg_shortcodes' ) ) {
	//add_action('pizzahouse_action_shortcodes_list', 'pizzahouse_sc_quote_reg_shortcodes');
	function pizzahouse_sc_quote_reg_shortcodes() {
	
		pizzahouse_sc_map("trx_quote", array(
			"title" => esc_html__("Quote", "pizzahouse"),
			"desc" => wp_kses_data( __("Quote text", "pizzahouse") ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"cite" => array(
					"title" => esc_html__("Quote cite", "pizzahouse"),
					"desc" => wp_kses_data( __("URL for quote cite", "pizzahouse") ),
					"value" => "",
					"type" => "text"
				),
				"title" => array(
					"title" => esc_html__("Title (author)", "pizzahouse"),
					"desc" => wp_kses_data( __("Quote title (author name)", "pizzahouse") ),
					"value" => "",
					"type" => "text"
				),
				"_content_" => array(
					"title" => esc_html__("Quote content", "pizzahouse"),
					"desc" => wp_kses_data( __("Quote content", "pizzahouse") ),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"width" => pizzahouse_shortcodes_width(),
				"top" => pizzahouse_get_sc_param('top'),
				"bottom" => pizzahouse_get_sc_param('bottom'),
				"left" => pizzahouse_get_sc_param('left'),
				"right" => pizzahouse_get_sc_param('right'),
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
if ( !function_exists( 'pizzahouse_sc_quote_reg_shortcodes_vc' ) ) {
	//add_action('pizzahouse_action_shortcodes_list_vc', 'pizzahouse_sc_quote_reg_shortcodes_vc');
	function pizzahouse_sc_quote_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_quote",
			"name" => esc_html__("Quote", "pizzahouse"),
			"description" => wp_kses_data( __("Quote text", "pizzahouse") ),
			"category" => esc_html__('Content', 'pizzahouse'),
			'icon' => 'icon_trx_quote',
			"class" => "trx_sc_single trx_sc_quote",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "cite",
					"heading" => esc_html__("Quote cite", "pizzahouse"),
					"description" => wp_kses_data( __("URL for the quote cite link", "pizzahouse") ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title (author)", "pizzahouse"),
					"description" => wp_kses_data( __("Quote title (author name)", "pizzahouse") ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "content",
					"heading" => esc_html__("Quote content", "pizzahouse"),
					"description" => wp_kses_data( __("Quote content", "pizzahouse") ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				pizzahouse_get_vc_param('id'),
				pizzahouse_get_vc_param('class'),
				pizzahouse_get_vc_param('animation'),
				pizzahouse_get_vc_param('css'),
				pizzahouse_vc_width(),
				pizzahouse_get_vc_param('margin_top'),
				pizzahouse_get_vc_param('margin_bottom'),
				pizzahouse_get_vc_param('margin_left'),
				pizzahouse_get_vc_param('margin_right')
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Quote extends Pizzahouse_VC_ShortCodeSingle {}
	}
}
?>