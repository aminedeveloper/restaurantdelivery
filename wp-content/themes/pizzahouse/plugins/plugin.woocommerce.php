<?php
/* Woocommerce support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('pizzahouse_woocommerce_theme_setup')) {
	add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_woocommerce_theme_setup', 1 );
	function pizzahouse_woocommerce_theme_setup() {

		if (pizzahouse_exists_woocommerce()) {
			
            add_theme_support( 'woocommerce' );
            // Next setting from the WooCommerce 3.0+ enable built-in image zoom on the single product page
            add_theme_support( 'wc-product-gallery-zoom' );
            // Next setting from the WooCommerce 3.0+ enable built-in image slider on the single product page
            add_theme_support( 'wc-product-gallery-slider' );
            // Next setting from the WooCommerce 3.0+ enable built-in image lightbox on the single product page
            add_theme_support( 'wc-product-gallery-lightbox' );
			
			add_action('pizzahouse_action_add_styles', 				'pizzahouse_woocommerce_frontend_scripts' );

			// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
			add_filter('pizzahouse_filter_get_blog_type',				'pizzahouse_woocommerce_get_blog_type', 9, 2);
			add_filter('pizzahouse_filter_get_blog_title',			'pizzahouse_woocommerce_get_blog_title', 9, 2);
			add_filter('pizzahouse_filter_get_current_taxonomy',		'pizzahouse_woocommerce_get_current_taxonomy', 9, 2);
			add_filter('pizzahouse_filter_is_taxonomy',				'pizzahouse_woocommerce_is_taxonomy', 9, 2);
			add_filter('pizzahouse_filter_get_stream_page_title',		'pizzahouse_woocommerce_get_stream_page_title', 9, 2);
			add_filter('pizzahouse_filter_get_stream_page_link',		'pizzahouse_woocommerce_get_stream_page_link', 9, 2);
			add_filter('pizzahouse_filter_get_stream_page_id',		'pizzahouse_woocommerce_get_stream_page_id', 9, 2);
			add_filter('pizzahouse_filter_detect_inheritance_key',	'pizzahouse_woocommerce_detect_inheritance_key', 9, 1);
			add_filter('pizzahouse_filter_detect_template_page_id',	'pizzahouse_woocommerce_detect_template_page_id', 9, 2);
			add_filter('pizzahouse_filter_orderby_need',				'pizzahouse_woocommerce_orderby_need', 9, 2);

			add_filter('pizzahouse_filter_show_post_navi', 			'pizzahouse_woocommerce_show_post_navi');
			add_filter('pizzahouse_filter_list_post_types', 			'pizzahouse_woocommerce_list_post_types');

			add_action('pizzahouse_action_shortcodes_list', 			'pizzahouse_woocommerce_reg_shortcodes', 20);
			if (function_exists('pizzahouse_exists_visual_composer') && pizzahouse_exists_visual_composer())
				add_action('pizzahouse_action_shortcodes_list_vc',	'pizzahouse_woocommerce_reg_shortcodes_vc', 20);
		}

		if (is_admin()) {
			add_filter( 'pizzahouse_filter_importer_required_plugins',		'pizzahouse_woocommerce_importer_required_plugins', 10, 2 );
			add_filter( 'pizzahouse_filter_required_plugins',					'pizzahouse_woocommerce_required_plugins' );
		}
	}
}

if ( !function_exists( 'pizzahouse_woocommerce_settings_theme_setup2' ) ) {
	add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_woocommerce_settings_theme_setup2', 3 );
	function pizzahouse_woocommerce_settings_theme_setup2() {
		if (pizzahouse_exists_woocommerce()) {
			// Add WooCommerce pages in the Theme inheritance system
			pizzahouse_add_theme_inheritance( array( 'woocommerce' => array(
				'stream_template' => 'blog-woocommerce',		// This params must be empty
				'single_template' => 'single-woocommerce',		// They are specified to enable separate settings for blog and single wooc
				'taxonomy' => array('product_cat'),
				'taxonomy_tags' => array('product_tag'),
				'post_type' => array('product'),
				'override' => 'custom'
				) )
			);

			// Add WooCommerce specific options in the Theme Options

			pizzahouse_storage_set_array_before('options', 'partition_service', array(
				
				"partition_woocommerce" => array(
					"title" => esc_html__('WooCommerce', 'pizzahouse'),
					"icon" => "iconadmin-basket",
					"type" => "partition"),

				"info_wooc_1" => array(
					"title" => esc_html__('WooCommerce products list parameters', 'pizzahouse'),
					"desc" => esc_html__("Select WooCommerce products list's style and crop parameters", 'pizzahouse'),
					"type" => "info"),
		
				"shop_mode" => array(
					"title" => esc_html__('Shop list style',  'pizzahouse'),
					"desc" => esc_html__("WooCommerce products list's style: thumbs or list with description", 'pizzahouse'),
					"std" => "thumbs",
					"divider" => false,
					"options" => array(
						'thumbs' => esc_html__('Thumbs', 'pizzahouse'),
						'list' => esc_html__('List', 'pizzahouse')
					),
					"type" => "checklist"),
		
				"show_mode_buttons" => array(
					"title" => esc_html__('Show style buttons',  'pizzahouse'),
					"desc" => esc_html__("Show buttons to allow visitors change list style", 'pizzahouse'),
					"std" => "yes",
					"options" => pizzahouse_get_options_param('list_yes_no'),
					"type" => "switch"),

				"shop_loop_columns" => array(
					"title" => esc_html__('Shop columns',  'pizzahouse'),
					"desc" => esc_html__("How many columns used to show products on shop page", 'pizzahouse'),
					"std" => "3",
					"step" => 1,
					"min" => 1,
					"max" => 6,
					"type" => "spinner"),

				"show_currency" => array(
					"title" => esc_html__('Show currency selector', 'pizzahouse'),
					"desc" => esc_html__('Show currency selector in the header', 'pizzahouse'),
					"std" => "yes",
					"options" => pizzahouse_get_options_param('list_yes_no'),
					"type" => "switch"),
		
				"show_cart" => array(
					"title" => esc_html__('Show cart button', 'pizzahouse'),
					"desc" => esc_html__('Show cart button in the header', 'pizzahouse'),
					"std" => "shop",
					"options" => array(
						'hide'   => esc_html__('Hide', 'pizzahouse'),
						'always' => esc_html__('Always', 'pizzahouse'),
						'shop'   => esc_html__('Only on shop pages', 'pizzahouse')
					),
					"type" => "checklist"),

				"crop_product_thumb" => array(
					"title" => esc_html__("Crop product's thumbnail",  'pizzahouse'),
					"desc" => esc_html__("Crop product's thumbnails on search results page or scale it", 'pizzahouse'),
					"std" => "no",
					"options" => pizzahouse_get_options_param('list_yes_no'),
					"type" => "switch")
				
				)
			);

		}
	}
}

// WooCommerce hooks
if (!function_exists('pizzahouse_woocommerce_theme_setup3')) {
	add_action( 'pizzahouse_action_after_init_theme', 'pizzahouse_woocommerce_theme_setup3' );
	function pizzahouse_woocommerce_theme_setup3() {

		if (pizzahouse_exists_woocommerce()) {
			add_action(    'woocommerce_before_subcategory_title',		'pizzahouse_woocommerce_open_thumb_wrapper', 9 );
			add_action(    'woocommerce_before_shop_loop_item_title',	'pizzahouse_woocommerce_open_thumb_wrapper', 9 );

			add_action(    'woocommerce_before_subcategory_title',		'pizzahouse_woocommerce_open_item_wrapper', 20 );
			add_action(    'woocommerce_before_shop_loop_item_title',	'pizzahouse_woocommerce_open_item_wrapper', 20 );

			add_action(    'woocommerce_after_subcategory',				'pizzahouse_woocommerce_close_item_wrapper', 20 );
			add_action(    'woocommerce_after_shop_loop_item',			'pizzahouse_woocommerce_close_item_wrapper', 20 );

			add_action(    'woocommerce_after_shop_loop_item_title',	'pizzahouse_woocommerce_after_shop_loop_item_title', 7);

			add_action(    'woocommerce_after_subcategory_title',		'pizzahouse_woocommerce_after_subcategory_title', 10 );

			add_action(    'the_title',									'pizzahouse_woocommerce_the_title');

            // Wrap category title into link
			remove_action( 'woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_title', 10 );
            add_action(		'woocommerce_shop_loop_subcategory_title',  'pizzahouse_woocommerce_shop_loop_subcategory_title', 9, 1);

			// Remove link around product item
			remove_action('woocommerce_before_shop_loop_item',			'woocommerce_template_loop_product_link_open', 10);
			remove_action('woocommerce_after_shop_loop_item',			'woocommerce_template_loop_product_link_close', 5);
			// Remove link around product category
			remove_action('woocommerce_before_subcategory',				'woocommerce_template_loop_category_link_open', 10);
			remove_action('woocommerce_after_subcategory',				'woocommerce_template_loop_category_link_close', 10);
            // Replace product item title tag from h2 to h3
            remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
            add_action( 'woocommerce_shop_loop_item_title',    'tennisclub_woocommerce_template_loop_product_title', 10 );
		}

		if (pizzahouse_is_woocommerce_page()) {
			
			remove_action( 'woocommerce_sidebar', 						'woocommerce_get_sidebar', 10 );					// Remove WOOC sidebar
			
			remove_action( 'woocommerce_before_main_content',			'woocommerce_output_content_wrapper', 10);
			add_action(    'woocommerce_before_main_content',			'pizzahouse_woocommerce_wrapper_start', 10);
			
			remove_action( 'woocommerce_after_main_content',			'woocommerce_output_content_wrapper_end', 10);		
			add_action(    'woocommerce_after_main_content',			'pizzahouse_woocommerce_wrapper_end', 10);

			add_action(    'woocommerce_show_page_title',				'pizzahouse_woocommerce_show_page_title', 10);

			remove_action( 'woocommerce_single_product_summary',		'woocommerce_template_single_title', 5);		
			add_action(    'woocommerce_single_product_summary',		'pizzahouse_woocommerce_show_product_title', 5 );

			add_action(    'woocommerce_before_shop_loop', 				'pizzahouse_woocommerce_before_shop_loop', 10 );

			remove_action( 'woocommerce_after_shop_loop',				'woocommerce_pagination', 10 );
			add_action(    'woocommerce_after_shop_loop',				'pizzahouse_woocommerce_pagination', 10 );

			add_action(    'woocommerce_product_meta_end',				'pizzahouse_woocommerce_show_product_id', 10);

            if (pizzahouse_param_is_on(pizzahouse_get_custom_option('show_post_related'))) {
                add_filter('woocommerce_output_related_products_args', 'pizzahouse_woocommerce_output_related_products_args');
                add_filter('woocommerce_related_products_args', 'pizzahouse_woocommerce_related_products_args');
            } else {
                remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
            }

			add_filter(    'woocommerce_product_thumbnails_columns',	'pizzahouse_woocommerce_product_thumbnails_columns' );

			add_filter(    'get_product_search_form',					'pizzahouse_woocommerce_get_product_search_form' );


			add_filter(    'post_class',								'pizzahouse_woocommerce_loop_shop_columns_class' );
            add_filter(    'product_cat_class',							'pizzahouse_woocommerce_loop_shop_columns_class', 10, 3 );
			
			pizzahouse_enqueue_popup();
		}
	}
}



// Check if WooCommerce installed and activated
if ( !function_exists( 'pizzahouse_exists_woocommerce' ) ) {
	function pizzahouse_exists_woocommerce() {
		return class_exists('Woocommerce');
	}
}

// Return true, if current page is any woocommerce page
if ( !function_exists( 'pizzahouse_is_woocommerce_page' ) ) {
	function pizzahouse_is_woocommerce_page() {
		$rez = false;
		if (pizzahouse_exists_woocommerce()) {
			if (!pizzahouse_storage_empty('pre_query')) {
				$id = pizzahouse_storage_get_obj_property('pre_query', 'queried_object_id', 0);
				$rez = pizzahouse_storage_call_obj_method('pre_query', 'get', 'post_type')=='product' 
						|| $id==wc_get_page_id('shop')
						|| $id==wc_get_page_id('cart')
						|| $id==wc_get_page_id('checkout')
						|| $id==wc_get_page_id('myaccount')
						|| pizzahouse_storage_call_obj_method('pre_query', 'is_tax', 'product_cat')
						|| pizzahouse_storage_call_obj_method('pre_query', 'is_tax', 'product_tag')
						|| pizzahouse_storage_call_obj_method('pre_query', 'is_tax', get_object_taxonomies('product'));
						
			} else
				$rez = is_shop() || is_product() || is_product_category() || is_product_tag() || is_product_taxonomy() || is_cart() || is_checkout() || is_account_page();
		}
		return $rez;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'pizzahouse_woocommerce_detect_inheritance_key' ) ) {
	
	function pizzahouse_woocommerce_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return pizzahouse_is_woocommerce_page() ? 'woocommerce' : '';
	}
}

// Filter to detect current template page id
if ( !function_exists( 'pizzahouse_woocommerce_detect_template_page_id' ) ) {
	
	function pizzahouse_woocommerce_detect_template_page_id($id, $key) {
		if (!empty($id)) return $id;
		if ($key == 'woocommerce_cart')				$id = get_option('woocommerce_cart_page_id');
		else if ($key == 'woocommerce_checkout')	$id = get_option('woocommerce_checkout_page_id');
		else if ($key == 'woocommerce_account')		$id = get_option('woocommerce_account_page_id');
		else if ($key == 'woocommerce')				$id = get_option('woocommerce_shop_page_id');
		return $id;
	}
}

// Filter to detect current page type (slug)
if ( !function_exists( 'pizzahouse_woocommerce_get_blog_type' ) ) {
	
	function pizzahouse_woocommerce_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		
		if (is_shop()) 					$page = 'woocommerce_shop';
		else if ($query && $query->get('post_type')=='product' || is_product())		$page = 'woocommerce_product';
		else if ($query && $query->get('product_tag')!='' || is_product_tag())		$page = 'woocommerce_tag';
		else if ($query && $query->get('product_cat')!='' || is_product_category())	$page = 'woocommerce_category';
		else if (is_cart())				$page = 'woocommerce_cart';
		else if (is_checkout())			$page = 'woocommerce_checkout';
		else if (is_account_page())		$page = 'woocommerce_account';
		else if (is_woocommerce())		$page = 'woocommerce';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'pizzahouse_woocommerce_get_blog_title' ) ) {
	
	function pizzahouse_woocommerce_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		
		if ( pizzahouse_strpos($page, 'woocommerce')!==false ) {
			if ( $page == 'woocommerce_category' ) {
				$term = get_term_by( 'slug', get_query_var( 'product_cat' ), 'product_cat', OBJECT);
				$title = $term->name;
			} else if ( $page == 'woocommerce_tag' ) {
				$term = get_term_by( 'slug', get_query_var( 'product_tag' ), 'product_tag', OBJECT);
				$title = esc_html__('Tag:', 'pizzahouse') . ' ' . esc_html($term->name);
			} else if ( $page == 'woocommerce_cart' ) {
				$title = esc_html__( 'Your cart', 'pizzahouse' );
			} else if ( $page == 'woocommerce_checkout' ) {
				$title = esc_html__( 'Checkout', 'pizzahouse' );
			} else if ( $page == 'woocommerce_account' ) {
				$title = esc_html__( 'Account', 'pizzahouse' );
			} else if ( $page == 'woocommerce_product' ) {
				$title = pizzahouse_get_post_title();
			} else if (($page_id=get_option('woocommerce_shop_page_id')) > 0) {
				$title = pizzahouse_get_post_title($page_id);
			} else {
				$title = esc_html__( 'Shop', 'pizzahouse' );
			}
		}
		
		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'pizzahouse_woocommerce_get_stream_page_title' ) ) {
	
	function pizzahouse_woocommerce_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (pizzahouse_strpos($page, 'woocommerce')!==false) {
			if (($page_id = pizzahouse_woocommerce_get_stream_page_id(0, $page)) > 0)
				$title = pizzahouse_get_post_title($page_id);
			else
				$title = esc_html__('Shop', 'pizzahouse');				
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'pizzahouse_woocommerce_get_stream_page_id' ) ) {
	
	function pizzahouse_woocommerce_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (pizzahouse_strpos($page, 'woocommerce')!==false) {
			$id = get_option('woocommerce_shop_page_id');
		}
		return $id;
	}
}

// Filter to detect stream page link
if ( !function_exists( 'pizzahouse_woocommerce_get_stream_page_link' ) ) {
	
	function pizzahouse_woocommerce_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (pizzahouse_strpos($page, 'woocommerce')!==false) {
			$id = pizzahouse_woocommerce_get_stream_page_id(0, $page);
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'pizzahouse_woocommerce_get_current_taxonomy' ) ) {
	
	function pizzahouse_woocommerce_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( pizzahouse_strpos($page, 'woocommerce')!==false ) {
			$tax = 'product_cat';
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'pizzahouse_woocommerce_is_taxonomy' ) ) {
	
	function pizzahouse_woocommerce_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else 
			return $query!==null && $query->get('product_cat')!='' || is_product_category() ? 'product_cat' : '';
	}
}

// Return false if current plugin not need theme orderby setting
if ( !function_exists( 'pizzahouse_woocommerce_orderby_need' ) ) {
	
	function pizzahouse_woocommerce_orderby_need($need) {
		if ($need == false || pizzahouse_storage_empty('pre_query'))
			return $need;
		else {
			return pizzahouse_storage_call_obj_method('pre_query', 'get', 'post_type')!='product' 
					&& pizzahouse_storage_call_obj_method('pre_query', 'get', 'product_cat')==''
					&& pizzahouse_storage_call_obj_method('pre_query', 'get', 'product_tag')=='';
		}
	}
}

// Add custom post type into list
if ( !function_exists( 'pizzahouse_woocommerce_list_post_types' ) ) {
	
	function pizzahouse_woocommerce_list_post_types($list) {
		$list = is_array($list) ? $list : array();
		$list['product'] = esc_html__('Products', 'pizzahouse');
		return $list;
	}
}


	
// Enqueue WooCommerce custom styles
if ( !function_exists( 'pizzahouse_woocommerce_frontend_scripts' ) ) {
	
	function pizzahouse_woocommerce_frontend_scripts() {
		if (pizzahouse_is_woocommerce_page() || pizzahouse_get_custom_option('show_cart')=='always')
			if (file_exists(pizzahouse_get_file_dir('css/plugin.woocommerce.css')))
                wp_enqueue_style( 'pizzahouse-plugin-woocommerce-style',  pizzahouse_get_file_url('css/plugin.woocommerce.css'), array(), null );
	}
}

// Before main content
if ( !function_exists( 'pizzahouse_woocommerce_wrapper_start' ) ) {
	
	
	function pizzahouse_woocommerce_wrapper_start() {
		if (is_product() || is_cart() || is_checkout() || is_account_page()) {
			?>
			<article class="post_item post_item_single post_item_product">
			<?php
		} else {
			?>
			<div class="list_products shop_mode_<?php echo !pizzahouse_storage_empty('shop_mode') ? pizzahouse_storage_get('shop_mode') : 'thumbs'; ?>">
			<?php
		}
	}
}

// After main content
if ( !function_exists( 'pizzahouse_woocommerce_wrapper_end' ) ) {
	
	
	function pizzahouse_woocommerce_wrapper_end() {
		if (is_product() || is_cart() || is_checkout() || is_account_page()) {
			?>
			</article>	<!-- .post_item -->
			<?php
		} else {
			?>
			</div>	<!-- .list_products -->
			<?php
		}
	}
}

// Check to show page title
if ( !function_exists( 'pizzahouse_woocommerce_show_page_title' ) ) {
	
	function pizzahouse_woocommerce_show_page_title($defa=true) {
		return pizzahouse_get_custom_option('show_page_title')=='no';
	}
}

// Check to show product title
if ( !function_exists( 'pizzahouse_woocommerce_show_product_title' ) ) {
	
	
	function pizzahouse_woocommerce_show_product_title() {
		if (pizzahouse_get_custom_option('show_post_title')=='yes' || pizzahouse_get_custom_option('show_page_title')=='no') {
			wc_get_template( 'single-product/title.php' );
		}
	}
}

// New product excerpt with video shortcode
if ( !function_exists( 'pizzahouse_template_single_excerpt' ) ) {
    
    
    function pizzahouse_template_single_excerpt() {
        if ( ! defined( 'ABSPATH' ) ) {
            exit; // Exit if accessed directly
        }
        global $post;
        if ( ! $post->post_excerpt ) {
            return;
        }
        ?>
        <div itemprop="description">
            <?php echo pizzahouse_substitute_all(apply_filters( 'woocommerce_short_description', $post->post_excerpt )); ?>
        </div>
    <?php
    }
}

// Add list mode buttons
if ( !function_exists( 'pizzahouse_woocommerce_before_shop_loop' ) ) {
	
	function pizzahouse_woocommerce_before_shop_loop() {
		if (pizzahouse_get_custom_option('show_mode_buttons')=='yes') {
			echo '<div class="mode_buttons"><form action="' . esc_url(pizzahouse_get_current_url()) . '" method="post">'
				. '<input type="hidden" name="pizzahouse_shop_mode" value="'.esc_attr(pizzahouse_storage_get('shop_mode')).'" />'
				. '<a href="#" class="woocommerce_thumbs icon-th" title="'.esc_attr__('Show products as thumbs', 'pizzahouse').'"></a>'
				. '<a href="#" class="woocommerce_list icon-th-list" title="'.esc_attr__('Show products as list', 'pizzahouse').'"></a>'
				. '</form></div>';
		}
	}
}


// Open thumbs wrapper for categories and products
if ( !function_exists( 'pizzahouse_woocommerce_open_thumb_wrapper' ) ) {
	
	
	function pizzahouse_woocommerce_open_thumb_wrapper($cat='') {
		pizzahouse_storage_set('in_product_item', true);
		?>
		<div class="post_item_wrap">
			<div class="post_featured">
				<div class="post_thumb">
					<a class="hover_icon hover_icon_link" href="<?php echo esc_url(is_object($cat) ? get_term_link($cat->slug, 'product_cat') : get_permalink()); ?>">
		<?php
	}
}

// Open item wrapper for categories and products
if ( !function_exists( 'pizzahouse_woocommerce_open_item_wrapper' ) ) {
	
	
	function pizzahouse_woocommerce_open_item_wrapper($cat='') {
		?>
				</a>
			</div>
		</div>
		<div class="post_content">
		<?php
	}
}

// Close item wrapper for categories and products
if ( !function_exists( 'pizzahouse_woocommerce_close_item_wrapper' ) ) {
	
	
	function pizzahouse_woocommerce_close_item_wrapper($cat='') {
		?>
			</div>
		</div>
		<?php
		pizzahouse_storage_set('in_product_item', false);
	}
}

// Add excerpt in output for the product in the list mode
if ( !function_exists( 'pizzahouse_woocommerce_after_shop_loop_item_title' ) ) {
	
	function pizzahouse_woocommerce_after_shop_loop_item_title() {
		if (pizzahouse_storage_get('shop_mode') == 'list') {
		    $excerpt = apply_filters('the_excerpt', get_the_excerpt());
			echo '<div class="description">'.trim($excerpt).'</div>';
		}
	}
}

// Add excerpt in output for the product in the list mode
if ( !function_exists( 'pizzahouse_woocommerce_after_subcategory_title' ) ) {
	
	function pizzahouse_woocommerce_after_subcategory_title($category) {
		if (pizzahouse_storage_get('shop_mode') == 'list')
			echo '<div class="description">' . trim($category->description) . '</div>';
	}
}

// Add Product ID for single product
if ( !function_exists( 'pizzahouse_woocommerce_show_product_id' ) ) {
	
	function pizzahouse_woocommerce_show_product_id() {
		global $post, $product;
		echo '<span class="product_id">'.esc_html__('Product ID: ', 'pizzahouse') . '<span>' . ($post->ID) . '</span></span>';
	}
}

// Redefine number of related products
if ( !function_exists( 'pizzahouse_woocommerce_output_related_products_args' ) ) {
	
	function pizzahouse_woocommerce_output_related_products_args($args) {
		$ppp = $ccc = 0;
		if (pizzahouse_param_is_on(pizzahouse_get_custom_option('show_post_related'))) {
			$ccc_add = in_array(pizzahouse_get_custom_option('body_style'), array('fullwide', 'fullscreen')) ? 1 : 0;
			$ccc =  pizzahouse_get_custom_option('post_related_columns');
			$ccc = $ccc > 0 ? $ccc : (pizzahouse_param_is_off(pizzahouse_get_custom_option('show_sidebar_main')) ? 3+$ccc_add : 2+$ccc_add);
			$ppp = pizzahouse_get_custom_option('post_related_count');
			$ppp = $ppp > 0 ? $ppp : $ccc;
		}
		$args['posts_per_page'] = $ppp;
		$args['columns'] = $ccc;
		return $args;
	}
}

// Redefine post_type if number of related products == 0
if ( !function_exists( 'pizzahouse_woocommerce_related_products_args' ) ) {
	
	function pizzahouse_woocommerce_related_products_args($args) {
		if ($args['posts_per_page'] == 0)
			$args['post_type'] .= '_';
		return $args;
	}
}

// Number columns for product thumbnails
if ( !function_exists( 'pizzahouse_woocommerce_product_thumbnails_columns' ) ) {
	
	function pizzahouse_woocommerce_product_thumbnails_columns($cols) {
		return 4;
	}
}

// Add column class into product item in shop streampage
if ( !function_exists( 'pizzahouse_woocommerce_loop_shop_columns_class' ) ) {
	
	
	function pizzahouse_woocommerce_loop_shop_columns_class($class, $class2='', $cat='') {
		global $woocommerce_loop;
        if (!is_product() && !is_cart() && !is_checkout() && !is_account_page()) {
            $cols = function_exists('wc_get_default_products_per_row') ? wc_get_default_products_per_row() : 2;
            $class[] = ' column-1_' . $cols;
        }
        return $class;
	}
}

// Search form
if ( !function_exists( 'pizzahouse_woocommerce_get_product_search_form' ) ) {
	
	function pizzahouse_woocommerce_get_product_search_form($form) {
		return '
		<form role="search" method="get" class="search_form" action="' . esc_url(home_url('/')) . '">
			<input type="text" class="search_field" placeholder="' . esc_attr__('Search for products &hellip;', 'pizzahouse') . '" value="' . get_search_query() . '" name="s" title="' . esc_attr__('Search for products:', 'pizzahouse') . '" /><button class="search_button icon-search" type="submit"></button>
			<input type="hidden" name="post_type" value="product" />
		</form>
		';
	}
}

// Wrap product title into link
if ( !function_exists( 'pizzahouse_woocommerce_the_title' ) ) {
	
	function pizzahouse_woocommerce_the_title($title) {
		if (pizzahouse_storage_get('in_product_item') && get_post_type()=='product') {
			$title = '<a href="'.esc_url(get_permalink()).'">'.($title).'</a>';
		}
		return $title;
	}
}

// Wrap category title into link
if ( !function_exists( 'pizzahouse_woocommerce_shop_loop_subcategory_title' ) ) {
	
	function pizzahouse_woocommerce_shop_loop_subcategory_title($cat) {
		if (pizzahouse_storage_get('in_product_item') && is_object($cat)) {
			$cat->name = sprintf('<a href="%s">%s</a>', esc_url(get_term_link($cat->slug, 'product_cat')), $cat->name);
		}
		?>
		<h2 class="woocommerce-loop-category__title">
			<?php
			echo trim($cat->name);

			if ( $cat->count > 0 ) {
				echo apply_filters( 'woocommerce_subcategory_count_html', ' <mark class="count">(' . esc_html( $cat->count ) . ')</mark>', $cat ); // WPCS: XSS ok.
			}
			?>
		</h2>
		<?php
	}
}

// Replace H2 tag to H3 tag in product headings
if ( !function_exists( 'tennisclub_woocommerce_template_loop_product_title' ) ) {
    
    function tennisclub_woocommerce_template_loop_product_title() {
        echo '<h3>' . wp_kses_post( get_the_title() ) . '</h3>';
    }
}

// Show pagination links
if ( !function_exists( 'pizzahouse_woocommerce_pagination' ) ) {
	
	function pizzahouse_woocommerce_pagination() {
		if ( ! wc_get_loop_prop( 'is_paginated' ) || ! woocommerce_products_will_display() ) {
			return;
		}
		$style = pizzahouse_get_custom_option('blog_pagination');
		pizzahouse_show_pagination(array(
			'class' => 'pagination_wrap pagination_' . esc_attr($style),
			'style' => $style,
			'button_class' => '',
			'first_text'=> '',
			'last_text' => '',
			'prev_text' => '',
			'next_text' => '',
			'pages_in_group' => $style=='pages' ? 10 : 20
			)
		);
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'pizzahouse_woocommerce_required_plugins' ) ) {
	
	function pizzahouse_woocommerce_required_plugins($list=array()) {
		if (in_array('woocommerce', (array)pizzahouse_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> 'WooCommerce',
					'slug' 		=> 'woocommerce',
					'required' 	=> false
				);

		return $list;
	}
}

// Show products navigation
if ( !function_exists( 'pizzahouse_woocommerce_show_post_navi' ) ) {
	
	function pizzahouse_woocommerce_show_post_navi($show=false) {
		return $show || (pizzahouse_get_custom_option('show_page_title')=='yes' && is_single() && pizzahouse_is_woocommerce_page());
	}
}

if ( ! function_exists( 'pizzahouse_woocommerce_price_filter_widget_step' ) ) {
    add_filter('woocommerce_price_filter_widget_step', 'pizzahouse_woocommerce_price_filter_widget_step');
    function pizzahouse_woocommerce_price_filter_widget_step( $step = '' ) {
        $step = 1;
        return $step;
    }
}



// One-click import support
//------------------------------------------------------------------------

// Check WooC in the required plugins
if ( !function_exists( 'pizzahouse_woocommerce_importer_required_plugins' ) ) {
	
	function pizzahouse_woocommerce_importer_required_plugins($not_installed='', $list='') {
		if (pizzahouse_strpos($list, 'woocommerce')!==false && !pizzahouse_exists_woocommerce() )
			$not_installed .= '<br>' . esc_html__('WooCommerce', 'pizzahouse');
		return $not_installed;
	}
}



// Register shortcodes to the internal builder
//------------------------------------------------------------------------
if ( !function_exists( 'pizzahouse_woocommerce_reg_shortcodes' ) ) {
	
	function pizzahouse_woocommerce_reg_shortcodes() {

		// WooCommerce - Cart
		pizzahouse_sc_map("woocommerce_cart", array(
			"title" => esc_html__("Woocommerce: Cart", "pizzahouse"),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show Cart page", "pizzahouse") ),
			"decorate" => false,
			"container" => false,
			"params" => array()
			)
		);
		
		// WooCommerce - Checkout
		pizzahouse_sc_map("woocommerce_checkout", array(
			"title" => esc_html__("Woocommerce: Checkout", "pizzahouse"),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show Checkout page", "pizzahouse") ),
			"decorate" => false,
			"container" => false,
			"params" => array()
			)
		);
		
		// WooCommerce - My Account
		pizzahouse_sc_map("woocommerce_my_account", array(
			"title" => esc_html__("Woocommerce: My Account", "pizzahouse"),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show My Account page", "pizzahouse") ),
			"decorate" => false,
			"container" => false,
			"params" => array()
			)
		);
		
		// WooCommerce - Order Tracking
		pizzahouse_sc_map("woocommerce_order_tracking", array(
			"title" => esc_html__("Woocommerce: Order Tracking", "pizzahouse"),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show Order Tracking page", "pizzahouse") ),
			"decorate" => false,
			"container" => false,
			"params" => array()
			)
		);
		
		// WooCommerce - Shop Messages
		pizzahouse_sc_map("shop_messages", array(
			"title" => esc_html__("Woocommerce: Shop Messages", "pizzahouse"),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show shop messages", "pizzahouse") ),
			"decorate" => false,
			"container" => false,
			"params" => array()
			)
		);
		
		// WooCommerce - Product Page
		pizzahouse_sc_map("product_page", array(
			"title" => esc_html__("Woocommerce: Product Page", "pizzahouse"),
			"desc" => wp_kses_data( __("WooCommerce shortcode: display single product page", "pizzahouse") ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"sku" => array(
					"title" => esc_html__("SKU", "pizzahouse"),
					"desc" => wp_kses_data( __("SKU code of displayed product", "pizzahouse") ),
					"value" => "",
					"type" => "text"
				),
				"id" => array(
					"title" => esc_html__("ID", "pizzahouse"),
					"desc" => wp_kses_data( __("ID of displayed product", "pizzahouse") ),
					"value" => "",
					"type" => "text"
				),
				"posts_per_page" => array(
					"title" => esc_html__("Number", "pizzahouse"),
					"desc" => wp_kses_data( __("How many products showed", "pizzahouse") ),
					"value" => "1",
					"min" => 1,
					"type" => "spinner"
				),
				"post_type" => array(
					"title" => esc_html__("Post type", "pizzahouse"),
					"desc" => wp_kses_data( __("Post type for the WP query (leave 'product')", "pizzahouse") ),
					"value" => "product",
					"type" => "text"
				),
				"post_status" => array(
					"title" => esc_html__("Post status", "pizzahouse"),
					"desc" => wp_kses_data( __("Display posts only with this status", "pizzahouse") ),
					"value" => "publish",
					"type" => "select",
					"options" => array(
						"publish" => esc_html__('Publish', 'pizzahouse'),
						"protected" => esc_html__('Protected', 'pizzahouse'),
						"private" => esc_html__('Private', 'pizzahouse'),
						"pending" => esc_html__('Pending', 'pizzahouse'),
						"draft" => esc_html__('Draft', 'pizzahouse')
						)
					)
				)
			)
		);
		
		// WooCommerce - Product
		pizzahouse_sc_map("product", array(
			"title" => esc_html__("Woocommerce: Product", "pizzahouse"),
			"desc" => wp_kses_data( __("WooCommerce shortcode: display one product", "pizzahouse") ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"sku" => array(
					"title" => esc_html__("SKU", "pizzahouse"),
					"desc" => wp_kses_data( __("SKU code of displayed product", "pizzahouse") ),
					"value" => "",
					"type" => "text"
				),
				"id" => array(
					"title" => esc_html__("ID", "pizzahouse"),
					"desc" => wp_kses_data( __("ID of displayed product", "pizzahouse") ),
					"value" => "",
					"type" => "text"
					)
				)
			)
		);
		
		// WooCommerce - Best Selling Products
		pizzahouse_sc_map("best_selling_products", array(
			"title" => esc_html__("Woocommerce: Best Selling Products", "pizzahouse"),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show best selling products", "pizzahouse") ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", "pizzahouse"),
					"desc" => wp_kses_data( __("How many products showed", "pizzahouse") ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", "pizzahouse"),
					"desc" => wp_kses_data( __("How many columns per row use for products output", "pizzahouse") ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
					)
				)
			)
		);

		// WooCommerce - Recent Products
		pizzahouse_sc_map("recent_products", array(
			"title" => esc_html__("Woocommerce: Recent Products", 'pizzahouse'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show recent products", 'pizzahouse') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", 'pizzahouse'),
					"desc" => wp_kses_data( __("How many products showed", 'pizzahouse') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'pizzahouse'),
					"desc" => wp_kses_data( __("How many columns per row use for products output", 'pizzahouse') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'pizzahouse'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'pizzahouse') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'pizzahouse'),
						"title" => esc_html__('Title', 'pizzahouse')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'pizzahouse'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'pizzahouse') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => pizzahouse_get_sc_param('ordering')
					)
				)
			)
		);

		// WooCommerce - Related Products
		pizzahouse_sc_map("related_products", array(
			"title" => esc_html__("Woocommerce: Related Products", 'pizzahouse'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show related products", 'pizzahouse') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"posts_per_page" => array(
					"title" => esc_html__("Number", 'pizzahouse'),
					"desc" => wp_kses_data( __("How many products showed", 'pizzahouse') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'pizzahouse'),
					"desc" => wp_kses_data( __("How many columns per row use for products output", 'pizzahouse') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'pizzahouse'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'pizzahouse') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'pizzahouse'),
						"title" => esc_html__('Title', 'pizzahouse')
						)
					)
				)
			)
		);

		// WooCommerce - Featured Products
		pizzahouse_sc_map("featured_products", array(
			"title" => esc_html__("Woocommerce: Featured Products", 'pizzahouse'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show featured products", 'pizzahouse') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", 'pizzahouse'),
					"desc" => wp_kses_data( __("How many products showed", 'pizzahouse') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'pizzahouse'),
					"desc" => wp_kses_data( __("How many columns per row use for products output", 'pizzahouse') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'pizzahouse'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'pizzahouse') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'pizzahouse'),
						"title" => esc_html__('Title', 'pizzahouse')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'pizzahouse'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'pizzahouse') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => pizzahouse_get_sc_param('ordering')
					)
				)
			)
		);

		// WooCommerce - Top Rated Products
		pizzahouse_sc_map("featured_products", array(
			"title" => esc_html__("Woocommerce: Top Rated Products", 'pizzahouse'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show top rated products", 'pizzahouse') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", 'pizzahouse'),
					"desc" => wp_kses_data( __("How many products showed", 'pizzahouse') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'pizzahouse'),
					"desc" => wp_kses_data( __("How many columns per row use for products output", 'pizzahouse') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'pizzahouse'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'pizzahouse') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'pizzahouse'),
						"title" => esc_html__('Title', 'pizzahouse')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'pizzahouse'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'pizzahouse') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => pizzahouse_get_sc_param('ordering')
					)
				)
			)
		);

		// WooCommerce - Sale Products
		pizzahouse_sc_map("featured_products", array(
			"title" => esc_html__("Woocommerce: Sale Products", 'pizzahouse'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: list products on sale", 'pizzahouse') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", 'pizzahouse'),
					"desc" => wp_kses_data( __("How many products showed", 'pizzahouse') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'pizzahouse'),
					"desc" => wp_kses_data( __("How many columns per row use for products output", 'pizzahouse') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", "pizzahouse"),
					"desc" => wp_kses_data( __("Sorting order for products output", "pizzahouse") ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'pizzahouse'),
						"title" => esc_html__('Title', 'pizzahouse')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", "pizzahouse"),
					"desc" => wp_kses_data( __("Sorting order for products output", "pizzahouse") ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => pizzahouse_get_sc_param('ordering')
					)
				)
			)
		);
		
		// WooCommerce - Product Category
		pizzahouse_sc_map("product_category", array(
			"title" => esc_html__("Woocommerce: Products from category", "pizzahouse"),
			"desc" => wp_kses_data( __("WooCommerce shortcode: list products in specified category(-ies)", "pizzahouse") ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", "pizzahouse"),
					"desc" => wp_kses_data( __("How many products showed", "pizzahouse") ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", "pizzahouse"),
					"desc" => wp_kses_data( __("How many columns per row use for products output", "pizzahouse") ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", "pizzahouse"),
					"desc" => wp_kses_data( __("Sorting order for products output", "pizzahouse") ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'pizzahouse'),
						"title" => esc_html__('Title', 'pizzahouse')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", "pizzahouse"),
					"desc" => wp_kses_data( __("Sorting order for products output", "pizzahouse") ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => pizzahouse_get_sc_param('ordering')
				),
				"category" => array(
					"title" => esc_html__("Categories", "pizzahouse"),
					"desc" => wp_kses_data( __("Comma separated category slugs", "pizzahouse") ),
					"value" => '',
					"type" => "text"
				),
				"operator" => array(
					"title" => esc_html__("Operator", "pizzahouse"),
					"desc" => wp_kses_data( __("Categories operator", "pizzahouse") ),
					"value" => "IN",
					"type" => "checklist",
					"size" => "medium",
					"options" => array(
						"IN" => esc_html__('IN', 'pizzahouse'),
						"NOT IN" => esc_html__('NOT IN', 'pizzahouse'),
						"AND" => esc_html__('AND', 'pizzahouse')
						)
					)
				)
			)
		);
		
		// WooCommerce - Products
		pizzahouse_sc_map("products", array(
			"title" => esc_html__("Woocommerce: Products", "pizzahouse"),
			"desc" => wp_kses_data( __("WooCommerce shortcode: list all products", "pizzahouse") ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"skus" => array(
					"title" => esc_html__("SKUs", "pizzahouse"),
					"desc" => wp_kses_data( __("Comma separated SKU codes of products", "pizzahouse") ),
					"value" => "",
					"type" => "text"
				),
				"ids" => array(
					"title" => esc_html__("IDs", "pizzahouse"),
					"desc" => wp_kses_data( __("Comma separated ID of products", "pizzahouse") ),
					"value" => "",
					"type" => "text"
				),
				"columns" => array(
					"title" => esc_html__("Columns", "pizzahouse"),
					"desc" => wp_kses_data( __("How many columns per row use for products output", "pizzahouse") ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", "pizzahouse"),
					"desc" => wp_kses_data( __("Sorting order for products output", "pizzahouse") ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'pizzahouse'),
						"title" => esc_html__('Title', 'pizzahouse')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", "pizzahouse"),
					"desc" => wp_kses_data( __("Sorting order for products output", "pizzahouse") ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => pizzahouse_get_sc_param('ordering')
					)
				)
			)
		);
		
		// WooCommerce - Product attribute
		pizzahouse_sc_map("product_attribute", array(
			"title" => esc_html__("Woocommerce: Products by Attribute", "pizzahouse"),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show products with specified attribute", "pizzahouse") ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", "pizzahouse"),
					"desc" => wp_kses_data( __("How many products showed", "pizzahouse") ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", "pizzahouse"),
					"desc" => wp_kses_data( __("How many columns per row use for products output", "pizzahouse") ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", "pizzahouse"),
					"desc" => wp_kses_data( __("Sorting order for products output", "pizzahouse") ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'pizzahouse'),
						"title" => esc_html__('Title', 'pizzahouse')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", "pizzahouse"),
					"desc" => wp_kses_data( __("Sorting order for products output", "pizzahouse") ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => pizzahouse_get_sc_param('ordering')
				),
				"attribute" => array(
					"title" => esc_html__("Attribute", "pizzahouse"),
					"desc" => wp_kses_data( __("Attribute name", "pizzahouse") ),
					"value" => "",
					"type" => "text"
				),
				"filter" => array(
					"title" => esc_html__("Filter", "pizzahouse"),
					"desc" => wp_kses_data( __("Attribute value", "pizzahouse") ),
					"value" => "",
					"type" => "text"
					)
				)
			)
		);
		
		// WooCommerce - Products Categories
		pizzahouse_sc_map("product_categories", array(
			"title" => esc_html__("Woocommerce: Product Categories", 'pizzahouse'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show categories with products", 'pizzahouse') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"number" => array(
					"title" => esc_html__("Number", 'pizzahouse'),
					"desc" => wp_kses_data( __("How many categories showed", 'pizzahouse') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'pizzahouse'),
					"desc" => wp_kses_data( __("How many columns per row use for categories output", 'pizzahouse') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'pizzahouse'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'pizzahouse') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'pizzahouse'),
						"title" => esc_html__('Title', 'pizzahouse')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", "pizzahouse"),
					"desc" => wp_kses_data( __("Sorting order for products output", "pizzahouse") ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => pizzahouse_get_sc_param('ordering')
				),
				"parent" => array(
					"title" => esc_html__("Parent", "pizzahouse"),
					"desc" => wp_kses_data( __("Parent category slug", "pizzahouse") ),
					"value" => "",
					"type" => "text"
				),
				"ids" => array(
					"title" => esc_html__("IDs", "pizzahouse"),
					"desc" => wp_kses_data( __("Comma separated ID of products", "pizzahouse") ),
					"value" => "",
					"type" => "text"
				),
				"hide_empty" => array(
					"title" => esc_html__("Hide empty", "pizzahouse"),
					"desc" => wp_kses_data( __("Hide empty categories", "pizzahouse") ),
					"value" => "yes",
					"type" => "switch",
					"options" => pizzahouse_get_sc_param('yes_no')
					)
				)
			)
		);
	}
}



// Register shortcodes to the VC builder
//------------------------------------------------------------------------
if ( !function_exists( 'pizzahouse_woocommerce_reg_shortcodes_vc' ) ) {
	
	function pizzahouse_woocommerce_reg_shortcodes_vc() {

		if (false && function_exists('pizzahouse_exists_woocommerce') && pizzahouse_exists_woocommerce()) {

			// WooCommerce - Cart
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "woocommerce_cart",
				"name" => esc_html__("Cart", "pizzahouse"),
				"description" => wp_kses_data( __("WooCommerce shortcode: show cart page", "pizzahouse") ),
				"category" => esc_html__('WooCommerce', 'pizzahouse'),
				'icon' => 'icon_trx_wooc_cart',
				"class" => "trx_sc_alone trx_sc_woocommerce_cart",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array(
					array(
						"param_name" => "dummy",
						"heading" => esc_html__("Dummy data", "pizzahouse"),
						"description" => wp_kses_data( __("Dummy data - not used in shortcodes", "pizzahouse") ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );

			class WPBakeryShortCode_Woocommerce_Cart extends Pizzahouse_VC_ShortCodeAlone {}


			// WooCommerce - Checkout
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "woocommerce_checkout",
				"name" => esc_html__("Checkout", "pizzahouse"),
				"description" => wp_kses_data( __("WooCommerce shortcode: show checkout page", "pizzahouse") ),
				"category" => esc_html__('WooCommerce', 'pizzahouse'),
				'icon' => 'icon_trx_wooc_checkout',
				"class" => "trx_sc_alone trx_sc_woocommerce_checkout",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array(
					array(
						"param_name" => "dummy",
						"heading" => esc_html__("Dummy data", "pizzahouse"),
						"description" => wp_kses_data( __("Dummy data - not used in shortcodes", "pizzahouse") ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );

			class WPBakeryShortCode_Woocommerce_Checkout extends Pizzahouse_VC_ShortCodeAlone {}


			// WooCommerce - My Account
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "woocommerce_my_account",
				"name" => esc_html__("My Account", 'pizzahouse'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show my account page", 'pizzahouse') ),
				"category" => esc_html__('WooCommerce', 'pizzahouse'),
				'icon' => 'icon_trx_wooc_my_account',
				"class" => "trx_sc_alone trx_sc_woocommerce_my_account",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array(
					array(
						"param_name" => "dummy",
						"heading" => esc_html__("Dummy data", 'pizzahouse'),
						"description" => wp_kses_data( __("Dummy data - not used in shortcodes", 'pizzahouse') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );

			class WPBakeryShortCode_Woocommerce_My_Account extends Pizzahouse_VC_ShortCodeAlone {}


			// WooCommerce - Order Tracking
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "woocommerce_order_tracking",
				"name" => esc_html__("Order Tracking", 'pizzahouse'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show order tracking page", 'pizzahouse') ),
				"category" => esc_html__('WooCommerce', 'pizzahouse'),
				'icon' => 'icon_trx_wooc_order_tracking',
				"class" => "trx_sc_alone trx_sc_woocommerce_order_tracking",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array(
					array(
						"param_name" => "dummy",
						"heading" => esc_html__("Dummy data", 'pizzahouse'),
						"description" => wp_kses_data( __("Dummy data - not used in shortcodes", 'pizzahouse') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );

			class WPBakeryShortCode_Woocommerce_Order_Tracking extends Pizzahouse_VC_ShortCodeAlone {}


			// WooCommerce - Shop Messages
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "shop_messages",
				"name" => esc_html__("Shop Messages", 'pizzahouse'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show shop messages", 'pizzahouse') ),
				"category" => esc_html__('WooCommerce', 'pizzahouse'),
				'icon' => 'icon_trx_wooc_shop_messages',
				"class" => "trx_sc_alone trx_sc_shop_messages",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array(
					array(
						"param_name" => "dummy",
						"heading" => esc_html__("Dummy data", 'pizzahouse'),
						"description" => wp_kses_data( __("Dummy data - not used in shortcodes", 'pizzahouse') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );

			class WPBakeryShortCode_Shop_Messages extends Pizzahouse_VC_ShortCodeAlone {}


			// WooCommerce - Product Page
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "product_page",
				"name" => esc_html__("Product Page", 'pizzahouse'),
				"description" => wp_kses_data( __("WooCommerce shortcode: display single product page", 'pizzahouse') ),
				"category" => esc_html__('WooCommerce', 'pizzahouse'),
				'icon' => 'icon_trx_product_page',
				"class" => "trx_sc_single trx_sc_product_page",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "sku",
						"heading" => esc_html__("SKU", 'pizzahouse'),
						"description" => wp_kses_data( __("SKU code of displayed product", 'pizzahouse') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "id",
						"heading" => esc_html__("ID", 'pizzahouse'),
						"description" => wp_kses_data( __("ID of displayed product", 'pizzahouse') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "posts_per_page",
						"heading" => esc_html__("Number", 'pizzahouse'),
						"description" => wp_kses_data( __("How many products showed", 'pizzahouse') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "post_type",
						"heading" => esc_html__("Post type", 'pizzahouse'),
						"description" => wp_kses_data( __("Post type for the WP query (leave 'product')", 'pizzahouse') ),
						"class" => "",
						"value" => "product",
						"type" => "textfield"
					),
					array(
						"param_name" => "post_status",
						"heading" => esc_html__("Post status", 'pizzahouse'),
						"description" => wp_kses_data( __("Display posts only with this status", 'pizzahouse') ),
						"class" => "",
						"value" => array(
							esc_html__('Publish', 'pizzahouse') => 'publish',
							esc_html__('Protected', 'pizzahouse') => 'protected',
							esc_html__('Private', 'pizzahouse') => 'private',
							esc_html__('Pending', 'pizzahouse') => 'pending',
							esc_html__('Draft', 'pizzahouse') => 'draft'
						),
						"type" => "dropdown"
					)
				)
			) );

			class WPBakeryShortCode_Product_Page extends Pizzahouse_VC_ShortCodeSingle {}



			// WooCommerce - Product
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "product",
				"name" => esc_html__("Product", 'pizzahouse'),
				"description" => wp_kses_data( __("WooCommerce shortcode: display one product", 'pizzahouse') ),
				"category" => esc_html__('WooCommerce', 'pizzahouse'),
				'icon' => 'icon_trx_product',
				"class" => "trx_sc_single trx_sc_product",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "sku",
						"heading" => esc_html__("SKU", 'pizzahouse'),
						"description" => wp_kses_data( __("Product's SKU code", 'pizzahouse') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "id",
						"heading" => esc_html__("ID", 'pizzahouse'),
						"description" => wp_kses_data( __("Product's ID", 'pizzahouse') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );

			class WPBakeryShortCode_Product extends Pizzahouse_VC_ShortCodeSingle {}


			// WooCommerce - Best Selling Products
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "best_selling_products",
				"name" => esc_html__("Best Selling Products", 'pizzahouse'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show best selling products", 'pizzahouse') ),
				"category" => esc_html__('WooCommerce', 'pizzahouse'),
				'icon' => 'icon_trx_best_selling_products',
				"class" => "trx_sc_single trx_sc_best_selling_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", 'pizzahouse'),
						"description" => wp_kses_data( __("How many products showed", 'pizzahouse') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'pizzahouse'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'pizzahouse') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					)
				)
			) );

			class WPBakeryShortCode_Best_Selling_Products extends Pizzahouse_VC_ShortCodeSingle {}



			// WooCommerce - Recent Products
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "recent_products",
				"name" => esc_html__("Recent Products", 'pizzahouse'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show recent products", 'pizzahouse') ),
				"category" => esc_html__('WooCommerce', 'pizzahouse'),
				'icon' => 'icon_trx_recent_products',
				"class" => "trx_sc_single trx_sc_recent_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", 'pizzahouse'),
						"description" => wp_kses_data( __("How many products showed", 'pizzahouse') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"

					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'pizzahouse'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'pizzahouse') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'pizzahouse'),
						"description" => wp_kses_data( __("Sorting order for products output", 'pizzahouse') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'pizzahouse') => 'date',
							esc_html__('Title', 'pizzahouse') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", "pizzahouse"),
						"description" => wp_kses_data( __("Sorting order for products output", "pizzahouse") ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip((array)pizzahouse_get_sc_param('ordering')),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Recent_Products extends Pizzahouse_VC_ShortCodeSingle {}



			// WooCommerce - Related Products
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "related_products",
				"name" => esc_html__("Related Products", "pizzahouse"),
				"description" => wp_kses_data( __("WooCommerce shortcode: show related products", "pizzahouse") ),
				"category" => esc_html__('WooCommerce', 'pizzahouse'),
				'icon' => 'icon_trx_related_products',
				"class" => "trx_sc_single trx_sc_related_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "posts_per_page",
						"heading" => esc_html__("Number", "pizzahouse"),
						"description" => wp_kses_data( __("How many products showed", "pizzahouse") ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", "pizzahouse"),
						"description" => wp_kses_data( __("How many columns per row use for products output", "pizzahouse") ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", "pizzahouse"),
						"description" => wp_kses_data( __("Sorting order for products output", "pizzahouse") ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'pizzahouse') => 'date',
							esc_html__('Title', 'pizzahouse') => 'title'
						),
						"type" => "dropdown"
					)
				)
			) );

			class WPBakeryShortCode_Related_Products extends Pizzahouse_VC_ShortCodeSingle {}



			// WooCommerce - Featured Products
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "featured_products",
				"name" => esc_html__("Featured Products", 'pizzahouse'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show featured products", 'pizzahouse') ),
				"category" => esc_html__('WooCommerce', 'pizzahouse'),
				'icon' => 'icon_trx_featured_products',
				"class" => "trx_sc_single trx_sc_featured_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", 'pizzahouse'),
						"description" => wp_kses_data( __("How many products showed", 'pizzahouse') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'pizzahouse'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'pizzahouse') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'pizzahouse'),
						"description" => wp_kses_data( __("Sorting order for products output", 'pizzahouse') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'pizzahouse') => 'date',
							esc_html__('Title', 'pizzahouse') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'pizzahouse'),
						"description" => wp_kses_data( __("Sorting order for products output", 'pizzahouse') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(pizzahouse_get_sc_param('ordering')),
						"type" => "dropdown"
					)
				)
			) );

			class WPBakeryShortCode_Featured_Products extends Pizzahouse_VC_ShortCodeSingle {}



			// WooCommerce - Top Rated Products
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "top_rated_products",
				"name" => esc_html__("Top Rated Products", 'pizzahouse'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show top rated products", 'pizzahouse') ),
				"category" => esc_html__('WooCommerce', 'pizzahouse'),
				'icon' => 'icon_trx_top_rated_products',
				"class" => "trx_sc_single trx_sc_top_rated_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", 'pizzahouse'),
						"description" => wp_kses_data( __("How many products showed", 'pizzahouse') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'pizzahouse'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'pizzahouse') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'pizzahouse'),
						"description" => wp_kses_data( __("Sorting order for products output", 'pizzahouse') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'pizzahouse') => 'date',
							esc_html__('Title', 'pizzahouse') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'pizzahouse'),
						"description" => wp_kses_data( __("Sorting order for products output", 'pizzahouse') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip((array)pizzahouse_get_sc_param('ordering')),
						"type" => "dropdown"
					)
				)
			) );

			class WPBakeryShortCode_Top_Rated_Products extends Pizzahouse_VC_ShortCodeSingle {}



			// WooCommerce - Sale Products
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "sale_products",
				"name" => esc_html__("Sale Products", "pizzahouse"),
				"description" => wp_kses_data( __("WooCommerce shortcode: list products on sale", "pizzahouse") ),
				"category" => esc_html__('WooCommerce', 'pizzahouse'),
				'icon' => 'icon_trx_sale_products',
				"class" => "trx_sc_single trx_sc_sale_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", "pizzahouse"),
						"description" => wp_kses_data( __("How many products showed", "pizzahouse") ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", "pizzahouse"),
						"description" => wp_kses_data( __("How many columns per row use for products output", "pizzahouse") ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", "pizzahouse"),
						"description" => wp_kses_data( __("Sorting order for products output", "pizzahouse") ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'pizzahouse') => 'date',
							esc_html__('Title', 'pizzahouse') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", "pizzahouse"),
						"description" => wp_kses_data( __("Sorting order for products output", "pizzahouse") ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip((array)pizzahouse_get_sc_param('ordering')),
						"type" => "dropdown"
					)
				)
			) );

			class WPBakeryShortCode_Sale_Products extends Pizzahouse_VC_ShortCodeSingle {}



			// WooCommerce - Product Category
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "product_category",
				"name" => esc_html__("Products from category", 'pizzahouse'),
				"description" => wp_kses_data( __("WooCommerce shortcode: list products in specified category(-ies)", 'pizzahouse') ),
				"category" => esc_html__('WooCommerce', 'pizzahouse'),
				'icon' => 'icon_trx_product_category',
				"class" => "trx_sc_single trx_sc_product_category",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", 'pizzahouse'),
						"description" => wp_kses_data( __("How many products showed", 'pizzahouse') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'pizzahouse'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'pizzahouse') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'pizzahouse'),
						"description" => wp_kses_data( __("Sorting order for products output", 'pizzahouse') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'pizzahouse') => 'date',
							esc_html__('Title', 'pizzahouse') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'pizzahouse'),
						"description" => wp_kses_data( __("Sorting order for products output", 'pizzahouse') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(pizzahouse_get_sc_param('ordering')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "category",
						"heading" => esc_html__("Categories", 'pizzahouse'),
						"description" => wp_kses_data( __("Comma separated category slugs", 'pizzahouse') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "operator",
						"heading" => esc_html__("Operator", 'pizzahouse'),
						"description" => wp_kses_data( __("Categories operator", 'pizzahouse') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('IN', 'pizzahouse') => 'IN',
							esc_html__('NOT IN', 'pizzahouse') => 'NOT IN',
							esc_html__('AND', 'pizzahouse') => 'AND'
						),
						"type" => "dropdown"
					)
				)
			) );

			class WPBakeryShortCode_Product_Category extends Pizzahouse_VC_ShortCodeSingle {}



			// WooCommerce - Products
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "products",
				"name" => esc_html__("Products", 'pizzahouse'),
				"description" => wp_kses_data( __("WooCommerce shortcode: list all products", 'pizzahouse') ),
				"category" => esc_html__('WooCommerce', 'pizzahouse'),
				'icon' => 'icon_trx_products',
				"class" => "trx_sc_single trx_sc_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "skus",
						"heading" => esc_html__("SKUs", 'pizzahouse'),
						"description" => wp_kses_data( __("Comma separated SKU codes of products", 'pizzahouse') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("IDs", 'pizzahouse'),
						"description" => wp_kses_data( __("Comma separated ID of products", 'pizzahouse') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'pizzahouse'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'pizzahouse') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'pizzahouse'),
						"description" => wp_kses_data( __("Sorting order for products output", 'pizzahouse') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'pizzahouse') => 'date',
							esc_html__('Title', 'pizzahouse') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'pizzahouse'),
						"description" => wp_kses_data( __("Sorting order for products output", 'pizzahouse') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(pizzahouse_get_sc_param('ordering')),
						"type" => "dropdown"
					)
				)
			) );

			class WPBakeryShortCode_Products extends Pizzahouse_VC_ShortCodeSingle {}




			// WooCommerce - Product Attribute
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "product_attribute",
				"name" => esc_html__("Products by Attribute", 'pizzahouse'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show products with specified attribute", 'pizzahouse') ),
				"category" => esc_html__('WooCommerce', 'pizzahouse'),
				'icon' => 'icon_trx_product_attribute',
				"class" => "trx_sc_single trx_sc_product_attribute",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", 'pizzahouse'),
						"description" => wp_kses_data( __("How many products showed", 'pizzahouse') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'pizzahouse'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'pizzahouse') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'pizzahouse'),
						"description" => wp_kses_data( __("Sorting order for products output", 'pizzahouse') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'pizzahouse') => 'date',
							esc_html__('Title', 'pizzahouse') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'pizzahouse'),
						"description" => wp_kses_data( __("Sorting order for products output", 'pizzahouse') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(pizzahouse_get_sc_param('ordering')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "attribute",
						"heading" => esc_html__("Attribute", 'pizzahouse'),
						"description" => wp_kses_data( __("Attribute name", 'pizzahouse') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "filter",
						"heading" => esc_html__("Filter", 'pizzahouse'),
						"description" => wp_kses_data( __("Attribute value", 'pizzahouse') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );

			class WPBakeryShortCode_Product_Attribute extends Pizzahouse_VC_ShortCodeSingle {}



			// WooCommerce - Products Categories
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "product_categories",
				"name" => esc_html__("Product Categories", 'pizzahouse'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show categories with products", 'pizzahouse') ),
				"category" => esc_html__('WooCommerce', 'pizzahouse'),
				'icon' => 'icon_trx_product_categories',
				"class" => "trx_sc_single trx_sc_product_categories",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "number",
						"heading" => esc_html__("Number", 'pizzahouse'),
						"description" => wp_kses_data( __("How many categories showed", 'pizzahouse') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'pizzahouse'),
						"description" => wp_kses_data( __("How many columns per row use for categories output", 'pizzahouse') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", "pizzahouse"),
						"description" => wp_kses_data( __("Sorting order for products output", "pizzahouse") ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'pizzahouse') => 'date',
							esc_html__('Title', 'pizzahouse') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", "pizzahouse"),
						"description" => wp_kses_data( __("Sorting order for products output", "pizzahouse") ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip((array)pizzahouse_get_sc_param('ordering')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "parent",
						"heading" => esc_html__("Parent", "pizzahouse"),
						"description" => wp_kses_data( __("Parent category slug", "pizzahouse") ),
						"admin_label" => true,
						"class" => "",
						"value" => "date",
						"type" => "textfield"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("IDs", "pizzahouse"),
						"description" => wp_kses_data( __("Comma separated ID of products", "pizzahouse") ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "hide_empty",
						"heading" => esc_html__("Hide empty", "pizzahouse"),
						"description" => wp_kses_data( __("Hide empty categories", "pizzahouse") ),
						"class" => "",
						"value" => array("Hide empty" => "1" ),
						"type" => "checkbox"
					)
				)
			) );

			class WPBakeryShortCode_Products_Categories extends Pizzahouse_VC_ShortCodeSingle {}

		}
	}
}
?>