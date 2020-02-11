<style>
@media all{
.wpmm-wrapper{display:table;width:100%;}
.wpmm-wrapper #sidebar{padding:0 0 0 20px;width:33%;}
.wpmm-wrapper #sidebar .sidebar_box{background:none repeat scroll 0 0 #fff;border:1px solid #e5e5e5;-webkit-box-shadow:0 1px 1px rgba(0,0,0,.04);box-shadow:0 1px 1px rgba(0,0,0,.04);min-width:255px;line-height:1;margin-bottom:20px;padding:0;}
.wpmm-wrapper #sidebar .sidebar_box h3{margin:0;padding:8px 12px;border-bottom:1px solid #ececec;}
.wpmm-wrapper #sidebar .sidebar_box .inside{margin:6px 0 0;font-size:13px;line-height:1.4em;padding:0 12px 12px;}
.wpmm-wrapper .wrapper-cell{display:table-cell;}
}

</style>
<div id="sidebar" class="wrapper-cell">
    <div class="sidebar_box">
        <h3>Plugin Info</h3>
        <div class="inside">
            <ul>
                <li>Name: Go Login v2.0</li>
                <li>Author: 祭夜</li>
                <li>Website: <a href="https://www.jysafe.cn" target="_blank">祭夜の咖啡馆</a></li>
                <li>GitHub: <a href="https://github.com/jiyeme/Go-Login" target="_blank">Go-Login</a></li>
            </ul>
        </div>
    </div>
    <div class="sidebar_box">
        <h3>功能说明</h3>
        <div class="inside">
            <ol>
                <li>自带登陆状态显示小工具</li>
                <li>绑定(解绑)功能</li>
                <li>提供几个调用函数</li>
            </ol>
        </div>
    </div>
    <div class="sidebar_box">
        <h3>头像优先级</h3>
        <div class="inside">
            <p>QQ > 新浪 > 百度 > Github > 微信> Gravatar头像</p>
        </div>
    </div>
	<div class="sidebar_box">
		<h3>函数接口</h3>
		<div class="inside">
获取登录表单函数： Go_login_connect_form()<br />
获取登录链接：get_Go_login_url($type, $echo = true)<br />
参数说明：$type（必填）为登录类型，$echo（可选）此函数是返回值还是输出值，默认是输出，值为false即返回<br />
返回格式：登录url<br />
登陆参数为:"qq"."sina"."bd"."gh"."wx"；<br />
解绑参数为:"qqjd"."sinajd"."bdjb"."ghjb"."wxjb"<br />
		</div>
	</div>     

	
</div>