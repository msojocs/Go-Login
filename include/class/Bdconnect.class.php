<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2020.
// +----------------------------------------------------------------------
// | Author: 祭夜 <me@jysafe.cn>
// +----------------------------------------------------------------------
/**
 *  百度第三方登录认证
 */
class Bdconnect {
    private static $data;
    //APP ID
    private $app_id="";
    //APP KEY
    private $app_key="";
    //回调地址
    private $callBackUrl="";
    //Authorization Code
    private $code="";
    //access Token
    private $accessToken="";
    //role
    private $role="";

    public function __construct(){
        $this->app_id = get_option('Go_login_options_bd_apikey');//App Key
        $this->app_key = get_option('Go_login_options_bd_secretkey');//App Secret
        $this->callBackUrl = home_url('Gologin/callback/baidu');
        $this->code=$_GET['code'];
        //检查用户数据
        if(empty($_SESSION['QC_userData'])){
            self::$data = array();
        }else{
            self::$data = $_SESSION['QC_userData'];
        }
    }

    //获取Authorization Code
    public function getAuthCode(){
        $url="https://openapi.baidu.com/oauth/2.0/authorize";
        $param['response_type']="code";
        $param['client_id']=$this->app_id;
        $param['redirect_uri']=$this->callBackUrl;
        //-------生成唯一随机串防CSRF攻击
                $state = md5(uniqid(rand(), TRUE));
                $_SESSION['state']=$state;
        $param['state']=$state;
        $param =http_build_query($param,'','&');
        $url=$url."?".$param;
        header("Location:".$url);
    }

    //通过Authorization Code获取Access Token
    public function getAccessToken(){
        $url="https://openapi.baidu.com/oauth/2.0/token";
        $param['grant_type']="authorization_code";
        $param['client_id']=$this->app_id;
        $param['client_secret']=$this->app_key;
        $param['code']=$this->code;
        $param['redirect_uri']=$this->callBackUrl;
        $param =http_build_query($param,'','&');
        //$url=$url."?".$param;
        $data = $this->postUrl($url,$param);
        $data = json_decode($data);
        $_SESSION['access_tokenbd'] = $data->access_token;
        return $data;
    }
    
    //获取信息
    public function getUsrInfo(){
        $access_token = $_SESSION['access_tokenbd'];
        if(!$access_token){
            $rdata = $this->getAccessToken();
            $access_token = $rdata->access_token;
        }
        if(empty($access_token)){
            return false;
        }
        $url="https://openapi.baidu.com/rest/2.0/passport/users/getLoggedInUser";
        $param['access_token'] = $access_token;
        $param =http_build_query($param,'','&');
        $url=$url."?".$param;
        $rzt=json_decode($this->getUrl($url));
        return $rzt;
    }
    
    //CURL GET
    private function getUrl($url){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        if (!empty($options)){
            curl_setopt_array($ch, $options);
        }
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    //CURL POST
    private function postUrl($url,$data){
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
        curl_setopt ( $ch, CURLOPT_POST, TRUE );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $ret = curl_exec ( $ch );
        curl_close ( $ch );
        return $ret;
    }
}