<?
include "../class/dbclasses.class.php";

function getWsMunicipios($uf) {
	$oMunicipio = new municipio();
	$aListaMunicipios = $oMunicipio->getListaMunicipio($uf);
	$sRetorno = '';
	if (@count($aListaMunicipios) > 0) {
		foreach ($aListaMunicipios as $aDadoMunicipio) {
			$sRetorno .= '<MUNICIPIO>';
			$sRetorno .= '	<COD_MUNICIPIO>' . $aDadoMunicipio["cod_municipio"] . '</COD_MUNICIPIO>';
			$sRetorno .= '	<NOME_MUNICIPIO>' . $aDadoMunicipio["nome_municipio"] . '</NOME_MUNICIPIO>';
			$sRetorno .= '</MUNICIPIO>';
		}
	}
	return $sRetorno;
}

function getWsDespachantes($uf,$municipio) {
	$oUsuario = new usuario();
	$aListaDespachantes = $oUsuario->getListaDsespachantes($uf,$municipio);
	$sRetorno = '';
	if (@count($aListaDespachantes) > 0) {
		foreach ($aListaDespachantes as $aDadoDespachante) {
			$sRetorno .= '<DESPACHANTE>';
			$sRetorno .= '	<COD_DESPACHANTE>' . $aDadoDespachante["cod_usua"] . '</COD_DESPACHANTE>';
			$sRetorno .= '	<NOME_DESPACHANTE>' . $aDadoDespachante["nome_usua"] . '</NOME_DESPACHANTE>';
			$sRetorno .= '</DESPACHANTE>';
		}
	}
	return $sRetorno;
}

function getListaMunicipiosDespachante($cod_usua) {
	$oUsuario = new usuario();
	$aMunicipios = $oUsuario->getListaMunicipiosDespachante($cod_usua);
	$sRetorno = '';
	if (@count($aMunicipios) > 0) {
		foreach($aMunicipios as $vMunicipios){
			$sRetorno .= '<MUNICIPIO>';
			$sRetorno .= '	<COD_UF>' . $vMunicipios["cod_uf"] . '</COD_UF>';
			$sRetorno .= '	<COD_MUNICIPIO>' . $vMunicipios["cod_municipio"] . '</COD_MUNICIPIO>';
			$sRetorno .= '	<NOME_MUNICIPIO>' . $vMunicipios["nome_municipio"] . '</NOME_MUNICIPIO>';
			$sRetorno .= '</MUNICIPIO>';
		}
	}
	return $sRetorno;
}

$outBuffer = '';
$outBuffer .= '<RETORNO>';

switch ($_POST["ac"]) {
	case "getMunicipios":
		$outBuffer .= getWsMunicipios($_POST["uf"]);
	break;
	case "getDespachantes":
		$outBuffer .= getWsDespachantes($_POST["uf"],$_POST["municipio"]);
	break;
	case "getMunicipDespach":
		$outBuffer .= getListaMunicipiosDespachante($_POST["cod_usua"]);
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