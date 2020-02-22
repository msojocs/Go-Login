<?php
defined('ABSPATH') or exit;

function Go_login_menu() {
    $icon_url = plugins_url('/assets/img/favicon.ico', __FILE__);
    add_menu_page(
        'Gologin第三方登录登录设置',
        'Gologin登录设置',
        'administrator',
        'GO_login_setting_page',
        'display_Go_login_setting',
        $icon_url
    );
}
add_action('admin_menu','Go_login_menu');

function display_Go_login_setting() {
    echo "<h1>设置页面</h1>";
    ?>
    <div class="wpmm-wrapper">
        <div class="content" class="wrapper-cell">
            <form method="post" action="options.php">
                <?php settings_fields('GO_login_setting_field');
                ?>
                <?php do_settings_sections('GO_login_setting_page');
                    do_settings_sections('GO_login_setting_section');
                    do_settings_sections('GO_login_setting_field');
                ?>
                <?php submit_button();
                ?>
            </form>
        </div>
<?php
    include_once "sidebar.php";
    echo "</div>";
}

//添加设置表单
function add_Go_login_setting_field() {
    
    //Go_login_options_qq_appid
    add_settings_field(
        'Go_login_options_qq_appid',                      // ID used to identify the field throughout the theme
        'QQAppID',                           // The label to the left of the option interface element
        'Go_login_text_field_callback',  // The name of the function responsible for rendering the option interface
        'GO_login_setting_page',                          // The page on which this option will be displayed
        'GO_login_setting_section',
        
        array(
            'field' => 'Go_login_options_qq_appid',//传送给数组
            'desc' => '<p class="description">填入<a href="http://connect.qq.com" target="_blank">QQ互联</a>里申请到的APPID。</p>'
        )
    );
    
    //Go_login_options_qq_appkey
    add_settings_field(
        'Go_login_options_qq_appkey',                      // ID used to identify the field throughout the theme
        'QQAppKEY',                           // The label to the left of the option interface element
        'Go_login_text_field_callback',  // The name of the function responsible for rendering the option interface
        'GO_login_setting_page',                          // The page on which this option will be displayed
        'GO_login_setting_section',
        
        array(
            'field' => 'Go_login_options_qq_appkey',//传送给数组
            'desc' => '<p class="description">填入<a href="http://connect.qq.com" target="_blank">QQ互联</a>里申请到的APPKEY。</p>'
        )
    );
    
    //Go_login_options_sina_appkey
    add_settings_field(
        'Go_login_options_sina_appkey',                      // ID used to identify the field throughout the theme
        'Sina Appkey',                           // The label to the left of the option interface element
        'Go_login_text_field_callback',  // The name of the function responsible for rendering the option interface
        'GO_login_setting_page',                          // The page on which this option will be displayed
        'GO_login_setting_section',
        
        array(
            'field' => 'Go_login_options_sina_appkey',//传送给数组
            'desc' => '<p class="description">填入<a href="http://open.weibo.com" target="_blank">新浪微博开放平台</a>里申请到的APPKEY。</p>'
        )
    );
    
    //Go_login_options_sina_appsecret
    add_settings_field(
        'Go_login_options_sina_appsecret',                      // ID used to identify the field throughout the theme
        '新浪App Secret:',                           // The label to the left of the option interface element
        'Go_login_text_field_callback',  // The name of the function responsible for rendering the option interface
        'GO_login_setting_page',                          // The page on which this option will be displayed
        'GO_login_setting_section',
        
        array(
            'field' => 'Go_login_options_sina_appsecret',//传送给数组
            'desc' => '<p class="description">填入<a href="http://open.weibo.com" target="_blank">新浪微博开放平台</a>里申请到的App Secret。</p>'
        )
    );
    
    //Go_login_options_bd_apikey
    add_settings_field(
        'Go_login_options_bd_apikey',                      // ID used to identify the field throughout the theme
        '百度API Key:',                           // The label to the left of the option interface element
        'Go_login_text_field_callback',  // The name of the function responsible for rendering the option interface
        'GO_login_setting_page',                          // The page on which this option will be displayed
        'GO_login_setting_section',
        
        array(
            'field' => 'Go_login_options_bd_apikey',//传送给数组
            'desc' => '<p class="description">填入<a href="http://dev.baidu.com" target="_blank">百度开放平台</a>里申请到的Api Key。</p>'
        )
    );
    
    //Go_login_options_bd_secretkey
    add_settings_field(
        'Go_login_options_bd_secretkey',                      // ID used to identify the field throughout the theme
        '百度Secret Key:',                           // The label to the left of the option interface element
        'Go_login_text_field_callback',  // The name of the function responsible for rendering the option interface
        'GO_login_setting_page',                          // The page on which this option will be displayed
        'GO_login_setting_section',
        
        array(
            'field' => 'Go_login_options_bd_secretkey',//传送给数组
            'desc' => '<p class="description">填入<a href="http://dev.baidu.com" target="_blank">百度开放平台</a>里申请到的Secret Key。</p>'
        )
    );
    
    //Go_login_options_gh_ClientID
    add_settings_field(
        'Go_login_options_gh_ClientID',                      // ID used to identify the field throughout the theme
        'Github Client ID:',                           // The label to the left of the option interface element
        'Go_login_text_field_callback',  // The name of the function responsible for rendering the option interface
        'GO_login_setting_page',                          // The page on which this option will be displayed
        'GO_login_setting_section',
        
        array(
            'field' => 'Go_login_options_gh_ClientID',//传送给数组
            'desc' => '<p class="description">填入<a href="https://github.com/settings/developers" target="_blank">Github开放平台</a>里申请到的Client ID。</p>'
        )
    );
    
    //Go_login_options_gh_ClientSecret
    add_settings_field(
        'Go_login_options_gh_ClientSecret',                      // ID used to identify the field throughout the theme
        'Github Client Secret:',                           // The label to the left of the option interface element
        'Go_login_text_field_callback',  // The name of the function responsible for rendering the option interface
        'GO_login_setting_page',                          // The page on which this option will be displayed
        'GO_login_setting_section',
        
        array(
            'field' => 'Go_login_options_gh_ClientSecret',//传送给数组
            'desc' => '<p class="description">填入<a href="https://github.com/settings/developers" target="_blank">Github开放平台</a>里申请到的Client Secret。</p>'
        )
    );
    
    //WeChat
    add_settings_field(
        'Go_login_options_wechat_switch',                      // ID used to identify the field throughout the theme
        '启用微信登录:',                           // The label to the left of the option interface element
        'Go_login_checkbox_field_callback',  // The name of the function responsible for rendering the option interface
        'GO_login_setting_page',                          // The page on which this option will be displayed
        'GO_login_setting_section',
        
        array(
            'field' => 'Go_login_options_wechat_switch',//传送给数组
            'desc' => '启用微信登录'
        )
    );
}
add_action('admin_init', 'add_Go_login_setting_field');

