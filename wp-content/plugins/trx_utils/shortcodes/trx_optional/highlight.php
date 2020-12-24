<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('pizzahouse_sc_highlight_theme_setup')) {
	add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_sc_highlight_theme_setup' );
	function pizzahouse_sc_highlight_theme_setup() {
		add_action('pizzahouse_action_shortcodes_list', 		'pizzahouse_sc_highlight_reg_shortcodes');
		if (function_exists('pizzahouse_exists_visual_composer') && pizzahouse_exists_visual_composer())
			add_action('pizzahouse_action_shortcodes_list_vc','pizzahouse_sc_highlight_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_highlight id="unique_id" color="fore_color's_name_or_#rrggbb" backcolor="back_color's_name_or_#rrggbb" style="custom_style"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_highlight]
*/

if (!function_exists('pizzahouse_sc_highlight')) {
	function pizzahouse_sc_highlight($atts, $content=null){
		if (pizzahouse_in_shortcode_blogger()) return '';
		extract(pizzahouse_html_decode(shortcode_atts(array(
			// Individual params
			"color" => "",
			"bg_color" => "",
			"font_size" => "",
			"type" => "1",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		$css .= ($color != '' ? 'color:' . esc_attr($color) . ';' : '')
			.($bg_color != '' ? 'background-color:' . esc_attr($bg_color) . ';' : '')
			.($font_size != '' ? 'font-size:' . esc_attr(pizzahouse_prepare_css_value($font_size)) . '; line-height: 1em;' : '');
		$output = '<span' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_highlight'.($type>0 ? ' sc_highlight_style_'.esc_attr($type) : ''). (!empty($class) ? ' '.esc_attr($class) : '').'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>' 
				. do_shortcode($content) 
				. '</span>';
		return apply_filters('pizzahouse_shortcode_output', $output, 'trx_highlight', $atts, $content);
	}
	add_shortcode('trx_highlight', 'pizzahouse_sc_highlight');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pizzahouse_sc_highlight_reg_shortcodes' ) ) {
	//add_action('pizzahouse_action_shortcodes_list', 'pizzahouse_sc_highlight_reg_shortcodes');
	function pizzahouse_sc_highlight_reg_shortcodes() {
	
		pizzahouse_sc_map("trx_highlight", array(
			"title" => esc_html__("Highlight text", 'pizzahouse'),
			"desc" => wp_kses_data( __("Highlight text with selected color, background color and other styles", 'pizzahouse') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"type" => array(
					"title" => esc_html__("Type", 'pizzahouse'),
					"desc" => wp_kses_data( __("Highlight type", 'pizzahouse') ),
					"value" => "1",
					"type" => "checklist",
					"options" => array(
						0 => esc_html__('Custom', 'pizzahouse'),
						1 => esc_html__('Type 1', 'pizzahouse'),
						2 => esc_html__('Type 2', 'pizzahouse'),
						3 => esc_html__('Type 3', 'pizzahouse')
					)
				),
				"color" => array(
					"title" => esc_html__("Color", "pizzahouse"),
					"desc" => wp_kses_data( __("Color for the highlighted text", "pizzahouse") ),
					"divider" => true,
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", 'pizzahouse'),
					"desc" => wp_kses_data( __("Background color for the highlighted text", 'pizzahouse') ),
					"value" => "",
					"type" => "color"
				),
				"font_size" => array(
					"title" => esc_html__("Font size", 'pizzahouse'),
					"desc" => wp_kses_data( __("Font size of the highlighted text (default - in pixels, allows any CSS units of measure)", 'pizzahouse') ),
					"value" => "",
					"type" => "text"
				),
				"_content_" => array(
					"title" => esc_html__("Highlighting content", 'pizzahouse'),
					"desc" => wp_kses_data( __("Content for highlight", 'pizzahouse') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"id" => pizzahouse_get_sc_param('id'),
				"class" => pizzahouse_get_sc_param('class'),
				"css" => pizzahouse_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pizzahouse_sc_highlight_reg_shortcodes_vc' ) ) {
	//add_action('pizzahouse_action_shortcodes_list_vc', 'pizzahouse_sc_highlight_reg_shortcodes_vc');
	function pizzahouse_sc_highlight_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_highlight",
			"name" => esc_html__("Highlight text", 'pizzahouse'),
			"description" => wp_kses_data( __("Highlight text with selected color, background color and other styles", 'pizzahouse') ),
			"category" => esc_html__('Content', 'pizzahouse'),
			'icon' => 'icon_trx_highlight',
			"class" => "trx_sc_single trx_sc_highlight",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "type",
					"heading" => esc_html__("Type", 'pizzahouse'),
					"description" => wp_kses_data( __("Highlight type", 'pizzahouse') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
							esc_html__('Custom', 'pizzahouse') => 0,
							esc_html__('Type 1', 'pizzahouse') => 1,
							esc_html__('Type 2', 'pizzahouse') => 2,
							esc_html__('Type 3', 'pizzahouse') => 3
						),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", 'pizzahouse'),
					"description" => wp_kses_data( __("Color for the highlighted text", 'pizzahouse') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'pizzahouse'),
					"description" => wp_kses_data( __("Background color for the highlighted text", 'pizzahouse') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'pizzahouse'),
					"description" => wp_kses_data( __("Font size for the highlighted text (default - in pixels, allows any CSS units of measure)", 'pizzahouse') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "content",
					"heading" => esc_html__("Highlight text", 'pizzahouse'),
					"description" => wp_kses_data( __("Content for highlight", 'pizzahouse') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				pizzahouse_get_vc_param('id'),
				pizzahouse_get_vc_param('class'),
				pizzahouse_get_vc_param('css')
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Highlight extends Pizzahouse_VC_ShortCodeSingle {}
	}
}
?>