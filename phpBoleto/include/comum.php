<?php
// @(#) $Id: comum.php,v 1.1 2007/01/18 19:43:34 leonardo.kopp Exp $
include_once(BOLETO_INC_PATH . "class.ini.php");
$ini = new File_Ini(BOLETO_CONF_PATH . "phpboleto.ini.php", "#");
$inidata = (object) $ini->getBlockValues("Banco de Dados");

// dsn para a conexão ao banco de dados - os valores vem de phpboleto.php
$dsn = array(
    'phptype'  => $inidata->BOLETO_DBTYPE,
    'hostspec' => $inidata->BOLETO_DBHOST,
    'database' => $inidata->BOLETO_DBNAME,
    'username' => $inidata->BOLETO_DBUSER,
    'password' => $inidata->BOLETO_DBPASS
);

function usuario_Autenticado()
{
    global $phpboleto_cookie, $ini;
    $inidata = (object) $ini->getBlockValues("Admin Geral");

    // abra o vetor do cookie
    $cookie = unserialize(base64_decode($phpboleto_cookie));
    if ($cookie["senha_form"] != md5($inidata->PALAVRA_SECRETA . $inidata->SENHA_MESTRE)) {
        return false;
    }

    // checa pelo tempo máximo de login
    if ((time() - $cookie["horario"]) > $inidata->TEMPO_MAXIMO_LOGIN) {
        return false;
    }

    return true;
}

function checaAutenticacao()
{
    if (!usuario_Autenticado()) {
        // deleta o cookie e redireciona o usuário de volta para a página de login
        setcookie("phpboleto_cookie", "");

        header("Location: index.php");
        exit;
    }
}

function rodaSlashes($string)
{
    if (get_magic_quotes_gpc() == 1) {
        return $string;
    } else {
        return addslashes($string);
    }
}

function corLoop($i)
{
    if ($i % 2) {
        return "#CCCCCC";
    } else {
        return "#999999";
    }
}

function inicializar($nome_var, $valor)
{
    if (!isset($GLOBALS[$nome_var])) {
        $GLOBALS[$nome_var] = $valor;
    }
}

function checaErro($objeto)
{
    if (PEAR::isError($objeto)) {
        echo $objeto->getMessage();
        exit;
    }
}

function mostraTitulo($string)
{
?>
<table width="600" border="0" cellspacing="0" cellpadding="5" bgcolor="#003366">
  <tr>
    <td>
      <h2><font color="#FFFFFF">phpBoleto - <?php echo $string; ?></font></h2>
    </td>
  </tr>
</table>
<?php
}

// Funcoes do template da area de administracao
function mostraCabecalho($titulo)
{
    global $ini;
    $inidata = (object) $ini->getBlockValues("Admin Geral");
?>
<html>
<head>
  <title><?php echo $titulo; ?></title>
  <link rel="stylesheet" href="../config/estilo.css" type="text/css">
</head>

<body bgcolor="#FFFFFF">
<table width="600" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%" class="normal">
      Copyright © 2001 <a href="<?php echo $inidata->URL_PROJETO; ?>">Equipe do phpBoleto</a>
    </td>
    <td align="right" width="50%" class="normal">
      <?php echo $inidata->VERSAO; ?>
    </td>
  </tr>
</table>
<br>
<?php
}

function mostraRodape()
{
?>

</body>
</html>
<?php
}

// limpa as variaveis para os outros scripts
unset($ini);
unset($inidata);
?>