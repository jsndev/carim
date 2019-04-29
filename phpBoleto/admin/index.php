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
// @(#) $Id: index.php,v 1.1 2007/01/18 19:43:34 leonardo.kopp Exp $
//
error_reporting(E_ALL);
ini_set("include_path", ".");
include_once("../include/pre.php");
include_once(BOLETO_INC_PATH . "comum.php");
include_once(BOLETO_INC_PATH . "class.ini.php");
$ini = new File_Ini(BOLETO_CONF_PATH . "phpboleto.ini.php", "#");
$inidata = (object) $ini->getBlockValues("Admin Geral");

mostraCabecalho("phpBoleto - Login");
?>
<form method="post" action="login.php">
  <table width="600" border="0" cellspacing="0" cellpadding="5" bgcolor="#003366">
    <tr>
      <td align="center" colspan="2"> 
        <h1><font color="#FFFFFF">phpBoleto<br>
          Login</font></h1>
        <hr>
      </td>
    </tr>
    <?php if (isset($erro)) : ?>
    <tr>
      <td colspan="2" align="center" class="erro">
        <?php echo $inidata->ERRO_SENHA_INCORRETA; ?>
      </td>
    </tr>
    <?php endif; ?>
    <tr>
      <td align="right" width="50%" class="normal"><font color="#FFFFFF">Senha Mestre:</font></td>
      <td width="50%">
        <input type="password" name="senha_mestre" size="12" maxlength="20">
      </td>
    </tr>
    <tr align="center">
      <td colspan="2">
        <input type="submit" name="Submit" value="Login" class="button">
      </td>
    </tr>
  </table>
</form>
<script language="JavaScript">
<!--
this.document.forms[0].senha_mestre.focus();
//-->
</script>
<?php
mostraRodape();
?>