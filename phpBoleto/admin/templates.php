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
    /** Opções para Envio de Email **/
    "boletomail"          => "sim",
    "remetente_nome"      => "Impleo.net - Suporte",
    "remetente_email"     => "suporte@impleo.net",
    "recipiente_nome"     => "Recipiente",
    "recipiente_email"    => "recipiente@impleo.net",
    "assunto"             => "Boleto da Compra",
    "mensagem_texto"      => "O seu boleto vai anexado",
    "mensagem_html"       => "",
    "enviar_pdf"          => "nao", // funcionará somente se "tipo"
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
    /** Opções para Envio de Email **/
    "boletomail"          => "sim",
    "remetente_nome"      => "Impleo.net - Suporte",
    "remetente_email"     => "suporte@impleo.net",
    "recipiente_nome"     => "Recipiente",
    "recipiente_email"    => "recipiente@impleo.net",
    "assunto"             => "Boleto da Compra",
    "mensagem_texto"      => "O seu boleto vai anexado",
    "mensagem_html"       => "",
    "enviar_pdf"          => "sim", // funcionará somente se "tipo"
                                    // for diferente de "pdf"
    "servidor_smtp"       => "smtp.mail.yahoo.com",
    "servidor_http"       => ""
);
$boleto->geraBoleto($info, ' . $HTTP_GET_VARS["bid"] . ');
?>';

mostraCabecalho($inidata->TITULO_ADMIN_NORMAL);
mostraTitulo("Templates de Geração de Boletos");
?>
<table width="600" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td class="normal">
      <b>Abaixo estão disponíveis vários templates de scripts PHP que podem ser 
      usados para a geração de Boletos usando o phpBoleto. Os parâmetros dos 
      mesmos estão corretos de acordo com as opções do Boleto, só precisando de 
      modificações manuais no parâmetro de 'tipo' de Boleto (Imagem, HTML ou PDF).
      <br><br>
      A maioria desses parâmetros são opcionais e disponíveis para serem modificados
      pela Interface de Administração que você está usando. Mesmo assim, eles estão
      disponíveis para que possam ser parâmetros dinâmicos supridos a cada chamada ao
      script de geração de Boleto. Se os parâmetros opcionais não forem passados ao
      script, ele irá pegar as opções do banco de dados automaticamente.
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
      <table width="100%">
        <tr>
          <td nowrap colspan="2" bgcolor="#CCCCCC">
            <h4>&nbsp;Template de Geração de Boleto como Imagem</h4>
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
            <h4>&nbsp;Template de Geração de Boleto como HTML</h4>
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
            <h4>&nbsp;Template de Geração de Boleto como PDF</h4>
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
            <h4>&nbsp;Enviando Boleto por Email com Cópia <br>do Boleto Anexada em PDF</h4>
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