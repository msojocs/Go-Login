<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2020.
// +----------------------------------------------------------------------
// | Author: 祭夜 <me@jysafe.cn>
// +----------------------------------------------------------------------
/**
 *  Github第三方登录认证
 */
class Ghconnect {
    private static $data;
    //APP ID
    private $app_id = "";
    //APP KEY
    private $app_key = "";
    //回调地址
    private $callBackUrl = "";
    //Authorization Code
    private $code = "";
    //access Token
    private $accessToken = "";
    //role
    private $role = "";

    public function __construct() {
        $this->app_id = get_option('Go_login_options_gh_ClientID');
        //Client ID
        $this->app_key = get_option('Go_login_options_gh_ClientSecret');
        //Client Secret
        $this->callBackUrl = home_url('Gologin/callback/github');
        $this->code = isset($_GET['code'])?$_GET['code']:null;
        //检查用户数据
        if (empty($_SESSION['QC_userData'])) {
            self::$data = array();
        } else {
            self::$data = $_SESSION['QC_userData'];
        }
    }

    //获取Code
    public function getAuthCode() {
        $url = "https://github.com/login/oauth/authorize";
        $param['client_id'] = $this->app_id;
        $param['redirect_uri'] = $this->callBackUrl;
        //-------生成唯一随机串防CSRF攻击
        $state = md5(uniqid(rand(), TRUE));
        $_SESSION['state'] = $state;
        $param['state'] = $state;
        //$param['scope']="get_user_info";
        $param = http_build_query($param,'','&');
        $url = $url."?".$param;
        header("Location:".$url);
    }

    //通过Code获取Access Token
    public function getAccessToken() {
        $url = "https://github.com/login/oauth/access_token";
        $param['client_id'] = $this->app_id;
        $param['client_secret'] = $this->app_key;
        $param['code'] = $this->code;
        $param['redirect_uri'] = $this->callBackUrl;
        $param = http_build_query($param,'','&');
        $data = $this->postURL($url,$param);
        $data = json_decode($data);
        return $data;
    }

    //获取信息
    public function getUsrInfo() {
        $rdata = $this->getAccessToken();
        $access_token = $rdata->access_token;
        if (empty($access_token)) {
            return 203;
        }
        $url = "https://api.github.com/user";
        $ret = $this->getUrl($url, $access_token);
        $ret = json_decode($ret);
        unset($_SESSION['state']);
        return $ret;
    }

    //CURL GET
    private function getUrl($url, $access_token) {
        $ch = curl_init($url);
        $headers[] = "User-Agent: jiyeme";
        $headers[] = 'Authorization: token ' . $access_token;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    //CURL POST
    function postUrl($url,$data) {
        $ch = curl_init ();
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt ($ch, CURLOPT_POST, TRUE);
        $headers[] = 'Accept: application/json';
        curl_setopt ($ch, CURLOPT_HTTPHEADER,$headers);
        curl_setopt ($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
        $ret = curl_exec ($ch);
        curl_close ($ch);
        return $ret;
    }

}