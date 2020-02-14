<?php
function openidtype2field($type){
    $openidtype2field = array(
        'qq' => 'qq_openid',
        'sina' => 'sina_uid',
        'bd' => 'bduid',
        'gh' => 'ghid',
        'wx' => 'wx_openid'
        );
    return $openidtype2field[$type];
}

//头像处理
function Gologin_avatar($avatar, $id_or_email, $size, $default, $alt) {
    if ($id_or_email != '') {
        if (is_numeric($id_or_email)) {
            $id = (int)$id_or_email;
            $username = get_user_meta($id, 'nickname', 1);
        } elseif (is_object($id_or_email)) {
            if (! empty($id_or_email->comment_type) && ! in_array($id_or_email->comment_type, (array) $allowed_comment_types))
                return false;
            if (! empty($id_or_email->user_id)) {
                $id = (int) $id_or_email->user_id;
            }
            $username = get_user_meta($id, 'nickname', 1);
        } else {
            $email = $id_or_email;
            $user = get_user_by('email',$email);
            $id = $user->ID;
            $username = $user->user_nicename;
        }
        $qquserimg = get_user_meta($id, 'qquserimg', true);
        $sinauserimg = get_user_meta($id, 'sinauserimg', true);
        $bduserimg = get_user_meta($id, 'bduserimg', true);
        $ghuserimg = get_user_meta($id, 'ghuserimg', true);
        $wxuserimg = get_user_meta($id, 'wxuserimg', true);
        if ($alt == '') {
            $alt = $username;
        }
        if ($qquserimg != '') {
            $userimg = $qquserimg;
        } elseif ($sinauserimg != '') {
            $userimg = $sinauserimg;
        } elseif ($bduserimg != '') {
            $userimg = $bduserimg;
        } elseif ($ghuserimg != '') {
            $userimg = $ghuserimg;
        } elseif ($wxuserimg != '') {
            $userimg = $wxuserimg;
        }else{
            return $avatar;
        }
        $avatar = "<img width=\"{$size}\" height=\"{$size}\" class=\"avatar\" src=\"{$userimg}\" alt=\"{$alt}\">";
        return $avatar;
    }
    return $avatar;
}
add_filter('get_avatar','Gologin_avatar', 1,5);

/**
 * 创建数据库字段，在user表中增加一个`qq_openid`和 `sina_uid`和`bduid`和`ghid`四个字段
 * qq_openid字段用于记录QQ返回的用户openid
 * sina_uid用于记录新浪微博返回的用户uid
 * bduid用于记录百度返回的uid
 * ghid用于记录Github返回的id
 * @author 祭夜
 * @version  2.0
 */
if (!function_exists('Go_login_plugin_activation')) {
    function Go_login_plugin_activation() {
        global $wpdb;
        // 创建qq_openid字段(QQ)
        $var = $wpdb->query("SELECT `qq_openid` FROM $wpdb->users");
        if (!$var) {
            $wpdb->query("ALTER TABLE $wpdb->users ADD qq_openid varchar(50)");
        }
        // 创建sina_uid字段(新浪)
        $var1 = $wpdb->query("SELECT `sina_uid` FROM $wpdb->users");
        if (!$var1) {
            $wpdb->query("ALTER TABLE $wpdb->users ADD sina_uid varchar(50)");
        }
        // 创建bduid字段(百度)
        $var2 = $wpdb->query("SELECT `bduid` FROM $wpdb->users");
        if (!$var2) {
            $wpdb->query("ALTER TABLE $wpdb->users ADD bduid varchar(50)");
        }
        // 创建ghid字段(Github)
        $var3 = $wpdb->query("SELECT ghid FROM $wpdb->users");
        if (!$var3) {
            $wpdb->query("ALTER TABLE $wpdb->users ADD ghid varchar(50)");
        }
        // 创建wx_openid字段(微信)
        $var4 = $wpdb->query("SELECT wx_openid FROM $wpdb->users");
        if (!$var4) {
            $wpdb->query("ALTER TABLE $wpdb->users ADD wx_openid varchar(50)");
        }
    }
}
add_action('activated_plugin', 'Go_login_plugin_activation');

