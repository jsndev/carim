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
include_once("./include/pre.php");
include_once(BOLETO_INC_PATH . "comum.php");
include_once(BOLETO_INC_PATH . "class.ini.php");
$ini = new File_Ini(BOLETO_CONF_PATH . "phpboleto.ini.php", "#");
$inidata = (object) $ini->getBlockValues("Admin Geral");

include_once(BOLETO_INC_PATH . "class.db.php");
$db_api = Boleto_DB::conectar($inidata->BOLETO_SISTEMA);
?>
<html>
<head>
  <title>phpBoleto - Tela de Testes</title>
  <link rel="stylesheet" href="config/estilo.css" type="text/css">
<script language="JavaScript">
<!--
var submitcount=0;
function validatePrompt (Ctrl, PromptStr) {
    alert (PromptStr)
    Ctrl.focus();
    return;
}
function campovazio(Ctrl, Msg) {
    if (Ctrl.value == "") {
        validatePrompt (Ctrl, Msg)
        return (false);
    } else
        return (true);
}
function isEmail(string) {
    if (string.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) != -1)
        return true;
    else
        return false;
}
function IsReadyEmail(Field) {
    if(document.forms[0].boletomail.options[document.forms[0].boletomail.selectedIndex].value == "0")
        return true;

    if (isEmail(Field.value) == false) {
        validatePrompt (Field, "Email invalido. Por favor somente letras, digitos e \"._-@\" no \"Email\".");        
        return false;
    }
    return true;
}
function verify()  {
    if (!IsReadyEmail(document.forms[0].email))
        return;

    document.forms[0].submit();
    return;
}
//-->
</script>
</head>

<body bgcolor="#FFFFFF">
<table width="600" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%" class="normal">
      Copyright © 2001 <a href="<?php echo $inidata->URL_PROJETO; ?>">Equipe do phpBoleto</a>
    </td>
    <td align="right" width="50%" class="normal">
      <?php echo $inidata->VERSAO; ?>
    </td>
  </tr>
</table>
<br>
<table width="600" border="0" cellspacing="0" cellpadding="5" bgcolor="#003366">
  <tr>
    <td>
      <h2><font color="#FFFFFF">phpBoleto - Tela de Testes</font></h2>
    </td>
  </tr>
