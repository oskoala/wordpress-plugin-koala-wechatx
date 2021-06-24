<?php


//请求地址 host/wp-admin/admin-ajax.php?action=post
add_action('wp_ajax_nopriv_post', 'koala_wechat_x_get_post_by_id');
add_action('wp_ajax_post', 'koala_wechat_x_get_post_by_id');