/**
 * GoLogin解绑
 * @param string $type 解绑类型，四个参数qq或者sina或者bd或者gh
 * @author 祭夜
 * @version  2.0
 */
if (!function_exists('Go_login_jb')) {
    function Go_login_jb($type) {
        if (!is_user_logged_in())
            exit('<meta charset="utf-8" />ErrorCode:GoLogin0002<br/>ErrorMessage:请登录后再进行解绑。<br/>Contact QQ:<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1690127128&site=qq&menu=yes">1690127128</a>');
        if(empty($type))
            exit('解绑时发生错误');
        global $wpdb;
        $userid = get_current_user_id();
        $key = openidtype2field($type);
        $r = $wpdb->query("UPDATE `{$wpdb->users}` SET `{$key}` = '' WHERE ID = '{$userid}'");
        delete_user_meta($userid, $type . 'userimg');
        return $r;
    }
}

/**
 * 构造登录链接
 * @author 祭夜
 * @version  2.0
 * @param string $type 登录链接类型，接受8个固定参数，登陆参数为:'qq'和'sina'和'bd'和'gh','qqjb'和'sinajb'和'bdjb'和'ghjb'
 * @param boole $echo 结果是否直接输出，1（默认）输出值，0返回值
 * @return string (url)
 */
if (!function_exists('get_Go_login_url')) {
    function get_Go_login_url($type, $echo = true) {
        $login_url = home_url("/").'?Go_login_type='.$type;
        if ($echo)
            echo $login_url;
        else
            return $login_url;
    }
}

function add_Go_login_action_links ($links) {
    $links[] = '<a href="' . admin_url('admin.php?page=GO_login_setting_page') . '">设置</a>';
    return $links;
}
add_filter('plugin_action_links_Go_login/index.php', 'add_Go_login_action_links');

//自定义登录框内容
if (!function_exists('Gologin_custom_login_img')) {
    function Gologin_custom_login_img() {
        Go_login_connect_form();
    }
}
add_action('login_form', 'Gologin_custom_login_img');

//增加用户信息字段
function modify_user_contact_methods($user_contact) {
    $user_contact['qquserimg'] = 'QQ头像';
    $user_contact['sinauserimg'] = '新浪头像';
    $user_contact['bduserimg'] = '百度头像';
    $user_contact['ghuserimg'] = 'Github头像';
    $user_contact['wxuserimg'] = '微信头像';
    return $user_contact;
}
add_filter('user_contactmethods', 'modify_user_contact_methods');

//小工具
class Go_login_Widget extends WP_Widget {
    function __construct() {
        $widget_ops = array('description' => 'GoLogin登陆插件小工具，可以显示用户登陆状态。');
        parent::__construct(false,$name = 'GoLogin社会化登录',$widget_ops);
    }
    function form($instance) {
        $defaults = array('title' => 'GoLogin社会化登录');
        $instance = wp_parse_args((array) $instance, $defaults);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title');
                ?>"><?php _e('Title:');
                ?></label>
            <input id="<?php echo $this->get_field_id('title');
            ?>" name="<?php echo $this->get_field_name('title');
            ?>" value="<?php echo $instance['title'];
            ?>" class="widefat" type="text" />
        </p>
        <?php
    }
    function update($new_instance, $old_instance) {
        return $new_instance;
    }
    function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title']);

        echo $args['before_widget'];
        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        include('widget.php');
        echo $args['after_widget'];
    }
}
function Go_login_load_widgets() {
    register_widget('Go_login_Widget');
}
add_action('widgets_init', 'Go_login_load_widgets');

//css
function Go_login_css() {
    wp_register_style('Go_login_css', WP_PLUGIN_URL."/".dirname(plugin_basename(__FILE__)).'/assets/css/widget.css');
    wp_enqueue_style('Go_login_css');
    wp_enqueue_script("jquery");
    wp_register_script('login_script', plugins_url('assets/js/login.js', __FILE__), false, '1.0', true);
    wp_enqueue_script('login_script');
}
add_action('init', 'Go_login_css');

