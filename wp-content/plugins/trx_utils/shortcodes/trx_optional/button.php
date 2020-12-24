<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('pizzahouse_sc_button_theme_setup')) {
	add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_sc_button_theme_setup' );
	function pizzahouse_sc_button_theme_setup() {
		add_action('pizzahouse_action_shortcodes_list', 		'pizzahouse_sc_button_reg_shortcodes');
		if (function_exists('pizzahouse_exists_visual_composer') && pizzahouse_exists_visual_composer())
			add_action('pizzahouse_action_shortcodes_list_vc','pizzahouse_sc_button_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_button id="unique_id" type="square|round" fullsize="0|1" style="global|light|dark" size="mini|medium|big|huge|banner" icon="icon-name" link='#' target='']Button caption[/trx_button]
*/

if (!function_exists('pizzahouse_sc_button')) {
	function pizzahouse_sc_button($atts, $content=null){
		if (pizzahouse_in_shortcode_blogger()) return '';
		extract(pizzahouse_html_decode(shortcode_atts(array(
			// Individual params
			"type" => "square",
			"style" => "filled",
			"size" => "small",
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			"link" => "",
			"target" => "",
			"align" => "",
			"rel" => "",
			"popup" => "no",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . pizzahouse_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= pizzahouse_get_css_dimensions_from_values($width, $height)
			. ($color !== '' ? 'color:' . esc_attr($color) .';' : '')
			. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) . '; border-color:'. esc_attr($bg_color) .';' : '');
		if (pizzahouse_param_is_on($popup)) pizzahouse_enqueue_popup('magnific');
		$output = '<a href="' . (empty($link) ? '#' : $link) . '"'
			. (!empty($target) ? ' target="'.esc_attr($target).'"' : '')
			. (!empty($rel) ? ' rel="'.esc_attr($rel).'"' : '')
			. (!pizzahouse_param_is_off($animation) ? ' data-animation="'.esc_attr(pizzahouse_get_animation_classes($animation)).'"' : '')
			. ' class="sc_button sc_button_' . esc_attr($type) 
					. ' sc_button_style_' . esc_attr($style) 
					. ' sc_button_size_' . esc_attr($size)
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ($icon!='' ? '  sc_button_iconed '. esc_attr($icon) : '') 
					. (pizzahouse_param_is_on($popup) ? ' sc_popup_link' : '')
					. '"'
			. ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
			. '>'
			. do_shortcode($content)
			. '</a>';
		return apply_filters('pizzahouse_shortcode_output', $output, 'trx_button', $atts, $content);
	}
	add_shortcode('trx_button', 'pizzahouse_sc_button');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pizzahouse_sc_button_reg_shortcodes' ) ) {
	//add_action('pizzahouse_action_shortcodes_list', 'pizzahouse_sc_button_reg_shortcodes');
	function pizzahouse_sc_button_reg_shortcodes() {
	
		pizzahouse_sc_map("trx_button", array(
			"title" => esc_html__("Button", 'pizzahouse'),
			"desc" => wp_kses_data( __("Button with link", 'pizzahouse') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Caption", 'pizzahouse'),
					"desc" => wp_kses_data( __("Button caption", 'pizzahouse') ),
					"value" => "",
					"type" => "text"
				),
				"style" => array(
					"title" => esc_html__("Button's style", 'pizzahouse'),
					"desc" => wp_kses_data( __("Select button's style", 'pizzahouse') ),
					"value" => "default",
					"dir" => "horizontal",
					"options" => array(
						'filled' => esc_html__('Filled', 'pizzahouse'),
						'filled_2' => esc_html__('Filled 2', 'pizzahouse'),
						'filled_3' => esc_html__('Filled 3', 'pizzahouse'),
					),
					"type" => "checklist"
				), 
				"size" => array(
					"title" => esc_html__("Button's size", 'pizzahouse'),
					"desc" => wp_kses_data( __("Select button's size", 'pizzahouse') ),
					"value" => "small",
					"dir" => "horizontal",
					"options" => array(
						'small' => esc_html__('Small', 'pizzahouse'),
						'medium' => esc_html__('Medium', 'pizzahouse'),
						'large' => esc_html__('Large', 'pizzahouse')
					),
					"type" => "checklist"
				), 
				"icon" => array(
					"title" => esc_html__("Button's icon",  'pizzahouse'),
					"desc" => wp_kses_data( __('Select icon for the title from Fontello icons set',  'pizzahouse') ),
					"value" => "",
					"type" => "icons",
					"options" => pizzahouse_get_sc_param('icons')
				),
				"color" => array(
					"title" => esc_html__("Button's text color", 'pizzahouse'),
					"desc" => wp_kses_data( __("Any color for button's caption", 'pizzahouse') ),
					"std" => "",
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Button's backcolor", 'pizzahouse'),
					"desc" => wp_kses_data( __("Any color for button's background", 'pizzahouse') ),
					"value" => "",
					"type" => "color"
				),
				"align" => array(
					"title" => esc_html__("Button's alignment", 'pizzahouse'),
					"desc" => wp_kses_data( __("Align button to left, center or right", 'pizzahouse') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => pizzahouse_get_sc_param('align')
				), 
				"link" => array(
					"title" => esc_html__("Link URL", 'pizzahouse'),
					"desc" => wp_kses_data( __("URL for link on button click", 'pizzahouse') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"target" => array(
					"title" => esc_html__("Link target", 'pizzahouse'),
					"desc" => wp_kses_data( __("Target for link on button click", 'pizzahouse') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"popup" => array(
					"title" => esc_html__("Open link in popup", 'pizzahouse'),
					"desc" => wp_kses_data( __("Open link target in popup window", 'pizzahouse') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "no",
					"type" => "switch",
					"options" => pizzahouse_get_sc_param('yes_no')
				), 
				"rel" => array(
					"title" => esc_html__("Rel attribute", 'pizzahouse'),
					"desc" => wp_kses_data( __("Rel attribute for button's link (if need)", 'pizzahouse') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"width" => pizzahouse_shortcodes_width(),
				"height" => pizzahouse_shortcodes_height(),
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
if ( !function_exists( 'pizzahouse_sc_button_reg_shortcodes_vc' ) ) {
	//add_action('pizzahouse_action_shortcodes_list_vc', 'pizzahouse_sc_button_reg_shortcodes_vc');
	function pizzahouse_sc_button_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_button",
			"name" => esc_html__("Button", 'pizzahouse'),
			"description" => wp_kses_data( __("Button with link", 'pizzahouse') ),
			"category" => esc_html__('Content', 'pizzahouse'),
			'icon' => 'icon_trx_button',
			"class" => "trx_sc_single trx_sc_button",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "content",
					"heading" => esc_html__("Caption", 'pizzahouse'),
					"description" => wp_kses_data( __("Button caption", 'pizzahouse') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Button's style", 'pizzahouse'),
					"description" => wp_kses_data( __("Select button's style", 'pizzahouse') ),
					"class" => "",
					"value" => array(
						esc_html__('Filled', 'pizzahouse') => 'filled',
						esc_html__('Filled 2', 'pizzahouse') => 'filled_2',
						esc_html__('Filled 3', 'pizzahouse') => 'filled_3'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "size",
					"heading" => esc_html__("Button's size", 'pizzahouse'),
					"description" => wp_kses_data( __("Select button's size", 'pizzahouse') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Small', 'pizzahouse') => 'small',
						esc_html__('Medium', 'pizzahouse') => 'medium',
						esc_html__('Large', 'pizzahouse') => 'large'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Button's icon", 'pizzahouse'),
					"description" => wp_kses_data( __("Select icon for the title from Fontello icons set", 'pizzahouse') ),
					"class" => "",
					"value" => pizzahouse_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Button's text color", 'pizzahouse'),
					"description" => wp_kses_data( __("Any color for button's caption", 'pizzahouse') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Button's backcolor", 'pizzahouse'),
					"description" => wp_kses_data( __("Any color for button's background", 'pizzahouse') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Button's alignment", 'pizzahouse'),
					"description" => wp_kses_data( __("Align button to left, center or right", 'pizzahouse') ),
					"class" => "",
					"value" => array_flip((array)pizzahouse_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'pizzahouse'),
					"description" => wp_kses_data( __("URL for the link on button click", 'pizzahouse') ),
					"class" => "",
					"group" => esc_html__('Link', 'pizzahouse'),
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "target",
					"heading" => esc_html__("Link target", 'pizzahouse'),
					"description" => wp_kses_data( __("Target for the link on button click", 'pizzahouse') ),
					"class" => "",
					"group" => esc_html__('Link', 'pizzahouse'),
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "popup",
					"heading" => esc_html__("Open link in popup", 'pizzahouse'),
					"description" => wp_kses_data( __("Open link target in popup window", 'pizzahouse') ),
					"class" => "",
					"group" => esc_html__('Link', 'pizzahouse'),
					"value" => array(esc_html__('Open in popup', 'pizzahouse') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "rel",
					"heading" => esc_html__("Rel attribute", 'pizzahouse'),
					"description" => wp_kses_data( __("Rel attribute for the button's link (if need", 'pizzahouse') ),
					"class" => "",
					"group" => esc_html__('Link', 'pizzahouse'),
					"value" => "",
					"type" => "textfield"
				),
				pizzahouse_get_vc_param('id'),
				pizzahouse_get_vc_param('class'),
				pizzahouse_get_vc_param('animation'),
				pizzahouse_get_vc_param('css'),
				pizzahouse_vc_width(),
				pizzahouse_vc_height(),
				pizzahouse_get_vc_param('margin_top'),
				pizzahouse_get_vc_param('margin_bottom'),
				pizzahouse_get_vc_param('margin_left'),
				pizzahouse_get_vc_param('margin_right')
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Button extends Pizzahouse_VC_ShortCodeSingle {}
	}
}
?>