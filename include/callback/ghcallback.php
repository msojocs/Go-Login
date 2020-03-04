<?php
$gh = new Ghconnect();
$uinfo = $gh->getUsrInfo();
$openid = $uinfo->id;
$email = $uinfo->email;

if(!$openid){
    echo '用户信息获取状况：<br />';
    var_dump($uinfo);
    echo "<br />OpenId获取失败！<a href=\"" . get_Go_login_url('gh', false) . "\">重试</a>？||||||或者<a href=\"" . home_url() . "\" >回首页</a>？";
    die;
}

//头像
$img = $uinfo->avatar_url;

$userinfo = array(
    'avatar' => $img,
    'nickname' => $uinfo->name,
    'email' => $email
);
Go_login_handle($openid,$userinfo,'gh');