/**
 * 判断是否绑定
 * @author 祭夜
 * @version  2.0
 * @param string $type 需要检查的类型，只接受四个固定参数，即:'qq'和'sina'和'bd'和'gh'.
 * @return boole/string 存在返回值，不存在返回false
 */
function is_gologin_bind($type) {
    global $wpdb;
    $field = openidtype2field($type);
    $user_ID = get_current_user_id();
    $query = "SELECT {$field} FROM `{$wpdb->users}` where ID='{$user_ID}'";
    $openid_db = $wpdb->get_var($query);
    if ($openid_db)
        return $openid_db;
    else
        return false;
}

//资料页
function Go_login_profile() {
    ?>
    <table class="form-table">
        <tr>
            <th scope="row"><label>Gologin登陆绑定</label></th>
            <td><script>
                var url = location.search; if (url.indexOf("?")!=-1) {
                    url = window.location.href; url = url.split("?")[0];
                    self.location = url;
                }
            </script>
                <div class="Go_login_widget">
                    <?php
                        echo '<script src="//lib.baomitu.com/jquery/1.8.3/jquery.min.js"></script>';
                        //解决解绑后在当前页面再绑定时自动解绑的尴尬现象
                        
                        if (is_gologin_bind('qq')) {
                            $qq_jburl = add_query_arg('Go_login_type', 'qqjb', get_edit_user_link());
                            echo '<a class="qq-b" title="QQ解绑" href="'.$qq_jburl.'"></a>';
                        } else {
                            echo '<a class="gologin-button qq" href="' . get_Go_login_url('qq', false) . '" title="QQ登录绑定"></a>';
                        }
                        
                        if (is_gologin_bind('sina')) {
                            $sina_jburl = add_query_arg('Go_login_type','sinajb',get_edit_user_link());
                            echo '<a class="sina-b" title="新浪微博解绑" href="'.$sina_jburl.'"></a>';
                        } else {
                            echo '<a class="gologin-button sina" href="' . get_Go_login_url('sina', false) . '" title="新浪微博登录绑定"></a>';
                        }
                        
                        if (is_gologin_bind('bd')) {
                            $bd_jburl = add_query_arg('Go_login_type','bdjb',get_edit_user_link());
                            echo '<a class="bd-b" title="百度解绑" href="'.$bd_jburl.'"></a>';
                        } else {
                            echo '<a class="gologin-button bd" href="' . get_Go_login_url('bd', false) . '" title="百度登录绑定"></a>';
                        }
                        
                        if (is_gologin_bind('gh')) {
                            $gh_jburl = add_query_arg('Go_login_type','ghjb',get_edit_user_link());
                            echo '<a class="github-b"title="Github解绑" href="'.$gh_jburl.'"></a>';
                        } else {
                            echo '<a class="gologin-button github" href="' . get_Go_login_url('sina', false) . '" title="Github登录绑定"></a>';
                        }
                        
                        if (is_gologin_bind('wx')) {
                            $wx_jburl = add_query_arg('Go_login_type','wxjb',get_edit_user_link());
                            echo '<a class="wechat-b" title="微信解绑" href="'.$wx_jburl.'"></a>';
                        } else {
                            echo '<a class="gologin-button wechat" href="' . get_Go_login_url('sina', false) . '" title="微信登录绑定"></a>';
                        }
                    
                    ?>
                </div>
            </td>
        </tr>
    </table>
    <?php
}
add_action('show_user_profile','Go_login_profile');

