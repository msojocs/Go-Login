<?php

//Callback
function Go_login_callback() {
    $callbackURL = $_SERVER['REDIRECT_URL'];
    if(Go_login_startwith($callbackURL, '/Gologin/callback') === true)
    {
        switch(substr($callbackURL, strripos($callbackURL,"callback/") + 9))
        {
            case 'qq':
                include 'callback/qqcallback.php';
                break;
            case 'sina':
                include 'callback/sinacallback.php';
                break;
            case 'baidu':
                include 'callback/bdcallback.php';
                break;
            case 'github':
                include 'callback/ghcallback.php';
                break;
            default:
                break;
        }
    }
}
add_action('init', 'Go_login_callback');

function Go_login_startwith($str,$pattern) {
    if(strpos($str,$pattern) === 0)
        return true;
    else
        return false;
}