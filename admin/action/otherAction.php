<?php
function koala_wechat_x_Add_quick_tags()
{
    ?>
    <script type="text/javascript">
        if (typeof QTags != "undefined") {
            QTags.addButton('插入隐藏标签', '插入隐藏标签', '<!--wp:wechatx-hidden word="<?php echo get_option(koala_wechat_x_hidden_area_tips)?>" start-->', '<!--wp:wechatx-hidden end-->');
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
function koala_wechat_x_article_encryption($content)
{
    global $plugins_url;
    $url         = get_permalink();
    $id          = url_to_postid($url);
    $cookie_name = "WP-WechatX" . $id;

    $qrcode = wp_upload_dir()['url'] . '/' . get_option(koala_wechat_x_official_account_img);

    $koala_wechat_x_official_account_name          = get_option(koala_wechat_x_official_account_name);
    $koala_wechat_x_official_account_keyword       = get_option(koala_wechat_x_official_account_keyword);
    $koala_wechat_x_hidden_area_tips_color         = get_option(koala_wechat_x_hidden_area_tips_color);
    $koala_wechat_x_hidden_area_tips_border_radius = get_option(koala_wechat_x_hidden_area_tips_border_radius);
    $koala_wechat_x_official_account_code_length   = strlen(get_option(koala_wechat_x_official_account_code));

    if (preg_match_all('/<!--wp:wechatx-hidden word="(.*)" start-->([\s\S]*?)<!--wp:wechatx-hidden end-->/i', $content, $hide_words)) {
        //print_r($hide_words);
        $cv          = md5($cookie_name);
        $cookievalue = isset($_COOKIE[$cookie_name]) ? $_COOKIE[$cookie_name] : '';
        if ($cookievalue == $cv) {
            //print_r($hide_words);
            $hide_words[0] = array_unique($hide_words[0]);
            for ($i = 0; $i < count($hide_words); $i++) {
                if (isset($hide_words[0]) && isset($hide_words[0][$i])) {
                    $content = str_replace($hide_words[0][$i], '<div class = "koala-wechat-x-hidden-area" style="border-radius:' . $koala_wechat_x_hidden_area_tips_border_radius . ';border: 1px dashed ' . $koala_wechat_x_hidden_area_tips_color . '">' . $hide_words[0][$i] . '</div>', $content);
                }
            }
        } else {
            $hide_words[0] = array_unique($hide_words[0]);
            $hide_notice   = <<< HTML
<div class="koala-wechat-x-modal-overlay" data-target="koala-wechat-x-hidden-modal"
     id="koala-wechat-x-hidden-modal-overlay"
     style="display: none"
>
</div>
<div class="koala-wechat-x-modal" id="koala-wechat-x-hidden-modal" style="display: none">
    <span class="koala-wechat-x-modal-title">关注公众号获取隐藏内容</span>
    <div class="koala-wechat-x-modal-content">
        <div class="koala-wechat-x-qrcode" style="background-image: url('$qrcode')"></div>
        <span class="koala-wechat-x-tips">
            使用微信扫描上方二维码关注公众号后，回复“<span>$koala_wechat_x_official_account_keyword</span>”
        </span>
        <div class="koala-wechat-x-code-input">
            <div class="koala-wechat-x-code-input-tips">
                <span>输入验证码：</span>
            </div>
            <div class="koala-wechat-x-code-input-content" unselectable="on" onselectstart="return false;"
                 style="-moz-user-select:none;">
                <input type="text" class="koala-wechat-x-code-input-input" maxlength="1" index="1" post-id="$id">
                <input type="text" class="koala-wechat-x-code-input-input" maxlength="1" index="2" post-id="$id">
                <input type="text" class="koala-wechat-x-code-input-input" maxlength="1" index="3" post-id="$id">
                <input type="text" class="koala-wechat-x-code-input-input" maxlength="1" index="4" post-id="$id">
                <input type="text" class="koala-wechat-x-code-input-input" maxlength="1" index="5" post-id="$id">
                <input type="text" class="koala-wechat-x-code-input-input" maxlength="1" index="6" post-id="$id">
            </div>
        </div>

        <div class="koala-wechat-x-status koala-wechat-x-status-right">
            <div style="width: 22px;height: 22px;background-image: url('$plugins_url/public/img/right.png');background-size: 100% 100%"></div>
            <span>验证通过</span>
        </div>

        <div class="koala-wechat-x-status koala-wechat-x-status-wrong">
            <div style="width: 22px;height: 22px;background-image: url('$plugins_url/public/img/wrong.png');background-size: 100% 100%"></div>
            <span>验证码错误，请重新输入</span>
        </div>

    </div>
</div>
HTML;
            for ($i = 0; $i < count($hide_words[0]); $i++) {
                $content = str_replace($hide_words[0][$i], '<div class="koala-wechat-x-hidden-box" style="border-radius:' . $koala_wechat_x_hidden_area_tips_border_radius . ';border: 1px dashed ' . $koala_wechat_x_hidden_area_tips_color . '">
                    <span>
                            ' . $hide_words[1][$i] . '
                    </span>
                    <div class="koala-wechat-x-button-box">
                        <input type="button" class="koala-wechat-x-modal-active-btn" value="获取隐藏内容"
                               data-target="koala-wechat-x-hidden-modal">
                    </div>
                </div>', $content);
            }
            $content .= $hide_notice; //str_replace($hide_words[0], $hide_notice, $content);
        }
    }

    return $content;
}

function koala_wechat_x_add_stylesheet_to_head()
{
    global $plugins_url;
    echo "<link href='$plugins_url/public/css/style.css' rel='stylesheet' type='text/css'>";
}
