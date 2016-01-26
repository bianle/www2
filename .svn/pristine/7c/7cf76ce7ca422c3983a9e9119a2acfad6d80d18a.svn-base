<?php
require("www2-funcs.php");

set_fromhost();
cache_header("nocache");

@$id = $_POST["id"];
@$passwd = $_POST["passwd"];
@$kick_multi = $_POST["kick_multi"];
@$mainurl = $_GET["mainurl"];
if ($mainurl!="") $mainurl=urlencode($mainurl);
if ($id=="") error_alert("用户名不能为空");

$ret = bbs_check_ban_ip($id, $fromhost);
switch($ret) {
case 1:
    error_alert("对不起，当前位置不允许登录该ID。");
    break;
case 2:
    error_alert("该 ID 不欢迎来自该 IP 的用户。");
    break;
case 3:
    error_alert("用户密码错误，请重新登录！");
    break;
}

if (($id!="guest")&&bbs_checkpasswd($id,$passwd)!=0) {
    error_alert("用户密码错误，请重新登录！");
}

$error=bbs_wwwlogin(($kick_multi!="") ? 1 : 0, $fromhost, $fullfromhost);
switch($error) {
	case 0:
	case 2:
		//normal
		break;
	case -1:
		prompt_multilogin();
		exit;
	case 3:
		error_alert("本帐号已停机或正在戒网");
	case 5:
		error_alert("登录过于频繁");
	case 1:
		error_alert("对不起，系统忙碌，请稍候再尝试登录");
	default:
		error_alert("登录错误，错误号：" . $error);
}

$data = array();
$num=bbs_getcurrentuinfo($data);

if ($data["userid"] != "guest") {
	$wwwparameters = bbs_getwwwparameters();
	setcookie("WWWPARAMS",$wwwparameters,0,"/");
	$currentuser_num=bbs_getcurrentuser($currentuser);
	
	if(!($currentuser["userlevel"]&BBS_PERM_LOGINOK )) {
		$mainurl = "bbsnew.php";
	}
	$mbids = bbs_bm_get_manageable_bids();
	if ($mbids) {
		setcookie("MANAGEBIDS", $mbids,0,"/");
	}
	header("Set-KBSRC: " . $data["userid"]);
}
setcookie("UTMPKEY",$data["utmpkey"],0,"/");
setcookie("UTMPNUM",$num,0,"/");
setcookie("UTMPUSERID",$data["userid"],0,"/");

$target = "frames.html";

$data["simplepasswd"] = bbs_simplepasswd($passwd);
if ($data["simplepasswd"]) {
	prompt_changepasswd();
	exit;
}

if ($mainurl!="") {
	if (!strcasecmp(substr($mainurl,0,"6"),"atomic"))
		header("Location: ".$mainurl);
	else
		header("Location: $target?mainurl=" . $mainurl);
} else
	header("Location: $target");


function prompt_multilogin() {
	global $id, $passwd, $mainurl;
?>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=gb2312" /></head>
<script type="text/javascript">
function cc() {
	if (confirm("你登录的窗口过多，是否踢出多余的窗口？"))
		document.infoform.submit();
	else
		window.location = "index.html";
}
</script>
<body onload="cc()">
<form name="infoform" action="bbslogin.php?mainurl=<?php echo $mainurl; ?>" method="post">
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="hidden" name="passwd" value="<?php echo $passwd; ?>">
<input type="hidden" name="kick_multi" value="1">
</form> 
</body>
</html>
<?php
} 

function prompt_changepasswd() {
	global $data, $target;
	if ($data["simplepasswd"] == -1) {
		$text = "您的密码由于安全原因被禁止使用，是否立即修改密码？";
		$target = "bbslogout.php";
		$mainurl = "?mainurl=bbspwd.php";
	} else if ($data["simplepasswd"] == 2) {
		$text = "您的密码过于简单，是否立即修改密码？";
		$mainurl = "?mainurl=bbspwd.php";
	} else {
		$text = "您的密码比较简单，请注意保护密码和个人信息。";
		$mainurl = "";
	}
?>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=gb2312" /></head>
<script type="text/javascript">
function cp() {
	if (confirm("<?php echo $text; ?>"))
		document.passwdform.submit();
	else
		window.location = "<?php echo $target; ?>";
}
</script>
<body onload="cp()">
<form name="passwdform" action="frames.html<?php echo $mainurl; ?>" method="post">
</form> 
</body>
</html>
<?php
} 
?>
