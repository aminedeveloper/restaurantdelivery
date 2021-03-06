<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'pizzahouse_template_header_2_theme_setup' ) ) {
	add_action( 'pizzahouse_action_before_init_theme', 'pizzahouse_template_header_2_theme_setup', 1 );
	function pizzahouse_template_header_2_theme_setup() {
		pizzahouse_add_template(array(
			'layout' => 'header_2',
			'mode'   => 'header',
			'title'  => esc_html__('Header 2', 'pizzahouse'),
			'icon'   => pizzahouse_get_file_url('templates/headers/images/2.jpg')
			));
	}
}

// Template output
if ( !function_exists( 'pizzahouse_template_header_2_output' ) ) {
	function pizzahouse_template_header_2_output($post_options, $post_data) {

		// WP custom header
		$header_css = '';
		if ($post_options['position'] != 'over') {
			$header_image = get_header_image();
			$header_css = $header_image!='' 
				? ' style="background-image: url('.esc_url($header_image).')"' 
				: '';
		}
		?>

		<div class="top_panel_fixed_wrap"></div>

		<header class="top_panel_wrap top_panel_style_2 scheme_<?php echo esc_attr($post_options['scheme']); ?>">
			<div class="top_panel_wrap_inner top_panel_inner_style_2 top_panel_position_<?php echo esc_attr(pizzahouse_get_custom_option('top_panel_position')); ?>">
			
				<?php if (pizzahouse_get_custom_option('show_top_panel_top')=='yes') { ?>
					<div class="top_panel_top">
						<div class="content_wrap clearfix">
							<?php
							pizzahouse_template_set_args('top-panel-top', array(
								'top_panel_top_components' => array('search', 'contact_info', 'open_hours', 'login', 'socials', 'currency', 'language', 'bookmarks')
							));
							get_template_part(pizzahouse_get_file_slug('templates/headers/_parts/top-panel-top.php'));
							?>
						</div>
					</div>
				<?php } ?>

			<div class="top_panel_middle" <?php pizzahouse_show_layout($header_css); ?>>
					<div class="content_wrap">
						<div class="top_wrap">
							<div class="contact_menu left">
								<nav class="menu_main_nav_area menu_hover_<?php echo esc_attr(pizzahouse_get_theme_option('menu_hover')); ?>">
									<?php
									$menu_main = pizzahouse_get_nav_menu('menu_main');
									if (empty($menu_main)) $menu_main = pizzahouse_get_nav_menu();
						pizzahouse_show_layout($menu_main);
									?>
								</nav>
							</div>
							<div class="contact_logo center">
								<?php pizzahouse_show_logo(); ?>
							</div>
							<?php
							// Phone and email
							$contact_phone=trim(pizzahouse_get_custom_option('contact_phone'));
							$contact_link=trim(pizzahouse_get_theme_option('contact_link_title'));
							$contact_link_url=trim(pizzahouse_get_theme_option('contact_link_url'));
							if ((!empty($contact_link) && !empty($contact_link_url)) || !empty($contact_phone) || !empty($contact_email) ||	(function_exists('pizzahouse_exists_woocommerce') && pizzahouse_exists_woocommerce() && (pizzahouse_is_woocommerce_page() && pizzahouse_get_custom_option('show_cart')=='shop' || pizzahouse_get_custom_option('show_cart')=='always') && !(is_checkout() || is_cart() || defined('WOOCOMMERCE_CHECKOUT') || defined('WOOCOMMERCE_CART')))) {
								?>
								<div class="contact_field right">
									<div class="contact_phone"><span class="contact_icon icon-telephone"></span><?php pizzahouse_show_layout('<a href="tel:'.($contact_phone).'">'.($contact_phone).'</a>'); ?></div>
									<?php
									// Woocommerce Cart
									if (function_exists('pizzahouse_exists_woocommerce') && pizzahouse_exists_woocommerce() && (pizzahouse_is_woocommerce_page() && pizzahouse_get_custom_option('show_cart')=='shop' || pizzahouse_get_custom_option('show_cart')=='always') && !(is_checkout() || is_cart() || defined('WOOCOMMERCE_CHECKOUT') || defined('WOOCOMMERCE_CART'))) { ?>
										<div class="contact_cart"><?php get_template_part(pizzahouse_get_file_slug('templates/headers/_parts/contact-info-cart.php')); ?></div><?php
									}
									if(!empty($contact_link) && !empty($contact_link_url)) { ?>
										<div class="contact_link"><a class="sc_button sc_button_style_filled" href="<?php echo esc_url($contact_link_url); ?>"><?php pizzahouse_show_layout($contact_link); ?></a></div>
									<?php
									}
									?>
								</div><?php
							}
							?></div>
					</div>
				</div>
			</div>
		</header>

		<?php
		pizzahouse_storage_set('header_mobile', array(
				 'open_hours' => false,
				 'login' => false,
				 'socials' => false,
				 'bookmarks' => false,
				 'contact_address' => true,
				 'contact_phone_email' => true,
				 'woo_cart' => true,
				 'search' => false,
				 'link' => true
			)
		);
	}
}
?>