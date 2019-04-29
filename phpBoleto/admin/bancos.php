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
// @(#) $Id: bancos.php,v 1.1 2007/01/18 19:43:34 leonardo.kopp Exp $
//
error_reporting(E_ALL);
ini_set("include_path", ".");
include_once("../include/pre.php");
include_once(BOLETO_INC_PATH . "comum.php");
include_once(BOLETO_INC_PATH . "class.ini.php");
$ini = new File_Ini(BOLETO_CONF_PATH . "phpboleto.ini.php", "#");
$inidata = (object) $ini->getBlockValues("Admin Geral");

checaAutenticacao();

include_once(BOLETO_INC_PATH . "class.db.php");
$db_api = Boleto_DB::conectar($inidata->BOLETO_SISTEMA);

if ((isset($HTTP_POST_VARS["cat"])) && ($HTTP_POST_VARS["cat"] == "deletar")) {
    if (isset($HTTP_POST_VARS["bancos"])) {
        $db_api->deletarBancos($HTTP_POST_VARS["bancos"]);
    }
} elseif ((isset($HTTP_POST_VARS["cat"])) && ($HTTP_POST_VARS["cat"] == "update")) {
    $db_api->atualizarBanco();
} elseif ((isset($HTTP_POST_VARS["cat"])) && ($HTTP_POST_VARS["cat"] == "insert")) {
    $db_api->adicionarBanco();
}

mostraCabecalho($inidata->TITULO_ADMIN_NORMAL);
mostraTitulo("Bancos");
?>
<table width="600" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td class="normal">
      <b>Essa tela contém a lista de bancos disponíveis para uso no phpBoleto. Você pode
      criar novos bancos clicando no botão "Novo Banco" ou editar bancos já existentes 
      clicando no nome do mesmo na lista abaixo. Essas opções serão usadas na criação 
      do boleto com os parâmetros especificados.
      </b>
    </td>
  </tr>
  <tr>
    <td align="center" class="navegacao">
      <hr>
      <a href="principal.php">Menu Principal</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="config.php">Configuração Geral</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="boletos.php">Boletos</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="bancos.php">Bancos</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="configuracoes.php">Configurações Personalizadas</a>
      <hr>
    </td>
  </tr>
<?php
if ((isset($HTTP_GET_VARS["cat"])) && (($HTTP_GET_VARS["cat"] == "novo") || ($HTTP_GET_VARS["cat"] == "modificar"))) {
    if ($HTTP_GET_VARS["cat"] == "modificar") {
        extract($db_api->pegaDadosBanco($HTTP_GET_VARS["bnid"]), EXTR_OVERWRITE);
    }
    inicializar("layout", "");
    inicializar("nome", "");
    inicializar("codigo", "");
    inicializar("uso_do_banco", "");
?>
  <tr>
    <td>
      <script language="JavaScript" src="../include/functions.js"></script>
      <script language="JavaScript">
      <!--
      function checaFormulario()
      {
          var checa = document.banco_form;
          if (isWhitespace(checa.nome.value)) {
              alert("Por favor digite o nome do banco.");
              checa.nome.focus();
              return false;
          }
          if (!isNumberOnly(checa.codigo.value)) {
              alert("Por favor utilize somente números no código do banco.");
              checa.codigo.focus();
              return false;
          }
          checa.submit();
      }
      //-->
      </script>
      <form name="banco_form" method="post" action="<?php echo $PHP_SELF; ?>">
      <?php if ($HTTP_GET_VARS["cat"] == "novo") : ?>
      <input type="hidden" name="cat" value="insert">
      <?php elseif ($HTTP_GET_VARS["cat"] == "modificar") : ?>
      <input type="hidden" name="cat" value="update">
      <input type="hidden" name="bnid" value="<?php echo $HTTP_GET_VARS["bnid"]; ?>">
      <?php endif; ?>
      <table width="100%" border="0" cellpadding="1" cellspacing="0">
        <tr bgcolor="#000000">
          <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="3" bgcolor="#999999">
              <tr> 
                <td nowrap class="normal"><b>Layout:</b></td>
                <td nowrap width="100%"> 
                  <select name="layout">
                    <?php
                    $bancos = $db_api->listaLayouts();
                    reset($bancos);
                    while (list($chave, $valor) = each($bancos)) {
                        if ($layout == $chave) {
                            echo "<option value=\"$chave\" selected>$valor</option>\n";
                        } else {
                            echo "<option value=\"$chave\">$valor</option>\n";
                        }
                    }
                    ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td nowrap class="normal"><b>Nome do Banco:</b></td>
                <td width="100%">
                  <input type="text" name="nome" size="40" maxlength="40" value="<?php echo $nome; ?>">
                </td>
              </tr>
              <tr>
                <td nowrap class="normal"><b>Código:</b></td>
                <td width="100%">
                  <input type="text" name="codigo" size="40" maxlength="40" value="<?php echo $codigo; ?>">
                  <font size="-1">(utilize somente números)</font>
                </td>
              </tr>
              <tr>
                <td nowrap class="normal"><b>Uso do Banco:</b></td>
                <td width="100%">
                  <input type="text" name="uso_do_banco" size="40" maxlength="50" value="<?php echo $uso_do_banco; ?>">
                </td>
              </tr>
              <tr>
                <td nowrap colspan="2">
                  <input type="button" value="Salvar" class="button" onClick="javascript:checaFormulario();">
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      </form>
    </td>
  </tr>
  <tr>
    <td> 
      <hr>
    </td>
  </tr>
<?php
}
?>
  <tr>
    <td>
      <script language="JavaScript">
      <!--
      function checaRemocao()
      {
          var checa = document.deleta_form;
          if (confirm("Essa opção irá remover as emissões selecionadas.")) {
              checa.submit();
          } else {
              return false;
          }
      }
      //-->
      </script>
      <form name="deleta_form" method="post" action="<?php echo $PHP_SELF; ?>">
      <input type="hidden" name="cat" value="deletar">
      <table width="100%" border="0" cellspacing="0" cellpadding="3">
        <tr bgcolor="#CCCCCC">
          <td colspan="3">
            <h4>Lista de Bancos</h4>
          </td>
        </tr>
        <tr>
          <td width="5" nowrap>&nbsp;</td>
          <td nowrap class="normal"><b>Nome do Banco</b></td>
          <td width="60%" class="normal"><b>Código</b></td>
        </tr>
<?php
$bancos = $db_api->listaBancos();
for ($i = 0; $i < count($bancos); $i++) {
?>
        <tr bgcolor="<?php echo corLoop($i); ?>">
          <td width="5" nowrap><input type="checkbox" name="bancos[]" value="<?php echo $bancos[$i]["bnid"]; ?>"></td>
          <td nowrap class="normal"><a href="<?php echo $PHP_SELF; ?>?cat=modificar&bnid=<?php echo $bancos[$i]["bnid"]; ?>"><?php echo $bancos[$i]["nome"]; ?></a></td>
          <td width="60%" class="normal"><?php echo htmlspecialchars(stripslashes($bancos[$i]["codigo"])); ?></td>
        </tr>
<?php
}
?>
        <tr>
          <td colspan="3">
            <hr>
          </td>
        </tr>
        <tr>
          <td colspan="3">
            <input type="button" value="Novo Banco" class="button" onClick="javascript:location.href='<?php echo $PHP_SELF; ?>?cat=novo';">
            &nbsp; 
            <input type="button" value="Deletar" class="button" onClick="javascript:checaRemocao();">
          </td>
        </tr>
      </table>
      </form>
    </td>
  </tr>
</table>
<?php
mostraRodape();
?>