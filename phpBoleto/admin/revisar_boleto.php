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
// @(#) $Id: revisar_boleto.php,v 1.1 2007/01/18 19:43:34 leonardo.kopp Exp $
//
error_reporting(E_ALL);
ini_set("include_path", ".");
include_once("../include/pre.php");

if ((isset($HTTP_POST_VARS["tipo"])) && (!empty($HTTP_POST_VARS["tipo"])) && ($HTTP_POST_VARS["cat"] == "db")) {
    include_once(BOLETO_INC_PATH . "class.boleto.php");
    $info = array(
        "tipo"                => strtolower($HTTP_POST_VARS["tipo"]),
        "vencimento"          => date("d/m/Y", time()+60*60*24*7), // opcional
        "nosso_numero"        => "961580786",
        "numero_documento"    => "", // opcional
        "codigo_barra"        => "", // opcional
        "data_documento"      => date("d/m/Y"), // opcional
        "valor_documento"     => $HTTP_POST_VARS["valor_documento"],
    );
    $boleto = new Boleto;
    $boleto->geraBoleto($info, $HTTP_POST_VARS["bid"]);

} else {
    include_once(BOLETO_INC_PATH . "comum.php");
    include_once(BOLETO_INC_PATH . "class.ini.php");
    $ini = new File_Ini(BOLETO_CONF_PATH . "phpboleto.ini.php", "#");
    $inidata = (object) $ini->getBlockValues("Admin Geral");

    checaAutenticacao();

    include_once(BOLETO_INC_PATH . "class.db.php");
    $db_api = Boleto_DB::conectar($inidata->BOLETO_SISTEMA);

    mostraCabecalho($inidata->TITULO_ADMIN_NORMAL);
    mostraTitulo("Revisar Boleto");
?>
<table width="600" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td class="normal">
      <b>Use o formulário abaixo para escolher algumas variáveis necessárias para
      a geração do boleto. Você pode usar o mesmo para revisar as opções de cada
      boleto e checar a sua consistência em relação às suas necessidades.
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
  <tr>
    <td>
      <script language="JavaScript" src="../include/functions.js"></script>
      <script language="JavaScript">
      <!--
      function checaFormulario()
      {
          var checa = document.revisar_form;
          if (isWhitespace(checa.valor_documento.value)) {
              alert("Por favor digite o valor para o boleto.");
              checa.valor_documento.focus();
              return false;
          }
          checa.submit();
      }
      //-->
      </script>
      <form name="revisar_form" method="post" action="<?php echo $PHP_SELF; ?>">
      <input type="hidden" name="cat" value="db">
      <input type="hidden" name="bid" value="<?php echo $HTTP_GET_VARS["bid"]; ?>">
      <table width="100%" border="0" cellpadding="1" cellspacing="0">
        <tr bgcolor="#000000">
          <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="3" bgcolor="#999999">
              <tr>
                <td width="20%" nowrap class="normal">
                  <b>Tipo de Boleto:</b>
                </td>
                <td width="80%">
                  <select name="tipo">
                    <option value="html">HTML</option>
                    <option value="imagem">Imagem</option>
                    <option value="pdf">PDF</option>
                    <option value="svg">SVG (experimental)</option>
                  </select>
                </td>
              </tr>
              <tr>
                <td width="20%" nowrap class="normal">
                  <b>Valor do Documento:</b>
                </td>
                <td width="80%">
                  <input type="text" name="valor_documento" value="19,95">
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <input type="button" class="button" value="Gerar Boleto" onClick="javascript:checaFormulario();">
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      </form>
    </td>
  </tr>
</table>
<?php
    mostraRodape();
}
?>