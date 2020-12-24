<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('pizzahouse_sc_search_theme_setup')) {
	add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_sc_search_theme_setup' );
	function pizzahouse_sc_search_theme_setup() {
		add_action('pizzahouse_action_shortcodes_list', 		'pizzahouse_sc_search_reg_shortcodes');
		if (function_exists('pizzahouse_exists_visual_composer') && pizzahouse_exists_visual_composer())
			add_action('pizzahouse_action_shortcodes_list_vc','pizzahouse_sc_search_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_search id="unique_id" open="yes|no"]
*/

if (!function_exists('pizzahouse_sc_search')) {	
	function pizzahouse_sc_search($atts, $content=null){	
		if (pizzahouse_in_shortcode_blogger()) return '';
		extract(pizzahouse_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "",
			"state" => "",
			"ajax" => "",
			"title" => esc_html__('Search', 'pizzahouse'),
			"scheme" => "original",
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
		if ($style == 'fullscreen') {
			if (empty($ajax)) $ajax = "no";
			if (empty($state)) $state = "closed";
		} else if ($style == 'expand') {
			if (empty($ajax)) $ajax = pizzahouse_get_theme_option('use_ajax_search');
			if (empty($state)) $state = "closed";
		} else if ($style == 'slide') {
			if (empty($ajax)) $ajax = pizzahouse_get_theme_option('use_ajax_search');
			if (empty($state)) $state = "closed";
		} else {
			if (empty($ajax)) $ajax = pizzahouse_get_theme_option('use_ajax_search');
			if (empty($state)) $state = "fixed";
		}
		// Load core messages
		pizzahouse_enqueue_messages();
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') . ' class="search_wrap search_style_'.esc_attr($style).' search_state_'.esc_attr($state)
						. (pizzahouse_param_is_on($ajax) ? ' search_ajax' : '')
						. ($class ? ' '.esc_attr($class) : '')
						. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!pizzahouse_param_is_off($animation) ? ' data-animation="'.esc_attr(pizzahouse_get_animation_classes($animation)).'"' : '')
					. '>
						<div class="search_form_wrap">
							<form role="search" method="get" class="search_form" action="' . esc_url(home_url('/')) . '">
								<button type="submit" class="search_submit icon-search-light" title="' . ($state=='closed' ? esc_attr__('Open search', 'pizzahouse') : esc_attr__('Start search', 'pizzahouse')) . '"></button>
								<input type="text" class="search_field" placeholder="' . esc_attr($title) . '" value="' . esc_attr(get_search_query()) . '" name="s" />'
								. ($style == 'fullscreen' ? '<a class="search_close icon-cancel"></a>' : '')
							. '</form>
						</div>'
						. (pizzahouse_param_is_on($ajax) ? '<div class="search_results widget_area' . ($scheme && !pizzahouse_param_is_off($scheme) && !pizzahouse_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') . '"><a class="search_results_close icon-cancel"></a><div class="search_results_content"></div></div>' : '')
					. '</div>';
		return apply_filters('pizzahouse_shortcode_output', $output, 'trx_search', $atts, $content);
	}
	add_shortcode('trx_search', 'pizzahouse_sc_search');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pizzahouse_sc_search_reg_shortcodes' ) ) {
	//add_action('pizzahouse_action_shortcodes_list', 'pizzahouse_sc_search_reg_shortcodes');
	function pizzahouse_sc_search_reg_shortcodes() {
	
		pizzahouse_sc_map("trx_search", array(
			"title" => esc_html__("Search", 'pizzahouse'),
			"desc" => wp_kses_data( __("Show search form", 'pizzahouse') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", 'pizzahouse'),
					"desc" => wp_kses_data( __("Select style to display search field", 'pizzahouse') ),
					"value" => "regular",
					"options" => pizzahouse_get_list_search_styles(),
					"type" => "checklist"
				),
				"state" => array(
					"title" => esc_html__("State", 'pizzahouse'),
					"desc" => wp_kses_data( __("Select search field initial state", 'pizzahouse') ),
					"value" => "fixed",
					"options" => array(
						"fixed"  => esc_html__('Fixed',  'pizzahouse'),
						"opened" => esc_html__('Opened', 'pizzahouse'),
						"closed" => esc_html__('Closed', 'pizzahouse')
					),
					"type" => "checklist"
				),
				"title" => array(
					"title" => esc_html__("Title", 'pizzahouse'),
					"desc" => wp_kses_data( __("Title (placeholder) for the search field", 'pizzahouse') ),
					"value" => esc_html__("Search &hellip;", 'pizzahouse'),
					"type" => "text"
				),
				"ajax" => array(
					"title" => esc_html__("AJAX", 'pizzahouse'),
					"desc" => wp_kses_data( __("Search via AJAX or reload page", 'pizzahouse') ),
					"value" => "yes",
					"options" => pizzahouse_get_sc_param('yes_no'),
					"type" => "switch"
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
if ( !function_exists( 'pizzahouse_sc_search_reg_shortcodes_vc' ) ) {
	//add_action('pizzahouse_action_shortcodes_list_vc', 'pizzahouse_sc_search_reg_shortcodes_vc');
	function pizzahouse_sc_search_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_search",
			"name" => esc_html__("Search form", 'pizzahouse'),
			"description" => wp_kses_data( __("Insert search form", 'pizzahouse') ),
			"category" => esc_html__('Content', 'pizzahouse'),
			'icon' => 'icon_trx_search',
			"class" => "trx_sc_single trx_sc_search",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'pizzahouse'),
					"description" => wp_kses_data( __("Select style to display search field", 'pizzahouse') ),
					"class" => "",
					"value" => pizzahouse_get_list_search_styles(),
					"type" => "dropdown"
				),
				array(
					"param_name" => "state",
					"heading" => esc_html__("State", 'pizzahouse'),
					"description" => wp_kses_data( __("Select search field initial state", 'pizzahouse') ),
					"class" => "",
					"value" => array(
						esc_html__('Fixed', 'pizzahouse')  => "fixed",
						esc_html__('Opened', 'pizzahouse') => "opened",
						esc_html__('Closed', 'pizzahouse') => "closed"
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'pizzahouse'),
					"description" => wp_kses_data( __("Title (placeholder) for the search field", 'pizzahouse') ),
					"admin_label" => true,
					"class" => "",
					"value" => esc_html__("Search &hellip;", 'pizzahouse'),
					"type" => "textfield"
				),
				array(
					"param_name" => "ajax",
					"heading" => esc_html__("AJAX", 'pizzahouse'),
					"description" => wp_kses_data( __("Search via AJAX or reload page", 'pizzahouse') ),
					"class" => "",
					"value" => array(esc_html__('Use AJAX search', 'pizzahouse') => 'yes'),
					"type" => "checkbox"
				),
				pizzahouse_get_vc_param('id'),
				pizzahouse_get_vc_param('class'),
				pizzahouse_get_vc_param('animation'),
				pizzahouse_get_vc_param('css'),
				pizzahouse_get_vc_param('margin_top'),
				pizzahouse_get_vc_param('margin_bottom'),
				pizzahouse_get_vc_param('margin_left'),
				pizzahouse_get_vc_param('margin_right')
			)
		) );
		
		class WPBakeryShortCode_Trx_Search extends Pizzahouse_VC_ShortCodeSingle {}
	}
}
?>