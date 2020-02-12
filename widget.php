<div class="textwidget">
    <?php
    if (is_user_logged_in()) {
        global $wp;
        $current_user = wp_get_current_user();
        $user_ID = $current_user->ID;
        $posts_count = count_user_posts($user_ID);
        $current_url = add_query_arg($wp->query_string, '', home_url($wp->request));
        ?>
        <div class="Gologin_avatar">
            <div class="Gologin_avatar_l">
                <?php echo get_avatar($user_ID,'48');
                ?>
            </div>
            <div class="Gologin_avatar_r">
                <p>
                    <a title="<?php echo $current_user->display_name;
                        ?>" href="<?php echo get_author_posts_url($user_ID);
                        ?>"><?php echo $current_user->display_name;
                        ?></a>
                    <a href="<?php echo wp_logout_url($current_url);
                        ?>">[安全退出]</a>
                    <a href="<?php echo get_edit_user_link();
                        ?>">[修改资料]</a>
                </p>
                <p>
                    我的文章总数：<a title="<?php echo $current_user->display_name;
                        ?>发布的文章" href="<?php echo get_author_posts_url($user_ID);
                        ?>"><?php echo $posts_count ?></a>
                </p>
            </div>
        </div>
        <?php
    } else {
        ?>
        <div class="Go_login_widget">
            <?php
            if (get_option('Go_login_options_qq_appid') && get_option('Go_login_options_qq_appkey'))
                echo "<a class=\"qqa\" href=\"javascript:Go_login('qq');\" title=\"QQ登录\"></a>";
            if (get_option('Go_login_options_sina_appkey') && get_option('Go_login_options_sina_appkey'))
                echo "<a class=\"sina\" href=\"javascript:Go_login('sina');\" title=\"新浪微博登录\"></a>";
            if (get_option('Go_login_options_bd_apikey') && get_option('Go_login_options_bd_secretkey'))
                echo "<a class=\"bd\" href=\"javascript:Go_login('bd');\" title=\"百度登录\"></a>";
            if (get_option('Go_login_options_gh_ClientID') && get_option('Go_login_options_gh_ClientSecret'))
                echo "<a class=\"qqck github\" href=\"" . get_Go_login_url('gh', false) . "\" title=\"Github登录\"></a>";
            if(get_option('Go_login_options_wechat_switch') == 'on')
                echo "<a class=\"wechat\" href=\"javascript:Go_login('wx');\" title=\"微信登录\"></a>";
            ?>
        </div>
        <?php
    }
    ?>
</div>