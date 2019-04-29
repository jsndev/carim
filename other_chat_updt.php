<?
	// incluindo arquivo de configuração e classes
	include "./class/dbclasses.class.php";

	if(!isset($_SESSION['CODCHTU'])){
		print 'USUARIO NAO LOGADO';
		exit();
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<style>
	* { font-family: Verdana; font-size:7pt; }
	body { margin:0px; padding:1px; }
	</style>
</head>
<body>
<?
	if($_SESSION['CHAT']!=''){
			$db->query="UPDATE chatsessoes SET DT_USUA=now() WHERE COD_CHAT='".mysql_real_escape_string($_SESSION['CHAT'])."'";
			$db->query();
			//print $db->query.'<hr>';
	}
	print $_SESSION['CHAT'].' <b>UPDATE:</b> '.microtime();
?>
</body>
</html>