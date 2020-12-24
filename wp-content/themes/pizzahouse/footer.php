<?php
/**
 * The template for displaying the footer.
 */

				pizzahouse_close_wrapper();	// <!-- </.content> -->

				// Show main sidebar
				get_sidebar();

				if (pizzahouse_get_custom_option('body_style')!='fullscreen') pizzahouse_close_wrapper();	// <!-- </.content_wrap> -->
				?>
			
			</div>		<!-- </.page_content_wrap> -->
			
			<?php
			// Footer Testimonials stream
			if (pizzahouse_get_custom_option('show_testimonials_in_footer')=='yes') {
				$count = max(1, pizzahouse_get_custom_option('testimonials_count'));
				$data = pizzahouse_sc_testimonials(array('count'=>$count));
				if ($data) {
					?>
					<footer class="testimonials_wrap sc_section scheme_<?php echo esc_attr(pizzahouse_get_custom_option('testimonials_scheme')); ?>">
						<div class="testimonials_wrap_inner sc_section_inner sc_section_overlay">
							<div class="content_wrap"><?php pizzahouse_show_layout($data); ?></div>
						</div>
					</footer>
					<?php
				}
			}



			// Footer Twitter stream
			if (pizzahouse_get_custom_option('show_twitter_in_footer')=='yes') {
				$count = max(1, pizzahouse_get_custom_option('twitter_count'));
				$data = pizzahouse_sc_twitter(array('count'=>$count));
				if ($data) {
					?>
					<footer class="twitter_wrap sc_section scheme_<?php echo esc_attr(pizzahouse_get_custom_option('twitter_scheme')); ?>">
						<div class="twitter_wrap_inner sc_section_inner sc_section_overlay">
							<div class="content_wrap"><?php pizzahouse_show_layout($data); ?></div>
						</div>
					</footer>
				<?php
				}
			}


			// Google map
			if ( pizzahouse_get_custom_option('show_googlemap')=='yes' ) {
				$map_address = pizzahouse_get_custom_option('googlemap_address');
				$map_latlng  = pizzahouse_get_custom_option('googlemap_latlng');
				$map_zoom    = pizzahouse_get_custom_option('googlemap_zoom');
				$map_style   = pizzahouse_get_custom_option('googlemap_style');
				$map_height  = pizzahouse_get_custom_option('googlemap_height');
				if (!empty($map_address) || !empty($map_latlng)) {
					$args = array();
					if (!empty($map_style))		$args['style'] = esc_attr($map_style);
					if (!empty($map_zoom))		$args['zoom'] = esc_attr($map_zoom);
					if (!empty($map_height))	$args['height'] = esc_attr($map_height);
					pizzahouse_show_layout(pizzahouse_sc_googlemap($args));
				}
			}


			// Footer sidebar
			$footer_show  = pizzahouse_get_custom_option('show_sidebar_footer');
			$sidebar_name = pizzahouse_get_custom_option('sidebar_footer');
			if (!pizzahouse_param_is_off($footer_show) && is_active_sidebar($sidebar_name)) { 
				pizzahouse_storage_set('current_sidebar', 'footer');
				?>
				<footer class="footer_wrap widget_area scheme_<?php echo esc_attr(pizzahouse_get_custom_option('sidebar_footer_scheme')); ?>">
					<div class="footer_wrap_inner widget_area_inner">
						<div class="content_wrap">
							<div class="columns_wrap"><?php
							ob_start();
							do_action( 'before_sidebar' );
                            if ( is_active_sidebar( $sidebar_name ) ) {
                                dynamic_sidebar( $sidebar_name );
                            }
							do_action( 'after_sidebar' );
							$out = ob_get_contents();
							ob_end_clean();
							pizzahouse_show_layout(chop(preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $out)));
							?></div>	<!-- /.columns_wrap -->
						</div>	<!-- /.content_wrap -->
					</div>	<!-- /.footer_wrap_inner -->
				</footer>	<!-- /.footer_wrap -->
				<?php
			}

			// Footer contacts and Copyright area
			$copyright_style = pizzahouse_get_custom_option('show_copyright_in_footer');
			if (pizzahouse_get_custom_option('show_contacts_in_footer')=='yes' || !pizzahouse_param_is_off($copyright_style)) {
				?>
				<footer class="contacts_copyright_wrap<?php echo !pizzahouse_param_is_off($copyright_style) ? ' show_copyright' : ''; ?><?php echo pizzahouse_get_custom_option('show_contacts_in_footer')=='yes' ? ' show_contacts' : ''; ?>">
					<?php
					if (pizzahouse_get_custom_option('show_contacts_in_footer') == 'yes') {
						?>
						<div class="contacts_wrap">
							<div class="contacts_wrap_inner">
								<div class="content_wrap">
									<?php pizzahouse_show_logo(false, false, true, false, false, false); ?>
								</div><!-- /.content_wrap -->
							</div><!-- /.contacts_wrap_inner -->
						</div><!-- /.contacts_wrap -->
						<?php
					}
					if (!pizzahouse_param_is_off($copyright_style)) {
						?>
						<div class="copyright_wrap copyright_style_<?php echo esc_attr($copyright_style); ?>">
							<div class="copyright_wrap_inner">
								<div class="content_wrap">
									<?php
									if ($copyright_style == 'menu') {
										if (($menu = pizzahouse_get_nav_menu('menu_footer'))!='') {
									pizzahouse_show_layout($menu);
										}
									} else if ($copyright_style == 'socials') {
								pizzahouse_show_layout(pizzahouse_sc_socials(array('size'=>"tiny")));
									}
									?>
									<div class="copyright_text"><?php
                                        $pizzahouse_copyright = pizzahouse_get_custom_option('footer_copyright');
                                        $pizzahouse_copyright = str_replace(array('{{Y}}', '{Y}'), date('Y'), $pizzahouse_copyright);
                                        echo wp_kses_post($pizzahouse_copyright);
                                        ?></div>
								</div>
							</div>
						</div>
						<?php
					}
					?>
				</footer><!-- /.contacts_copyright_wrap -->
			<?php
			}
			?>

		</div>	<!-- /.page_wrap -->

	</div>		<!-- /.body_wrap -->
	
	<?php wp_footer(); ?>

</body>
</html>