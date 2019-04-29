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
// |          Pablo Martins F. Costa <pablo@users.sourceforge.net>        |
// |          Miguel Angelo Crosariol <miguel@assintel.com.br>            |
// +----------------------------------------------------------------------+
//
// @(#) $Id: class.banco.bancodobrasil.php,v 1.1 2007/01/18 19:43:35 leonardo.kopp Exp $
//

require_once(BOLETO_INC_PATH . "boletos" . BOLETO_SEPARADOR . "bancos" . BOLETO_SEPARADOR . "class.banco.comum.php");

class Boleto_Banco_BancodoBrasil extends Boleto_Banco_Comum
{
    function geraDadosBanco($info)
    {
        /* VARIAVEIS */
        $codbank = "001"; // numero do banco do brasil
        $nconvenio = 222222; // numero do convenio
        $nservico = 21; // numero do servico
        $cZero ="0"; // nao mexa
        $nmoeda = 9; // moeda (R$)
        $cart = 18; // numero da carteira

        // formata o valor do documento para o codigo de barras
        $valor = str_replace("R\$", "", $info["valor_documento"]);
        $valor = str_replace(chr(44), "", $valor);
        // deixando o valor com 10 digitos
        $nV1=strlen ($valor);
        while ($nV1 < 10) {
            $valor = "$cZero$valor";
            $nV1 ++;
        }

        // vencimento
        $vence = explode("/", $info["vencimento"]);
        $dvence = $vence[0];
        $mvence = $vence[1];
        $avence = $vence[2];
        $vcto = "$dvence/$mvence/$avence";
        $fatorvcto = $this->_fatorVencimento($avence, $mvence, $dvence);

        // deixando o nosso numero com 17 digitos
        $nnum = $info["nosso_numero"];
        $nV1=strlen ($nnum);
        while ($nV1 < 11) {
            $nnum = "$cZero$nnum";
            $nV1 ++;
        }
        $nnum = "$nconvenio$nnum";
        // calculando o dv campo 4 do campo livre
        // 43 numeros para o calculo do digito verificador
        $dvcampo = "$codbank$nmoeda$fatorvcto$valor$nconvenio$nnum$nservico";
        $dv = $this->_modulo11($dvcampo);

        // Montagem da agencia e conta cedente
        $agencia = substr($info["agencia"], 0, 4);
        $contacedente = substr($info["conta_cedente"], 0, 4);

        // Numero para o codigo de barras com 44 digitos
        $num="$codbank$nmoeda$dv$fatorvcto$valor$nconvenio$nnum$nservico";

        // Devolve a linha digitavel
        $linha_digitavel = $this->_montaLinha($num);
        $codigo_banco = $this->_geraCodigoBanco($codbank);

        $nnum = $info["nosso_numero"];
        $nosso_numero = "$cart/$nnum";

        /* AGENCIA / CONTACEDENTE*/
        $p0 = $this->_digitoVerificador($agencia);
        $p1 = $this->_digitoVerificador($contacedente);
        $agencia_codigo = "$agencia-$p0/$contacedente-$p1";

        return array(
            "linha_digitavel" => $linha_digitavel,
            "agencia_codigo"  => $agencia_codigo,
            "codigo_barras"   => $num,
            "codigo_banco"    => $codigo_banco,
            "nosso_numero"    => $nosso_numero
        );
    }

    function _digitoVerificador($numero)
    {
        $resto = $this->_modulo11($numero, 7, 1);
        $digito = 11 - $resto;
        if ($resto == 1) {
           $digito = "P";
        } elseif ($resto == 0) {
           $digito = 0;
        }
        return $digito;
    }
}
?>