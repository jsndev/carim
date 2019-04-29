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