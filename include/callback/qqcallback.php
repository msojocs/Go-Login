<?php
$qq = new Qqconnect();
$openid = $qq->getOpenID();
$uinfo = $qq->getUsrInfo();
if(!$openid){
	header("Location: " . home_url());
    die;
}
//头像
$img = $uinfo->figureurl_qq_2;
preg_match("/^(http:)?([^\b]+)/i",$img, $matches);
$img = $matches[2];
if(!$img)
    $img = $uinfo->figureurl_qq_1;
    preg_match("/^(http:)?([^\b]+)/i",$img, $matches);
    $img = $matches[2];
    $userinfo = array(
    'avatar' => $img,
    'nickname' => $uinfo->nickname
);
Go_login_handle($openid,$userinfo,'qq');