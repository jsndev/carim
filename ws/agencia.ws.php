<?
include "../class/dbclasses.class.php";

function getAgenciaNome($ag) {
	$oAgencia = new agenciabb();
	$aListaAgencias = $oAgencia->pesquisarPk($ag);
	$sRetorno = '';
	if (@count($aListaAgencias) > 0) {
		foreach ($aListaAgencias as $aDadoAgencia) {
			$sRetorno .= '<AGENCIA>';
			$sRetorno .= '	<COD_AGENCIA>' . $aDadoAgencia["cod_agbb"] . '</COD_AGENCIA>';
			$sRetorno .= '	<NOME_AGENCIA>' . $aDadoAgencia["nome_agbb"] . '</NOME_AGENCIA>';
			$sRetorno .= '</AGENCIA>';
		}
	}
	return $sRetorno;
}

$outBuffer = '';
$outBuffer .= '<RETORNO>';

switch ($_POST["ac"]) {
	case "getAgenciaNome":
		$outBuffer .= getAgenciaNome($_POST["ag"]);
	break;
	default:
		$outBuffer .= '<ERROR>Invalid request.</ERROR>';
	break;
}

$outBuffer .= '</RETORNO>';

$oXml = new xml();
$oXml->setXmlData($outBuffer);
$oXml->send();
?>