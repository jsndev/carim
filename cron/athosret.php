#!/opt/downloads/php-4.4.2/sapi/cli/php
<?php
error_reporting(E_ALL);

include "../class/dbclasses.class.php";

$oArquivo = new arquivo();
$oProposta = new proposta();

$aDadosUltimoLote909IN  = $oArquivo->getArquivo(ATHOSFILE909IN);
$aDadosUltimoLote906IN  = $oArquivo->getArquivo(ATHOSFILE906IN);
$aDadosUltimoLote906OUT = $oArquivo->getArquivo(ATHOSFILE906OUT);

$iRemessa909in  = $aDadosUltimoLote909IN[0]["ultimaremessa_arqu"];
$iRemessa906in  = $aDadosUltimoLote906IN[0]["ultimaremessa_arqu"];
$iRemessa906out = $aDadosUltimoLote906OUT[0]["ultimaremessa_arqu"];

"<br>\n\n\n\n\n\n\n\n\n\n";
"--------------------------------------------------------------------------------<br>\n";
"--------------------------------------------------------------------------------<br><br>\n\n";

$sArquivo906in = "ATHOSGESTAO.COFSP906.".str_pad(($iRemessa906in+1),6,"0",STR_PAD_LEFT).".SAI";
"--------------------------------------------------------------------------------<br>\n";
echo "Verificando se existe arquivo ".$sArquivo906in."... <br>\n";
"--------------------------------------------------------------------------------<br><br>\n\n";
echo ATHOSFILEPATH_SAI.$sArquivo906in;
if (@file_exists(ATHOSFILEPATH_SAI.$sArquivo906in)) {

	echo "Arquivo ".$sArquivo906in." encontrado.\n\n";
	echo "Aguarde... Processando arquivo ".$sArquivo906in."...\n<br>";

	$oArquivo->recRetorno($sArquivo906in);
	
	echo "Total linhas.....................: ".$oArquivo->getNumRows()."\n<br>";
	echo "Total de registros processados...: ".$oArquivo->getProcLines()."\n<br>";
	
	$aDadosEstatistica = $oArquivo->get906Estatistica($oArquivo->getNumRemessa());
	
	echo "\nRetorno da remessa...................: ".$oArquivo->getNumRemessa()."\n<br>";

	echo "Qtde de registros enviados com sucesso.: ".$aDadosEstatistica[0]["registrosucesso"]."\n<br>";
	echo "Qtde de registros enviados com erro....: ".$aDadosEstatistica[0]["registroerro"]."\n<br>";
} else {
	echo "Arquivo ".$sArquivo906in." não encontrado.\n\n<br>";
}

$sArquivo909in = "ATHOSGESTAO.COFSP909.".str_pad(($iRemessa909in+1),6,"0",STR_PAD_LEFT).".SAI";
"--------------------------------------------------------------------------------\n<br><br>";
echo "Verificando se existe arquivo ".$sArquivo909in."... \n<br>";
"--------------------------------------------------------------------------------\n\n<br><br>";

if (@file_exists(ATHOSFILEPATH_SAI.$sArquivo909in)) {
	echo "Arquivo ".$sArquivo909in." encontrado.\n\n";
	echo "Aguarde... Processando arquivo ".$sArquivo909in."...\n<br>";
	$oArquivo->recLoteCadastro($sArquivo909in);
	echo "Total linhas:                   ".$oArquivo->getNumRows()."\n<br>";
	echo "Total de registros processados: ".$oArquivo->getProcLines()."\n<br>";
	echo "Total de registros com erro:    ".(($oArquivo->getNumRows()-2)-$oArquivo->getProcLines())."\n\n<br><br>";
	$oArquivo->addLogArquivo(ATHOSFILE909IN,$oArquivo->getNumRemessa(),$oArquivo->getProcLines(),(($oArquivo->getNumRows()-2)-$oArquivo->getProcLines()),$oArquivo->getNumRows(),$oArquivo->getDtRemessa(),file_get_contents(ATHOSFILEPATH_SAI.$sArquivo909in));
} else {
	echo "Arquivo ".$sArquivo909in." não encontrado.\n\n<br><br>";
}

"\n\n\n";
?>
