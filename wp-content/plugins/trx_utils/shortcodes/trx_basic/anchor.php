<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('pizzahouse_sc_anchor_theme_setup')) {
	add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_sc_anchor_theme_setup' );
	function pizzahouse_sc_anchor_theme_setup() {
		add_action('pizzahouse_action_shortcodes_list', 		'pizzahouse_sc_anchor_reg_shortcodes');
		if (function_exists('pizzahouse_exists_visual_composer') && pizzahouse_exists_visual_composer())
			add_action('pizzahouse_action_shortcodes_list_vc','pizzahouse_sc_anchor_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_anchor id="unique_id" description="Anchor description" title="Short Caption" icon="icon-class"]
*/

if (!function_exists('pizzahouse_sc_anchor')) {	
	function pizzahouse_sc_anchor($atts, $content = null) {
		if (pizzahouse_in_shortcode_blogger()) return '';
		extract(pizzahouse_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"description" => '',
			"icon" => '',
			"url" => "",
			"separator" => "no",
			// Common params
			"id" => ""
		), $atts)));
		$output = $id 
			? '<a id="'.esc_attr($id).'"'
				. ' class="sc_anchor"' 
				. ' title="' . ($title ? esc_attr($title) : '') . '"'
				. ' data-description="' . ($description ? esc_attr(pizzahouse_strmacros($description)) : ''). '"'
				. ' data-icon="' . ($icon ? $icon : '') . '"' 
				. ' data-url="' . ($url ? esc_attr($url) : '') . '"' 
				. ' data-separator="' . (pizzahouse_param_is_on($separator) ? 'yes' : 'no') . '"'
				. '></a>'
			: '';
		return apply_filters('pizzahouse_shortcode_output', $output, 'trx_anchor', $atts, $content);
	}
	add_shortcode("trx_anchor", "pizzahouse_sc_anchor");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pizzahouse_sc_anchor_reg_shortcodes' ) ) {
	//add_action('pizzahouse_action_shortcodes_list', 'pizzahouse_sc_anchor_reg_shortcodes');
	function pizzahouse_sc_anchor_reg_shortcodes() {
	
		pizzahouse_sc_map("trx_anchor", array(
			"title" => esc_html__("Anchor", 'pizzahouse'),
			"desc" => wp_kses_data( __("Insert anchor for the TOC (table of content)", 'pizzahouse') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"icon" => array(
					"title" => esc_html__("Anchor's icon",  'pizzahouse'),
					"desc" => wp_kses_data( __('Select icon for the anchor from Fontello icons set',  'pizzahouse') ),
					"value" => "",
					"type" => "icons",
					"options" => pizzahouse_get_sc_param('icons')
				),
				"title" => array(
					"title" => esc_html__("Short title", 'pizzahouse'),
					"desc" => wp_kses_data( __("Short title of the anchor (for the table of content)", 'pizzahouse') ),
					"value" => "",
					"type" => "text"
				),
				"description" => array(
					"title" => esc_html__("Long description", 'pizzahouse'),
					"desc" => wp_kses_data( __("Description for the popup (then hover on the icon). You can use:<br>'{{' and '}}' - to make the text italic,<br>'((' and '))' - to make the text bold,<br>'||' - to insert line break", 'pizzahouse') ),
					"value" => "",
					"type" => "text"
				),
				"url" => array(
					"title" => esc_html__("External URL", 'pizzahouse'),
					"desc" => wp_kses_data( __("External URL for this TOC item", 'pizzahouse') ),
					"value" => "",
					"type" => "text"
				),
				"separator" => array(
					"title" => esc_html__("Add separator", 'pizzahouse'),
					"desc" => wp_kses_data( __("Add separator under item in the TOC", 'pizzahouse') ),
					"value" => "no",
					"type" => "switch",
					"options" => pizzahouse_get_sc_param('yes_no')
				),
				"id" => pizzahouse_get_sc_param('id')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pizzahouse_sc_anchor_reg_shortcodes_vc' ) ) {
	//add_action('pizzahouse_action_shortcodes_list_vc', 'pizzahouse_sc_anchor_reg_shortcodes_vc');
	function pizzahouse_sc_anchor_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_anchor",
			"name" => esc_html__("Anchor", 'pizzahouse'),
			"description" => wp_kses_data( __("Insert anchor for the TOC (table of content)", 'pizzahouse') ),
			"category" => esc_html__('Content', 'pizzahouse'),
			'icon' => 'icon_trx_anchor',
			"class" => "trx_sc_single trx_sc_anchor",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Anchor's icon", 'pizzahouse'),
					"description" => wp_kses_data( __("Select icon for the anchor from Fontello icons set", 'pizzahouse') ),
					"class" => "",
					"value" => pizzahouse_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Short title", 'pizzahouse'),
					"description" => wp_kses_data( __("Short title of the anchor (for the table of content)", 'pizzahouse') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "description",
					"heading" => esc_html__("Long description", 'pizzahouse'),
					"description" => wp_kses_data( __("Description for the popup (then hover on the icon). You can use:<br>'{{' and '}}' - to make the text italic,<br>'((' and '))' - to make the text bold,<br>'||' - to insert line break", 'pizzahouse') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "url",
					"heading" => esc_html__("External URL", 'pizzahouse'),
					"description" => wp_kses_data( __("External URL for this TOC item", 'pizzahouse') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "separator",
					"heading" => esc_html__("Add separator", 'pizzahouse'),
					"description" => wp_kses_data( __("Add separator under item in the TOC", 'pizzahouse') ),
					"class" => "",
					"value" => array("Add separator" => "yes" ),
					"type" => "checkbox"
				),
				pizzahouse_get_vc_param('id')
			),
		) );
		
		class WPBakeryShortCode_Trx_Anchor extends Pizzahouse_VC_ShortCodeSingle {}
	}
}
?>