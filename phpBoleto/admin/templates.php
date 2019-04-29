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
// @(#) $Id: templates.php,v 1.1 2007/01/18 19:43:34 leonardo.kopp Exp $
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

$imagem_template = '<?php
include_once("' . BOLETO_INC_PATH . 'pre.php");
include_once("' . BOLETO_INC_PATH . 'class.boleto.php");

$boleto = new Boleto;
$info = array(
    "tipo"                => "imagem",
    "vencimento"          => date("d/m/Y", time()+60*60*24*7), // opcional
    "nosso_numero"        => "961580786",
    "numero_documento"    => "",
    "codigo_barra"        => "",
    "data_documento"      => date("d/m/Y"), // opcional
    "valor_documento"     => "1250,00"
);
$boleto->geraBoleto($info, ' . $HTTP_GET_VARS["bid"] . ');
?>';

$html_template = '<?php
include_once("' . BOLETO_INC_PATH . 'pre.php");
include_once("' . BOLETO_INC_PATH . 'class.boleto.php");

$boleto = new Boleto;
$info = array(
    "tipo"                => "html",
    "vencimento"          => date("d/m/Y", time()+60*60*24*7), // opcional
    "nosso_numero"        => "961580786",
    "numero_documento"    => "",
    "codigo_barra"        => "",
    "data_documento"      => date("d/m/Y"), // opcional
    "valor_documento"     => "1250,00"
);
$boleto->geraBoleto($info, ' . $HTTP_GET_VARS["bid"] . ');
?>';

$pdf_template = '<?php
include_once("' . BOLETO_INC_PATH . 'pre.php");
include_once("' . BOLETO_INC_PATH . 'class.boleto.php");

$boleto = new Boleto;
$info = array(
    "tipo"                => "pdf",
    "vencimento"          => date("d/m/Y", time()+60*60*24*7), // opcional
    "nosso_numero"        => "961580786",
    "numero_documento"    => "",
    "codigo_barra"        => "",
    "data_documento"      => date("d/m/Y"), // opcional
    "valor_documento"     => "1250,00"
);
$boleto->geraBoleto($info, ' . $HTTP_GET_VARS["bid"] . ');
?>';

$email_template = '<?php
include_once("' . BOLETO_INC_PATH . 'pre.php");
include_once("' . BOLETO_INC_PATH . 'class.boleto.php");

$boleto = new Boleto;
$info = array(
    "tipo"                => "imagem",
    "vencimento"          => date("d/m/Y", time()+60*60*24*7), // opcional
    "nosso_numero"        => "961580786",
    "numero_documento"    => "",
    "codigo_barra"        => "",
    "data_documento"      => date("d/m/Y"), // opcional
    "valor_documento"     => "1250,00",
    /** Op��es para Envio de Email **/
    "boletomail"          => "sim",
    "remetente_nome"      => "Impleo.net - Suporte",
    "remetente_email"     => "suporte@impleo.net",
    "recipiente_nome"     => "Recipiente",
    "recipiente_email"    => "recipiente@impleo.net",
    "assunto"             => "Boleto da Compra",
    "mensagem_texto"      => "O seu boleto vai anexado",
    "mensagem_html"       => "",
    "enviar_pdf"          => "nao", // funcionar� somente se "tipo"
                                    // for diferente de "pdf"
    "servidor_smtp"       => "smtp.mail.yahoo.com",
    "servidor_http"       => ""
);
$boleto->geraBoleto($info, ' . $HTTP_GET_VARS["bid"] . ');
?>';

$email_pdf_template = '<?php
include_once("' . BOLETO_INC_PATH . 'pre.php");
include_once("' . BOLETO_INC_PATH . 'class.boleto.php");

$boleto = new Boleto;
$info = array(
    "tipo"                => "imagem",
    "vencimento"          => date("d/m/Y", time()+60*60*24*7), // opcional
    "nosso_numero"        => "961580786",
    "numero_documento"    => "",
    "codigo_barra"        => "",
    "data_documento"      => date("d/m/Y"), // opcional
    "valor_documento"     => "1250,00",
    /** Op��es para Envio de Email **/
    "boletomail"          => "sim",
    "remetente_nome"      => "Impleo.net - Suporte",
    "remetente_email"     => "suporte@impleo.net",
    "recipiente_nome"     => "Recipiente",
    "recipiente_email"    => "recipiente@impleo.net",
    "assunto"             => "Boleto da Compra",
    "mensagem_texto"      => "O seu boleto vai anexado",
    "mensagem_html"       => "",
    "enviar_pdf"          => "sim", // funcionar� somente se "tipo"
                                    // for diferente de "pdf"
    "servidor_smtp"       => "smtp.mail.yahoo.com",
    "servidor_http"       => ""
);
$boleto->geraBoleto($info, ' . $HTTP_GET_VARS["bid"] . ');
?>';

