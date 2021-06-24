<?php
/**
 * 验证码校验
 */
function koala_wechat_x_get_post_by_id() {
	$verifycode = sanitize_text_field( $_POST['verifycode'] );
	$ok         = true;

	if ( ! isset( $_POST['verifycode'] ) || $verifycode != get_option( koala_wechat_x_official_account_code ) ) {
		$ok = false;
	}
	if ( ! $ok ) {
		$data = array(
			'msg' => "验证码无效或已过期！"
		);
		wp_send_json_error( $data, 200 );
	}
	$id                 = sanitize_text_field( $_POST['id'] );
	$post               = get_post( $id );
	$content            = $post->post_content;
	preg_match_all('/<!--wp:wechatx-hidden word="(.*)" start-->([\s\S]*?)<!--wp:wechatx-hidden end-->/i', $content, $hide_words);
	$hide_words[0] = array_unique($hide_words[0]);
	for($i = 0;$i<count($hide_words);$i++){
		$content = str_replace($hide_words[0][$i], '<div  class="koala_wechat_x_hiddenTips">'.$hide_words[0][$i].'</div>', $content);
	}
	$post->post_content = $content;

	$cookie_name = "WP-WechatX" . $id;
	$cv          = md5( $cookie_name );
	if ( isset( $_POST['verifycode'] ) ) {
		setcookie( $cookie_name, $cv, time() + 3 * 86400, "/" );
		$_COOKIE[ $cookie_name ] = $cv;
	}

	$data = array(
		'id'      => $id,
		'content' => $post->post_content,
		'msg'     => "验证成功！",
	);
	wp_send_json_success( $data, 200 );
}