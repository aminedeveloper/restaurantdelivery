<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('pizzahouse_sc_infobox_theme_setup')) {
	add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_sc_infobox_theme_setup' );
	function pizzahouse_sc_infobox_theme_setup() {
		add_action('pizzahouse_action_shortcodes_list', 		'pizzahouse_sc_infobox_reg_shortcodes');
		if (function_exists('pizzahouse_exists_visual_composer') && pizzahouse_exists_visual_composer())
			add_action('pizzahouse_action_shortcodes_list_vc','pizzahouse_sc_infobox_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_infobox id="unique_id" style="regular|info|success|error|result" static="0|1"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_infobox]
*/

if (!function_exists('pizzahouse_sc_infobox')) {
	function pizzahouse_sc_infobox($atts, $content=null){
		if (pizzahouse_in_shortcode_blogger()) return '';
		extract(pizzahouse_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "regular",
			"closeable" => "no",
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . pizzahouse_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= ($color !== '' ? 'color:' . esc_attr($color) .';' : '')
			. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) .';' : '');
		if (empty($icon)) {
			if ($style=='regular')
				$icon = 'icon-cog';
			else if ($style=='success')
				$icon = 'icon-check';
			else if ($style=='error')
				$icon = 'icon-attention';
			else if ($style=='info')
				$icon = 'icon-info';
		} else if ($icon=='none')
			$icon = '';

		$content = do_shortcode($content);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_infobox sc_infobox_style_' . esc_attr($style) 
					. (pizzahouse_param_is_on($closeable) ? ' sc_infobox_closeable' : '')
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. ($icon!='' && !pizzahouse_param_is_inherit($icon) ? ' sc_infobox_iconed '. esc_attr($icon) : '')
					. '"'
				. (!pizzahouse_param_is_off($animation) ? ' data-animation="'.esc_attr(pizzahouse_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>'
				. trim($content)
				. '</div>';
		return apply_filters('pizzahouse_shortcode_output', $output, 'trx_infobox', $atts, $content);
	}
	add_shortcode('trx_infobox', 'pizzahouse_sc_infobox');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pizzahouse_sc_infobox_reg_shortcodes' ) ) {
	//add_action('pizzahouse_action_shortcodes_list', 'pizzahouse_sc_infobox_reg_shortcodes');
	function pizzahouse_sc_infobox_reg_shortcodes() {
	
		pizzahouse_sc_map("trx_infobox", array(
			"title" => esc_html__("Infobox", "pizzahouse"),
			"desc" => wp_kses_data( __("Insert infobox into your post (page)", "pizzahouse") ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", "pizzahouse"),
					"desc" => wp_kses_data( __("Infobox style", "pizzahouse") ),
					"value" => "regular",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => array(
						'regular' => esc_html__('Regular', 'pizzahouse'),
						'info' => esc_html__('Info', 'pizzahouse'),
						'success' => esc_html__('Success', 'pizzahouse'),
						'error' => esc_html__('Error', 'pizzahouse')
					)
				),
				"closeable" => array(
					"title" => esc_html__("Closeable box", "pizzahouse"),
					"desc" => wp_kses_data( __("Create closeable box (with close button)", "pizzahouse") ),
					"value" => "no",
					"type" => "switch",
					"options" => pizzahouse_get_sc_param('yes_no')
				),
				"icon" => array(
					"title" => esc_html__("Custom icon",  'pizzahouse'),
					"desc" => wp_kses_data( __('Select icon for the infobox from Fontello icons set. If empty - use default icon',  'pizzahouse') ),
					"value" => "",
					"type" => "icons",
					"options" => pizzahouse_get_sc_param('icons')
				),
				"color" => array(
					"title" => esc_html__("Text color", "pizzahouse"),
					"desc" => wp_kses_data( __("Any color for text and headers", "pizzahouse") ),
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", "pizzahouse"),
					"desc" => wp_kses_data( __("Any background color for this infobox", "pizzahouse") ),
					"value" => "",
					"type" => "color"
				),
				"_content_" => array(
					"title" => esc_html__("Infobox content", "pizzahouse"),
					"desc" => wp_kses_data( __("Content for infobox", "pizzahouse") ),
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
				"animation" => pizzahouse_get_sc_param('animation'),
				"css" => pizzahouse_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pizzahouse_sc_infobox_reg_shortcodes_vc' ) ) {
	//add_action('pizzahouse_action_shortcodes_list_vc', 'pizzahouse_sc_infobox_reg_shortcodes_vc');
	function pizzahouse_sc_infobox_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_infobox",
			"name" => esc_html__("Infobox", "pizzahouse"),
			"description" => wp_kses_data( __("Box with info or error message", "pizzahouse") ),
			"category" => esc_html__('Content', 'pizzahouse'),
			'icon' => 'icon_trx_infobox',
			"class" => "trx_sc_container trx_sc_infobox",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", "pizzahouse"),
					"description" => wp_kses_data( __("Infobox style", "pizzahouse") ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
							esc_html__('Regular', 'pizzahouse') => 'regular',
							esc_html__('Info', 'pizzahouse') => 'info',
							esc_html__('Success', 'pizzahouse') => 'success',
							esc_html__('Error', 'pizzahouse') => 'error',
							esc_html__('Result', 'pizzahouse') => 'result'
						),
					"type" => "dropdown"
				),
				array(
					"param_name" => "closeable",
					"heading" => esc_html__("Closeable", "pizzahouse"),
					"description" => wp_kses_data( __("Create closeable box (with close button)", "pizzahouse") ),
					"class" => "",
					"value" => array(esc_html__('Close button', 'pizzahouse') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Custom icon", "pizzahouse"),
					"description" => wp_kses_data( __("Select icon for the infobox from Fontello icons set. If empty - use default icon", "pizzahouse") ),
					"class" => "",
					"value" => pizzahouse_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", "pizzahouse"),
					"description" => wp_kses_data( __("Any color for the text and headers", "pizzahouse") ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", "pizzahouse"),
					"description" => wp_kses_data( __("Any background color for this infobox", "pizzahouse") ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				pizzahouse_get_vc_param('id'),
				pizzahouse_get_vc_param('class'),
				pizzahouse_get_vc_param('animation'),
				pizzahouse_get_vc_param('css'),
				pizzahouse_get_vc_param('margin_top'),
				pizzahouse_get_vc_param('margin_bottom'),
				pizzahouse_get_vc_param('margin_left'),
				pizzahouse_get_vc_param('margin_right')
			),
			'js_view' => 'VcTrxTextContainerView'
		) );
		
		class WPBakeryShortCode_Trx_Infobox extends PIZZAHOUSE_VC_ShortCodeContainer {}
	}
}
?>