mostraCabecalho($inidata->TITULO_ADMIN_NORMAL);
mostraTitulo("Templates de Gera��o de Boletos");
?>
<table width="600" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td class="normal">
      <b>Abaixo est�o dispon�veis v�rios templates de scripts PHP que podem ser 
      usados para a gera��o de Boletos usando o phpBoleto. Os par�metros dos 
      mesmos est�o corretos de acordo com as op��es do Boleto, s� precisando de 
      modifica��es manuais no par�metro de 'tipo' de Boleto (Imagem, HTML ou PDF).
      <br><br>
      A maioria desses par�metros s�o opcionais e dispon�veis para serem modificados
      pela Interface de Administra��o que voc� est� usando. Mesmo assim, eles est�o
      dispon�veis para que possam ser par�metros din�micos supridos a cada chamada ao
      script de gera��o de Boleto. Se os par�metros opcionais n�o forem passados ao
      script, ele ir� pegar as op��es do banco de dados automaticamente.
      </b>
    </td>
  </tr>
  <tr>
    <td align="center" class="navegacao">
      <hr>
      <a href="principal.php">Menu Principal</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="config.php">Configura��o Geral</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="boletos.php">Boletos</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="bancos.php">Bancos</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="configuracoes.php">Configura��es Personalizadas</a>
      <hr>
    </td>
  </tr>
  <tr>
    <td>
      <table width="100%">
        <tr>
          <td nowrap colspan="2" bgcolor="#CCCCCC">
            <h4>&nbsp;Template de Gera��o de Boleto como Imagem</h4>
          </td>
        </tr>
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#000000">
              <tr>
                <td>
                  <table width="100%" border="0" cellspacing="0" cellpadding="2" bgcolor="#FFFFFF">
                    <tr>
                      <td class="normal">
<?php highlight_string($imagem_template); ?>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table width="100%">
        <tr>
          <td nowrap colspan="2" bgcolor="#CCCCCC">
            <h4>&nbsp;Template de Gera��o de Boleto como HTML</h4>
          </td>
        </tr>
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#000000">
              <tr>
                <td>
                  <table width="100%" border="0" cellspacing="0" cellpadding="2" bgcolor="#FFFFFF">
                    <tr>
                      <td class="normal">
<?php highlight_string($html_template); ?>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table width="100%">
        <tr>
          <td nowrap colspan="2" bgcolor="#CCCCCC">
            <h4>&nbsp;Template de Gera��o de Boleto como PDF</h4>
          </td>
        </tr>
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#000000">
              <tr>
                <td>
                  <table width="100%" border="0" cellspacing="0" cellpadding="2" bgcolor="#FFFFFF">
                    <tr>
                      <td class="normal">
<?php highlight_string($pdf_template); ?>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table width="100%">
        <tr>
          <td nowrap colspan="2" bgcolor="#CCCCCC">
            <h4>&nbsp;Enviando Boleto por Email</h4>
          </td>
        </tr>
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#000000">
              <tr>
                <td>
                  <table width="100%" border="0" cellspacing="0" cellpadding="2" bgcolor="#FFFFFF">
                    <tr>
                      <td class="normal">
<?php highlight_string($email_template); ?>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table width="100%">
        <tr>
          <td nowrap colspan="2" bgcolor="#CCCCCC">
            <h4>&nbsp;Enviando Boleto por Email com C�pia <br>do Boleto Anexada em PDF</h4>
          </td>
        </tr>
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#000000">
              <tr>
                <td>
                  <table width="100%" border="0" cellspacing="0" cellpadding="2" bgcolor="#FFFFFF">
                    <tr>
                      <td class="normal">
<?php highlight_string($email_pdf_template); ?>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
<?php
mostraRodape();
?>