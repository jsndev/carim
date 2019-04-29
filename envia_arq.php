<?
/*ob_start();*/
include "lib/header.inc.php";

/*
if ($_SESSION["LEVEL_USUA"] != "3") {
	ob_end_clean();
	header("Location: restrita.php");
	exit();
}
ob_end_flush();
*/
if ($_GET["ctarq"] == "1") {
	//Array ( [arquivo] => Array ( [name] => [type] => [tmp_name] => [error] => 4 [size] => 0 ) ) 
	if ($_FILES["arquivo"]["error"] == "0") {
		if (strtolower($_FILES["arquivo"]["name"]) == "cofsp909.athos.sai") {
			@unlink("../athosfiles/COFSP909.ATHOS.SAI");
			copy($_FILES["arquivo"]["tmp_name"], "../athosfiles/COFSP909.ATHOS.SAI");
			$arquivo = new arquivo();
			if (!$arquivo->recLoteCadastro()) {
				$totalLines = ($arquivo->numRows-2);
				$procLines = ($arquivo->procLines);
				$msgSuccess = "Foram processados ".$procLines." registros do total de ".$totalLines;
			} else {
				$msgErr = $arquivo->getErrDesc();
			}
		} else {
			$msgErr = "Arquivo a ser enviado deve ter o nome COFSP909.ATHOS.SAI";
		}
	}
}
?>
<center>
<fieldset style="width:500px;">
	<legend>Envio de arquivo</legend>
<?
if ($msgErr) {
?>
	<div style="color:#FF0000;"><? echo $msgErr; ?></div><br />
<?
}
?>
<?
if ($msgSuccess) {
?>
	<div style="color:#0000FF;"><? echo $msgSuccess; ?></div><br />
<?
}
?>




	Selecione o arquivo a ser enviado ao servidor<br>
	<br>
	<form method="POST" enctype="multipart/form-data" name="formarq" id="formarq" action="envia_arq.php?k=<? echo $crypt->encrypt("ctarq=1"); ?>">
		Arquivo: <input type="file" name="arquivo" id="arquivo" />&nbsp;&nbsp;&nbsp;<input type="submit" value="enviar" />
	</form>
</fieldset>
</center>
<?
include "lib/footer.inc.php";
?>
