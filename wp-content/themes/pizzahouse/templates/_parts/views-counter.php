<?php 
if (is_singular() && pizzahouse_get_theme_option('use_ajax_views_counter')=='no') {
    do_action('trx_utils_filter_set_post_views', get_the_ID());
}
?>