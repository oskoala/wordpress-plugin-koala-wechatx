<?php
add_action('admin_menu', 'koala_wechat_x_setting');
global $wpdb;

function koala_wechat_x_setting()
{
    global $wechatx_settings_page_hook; //名称可自由定义
    $wechatx_settings_page_hook = add_options_page('文章权限控制插件设置', 'WechatX插件设置', 'manage_options', 'WechatX', 'koala_wechat_x_setting_page');
}


function koala_wechat_x_setting_save()
{

    if (sanitize_text_field($_POST['submit'] ?? "")) {
        $updated = true;
        $msg     = "";
        if (sanitize_text_field($_POST[koala_wechat_x_official_account_name])) {
            update_option(koala_wechat_x_official_account_name, sanitize_text_field($_POST[koala_wechat_x_official_account_name]));
        }
        if (sanitize_text_field($_POST[koala_wechat_x_official_account_keyword])) {
            update_option(koala_wechat_x_official_account_keyword, sanitize_text_field($_POST[koala_wechat_x_official_account_keyword]));
        }
        if (strlen(sanitize_text_field($_POST[koala_wechat_x_official_account_code])) > 6) {
            $updated = false;
            $msg     = "自动回复的验证码不能多于六位哦";
        } else if (sanitize_text_field($_POST[koala_wechat_x_official_account_code])) {
            update_option(koala_wechat_x_official_account_code, sanitize_text_field($_POST[koala_wechat_x_official_account_code]));
        }
        if (sanitize_text_field($_POST[koala_wechat_x_hidden_area_tips])) {
            update_option(koala_wechat_x_hidden_area_tips, sanitize_text_field($_POST[koala_wechat_x_hidden_area_tips]));
        }
        if ($_FILES[koala_wechat_x_official_account_img] && $_FILES[koala_wechat_x_official_account_img]['error'] == 0) {
            $img_name     = $_FILES[koala_wechat_x_official_account_img]['name'];
            $img_tmp_name = $_FILES[koala_wechat_x_official_account_img] ['tmp_name'];
            $save_pah     = koala_wechat_x_save_to_local($img_tmp_name, $img_name);
            update_option(koala_wechat_x_official_account_img, sanitize_text_field($save_pah));
        }

        if (sanitize_text_field($_POST[koala_wechat_x_hidden_area_tips_color])) {
            update_option(koala_wechat_x_hidden_area_tips_color, sanitize_text_field($_POST[koala_wechat_x_hidden_area_tips_color]));
        }

        if (sanitize_text_field($_POST[koala_wechat_x_hidden_area_tips_border_radius])) {
            update_option(koala_wechat_x_hidden_area_tips_border_radius, sanitize_text_field($_POST[koala_wechat_x_hidden_area_tips_border_radius]));
        }

        if ($updated) {
            echo '<div id="message" class="notice notice-success is-dismissible"><p>设置保存成功!</p></div>';//保存完毕显示文字提示
        } else {
            echo '<div id="message" class="notice notice-warning is-dismissible"><p>设置保存失败!' . $msg . '</p></div>';//保存完毕显示文字提示
        }
    } //if

} //function


function koala_wechat_x_setting_page()
{
    ?>

    <?php screen_icon() ?>
    <?php koala_wechat_x_setting_save(); ?>

    <div class="wrap">
        <h1>WechatX插件设置</h1>

        <form method="post" novalidate="novalidate" enctype="multipart/form-data">
            <table class="form-table" role="presentation">
                <tbody>

                <tr>
                    <th scope="row">
                        <label for="<?php echo koala_wechat_x_official_account_keyword ?>">回复以下内容获取验证码 </label>
                    </th>
                    <td>
                        <input name="<?php echo koala_wechat_x_official_account_keyword ?>" type="text"
                               id="<?php echo koala_wechat_x_official_account_keyword ?>"
                               value="<?php echo get_option(koala_wechat_x_official_account_keyword) ?>"
                               class="regular-text">
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="<?php echo koala_wechat_x_official_account_code ?>">自动回复的验证码 </label>
                    </th>
                    <td>
                        <input name="<?php echo koala_wechat_x_official_account_code ?>" type="text"
                               maxlength="6"
                               id="<?php echo koala_wechat_x_official_account_code ?>"
                               value="<?php echo get_option(koala_wechat_x_official_account_code) ?>"
                               class="regular-text">
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="<?php echo koala_wechat_x_hidden_area_tips ?>">隐藏区域提示 </label>
                    </th>
                    <td>
                        <input name="<?php echo koala_wechat_x_hidden_area_tips ?>" type="text"
                               id="<?php echo koala_wechat_x_hidden_area_tips ?>"
                               value="<?php echo get_option(koala_wechat_x_hidden_area_tips) ?>"
                               class="regular-text">
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="<?php echo koala_wechat_x_hidden_area_tips_color ?>">提示区域边框颜色 </label>
                    </th>
                    <td>
                        <input name="<?php echo koala_wechat_x_hidden_area_tips_color ?>" type="text"
                               id="<?php echo koala_wechat_x_hidden_area_tips_color ?>"
                               value="<?php echo get_option(koala_wechat_x_hidden_area_tips_color) ?>"
                               class="regular-text">
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="<?php echo koala_wechat_x_hidden_area_tips_border_radius ?>">提示区域边框弧度 </label>
                    </th>
                    <td>
                        <input name="<?php echo koala_wechat_x_hidden_area_tips_border_radius ?>" type="text"
                               id="<?php echo koala_wechat_x_hidden_area_tips_border_radius ?>"
                               value="<?php echo get_option(koala_wechat_x_hidden_area_tips_border_radius) ?>"
                               class="regular-text">
                    </td>
                </tr>


                <tr>
                    <th scope="row">
                        <label for="<?php echo koala_wechat_x_official_account_img ?>">公众号二维码 </label>
                    </th>
                    <td>
                        <input name="<?php echo koala_wechat_x_official_account_img ?>" type="file"
                               accept="image/*"
                               id="<?php echo koala_wechat_x_official_account_img ?>"
                               aria-describedby="tagline-description"
                               class="regular-text">
                        <p class="description" id="tagline-description">
                            <img width="200"
                                 src="<?php echo wp_upload_dir()['url'] . '/' . get_option(koala_wechat_x_official_account_img) ?>"
                                 alt="">
                        </p></td>
                    </td>
                </tr>

                </tbody>
            </table>

            <p class="submit">
                <input type="submit" name="submit" id="submit" class="button button-primary" value="保存更改">
            </p>
        </form>

    </div>

    <?php
}


