<?php
/**
 * pizzahouse Framework: Admin functions
 *
 * @package	pizzahouse
 * @since	pizzahouse 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* Admin actions and filters:
------------------------------------------------------------------------ */

if (is_admin()) {

	/* Theme setup section
	-------------------------------------------------------------------- */
	
	if ( !function_exists( 'pizzahouse_admin_theme_setup' ) ) {
		add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_admin_theme_setup', 11 );
		function pizzahouse_admin_theme_setup() {
			if ( is_admin() ) {
				add_filter("pizzahouse_filter_localize_script_admin", 'pizzahouse_admin_localize_script');
				add_action("admin_enqueue_scripts",	'pizzahouse_admin_load_scripts');
				add_action("admin_footer",			'pizzahouse_admin_add_js_vars', 2);
				add_action('tgmpa_register',		'pizzahouse_admin_register_plugins');

				// AJAX: Get terms for specified post type
				add_action('wp_ajax_pizzahouse_admin_change_post_type', 		'pizzahouse_callback_admin_change_post_type');
				add_action('wp_ajax_nopriv_pizzahouse_admin_change_post_type','pizzahouse_callback_admin_change_post_type');
			}
		}
	}
	
	// Load required styles and scripts for admin mode
	if ( !function_exists( 'pizzahouse_admin_load_scripts' ) ) {
		
		function pizzahouse_admin_load_scripts() {
			wp_enqueue_style( 'pizzahouse-admin-style', pizzahouse_get_file_url('css/core.admin.css'), array(), null );
			if (pizzahouse_check_admin_page('widgets.php')) {
                wp_enqueue_style( 'fontello-style', pizzahouse_get_file_url('css/fontello-admin/css/fontello-admin.css'), array(), null );
                wp_enqueue_style( 'animations-style', pizzahouse_get_file_url('css/fontello-admin/css/animation.css'), array(), null );
			}

            if (pizzahouse_get_theme_option('debug_mode')=='yes') {
                wp_enqueue_script( 'pizzahouse-debug-script', pizzahouse_get_file_url('js/core.debug.js'), array('jquery'), null, true );
            }
            wp_enqueue_script( 'pizzahouse-admin-script', pizzahouse_get_file_url('js/core.admin.js'), array('jquery'), null, true );
		}
	}
	
	// Prepare required styles and scripts for admin mode
	if ( !function_exists( 'pizzahouse_admin_localize_script' ) ) {
		
		function pizzahouse_admin_localize_script($vars) {
			$vars['admin_mode'] = true;
			$vars['user_logged_in'] = true;
			$vars['ajax_nonce'] = wp_create_nonce(admin_url('admin-ajax.php'));
			$vars['ajax_url'] = esc_url(admin_url('admin-ajax.php'));
			$vars['ajax_error'] = esc_html__('Invalid server answer', 'pizzahouse');
			$vars['importer_error_msg'] = esc_html__('Errors that occurred during the import process:', 'pizzahouse');
			return $vars;
		}
	}

	//  Localize scripts in the footer hook
	if ( !function_exists( 'pizzahouse_admin_add_js_vars' ) ) {
		
		function pizzahouse_admin_add_js_vars() {
			$vars = apply_filters( 'pizzahouse_filter_localize_script_admin', pizzahouse_storage_empty('js_vars') ? array() : pizzahouse_storage_get('js_vars'));
			if (!empty($vars)) wp_localize_script( 'pizzahouse-admin-script', 'PIZZAHOUSE_STORAGE', $vars);
			if (!pizzahouse_storage_empty('js_code')) {
				$holder = 'script';
				?><<?php pizzahouse_show_layout($holder); ?>>
					jQuery(document).ready(function() {
						<?php pizzahouse_show_layout(pizzahouse_minify_js(pizzahouse_storage_get('js_code'))); ?>
					}
				</<?php pizzahouse_show_layout($holder); ?>><?php
			}
		}
	}
	
	// AJAX: Get terms for specified post type
	if ( !function_exists( 'pizzahouse_callback_admin_change_post_type' ) ) {
		
		
		function pizzahouse_callback_admin_change_post_type() {
			if ( !wp_verify_nonce( pizzahouse_get_value_gp('nonce'), admin_url('admin-ajax.php') ) )
				wp_die();
			$post_type = pizzahouse_get_value_gpc('post_type');
			$terms = pizzahouse_get_list_terms(false, pizzahouse_get_taxonomy_categories_by_post_type($post_type));
			$terms = pizzahouse_array_merge(array(0 => esc_html__('- Select category -', 'pizzahouse')), $terms);
			$response = array(
				'error' => '',
				'data' => array(
					'ids' => array_keys($terms),
					'titles' => array_values($terms)
				)
			);
			echo json_encode($response);
			wp_die();
		}
	}

	// Return current post type in dashboard
	if ( !function_exists( 'pizzahouse_admin_get_current_post_type' ) ) {
		function pizzahouse_admin_get_current_post_type() {
			global $post, $typenow, $current_screen;
			if ( $post && $post->post_type )							//we have a post so we can just get the post type from that
				return $post->post_type;
			else if ( $typenow )										//check the global $typenow — set in admin.php
				return $typenow;
			else if ( $current_screen && $current_screen->post_type )	//check the global $current_screen object — set in sceen.php
				return $current_screen->post_type;
			else if ( isset( $_REQUEST['post_type'] ) )					//check the post_type querystring
				return sanitize_key( $_REQUEST['post_type'] );
			else if ( isset( $_REQUEST['post'] ) ) {					//lastly check the post id querystring
				$post = get_post( sanitize_key( $_REQUEST['post'] ) );
				return !empty($post->post_type) ? $post->post_type : '';
			} else														//we do not know the post type!
				return '';
		}
	}

	// Add admin menu pages
	if ( !function_exists( 'pizzahouse_admin_add_menu_item' ) ) {
		function pizzahouse_admin_add_menu_item($mode, $item, $pos='100') {
			static $shift = 0;
			if ($pos=='100') $pos .= '.'.$shift++;
			$fn = join('_', array('add', $mode, 'page'));
			if (empty($item['parent']))
				$fn($item['page_title'], $item['menu_title'], $item['capability'], $item['menu_slug'], $item['callback']);
			else
				$fn($item['parent'], $item['page_title'], $item['menu_title'], $item['capability'], $item['menu_slug'], $item['callback']);
		}
	}
	
	// Register optional plugins
	if ( !function_exists( 'pizzahouse_admin_register_plugins' ) ) {
		function pizzahouse_admin_register_plugins() {

			$plugins = apply_filters('pizzahouse_filter_required_plugins', array());
			$config = array(
				'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
				'default_path' => '',                      // Default absolute path to bundled plugins.
				'menu'         => 'tgmpa-install-plugins', // Menu slug.
				'parent_slug'  => 'themes.php',            // Parent menu slug.
				'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
				'has_notices'  => true,                    // Show admin notices or not.
				'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
				'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
				'is_automatic' => true,                    // Automatically activate plugins after installation or not.
				'message'      => ''                       // Message to output right before the plugins table.
			);
	
			tgmpa( $plugins, $config );
		}
	}

	require_once PIZZAHOUSE_FW_PATH . 'lib/tgm/class-tgm-plugin-activation.php';
}

?>