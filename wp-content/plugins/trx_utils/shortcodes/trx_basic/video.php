<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('pizzahouse_sc_video_theme_setup')) {
	add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_sc_video_theme_setup' );
	function pizzahouse_sc_video_theme_setup() {
		add_action('pizzahouse_action_shortcodes_list', 		'pizzahouse_sc_video_reg_shortcodes');
		if (function_exists('pizzahouse_exists_visual_composer') && pizzahouse_exists_visual_composer())
			add_action('pizzahouse_action_shortcodes_list_vc','pizzahouse_sc_video_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_video id="unique_id" url="http://player.vimeo.com/video/20245032?title=0&amp;byline=0&amp;portrait=0" width="" height=""]

if (!function_exists('pizzahouse_sc_video')) {
	function pizzahouse_sc_video($atts, $content = null) {
		if (pizzahouse_in_shortcode_blogger()) return '';
		extract(pizzahouse_html_decode(shortcode_atts(array(
			// Individual params
			"url" => '',
			"src" => '',
			"image" => '',
			"ratio" => '16:9',
			"autoplay" => 'off',
			"align" => '',
			"bg_image" => '',
			"bg_top" => '',
			"bg_bottom" => '',
			"bg_left" => '',
			"bg_right" => '',
			"frame" => "on",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => '',
			"height" => '',
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
	
		if (empty($autoplay)) $autoplay = 'off';
		
		$ratio = empty($ratio) ? "16:9" : str_replace(array('/','\\','-'), ':', $ratio);
		$ratio_parts = explode(':', $ratio);
		if (empty($height) && empty($width)) {
			$width='100%';
			if (pizzahouse_param_is_off(pizzahouse_get_custom_option('substitute_video'))) $height="400";
		}
		$ed = pizzahouse_substr($width, -1);
		if (empty($height) && !empty($width) && $ed!='%') {
			$height = round($width / $ratio_parts[0] * $ratio_parts[1]);
		}
		if (!empty($height) && empty($width)) {
			$width = round($height * $ratio_parts[0] / $ratio_parts[1]);
		}
		$class .= ($class ? ' ' : '') . pizzahouse_get_css_position_as_classes($top, $right, $bottom, $left);
		$css_dim = pizzahouse_get_css_dimensions_from_values($width, $height);
		$css_bg = pizzahouse_get_css_paddings_from_values($bg_top, $bg_right, $bg_bottom, $bg_left);
	
		if ($src=='' && $url=='' && isset($atts[0])) {
			$src = $atts[0];
		}
		$url = $src!='' ? $src : $url;
		if ($image!='' && pizzahouse_param_is_off($image))
			$image = '';
		else {
			if (pizzahouse_param_is_on($autoplay) && is_singular() && !pizzahouse_storage_get('blog_streampage'))
				$image = '';
			else {
				if ($image > 0) {
					$attach = wp_get_attachment_image_src( $image, 'full' );
					if (isset($attach[0]) && $attach[0]!='')
						$image = $attach[0];
				}
				if ($bg_image) {
					$thumb_sizes = pizzahouse_get_thumb_sizes(array(
						'layout' => 'grid_3'
					));
					if (!is_single() || !empty($image)) $image = pizzahouse_get_resized_image_url(empty($image) ? get_the_ID() : $image, $thumb_sizes['w'], $thumb_sizes['h'], null, false, false, false);
				} else
					if (!is_single() || !empty($image)) $image = pizzahouse_get_resized_image_url(empty($image) ? get_the_ID() : $image, $ed!='%' ? $width : null, $height);
				if (empty($image) && (!is_singular() || pizzahouse_storage_get('blog_streampage')))	// || pizzahouse_param_is_off($autoplay)))
					$image = pizzahouse_get_video_cover_image($url);
			}
		}
		if ($bg_image > 0) {
			$attach = wp_get_attachment_image_src( $bg_image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$bg_image = $attach[0];
		}
		if ($bg_image) {
			$css_bg .= $css . 'background-image: url('.esc_url($bg_image).');';
			$css = $css_dim;
		} else {
			$css .= $css_dim;
		}
	
		$url = pizzahouse_get_video_player_url($src!='' ? $src : $url);
		
		$video = '<video' . ($id ? ' id="' . esc_attr($id) . '"' : '') 
			. ' class="sc_video"'
			. ' src="' . esc_url($url) . '"'
			. ' width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"' 
			. ' data-width="' . esc_attr($width) . '" data-height="' . esc_attr($height) . '"' 
			. ' data-ratio="'.esc_attr($ratio).'"'
			. ($image ? ' poster="'.esc_attr($image).'" data-image="'.esc_attr($image).'"' : '') 
			. (!pizzahouse_param_is_off($animation) ? ' data-animation="'.esc_attr(pizzahouse_get_animation_classes($animation)).'"' : '')
			. ($align && $align!='none' ? ' data-align="'.esc_attr($align).'"' : '')
			. ($class ? ' data-class="'.esc_attr($class).'"' : '')
			. ($bg_image ? ' data-bg-image="'.esc_attr($bg_image).'"' : '') 
			. ($css_bg!='' ? ' data-style="'.esc_attr($css_bg).'"' : '') 
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
			. (($image && pizzahouse_param_is_on(pizzahouse_get_custom_option('substitute_video'))) || (pizzahouse_param_is_on($autoplay) && is_singular() && !pizzahouse_storage_get('blog_streampage')) ? ' autoplay="autoplay"' : '')
			. ' controls="controls" loop="loop"'
			. '>'
			. '</video>';
		if (pizzahouse_param_is_off(pizzahouse_get_custom_option('substitute_video'))) {
			if (pizzahouse_param_is_on($frame)) $video = pizzahouse_get_video_frame($video, $image, $css, $css_bg);
		} else {
			if ((isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') && (isset($_POST['action']) && $_POST['action']=='vc_load_shortcode')) {
				$video = pizzahouse_substitute_video($video, $width, $height, false);
			}
		}
		if (pizzahouse_get_theme_option('use_mediaelement')=='yes')
			wp_enqueue_script('wp-mediaelement');
		return apply_filters('pizzahouse_shortcode_output', $video, 'trx_video', $atts, $content);
	}
	add_shortcode("trx_video", "pizzahouse_sc_video");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pizzahouse_sc_video_reg_shortcodes' ) ) {
	//add_action('pizzahouse_action_shortcodes_list', 'pizzahouse_sc_video_reg_shortcodes');
	function pizzahouse_sc_video_reg_shortcodes() {
	
		pizzahouse_sc_map("trx_video", array(
			"title" => esc_html__("Video", "pizzahouse"),
			"desc" => wp_kses_data( __("Insert video player", "pizzahouse") ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"url" => array(
					"title" => esc_html__("URL for video file", 'pizzahouse'),
					"desc" => wp_kses_data( __("Select video from media library or paste URL for video file from other site", 'pizzahouse') ),
					"readonly" => false,
					"value" => "",
					"type" => "media",
					"before" => array(
						'title' => esc_html__('Choose video', 'pizzahouse'),
						'action' => 'media_upload',
						'type' => 'video',
						'multiple' => false,
						'linked_field' => '',
						'captions' => array( 	
							'choose' => esc_html__('Choose video file', 'pizzahouse'),
							'update' => esc_html__('Select video file', 'pizzahouse')
						)
					),
					"after" => array(
						'icon' => 'icon-cancel',
						'action' => 'media_reset'
					)
				),
				"ratio" => array(
					"title" => esc_html__("Ratio", 'pizzahouse'),
					"desc" => wp_kses_data( __("Ratio of the video", 'pizzahouse') ),
					"value" => "16:9",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => array(
						"16:9" => esc_html__("16:9", 'pizzahouse'),
						"4:3" => esc_html__("4:3", 'pizzahouse')
					)
				),
				"autoplay" => array(
					"title" => esc_html__("Autoplay video", 'pizzahouse'),
					"desc" => wp_kses_data( __("Autoplay video on page load", 'pizzahouse') ),
					"value" => "off",
					"type" => "switch",
					"options" => pizzahouse_get_sc_param('on_off')
				),
				"align" => array(
					"title" => esc_html__("Align", 'pizzahouse'),
					"desc" => wp_kses_data( __("Select block alignment", 'pizzahouse') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => pizzahouse_get_sc_param('align')
				),
				"image" => array(
					"title" => esc_html__("Cover image", 'pizzahouse'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site for video preview", 'pizzahouse') ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"bg_image" => array(
					"title" => esc_html__("Background image", 'pizzahouse'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site for video background. Attention! If you use background image - specify paddings below from background margins to video block in percents!", 'pizzahouse') ),
					"divider" => true,
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"bg_top" => array(
					"title" => esc_html__("Top offset", 'pizzahouse'),
					"desc" => wp_kses_data( __("Top offset (padding) inside background image to video block (in percent). For example: 3%", 'pizzahouse') ),
					"dependency" => array(
						'bg_image' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"bg_bottom" => array(
					"title" => esc_html__("Bottom offset", 'pizzahouse'),
					"desc" => wp_kses_data( __("Bottom offset (padding) inside background image to video block (in percent). For example: 3%", 'pizzahouse') ),
					"dependency" => array(
						'bg_image' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"bg_left" => array(
					"title" => esc_html__("Left offset", 'pizzahouse'),
					"desc" => wp_kses_data( __("Left offset (padding) inside background image to video block (in percent). For example: 20%", 'pizzahouse') ),
					"dependency" => array(
						'bg_image' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"bg_right" => array(
					"title" => esc_html__("Right offset", 'pizzahouse'),
					"desc" => wp_kses_data( __("Right offset (padding) inside background image to video block (in percent). For example: 12%", 'pizzahouse') ),
					"dependency" => array(
						'bg_image' => array('not_empty')
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
if ( !function_exists( 'pizzahouse_sc_video_reg_shortcodes_vc' ) ) {
	//add_action('pizzahouse_action_shortcodes_list_vc', 'pizzahouse_sc_video_reg_shortcodes_vc');
	function pizzahouse_sc_video_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_video",
			"name" => esc_html__("Video", 'pizzahouse'),
			"description" => wp_kses_data( __("Insert video player", 'pizzahouse') ),
			"category" => esc_html__('Content', 'pizzahouse'),
			'icon' => 'icon_trx_video',
			"class" => "trx_sc_single trx_sc_video",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "url",
					"heading" => esc_html__("URL for video file", 'pizzahouse'),
					"description" => wp_kses_data( __("Paste URL for video file", 'pizzahouse') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "ratio",
					"heading" => esc_html__("Ratio", 'pizzahouse'),
					"description" => wp_kses_data( __("Select ratio for display video", 'pizzahouse') ),
					"class" => "",
					"value" => array(
						esc_html__('16:9', 'pizzahouse') => "16:9",
						esc_html__('4:3', 'pizzahouse') => "4:3"
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "autoplay",
					"heading" => esc_html__("Autoplay video", 'pizzahouse'),
					"description" => wp_kses_data( __("Autoplay video on page load", 'pizzahouse') ),
					"admin_label" => true,
					"class" => "",
					"value" => array("Autoplay" => "on" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", "pizzahouse"),
					"description" => wp_kses_data( __("Select block alignment", "pizzahouse") ),
					"class" => "",
					"value" => array_flip((array)pizzahouse_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("Cover image", "pizzahouse"),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for video preview", "pizzahouse") ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "bg_image",
					"heading" => esc_html__("Background image", 'pizzahouse'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for video background. Attention! If you use background image - specify paddings below from background margins to video block in percents!", 'pizzahouse') ),
					"group" => esc_html__('Background', 'pizzahouse'),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "bg_top",
					"heading" => esc_html__("Top offset", 'pizzahouse'),
					"description" => wp_kses_data( __("Top offset (padding) from background image to video block (in percent). For example: 3%", 'pizzahouse') ),
					"group" => esc_html__('Background', 'pizzahouse'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_bottom",
					"heading" => esc_html__("Bottom offset", 'pizzahouse'),
					"description" => wp_kses_data( __("Bottom offset (padding) from background image to video block (in percent). For example: 3%", 'pizzahouse') ),
					"group" => esc_html__('Background', 'pizzahouse'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_left",
					"heading" => esc_html__("Left offset", 'pizzahouse'),
					"description" => wp_kses_data( __("Left offset (padding) from background image to video block (in percent). For example: 20%", 'pizzahouse') ),
					"group" => esc_html__('Background', 'pizzahouse'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_right",
					"heading" => esc_html__("Right offset", 'pizzahouse'),
					"description" => wp_kses_data( __("Right offset (padding) from background image to video block (in percent). For example: 12%", 'pizzahouse') ),
					"group" => esc_html__('Background', 'pizzahouse'),
					"class" => "",
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
			)
		) );
		
		class WPBakeryShortCode_Trx_Video extends Pizzahouse_VC_ShortCodeSingle {}
	}
}
?>