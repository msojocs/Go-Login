<?php
require('WechatLogin.php');

///////////GoAuth////////////////
//ajax生成登录二维码
function Gologin_goauth_qr_gen() {
    if (isset($_POST['wastart']) && $_POST['action'] == 'Gologin_goauth_qr_gen') {
        if (!empty($_POST['wastart'])) {
            $rest = implode("|", Gologin_get_goauth_qr());
            exit($rest);
        }
    }
}
add_action('wp_ajax_Gologin_goauth_qr_gen', 'Gologin_goauth_qr_gen');
add_action('wp_ajax_nopriv_Gologin_goauth_qr_gen', 'Gologin_goauth_qr_gen');

//检查登录状况
function Gologin_goauth_check() {
    if (isset($_POST['sk']) && $_POST['action'] == 'Gologin_goauth_check') {
        $str = urldecode($_POST['sk']);
        $sk_len = 32 - 1 - strlen($_SERVER['HTTP_HOST']);
        $rest = substr($str, -$sk_len);
        //判断小程序码是否过期
        if(!get_transient($rest))
            exit('101');
        //key
        $goauth_cache = get_transient($rest.'ok');
        if (!empty($goauth_cache)) {
            exit($rest);
            //key
        }
    }
}
add_action('wp_ajax_Gologin_goauth_check', 'Gologin_goauth_check');
add_action('wp_ajax_nopriv_Gologin_goauth_check', 'Gologin_goauth_check');

//返回响应头goauth: ok，以获得授权成功的提示
function Gologin_goauth_redirect() {
    header("goauth: ok");
    die;
}

function Gologin_get_goauth_token() {
    $sk_len = 32 - 1 - strlen($_SERVER['HTTP_HOST']);
    $sk = Gologin_getRandomStr($sk_len, true);
    set_transient($sk, 1, 60*2);
    $sk = urlencode($sk);
    $key = $_SERVER['HTTP_HOST'].'@'.$sk;
    return $key;
}

/**
 * 获得随机字符串
 * @param $len             需要的长度
 * @param $special        是否需要特殊符号
 * @return string       返回随机字符串
 */
function Gologin_getRandomStr($len, $special = true){
    $chars = array(
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
        "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
        "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
        "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
        "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
        "3", "4", "5", "6", "7", "8", "9"
    );

    if($special){
        //!#$()*+,/:;=?-._~  @
        $chars = array_merge($chars, array(
            "!", "#", "$",
            "(", ")", "*", "+", ",",
            "/", ":", ";", "=", "?",
            "-", ".", "_", "~"
        ));
    }

    $charsLen = count($chars) - 1;
    shuffle($chars);                            //打乱数组顺序
    $str = '';
    for($i=0; $i<$len; $i++){
        $str .= $chars[mt_rand(0, $charsLen)];    //随机取出一位
    }
    return $str;
}

function Gologin_get_goauth_qr() {
    $qr64 = [];
    $qr64['key'] = Gologin_get_goauth_token();
    $qr64['qrcode'] = json_decode(curl_get('https://api.goauth.jysafe.cn/qrcode?str='.$qr64['key']),true)['qrcode'];
    return $qr64;
}

function Gologin_goauth_rewrite_rules($wp_rewrite) {
    if ($ps = get_option('permalink_structure')) {
        $new_rules['^goauth'] = 'index.php?userinfo=$matches[1]&sk=$matches[2]';
        $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
    }
}
add_action('generate_Gologin_rewrite_rules', 'Gologin_goauth_rewrite_rules');

function Gologin_goauth_oauth() {
    $goauth_user = $_GET['userinfo'];
    $goauth_sk = esc_attr($_GET['sk']);
    $goauth_res = get_transient($goauth_sk);
    
    if (empty($goauth_res)) {
        die;
    }

    //临时储存用户数据
    set_transient($goauth_sk . 'ok', $goauth_user, 60);
    
    //返回响应头goauth: ok，以获得授权成功的提示
    Gologin_goauth_redirect();
}
//初始化
function Gologin_goauth_oauth_init() {
    if (isset($_GET['userinfo']) && isset($_GET['sk'])) {
        Gologin_goauth_oauth();
    }
}
add_action('init','Gologin_goauth_oauth_init');

//GET自动登录
function Gologin_goauth_auto_login() {
    $key = isset($_GET['goauth_login']) ? $_GET['goauth_login'] : false;
    if ($key) {
        $user_info = get_transient($key.'ok');
        if (!empty($user_info)) {
            //处理信息
            // header("application/json");
            $userInfo = json_decode(stripslashes($user_info), true);
            
            $nickname = $userInfo['nickName'];
            $wxavatar = $userInfo['avatarUrl'];
            $openid = $userInfo['openid'];
            $login_name = 'wx_' . wp_create_nonce($openid);

            if (!$openid) {
                exit('<meta charset="utf-8" />ErrorCode:Traum0001<br/>ErrorMessage:openid is empty <a href="/">Click to home</a><br/>Contact QQ:<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1690127128&site=qq&menu=yes">1690127128</a>');
            }
            $userinfo = array(
                'avatar' => $wxavatar,
                'nickname' => $nickname,
                'user_login' => $login_name
            );

            Go_login_handle($openid,$userinfo,'wx');

        }else
        {
            exit("微信登录码不存在或已过期");
        }
    }
}
add_action('init', 'Gologin_goauth_auto_login');
////////////////GoAuth//////////////
?>