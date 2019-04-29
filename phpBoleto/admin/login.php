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
// @(#) $Id: login.php,v 1.1 2007/01/18 19:43:34 leonardo.kopp Exp $
//
error_reporting(E_ALL);
ini_set("include_path", ".");
include_once("../include/pre.php");
include_once(BOLETO_INC_PATH . "comum.php");
include_once(BOLETO_INC_PATH . "class.ini.php");
$ini = new File_Ini(BOLETO_CONF_PATH . "phpboleto.ini.php", "#");
$inidata = (object) $ini->getBlockValues("Admin Geral");

// checa se a senha mestre confere com a da configuracao do sistema
if (isset($HTTP_POST_VARS["senha_mestre"])) {
    $senha_form = md5($HTTP_POST_VARS["senha_mestre"]);

    if ($senha_form != $inidata->SENHA_MESTRE) {
        header("Location: index.php?erro=1");
        exit;
    } else {
        // cria o cookie de autenticacao
        $cookie = array(
          "autenticado" => "sim",
          "horario"     => time(),
          "senha_form"  => md5($inidata->PALAVRA_SECRETA . $senha_form)
        );
        $cookie = base64_encode(serialize($cookie));
        setcookie("phpboleto_cookie", $cookie);

        // redireciona o usuario
        header("Location: principal.php");
        exit;
    }
}
?>