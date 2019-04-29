<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | phpBoleto v2.0                                                       |
// +----------------------------------------------------------------------+
// | Copyright (c) 1999-2001 Pablo Martins F. Costa, Jo�o Prado Maia      |
// +----------------------------------------------------------------------+
// | Este arquivo est� sujeito a vers�o 2 da GNU General Public License,  |
// | que foi adicionada nesse pacote no arquivo COPYING e est� dispon�vel |
// | pela Web em http://www.gnu.org/copyleft/gpl.txt                      |
// | Voc� deve ter recebido uma c�pia da GNU Public License junto com     |
// | esse pacote; se n�o, escreva para:                                   |
// |                                                                      |
// | Free Software Foundation, Inc.                                       |
// | 59 Temple Place - Suite 330                                          |
// | Boston, MA 02111-1307, USA.                                          |
// +----------------------------------------------------------------------+
// | Autores: Jo�o Prado Maia <jpm@phpbrasil.com>                         |
// +----------------------------------------------------------------------+
//
// @(#) $Id: pre.php,v 1.1 2007/01/18 19:43:34 leonardo.kopp Exp $
//

if (stristr(PHP_OS, "win")) {
    define("BOLETO_SEPARADOR", "\\");
} else {
    define("BOLETO_SEPARADOR", "/");
}

// **************************************************************************
// ATEN��O - Modifique na pr�xima linha o caminho da instala��o do phpBoleto 
// **************************************************************************
// Vers�o para UNIXes (remova os '//' da pr�xima linha)
define("BOLETO_PATH", "/phpBoleto/");
// Vers�o para Windows (remova os '//' da pr�xima linha)
//define("BOLETO_PATH", "c:\\www\\htdocs\\site.com.br\\docs\\phpBoleto");
// **************************************************************************
// ATEN��O - Modifique a pr�xima linha para setar a URL relativa do phpBoleto
//           no seu site (ex: coloque "/admin/phpBoleto/" se a URL para o 
//           mesmo � "http://www.site.com.br/admin/phpBoleto/")
// **************************************************************************
define("BOLETO_URL", "/phpBoleto/");
// ************************************************
// N�o � necess�rio mexer com o resto desse script!
// ************************************************

if (!defined("BOLETO_PATH")) {
    exit("Erro: Edite o caminho do phpBoleto no arquivo 'pre.php' encontrado no diret�rio 'include' da instala��o do phpBoleto.");
}
if (!defined("BOLETO_URL")) {
    exit("Erro: Edite a URL do phpBoleto no arquivo 'pre.php' encontrado no diret�rio 'include' da instala��o do phpBoleto.");
}


// ************************************************
// N�o � necess�rio mexer com o resto desse script!
// ************************************************
define("BOLETO_INC_PATH", BOLETO_PATH . BOLETO_SEPARADOR . "include" . BOLETO_SEPARADOR);
define("BOLETO_CONF_PATH", BOLETO_PATH . BOLETO_SEPARADOR . "config" . BOLETO_SEPARADOR);
define("BOLETO_FONT_PATH", BOLETO_PATH . BOLETO_SEPARADOR . "fonts" . BOLETO_SEPARADOR);
define("BOLETO_IMAGE_PATH", BOLETO_PATH . BOLETO_SEPARADOR . "imagens" . BOLETO_SEPARADOR);
define("BOLETO_TEMP_PATH", BOLETO_PATH . BOLETO_SEPARADOR . "temp" . BOLETO_SEPARADOR);

define("BOLETO_IMAGE_URL", BOLETO_URL . "imagens/");

// caminho completo do arquivo de log de erros
define("BOLETO_ERRORLOG_PATH", BOLETO_CONF_PATH . "log_de_erros.txt");
define("BOLETO_NOTIFICAR_ERRO", false);
// adicione aqui quantos emails quiser, separados por espa�os
define("BOLETO_NOTIFICAR_LISTA", "jpm@impleo.net");

// define o caminho para as bibliotecas PEAR distribu�das junto com o phpBoleto
$pear_dir = BOLETO_INC_PATH . "pear";
if (stristr(PHP_OS, 'WIN')) {
    $separador = ";";
} else {
    $separador = ":";
}
if (defined("PHP_INCLUDE_PATH")) {
    @ini_set("include_path", "." . $separador . $pear_dir . $separador . PHP_INCLUDE_PATH);
} else {
    @ini_set("include_path", "." . $separador . $pear_dir);
}
unset($separador);
?>