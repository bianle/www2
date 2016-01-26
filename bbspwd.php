<?php
	require("www2-funcs.php");
	login_init();
	toolbox_header("�����޸�");
	assert_login();

	if (isset($_GET['do'])) {
		$pass = $_POST['pw2'];
		if (strlen($pass) < 4 || strlen($pass) > 39)
			html_error_quit("�����볤��ӦΪ 4��39");
		if ($pass != $_POST['pw3'])
			html_error_quit("������������벻��ͬ");
		if (bbs_checkuserpasswd($currentuser["userid"],$_POST['pw1']) != 0)
			html_error_quit("���벻��ȷ");
		$simplepasswd = bbs_simplepasswd($pass);
		if ($simplepasswd == -1)
			html_error_quit("�����뱻��ֹʹ�ã���������������");
		else if ($simplepasswd)
			prompt_setpasswd();
		if (!bbs_setpassword($currentuser["userid"],$pass))
			html_error_quit("ϵͳ��������ϵ����Ա");
		html_success_quit("�����޸ĳɹ����������������趨");
		exit;
	}
?>
<script type="text/javascript">
function DoPwd()
{
	if(getObj('pwd2').value != getObj('pwd3').value) {
		alert('������������벻��ͬ');
		getObj('pwd3').focus();
		return false;
	}
	return true;
}
</script>
<form action="bbspwd.php?do" method="post" class="small" onsubmit="return DoPwd();">
	<fieldset><legend>�޸�����</legend>
		<div class="inputs">
			<label>���ľ�����:</label><input maxlength="39" size="12" type="password" name="pw1" id="sfocus"/><br/>
			<label>����������:</label><input maxlength="39" size="12" type="password" name="pw2" id="pwd2"/><br/>
			<label>������һ��:</label><input maxlength="39" size="12" type="password" name="pw3" id="pwd3"/>
		</div>
	</fieldset>
	<div class="oper"><input type="submit" value="ȷ���޸�"></div>
</form>
<?php
	function prompt_setpasswd() {
	global $simplepasswd;
	if ($simplepasswd == 2) {
		$text = "������������ڼ򵥣��Ƿ������������룿";
		$target = "bbspwd.php";
	} else {
		$text = "����������Ƚϼ򵥣���ע�Ᵽ������͸�����Ϣ��";
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
