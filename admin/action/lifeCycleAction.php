<?php

/**
 * 启动插件
 */
function koala_wechat_x_register_activation_hook() {
	add_option( koala_wechat_x_official_account_name, '' );
	add_option( koala_wechat_x_official_account_keyword, '' );
	add_option( koala_wechat_x_official_account_code, '' );
	add_option( koala_wechat_x_official_account_img, '' );
	add_option( koala_wechat_x_hidden_area_tips, '内容已隐藏，请关注公众号输入验证码后查看隐藏内容' );
	add_option( koala_wechat_x_hidden_area_tips_color, '#999999' );
	add_option( koala_wechat_x_hidden_area_tips_border_radius, '9px' );

}


/**
 * 卸载插件
 */
function koala_wechat_x_register_uninstall_hook() {
	delete_option( koala_wechat_x_official_account_name );
	delete_option( koala_wechat_x_official_account_keyword );
	delete_option( koala_wechat_x_official_account_code );
	delete_option( koala_wechat_x_official_account_img );
	delete_option( koala_wechat_x_hidden_area_tips );
	delete_option( koala_wechat_x_hidden_area_tips_color );
	delete_option( koala_wechat_x_hidden_area_tips_border_radius );
}