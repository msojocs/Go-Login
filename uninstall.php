<?php

// If uninstall not called from WordPress, then exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit();
}

/**
 * Uninstall operations
 */
function single_uninstall() {
    // delete table
    // $GLOBALS['wpdb']->query("DROP TABLE IF EXISTS {$GLOBALS['wpdb']->prefix}table_name");

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