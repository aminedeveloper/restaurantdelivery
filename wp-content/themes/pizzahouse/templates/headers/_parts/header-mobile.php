<?php
$header_options = pizzahouse_storage_get('header_mobile');
$contact_phone = trim(pizzahouse_get_custom_option('contact_phone'));
$contact_email = trim(pizzahouse_get_custom_option('contact_email'));
?>
	<div class="header_mobile">
		<div class="content_wrap">
			<div class="menu_button icon-menu"></div>
			<?php 
			pizzahouse_show_logo();
			if ($header_options['woo_cart']){
				if (function_exists('pizzahouse_exists_woocommerce') && pizzahouse_exists_woocommerce() && (pizzahouse_is_woocommerce_page() && pizzahouse_get_custom_option('show_cart')=='shop' || pizzahouse_get_custom_option('show_cart')=='always') && !(is_checkout() || is_cart() || defined('WOOCOMMERCE_CHECKOUT') || defined('WOOCOMMERCE_CART'))) {
					?>
					<div class="menu_main_cart top_panel_icon">
						<?php get_template_part(pizzahouse_get_file_slug('templates/headers/_parts/contact-info-cart.php')); ?>
					</div>
					<?php
				}
			}
			?>
		</div>
		<div class="side_wrap">
			<div class="close"><?php esc_html_e('Close', 'pizzahouse'); ?></div>
			<div class="panel_top">
				<nav class="menu_main_nav_area">
					<?php
						$menu_main = pizzahouse_get_nav_menu('menu_main');
						if (empty($menu_main)) $menu_main = pizzahouse_get_nav_menu();
						$menu_main = pizzahouse_set_tag_attrib($menu_main, '<ul>', 'id', 'menu_mobile');
						pizzahouse_show_layout($menu_main);
					?>
				</nav>
				<?php 
				if ($header_options['search'] && pizzahouse_get_custom_option('show_search')=='yes')
					pizzahouse_show_layout(pizzahouse_sc_search(array()));
				
				if ($header_options['login']) {
					if ( is_user_logged_in() ) { 
						?>
						<div class="login"><a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>" class="popup_link"><?php esc_html_e('Logout', 'pizzahouse'); ?></a></div>
						<?php
					} else {
						// Load core messages
						pizzahouse_enqueue_messages();
						// Load Popup engine
						pizzahouse_enqueue_popup();
						?>
						<div class="login"><a href="#popup_login" class="popup_link popup_login_link icon-user"><?php esc_html_e('Login', 'pizzahouse'); ?></a><?php
							if (pizzahouse_get_theme_option('show_login')=='yes') {
								get_template_part(pizzahouse_get_file_slug('templates/headers/_parts/login.php'));
							}?>
						</div>
						<?php
						// Anyone can register ?
						if ( (int) get_option('users_can_register') > 0) {
							?>
							<div class="login"><a href="#popup_registration" class="popup_link popup_register_link icon-pencil"><?php esc_html_e('Register', 'pizzahouse'); ?></a><?php
								if (pizzahouse_get_theme_option('show_login')=='yes') {
									get_template_part(pizzahouse_get_file_slug('templates/headers/_parts/register.php'));
								}?>
							</div>
							<?php 
						}
					}
				}
				?>
			</div>
			
			<?php if ($header_options['contact_address'] || $header_options['contact_phone_email'] || $header_options['open_hours']) { ?>
			<div class="panel_middle">
				<?php

				if ($header_options['contact_phone_email'] && (!empty($contact_phone) || !empty($contact_email))) {
					?><div class="contact_field contact_phone">
						<span class="contact_icon icon-phone"></span>
						<span class="contact_label contact_phone"><?php pizzahouse_show_layout($contact_phone); ?></span>
						<span class="contact_email"><?php pizzahouse_show_layout($contact_email); ?></span>
					</div><?php
				}

				?>
			</div>
			<?php } ?>


			<?php
			$contact_link=trim(pizzahouse_get_theme_option('contact_link_title'));
			$contact_link_url=trim(pizzahouse_get_theme_option('contact_link_url'));
			if ($header_options['link'] && !empty($contact_link) && !empty($contact_link_url)) { ?>
				<div class="contact_link"><a class="sc_button sc_button_style_filled" href="<?php echo esc_url($contact_link_url); ?>"><?php pizzahouse_show_layout($contact_link); ?></a></div>
				<?php
			}
			?>


			<div class="panel_bottom">
				<?php if ($header_options['socials'] && pizzahouse_get_custom_option('show_socials')=='yes') { ?>
					<div class="contact_socials">
						<?php pizzahouse_show_layout(pizzahouse_sc_socials(array('size'=>'small'))); ?>
					</div>
				<?php } ?>
			</div>
		</div>
		<div class="mask"></div>
	</div>