<?php
/*
Plugin Name: GoLogin社会化登录
Plugin URI: https://www.jysafe.cn
Description: QQ，新浪微博，百度，Github，微信登录插件
Author: 祭夜
Version: 1.0.2
Author URI: https://www.jysafe.cn
*/
if (!defined('Go_Login_DIR')) {
    define('Go_Login_DIR', plugin_dir_url(__FILE__));
}
session_start();
if (!function_exists('wp_get_current_user')) {
    include(ABSPATH . "wp-includes/pluggable.php");
}
include_once 'functions.php';
include_once 'include/class/Qqconnect.class.php';
include_once 'include/class/Sinaconnect.class.php';
include_once 'include/class/Bdconnect.class.php';
include_once 'include/class/Githubconnect.class.php';
include_once 'include/Wechatconnect.php' ;
include_once 'include/callback.php' ;
require "option.php";

function Go_login_query() {
    $type = queryGET('Go_login_type');
    
    switch ($type) {
        //QQ
        case 'qq':
            if (empty(get_option('Go_login_options_qq_appid')) || empty(get_option('Go_login_options_qq_appkey')))
                exit("非常抱歉，QQ登录已关闭！");
            $qq = new Qqconnect();
            $qq->getAuthCode();
            exit;
            break;
            
        //QQ解绑
        case 'qqjb':
            Go_login_jb('qq');
            break;
        
        //SINA
        case 'sina':
            if (empty(get_option('Go_login_options_sina_appkey')) || empty(get_option('Go_login_options_sina_appkey')))
                exit("非常抱歉，微博登录已关闭！");
            $sina = new Sinaconnect();
            $sina->getAuthCode();
            exit;
            break;
            
        //SINA解绑
        case 'sinajb':
            Go_login_jb('sina');
            break;
        
        //百度
        case 'bd':
            if (empty(get_option('Go_login_options_bd_apikey')) || empty(get_option('Go_login_options_bd_secretkey')))
                exit("非常抱歉，百度登录已关闭！");
            $bd = new Bdconnect();
            $bd->getAuthCode();
            exit;
            break;
        
        //百度解绑
        case 'bdjb':
            Go_login_jb('bd');
            break;
            
        //Github
        case 'gh':
            if (empty(get_option('Go_login_options_gh_ClientID')) || empty(get_option('Go_login_options_gh_ClientSecret')))
                exit("非常抱歉，Github登录已关闭！");
            $gh = new Ghconnect();
            $gh->getAuthCode();
            exit;
            break;
            
        //Github解绑
        case 'ghjb':
            Go_login_jb('gh');
        break;
        
        //微信
        case 'wx':
            if(get_option('Go_login_options_wechat_switch') != 'on')
                exit("非常抱歉，微信登录已关闭！");
            wx_login();
            exit;
        break;
        
        //微信解绑
        case 'wxjb':
            Go_login_jb('wx');
        break;
        
        default:
            // code...
            break;
    }
    
    
}
add_action('init', 'Go_login_query');