<?php
function koala_wechat_x_Add_quick_tags() {
	?>
    <script type="text/javascript">
        if (typeof QTags != "undefined") {
            QTags.addButton('插入隐藏标签', '插入隐藏标签', '<!--wp:wechatx-hidden word="<?php echo get_option( koala_wechat_x_hidden_area_tips )?>" start-->', '<!--wp:wechatx-hidden end-->');
        }
    </script>
	<?php
}

/**
 * @param $content
 *
 * @return string|string[]
 * 文章加密
 */
function koala_wechat_x_article_encryption( $content ) {
	$url         = get_permalink();
	$id          = url_to_postid( $url );
	$cookie_name = "WP-WechatX" . $id;
	if ( preg_match_all( '/<!--wp:wechatx-hidden word="(.*)" start-->([\s\S]*?)<!--wp:wechatx-hidden end-->/i', $content, $hide_words ) ) {
		//print_r($hide_words);
		$cv          = md5( $cookie_name );
		$cookievalue = isset( $_COOKIE[ $cookie_name ] ) ? $_COOKIE[ $cookie_name ] : '';
		if ( $cookievalue == $cv ) {
			//print_r($hide_words);
			$hide_words[0] = array_unique( $hide_words[0] );
			for ( $i = 0; $i < count( $hide_words ); $i ++ ) {
				if(isset($hide_words[0]) && isset($hide_words[0][$i])){
    					$content = str_replace( $hide_words[0][ $i ], '<div class = "koala_wechat_x_hiddenTips">' . $hide_words[0][ $i ] . '</div>', $content );   
			    	}
			}
		} else {
			$qrcode = wp_upload_dir()['url'] . '/' . get_option( koala_wechat_x_official_account_img );

			$hide_words[0] = array_unique( $hide_words[0] );
			$koala_wechat_x_official_account_name    = get_option( koala_wechat_x_official_account_name );
			$koala_wechat_x_official_account_keyword = get_option( koala_wechat_x_official_account_keyword );
			$koala_wechat_x_hidden_area_tips_color = get_option( koala_wechat_x_hidden_area_tips_color );
			$koala_wechat_x_hidden_area_tips_border_radius = get_option( koala_wechat_x_hidden_area_tips_border_radius );
			$hide_notice                             = <<< HTML
<style>
    .koala_wechat_x_huoduan_hide_box{
        border-color: $koala_wechat_x_hidden_area_tips_color !important;
        border-radius: $koala_wechat_x_hidden_area_tips_border_radius !important;
    }
    .koala_wechat_x_hiddenTips{
        border-color: $koala_wechat_x_hidden_area_tips_color !important;
        border-radius: $koala_wechat_x_hidden_area_tips_border_radius !important;
    }
</style>
<div class="koala_wechat_x_huoduan_hide_box">
    <span style="font-size:18px;">文中部分内容已被作者隐藏，关注“
    $koala_wechat_x_official_account_name ”公众号获取验证码后可浏览隐藏内容</span>
     <div style="display:flex; flex-direction:row;">
        <div style="flex:1">
             <div class="koala_wechat_x_huoduan_hide_tip">
                验证码获取方式：关注“<span>$koala_wechat_x_official_account_name</span>”公众号后，回复“<span>$koala_wechat_x_official_account_keyword</span>”。
            </div>
        </div>
        <div class="koala_wechat_x_wxpic" style="background-image:url('$qrcode');background-size:100% 100%;">
        </div>
    </div>
    <form method="post" style="margin:10px 0;">
        <span class="koala_wechat_x_yzts" style="font-size:18px;float:left;">验证码：</span>
        <input class="koala_wechat_x_verifycode" name="verifycode" id="koala_wechat_x_verifycode" type="text"
                value="">
        <input id="koala_wechat_x_verifybtn" class="koala_wechat_x_verifybtn" postid="$id" name="" type="button" value="提交">
    </form>
</div>
HTML;
			for ( $i = 0; $i < count( $hide_words[0] ); $i ++ ) {
				$content = str_replace( $hide_words[0][ $i ], '<div class="koala_wechat_x_hiddenTips">' . $hide_words[1][ $i ] . '</div>', $content );
			}
			$content .= $hide_notice; //str_replace($hide_words[0], $hide_notice, $content);
		}
	}

	return $content;
}

function koala_wechat_x_add_stylesheet_to_head() {
	global $plugins_url;
	echo "<link href='$plugins_url/public/css/style.css' rel='stylesheet' type='text/css'>";
}


function koala_wechat_x_script() {
	?>
    <script>
        // jQuery.noConflict();    // 由于wordpress 添加了这一行，所以， $ 操作，会报错。
        (function ($) {
            function readyFn() {
                $("#koala_wechat_x_verifybtn").click(function () {
                    let id = $(this).attr("postid");
                    let verifycode = $("#koala_wechat_x_verifycode").val();
                    if (verifycode === "" || !verifycode) {
                        alert("请输入验证码");
                        return;
                    }
                    $.post("/wp-admin/admin-ajax.php?action=post", {
                        'id': id,
                        "verifycode": verifycode
                    }, function (data) {
                        if (data.success) {
                            $("#post-" + id + " .entry-content").html(data.data.content);
                        } else {
                            alert(data.data.msg);
                        }
                    });
                })
            }

            $(document).ready(readyFn);
        })(jQuery);
    </script>

	<?php
}
