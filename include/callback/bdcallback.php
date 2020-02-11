<?php
$bd = new Bdconnect();
$uinfo = $bd->getUsrInfo();
$openid = $uinfo->uid;
if(!$openid){
	header("Location: " . home_url());
    die;
}
//头像
$img = $uinfo->portrait;
$img = 'https://ss0.bdstatic.com/7Ls0a8Sm1A5BphGlnYG/sys/portrait/item/'.$img;
if(!$img)
    $img = $uinfo->portrait;
$userinfo = array(
    'avatar' => $img,
    'nickname' => $uinfo->uname
);
Go_login_handle($openid,$userinfo,'bd');