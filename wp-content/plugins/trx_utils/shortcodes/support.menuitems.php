<?php
/**
 * PizzaHouse Framework: MenuItems support
 *
 * @package	pizzahouse
 * @since	pizzahouse 3.5
 */

// Theme init
if (!function_exists('pizzahouse_menuitems_theme_setup')) {
	add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_menuitems_theme_setup', 1 );
	function pizzahouse_menuitems_theme_setup() {

		// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
		add_filter('pizzahouse_filter_get_blog_type',			'pizzahouse_menuitems_get_blog_type', 9, 2);
		add_filter('pizzahouse_filter_get_blog_title',		'pizzahouse_menuitems_get_blog_title', 9, 2);
		add_filter('pizzahouse_filter_get_current_taxonomy',	'pizzahouse_menuitems_get_current_taxonomy', 9, 2);
		add_filter('pizzahouse_filter_is_taxonomy',			'pizzahouse_menuitems_is_taxonomy', 9, 2);
		add_filter('pizzahouse_filter_get_stream_page_title',	'pizzahouse_menuitems_get_stream_page_title', 9, 2);
		add_filter('pizzahouse_filter_get_stream_page_link',	'pizzahouse_menuitems_get_stream_page_link', 9, 2);
		add_filter('pizzahouse_filter_get_stream_page_id',	'pizzahouse_menuitems_get_stream_page_id', 9, 2);
		add_filter('pizzahouse_filter_query_add_filters',		'pizzahouse_menuitems_query_add_filters', 9, 2);
		add_filter('pizzahouse_filter_detect_inheritance_key','pizzahouse_menuitems_detect_inheritance_key', 9, 1);
		
		add_action('wp_ajax_ajax_menuitem',			'pizzahouse_callback_ajax_menuitem');
		add_action('wp_ajax_nopriv_ajax_menuitem',	'pizzahouse_callback_ajax_menuitem');
		
		// Extra column for menuitems lists
		if (pizzahouse_get_theme_option('show_overriden_posts')=='yes') {
			add_filter('manage_edit-menuitems_columns',			'pizzahouse_post_add_options_column', 9);
			add_filter('manage_menuitems_posts_custom_column',	'pizzahouse_post_fill_options_column', 9, 2);
		}

		// Registar shortcodes [trx_menuitems] and [trx_menuitems_item] in the shortcodes list
		add_action('pizzahouse_action_shortcodes_list',		'pizzahouse_menuitems_reg_shortcodes');
		if (function_exists('pizzahouse_exists_visual_composer') && pizzahouse_exists_visual_composer())
			add_action('pizzahouse_action_shortcodes_list_vc','pizzahouse_menuitems_reg_shortcodes_vc');
		
		// Add supported data types
		pizzahouse_theme_support_pt('menuitems');
		pizzahouse_theme_support_tx('menuitems_group');
	}
}

if ( !function_exists( 'pizzahouse_menuitems_settings_theme_setup2' ) ) {
	add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_menuitems_settings_theme_setup2', 3 );
	function pizzahouse_menuitems_settings_theme_setup2() {
		// Add post type 'menuitems' and taxonomy 'menuitems_group' into theme inheritance list
		pizzahouse_add_theme_inheritance( array('menuitems' => array(
			'stream_template' => 'blog-menuitems',
			'single_template' => 'single-menuitems',
			'taxonomy' => array('menuitems_group'),
			'taxonomy_tags' => array(),
			'post_type' => array('menuitems'),
			'override' => 'custom'
			) )
		);
	}
}



