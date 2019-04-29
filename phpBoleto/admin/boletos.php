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
// @(#) $Id: boletos.php,v 1.1 2007/01/18 19:43:34 leonardo.kopp Exp $
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
    if (isset($HTTP_POST_VARS["boletos"])) {
        $db_api->deletarBoletos($HTTP_POST_VARS["boletos"]);
    }
} elseif ((isset($HTTP_POST_VARS["cat"])) && ($HTTP_POST_VARS["cat"] == "update")) {
    $db_api->atualizarBoleto();
} elseif ((isset($HTTP_POST_VARS["cat"])) && ($HTTP_POST_VARS["cat"] == "insert")) {
    $db_api->adicionarBoleto();
}

mostraCabecalho($inidata->TITULO_ADMIN_NORMAL);
mostraTitulo("Boletos");
?>
<table width="600" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td class="normal">
      <b>Essa tela mostra a lista de modelos de boletos disponíveis para uso pelo 
      phpBoleto. As opções usadas nessa tela irão afetar diretamente a geração do
      boleto, então verifique as suas mudanças clicando no link "Revisar Boleto"
      abaixo. O link "Gerar Templates" serve para gerar código PHP que será usado
      para chamar as funções do phpBoleto e criar o modelo de boleto especificado.
      <br><br>
      Os modelos de boletos listados aqui são conectados aos dados de Bancos e 
      Configurações Personalizadas, pois eles definem como o modelo de boleto
      deve ser gerado e outras opções de design do mesmo.
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
        extract($db_api->pegaDadosBoleto($HTTP_GET_VARS["bid"]), EXTR_OVERWRITE);
    }
    inicializar("titulo", "");
    inicializar("bnid", "");
    inicializar("cid", "");
    inicializar("cedente", "");
    inicializar("agencia", "");
    inicializar("conta_cedente", "");
    inicializar("especie_documento", "REC");
    inicializar("codigo", "");
    inicializar("sacado", "");
    inicializar("cpf", "");
    inicializar("local_pagamento", "");
    inicializar("sacador", "");
    inicializar("carteira", "");
    inicializar("instrucoes_linha1", "");
    inicializar("instrucoes_linha2", "");
    inicializar("instrucoes_linha3", "");
    inicializar("instrucoes_linha4", "");
    inicializar("instrucoes_linha5", "");
?>
  <tr> 
    <td>
      <script language="JavaScript" src="../include/functions.js"></script>
      <script language="JavaScript">
      <!--
      function checaFormulario()
      {
          var checa = document.boleto_form;
          if (isWhitespace(checa.titulo.value)) {
              alert("Por favor digite o título para esse boleto.");
              checa.titulo.focus();
              return false;
          }
          if (checa.bnid.length == 0) {
              alert("Por favor adicione bancos antes de tentar criar um boleto.");
              return false;
          }
          if (checa.cid.length == 0) {
              alert("Por favor adicione uma configuração personalizada antes de tentar criar um boleto.");
              return false;
          }
          if (isWhitespace(checa.cedente.value)) {
              alert("Por favor digite o cedente para esse boleto.");
              checa.cedente.focus();
              return false;
          }
          if (isWhitespace(checa.agencia.value)) {
              alert("Por favor digite a agência para esse boleto.");
              checa.agencia.focus();
              return false;
          }
          if (isWhitespace(checa.codigo.value)) {
              alert("Por favor digite o código para esse boleto.");
              checa.codigo.focus();
              return false;
          }
          if (isWhitespace(checa.carteira.value)) {
              alert("Por favor digite a carteira para esse boleto.");
              checa.carteira.focus();
              return false;
          }
          checa.submit();
      }
      //-->
      </script>
      <form name="boleto_form" method="post" action="<?php echo $PHP_SELF; ?>">
      <?php if ($HTTP_GET_VARS["cat"] == "novo") : ?>
      <input type="hidden" name="cat" value="insert">
      <?php elseif ($HTTP_GET_VARS["cat"] == "modificar") : ?>
      <input type="hidden" name="cat" value="update">
      <input type="hidden" name="bid" value="<?php echo $HTTP_GET_VARS["bid"]; ?>">
      <?php endif; ?>
      <table width="100%" border="0" cellpadding="1" cellspacing="0">
        <tr bgcolor="#000000">
          <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="3" bgcolor="#999999">
              <tr> 
                <td nowrap class="normal"><b>T&iacute;tulo do Boleto:</b></td>
                <td colspan="2" nowrap width="100%"> 
                  <input type="text" name="titulo" size="40" maxlength="30" value="<?php echo $titulo; ?>">
                </td>
              </tr>
              <tr>
                <td nowrap class="normal"><b>Banco:</b></td>
                <td width="100%">
                  <select name="bnid">
<?php
    $bancos = $db_api->listaBancos();
    for ($i = 0; $i < count($bancos); $i++) {
        $checado = ($bancos[$i]["bnid"] == $bnid) ? "selected" : "";
        echo "<option $checado value=\"" . $bancos[$i]["bnid"] . "\">" . $bancos[$i]["nome"] . "</option>\n";
    }
?>
                  </select>
                </td>
                <td align="center">
                  <?php if (!empty($bnid)) : ?>
                  <input type="button" value="Modificar Dados do Banco" class="button" onClick="javascript:location.href='bancos.php?cat=modificar&bnid=<?php echo $bnid; ?>';">
                  <?php endif; ?>
                </td>
              </tr>
              <tr>
                <td nowrap class="normal"><b>Configuração Personalizadas</b></td>
                <td width="100%">
                  <select name="cid">
