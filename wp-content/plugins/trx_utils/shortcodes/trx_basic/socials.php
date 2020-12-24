<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('pizzahouse_sc_socials_theme_setup')) {
	add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_sc_socials_theme_setup' );
	function pizzahouse_sc_socials_theme_setup() {
		add_action('pizzahouse_action_shortcodes_list', 		'pizzahouse_sc_socials_reg_shortcodes');
		if (function_exists('pizzahouse_exists_visual_composer') && pizzahouse_exists_visual_composer())
			add_action('pizzahouse_action_shortcodes_list_vc','pizzahouse_sc_socials_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_socials id="unique_id" size="small"]
	[trx_social_item name="facebook" url="profile url" icon="path for the icon"]
	[trx_social_item name="twitter" url="profile url"]
[/trx_socials]
*/

if (!function_exists('pizzahouse_sc_socials')) {	
	function pizzahouse_sc_socials($atts, $content=null){	
		if (pizzahouse_in_shortcode_blogger()) return '';
		extract(pizzahouse_html_decode(shortcode_atts(array(
			// Individual params
			"size" => "small",		// tiny | small | medium | large
			"shape" => "square",	// round | square
			"type" => pizzahouse_get_theme_setting('socials_type'),	// icons | images
			"socials" => "",
			"custom" => "no",
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
		pizzahouse_storage_set('sc_social_data', array(
			'icons' => false,
            'type' => $type
            )
        );
		if (!empty($socials)) {
			$allowed = explode('|', $socials);
			$list = array();
			for ($i=0; $i<count($allowed); $i++) {
				$s = explode('=', $allowed[$i]);
				if (!empty($s[1])) {
					$list[] = array(
						'icon'	=> $type=='images' ? pizzahouse_get_socials_url($s[0]) : 'icon-'.trim($s[0]),
						'url'	=> $s[1]
						);
				}
			}
			if (count($list) > 0) pizzahouse_storage_set_array('sc_social_data', 'icons', $list);
		} else if (pizzahouse_param_is_off($custom))
			$content = do_shortcode($content);
		if (pizzahouse_storage_get_array('sc_social_data', 'icons')===false) pizzahouse_storage_set_array('sc_social_data', 'icons', pizzahouse_get_custom_option('social_icons'));
		$output = pizzahouse_prepare_socials(pizzahouse_storage_get_array('sc_social_data', 'icons'));
		$output = $output
			? '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_socials sc_socials_type_' . esc_attr($type) . ' sc_socials_shape_' . esc_attr($shape) . ' sc_socials_size_' . esc_attr($size) . (!empty($class) ? ' '.esc_attr($class) : '') . '"' 
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. (!pizzahouse_param_is_off($animation) ? ' data-animation="'.esc_attr(pizzahouse_get_animation_classes($animation)).'"' : '')
				. '>' 
				. ($output)
				. '</div>'
			: '';
		return apply_filters('pizzahouse_shortcode_output', $output, 'trx_socials', $atts, $content);
	}
	add_shortcode('trx_socials', 'pizzahouse_sc_socials');
}


if (!function_exists('pizzahouse_sc_social_item')) {	
	function pizzahouse_sc_social_item($atts, $content=null){	
		if (pizzahouse_in_shortcode_blogger()) return '';
		extract(pizzahouse_html_decode(shortcode_atts(array(
			// Individual params
			"name" => "",
			"url" => "",
			"icon" => ""
		), $atts)));
		if (!empty($name) && empty($icon)) {
			$type = pizzahouse_storage_get_array('sc_social_data', 'type');
			if ($type=='images') {
				if (file_exists(pizzahouse_get_socials_dir($name.'.png')))
					$icon = pizzahouse_get_socials_url($name.'.png');
			} else
				$icon = 'icon-'.esc_attr($name);
		}
		if (!empty($icon) && !empty($url)) {
			if (pizzahouse_storage_get_array('sc_social_data', 'icons')===false) pizzahouse_storage_set_array('sc_social_data', 'icons', array());
			pizzahouse_storage_set_array2('sc_social_data', 'icons', '', array(
				'icon' => $icon,
				'url' => $url
				)
			);
		}
		return '';
	}
	add_shortcode('trx_social_item', 'pizzahouse_sc_social_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pizzahouse_sc_socials_reg_shortcodes' ) ) {
	//add_action('pizzahouse_action_shortcodes_list', 'pizzahouse_sc_socials_reg_shortcodes');
	function pizzahouse_sc_socials_reg_shortcodes() {
	
		pizzahouse_sc_map("trx_socials", array(
			"title" => esc_html__("Social icons", 'pizzahouse'),
			"desc" => wp_kses_data( __("List of social icons (with hovers)", 'pizzahouse') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"type" => array(
					"title" => esc_html__("Icon's type", 'pizzahouse'),
					"desc" => wp_kses_data( __("Type of the icons - images or font icons", 'pizzahouse') ),
					"value" => pizzahouse_get_theme_setting('socials_type'),
					"options" => array(
						'icons' => esc_html__('Icons', 'pizzahouse'),
						'images' => esc_html__('Images', 'pizzahouse')
					),
					"type" => "checklist"
				), 
				"size" => array(
					"title" => esc_html__("Icon's size", 'pizzahouse'),
					"desc" => wp_kses_data( __("Size of the icons", 'pizzahouse') ),
					"value" => "small",
					"options" => pizzahouse_get_sc_param('sizes'),
					"type" => "checklist"
				), 
				"shape" => array(
					"title" => esc_html__("Icon's shape", 'pizzahouse'),
					"desc" => wp_kses_data( __("Shape of the icons", 'pizzahouse') ),
					"value" => "square",
					"options" => pizzahouse_get_sc_param('shapes'),
					"type" => "checklist"
				), 
				"socials" => array(
					"title" => esc_html__("Manual socials list", 'pizzahouse'),
					"desc" => wp_kses_data( __("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebook.com/my_profile. If empty - use socials from Theme options.", 'pizzahouse') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"custom" => array(
					"title" => esc_html__("Custom socials", 'pizzahouse'),
					"desc" => wp_kses_data( __("Make custom icons from inner shortcodes (prepare it on tabs)", 'pizzahouse') ),
					"divider" => true,
					"value" => "no",
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
			),
			"children" => array(
				"name" => "trx_social_item",
				"title" => esc_html__("Custom social item", 'pizzahouse'),
				"desc" => wp_kses_data( __("Custom social item: name, profile url and icon url", 'pizzahouse') ),
				"decorate" => false,
				"container" => false,
				"params" => array(
					"name" => array(
						"title" => esc_html__("Social name", 'pizzahouse'),
						"desc" => wp_kses_data( __("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", 'pizzahouse') ),
						"value" => "",
						"type" => "text"
					),
					"url" => array(
						"title" => esc_html__("Your profile URL", 'pizzahouse'),
						"desc" => wp_kses_data( __("URL of your profile in specified social network", 'pizzahouse') ),
						"value" => "",
						"type" => "text"
					),
					"icon" => array(
						"title" => esc_html__("URL (source) for icon file", 'pizzahouse'),
						"desc" => wp_kses_data( __("Select or upload image or write URL from other site for the current social icon", 'pizzahouse') ),
						"readonly" => false,
						"value" => "",
						"type" => "media"
					)
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pizzahouse_sc_socials_reg_shortcodes_vc' ) ) {
	//add_action('pizzahouse_action_shortcodes_list_vc', 'pizzahouse_sc_socials_reg_shortcodes_vc');
	function pizzahouse_sc_socials_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_socials",
			"name" => esc_html__("Social icons", 'pizzahouse'),
			"description" => wp_kses_data( __("Custom social icons", 'pizzahouse') ),
			"category" => esc_html__('Content', 'pizzahouse'),
			'icon' => 'icon_trx_socials',
			"class" => "trx_sc_collection trx_sc_socials",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"as_parent" => array('only' => 'trx_social_item'),
			"params" => array_merge(array(
				array(
					"param_name" => "type",
					"heading" => esc_html__("Icon's type", "pizzahouse"),
					"description" => wp_kses_data( __("Type of the icons - images or font icons", "pizzahouse") ),
					"class" => "",
					"std" => pizzahouse_get_theme_setting('socials_type'),
					"value" => array(
						esc_html__('Icons', 'pizzahouse') => 'icons',
						esc_html__('Images', 'pizzahouse') => 'images'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "size",
					"heading" => esc_html__("Icon's size", "pizzahouse"),
					"description" => wp_kses_data( __("Size of the icons", "pizzahouse") ),
					"class" => "",
					"std" => "small",
					"value" => array_flip((array)pizzahouse_get_sc_param('sizes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "shape",
					"heading" => esc_html__("Icon's shape", "pizzahouse"),
					"description" => wp_kses_data( __("Shape of the icons", "pizzahouse") ),
					"class" => "",
					"std" => "square",
					"value" => array_flip((array)pizzahouse_get_sc_param('shapes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "socials",
					"heading" => esc_html__("Manual socials list", "pizzahouse"),
					"description" => wp_kses_data( __("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebook.com/my_profile. If empty - use socials from Theme options.", "pizzahouse") ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "custom",
					"heading" => esc_html__("Custom socials", "pizzahouse"),
					"description" => wp_kses_data( __("Make custom icons from inner shortcodes (prepare it on tabs)", "pizzahouse") ),
					"class" => "",
					"value" => array(esc_html__('Custom socials', 'pizzahouse') => 'yes'),
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
			))
		) );
		
		
		vc_map( array(
			"base" => "trx_social_item",
			"name" => esc_html__("Custom social item", "pizzahouse"),
			"description" => wp_kses_data( __("Custom social item: name, profile url and icon url", "pizzahouse") ),
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => false,
			'icon' => 'icon_trx_social_item',
			"class" => "trx_sc_single trx_sc_social_item",
			"as_child" => array('only' => 'trx_socials'),
			"as_parent" => array('except' => 'trx_socials'),
			"params" => array(
				array(
					"param_name" => "name",
					"heading" => esc_html__("Social name", "pizzahouse"),
					"description" => wp_kses_data( __("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", "pizzahouse") ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "url",
					"heading" => esc_html__("Your profile URL", 'pizzahouse'),
					"description" => wp_kses_data( __("URL of your profile in specified social network", 'pizzahouse') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("URL (source) for icon file", 'pizzahouse'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for the current social icon", 'pizzahouse') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				)
			)
		) );
		
		class WPBakeryShortCode_Trx_Socials extends PIZZAHOUSE_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Social_Item extends Pizzahouse_VC_ShortCodeSingle {}
	}
}
?>