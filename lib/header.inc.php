<?
// incluindo arquivo de configuração e classes
include_once "./class/dbclasses.class.php";
//------------------------------------------------------------------------------------------//
header("Content-Type: text/html; charset=iso-8859-1");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Contrathos</title>
	<link href="css/style.css" rel="stylesheet" type="text/css" />
	<link href="css/dtree.css" rel="stylesheet" type="text/css" />
	<script src="js/script-v1.js" type="text/javascript"></script>
	<script src="js/dtree.js" type="text/javascript"></script>
		<script src="js/ajax.js" type="text/javascript"></script>

</head>
<body>
<div id="mainContainer">
	<div id="lTopo"><img src="images/topo.jpg" alt=" " /></div>
	<div id="mainOuterContent">
		<div id="topPage">
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
		</div>
		<div id="mainContent">
<?/* Não retire.. É referência para uso futuro... Paulo
			<div class="quadroInterno">
				<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
				<p>
					teste de texto
				</p>
				<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
			</div>
			<div class="separadorQuadros"><img src="images/layout/transp.gif" alt=" " /></div>
			<div class="quadroInterno">
				<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
				<p>
					teste de texto
				</p>
				<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
			</div>
*/?>