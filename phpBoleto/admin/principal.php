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
      <b><font color="#FFFFFF">Bem-vindo à Interface de Administração do phpBoleto!</font></b>
      </span>
      <hr>
    </td>
  </tr>
  <tr> 
    <td width="40%">&nbsp;</td>
    <td nowrap class="normal">
      <ul>
        <li><a href="config.php"><font color="#FFFFFF">Configuração Geral do Sistema</font></a></li>
      </ul>
      <ul>
        <li><a href="boletos.php"><font color="#FFFFFF">Administração de Boletos</font></a></li>
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