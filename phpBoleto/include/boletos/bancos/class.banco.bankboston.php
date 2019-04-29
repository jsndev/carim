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
// @(#) $Id: class.banco.bankboston.php,v 1.1 2007/01/18 19:43:35 leonardo.kopp Exp $
//

require_once(BOLETO_INC_PATH . "boletos" . BOLETO_SEPARADOR . "bancos" . BOLETO_SEPARADOR . "class.banco.comum.php");

class Boleto_Banco_Bankboston extends Boleto_Banco_Comum
{
    function geraDadosBanco($info)
    {
        // Montagem do calculo para o nosso numero
        $agcod = $info["agencia"] . $info["conta_cedente"];

        // formatacao do numero para o codigo de barras
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

        $nnum = intval($info["nosso_numero"]);
        // formata o nosso numero para ter 14 campos com zeros a esquerda
        $nn = sprintf("%015d", $nnum);
        $moeda = "9";
        $codbank = 479;
        // 43 numeros para o calculo do digito verificador
        $dvcampo = "$codbank$moeda$fatorvcto$valor$agcod$nn$moeda";
        $dv = $this->_modulo11($dvcampo);

        // Numero para o codigo de barras com 44 digitos
        $num = "$codbank$moeda$dv$fatorvcto$valor$agcod$nn$moeda";

        // Devolve a linha digitavel
        $linha_digitavel = $this->_montaLinha($num);
        $codigo_banco = $this->_geraCodigoBanco($codbank);

        return array(
            "linha_digitavel" => $linha_digitavel,
            "agencia_codigo"  => $agcod,
            "codigo_barras"   => $num,
            "codigo_banco"    => $codigo_banco,
            "nosso_numero"    => $nn
        );
    }
}
?>