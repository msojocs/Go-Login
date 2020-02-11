<?php
$sina = new Sinaconnect();
$token = $sina->getAccessToken();
$openid = $token->uid;
$uinfo = $sina->getUsrInfo();
if (!$openid) {
    header("Location: " . home_url());
    die;
}

//头像
$img = $uinfo->avatar_large;
preg_match("/^(http:)?([^\b]+)/i",$img, $matches);
$img = $matches[2];
if (!$img)
    $img = $uinfo->profile_image_url;
preg_match("/^(http:)?([^\b]+)/i",$img, $matches);
$img = $matches[2];
$userinfo = array(
    'avatar' => $img,
    'nickname' => $uinfo->screen_name
);

Go_login_handle($openid, $userinfo, 'sina');