if (!function_exists('pizzahouse_menuitems_after_theme_setup')) {
	add_action( 'pizzahouse_action_after_init_theme', 'pizzahouse_menuitems_after_theme_setup' );
	function pizzahouse_menuitems_after_theme_setup() {
		// Update fields in the override options
		if (pizzahouse_storage_get_array('post_override_options', 'page')=='menuitems') {

			// Meta box fields
			pizzahouse_storage_set_array('post_override_options', 'title', esc_html__('MenuItem Options', 'pizzahouse'));
			pizzahouse_storage_set_array('post_override_options', 'fields', array(

				"mb_partition_menuitems" => array(
					"title" => esc_html__('MenuItems', 'pizzahouse'),
					"override" => "page,post,custom",
					"divider" => false,
					"icon" => "icon-th-list",
					"type" => "partition"),

				"mb_info_menuitems_1" => array(
					"title" => esc_html__('MenuItem details', 'pizzahouse'),
					"override" => "page,post,custom",
					"divider" => false,
					"desc" => wp_kses_data( __('In this section you can put details for this menuitem', 'pizzahouse') ),
					"class" => "menuitem_meta",
					"type" => "info"),
				"menuitem_price" => array(
					"title" => esc_html__('Price',  'pizzahouse'),
					"desc" => '',
					"override" => "page,post,custom",
					"class" => "menuitem_price",
					"std" => '',
					"allow_html" => true,
					"type" => "textarea"),
				"menuitem_spicylevel" => array(
					"title" => esc_html__("Spicy Level", 'pizzahouse'),
					"desc" => '',
					"override" => "page,post,custom",
					"class" => "menuitem_spicylevel",
					"std" => 0,
					"min" => 0,
					"max" => 5,
					"step" => 1,
					"type" => "spinner"),
				
				
				"mb_info_menuitems_2" => array(
					"title" => esc_html__('Nutritional Information', 'pizzahouse'),
					"override" => "page,post,custom",
					"divider" => false,
					"desc" => '',
					"class" => "menuitem_nutritional",
					"type" => "info"),
				"menuitem_calories" => array(
					"title" => esc_html__('Calories',  'pizzahouse'),
					"desc" => wp_kses_data( __("Calories in: Kcal", 'pizzahouse') ),
					"override" => "page,post,custom",
					"class" => "menuitem_calories",
					"std" => '',
					"type" => "text"),
				"menuitem_cholesterol" => array(
					"title" => esc_html__('Cholesterol',  'pizzahouse'),
					"desc" => wp_kses_data( __("Cholesterol in: mg", 'pizzahouse') ),
					"override" => "page,post,custom",
					"class" => "menuitem_cholesterol",
					"std" => '',
					"type" => "text"),
				"menuitem_fiber" => array(
					"title" => esc_html__('Fiber',  'pizzahouse'),
					"desc" => wp_kses_data( __("Fiber in: g", 'pizzahouse') ),
					"override" => "page,post,custom",
					"class" => "menuitem_fiber",
					"std" => '',
					"type" => "text"),
				"menuitem_sodium" => array(
					"title" => esc_html__('Sodium',  'pizzahouse'),
					"desc" => wp_kses_data( __("Sodium in: mg", 'pizzahouse') ),
					"override" => "page,post,custom",
					"class" => "menuitem_sodium",
					"std" => '',
					"type" => "text"),
				"menuitem_carbohydrates" => array(
					"title" => esc_html__('Carbohydrates',  'pizzahouse'),
					"desc" => wp_kses_data( __("Carbohydrates in: g", 'pizzahouse') ),
					"override" => "page,post,custom",
					"class" => "menuitem_carbohydrates",
					"std" => '',
					"type" => "text"),
				"menuitem_fat" => array(
					"title" => esc_html__('Fat',  'pizzahouse'),
					"desc" => wp_kses_data( __("Fat in: g", 'pizzahouse') ),
					"override" => "page,post,custom",
					"class" => "menuitem_fat",
					"std" => '',
					"type" => "text"),
				"menuitem_protein" => array(
					"title" => esc_html__('Protein',  'pizzahouse'),
					"desc" => wp_kses_data( __("Protein in: g", 'pizzahouse') ),
					"override" => "page,post,custom",
					"class" => "menuitem_protein",
					"std" => '',
					"type" => "text"),
				"menuitem_ingredients" => array(
					"title" => esc_html__('Ingredients',  'pizzahouse'),
					"desc" => wp_kses_data( __('One ingredient in row',  'pizzahouse') ),
					"override" => "page,post,custom",
					"class" => "menuitem_ingredients",
					"std" => "",
					"allow_html" => true,
					"allow_js" => true,
					"type" => "textarea"),
				"menuitem_product" => array(
					"title" => __('Link to product',  'pizzahouse'),
					"desc" => __("Link to product page for this menu items", 'pizzahouse'),
					"override" => "page,post,custom",
					"class" => "menuitem_product",
					"std" => '',
					"options" => pizzahouse_get_list_posts(false, 'product'),
					"type" => "select")
				
				)
			);
		}
	}
}


// Return true, if current page is menuitems page
if ( !function_exists( 'pizzahouse_is_menuitems_page' ) ) {
	function pizzahouse_is_menuitems_page() {
		$is = in_array(pizzahouse_storage_get('page_template'), array('blog-menuitems', 'single-menuitem'));
		if (!$is) {
			if (!pizzahouse_storage_empty('pre_query'))
				$is = pizzahouse_storage_call_obj_method('pre_query', 'get', 'post_type')=='menuitems'
						|| pizzahouse_storage_call_obj_method('pre_query', 'is_tax', 'menuitems_group')
						|| (pizzahouse_storage_call_obj_method('pre_query', 'is_page')
							&& ($id=pizzahouse_get_template_page_id('blog-menuitems')) > 0
							&& $id==pizzahouse_storage_get_obj_property('pre_query', 'queried_object_id', 0)
							);
			else
				$is = get_query_var('post_type')=='menuitems' 
						|| is_tax('menuitems_group') 
						|| (is_page() && ($id=pizzahouse_get_template_page_id('blog-menuitems')) > 0 && $id==get_the_ID());
		}
		return $is;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'pizzahouse_menuitems_detect_inheritance_key' ) ) {
	//Handler of add_filter('pizzahouse_filter_detect_inheritance_key',	'pizzahouse_menuitems_detect_inheritance_key', 9, 1);
	function pizzahouse_menuitems_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return pizzahouse_is_menuitems_page() ? 'menuitems' : '';
	}
}

