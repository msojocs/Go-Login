<?php
//登录界面
function wx_login(){
?>
<style>
@media all{
    :after,:before{box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;}
    a{color:#00a67c;text-decoration:none;}
    a:focus,a:hover{color:#007046;text-decoration:none;}
    ::selection{background:#72d0eb;color:#fff;text-shadow:none;}
    ::-moz-selection{background:#72d0eb;color:#fff;text-shadow:none;}
    .article-content a{color:#00a67c;}
    .article-content a:hover{color:#d9534f;}
    .lhb{display:inline-block;margin:5px 0 10px 15px;padding:5px 50px;border:1px solid #4094ef;box-shadow:0 1px 3px rgba(0,0,0,.1);color:#4094ef!important;font-size:1.1875rem;font-family:"Microsoft YaHei";}
    .lhb:hover{border-color:#fff;background:#4094ef;color:#fff!important;transition-duration:.3s;}
    
    .pull-center{text-align:center;}
    #sc_error{overflow:hidden;margin:10px 0;padding:15px 15px 15px 35px;border:1px solid #ebb1b1;background:#ffecea url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAaCAMAAAHpk4xqAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAJZQTFRFzGZmzWpqzmtrzmxszm1tz3Bw0HFx0HJy0HNz1H9/1YCA1YGB1oOD1oSE4KGh4KKi4aSk4aam5K2t5K+v6Lu76by86b296b6+6r+/6sDA6sHB67Gx68LC8dbW89vb89zc9N3d9uXl9+bm9+fn+Onp+Ovr+e7u+u/v+vDw+vHx/Pb2/fj4/fn5/vv7/vz8/v39//7+////tYx/FQAAAPJJREFUKM+lkNt2gjAQRbcoAXqlLSg18UKpoLY2mf//uT5ESrT1oct5ydrZJzNJ0Fpr9FqBTgENoLXG1z30m341WNDwdetzQxRmIMCuMgAWWoCy/gkwZHuqpLMuOpIA8Dj15ACYNJ5GkhO3qz+6hPWr/xlF1nYy70kSYGk8FU8AiKc9ypgXNp5qxfFJGsg6gMQduyy2ilzG/bys+SgvTA/olP+tsuLtcGjKm3M1Wsg2VxDnrazGocpsl/bptHV3gaqdGoZM7Hug9juIX40x5hnYfAaqkHw49SDT8BozWSdeqKWY08tHlRPbdVbcPLryNy7WN8NDGl8qJ1gWAAAAAElFTkSuQmCC) -1px -1px no-repeat;color:#c66;}
    #sc_notice{overflow:hidden;margin:10px 0;padding:15px 15px 15px 35px;border:1px solid #aac66d;background:#ecf2d6 url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAaCAMAAAHpk4xqAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAKVQTFRFqsZtvNaEvdaFvteHvteIv9iJv9iKwNmMwdmNwdmOwtmOwtqPw9qQw9qRx92YyN2ZyN2ayN6byd6byd6c0eOq1ua01+e22Oe33uvD4e3I4u3J4u3K4+7M5O7N5O/O6PHW7fTe7/Xi7/bj8Pbk8/jp8/jq9Pjq9Pjr9Pns9fns9fnt9vrv+Prx+Pvy+Pvz+fvz+vz1+/34/P36/f78/v79/v/+////1tsuPQAAAQJJREFUKM+lkN1SwjAQRveUAqVqFaVVKFB+RBFaEEj2/R/Ni1AmqJ1xxu8mc3I2uzsRERGRZYRIDCKAiLiT4MkxUJ+BQQTMw7lOLpYcFGHfVxAslAiM3sBvcIkIMNbK2vBMCnCT5o4sHxrQWTtqaUa3XPzS5So/BlxTaGylk5q0B8ynjobPwAB1dGBAWrB1tIpuDwUYR0lFF3rnSma7iEzb9bxkfRw1TPeXpiF/U8nw/XRaj+6/q9ZMd1kE3azURdtXia3iujou9dFTKxu5+1Rf6JiNpw57eP3sp1oEwPboqaFmcLcpAvcy99fIddlzLaO5Tq+XD8dWTVUZtZPwn7/RmC+jnBV6oPkZWgAAAABJRU5ErkJggg==) -1px -1px no-repeat;color:#7da33c;}
}
</style>
<div class="article-content" style="height:600px">
    <p class="pull-center"><a class="lhb" href="javascript:void(0);" onclick="qr_gen();notice();">重新获取</a></p>
    <div class="pull-center" id="goauth_qr"><div id="sc_notice" class="pull-center">获取中......请使用微信扫码登录，点击授权应用</div></div><span style="display:none" id="ssk"></span>
</div>
<script src="https://cdn.staticfile.org/sweetalert/2.1.2/sweetalert.min.js"></script>
<script type="text/javascript">
qr_gen();

function notice(){
document.getElementById('goauth_qr').innerHTML = '<div id="sc_notice" class="pull-center">重新获取中......</div>';}

function goauthok(k) {
    if(k == 101)
    {
        //返回101表明小程序码过期
        clearTimeout(timeres);
        document.getElementById("goauth_qr").innerHTML = "<div class=\"pull-center\"><div id=\"sc_error\" class=\"pull-center\">哎呀，小程序码过期了呢！</div></div><svg class=\"icon\" viewBox=\"0 0 1289 1024\" xmlns=\"http://www.w3.org/2000/svg\" width=\"300\" height=\"300\"><path d=\"M758.426.58c-22.753 0-75.843 0-75.843 36.557s53.09 36.556 75.843 36.556h379.212c53.09 0 75.843 21.918 75.843 73.074v584.86l-310.955-211.98a37.618 37.618 0 00-53.09 0l-310.954 211.98-204.775-153.505c-15.168-14.638-37.921-14.638-60.674 0L75.843 731.589V146.767c0-51.156 22.752-73.074 75.842-73.074h379.213c22.753 0 75.842 7.319 75.842-36.556S561.235.58 538.482.58H151.685C53.09.58 0 51.774 0 146.767v731.046C0 972.806 60.674 1024 159.27 1024h401.965c22.753 0 45.505 0 45.505-36.556s-22.752-36.556-45.505-36.556h-409.55c-53.09 0-75.842-21.919-75.842-73.075v-58.512L303.37 643.877l204.775 160.786c15.169 14.638 37.921 14.638 60.674 0l310.955-219.26L1213.48 819.3v58.512c0 51.156-22.753 73.075-75.843 73.075H735.673c-22.753 0-53.09 0-53.09 36.556S712.92 1024 735.673 1024h401.965c98.596 0 151.685-51.194 151.685-146.187V154.086c0-95.03-53.09-153.505-151.685-153.505H758.426zM553.65 373.386c-22.753 7.318-30.337 29.237-15.169 51.193 15.169 21.919 53.09 58.475 106.18 58.475s98.595-36.556 106.18-58.475c15.168-14.637 7.584-43.875-15.17-51.193-15.168-14.6-45.505-7.281-53.089 14.637 0 0-15.169 21.919-45.506 21.919-22.752 0-37.92-21.919-45.505-21.919 0-21.918-22.753-29.237-37.921-14.637zm-60.674-153.506c-22.753 0-37.922 21.957-37.922 51.194v51.156c0 21.918 15.169 43.875 37.922 43.875s37.92-21.957 37.92-51.194v-43.837c0-29.237-15.168-51.194-37.92-51.194zm303.37 146.225c22.753 0 37.921-21.957 37.921-51.194v-43.837c0-29.275-15.168-51.194-37.921-51.194s-37.921 21.919-37.921 51.194v51.156c0 21.918 15.168 43.875 37.92 43.875z\" fill=\"#52698A\"/></svg>";
    }else if (k != 0) {
		clearTimeout(timeres);
		swal("微信登录成功！", "您以后都可以使用微信登录网站", "success").then((value)=> {
		    k = encodeURIComponent(k);
			window.location.href = "?goauth_login=" + k + "";
		});
	}
}

function qr_gen() {
	var a = new XMLHttpRequest(),qrdiv = document.getElementById("goauth_qr"),ssk = document.getElementById("ssk");
	a.open("POST", "<?php echo admin_url('admin-ajax.php');?>");
	a.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	a.send("action=Gologin_goauth_qr_gen&wastart=1");
	a.onreadystatechange = function() {
		if (a.readyState == 4 && a.status == 200) {
            var ss = a.responseText.split("|");
            qrdiv.innerHTML = "<img style=\"width:300px;height:300px\" src="+ss[1].replace("&quot;","")+">";
            ssk.innerHTML = ss[0];
            //开始检测授权情况
            setTimeout(timecheck, 3*1000);
		}
	}
}

function goauth_check() {
      var ssk = document.getElementById("ssk").innerHTML;
      ssk = encodeURIComponent(ssk);
      if (typeof(ssk) != "undefined" && ssk != null && ssk != "" && ssk.length != 0) {
	      var a = new XMLHttpRequest();
	      a.open("POST", "<?php echo admin_url('admin-ajax.php');?>");
	      a.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	      a.send("action=Gologin_goauth_check&sk=" + ssk);
	      a.onreadystatechange = function() {
		      if (a.readyState == 4 && a.status == 200) {
			      goauthok(a.responseText);
		    }
	   }
  }
}
  
function timecheck(){
	timeres = setTimeout(timecheck,3*1000);
	goauth_check();
}
</script>
<?php } ?>