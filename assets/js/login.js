jQuery(document).ready(function($) {
    $(".qqck").click(function() {
        var t = $(this);
        var Go_login_url = t.attr('href');
        var Traum_iheight = 450;
        var Traum_iwidth = 500;
        var Traum_top = (window.screen.availHeight - Traum_iheight) * 0.5;
        var Traum_left = (window.screen.availWidth - Traum_iwidth) * 0.5;
        window.open(Go_login_url, 'Gologin用户登陆', 'width=' + Traum_iwidth + ',height=' + Traum_iheight + ',top=' + Traum_top + ',left=' + Traum_left + ',innerHeight=' + Traum_iheight + ',innerWidth=' + Traum_iwidth + ',menubar=0,scrollbars=1, resizable=1,status=1,titlebar=0,toolbar=0,location=1');
        return false;
    });
});

function Go_login(val) {
    $('body').append('<div class="login_Div"><span class="close" onclick="Go_login_close();">&times;</span><iframe id="login_frame" name="login_frame" style="margin: 0;padding: 0;height: 400px!important; frameborder="0" scrolling="no" width="100%" height="100%" src="/?Go_login_type=' + val + '"></iframe></div><div class="mask_Div"></div>');
}

function Go_login_close() {
    $('.login_Div').remove();
    $('.mask_Div').remove();
}
