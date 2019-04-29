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
// +----------------------------------------------------------------------+
//
// @(#) $Id: class.banco.bradesco.php,v 1.1 2007/01/18 19:43:35 leonardo.kopp Exp $
//

require_once(BOLETO_INC_PATH . "boletos" . BOLETO_SEPARADOR . "bancos" . BOLETO_SEPARADOR . "class.banco.comum.php");

class Boleto_Banco_Bradesco extends Boleto_Banco_Comum
{
    function geraDadosBanco($info)
    {
        // formata o valor do documento para o codigo de barras
        $v = str_replace("R\$", "", $info["valor_documento"]);
        $v = str_replace(chr(44), "", $v);
        $valor = sprintf("%010d", $v);

        // vencimento
        $vence = explode("/", $info["vencimento"]);
        $dvence = $vence[0];
        $mvence = $vence[1];
        $avence = $vence[2];
        $vcto = "$dvence/$mvence/$avence";
        $fatorvcto = $this->_fatorVencimento($avence, $mvence, $dvence);

        /*
         calculando modulo11 para o nosso numero
         ficando com 14 digitos - acrescentar o cod. da carteira ...
         com registro 19 ***** sem registro 06 ****
        */
        $ano = date("y");
        $cart = "05";
        $nnum = sprintf("%09d", $info["nosso_numero"]);
        $nnum = "$cart$ano$nnum";
        $dv = $this->_digitoVerificador($nnum);
        $nnum = "$nnum$dv";
        $zero = 0;

        // Montagem da agencia e conta cedente
        $agencia = substr($info["agencia"], 0, 4);
        $contacedente = substr($info["conta_cedente"], 0, 7);

        // nosso numero sem o digito, com 13 caracteres
        $nn = substr($nnum, 0, 13);
        $moeda = "9";
        $codbank = 237;

        // 43 numeros para o calculo do digito verificador
        $dvcampo = "$codbank$moeda$fatorvcto$valor$agencia$nn$contacedente$zero";
        $dv =  $this->_modulo11($dvcampo);
        // Numero para o codigo de barras com 44 digitos
        $num = "$codbank$moeda$dv$fatorvcto$valor$agencia$nn$contacedente$zero";

        // Devolve a linha digitavel
        $linha_digitavel = $this->_montaLinha($num);
        $codigo_banco = $this->_geraCodigoBanco($codbank);

        // nosso numero
        $p1 = substr($nnum, 0, 2);
        $p2 = substr($nnum, 2, 2);
        $p3 = substr($nnum, 4, 9);
        $p4 = substr($nnum, -1);
        $nosso_numero = "$p1/$p2/$p3-$p4";

        // agencia/codigo cedente
        $p1 = $this->_digitoVerificador($contacedente);
        $agencia_codigo = "$agencia-$contacedente/$p1";

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