<?
ob_start();
include "lib/header_clean.inc.php";

if (!isset($_GET["cod_tmpl"])) {
	ob_end_clean();
	header("Location: index.php");
	exit();
}
$conteudo = new conteudo();
$conteudos = $conteudo->getConteudosTemplate($_GET["cod_tmpl"]);
?>
<div style="padding: 10px;">
<?
if ($conteudos) {
	foreach ($conteudos as $cont) {
		// Conteudo Texto
		if ($cont["tipo_cotd"] == "1") {
?>
			<div style="font-weight: bold; border-bottom: 1px solid #AAAAAA;"><? echo $cont["titulo_cotd"]; ?></div>
			<div style="margin-bottom:10px;"><? echo nl2br($cont["texto_cotd"]); ?></div>
<?
		} elseif ($cont["tipo_cotd"] == "2") {
			$tmpDadoArq = $cont["arquivo_cotd"];
			$arrDadoArq = explode(".", $tmpDadoArq);
			$extDadoArq = $arrDadoArq[count($arrDadoArq)-1];
			$tamArq = @filesize("./files/".$cont["cod_cotd"].".".$extDadoArq);
			$tamArq = round($tamArq/1024);
?>
			<div style="margin:5px;"><a href="getfile.php?k=<? echo $crypt->encrypt("arquivo=".$cont["cod_cotd"].".".$extDadoArq."&origname=".$cont["arquivo_cotd"]); ?>" target="_blank">&raquo; <? echo $cont["titulo_cotd"]; ?> (<? echo $cont["arquivo_cotd"]." - ".$tamArq."kb"; ?>)</a></div>
<?
		} elseif ($cont["tipo_cotd"] == "3") {
			$tmpDadoArq = $cont["arquivo_cotd"];
			$arrDadoArq = explode(".", $tmpDadoArq);
			$extDadoArq = $arrDadoArq[count($arrDadoArq)-1];
?>
			<div style="margin:5px; text-align: center;"><img src="getimage.php?k=<? echo $crypt->encrypt("img=".$cont["cod_cotd"].".".$extDadoArq); ?>" /><br /><span style="font-size: 10px;"><? echo $cont["titulo_cotd"]; ?></span></div>
<?
		}
	}
}
?>
</div>
<?
include "lib/footer_clean.inc.php";
ob_end_flush();
?>