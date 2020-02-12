<?php

// If uninstall not called from WordPress, then exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit();
}

/**
 * Uninstall operations
 */
function single_uninstall() {
    // delete column
    // $GLOBALS['wpdb']->query("DROP TABLE IF EXISTS {$GLOBALS['wpdb']->prefix}table_name");
    global $wpdb;
    // 删除qq_openid字段(QQ)
    $var = $wpdb->query("SELECT `qq_openid` FROM $wpdb->users");
    if ($var) {
        $wpdb->query("ALTER TABLE $wpdb->users DROP qq_openid");
    }
    // 删除sina_uid字段(新浪)
    $var1 = $wpdb->query("SELECT `sina_uid` FROM $wpdb->users");
    if ($var1) {
        $wpdb->query("ALTER TABLE $wpdb->users DROP sina_uid");
    }
    // 删除bduid字段(百度)
    $var2 = $wpdb->query("SELECT `bduid` FROM $wpdb->users");
    if ($var2) {
        $wpdb->query("ALTER TABLE $wpdb->users DROP bduid");
    }
    // 删除ghid字段(Github)
    $var3 = $wpdb->query("SELECT ghid FROM $wpdb->users");
    if ($var3) {
        $wpdb->query("ALTER TABLE $wpdb->users DROP ghid");
    }
    // 删除wx_openid字段(微信)
    $var4 = $wpdb->query("SELECT wx_openid FROM $wpdb->users");
    if ($var4) {
        $wpdb->query("ALTER TABLE $wpdb->users DROP wx_openid");
    }
    
    // delete options
    delete_option('Go_login_options_qq_appid');
    delete_option('Go_login_options_qq_appkey');
    delete_option('Go_login_options_sina_appkey');
    delete_option('Go_login_options_sina_appsecret');
    delete_option('Go_login_options_bd_apikey');
    delete_option('Go_login_options_bd_secretkey');
    delete_option('Go_login_options_gh_ClientID');
    delete_option('Go_login_options_gh_ClientSecret');
    delete_option('Go_login_options_wechat_switch');
}

// Let's do it!
if (is_multisite()) {
    single_uninstall();

    // delete data foreach blog
    $blogs_list = $GLOBALS['wpdb']->get_results("SELECT blog_id FROM {$GLOBALS['wpdb']->blogs}", ARRAY_A);
    if (!empty($blogs_list)) {
        foreach ($blogs_list as $blog) {
            switch_to_blog($blog['blog_id']);
            single_uninstall();
            restore_current_blog();
        }
    }
} else {
    single_uninstall();
}