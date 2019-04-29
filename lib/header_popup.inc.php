<?
// incluindo arquivo de configuração e classes
include "./class/dbclasses.class.php";
//------------------------------------------------------------------------------------------//
header("Content-Type: text/html; charset=iso-8859-1");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Contrathos</title>
	<link href="css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="mainContainerPop">
	<div><img src="images/layout/popsubquadro_t.gif" alt=" " /></div>
	<div class="quadroInternoPop">
<?
if ($pageTitle) {
?>
			<p id="labelLocal"><? echo $pageTitle; ?></p>
<?
} else {
?>
			&nbsp;
<?
}
?>