<?php
    $configs = $db_api->listaConfiguracoes();
    for ($i = 0; $i < count($configs); $i++) {
        $checado = ($configs[$i]["cid"] == $cid) ? "checked" : "";
        echo "<option $checado value=\"" . $configs[$i]["cid"] . "\">" . htmlspecialchars(stripslashes($configs[$i]["titulo"])) . "</option>\n";
    }
?>
                  </select>
                </td>
                <td align="center">
                  <?php if (!empty($cid)) : ?>
                  <input type="button" value="Modificar Configuração" class="button" onClick="javascript:location.href='configuracoes.php?cat=modificar&cid=<?php echo $cid; ?>';">
                  <?php endif; ?>
                </td>
              </tr>
              <tr>
                <td nowrap class="normal"><b>Cedente:</b></td>
                <td colspan="2" width="100%">
                  <input type="text" name="cedente" size="40" maxlength="255" value="<?php echo $cedente; ?>">
                </td>
              </tr>
              <tr>
                <td nowrap class="normal"><b>Agência / Conta do Cedente:</b></td>
                <td colspan="2" width="100%">
                  <input type="text" name="agencia" size="10" maxlength="10" value="<?php echo $agencia; ?>">&nbsp;&nbsp;<b>/</b>&nbsp;&nbsp;<input type="text" name="conta_cedente" size="20" maxlength="20" value="<?php echo $conta_cedente; ?>">
                </td>
              </tr>
              <tr>
                <td nowrap class="normal"><b>Espécie de Documento:</b></td>
                <td colspan="2" width="100%">
                  <input type="text" name="especie_documento" size="10" maxlength="10" value="<?php echo $especie_documento; ?>">
                </td>
              </tr>
              <tr>
                <td nowrap class="normal"><b>Código:</b></td>
                <td colspan="2" width="100%">
                  <input type="text" name="codigo" size="40" maxlength="40" value="<?php echo $codigo; ?>">
                </td>
              </tr>
              <tr>
                <td nowrap class="normal"><b>Sacado:</b></td>
                <td colspan="2" width="100%">
                  <input type="text" name="sacado" size="40" maxlength="50" value="<?php echo $sacado; ?>">
                </td>
              </tr>
              <tr>
                <td nowrap class="normal"><b>CPF/CGC:</b></td>
                <td colspan="2" width="100%">
                  <input type="text" name="cpf" size="20" maxlength="20" value="<?php echo $cpf; ?>">
                </td>
              </tr>
              <tr>
                <td nowrap class="normal"><b>Local Pagamento:</b></td>
                <td colspan="2" width="100%">
                  <input type="text" name="local_pagamento" size="40" maxlength="255" value="<?php echo $local_pagamento; ?>">
                </td>
              </tr>
              <tr>
                <td nowrap class="normal"><b>Sacador:</b></td>
                <td colspan="2" width="100%">
                  <input type="text" name="sacador" size="40" maxlength="50" value="<?php echo $sacador; ?>">
                </td>
              </tr>
              <tr>
                <td nowrap class="normal"><b>Carteira:</b></td>
                <td colspan="2" width="100%">
                  <input type="text" name="carteira" size="40" maxlength="30" value="<?php echo $carteira; ?>">
                </td>
              </tr>
              <tr>
                <td nowrap class="normal"><b>Instruções:</b></td>
                <td colspan="2" width="100%">
                  <input type="text" name="instrucoes_linha1" size="40" maxlength="100" value="<?php echo $instrucoes_linha1; ?>"><br>
                  <input type="text" name="instrucoes_linha2" size="40" maxlength="100" value="<?php echo $instrucoes_linha2; ?>"><br>
                  <input type="text" name="instrucoes_linha3" size="40" maxlength="100" value="<?php echo $instrucoes_linha3; ?>"><br>
                  <input type="text" name="instrucoes_linha4" size="40" maxlength="100" value="<?php echo $instrucoes_linha4; ?>"><br>
                  <input type="text" name="instrucoes_linha5" size="40" maxlength="100" value="<?php echo $instrucoes_linha5; ?>">
                </td>
              </tr>
              <tr>
                <td nowrap colspan="3">
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
          <td colspan="4">
            <h4>Lista de Boletos</h4>
          </td>
        </tr>
        <tr>
          <td width="5" nowrap>&nbsp;</td>
          <td nowrap class="normal"><b>Banco</b></td>
          <td width="100%" class="normal"><b>Título do Boleto</b></td>
          <td nowrap>&nbsp;</td>
        </tr>
<?php
$boletos = $db_api->listaBoletos();
for ($i = 0; $i < count($boletos); $i++) {
?>
        <tr bgcolor="<?php echo corLoop($i); ?>">
          <td width="5" nowrap><input type="checkbox" name="boletos[]" value="<?php echo $boletos[$i]["bid"]; ?>"></td>
          <td nowrap class="normal"><?php echo $boletos[$i]["nome_banco"]; ?></td>
          <td width="100%" class="normal"><a href="<?php echo $PHP_SELF; ?>?cat=modificar&bid=<?php echo $boletos[$i]["bid"]; ?>"><?php echo htmlspecialchars(stripslashes($boletos[$i]["titulo"])); ?></a></td>
          <td nowrap class="normal">
            <a href="revisar_boleto.php?cat=db&bid=<?php echo $boletos[$i]["bid"]; ?>">Revisar Boleto</a>&nbsp;&nbsp;|&nbsp;
            <a href="templates.php?bid=<?php echo $boletos[$i]["bid"]; ?>">Gerar Templates</a>
          </td>
        </tr>
<?php
}
?>
        <tr>
          <td colspan="4">
            <hr>
          </td>
        </tr>
        <tr>
          <td colspan="4">
            <input type="button" value="Novo Boleto" class="button" onClick="javascript:location.href='<?php echo $PHP_SELF; ?>?cat=novo';">
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