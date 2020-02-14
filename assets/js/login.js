jQuery(document).ready(function($) {
    $(".gologin-button").click(function() {
        markHistory();
    });
});

function markHistory()
{
    var url = window.location.href;
    if(url.indexOf("wp-login.php") == -1)
        localStorage.setItem('historyUrl',JSON.stringify(url));
    else if(url.indexOf("redirect_to") > 0 )
    {
        url = url.split("redirect_to=")[1];
        url = decodeURIComponent(url);
        url = url.split("&")[0];
        localStorage.setItem('historyUrl',JSON.stringify(url));
    }else
        localStorage.setItem('historyUrl',JSON.stringify(window.location.origin));
}