</table>
<br>
<form method="post" action="geraboleto.php">
<table width="600" border="0" cellpadding="1" cellspacing="0" bgcolor="#000000">
  <tr>
    <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="3" bgcolor="#999999">
        <tr>
          <td width="100%" colspan="2" class="normal">
            <b>Dados do Documento</b>
          </td>
        </tr>
        <tr>
          <td width="140" class="normal">Valor:</td>
          <td width="100%">
            <input type="text" name="valor" size="20" value="347,77">
          </td>
        </tr>
        <tr>
          <td width="140" nowrap class="normal">Número do pedido:</td>
          <td width="100%">
            <input type="text" name="nosso_numero" size="20" value="961580786">
          </td>
        </tr>
        <tr> 
          <td width="140" class="normal">Vencimento do Título:</td>
          <td width="100%">
            <select name="vencimento[]">
              <option value="01">1</option>
              <option value="02">2</option>
              <option value="03">3</option>
              <option value="04">4</option>
              <option value="05">5</option>
              <option value="06">6</option>
              <option value="07">7</option>
              <option value="08">8</option>
              <option value="09">9</option>
              <option value="10">10</option>
              <option value="11">11</option>
              <option value="12">12</option>
              <option value="13">13</option>
              <option value="14">14</option>
              <option value="15">15</option>
              <option value="16">16</option>
              <option value="17">17</option>
              <option value="18">18</option>
              <option value="19">19</option>
              <option value="20">20</option>
              <option value="21">21</option>
              <option value="22">22</option>
              <option value="23">23</option>
              <option value="24">24</option>
              <option value="25">25</option>
              <option value="26">26</option>
              <option value="27">27</option>
              <option value="28" selected>28</option>
              <option value="29">29</option>
              <option value="30">30</option>
              <option value="31">31</option>
            </select>
            <select name="vencimento[]">
              <option value="01">Janeiro</option>
              <option value="02">Fevereiro</option>
              <option value="03">Marco</option>
              <option value="04">Abril</option>
              <option value="05" selected>Maio</option>
              <option value="06">Junho</option>
              <option value="07">Julho</option>
              <option value="08">Agosto</option>
              <option value="09">Setembro</option>
              <option value="10">Outubro</option>
              <option value="11">Novembro</option>
              <option value="12">Dezembro</option>
            </select>
            <select name="vencimento[]">
              <option value="2000">2000</option>
              <option value="2001" selected>2001</option>
            </select>
          </td>
        </tr>
        <tr>
          <td colspan="2" width="100%" class="normal">
            <hr>
            <b>Dados do Sacado</b>
          </td>
        </tr>
        <tr>
          <td width="140" class="normal">Nome/Razão Social:</td>
          <td width="100%">
            <input type="text" name="sacado[nome]" size="30" value="Nome do Cliente">
          </td>
        </tr>
        <tr>
          <td width="140" class="normal">C.G.C./C.P.F.:</td>
          <td width="100%">
            <input type="text" name="cpf" size="20" value="123.456.789-01">
          </td>
        </tr>
        <tr>
          <td width="140" class="normal">Endereço:</td>
          <td width="100%">
            <input type="text" name="sacado[endereco]" size="30" value="R. Phpboleto, 2000">
          </td>
        </tr>
        <tr>
          <td width="140" class="normal">Bairro:</td>
          <td width="100%">
            <input type="text" name="sacado[bairro]" size="20" value="Barra do I25">
          </td>
        </tr>
        <tr>
          <td width="140" class="normal">CEP:</td>
          <td width="100%">
            <input type="text" name="sacado[cep]" size="10" value="07070-000">
          </td>
        </tr>
        <tr>
          <td width="140" class="normal">Estado:</td>
          <td width="100%">
            <select size="1" name="sacado[estado]">
              <option value="SP">SP</option>
              <option value="AC">AC</option>
              <option value="AL">AL</option>
              <option value="AM">AM</option>
              <option value="AP">AP</option>
              <option value="BA">BA</option>
              <option value="CE">CE</option>
              <option value="DF">DF</option>
              <option value="ES">ES</option>
              <option value="GO">GO</option>
              <option value="MA">MA</option>
              <option value="MG">MG</option>
              <option value="MS">MS</option>
              <option value="MT">MT</option>
              <option value="PA">PA</option>
              <option value="PB">PB</option>
              <option value="PE">PE</option>
              <option value="PI">PI</option>
              <option value="PR">PR</option>
              <option value="RN">RN</option>
              <option value="RO">RO</option>
              <option value="RR">RR</option>
              <option value="RJ">RJ</option>
              <option value="RS">RS</option>
              <option value="SC">SC</option>
              <option value="SE">SE</option>
              <option value="TO">TO</option>
            </select>
          </td>
        </tr>                                                                        
        <tr>
          <td colspan="2" width="100%" class="normal">
            <hr>
            <b>Dados Complementares</b>
          </td>
        </tr>
        <tr>
          <td width="140" class="normal">Demonstrativo:</td>
          <td width="100%">
            <input type="text" name="demons1" size="30" value="Testando 1 2 3..."><br>
            <input type="text" name="demons2" size="30"><br>
            <input type="text" name="demons3" size="30"><br>
            <input type="text" name="demons4" size="30">
          </td>
        </tr>                                            
        <tr>
          <td colspan=2 width="100%" class="normal">
            <hr>
            <b>Dados do Banco</b>
          </td>
        </tr>
        <tr>
          <td width="140" class="normal">Agência:</td>
          <td width="100%">
            <input type="text" name="agencia" size="10" value="0436">
          </td>
        </tr>                      
        <tr>
          <td width="140" class="normal">Conta do Cedente:</td>
          <td width="100%">
            <input type="text" name="conta_cedente" size="20" value="0404392">
          </td>
        </tr>                      
        <tr>
          <td width="140" class="normal">Instruções para o Caixa:</td>
          <td width="100%">
            <input type="text" name="instrucoes_linha1" size="30" value="Sr. Caixa, não receber após o vencimento"><br>
            <input type="text" name="instrucoes_linha2" size="30"><br>
            <input type="text" name="instrucoes_linha3" size="30"><br>
            <input type="text" name="instrucoes_linha4" size="30"><br>
            <input type="text" name="instrucoes_linha5" size="30">
          </td>
        </tr>
        <tr>
          <td colspan=2 width="100%" class="normal">
            <hr>
            <b>Opções de Geração do Boleto</b>
          </td>
        </tr>      
        <tr>
          <td width="140" class="normal">Boleto:</td>
          <td width="100%">
            <select name="bid">
            <?php
            $boletos = $db_api->listaBoletos();
            for ($i = 0; $i < count($boletos); $i++) {
                echo "              <option value=\"" . $boletos[$i]["bid"] . "\">" . $boletos[$i]["titulo"] . "</option>\n";
            }
            ?>
            </select>
          </td>
        </tr>
        <tr>
          <td width="140" class="normal">Formato do Boleto:</td>
          <td width="100%">
            <select name="tipo">
              <option value="html">Boleto em HTML</option>
              <option value="imagem">Boleto em Imagem</option>
              <option value="pdf">Boleto em PDF</option>
              <option value="svg">Boleto em SVG (experimental)</option>
            </select>
          </td>
        </tr>
        <tr>
          <td width="140" class="normal">Mandar Boleto por Email:</td>
          <td width="100%">
            <select name="boletomail">
              <option value="nao">Não</option>
              <option value="nao">Sim</option>
            </select>
          </td>
        </tr>
        <tr>
          <td width="140" class="normal">Enviar Cópia em Anexo como PDF:</td>
          <td width="100%">
            <select name="enviar_pdf">
              <option value="nao">Não</option>
              <option value="nao">Sim</option>
            </select>
          </td>
        </tr>
        <tr>
          <td width="140" class="normal">Assunto:</td>
          <td width="100%">
            <input type="text" name="assunto" size="30">
          </td>
        </tr>
        <tr>
          <td width="140" class="normal">Mensagem:</td>
          <td width="100%">
            <textarea name="mensagem" cols="30" rows="6"></textarea>
          </td>
        </tr>
        <tr>
          <td width="140" class="normal">Nome do Remetente:</td>
          <td width="100%">
            <input type="text" name="remetente_nome" size="30">
          </td>
        </tr>
        <tr>
          <td width="140" class="normal">Email do Remetente:</td>
          <td width="100%">
            <input type="text" name="remetente_email" size="30">
          </td>
        </tr>
        <tr>
          <td width="140" class="normal">Nome do Recipiente:</td>
          <td width="100%">
            <input type="text" name="recipiente_nome" size="30">
          </td>
        </tr>
        <tr>
          <td width="140" class="normal">Email do Recipiente:</td>
          <td width="100%">
            <input type="text" name="recipiente_email" size="30">
          </td>
        </tr>
        <tr>
          <td width="140" class="normal">Servidor SMTP:</td>
          <td width="100%">
            <input type="text" name="servidor_smtp" size="30">
          </td>
        </tr>
        <tr>
          <td width="140" class="normal">Servidor HTTP:</td>
          <td width="100%">
            <input type="text" name="servidor_http" size="30">
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <hr>
            <input type="submit" value="Gerar Boleto &gt;&gt;" class="button">
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</form>

</body>
</html>
