<?php


/**
 * Plugin Name: WechatX
 * Plugin URI: https://www.oskoala.com/wechatx
 * Description: 文章加密，关注公众号获取验证码查看
 * Version: 1.0.0
 * Author: 考拉开源
 * Author URI: https://www.oskoala.com/
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wporg
 * Domain Path: /languages
 */

/**
 * 函数命名规则
 * 函数名称： koala_wechat_x_{function_name}
 */
require_once "admin/index.php";
$plugins_url = plugins_url( '', __FILE__ );
date_default_timezone_set( "PRC" );

register_activation_hook( __FILE__, "koala_wechat_x_register_activation_hook" );
register_uninstall_hook( __FILE__, 'koala_wechat_x_register_uninstall_hook' );


add_action( 'admin_print_footer_scripts', 'koala_wechat_x_Add_quick_tags' );

function koala_wechat_x_load_resources() {
//	wp_register_style( 'bootstrap', plugins_url( 'public/css/bootstrap.min.css', __FILE__ ) );
//	wp_enqueue_style( 'bootstrap' );
//
//
//	wp_register_script( 'disable', plugins_url( 'public/js/disable.js', __FILE__ ) );
//	wp_enqueue_script( 'disable' );
}


add_action( 'wp_enqueue_scripts', 'koala_wechat_x_load_resources' );


//文章加密
add_filter( 'the_content', 'koala_wechat_x_article_encryption' );

add_action( 'print_footer_scripts', 'koala_wechat_x_script' );


add_action( 'wp_head', 'koala_wechat_x_add_stylesheet_to_head' );


//function bl_print_tasks() {
//	echo '<pre>';
//	var_dump( _get_cron_array() );
//	echo '</pre>';
//}

//bl_print_tasks();

//print_r( wp_get_schedules() );

//print_r(wp_upload_dir("bing")['url'] . "2021/06/465871.jpg");

