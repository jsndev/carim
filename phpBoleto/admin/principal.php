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
// @(#) $Id: principal.php,v 1.1 2007/01/18 19:43:34 leonardo.kopp Exp $
//
error_reporting(E_ALL);
ini_set("include_path", ".");
include_once("../include/pre.php");
include_once(BOLETO_INC_PATH . "comum.php");
include_once(BOLETO_INC_PATH . "class.ini.php");
$ini = new File_Ini(BOLETO_CONF_PATH . "phpboleto.ini.php", "#");
$inidata = (object) $ini->getBlockValues("Admin Geral");

checaAutenticacao();

mostraCabecalho($inidata->TITULO_ADMIN_NORMAL);
?>
<table width="600" border="0" cellspacing="0" cellpadding="5" bgcolor="#003366">
  <tr> 
    <td align="center" colspan="3"> 
      <h1><font color="#FFFFFF">phpBoleto</font></h1>
      <br>
      <span class="normal">
      <b><font color="#FFFFFF">Bem-vindo � Interface de Administra��o do phpBoleto!</font></b>
      </span>
      <hr>
    </td>
  </tr>
  <tr> 
    <td width="40%">&nbsp;</td>
    <td nowrap class="normal">
      <ul>
        <li><a href="config.php"><font color="#FFFFFF">Configura��o Geral do Sistema</font></a></li>
      </ul>
      <ul>
        <li><a href="boletos.php"><font color="#FFFFFF">Administra��o de Boletos</font></a></li>
      </ul>
      <ul>
        <li><a href="../docs/index.html"><font color="#FFFFFF">Documenta&ccedil;&atilde;o do Sistema</font></a></li>
      </ul>
      <br>
    </td>
    <td width="40%">&nbsp;</td>
  </tr>
</table>
<?php
mostraRodape();
?>