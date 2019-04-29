<?php
// se a classe j� foi incluida, n�o deixa incluir de novo.
if (!isset($iCLOGIN_DBCLASS))
	$iCLOGIN_DBCLASS=1;
else
	return 1;

// limpando cache e falando pro brownser nao gravar cache.
//header("Cache-Control: no-cache, must-revalidate"); 
//header("Pragma: no-cache"); 

error_reporting(E_ALL ^ E_NOTICE);

/****************************************************************/
//
//  INCLUS�ES PRINCIPAIS E CONFIGURA��ES DIVERSAS
//
// Variavel com o path base para todo o sistema.
// Dever� ter uma para o ambiente e desenvolvimento e outra para o ambiente de produ��o
define("BASE_PATH", "");

// Define o reposit�rio de classes do sistema
define("CLASS_PATH", BASE_PATH."");

// Define o reposit�rio de classes de neg�cios
define("DATA_PATH", CLASS_PATH."data/");

# tempo para expirar a se��o (login) - em segundos
define("TIME_EXP_LOGIN", "5400");

# flag que define se o sistema � Previ ou n�o
define("FLG_PREVI", true);

# Definicoes sobre o sistema de transferencia de arquivos - PREVI
define ("ATHOSFILEPATH_SAI", "/var/www/previ/remessa/"); // $sArquivo906in e $sArquivo909in
define ("ATHOSFILEPATH_ENT", "/var/www/previ/retorno/"); // $sArquivo906out
define ("ATHOSFILEPATH", BASE_PATH."/var/www/previ/remessa/");

/*
define ("ATHOSFILE909IN", "ATHOSGESTAO.COFSP909.0000011.SAI");
define ("ATHOSFILE906IN", "ATHOSGEST�O.COFSP906.0000011.SAI");
define ("ATHOSFILE906OUT", "ATHOSGEST�O.COFSP906.0000011.ENT");
*/
define ("ATHOSFILE909IN", "cofsp909sai");
define ("ATHOSFILE906IN", "cofsp906sai");
define ("ATHOSFILE906OUT", "cofsp906ent");

# Tipos de Usuarios ( nivel de acesso )
define("TPUSER_PROPONENTE"    , "1");
define("TPUSER_ATENDENTE"	    , "2");
define("TPUSER_ADMPREVI"	    , "3");
define("TPUSER_ADMATHOS"	    , "4");
define("TPUSER_DESPACHANTE"   , "6");
define("TPUSER_JURIDICO"	    , "7");
define("TPUSER_ADMINISTRATIVO", "8");
//define("TPUSER_USUARIOMASTER"	, "9");
define("TPUSER_CONTRATANTE"	, "9");

// diret�rios dos modulos
$aMODULESDIR[0]["dir"]="home";
$aMODULESDIR[0]["aut"]=0;
$aMODULESDIR[1]["dir"]="usuario";
$aMODULESDIR[1]["aut"]=1;

// tipos de usu�rios
// Na classe usuario.class.php h� uma lista de defines 
// contendo estas informa��es.
/* Tipo 5 - Figura do Avaliador - Exclu�do do projeto */
$aTIPOSUSER[1]="Proponente";
$aTIPOSUSER[2]="Atendente";
$aTIPOSUSER[3]="Adm. Previ";
$aTIPOSUSER[4]="Adm. Athos";
$aTIPOSUSER[6]="Despachante";
$aTIPOSUSER[7]="Anal. Jur�dico";
$aTIPOSUSER[8]="Assist. Administrativo";
$aTIPOSUSER[9]="Super User";

// tipos de transa��es (tela)
$aLOG_TRANS[1]="Login";
$aLOG_TRANS[2]="Logout";
$aLOG_TRANS[3]="Simulador";
$aLOG_TRANS[4]="Proposta";

// tipos de logs (opera��o)
$aLOG_OPER[1]="Login";
$aLOG_OPER[2]="Logout";
$aLOG_OPER[3]="Simulador";
$aLOG_OPER[4]="Inclusao";
$aLOG_OPER[5]="Altera��o";
$aLOG_OPER[6]="Conclus�o";
$aLOG_OPER[7]="Hist�rico";

// tipos de situa��o de propostas
$aSIT_PPST[1]="Inten��o de Proposta";
$aSIT_PPST[2]="Montagem de Pasta";
$aSIT_PPST[3]="Avalia��o e An�lise Documental";
$aSIT_PPST[4]="An�lise Jur�dica";
$aSIT_PPST[5]="An�lise Documental";
$aSIT_PPST[6]="Proposta Aprovada";
$aSIT_PPST[7]="Assinatura Agendada";
$aSIT_PPST[8]="Contrato Emitido";
$aSIT_PPST[9]="Registro de Im�vel";
$aSIT_PPST[10]="Parecer Final";
$aSIT_PPST[11]="Finalizada";

// tipos de historico
$aTIP_HIST[1]="Processos do Sistema";
$aTIP_HIST[2]="Evento inserido Manualmente";
$aTIP_HIST[3]="Mensagem enviada pelo FALE CONOSCO";
$aTIP_HIST[4]="Sess�o de Chat"; // n�o � inserido na tabela

