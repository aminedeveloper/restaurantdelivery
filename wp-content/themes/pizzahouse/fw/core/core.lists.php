<?php
/**
 * PizzaHouse Framework: return lists
 *
 * @package pizzahouse
 * @since pizzahouse 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }



// Return styles list
if ( !function_exists( 'pizzahouse_get_list_styles' ) ) {
	function pizzahouse_get_list_styles($from=1, $to=2, $prepend_inherit=false) {
		$list = array();
		for ($i=$from; $i<=$to; $i++)
			$list[$i] = sprintf(esc_html__('Style %d', 'pizzahouse'), $i);
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}


// Return list of the shortcodes margins
if ( !function_exists( 'pizzahouse_get_list_margins' ) ) {
	function pizzahouse_get_list_margins($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_margins'))=='') {
			$list = array(
				'null'		=> esc_html__('0 (No margin)',	'pizzahouse'),
				'tiny'		=> esc_html__('Tiny',		'pizzahouse'),
				'small'		=> esc_html__('Small',		'pizzahouse'),
				'medium'	=> esc_html__('Medium',		'pizzahouse'),
				'large'		=> esc_html__('Large',		'pizzahouse'),
				'huge'		=> esc_html__('Huge',		'pizzahouse'),
				'tiny-'		=> esc_html__('Tiny (negative)',	'pizzahouse'),
				'small-'	=> esc_html__('Small (negative)',	'pizzahouse'),
				'medium-'	=> esc_html__('Medium (negative)',	'pizzahouse'),
				'large-'	=> esc_html__('Large (negative)',	'pizzahouse'),
				'huge-'		=> esc_html__('Huge (negative)',	'pizzahouse')
				);
			$list = apply_filters('pizzahouse_filter_list_margins', $list);
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_margins', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}


// Return list of the line styles
if ( !function_exists( 'pizzahouse_get_list_line_styles' ) ) {
	function pizzahouse_get_list_line_styles($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_line_styles'))=='') {
			$list = array(
				'solid'	=> esc_html__('Solid', 'pizzahouse'),
				'dashed'=> esc_html__('Dashed', 'pizzahouse'),
				'dotted'=> esc_html__('Dotted', 'pizzahouse'),
				'double'=> esc_html__('Double', 'pizzahouse'),
				'image'	=> esc_html__('Image', 'pizzahouse')
				);
			$list = apply_filters('pizzahouse_filter_list_line_styles', $list);
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_line_styles', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}


// Return list of the animations
if ( !function_exists( 'pizzahouse_get_list_animations' ) ) {
	function pizzahouse_get_list_animations($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_animations'))=='') {
			$list = array(
				'none'			=> esc_html__('- None -',	'pizzahouse'),
				'bounce'		=> esc_html__('Bounce',		'pizzahouse'),
				'elastic'		=> esc_html__('Elastic',	'pizzahouse'),
				'flash'			=> esc_html__('Flash',		'pizzahouse'),
				'flip'			=> esc_html__('Flip',		'pizzahouse'),
				'pulse'			=> esc_html__('Pulse',		'pizzahouse'),
				'rubberBand'	=> esc_html__('Rubber Band','pizzahouse'),
				'shake'			=> esc_html__('Shake',		'pizzahouse'),
				'swing'			=> esc_html__('Swing',		'pizzahouse'),
				'tada'			=> esc_html__('Tada',		'pizzahouse'),
				'wobble'		=> esc_html__('Wobble',		'pizzahouse')
				);
			$list = apply_filters('pizzahouse_filter_list_animations', $list);
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_animations', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}


// Return list of the enter animations
if ( !function_exists( 'pizzahouse_get_list_animations_in' ) ) {
	function pizzahouse_get_list_animations_in($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_animations_in'))=='') {
			$list = array(
				'none'				=> esc_html__('- None -',			'pizzahouse'),
				'bounceIn'			=> esc_html__('Bounce In',			'pizzahouse'),
				'bounceInUp'		=> esc_html__('Bounce In Up',		'pizzahouse'),
				'bounceInDown'		=> esc_html__('Bounce In Down',		'pizzahouse'),
				'bounceInLeft'		=> esc_html__('Bounce In Left',		'pizzahouse'),
				'bounceInRight'		=> esc_html__('Bounce In Right',	'pizzahouse'),
				'elastic'			=> esc_html__('Elastic In',			'pizzahouse'),
				'fadeIn'			=> esc_html__('Fade In',			'pizzahouse'),
				'fadeInUp'			=> esc_html__('Fade In Up',			'pizzahouse'),
				'fadeInUpSmall'		=> esc_html__('Fade In Up Small',	'pizzahouse'),
				'fadeInUpBig'		=> esc_html__('Fade In Up Big',		'pizzahouse'),
				'fadeInDown'		=> esc_html__('Fade In Down',		'pizzahouse'),
				'fadeInDownBig'		=> esc_html__('Fade In Down Big',	'pizzahouse'),
				'fadeInLeft'		=> esc_html__('Fade In Left',		'pizzahouse'),
				'fadeInLeftBig'		=> esc_html__('Fade In Left Big',	'pizzahouse'),
				'fadeInRight'		=> esc_html__('Fade In Right',		'pizzahouse'),
				'fadeInRightBig'	=> esc_html__('Fade In Right Big',	'pizzahouse'),
				'flipInX'			=> esc_html__('Flip In X',			'pizzahouse'),
				'flipInY'			=> esc_html__('Flip In Y',			'pizzahouse'),
				'lightSpeedIn'		=> esc_html__('Light Speed In',		'pizzahouse'),
				'rotateIn'			=> esc_html__('Rotate In',			'pizzahouse'),
				'rotateInUpLeft'	=> esc_html__('Rotate In Down Left','pizzahouse'),
				'rotateInUpRight'	=> esc_html__('Rotate In Up Right',	'pizzahouse'),
				'rotateInDownLeft'	=> esc_html__('Rotate In Up Left',	'pizzahouse'),
				'rotateInDownRight'	=> esc_html__('Rotate In Down Right','pizzahouse'),
				'rollIn'			=> esc_html__('Roll In',			'pizzahouse'),
				'slideInUp'			=> esc_html__('Slide In Up',		'pizzahouse'),
				'slideInDown'		=> esc_html__('Slide In Down',		'pizzahouse'),
				'slideInLeft'		=> esc_html__('Slide In Left',		'pizzahouse'),
				'slideInRight'		=> esc_html__('Slide In Right',		'pizzahouse'),
				'wipeInLeftTop'		=> esc_html__('Wipe In Left Top',	'pizzahouse'),
				'zoomIn'			=> esc_html__('Zoom In',			'pizzahouse'),
				'zoomInUp'			=> esc_html__('Zoom In Up',			'pizzahouse'),
				'zoomInDown'		=> esc_html__('Zoom In Down',		'pizzahouse'),
				'zoomInLeft'		=> esc_html__('Zoom In Left',		'pizzahouse'),
				'zoomInRight'		=> esc_html__('Zoom In Right',		'pizzahouse')
				);
			$list = apply_filters('pizzahouse_filter_list_animations_in', $list);
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_animations_in', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}


// Return list of the out animations
if ( !function_exists( 'pizzahouse_get_list_animations_out' ) ) {
	function pizzahouse_get_list_animations_out($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_animations_out'))=='') {
			$list = array(
				'none'				=> esc_html__('- None -',			'pizzahouse'),
				'bounceOut'			=> esc_html__('Bounce Out',			'pizzahouse'),
				'bounceOutUp'		=> esc_html__('Bounce Out Up',		'pizzahouse'),
				'bounceOutDown'		=> esc_html__('Bounce Out Down',	'pizzahouse'),
				'bounceOutLeft'		=> esc_html__('Bounce Out Left',	'pizzahouse'),
				'bounceOutRight'	=> esc_html__('Bounce Out Right',	'pizzahouse'),
				'fadeOut'			=> esc_html__('Fade Out',			'pizzahouse'),
				'fadeOutUp'			=> esc_html__('Fade Out Up',		'pizzahouse'),
				'fadeOutUpBig'		=> esc_html__('Fade Out Up Big',	'pizzahouse'),
				'fadeOutDown'		=> esc_html__('Fade Out Down',		'pizzahouse'),
				'fadeOutDownSmall'	=> esc_html__('Fade Out Down Small','pizzahouse'),
				'fadeOutDownBig'	=> esc_html__('Fade Out Down Big',	'pizzahouse'),
				'fadeOutLeft'		=> esc_html__('Fade Out Left',		'pizzahouse'),
				'fadeOutLeftBig'	=> esc_html__('Fade Out Left Big',	'pizzahouse'),
				'fadeOutRight'		=> esc_html__('Fade Out Right',		'pizzahouse'),
				'fadeOutRightBig'	=> esc_html__('Fade Out Right Big',	'pizzahouse'),
				'flipOutX'			=> esc_html__('Flip Out X',			'pizzahouse'),
				'flipOutY'			=> esc_html__('Flip Out Y',			'pizzahouse'),
				'hinge'				=> esc_html__('Hinge Out',			'pizzahouse'),
				'lightSpeedOut'		=> esc_html__('Light Speed Out',	'pizzahouse'),
				'rotateOut'			=> esc_html__('Rotate Out',			'pizzahouse'),
				'rotateOutUpLeft'	=> esc_html__('Rotate Out Down Left','pizzahouse'),
				'rotateOutUpRight'	=> esc_html__('Rotate Out Up Right','pizzahouse'),
				'rotateOutDownLeft'	=> esc_html__('Rotate Out Up Left',	'pizzahouse'),
				'rotateOutDownRight'=> esc_html__('Rotate Out Down Right','pizzahouse'),
				'rollOut'			=> esc_html__('Roll Out',			'pizzahouse'),
				'slideOutUp'		=> esc_html__('Slide Out Up',		'pizzahouse'),
				'slideOutDown'		=> esc_html__('Slide Out Down',		'pizzahouse'),
				'slideOutLeft'		=> esc_html__('Slide Out Left',		'pizzahouse'),
				'slideOutRight'		=> esc_html__('Slide Out Right',	'pizzahouse'),
				'zoomOut'			=> esc_html__('Zoom Out',			'pizzahouse'),
				'zoomOutUp'			=> esc_html__('Zoom Out Up',		'pizzahouse'),
				'zoomOutDown'		=> esc_html__('Zoom Out Down',		'pizzahouse'),
				'zoomOutLeft'		=> esc_html__('Zoom Out Left',		'pizzahouse'),
				'zoomOutRight'		=> esc_html__('Zoom Out Right',		'pizzahouse')
				);
			$list = apply_filters('pizzahouse_filter_list_animations_out', $list);
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_animations_out', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}

// Return classes list for the specified animation
if (!function_exists('pizzahouse_get_animation_classes')) {
	function pizzahouse_get_animation_classes($animation, $speed='normal', $loop='none') {
		// speed:	fast=0.5s | normal=1s | slow=2s
		// loop:	none | infinite
		return pizzahouse_param_is_off($animation) ? '' : 'animated '.esc_attr($animation).' '.esc_attr($speed).(!pizzahouse_param_is_off($loop) ? ' '.esc_attr($loop) : '');
	}
}


// Return list of the main menu hover effects
if ( !function_exists( 'pizzahouse_get_list_menu_hovers' ) ) {
	function pizzahouse_get_list_menu_hovers($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_menu_hovers'))=='') {
			$list = array(
				'fade'			=> esc_html__('Fade',		'pizzahouse'),
				'slide_line'	=> esc_html__('Slide Line',	'pizzahouse'),
				'slide_box'		=> esc_html__('Slide Box',	'pizzahouse'),
				'zoom_line'		=> esc_html__('Zoom Line',	'pizzahouse'),
				'path_line'		=> esc_html__('Path Line',	'pizzahouse'),
				'roll_down'		=> esc_html__('Roll Down',	'pizzahouse'),
				'color_line'	=> esc_html__('Color Line',	'pizzahouse'),
				);
			$list = apply_filters('pizzahouse_filter_list_menu_hovers', $list);
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_menu_hovers', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}



// Return list of the input field's hover effects
if ( !function_exists( 'pizzahouse_get_list_input_hovers' ) ) {
	function pizzahouse_get_list_input_hovers($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_input_hovers'))=='') {
			$list = array(
				'default'	=> esc_html__('Default',	'pizzahouse'),
				'accent'	=> esc_html__('Accented',	'pizzahouse'),
				'path'		=> esc_html__('Path',		'pizzahouse'),
				'jump'		=> esc_html__('Jump',		'pizzahouse'),
				'underline'	=> esc_html__('Underline',	'pizzahouse'),
				'iconed'	=> esc_html__('Iconed',		'pizzahouse'),
				);
			$list = apply_filters('pizzahouse_filter_list_input_hovers', $list);
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_input_hovers', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}


// Return list of the search field's styles
if ( !function_exists( 'pizzahouse_get_list_search_styles' ) ) {
	function pizzahouse_get_list_search_styles($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_search_styles'))=='') {
			$list = array(
				'default'	=> esc_html__('Default',	'pizzahouse'),
				
				'slide'		=> esc_html__('Slide',		'pizzahouse'),
				'expand'	=> esc_html__('Expand',		'pizzahouse'),
				);
			$list = apply_filters('pizzahouse_filter_list_search_styles', $list);
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_search_styles', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}


// Return list of categories
if ( !function_exists( 'pizzahouse_get_list_categories' ) ) {
	function pizzahouse_get_list_categories($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_categories'))=='') {
			$list = array();
			$args = array(
				'type'                     => 'post',
				'child_of'                 => 0,
				'parent'                   => '',
				'orderby'                  => 'name',
				'order'                    => 'ASC',
				'hide_empty'               => 0,
				'hierarchical'             => 1,
				'exclude'                  => '',
				'include'                  => '',
				'number'                   => '',
				'taxonomy'                 => 'category',
				'pad_counts'               => false );
			$taxonomies = get_categories( $args );
			if (is_array($taxonomies) && count($taxonomies) > 0) {
				foreach ($taxonomies as $cat) {
					$list[$cat->term_id] = $cat->name;
				}
			}
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_categories', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}


// Return list of taxonomies
if ( !function_exists( 'pizzahouse_get_list_terms' ) ) {
	function pizzahouse_get_list_terms($prepend_inherit=false, $taxonomy='category') {
		if (($list = pizzahouse_storage_get('list_taxonomies_'.($taxonomy)))=='') {
			$list = array();
			if ( is_array($taxonomy) || taxonomy_exists($taxonomy) ) {
				$terms = get_terms( $taxonomy, array(
					'child_of'                 => 0,
					'parent'                   => '',
					'orderby'                  => 'name',
					'order'                    => 'ASC',
					'hide_empty'               => 0,
					'hierarchical'             => 1,
					'exclude'                  => '',
					'include'                  => '',
					'number'                   => '',
					'taxonomy'                 => $taxonomy,
					'pad_counts'               => false
					)
				);
			} else {
				$terms = pizzahouse_get_terms_by_taxonomy_from_db($taxonomy);
			}
			if (!is_wp_error( $terms ) && is_array($terms) && count($terms) > 0) {
				foreach ($terms as $cat) {
					$list[$cat->term_id] = $cat->name;	
				}
			}
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_taxonomies_'.($taxonomy), $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}

// Return list of post's types
if ( !function_exists( 'pizzahouse_get_list_posts_types' ) ) {
	function pizzahouse_get_list_posts_types($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_posts_types'))=='') {
			// Return only theme inheritance supported post types
			$list = apply_filters('pizzahouse_filter_list_post_types', array());
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_posts_types', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}


// Return list post items from any post type and taxonomy
if ( !function_exists( 'pizzahouse_get_list_posts' ) ) {
	function pizzahouse_get_list_posts($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'post',
			'post_status'		=> 'publish',
			'taxonomy'			=> 'category',
			'taxonomy_value'	=> '',
			'posts_per_page'	=> -1,
			'orderby'			=> 'post_date',
			'order'				=> 'desc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));

		$hash = 'list_posts_'.($opt['post_type']).'_'.($opt['taxonomy']).'_'.($opt['taxonomy_value']).'_'.($opt['orderby']).'_'.($opt['order']).'_'.($opt['return']).'_'.($opt['posts_per_page']);
		if (($list = pizzahouse_storage_get($hash))=='') {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'pizzahouse');
			$args = array(
				'post_type' => $opt['post_type'],
				'post_status' => $opt['post_status'],
				'posts_per_page' => $opt['posts_per_page'],
				'ignore_sticky_posts' => true,
				'orderby'	=> $opt['orderby'],
				'order'		=> $opt['order']
			);
			if (!empty($opt['taxonomy_value'])) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => $opt['taxonomy'],
						'field' => (int) $opt['taxonomy_value'] > 0 ? 'id' : 'slug',
						'terms' => $opt['taxonomy_value']
					)
				);
			}
			$posts = get_posts( $args );
			if (is_array($posts) && count($posts) > 0) {
				foreach ($posts as $post) {
					$list[$opt['return']=='id' ? $post->ID : $post->post_title] = $post->post_title;
				}
			}
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set($hash, $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}


// Return list pages
if ( !function_exists( 'pizzahouse_get_list_pages' ) ) {
	function pizzahouse_get_list_pages($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'page',
			'post_status'		=> 'publish',
			'posts_per_page'	=> -1,
			'orderby'			=> 'title',
			'order'				=> 'asc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));
		return pizzahouse_get_list_posts($prepend_inherit, $opt);
	}
}


// Return list of registered users
if ( !function_exists( 'pizzahouse_get_list_users' ) ) {
	function pizzahouse_get_list_users($prepend_inherit=false, $roles=array('administrator', 'editor', 'author', 'contributor', 'shop_manager')) {
		if (($list = pizzahouse_storage_get('list_users'))=='') {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'pizzahouse');
			$args = array(
				'orderby'	=> 'display_name',
				'order'		=> 'ASC' );
			$users = get_users( $args );
			if (is_array($users) && count($users) > 0) {
				foreach ($users as $user) {
					$accept = true;
					if (is_array($user->roles)) {
						if (is_array($user->roles) && count($user->roles) > 0) {
							$accept = false;
							foreach ($user->roles as $role) {
								if (in_array($role, $roles)) {
									$accept = true;
									break;
								}
							}
						}
					}
					if ($accept) $list[$user->user_login] = $user->display_name;
				}
			}
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_users', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}


// Return slider engines list, prepended inherit (if need)
if ( !function_exists( 'pizzahouse_get_list_sliders' ) ) {
	function pizzahouse_get_list_sliders($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_sliders'))=='') {
			$list = array(
				'swiper' => esc_html__("Posts slider (Swiper)", 'pizzahouse')
			);
			$list = apply_filters('pizzahouse_filter_list_sliders', $list);
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_sliders', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}


// Return slider controls list, prepended inherit (if need)
if ( !function_exists( 'pizzahouse_get_list_slider_controls' ) ) {
	function pizzahouse_get_list_slider_controls($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_slider_controls'))=='') {
			$list = array(
				'no'		=> esc_html__('None', 'pizzahouse'),
				'side'		=> esc_html__('Side', 'pizzahouse'),
				'bottom'	=> esc_html__('Bottom', 'pizzahouse'),
				'pagination'=> esc_html__('Pagination', 'pizzahouse')
				);
			$list = apply_filters('pizzahouse_filter_list_slider_controls', $list);
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_slider_controls', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}


// Return slider controls classes
if ( !function_exists( 'pizzahouse_get_slider_controls_classes' ) ) {
	function pizzahouse_get_slider_controls_classes($controls) {
		if (pizzahouse_param_is_off($controls))	$classes = 'sc_slider_nopagination sc_slider_nocontrols';
		else if ($controls=='bottom')			$classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_bottom';
		else if ($controls=='pagination')		$classes = 'sc_slider_pagination sc_slider_pagination_bottom sc_slider_nocontrols';
		else									$classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_side';
		return $classes;
	}
}

// Return list with popup engines
if ( !function_exists( 'pizzahouse_get_list_popup_engines' ) ) {
	function pizzahouse_get_list_popup_engines($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_popup_engines'))=='') {
			$list = array(
				"pretty"	=> esc_html__("Pretty photo", 'pizzahouse'),
				"magnific"	=> esc_html__("Magnific popup", 'pizzahouse')
				);
			$list = apply_filters('pizzahouse_filter_list_popup_engines', $list);
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_popup_engines', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}

// Return menus list, prepended inherit
if ( !function_exists( 'pizzahouse_get_list_menus' ) ) {
	function pizzahouse_get_list_menus($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_menus'))=='') {
			$list = array();
			$list['default'] = esc_html__("Default", 'pizzahouse');
			$menus = wp_get_nav_menus();
			if (is_array($menus) && count($menus) > 0) {
				foreach ($menus as $menu) {
					$list[$menu->slug] = $menu->name;
				}
			}
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_menus', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}

// Return custom sidebars list, prepended inherit and main sidebars item (if need)
if ( !function_exists( 'pizzahouse_get_list_sidebars' ) ) {
	function pizzahouse_get_list_sidebars($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_sidebars'))=='') {
			if (($list = pizzahouse_storage_get('registered_sidebars'))=='') $list = array();
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_sidebars', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}

// Return sidebars positions
if ( !function_exists( 'pizzahouse_get_list_sidebars_positions' ) ) {
	function pizzahouse_get_list_sidebars_positions($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_sidebars_positions'))=='') {
			$list = array(
				'none'  => esc_html__('Hide',  'pizzahouse'),
				'left'  => esc_html__('Left',  'pizzahouse'),
				'right' => esc_html__('Right', 'pizzahouse')
				);
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_sidebars_positions', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}

// Return sidebars class
if ( !function_exists( 'pizzahouse_get_sidebar_class' ) ) {
	function pizzahouse_get_sidebar_class() {
		$sb_main = pizzahouse_get_custom_option('show_sidebar_main');
		$sb_outer = false;
		return (pizzahouse_param_is_off($sb_main) ? 'sidebar_hide' : 'sidebar_show sidebar_'.($sb_main))
				. ' ' . (pizzahouse_param_is_off($sb_outer) ? 'sidebar_outer_hide' : 'sidebar_outer_show sidebar_outer_'.($sb_outer));
	}
}

// Return body styles list, prepended inherit
if ( !function_exists( 'pizzahouse_get_list_body_styles' ) ) {
	function pizzahouse_get_list_body_styles($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_body_styles'))=='') {
			$list = array(
				'boxed'	=> esc_html__('Boxed',		'pizzahouse'),
				'wide'	=> esc_html__('Wide',		'pizzahouse')
				);
			if (pizzahouse_get_theme_setting('allow_fullscreen')) {
				$list['fullwide']	= esc_html__('Fullwide',	'pizzahouse');
				$list['fullscreen']	= esc_html__('Fullscreen',	'pizzahouse');
			}
			$list = apply_filters('pizzahouse_filter_list_body_styles', $list);
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_body_styles', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}

// Return templates list, prepended inherit
if ( !function_exists( 'pizzahouse_get_list_templates' ) ) {
	function pizzahouse_get_list_templates($mode='') {
		if (($list = pizzahouse_storage_get('list_templates_'.($mode)))=='') {
			$list = array();
			$tpl = pizzahouse_storage_get('registered_templates');
			if (is_array($tpl) && count($tpl) > 0) {
				foreach ($tpl as $k=>$v) {
					if ($mode=='' || in_array($mode, explode(',', $v['mode'])))
						$list[$k] = !empty($v['icon']) 
									? $v['icon'] 
									: (!empty($v['title']) 
										? $v['title'] 
										: pizzahouse_strtoproper($v['layout'])
										);
				}
			}
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_templates_'.($mode), $list);
		}
		return $list;
	}
}

// Return blog styles list, prepended inherit
if ( !function_exists( 'pizzahouse_get_list_templates_blog' ) ) {
	function pizzahouse_get_list_templates_blog($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_templates_blog'))=='') {
			$list = pizzahouse_get_list_templates('blog');
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_templates_blog', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}

// Return blogger styles list, prepended inherit
if ( !function_exists( 'pizzahouse_get_list_templates_blogger' ) ) {
	function pizzahouse_get_list_templates_blogger($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_templates_blogger'))=='') {
			$list = pizzahouse_array_merge(pizzahouse_get_list_templates('blogger'), pizzahouse_get_list_templates('blog'));
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_templates_blogger', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}

// Return single page styles list, prepended inherit
if ( !function_exists( 'pizzahouse_get_list_templates_single' ) ) {
	function pizzahouse_get_list_templates_single($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_templates_single'))=='') {
			$list = pizzahouse_get_list_templates('single');
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_templates_single', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}

// Return header styles list, prepended inherit
if ( !function_exists( 'pizzahouse_get_list_templates_header' ) ) {
	function pizzahouse_get_list_templates_header($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_templates_header'))=='') {
			$list = pizzahouse_get_list_templates('header');
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_templates_header', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}

// Return form styles list, prepended inherit
if ( !function_exists( 'pizzahouse_get_list_templates_forms' ) ) {
	function pizzahouse_get_list_templates_forms($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_templates_forms'))=='') {
			$list = pizzahouse_get_list_templates('forms');
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_templates_forms', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}

// Return article styles list, prepended inherit
if ( !function_exists( 'pizzahouse_get_list_article_styles' ) ) {
	function pizzahouse_get_list_article_styles($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_article_styles'))=='') {
			$list = array(
				"boxed"   => esc_html__('Boxed', 'pizzahouse'),
				"stretch" => esc_html__('Stretch', 'pizzahouse')
				);
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_article_styles', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}

// Return post-formats filters list, prepended inherit
if ( !function_exists( 'pizzahouse_get_list_post_formats_filters' ) ) {
	function pizzahouse_get_list_post_formats_filters($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_post_formats_filters'))=='') {
			$list = array(
				"no"      => esc_html__('All posts', 'pizzahouse'),
				"thumbs"  => esc_html__('With thumbs', 'pizzahouse'),
				"reviews" => esc_html__('With reviews', 'pizzahouse'),
				"video"   => esc_html__('With videos', 'pizzahouse'),
				"audio"   => esc_html__('With audios', 'pizzahouse'),
				"gallery" => esc_html__('With galleries', 'pizzahouse')
				);
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_post_formats_filters', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}

// Return portfolio filters list, prepended inherit
if ( !function_exists( 'pizzahouse_get_list_portfolio_filters' ) ) {
	function pizzahouse_get_list_portfolio_filters($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_portfolio_filters'))=='') {
			$list = array(
				"hide"		=> esc_html__('Hide', 'pizzahouse'),
				"tags"		=> esc_html__('Tags', 'pizzahouse'),
				"categories"=> esc_html__('Categories', 'pizzahouse')
				);
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_portfolio_filters', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}

// Return hover styles list, prepended inherit
if ( !function_exists( 'pizzahouse_get_list_hovers' ) ) {
	function pizzahouse_get_list_hovers($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_hovers'))=='') {
			$list = array();
			$list['circle effect1']  = esc_html__('Circle Effect 1',  'pizzahouse');
			$list['circle effect2']  = esc_html__('Circle Effect 2',  'pizzahouse');
			$list['circle effect3']  = esc_html__('Circle Effect 3',  'pizzahouse');
			$list['circle effect4']  = esc_html__('Circle Effect 4',  'pizzahouse');
			$list['circle effect5']  = esc_html__('Circle Effect 5',  'pizzahouse');
			$list['circle effect6']  = esc_html__('Circle Effect 6',  'pizzahouse');
			$list['circle effect7']  = esc_html__('Circle Effect 7',  'pizzahouse');
			$list['circle effect8']  = esc_html__('Circle Effect 8',  'pizzahouse');
			$list['circle effect9']  = esc_html__('Circle Effect 9',  'pizzahouse');
			$list['circle effect10'] = esc_html__('Circle Effect 10',  'pizzahouse');
			$list['circle effect11'] = esc_html__('Circle Effect 11',  'pizzahouse');
			$list['circle effect12'] = esc_html__('Circle Effect 12',  'pizzahouse');
			$list['circle effect13'] = esc_html__('Circle Effect 13',  'pizzahouse');
			$list['circle effect14'] = esc_html__('Circle Effect 14',  'pizzahouse');
			$list['circle effect15'] = esc_html__('Circle Effect 15',  'pizzahouse');
			$list['circle effect16'] = esc_html__('Circle Effect 16',  'pizzahouse');
			$list['circle effect17'] = esc_html__('Circle Effect 17',  'pizzahouse');
			$list['circle effect18'] = esc_html__('Circle Effect 18',  'pizzahouse');
			$list['circle effect19'] = esc_html__('Circle Effect 19',  'pizzahouse');
			$list['circle effect20'] = esc_html__('Circle Effect 20',  'pizzahouse');
			$list['square effect1']  = esc_html__('Square Effect 1',  'pizzahouse');
			$list['square effect2']  = esc_html__('Square Effect 2',  'pizzahouse');
			$list['square effect3']  = esc_html__('Square Effect 3',  'pizzahouse');
			$list['square effect5']  = esc_html__('Square Effect 5',  'pizzahouse');
			$list['square effect6']  = esc_html__('Square Effect 6',  'pizzahouse');
			$list['square effect7']  = esc_html__('Square Effect 7',  'pizzahouse');
			$list['square effect8']  = esc_html__('Square Effect 8',  'pizzahouse');
			$list['square effect9']  = esc_html__('Square Effect 9',  'pizzahouse');
			$list['square effect10'] = esc_html__('Square Effect 10',  'pizzahouse');
			$list['square effect11'] = esc_html__('Square Effect 11',  'pizzahouse');
			$list['square effect12'] = esc_html__('Square Effect 12',  'pizzahouse');
			$list['square effect13'] = esc_html__('Square Effect 13',  'pizzahouse');
			$list['square effect14'] = esc_html__('Square Effect 14',  'pizzahouse');
			$list['square effect15'] = esc_html__('Square Effect 15',  'pizzahouse');
			$list['square effect_dir']   = esc_html__('Square Effect Dir',   'pizzahouse');
			$list['square effect_shift'] = esc_html__('Square Effect Shift', 'pizzahouse');
			$list['square effect_book']  = esc_html__('Square Effect Book',  'pizzahouse');
			$list['square effect_more']  = esc_html__('Square Effect More',  'pizzahouse');
			$list['square effect_fade']  = esc_html__('Square Effect Fade',  'pizzahouse');
			$list['square effect_pull']  = esc_html__('Square Effect Pull',  'pizzahouse');
			$list['square effect_slide'] = esc_html__('Square Effect Slide', 'pizzahouse');
			$list['square effect_border'] = esc_html__('Square Effect Border', 'pizzahouse');
			$list = apply_filters('pizzahouse_filter_portfolio_hovers', $list);
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_hovers', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}


// Return list of the blog counters
if ( !function_exists( 'pizzahouse_get_list_blog_counters' ) ) {
	function pizzahouse_get_list_blog_counters($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_blog_counters'))=='') {
			$list = array(
				'views'		=> esc_html__('Views', 'pizzahouse'),
				'likes'		=> esc_html__('Likes', 'pizzahouse'),
				'rating'	=> esc_html__('Rating', 'pizzahouse'),
				'comments'	=> esc_html__('Comments', 'pizzahouse')
				);
			$list = apply_filters('pizzahouse_filter_list_blog_counters', $list);
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_blog_counters', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}

// Return list of the item sizes for the portfolio alter style, prepended inherit
if ( !function_exists( 'pizzahouse_get_list_alter_sizes' ) ) {
	function pizzahouse_get_list_alter_sizes($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_alter_sizes'))=='') {
			$list = array(
					'1_1' => esc_html__('1x1', 'pizzahouse'),
					'1_2' => esc_html__('1x2', 'pizzahouse'),
					'2_1' => esc_html__('2x1', 'pizzahouse'),
					'2_2' => esc_html__('2x2', 'pizzahouse'),
					'1_3' => esc_html__('1x3', 'pizzahouse'),
					'2_3' => esc_html__('2x3', 'pizzahouse'),
					'3_1' => esc_html__('3x1', 'pizzahouse'),
					'3_2' => esc_html__('3x2', 'pizzahouse'),
					'3_3' => esc_html__('3x3', 'pizzahouse')
					);
			$list = apply_filters('pizzahouse_filter_portfolio_alter_sizes', $list);
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_alter_sizes', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}

// Return extended hover directions list, prepended inherit
if ( !function_exists( 'pizzahouse_get_list_hovers_directions' ) ) {
	function pizzahouse_get_list_hovers_directions($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_hovers_directions'))=='') {
			$list = array(
				'left_to_right' => esc_html__('Left to Right',  'pizzahouse'),
				'right_to_left' => esc_html__('Right to Left',  'pizzahouse'),
				'top_to_bottom' => esc_html__('Top to Bottom',  'pizzahouse'),
				'bottom_to_top' => esc_html__('Bottom to Top',  'pizzahouse'),
				'scale_up'      => esc_html__('Scale Up',  'pizzahouse'),
				'scale_down'    => esc_html__('Scale Down',  'pizzahouse'),
				'scale_down_up' => esc_html__('Scale Down-Up',  'pizzahouse'),
				'from_left_and_right' => esc_html__('From Left and Right',  'pizzahouse'),
				'from_top_and_bottom' => esc_html__('From Top and Bottom',  'pizzahouse')
			);
			$list = apply_filters('pizzahouse_filter_portfolio_hovers_directions', $list);
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_hovers_directions', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}


// Return list of the label positions in the custom forms
if ( !function_exists( 'pizzahouse_get_list_label_positions' ) ) {
	function pizzahouse_get_list_label_positions($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_label_positions'))=='') {
			$list = array(
				'top'		=> esc_html__('Top',		'pizzahouse'),
				'bottom'	=> esc_html__('Bottom',		'pizzahouse'),
				'left'		=> esc_html__('Left',		'pizzahouse'),
				'over'		=> esc_html__('Over',		'pizzahouse')
			);
			$list = apply_filters('pizzahouse_filter_label_positions', $list);
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_label_positions', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}


// Return list of the bg image positions
if ( !function_exists( 'pizzahouse_get_list_bg_image_positions' ) ) {
	function pizzahouse_get_list_bg_image_positions($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_bg_image_positions'))=='') {
			$list = array(
				'left top'	   => esc_html__('Left Top', 'pizzahouse'),
				'center top'   => esc_html__("Center Top", 'pizzahouse'),
				'right top'    => esc_html__("Right Top", 'pizzahouse'),
				'left center'  => esc_html__("Left Center", 'pizzahouse'),
				'center center'=> esc_html__("Center Center", 'pizzahouse'),
				'right center' => esc_html__("Right Center", 'pizzahouse'),
				'left bottom'  => esc_html__("Left Bottom", 'pizzahouse'),
				'center bottom'=> esc_html__("Center Bottom", 'pizzahouse'),
				'right bottom' => esc_html__("Right Bottom", 'pizzahouse')
			);
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_bg_image_positions', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}


// Return list of the bg image repeat
if ( !function_exists( 'pizzahouse_get_list_bg_image_repeats' ) ) {
	function pizzahouse_get_list_bg_image_repeats($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_bg_image_repeats'))=='') {
			$list = array(
				'repeat'	=> esc_html__('Repeat', 'pizzahouse'),
				'repeat-x'	=> esc_html__('Repeat X', 'pizzahouse'),
				'repeat-y'	=> esc_html__('Repeat Y', 'pizzahouse'),
				'no-repeat'	=> esc_html__('No Repeat', 'pizzahouse')
			);
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_bg_image_repeats', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}


// Return list of the bg image attachment
if ( !function_exists( 'pizzahouse_get_list_bg_image_attachments' ) ) {
	function pizzahouse_get_list_bg_image_attachments($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_bg_image_attachments'))=='') {
			$list = array(
				'scroll'	=> esc_html__('Scroll', 'pizzahouse'),
				'fixed'		=> esc_html__('Fixed', 'pizzahouse'),
				'local'		=> esc_html__('Local', 'pizzahouse')
			);
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_bg_image_attachments', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}


// Return list of the bg tints
if ( !function_exists( 'pizzahouse_get_list_bg_tints' ) ) {
	function pizzahouse_get_list_bg_tints($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_bg_tints'))=='') {
			$list = array(
				'white'	=> esc_html__('White', 'pizzahouse'),
				'light'	=> esc_html__('Light', 'pizzahouse'),
				'dark'	=> esc_html__('Dark', 'pizzahouse')
			);
			$list = apply_filters('pizzahouse_filter_bg_tints', $list);
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_bg_tints', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}

// Return custom fields types list, prepended inherit
if ( !function_exists( 'pizzahouse_get_list_field_types' ) ) {
	function pizzahouse_get_list_field_types($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_field_types'))=='') {
			$list = array(
				'text'     => esc_html__('Text',  'pizzahouse'),
				'textarea' => esc_html__('Text Area','pizzahouse'),
				'password' => esc_html__('Password',  'pizzahouse'),
				'radio'    => esc_html__('Radio',  'pizzahouse'),
				'checkbox' => esc_html__('Checkbox',  'pizzahouse'),
				'select'   => esc_html__('Select',  'pizzahouse'),
				'date'     => esc_html__('Date','pizzahouse'),
				'time'     => esc_html__('Time','pizzahouse'),
				'button'   => esc_html__('Button','pizzahouse')
			);
			$list = apply_filters('pizzahouse_filter_field_types', $list);
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_field_types', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}

// Return Google map styles
if ( !function_exists( 'pizzahouse_get_list_googlemap_styles' ) ) {
	function pizzahouse_get_list_googlemap_styles($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_googlemap_styles'))=='') {
			$list = array(
				'default' => esc_html__('Default', 'pizzahouse')
			);
			$list = apply_filters('pizzahouse_filter_googlemap_styles', $list);
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_googlemap_styles', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}

// Return iconed classes list
if ( !function_exists( 'pizzahouse_get_list_icons' ) ) {
	function pizzahouse_get_list_icons($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_icons'))=='') {
			$list = pizzahouse_parse_icons_classes(pizzahouse_get_file_dir("css/fontello/css/fontello-codes.css"));
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_icons', $list);
		}
		return $prepend_inherit ? array_merge(array('inherit'), $list) : $list;
	}
}

// Return socials list
if ( !function_exists( 'pizzahouse_get_list_socials' ) ) {
	function pizzahouse_get_list_socials($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_socials'))=='') {
			$list = pizzahouse_get_list_images("images/socials", "png");
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_socials', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}

// Return list with 'Yes' and 'No' items
if ( !function_exists( 'pizzahouse_get_list_yesno' ) ) {
	function pizzahouse_get_list_yesno($prepend_inherit=false) {
		$list = array(
			'yes' => esc_html__("Yes", 'pizzahouse'),
			'no'  => esc_html__("No", 'pizzahouse')
		);
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}

// Return list with 'On' and 'Of' items
if ( !function_exists( 'pizzahouse_get_list_onoff' ) ) {
	function pizzahouse_get_list_onoff($prepend_inherit=false) {
		$list = array(
			"on" => esc_html__("On", 'pizzahouse'),
			"off" => esc_html__("Off", 'pizzahouse')
		);
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}

// Return list with 'Show' and 'Hide' items
if ( !function_exists( 'pizzahouse_get_list_showhide' ) ) {
	function pizzahouse_get_list_showhide($prepend_inherit=false) {
		$list = array(
			"show" => esc_html__("Show", 'pizzahouse'),
			"hide" => esc_html__("Hide", 'pizzahouse')
		);
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}

// Return list with 'Ascending' and 'Descending' items
if ( !function_exists( 'pizzahouse_get_list_orderings' ) ) {
	function pizzahouse_get_list_orderings($prepend_inherit=false) {
		$list = array(
			"asc" => esc_html__("Ascending", 'pizzahouse'),
			"desc" => esc_html__("Descending", 'pizzahouse')
		);
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}

// Return list with 'Horizontal' and 'Vertical' items
if ( !function_exists( 'pizzahouse_get_list_directions' ) ) {
	function pizzahouse_get_list_directions($prepend_inherit=false) {
		$list = array(
			"horizontal" => esc_html__("Horizontal", 'pizzahouse'),
			"vertical" => esc_html__("Vertical", 'pizzahouse')
		);
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}

// Return list with item's shapes
if ( !function_exists( 'pizzahouse_get_list_shapes' ) ) {
	function pizzahouse_get_list_shapes($prepend_inherit=false) {
		$list = array(
			"round"  => esc_html__("Round", 'pizzahouse'),
			"square" => esc_html__("Square", 'pizzahouse')
		);
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}

// Return list with item's sizes
if ( !function_exists( 'pizzahouse_get_list_sizes' ) ) {
	function pizzahouse_get_list_sizes($prepend_inherit=false) {
		$list = array(
			"tiny"   => esc_html__("Tiny", 'pizzahouse'),
			"small"  => esc_html__("Small", 'pizzahouse'),
			"medium" => esc_html__("Medium", 'pizzahouse'),
			"large"  => esc_html__("Large", 'pizzahouse')
		);
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}

// Return list with slider (scroll) controls positions
if ( !function_exists( 'pizzahouse_get_list_controls' ) ) {
	function pizzahouse_get_list_controls($prepend_inherit=false) {
		$list = array(
			"hide" => esc_html__("Hide", 'pizzahouse'),
			"side" => esc_html__("Side", 'pizzahouse'),
			"bottom" => esc_html__("Bottom", 'pizzahouse')
		);
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}

// Return list with float items
if ( !function_exists( 'pizzahouse_get_list_floats' ) ) {
	function pizzahouse_get_list_floats($prepend_inherit=false) {
		$list = array(
			"none" => esc_html__("None", 'pizzahouse'),
			"left" => esc_html__("Float Left", 'pizzahouse'),
			"right" => esc_html__("Float Right", 'pizzahouse')
		);
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}

// Return list with alignment items
if ( !function_exists( 'pizzahouse_get_list_alignments' ) ) {
	function pizzahouse_get_list_alignments($justify=false, $prepend_inherit=false) {
		$list = array(
			"none" => esc_html__("None", 'pizzahouse'),
			"left" => esc_html__("Left", 'pizzahouse'),
			"center" => esc_html__("Center", 'pizzahouse'),
			"right" => esc_html__("Right", 'pizzahouse')
		);
		if ($justify) $list["justify"] = esc_html__("Justify", 'pizzahouse');
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}

// Return list with horizontal positions
if ( !function_exists( 'pizzahouse_get_list_hpos' ) ) {
	function pizzahouse_get_list_hpos($prepend_inherit=false, $center=false) {
		$list = array();
		$list['left'] = esc_html__("Left", 'pizzahouse');
		if ($center) $list['center'] = esc_html__("Center", 'pizzahouse');
		$list['right'] = esc_html__("Right", 'pizzahouse');
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}

// Return list with vertical positions
if ( !function_exists( 'pizzahouse_get_list_vpos' ) ) {
	function pizzahouse_get_list_vpos($prepend_inherit=false, $center=false) {
		$list = array();
		$list['top'] = esc_html__("Top", 'pizzahouse');
		if ($center) $list['center'] = esc_html__("Center", 'pizzahouse');
		$list['bottom'] = esc_html__("Bottom", 'pizzahouse');
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}

// Return sorting list items
if ( !function_exists( 'pizzahouse_get_list_sortings' ) ) {
	function pizzahouse_get_list_sortings($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_sortings'))=='') {
			$list = array(
				"date" => esc_html__("Date", 'pizzahouse'),
				"title" => esc_html__("Alphabetically", 'pizzahouse'),
				"views" => esc_html__("Popular (views count)", 'pizzahouse'),
				"comments" => esc_html__("Most commented (comments count)", 'pizzahouse'),
				"author_rating" => esc_html__("Author rating", 'pizzahouse'),
				"users_rating" => esc_html__("Visitors (users) rating", 'pizzahouse'),
				"random" => esc_html__("Random", 'pizzahouse')
			);
			$list = apply_filters('pizzahouse_filter_list_sortings', $list);
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_sortings', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}

// Return list with columns widths
if ( !function_exists( 'pizzahouse_get_list_columns' ) ) {
	function pizzahouse_get_list_columns($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_columns'))=='') {
			$list = array(
				"none" => esc_html__("None", 'pizzahouse'),
				"1_1" => esc_html__("100%", 'pizzahouse'),
				"1_2" => esc_html__("1/2", 'pizzahouse'),
				"1_3" => esc_html__("1/3", 'pizzahouse'),
				"2_3" => esc_html__("2/3", 'pizzahouse'),
				"1_4" => esc_html__("1/4", 'pizzahouse'),
				"3_4" => esc_html__("3/4", 'pizzahouse'),
				"1_5" => esc_html__("1/5", 'pizzahouse'),
				"2_5" => esc_html__("2/5", 'pizzahouse'),
				"3_5" => esc_html__("3/5", 'pizzahouse'),
				"4_5" => esc_html__("4/5", 'pizzahouse'),
				"1_6" => esc_html__("1/6", 'pizzahouse'),
				"5_6" => esc_html__("5/6", 'pizzahouse'),
				"1_7" => esc_html__("1/7", 'pizzahouse'),
				"2_7" => esc_html__("2/7", 'pizzahouse'),
				"3_7" => esc_html__("3/7", 'pizzahouse'),
				"4_7" => esc_html__("4/7", 'pizzahouse'),
				"5_7" => esc_html__("5/7", 'pizzahouse'),
				"6_7" => esc_html__("6/7", 'pizzahouse'),
				"1_8" => esc_html__("1/8", 'pizzahouse'),
				"3_8" => esc_html__("3/8", 'pizzahouse'),
				"5_8" => esc_html__("5/8", 'pizzahouse'),
				"7_8" => esc_html__("7/8", 'pizzahouse'),
				"1_9" => esc_html__("1/9", 'pizzahouse'),
				"2_9" => esc_html__("2/9", 'pizzahouse'),
				"4_9" => esc_html__("4/9", 'pizzahouse'),
				"5_9" => esc_html__("5/9", 'pizzahouse'),
				"7_9" => esc_html__("7/9", 'pizzahouse'),
				"8_9" => esc_html__("8/9", 'pizzahouse'),
				"1_10"=> esc_html__("1/10", 'pizzahouse'),
				"3_10"=> esc_html__("3/10", 'pizzahouse'),
				"7_10"=> esc_html__("7/10", 'pizzahouse'),
				"9_10"=> esc_html__("9/10", 'pizzahouse'),
				"1_11"=> esc_html__("1/11", 'pizzahouse'),
				"2_11"=> esc_html__("2/11", 'pizzahouse'),
				"3_11"=> esc_html__("3/11", 'pizzahouse'),
				"4_11"=> esc_html__("4/11", 'pizzahouse'),
				"5_11"=> esc_html__("5/11", 'pizzahouse'),
				"6_11"=> esc_html__("6/11", 'pizzahouse'),
				"7_11"=> esc_html__("7/11", 'pizzahouse'),
				"8_11"=> esc_html__("8/11", 'pizzahouse'),
				"9_11"=> esc_html__("9/11", 'pizzahouse'),
				"10_11"=> esc_html__("10/11", 'pizzahouse'),
				"1_12"=> esc_html__("1/12", 'pizzahouse'),
				"5_12"=> esc_html__("5/12", 'pizzahouse'),
				"7_12"=> esc_html__("7/12", 'pizzahouse'),
				"10_12"=> esc_html__("10/12", 'pizzahouse'),
				"11_12"=> esc_html__("11/12", 'pizzahouse')
			);
			$list = apply_filters('pizzahouse_filter_list_columns', $list);
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_columns', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}

// Return list of locations for the dedicated content
if ( !function_exists( 'pizzahouse_get_list_dedicated_locations' ) ) {
	function pizzahouse_get_list_dedicated_locations($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_dedicated_locations'))=='') {
			$list = array(
				"default" => esc_html__('As in the post defined', 'pizzahouse'),
				"center"  => esc_html__('Above the text of the post', 'pizzahouse'),
				"left"    => esc_html__('To the left the text of the post', 'pizzahouse'),
				"right"   => esc_html__('To the right the text of the post', 'pizzahouse'),
				"alter"   => esc_html__('Alternates for each post', 'pizzahouse')
			);
			$list = apply_filters('pizzahouse_filter_list_dedicated_locations', $list);
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_dedicated_locations', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}

// Return post-format name
if ( !function_exists( 'pizzahouse_get_post_format_name' ) ) {
	function pizzahouse_get_post_format_name($format, $single=true) {
		$name = '';
		if ($format=='gallery')		$name = $single ? esc_html__('gallery', 'pizzahouse') : esc_html__('galleries', 'pizzahouse');
		else if ($format=='video')	$name = $single ? esc_html__('video', 'pizzahouse') : esc_html__('videos', 'pizzahouse');
		else if ($format=='audio')	$name = $single ? esc_html__('audio', 'pizzahouse') : esc_html__('audios', 'pizzahouse');
		else if ($format=='image')	$name = $single ? esc_html__('image', 'pizzahouse') : esc_html__('images', 'pizzahouse');
		else if ($format=='quote')	$name = $single ? esc_html__('quote', 'pizzahouse') : esc_html__('quotes', 'pizzahouse');
		else if ($format=='link')	$name = $single ? esc_html__('link', 'pizzahouse') : esc_html__('links', 'pizzahouse');
		else if ($format=='status')	$name = $single ? esc_html__('status', 'pizzahouse') : esc_html__('statuses', 'pizzahouse');
		else if ($format=='aside')	$name = $single ? esc_html__('aside', 'pizzahouse') : esc_html__('asides', 'pizzahouse');
		else if ($format=='chat')	$name = $single ? esc_html__('chat', 'pizzahouse') : esc_html__('chats', 'pizzahouse');
		else						$name = $single ? esc_html__('standard', 'pizzahouse') : esc_html__('standards', 'pizzahouse');
		return apply_filters('pizzahouse_filter_list_post_format_name', $name, $format);
	}
}

// Return post-format icon name (from Fontello library)
if ( !function_exists( 'pizzahouse_get_post_format_icon' ) ) {
	function pizzahouse_get_post_format_icon($format) {
		$icon = 'icon-';
		if ($format=='gallery')		$icon .= 'pictures';
		else if ($format=='video')	$icon .= 'video';
		else if ($format=='audio')	$icon .= 'note';
		else if ($format=='image')	$icon .= 'picture';
		else if ($format=='quote')	$icon .= 'quote';
		else if ($format=='link')	$icon .= 'link';
		else if ($format=='status')	$icon .= 'comment';
		else if ($format=='aside')	$icon .= 'doc-text';
		else if ($format=='chat')	$icon .= 'chat';
		else						$icon .= 'book-open';
		return apply_filters('pizzahouse_filter_list_post_format_icon', $icon, $format);
	}
}

// Return fonts styles list, prepended inherit
if ( !function_exists( 'pizzahouse_get_list_fonts_styles' ) ) {
	function pizzahouse_get_list_fonts_styles($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_fonts_styles'))=='') {
			$list = array(
				'i' => esc_html__('I','pizzahouse'),
				'u' => esc_html__('U', 'pizzahouse')
			);
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_fonts_styles', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}

// Return Google fonts list
if ( !function_exists( 'pizzahouse_get_list_fonts' ) ) {
	function pizzahouse_get_list_fonts($prepend_inherit=false) {
		if (($list = pizzahouse_storage_get('list_fonts'))=='') {
			$list = array();
			$list = pizzahouse_array_merge($list, pizzahouse_get_list_font_faces());
			// Google and custom fonts list:
			
			
			
			
			
			$list = pizzahouse_array_merge($list, array(
				'Advent Pro' => array('family'=>'sans-serif'),
				'Alegreya Sans' => array('family'=>'sans-serif'),
				'Arimo' => array('family'=>'sans-serif'),
				'Asap' => array('family'=>'sans-serif'),
				'Averia Sans Libre' => array('family'=>'cursive'),
				'Averia Serif Libre' => array('family'=>'cursive'),
				'Bree Serif' => array('family'=>'serif',),
				'Cabin' => array('family'=>'sans-serif'),
				'Cabin Condensed' => array('family'=>'sans-serif'),
				'Caudex' => array('family'=>'serif'),
				'Comfortaa' => array('family'=>'cursive'),
				'Cousine' => array('family'=>'sans-serif'),
				'Crimson Text' => array('family'=>'serif'),
				'Cuprum' => array('family'=>'sans-serif'),
				'Dosis' => array('family'=>'sans-serif'),
				'Economica' => array('family'=>'sans-serif'),
				'Exo' => array('family'=>'sans-serif'),
				'Expletus Sans' => array('family'=>'cursive'),
				'Karla' => array('family'=>'sans-serif'),
				'Lato' => array('family'=>'sans-serif'),
				'Lekton' => array('family'=>'sans-serif'),
				'Lobster Two' => array('family'=>'cursive'),
				'Maven Pro' => array('family'=>'sans-serif'),
				'Merriweather' => array('family'=>'serif'),
				'Merriweather Sans' => array('family'=>'sans-serif'),
				'Montserrat' => array('family'=>'sans-serif'),
				'Neuton' => array('family'=>'serif'),
				'Noticia Text' => array('family'=>'serif'),
				'Old Standard TT' => array('family'=>'serif'),
				'Open Sans' => array('family'=>'sans-serif'),
				'Orbitron' => array('family'=>'sans-serif'),
				'Oswald' => array('family'=>'sans-serif'),
				'Overlock' => array('family'=>'cursive'),
				'Oxygen' => array('family'=>'sans-serif'),
				'Philosopher' => array('family'=>'serif'),
				'PT Serif' => array('family'=>'serif'),
				'Puritan' => array('family'=>'sans-serif'),
				'Raleway' => array('family'=>'sans-serif'),
				'Roboto' => array('family'=>'sans-serif'),
				'Roboto Slab' => array('family'=>'sans-serif'),
				'Roboto Condensed' => array('family'=>'sans-serif'),
				'Rosario' => array('family'=>'sans-serif'),
				'Share' => array('family'=>'cursive'),
				'Signika' => array('family'=>'sans-serif'),
				'Signika Negative' => array('family'=>'sans-serif'),
				'Source Sans Pro' => array('family'=>'sans-serif'),
				'Tinos' => array('family'=>'serif'),
				'Ubuntu' => array('family'=>'sans-serif'),
				'Vollkorn' => array('family'=>'serif')
				)
			);
			$list = apply_filters('pizzahouse_filter_list_fonts', $list);
			if (pizzahouse_get_theme_setting('use_list_cache')) pizzahouse_storage_set('list_fonts', $list);
		}
		return $prepend_inherit ? pizzahouse_array_merge(array('inherit' => esc_html__("Inherit", 'pizzahouse')), $list) : $list;
	}
}


// Return Custom font-face list
if ( !function_exists( 'pizzahouse_get_list_font_faces' ) ) {
	function pizzahouse_get_list_font_faces($prepend_inherit=false) {
		static $list = false;
		if (is_array($list)) return $list;
		$fonts = pizzahouse_storage_get('required_custom_fonts');
		$list = array();
		if (is_array($fonts)) {
			foreach ($fonts as $font) {
				if (($url = pizzahouse_get_file_url('css/font-face/'.trim($font).'/stylesheet.css'))!='') {
					$list[sprintf(esc_html__('%s (uploaded font)', 'pizzahouse'), $font)] = array('css' => $url);
				}
			}
		}
		return $list;
	}
}
?>