/*
* 登录处理
* $type 处理类型 qq sina bd gh wx
*/
function Go_login_handle($openid, $userinfo, $type) {
    $success_massage = '<body><style type="text/css">.login_iframe{padding:20px 0;background-color:#FFF;}.sa-title{text-align:center;font-size:20px;font-weight:400;margin-top:20px;}.spinner{width:60px;height:60px;position:relative;margin:100px auto;}.double-bounce1,.double-bounce2{width:100%;height:100%;border-radius:50%;background-color:#67CF22;opacity:0.6;position:absolute;top:0;left:0;-webkit-animation:bounce 2.0s infinite ease-in-out;animation:bounce 2.0s infinite ease-in-out;}.double-bounce2{-webkit-animation-delay:-1.0s;animation-delay:-1.0s;}@-webkit-keyframes bounce{0%,100%{-webkit-transform:scale(0.0)}50%{-webkit-transform:scale(1.0)}}@keyframes bounce{0%,100%{transform:scale(0.0);-webkit-transform:scale(0.0);}50%{transform:scale(1.0);-webkit-transform:scale(1.0);}}</style><div class="login_iframe"><div class="spinner"><div class="double-bounce1"></div><div class="double-bounce2"></div></div><h2 class="sa-title">登录成功！即将返回登录前页面~</h2>
    <script>location.href = JSON.parse(localStorage.getItem(\'historyUrl\'));</script></div></body>';
    global $wpdb;
    $is_login = is_user_logged_in();
    $uid = get_current_user_id();
    if (empty($openid) || empty($userinfo) || empty($type)) {
        return false;
    }
    $key = openidtype2field($type);
    $query = "SELECT ID FROM `{$wpdb->users}` where `{$key}`='{$openid}'";
    $userID = $wpdb->get_var($query);
    if ($userID) {
        //存在open id
        if (!$uid) {
            //未登录
            $user = get_user_by('id', $userID);
            wp_set_current_user($userID,$user->user_login);
            wp_set_auth_cookie($userID);
            do_action('wp_login', $user->user_login);
            global $user_email;
            wp_get_current_user();
            $user_id = get_current_user_id();
            if (!$user_email) {
                Go_login_add_email();
            } else {
                    echo $success_massage;
            }
            exit();
        } else {
            //已登录
            exit('<meta charset="utf-8" />ErrorCode:Gologin0005<br/>ErrorMessage:当前'.$type.'已经绑定,不能再绑定<a href="/">Click to go Home</a><br/>Contact QQ:<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1690127128&site=qq&menu=yes">1690127128</a>');
        }
    } else {
        //不存在open id
        if (!$uid) {
            //未登录
            ($type == 'wx')?($login_name = $userinfo['user_login']):($login_name = wp_create_nonce($openid));
            $pass = wp_generate_password(12, false);
            $username = $userinfo['nickname'];
            $http = is_ssl()?"https://":"http://";
            $userdata = array(
                'user_login' => $login_name,
                'display_name' => $username,
                'user_pass' => $pass,
                'role' => get_option('default_role'),
                'nickname' => $username,
                //'first_name' => $username,
                $type.'userimg' => $userinfo['avatar']
            );
            if ($type == 'gh') {
                $email = $userinfo['email'];
                if (!email_exists($user_email)) {
                    $userdata['user_email'] = $email;
                }
            }
            $user_id = wp_insert_user($userdata);
            //根据用户数据创建用户
            if (is_wp_error($user_id)) {
                exit('<meta charset="utf-8" />ErrorCode:Gologin0005<br/>ErrorMessage:'.$user_id->get_error_message().'<a href="/">Click to go Home</a><br/>Contact QQ:<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1690127128&site=qq&menu=yes">1690127128</a>');
            } else {
                $key = openidtype2field($type);
                $ff = $wpdb->query("UPDATE `{$wpdb->users}` SET `{$key}` = '{$openid}' WHERE ID = '$user_id'");
                if ($ff) {
                    wp_set_current_user($user_id);
                    wp_set_auth_cookie($user_id);
                    global $user_email;
                    wp_get_current_user();
                    if (!$user_email) {
                        Go_login_add_email();
                    } else {
                        echo $success_massage;
                    }
                    exit();
                }
            }
        } else {
            //已登录
            $img = $userinfo['avatar'];
            $key = openidtype2field($type);
            $wpdb->query("UPDATE `{$wpdb->users}` SET `{$key}` = '{$openid}' WHERE ID = '{$uid}'");
            $data = update_user_meta($uid, "{$type}userimg", $img);
            
            global $user_email;
            wp_get_current_user();
            if (!$user_email) {
                Go_login_add_email();
            } else {
                echo $success_massage;
            }
            exit();
        }
    }
}

//登录表单
function Go_login_connect_form() {
    if (!is_user_logged_in()) {
        echo '<div class="Go_login_widget">';
        
        if (!empty(get_option('Go_login_options_qq_appid')) && !empty(get_option('Go_login_options_qq_appkey')))
            echo'<a class="gologin-button qq" href="' . get_Go_login_url('qq', false) . '" title="QQ登录"></a>';
            
        if (!empty(get_option('Go_login_options_sina_appkey')) && !empty(get_option('Go_login_options_sina_appkey')))
            echo'<a class="gologin-button sina" href="' . get_Go_login_url('sina', false) . '" title="新浪微博登录"></a>';
            
        if (!empty(get_option('Go_login_options_bd_apikey')) && !empty(get_option('Go_login_options_bd_secretkey')))
            echo'<a class="gologin-button bd" href="' . get_Go_login_url('bd', false) . '" title="百度登录"></a>';
            
        if (!empty(get_option('Go_login_options_gh_ClientID')) && !empty(get_option('Go_login_options_gh_ClientSecret')))
            echo'<a class="gologin-button github" href="' . get_Go_login_url('gh', false) . '" title="Github登录"></a>';
            
        if(get_option('Go_login_options_wechat_switch') == 'on')
            echo '<a class="gologin-button wechat" href="' . get_Go_login_url('wx', false) . '" title="微信登录"></a></div>';
    }
}

//添加邮箱
function Go_login_add_email(){
    global $wpdb;
    include __DIR__ . '/pages/emailModify.php';
}

//GET
function curl_get($url)
{
    $ch=curl_init($url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36');
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $content=curl_exec($ch);
    curl_close($ch);
    return($content);
}

//获取GET值
function queryGET($key)
{
    return isset($_GET[$key])?$_GET[$key]:1;
}

//获取POST值
function queryPOST($key)
{
    return isset($_POST[$key])?$_POST[$key]:null;
}
    
//写日志函数
function goLoginLogInfo($msg) {
    $logSwitch = 1;
    // 日志开关：1表示打开，0表示关闭
    $logFile = __DIR__ . '/log/Go-login.log';
    // 日志路径
    if ($logSwitch == 0) return;
    date_default_timezone_set('Asia/Shanghai');
    file_put_contents($logFile, date('[Y-m-d H:i:s]: ') . $msg . PHP_EOL, FILE_APPEND);
    return $msg;
}

//ajax profile
function go_login_do_profile() {
    global $wpdb;
    if (isset($_POST['mm_mail']) && $_POST['action'] == 'go_login_do_profile') {
        if (!empty($_POST['mm_mail'])) {
            
            //检查邮箱是否已存在
            if(email_exists($_POST['mm_mail']))
                exit('ycz');
            
    		$userdata = array();
    		$userdata['ID'] = wp_get_current_user()->ID;
    		//$userdata['nickname'] = str_replace(array('<','>','&','"','\'','#','^','*','_','+','$','?','!'), '', $wpdb->escape($_POST['mm_name']));
    		$userdata['user_email'] = $wpdb->escape($_POST['mm_mail']);
    		//$userdata['user_url'] = $wpdb->escape($_POST['mm_url']);
    		//$userdata['description'] = $wpdb->escape($_POST['mm_desc']);
    		wp_update_user($userdata);
    		exit("success");
        }
    }
}
add_action('wp_ajax_go_login_do_profile', 'go_login_do_profile');
add_action('wp_ajax_nopriv_go_login_do_profile', 'go_login_do_profile');

//end