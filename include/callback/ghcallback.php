<?php
$gh = new Ghconnect();
$uinfo = $gh->getUsrInfo();
$openid = $uinfo->id;
$email = $uinfo->email;

if(!$openid){
	header("Location: " . home_url());
    die;
}

//头像
$img = $uinfo->avatar_url;
if(!$img)
    $img = $uinfo->avatar_url;
$userinfo = array(
    'avatar' => $img,
    'nickname' => $uinfo->name,
    'email' => $email
);
Go_login_handle($openid,$userinfo,'gh');
