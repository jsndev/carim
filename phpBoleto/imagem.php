<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | phpBoleto v2.0                                                       |
// +----------------------------------------------------------------------+
// | Copyright (c) 1999-2001 Pablo Martins F. Costa, João Prado Maia      |
// +----------------------------------------------------------------------+
// | Este arquivo está sujeito a versão 2 da GNU General Public License,  |
// | que foi adicionada nesse pacote no arquivo COPYING e está disponível |
// | pela Web em http://www.gnu.org/copyleft/gpl.txt                      |
// | Você deve ter recebido uma cópia da GNU Public License junto com     |
// | esse pacote; se não, escreva para:                                   |
// |                                                                      |
// | Free Software Foundation, Inc.                                       |
// | 59 Temple Place - Suite 330                                          |
// | Boston, MA 02111-1307, USA.                                          |
// +----------------------------------------------------------------------+
// | Autores: João Prado Maia <jpm@phpbrasil.com>                         |
// +----------------------------------------------------------------------+
//
// @(#) $Id: imagem.php,v 1.1 2007/01/18 19:43:34 leonardo.kopp Exp $
//
ini_set("include_path", ".");
@include_once("./include/pre.php");
$img_filename = BOLETO_TEMP_PATH . $HTTP_GET_VARS["img"];
$extensao = substr(strrchr($HTTP_GET_VARS["img"], "."), 1);
if (($extensao == "jpeg") || ($extensao == "jpg")) {
    $mime = "image/jpeg";
} elseif ($extensao == "png") {
    $mime = "image/png";
} elseif ($extensao == "gif") {
    $mime = "image/gif";
}
header("Content-Type: $mime");
header("Content-Length: " . filesize($img_filename));
@readfile($img_filename);
?>