// tempo de expiracao do chat (segundos)
$iExpireChat = 7;


$aPROPOSTALISTA[1] = array(1,2,3,4,5,6,7,8,9,10,11);	// Proponente
$aPROPOSTALISTA[2] = array(2,3,4,5,6,7,8,9,10,11);		// Atendente
$aPROPOSTALISTA[3] = array(2,3,4,5,6,7,8,9,10,11);		// Administrador Previ
$aPROPOSTALISTA[4] = array(0);							// Administrador Athos
$aPROPOSTALISTA[5] = array(0);							// N�O UTILIZADO
$aPROPOSTALISTA[6] = array(3);							// Despachante
$aPROPOSTALISTA[7] = array(3,4,5,6,7,8,9,10,11);			// Advogado
$aPROPOSTALISTA[8] = array(1,2,3,4,5,6,7,8,9,10,11);						// Assistente Administrativo
$aPROPOSTALISTA[9] = array(2,3,4,5,6,7,8,9,10,11);		// Super User

$aPROPOSTALISTA[99] = array(2,3,4,5,6,7,8,9,10);

include CLASS_PATH."db.config.php"; // Configura��es do banco de dados
include CLASS_PATH."db.class.php";  // Abstra��o do MySQL

$db = new database();
global $db;

// outras classes
require_once(CLASS_PATH."utils.class.php");
require_once(CLASS_PATH."image.class.php");
require_once(CLASS_PATH."email.class.php");
require_once(CLASS_PATH."session.class.php");
require_once(CLASS_PATH."crypt.class.php");
require_once(CLASS_PATH."manidados.class.php");
require_once(CLASS_PATH."login.class.php");
require_once(CLASS_PATH."mensagens.class.php");
require_once(DATA_PATH."forms.class.php"); // estou trocando esta classe pela "listas"
require_once(DATA_PATH."listas.class.php");
require_once(DATA_PATH."parametros.class.php");

$utils   = new utils();
$listas  = new listas();
$session = new session();
$cLOGIN  = new login;
$forms   = new forms();
$oParametros = new parametros();

//------------------------- autentica��o do usu�rio -----------------------------------------//
// vendo se veio user e senha por post... se veio... o cara t� tentando logar
$cLOGIN->manter_user(); // verifica se o cara logou ou ainda t� logado 

// a partir daqui... tenho uma variavel que define se o cara t� ou n�o logado:
//$cLOGIN->bOK == 1 - logado
//$cLOGIN->bOK == 0 - sem login
//$cLOGIN->iLEVEL_USUA -  nivel do usu�rio

require_once(DATA_PATH."dataaccess.class.php");

// classes de neg�cios
require_once(CLASS_PATH."xml.class.php");
//require_once(DATA_PATH."conteudo.class.php");
//require_once(DATA_PATH."usuario.class.php");
//require_once(DATA_PATH."taxa.class.php");
//require_once(DATA_PATH."regiao.class.php");
//require_once(DATA_PATH."municipio.class.php");
//require_once(DATA_PATH."entidade.class.php");
//require_once(DATA_PATH."documento.class.php");
require_once(DATA_PATH."contrato.class.php");
//require_once(DATA_PATH."proposta.class.php");
require_once(DATA_PATH."agencia.class.php");
//require_once(DATA_PATH."historico.class.php");
		
// classes de neg�cios usadas em "listas.class.php"
require_once(DATA_PATH."ecivil.class.php");
//require_once(DATA_PATH."logradouro.class.php");
//require_once(DATA_PATH."bairro.class.php");
//require_once(DATA_PATH."pais.class.php");
//require_once(DATA_PATH."tipodoc.class.php");
//require_once(DATA_PATH."profissao.class.php");
//require_once(DATA_PATH."cnae.class.php");

//require_once(DATA_PATH."arquivo.class.php");

//--------------- carregando lista de status de propostas -------------------
if(empty($_SESSION["prop_status"])) {
	$_SESSION["prop_status"] = array();
	$_SESSION["prop_status"] = $oParametros->listaStatusProposta();
}

//--------------- carregando lista de indicadores de cancelamento -------------------
if(empty($_SESSION["indic_cancel"])) {
	$_SESSION["indic_cancel"] = array();
	$_SESSION["indic_cancel"] = $oParametros->listaIndicadorCancelamento();
}

//--------------- trabalhando com encripta��o de variaveis de url-------------------
if(empty($_SESSION["fator"])) {
	$fator="1.".rand(1,9299999999);
	$_SESSION["fator"]= $fator;
}
	
if(empty($_SESSION["chave"])) {
	$chave=rand(11000000,99999900);
	$_SESSION["chave"]= $chave;
}

$crypt = new crypt_class($_SESSION["chave"],$_SESSION["fator"]);

if ($_GET["k"]) {
	$crypt->decrypt_array($_GET["k"]);
}


// op��es de restri��o de p�gina..
if($iREQ_AUT>0 and !($cLOGIN->bOK == 1 and in_array($cLOGIN->iLEVEL_USUA,$aUSERS_PERM))){
	header ("Location: restrita.php?k=".$crypt->encrypt(time()));
	exit();
}
?>