// Filter to detect current page slug
if ( !function_exists( 'pizzahouse_menuitems_get_blog_type' ) ) {
	//Handler of add_filter('pizzahouse_filter_get_blog_type',	'pizzahouse_menuitems_get_blog_type', 9, 2);
	function pizzahouse_menuitems_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		if ($query && $query->is_tax('menuitems_group') || is_tax('menuitems_group'))
			$page = 'menuitems_category';
		else if ($query && $query->get('post_type')=='menuitems' || get_query_var('post_type')=='menuitems')
			$page = $query && $query->is_single() || is_single() ? 'menuitems_item' : 'menuitems';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'pizzahouse_menuitems_get_blog_title' ) ) {
	//Handler of add_filter('pizzahouse_filter_get_blog_title',	'pizzahouse_menuitems_get_blog_title', 9, 2);
	function pizzahouse_menuitems_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		if ( pizzahouse_strpos($page, 'menuitems')!==false ) {
			if ( $page == 'menuitems_category' ) {
				$term = get_term_by( 'slug', get_query_var( 'menuitems_group' ), 'menuitems_group', OBJECT);
				$title = $term->name;
			} else if ( $page == 'menuitems_item' ) {
				$title = pizzahouse_get_post_title();
			} else {
				$title = esc_html__('All menuitems', 'pizzahouse');
			}
		}
		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'pizzahouse_menuitems_get_stream_page_title' ) ) {
	//Handler of add_filter('pizzahouse_filter_get_stream_page_title',	'pizzahouse_menuitems_get_stream_page_title', 9, 2);
	function pizzahouse_menuitems_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (pizzahouse_strpos($page, 'menuitems')!==false) {
			if (($page_id = pizzahouse_menuitems_get_stream_page_id(0, $page=='menuitems' ? 'blog-menuitems' : $page)) > 0)
				$title = pizzahouse_get_post_title($page_id);
			else
				$title = esc_html__('All menuitems', 'pizzahouse');
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'pizzahouse_menuitems_get_stream_page_id' ) ) {
	//Handler of add_filter('pizzahouse_filter_get_stream_page_id',	'pizzahouse_menuitems_get_stream_page_id', 9, 2);
	function pizzahouse_menuitems_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (pizzahouse_strpos($page, 'menuitems')!==false) $id = pizzahouse_get_template_page_id('blog-menuitems');
		return $id;
	}
}

// Filter to detect stream page URL
if ( !function_exists( 'pizzahouse_menuitems_get_stream_page_link' ) ) {
	//Handler of add_filter('pizzahouse_filter_get_stream_page_link',	'pizzahouse_menuitems_get_stream_page_link', 9, 2);
	function pizzahouse_menuitems_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (pizzahouse_strpos($page, 'menuitems')!==false) {
			$id = pizzahouse_get_template_page_id('blog-menuitems');
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'pizzahouse_menuitems_get_current_taxonomy' ) ) {
	//Handler of add_filter('pizzahouse_filter_get_current_taxonomy',	'pizzahouse_menuitems_get_current_taxonomy', 9, 2);
	function pizzahouse_menuitems_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( pizzahouse_strpos($page, 'menuitems')!==false ) {
			$tax = 'menuitems_group';
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'pizzahouse_menuitems_is_taxonomy' ) ) {
	//Handler of add_filter('pizzahouse_filter_is_taxonomy',	'pizzahouse_menuitems_is_taxonomy', 9, 2);
	function pizzahouse_menuitems_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else 
			return $query && $query->get('menuitems_group')!='' || is_tax('menuitems_group') ? 'menuitems_group' : '';
	}
}

// Add custom post type and/or taxonomies arguments to the query
if ( !function_exists( 'pizzahouse_menuitems_query_add_filters' ) ) {
	//Handler of add_filter('pizzahouse_filter_query_add_filters',	'pizzahouse_menuitems_query_add_filters', 9, 2);
	function pizzahouse_menuitems_query_add_filters($args, $filter) {
		if ($filter == 'menuitems') {
			$args['post_type'] = 'menuitems';
		}
		return $args;
	}
}




