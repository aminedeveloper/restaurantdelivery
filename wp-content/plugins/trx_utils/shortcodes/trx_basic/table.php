<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('pizzahouse_sc_table_theme_setup')) {
	add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_sc_table_theme_setup' );
	function pizzahouse_sc_table_theme_setup() {
		add_action('pizzahouse_action_shortcodes_list', 		'pizzahouse_sc_table_reg_shortcodes');
		if (function_exists('pizzahouse_exists_visual_composer') && pizzahouse_exists_visual_composer())
			add_action('pizzahouse_action_shortcodes_list_vc','pizzahouse_sc_table_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_table id="unique_id" style="1"]
Table content, generated on one of many public internet resources, for example: http://www.impressivewebs.com/html-table-code-generator/
[/trx_table]
*/

if (!function_exists('pizzahouse_sc_table')) {	
	function pizzahouse_sc_table($atts, $content=null){	
		if (pizzahouse_in_shortcode_blogger()) return '';
		extract(pizzahouse_html_decode(shortcode_atts(array(
			// Individual params
			"align" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => "",
			"width" => "100%"
		), $atts)));
		$class .= ($class ? ' ' : '') . pizzahouse_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= pizzahouse_get_css_dimensions_from_values($width);
		$content = str_replace(
					array('<p><table', 'table></p>', '><br />'),
					array('<table', 'table>', '>'),
					html_entity_decode($content, ENT_COMPAT, 'UTF-8'));
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_table' 
					. (!empty($align) && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. '"'
				. (!pizzahouse_param_is_off($animation) ? ' data-animation="'.esc_attr(pizzahouse_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				.'>' 
				. do_shortcode($content) 
				. '</div>';
		return apply_filters('pizzahouse_shortcode_output', $output, 'trx_table', $atts, $content);
	}
	add_shortcode('trx_table', 'pizzahouse_sc_table');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pizzahouse_sc_table_reg_shortcodes' ) ) {
	//add_action('pizzahouse_action_shortcodes_list', 'pizzahouse_sc_table_reg_shortcodes');
	function pizzahouse_sc_table_reg_shortcodes() {
	
		pizzahouse_sc_map("trx_table", array(
			"title" => esc_html__("Table", "pizzahouse"),
			"desc" => wp_kses_data( __("Insert a table into post (page). ", "pizzahouse") ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"align" => array(
					"title" => esc_html__("Content alignment", "pizzahouse"),
					"desc" => wp_kses_data( __("Select alignment for each table cell", "pizzahouse") ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => pizzahouse_get_sc_param('align')
				),
				"_content_" => array(
					"title" => esc_html__("Table content", 'pizzahouse'),
					"desc" => wp_kses_data( __("Content, created with any table-generator", 'pizzahouse') ),
					"divider" => true,
					"rows" => 8,
					"value" => "Paste here table content, generated on one of many public internet resources, for example: http://www.impressivewebs.com/html-table-code-generator/ or http://html-tables.com/",
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
if ( !function_exists( 'pizzahouse_sc_table_reg_shortcodes_vc' ) ) {
	//add_action('pizzahouse_action_shortcodes_list_vc', 'pizzahouse_sc_table_reg_shortcodes_vc');
	function pizzahouse_sc_table_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_table",
			"name" => esc_html__("Table", 'pizzahouse'),
			"description" => wp_kses_data( __("Insert a table", 'pizzahouse') ),
			"category" => esc_html__('Content', 'pizzahouse'),
			'icon' => 'icon_trx_table',
			"class" => "trx_sc_container trx_sc_table",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "align",
					"heading" => esc_html__("Cells content alignment", 'pizzahouse'),
					"description" => wp_kses_data( __("Select alignment for each table cell", 'pizzahouse') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip((array)pizzahouse_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "content",
					"heading" => esc_html__("Table content", 'pizzahouse'),
					"description" => wp_kses_data( __("Content, created with any table-generator", 'pizzahouse') ),
					"class" => "",
					"value" => esc_html__("Paste here table content, generated on one of many public internet resources, for example: http://www.impressivewebs.com/html-table-code-generator/ or http://html-tables.com/", 'pizzahouse'),
					"type" => "textarea_html"
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
			'js_view' => 'VcTrxTextContainerView'
		) );
		
		class WPBakeryShortCode_Trx_Table extends PIZZAHOUSE_VC_ShortCodeContainer {}
	}
}
?>