//注册设置菜单
function register_Go_login_setting_field() {
    register_setting('GO_login_setting_field', 'Go_login_options_qq_appid');
    register_setting('GO_login_setting_field', 'Go_login_options_qq_appkey');
    register_setting('GO_login_setting_field', 'Go_login_options_sina_appkey');
    register_setting('GO_login_setting_field', 'Go_login_options_sina_appsecret');
    register_setting('GO_login_setting_field', 'Go_login_options_bd_apikey');
    register_setting('GO_login_setting_field', 'Go_login_options_bd_secretkey');
    register_setting('GO_login_setting_field', 'Go_login_options_gh_ClientID');
    register_setting('GO_login_setting_field', 'Go_login_options_gh_ClientSecret');
    register_setting('GO_login_setting_field', 'Go_login_options_wechat_switch');
}
add_action('admin_init', 'register_Go_login_setting_field');

//add section
function add_Go_login_setting_section()
{
    //setting
    add_settings_section(
        'GO_login_setting_section',                         // ID used to identify this section and with which to register options
        'GoLogin设置',                               // Title to be displayed on the administration page
        'GO_login_setting_section_callback',                // Callback used to render the description of the section
        'GO_login_setting_page'                          // Page on which to add this section of options
    );
}
add_action("admin_init", "add_Go_login_setting_section");

///////////////////////Callback///////////////////////////////

function GO_login_setting_section_callback()
{
    echo "这是GoLogin设置部分";
}

/**
 * $args  array(
 *      'field' => '???',
 *      'desc' => '???'
 * )
 */
function Go_login_text_field_callback($args)
{
    $html = "<input type=\"text\" id=\"{$args['field']}\" name=\"{$args['field']}\" value=\"" . get_option($args['field']) . "\" />";
    $html .= "<label for=\"{$args['field']}\">{$args['desc']}</label>";
    echo $html;
}

function Go_login_checkbox_field_callback($args)
{
    $html = "<input type=\"checkbox\" id=\"{$args['field']}\" name=\"{$args['field']}\" " . (get_option($args['field'])?'checked':'') . " />";
    $html .= "<label for=\"{$args['field']}\">{$args['desc']}</label>";
    echo $html;
}