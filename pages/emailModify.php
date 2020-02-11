<style>
    dt {
        font-weight:bold
    }
    .dl-horizontal dt {
        float:left;
        width:60px;
        clear:left;
        text-align:right;
        overflow:hidden;
        text-overflow:ellipsis;
        white-space:nowrap
    }
    .dl-horizontal dd {
        margin-left:80px
    }
    .profile-form dl {
        margin: 0 0 10px 0;
    }
    .profile-form dd, .profile-form dt {
        line-height: 30px;
    }
    .profile-form textarea {
        width: 60%;
    }

    #sc_notice {
        overflow:hidden;
        margin:10px 0;
        padding:15px 15px 15px 35px;
        border:1px solid #aac66d;
        background:#ecf2d6 url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAaCAMAAAHpk4xqAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAKVQTFRFqsZtvNaEvdaFvteHvteIv9iJv9iKwNmMwdmNwdmOwtmOwtqPw9qQw9qRx92YyN2ZyN2ayN6byd6byd6c0eOq1ua01+e22Oe33uvD4e3I4u3J4u3K4+7M5O7N5O/O6PHW7fTe7/Xi7/bj8Pbk8/jp8/jq9Pjq9Pjr9Pns9fns9fnt9vrv+Prx+Pvy+Pvz+fvz+vz1+/34/P36/f78/v79/v/+////1tsuPQAAAQJJREFUKM+lkN1SwjAQRveUAqVqFaVVKFB+RBFaEEj2/R/Ni1AmqJ1xxu8mc3I2uzsRERGRZYRIDCKAiLiT4MkxUJ+BQQTMw7lOLpYcFGHfVxAslAiM3sBvcIkIMNbK2vBMCnCT5o4sHxrQWTtqaUa3XPzS5So/BlxTaGylk5q0B8ynjobPwAB1dGBAWrB1tIpuDwUYR0lFF3rnSma7iEzb9bxkfRw1TPeXpiF/U8nw/XRaj+6/q9ZMd1kE3azURdtXia3iujou9dFTKxu5+1Rf6JiNpw57eP3sp1oEwPboqaFmcLcpAvcy99fIddlzLaO5Tq+XD8dWTVUZtZPwn7/RmC+jnBV6oPkZWgAAAABJRU5ErkJggg==) -1px -1px no-repeat;
        color:#7da33c;
    }
    .pull-center {
        text-align: center;
    }
</style>
<div id="infocenter">
    <?php
    global $userdata, $wp_http_referer;
    wp_get_current_user();

    if (!(function_exists('get_user_to_edit'))) {
        require_once(ABSPATH . '/wp-admin/includes/user.php');
    }

    if (!(function_exists('_wp_get_user_contactmethods'))) {
        require_once(ABSPATH . '/wp-includes/registration.php');
    }

    if (!$user_id) {
        $current_user = wp_get_current_user();
        $user_id = $user_ID = $current_user->ID;
    }

    $profileuser = get_user_to_edit($user_id);
    ?>
    <div id="sc_notice" class="pull-center">
        哎呀！邮箱是空的呢<img src="https://www.jysafe.cn/assets/images/smilies/啊.png" alt="#啊" class="wp-smiley" style="height: 1em; max-height: 1em;">为了方便收到回复，我们墙裂推荐绑定邮箱
    </div>
    <div class="profile-form">
        <form action="" method="post">
            <dl class="dl-horizontal">
                <dt>邮箱</dt>
                <dd>
                    <input type="text" class="profile-input" style="height:30px;" id="mm_mail" name="mm_mail" value="<?php echo esc_attr($profileuser->user_email) ?>">
                </dd>
            </dl>

            <dl class="dl-horizontal">
                <dt></dt>
                <dd>
                    <input type="button" id="doprofile" value="保存" style="border:none;padding:5px 15px;font-size:16px;text-align:center;background:#F04243;color:#FFFFFF" />
                </dd>
            </dl>
        </form>
    </div>
</div>
<script type="text/javascript" src="/wp-includes/js/jquery/jquery.js"></script>
<script type="text/javascript" src="https://cdn.staticfile.org/sweetalert/2.1.2/sweetalert.min.js"></script>

<script type="text/javascript">
    swal({
        title: "哎呀！邮箱是空的呢！",
        text: "为了方便阁下及时收到回复信息，建议阁下填写邮箱哦！",
        icon: 'warning',
        buttons: {
            confirm: "我要填写"
        }
    }).then(function(isConfirm) {
        if (isConfirm) {
            swal("注意", "邮箱要真实可用哦！", 'success');
        }
    });

    jQuery(document).ready(function($) {
        $("#doprofile").click(function() {
            var reg = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/;
            if (!reg.test($("#mm_mail").val().trim())) {
                alert("请输入正确邮箱，以免忘记密码时无法找回");
            } else
            {
                $("#doprofile").val("保存中...");
                $("#doprofile").css("width", "80px");
                $.ajax({
                    type: "post",
                    /*async: false,*/
                    url: "<?php echo admin_url('admin-ajax.php');
                    ?>",
                    data: "action=go_login_do_profile&mm_mail=" + $("#mm_mail").val(),
                    // contentType: "application/x-www-form-urlencoded",
                    dataType: "text",
                    success: function (data) {
                        switch (data) {
                            case 'success':
                                $("#doprofile").val("保存");
                                $("#doprofile").css("width", "60px");
                                alert("修改成功");
                                window.opener.location.href = "/";
                                break;
                            case 'emailinvalid':
                                alert("邮件地址无效<_<");
                                $("#doprofile").val("保存");
                                break;
                            case 'ycz':
                                alert('邮箱已经被抢走了>_<');
                                $("#doprofile").val("保存");
                                break;
                            case 'error':
                                alert('系统智障了~');
                                $("#doprofile").val("保存");
                                break;
                        }
                    },
                    error: function () {
                        $("#doprofile").val("保存");
                        $("#doprofile").css("width", "60px");
                        alert("系统智障了~");
                    }
                });
            }
        });

    });

</script>