// AJAX handler - return menuitems list
//----------------------------------------------------------------------------
if ( !function_exists( 'pizzahouse_callback_ajax_menuitem' ) ) {
	function pizzahouse_callback_ajax_menuitem() {
		
		if ( !wp_verify_nonce( pizzahouse_get_value_gp('nonce'), admin_url('admin-ajax.php') ) )
			die();
		
		$response = array('error'=>'', 'data' => '');
		
		$id = $_REQUEST['text'];
		
		$response['data'] = pizzahouse_sc_menuitems(array(
			'style' => 'menuitems-2',
			'columns' => '1',
			'popup' => 'no',
			'slider' => 'no',
			'custom' => 'no',
			'count' => '1',
			'offset' => '0',
			'orderby' => 'title',
			'order' => 'asc',
			'ids' => $id,
			'top' => 'inherit',
			'bottom' => 'inherit',
			'left' => 'inherit',
			'right' => 'inherit'
			));
		
		echo json_encode($response);
		die();
	}
}





// ---------------------------------- [trx_menuitems] ---------------------------------------

/*
[trx_menuitems id="unique_id" columns="3" style="menuitems-1|menuitems-2|..."]
	[trx_menuitems_item name="menuitem name" position="director" image="url"]Description text[/trx_menuitems_item]
	...
[/trx_menuitems]
*/
if ( !function_exists( 'pizzahouse_sc_menuitems' ) ) {
	function pizzahouse_sc_menuitems($atts, $content=null){
		if (pizzahouse_in_shortcode_blogger()) return '';
		extract(pizzahouse_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "menuitems-1",
			"columns" => 4,
			"popup" => "no",
			"slider" => "no",
			"slides_space" => 0,
			"controls" => "no",
			"interval" => "",
			"autoheight" => "no",
			"ids" => "",
			"cat" => "",
			"count" => 4,
			"offset" => "",
			"orderby" => "date",
			"order" => "desc",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link_caption" => esc_html__('Learn more', 'pizzahouse'),
			"link" => '',
			"scheme" => '',
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));

		if (empty($id)) $id = "sc_menuitems_".str_replace('.', '', mt_rand());
		if (empty($width)) $width = "100%";
		if (!empty($height) && pizzahouse_param_is_on($autoheight)) $autoheight = "no";
		if (empty($interval)) $interval = mt_rand(5000, 10000);

		$class .= ($class ? ' ' : '') . pizzahouse_get_css_position_as_classes($top, $right, $bottom, $left);

		$ws = pizzahouse_get_css_dimensions_from_values($width);
		$hs = pizzahouse_get_css_dimensions_from_values('', $height);
		$css .= ($hs) . ($ws);
	
		$columns = max(1, min(12, $columns));
		$count = max(1, (int) $count);
		if ($count < $columns) $columns = $count;

		if (pizzahouse_param_is_on($slider)) pizzahouse_enqueue_slider('swiper');

		pizzahouse_storage_set('sc_menuitems_data', array(
			'id'=>$id,
            'style'=>$style,
            'counter'=>0,
            'columns'=>$columns,
            'slider'=>$slider,
            'css_wh'=>$ws . $hs
            )
        );

		$output = '<div' . ($id ? ' id="'.esc_attr($id).'_wrap"' : '') 
						. ' class="sc_menuitems_wrap'
						. ($scheme && !pizzahouse_param_is_off($scheme) && !pizzahouse_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '')
						.'">'
					. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
						. ' class="sc_menuitems'
							. ' sc_menuitems_style_'.esc_attr($style)
							. ' ' . esc_attr(pizzahouse_get_template_property($style, 'container_classes'))
							. (!empty($class) ? ' '.esc_attr($class) : '')
						.'"'
						. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
						. (!pizzahouse_param_is_off($animation) ? ' data-animation="'.esc_attr(pizzahouse_get_animation_classes($animation)).'"' : '')
					. '>'
					. (!empty($subtitle) ? '<h6 class="sc_menuitems_subtitle sc_item_subtitle">' . trim(pizzahouse_strmacros($subtitle)) . '</h6>' : '')
					. (!empty($title) ? '<h2 class="sc_menuitems_title sc_item_title' . (empty($description) ? ' sc_item_title_without_descr' : ' sc_item_title_without_descr') . '">' . trim(pizzahouse_strmacros($title)) . '</h2>' : '')
					. (!empty($description) ? '<div class="sc_menuitems_descr sc_item_descr">' . trim(pizzahouse_strmacros($description)) . '</div>' : '')
					. (pizzahouse_param_is_on($slider)
						? ('<div class="sc_slider_swiper swiper-slider-container'
										. ' ' . esc_attr(pizzahouse_get_slider_controls_classes($controls))
										. (pizzahouse_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
										. ($hs ? ' sc_slider_height_fixed' : '')
										. '"'
									. (!empty($width) && pizzahouse_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
									. (!empty($height) && pizzahouse_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
									. ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
									. ($columns > 1 ? ' data-slides-per-view="' . esc_attr($columns) . '"' : '')
									. ($slides_space > 0 ? ' data-slides-space="' . esc_attr($slides_space) . '"' : '')
									. ($style!='menuitems-1' ? ' data-slides-min-width="250"' : '')
								. '>'
							. '<div class="slides swiper-wrapper">')
						: ($columns > 1 
							? '<div class="sc_columns columns_wrap">' 
							: '')
						);
	
		global $post;

		if (!empty($ids)) {
			$posts = explode(',', $ids);
			$count = count($posts);
		}
		
		$args = array(
			'post_type' => 'menuitems',
			'post_status' => 'publish',
			'posts_per_page' => $count,
			'ignore_sticky_posts' => true,
			'order' => $order=='asc' ? 'asc' : 'desc',
		);
	
		if ($offset > 0 && empty($ids)) {
			$args['offset'] = $offset;
		}
	
		$args = pizzahouse_query_add_sort_order($args, $orderby, $order);
		$args = pizzahouse_query_add_posts_and_cats($args, $ids, 'menuitems', $cat, 'menuitems_group');

		$query = new WP_Query( $args );

		$post_number = 0;
		$post_list = array();

		while ( $query->have_posts() ) { 
			$query->the_post();
			$post_number++;
			$args = array(
				'layout' => $style,
				'show' => false,
				'number' => $post_number,
				'posts_on_page' => ($count > 0 ? $count : $query->found_posts),
				"descr" => pizzahouse_get_custom_option('post_excerpt_maxlength'.($columns > 1 ? '_masonry' : '')),
				"orderby" => $orderby,
				'content' => false,
				'terms_list' => false,
				'columns_count' => $columns,
				'slider' => $slider,
				'tag_id' => $id ? $id . '_' . $post_number : '',
				'tag_class' => '',
				'tag_animation' => '',
				'tag_css' => '',
				'tag_css_wh' => $ws . $hs
			);
			$post_data = pizzahouse_get_post_data($args);
			$post_meta = get_post_meta($post_data['post_id'], pizzahouse_storage_get('options_prefix') . '_post_options', true);
			$thumb_sizes = pizzahouse_get_thumb_sizes(array('layout' => $style));
			$post_list[] = $post_data['post_id'];

			$args['menuitem_price'] = $post_meta['menuitem_price'];
			$args['menuitem_spicylevel'] = $post_meta['menuitem_spicylevel'];
			$args['menuitem_calories'] = $post_meta['menuitem_calories'];
			$args['menuitem_cholesterol'] = $post_meta['menuitem_cholesterol'];
			$args['menuitem_fiber'] = $post_meta['menuitem_fiber'];
			$args['menuitem_sodium'] = $post_meta['menuitem_sodium'];
			$args['menuitem_carbohydrates'] = $post_meta['menuitem_carbohydrates'];
			$args['menuitem_fat'] = $post_meta['menuitem_fat'];
			$args['menuitem_protein'] = $post_meta['menuitem_protein'];
			$args['menuitem_ingredients'] = $post_meta['menuitem_ingredients'];
			$args['menuitem_product'] = $post_meta['menuitem_product'];
			$args['menuitem_image'] = $post_data['post_thumb'];
			
			$args['popup'] = $popup;

			$args['menuitem_link'] = pizzahouse_param_is_on('menuitem_show_link')
				? (!empty($post_meta['menuitem_link']) ? $post_meta['menuitem_link'] : $post_data['post_link'])
				: '';
			
			$output .= pizzahouse_show_post_layout($args, $post_data);
		}
		wp_reset_postdata();

		pizzahouse_storage_set_array2('js_vars', 'menuitems', $id, join(',', $post_list));
	
		if (pizzahouse_param_is_on($slider)) {
			$output .= '</div>'
				. '<div class="sc_slider_controls_wrap"><a class="sc_slider_prev" href="#"></a><a class="sc_slider_next" href="#"></a></div>'
				. '<div class="sc_slider_pagination_wrap"></div>'
				. '</div>';
		} else if ($columns > 1) {
			$output .= '</div>';
		}

		$output .= (!empty($link) ? '<div class="sc_menuitems_button sc_item_button">'.pizzahouse_do_shortcode('[trx_button link="'.esc_url($link).'" icon="icon-right"]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
				. '</div><!-- /.sc_menuitems -->'
			. '</div><!-- /.sc_menuitems_wrap -->';
	
		// Add template specific scripts and styles
		do_action('pizzahouse_action_blog_scripts', $style);
	
		return apply_filters('pizzahouse_shortcode_output', $output, 'trx_menuitems', $atts, $content);
	}
	add_shortcode('trx_menuitems', 'pizzahouse_sc_menuitems');
}
// ---------------------------------- [/trx_menuitems] ---------------------------------------



// Add [trx_menuitems] and [trx_menuitems_item] in the shortcodes list
if (!function_exists('pizzahouse_menuitems_reg_shortcodes')) {
	//Handler of add_filter('pizzahouse_action_shortcodes_list',	'pizzahouse_menuitems_reg_shortcodes');
	function pizzahouse_menuitems_reg_shortcodes() {
		if (pizzahouse_storage_isset('shortcodes')) {

			$menuitems_groups = pizzahouse_get_list_terms(false, 'menuitems_group');
			$menuitems_styles = pizzahouse_get_list_templates('menuitems');
			$controls 		  = pizzahouse_get_list_slider_controls();

			pizzahouse_sc_map_after('trx_list', array(

				// MenuItems
				"trx_menuitems" => array(
					"title" => esc_html__("Menu Items", "pizzahouse"),
					"desc" => wp_kses_data( __("Insert menu items list in your page (post)", "pizzahouse") ),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"title" => array(
							"title" => esc_html__("Title", "pizzahouse"),
							"desc" => wp_kses_data( __("Title for the block", "pizzahouse") ),
							"value" => "",
							"type" => "text"
						),
						"subtitle" => array(
							"title" => esc_html__("Subtitle", "pizzahouse"),
							"desc" => wp_kses_data( __("Subtitle for the block", "pizzahouse") ),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => esc_html__("Description", "pizzahouse"),
							"desc" => wp_kses_data( __("Short description for the block", "pizzahouse") ),
							"value" => "",
							"type" => "textarea"
						),
						"style" => array(
							"title" => esc_html__("MenuItems style", "pizzahouse"),
							"desc" => wp_kses_data( __("Select style to display menuitems list", "pizzahouse") ),
							"value" => "menuitems-1",
							"type" => "select",
							"options" => $menuitems_styles
						),
						"columns" => array(
							"title" => esc_html__("Columns", "pizzahouse"),
							"desc" => wp_kses_data( __("How many columns use to show menuitems", "pizzahouse") ),
							"value" => 4,
							"min" => 2,
							"max" => 6,
							"step" => 1,
							"type" => "spinner"
						),
						"scheme" => array(
							"title" => esc_html__("Color scheme", "pizzahouse"),
							"desc" => wp_kses_data( __("Select color scheme for this block", "pizzahouse") ),
							"value" => "",
							"type" => "checklist",
							"options" => pizzahouse_get_sc_param('schemes')
						),
						"popup" => array(
							"title" => esc_html__("Popup info", "pizzahouse"),
							"desc" => wp_kses_data( __("If 'popup info' is set to 'no', the menu item will be opened in the same window. If it's switched to 'yes', a popup window will show up.", "pizzahouse") ),
							"value" => "no",
							"type" => "switch",
							"options" => pizzahouse_get_sc_param('yes_no')
						),
						"slider" => array(
							"title" => esc_html__("Slider", "pizzahouse"),
							"desc" => wp_kses_data( __("Use slider to show menuitems", "pizzahouse") ),
							"value" => "no",
							"type" => "switch",
							"options" => pizzahouse_get_sc_param('yes_no')
						),
						"controls" => array(
							"title" => esc_html__("Controls", "pizzahouse"),
							"desc" => wp_kses_data( __("Slider controls style and position", "pizzahouse") ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"divider" => true,
							"value" => "no",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $controls
						),
						"slides_space" => array(
							"title" => esc_html__("Space between slides", "pizzahouse"),
							"desc" => wp_kses_data( __("Size of space (in px) between slides", "pizzahouse") ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => 0,
							"min" => 0,
							"max" => 100,
							"step" => 10,
							"type" => "spinner"
						),
						"interval" => array(
							"title" => esc_html__("Slides change interval", "pizzahouse"),
							"desc" => wp_kses_data( __("Slides change interval (in milliseconds: 1000ms = 1s)", "pizzahouse") ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => 7000,
							"step" => 500,
							"min" => 0,
							"type" => "spinner"
						),
						"autoheight" => array(
							"title" => esc_html__("Autoheight", "pizzahouse"),
							"desc" => wp_kses_data( __("Change whole slider's height (make it equal current slide's height)", "pizzahouse") ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => "no",
							"type" => "switch",
							"options" => pizzahouse_get_sc_param('yes_no')
						),
						"cat" => array(
							"title" => esc_html__("Categories", "pizzahouse"),
							"desc" => wp_kses_data( __("Select categories (groups) to show menu items. If empty - select items from any category (group) or from IDs list", "pizzahouse") ),
							"dependency" => array(
								'custom' => array('no')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => pizzahouse_array_merge(array(0 => esc_html__('- Select category -', 'pizzahouse')), $menuitems_groups)
						),
						"count" => array(
							"title" => esc_html__("Number of posts", "pizzahouse"),
							"desc" => wp_kses_data( __("How many posts will be displayed? If used IDs - this parameter ignored.", "pizzahouse") ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 4,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => esc_html__("Offset before select posts", "pizzahouse"),
							"desc" => wp_kses_data( __("Skip posts before select next part.", "pizzahouse") ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Post order by", "pizzahouse"),
							"desc" => wp_kses_data( __("Select desired posts sorting method", "pizzahouse") ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "title",
							"type" => "select",
							"options" => pizzahouse_get_sc_param('sorting')
						),
						"order" => array(
							"title" => esc_html__("Post order", "pizzahouse"),
							"desc" => wp_kses_data( __("Select desired posts order", "pizzahouse") ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "asc",
							"type" => "switch",
							"size" => "big",
							"options" => pizzahouse_get_sc_param('ordering')
						),
						"ids" => array(
							"title" => esc_html__("Post IDs list", "pizzahouse"),
							"desc" => wp_kses_data( __("Comma separated list of posts ID. If set - parameters above are ignored!", "pizzahouse") ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "",
							"type" => "text"
						),
						"link" => array(
							"title" => esc_html__("Button URL", "pizzahouse"),
							"desc" => wp_kses_data( __("Link URL for the button at the bottom of the block", "pizzahouse") ),
							"value" => "",
							"type" => "text"
						),
						"link_caption" => array(
							"title" => esc_html__("Button caption", "pizzahouse"),
							"desc" => wp_kses_data( __("Caption for the button at the bottom of the block", "pizzahouse") ),
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
				)

			));
		}
	}
}


// Add [trx_menuitems] and [trx_menuitems_item] in the VC shortcodes list
if (!function_exists('pizzahouse_menuitems_reg_shortcodes_vc')) {
	//Handler of add_filter('pizzahouse_action_shortcodes_list_vc',	'pizzahouse_menuitems_reg_shortcodes_vc');
	function pizzahouse_menuitems_reg_shortcodes_vc() {

		$menuitems_groups = pizzahouse_get_list_terms(false, 'menuitems_group');
		$menuitems_styles = pizzahouse_get_list_templates('menuitems');
		$controls		= pizzahouse_get_list_slider_controls();

		// MenuItems
		vc_map( array(
				"base" => "trx_menuitems",
				"name" => esc_html__("MenuItems", 'pizzahouse'),
				"description" => wp_kses_data( __("Insert menuitems list", 'pizzahouse') ),
				"category" => esc_html__('Content', 'pizzahouse'),
				'icon' => 'icon_trx_menuitems',
				"class" => "trx_sc_columns trx_sc_menuitems",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_menuitems_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("MenuItems style", 'pizzahouse'),
						"description" => wp_kses_data( __("Select style to display menuitems list", 'pizzahouse') ),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip($menuitems_styles),
						"type" => "dropdown"
					),
					array(
						"param_name" => "scheme",
						"heading" => esc_html__("Color scheme", 'pizzahouse'),
						"description" => wp_kses_data( __("Select color scheme for this block", 'pizzahouse') ),
						"class" => "",
						"value" => array_flip(pizzahouse_get_sc_param('schemes')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "popup",
						"heading" => esc_html__("Popup info", 'pizzahouse'),
						"description" => wp_kses_data( __("If 'popup info' is set to 'no', the menu item will be opened in the same window. If it's switched to 'yes', a popup window will show up.", 'pizzahouse') ),
						"admin_label" => true,
						"class" => "",
						"std" => "no",
						"value" => array_flip(pizzahouse_get_sc_param('yes_no')),
						"type" => "dropdown"
						),
					array(
						"param_name" => "slider",
						"heading" => esc_html__("Slider", 'pizzahouse'),
						"description" => wp_kses_data( __("Use slider to show menuitems", 'pizzahouse') ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'pizzahouse'),
						"class" => "",
						"std" => "no",
						"value" => array_flip((array)pizzahouse_get_sc_param('yes_no')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "controls",
						"heading" => esc_html__("Controls", 'pizzahouse'),
						"description" => wp_kses_data( __("Slider controls style and position", 'pizzahouse') ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'pizzahouse'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"std" => "no",
						"value" => array_flip($controls),
						"type" => "dropdown"
					),
					array(
						"param_name" => "slides_space",
						"heading" => esc_html__("Space between slides", "pizzahouse"),
						"description" => wp_kses_data( __("Size of space (in px) between slides", "pizzahouse") ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'pizzahouse'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "interval",
						"heading" => esc_html__("Slides change interval", 'pizzahouse'),
						"description" => wp_kses_data( __("Slides change interval (in milliseconds: 1000ms = 1s)", 'pizzahouse') ),
						"group" => esc_html__('Slider', 'pizzahouse'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => "7000",
						"type" => "textfield"
					),
					array(
						"param_name" => "autoheight",
						"heading" => esc_html__("Autoheight", "pizzahouse"),
						"description" => wp_kses_data( __("Change whole slider's height (make it equal current slide's height)", "pizzahouse") ),
						"group" => esc_html__('Slider', 'pizzahouse'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => array("Autoheight" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "pizzahouse"),
						"description" => wp_kses_data( __("Title for the block", "pizzahouse") ),
						"admin_label" => true,
						"group" => esc_html__('Captions', 'pizzahouse'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "subtitle",
						"heading" => esc_html__("Subtitle", "pizzahouse"),
						"description" => wp_kses_data( __("Subtitle for the block", "pizzahouse") ),
						"group" => esc_html__('Captions', 'pizzahouse'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => esc_html__("Description", "pizzahouse"),
						"description" => wp_kses_data( __("Description for the block", "pizzahouse") ),
						"group" => esc_html__('Captions', 'pizzahouse'),
						"class" => "",
						"value" => "",
						"type" => "textarea"
					),
					array(
						"param_name" => "cat",
						"heading" => esc_html__("Categories", "pizzahouse"),
						"description" => wp_kses_data( __("Select category to show menuitems. If empty - select menuitems from any category (group) or from IDs list", "pizzahouse") ),
						"group" => esc_html__('Query', 'pizzahouse'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip((array)pizzahouse_array_merge(array(0 => esc_html__('- Select category -', 'pizzahouse')), $menuitems_groups)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", "pizzahouse"),
						"description" => wp_kses_data( __("How many columns use to show menuitems", "pizzahouse") ),
						"group" => esc_html__('Query', 'pizzahouse'),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Number of posts", "pizzahouse"),
						"description" => wp_kses_data( __("How many posts will be displayed? If used IDs - this parameter ignored.", "pizzahouse") ),
						"group" => esc_html__('Query', 'pizzahouse'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => esc_html__("Offset before select posts", "pizzahouse"),
						"description" => wp_kses_data( __("Skip posts before select next part.", "pizzahouse") ),
						"group" => esc_html__('Query', 'pizzahouse'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Post sorting", 'pizzahouse'),
						"description" => wp_kses_data( __("Select desired posts sorting method", 'pizzahouse') ),
						"group" => esc_html__('Query', 'pizzahouse'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip((array)pizzahouse_get_sc_param('sorting')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Post order", "pizzahouse"),
						"description" => wp_kses_data( __("Select desired posts order", "pizzahouse") ),
						"group" => esc_html__('Query', 'pizzahouse'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip((array)pizzahouse_get_sc_param('ordering')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("Menuitem's IDs list", "pizzahouse"),
						"description" => wp_kses_data( __("Comma separated list of menuitem's ID. If set - parameters above (category, count, order, etc.)  are ignored!", "pizzahouse") ),
						"group" => esc_html__('Query', 'pizzahouse'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Button URL", "pizzahouse"),
						"description" => wp_kses_data( __("Link URL for the button at the bottom of the block", "pizzahouse") ),
						"group" => esc_html__('Captions', 'pizzahouse'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link_caption",
						"heading" => esc_html__("Button caption", "pizzahouse"),
						"description" => wp_kses_data( __("Caption for the button at the bottom of the block", "pizzahouse") ),
						"group" => esc_html__('Captions', 'pizzahouse'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					pizzahouse_vc_width(),
					pizzahouse_vc_height(),
					pizzahouse_get_vc_param('margin_top'),
					pizzahouse_get_vc_param('margin_bottom'),
					pizzahouse_get_vc_param('margin_left'),
					pizzahouse_get_vc_param('margin_right'),
					pizzahouse_get_vc_param('id'),
					pizzahouse_get_vc_param('class'),
					pizzahouse_get_vc_param('animation'),
					pizzahouse_get_vc_param('css')
				),
				'js_view' => 'VcTrxColumnsView'
			) );
			
		class WPBakeryShortCode_Trx_MenuItems extends PIZZAHOUSE_VC_ShortCodeColumns {}

	}
}
?>