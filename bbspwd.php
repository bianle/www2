<?php
	require("www2-funcs.php");
	login_init();
	toolbox_header("密码修改");
	assert_login();

	if (isset($_GET['do'])) {
		$pass = $_POST['pw2'];
		if (strlen($pass) < 4 || strlen($pass) > 39)
			html_error_quit("新密码长度应为 4～39");
		if ($pass != $_POST['pw3'])
			html_error_quit("两次输入的密码不相同");
		if (bbs_checkuserpasswd($currentuser["userid"],$_POST['pw1']) != 0)
			html_error_quit("密码不正确");
		$simplepasswd = bbs_simplepasswd($pass);
		if ($simplepasswd == -1)
			html_error_quit("该密码被禁止使用，请重新设置密码");
		else if ($simplepasswd)
			prompt_setpasswd();
		if (!bbs_setpassword($currentuser["userid"],$pass))
			html_error_quit("系统错误，请联系管理员");
		html_success_quit("密码修改成功，您的新密码已设定");
		exit;
	}
?>
<script type="text/javascript">
function DoPwd()
{
	if(getObj('pwd2').value != getObj('pwd3').value) {
		alert('两次输入的密码不相同');
		getObj('pwd3').focus();
		return false;
	}
	return true;
}
</script>
<form action="bbspwd.php?do" method="post" class="small" onsubmit="return DoPwd();">
	<fieldset><legend>修改密码</legend>
		<div class="inputs">
			<label>您的旧密码:</label><input maxlength="39" size="12" type="password" name="pw1" id="sfocus"/><br/>
			<label>您的新密码:</label><input maxlength="39" size="12" type="password" name="pw2" id="pwd2"/><br/>
			<label>再输入一次:</label><input maxlength="39" size="12" type="password" name="pw3" id="pwd3"/>
		</div>
	</fieldset>
	<div class="oper"><input type="submit" value="确定修改"></div>
</form>
<?php
	function prompt_setpasswd() {
	global $simplepasswd;
	if ($simplepasswd == 2) {
		$text = "您的新密码过于简单，是否重新设置密码？";
		$target = "bbspwd.php";
	} else {
		$text = "您的新密码比较简单，请注意保护密码和个人信息。";
	}
?>  
<html>  
<head><meta http-equiv="Content-Type" content="text/html; charset=gb2312" /></head>
<script type="text/javascript">
function sp() {
<?php
	if ($simplepasswd == 2) {
?>
		if (confirm("<?php echo $text; ?>"))
			document.passwdform.submit();
<?php
	} else {
?>
		alert("<?php echo $text; ?>");
<?php
	}
?>
}   
</script>
<body onload="sp()">
<form name="passwdform" action="<?php echo $target; ?>" method="post">
</form> 
</body>
</html>
<?php
	}
	page_footer();
?>
