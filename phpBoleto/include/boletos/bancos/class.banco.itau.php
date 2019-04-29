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
// | Modificado para o Banco Itaú:                                        |
// |          Claudio Pereira <cpereira@brasilenergia.com.br>             |
// +----------------------------------------------------------------------+
//
// @(#) $Id: class.banco.itau.php,v 1.1 2007/01/18 19:43:35 leonardo.kopp Exp $
//

require_once(BOLETO_INC_PATH . "boletos" . BOLETO_SEPARADOR . "bancos" . BOLETO_SEPARADOR . "class.banco.comum.php");

class Boleto_Banco_Itau extends Boleto_Banco_Comum
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
        $cart = "175";
        $nnum = sprintf("%09d", $info["nosso_numero"]);
        $nosso_numero = sprintf("%09d", $info["nosso_numero"]);
        $nnum = "$cart$ano$nnum";
        $dv = $this->_digitoVerificador($nnum);
        $nnum = "$nnum$dv";
        $zero = "000";

        // Montagem da agencia e conta cedente
        $agencia = substr($info["agencia"], 0, 4);
        $contacedente = substr($info["conta_cedente"], 0, 7);
        $contacedente_sdac = substr($contacedente, 0, strlen($contacedente)-1);

        // nosso numero sem o digito, com 13 caracteres
        $nn = substr($nnum, 0, 13);
        $moeda = "9";
        $codbank = 341;
        $DAC_ACCN=$this->_modulo10("$agencia$contacedente_sdac$cart$nosso_numero");
        $DAC_ACC=$this->_modulo10("$agencia$contacedente_sdac");
        $DAC_NN=$this->_modulo10("$agencia$contacedente_sdac$nosso_numero");

        // 43 numeros para o calculo do digito verificador
        $dvcampo = "$codbank$moeda$fatorvcto$valor$cart".substr($info["nosso_numero"],-8)."$DAC_NN$agencia$contacedente$zero";
        $dv =  $this->_digitoVerificador($dvcampo);
        // Numero para o codigo de barras com 44 digitos
        $num = "$codbank$moeda$dv$fatorvcto$valor$cart".substr($info["nosso_numero"],-8)."$DAC_NN$agencia$contacedente$zero";

        // Devolve a linha digitavel
        $linha_digitavel = $this->_montaLinha($num);
        $codigo_banco = $this->_geraCodigoBanco($codbank);

        // nosso numero
        $nosso_numero = "$cart/$nosso_numero-$DAC_NN";

        // agencia/codigo cedente
        $agencia_codigo = "$agencia/$contacedente_sdac-$DAC_ACC"; //
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
        $digito = $this->_modulo11($numero);
        if (in_array((int)$digito,array(0,1,10,11))) {
            $digito = 1;
    }
        return $digito;
    }

    function _modulo10($num)
    {
        $numtotal10 = 0;
        $fator = 2;

        // Separacao dos numeros
        for ($i = strlen($num); $i > 0; $i--) {
            // pega cada numero isoladamente
            $numeros[$i] = substr($num,$i-1,1);
            // Efetua multiplicacao do numero pelo (falor 10)
            // 2002-07-07 01:33:34 Macete para adequar ao Mod10 do Itaú
            $temp = $numeros[$i] * $fator; 
            $temp0=0;
            foreach (preg_split('//',$temp,-1,PREG_SPLIT_NO_EMPTY) as $k=>$v){ $temp0+=$v; }
            $parcial10[$i] = $temp0; //$numeros[$i] * $fator;
            // monta sequencia para soma dos digitos no (modulo 10)
            $numtotal10 += $parcial10[$i];
            if ($fator == 2) {
                $fator = 1;
            } else {
                $fator = 2; // intercala fator de multiplicacao (modulo 10)
            }
        }

        // várias linhas removidas, vide função original
        // Calculo do modulo 10
        $resto = $numtotal10 % 10;
        $digito = 10 - $resto;
        if ($resto == 0) {
            $digito = 0;
        }

        return $digito;
    }

    function _montaLinha($codigo)
    {
        // 2002-07-06 19:41:28 alterado para Itaú
        // Posição  Conteúdo
        // Campo 1
        // 1 a 3  Número do Banco
        // 4    Código da Moeda - 9 para Real
        // 5 a 7  Código da carteira (175)
        // 8 a 9  Dois primeiros dígitos do nosso número
        // 10   DAC campo 1 (Mod10)
        //
        // Campo 2
        // 1 a 6  Restante do Nosso Número
        // 7    DAC de Agência/Conta/Carteira/NossoNúmero
        // 8 a 10 Três primeiros dígitos da Agência
        // 11     DAC campo 2 (Mod10)
        //
        // Campo 3
        // 1    Restante do número da Ag
        // 2 a 7  Conta corrente + DAC
        // 8 a 10 Zeros (Não utilizado)
        // 11   DAC campo 3 (Mod10)
        //
        // Campo 4
        // 1    DAC do Código de Barras
        //
        // Campo 5
        // 1 a 4  Fator de Vencimento
        // 5 a 14 Valor do Título

        $banco    = substr($codigo,0,3);
        $moeda    = substr($codigo,3,1);
        $k        = substr($codigo,4,1);
        $fator    = substr($codigo,5,4);
        $valor    = substr($codigo,9,10);
        $carteira = substr($codigo,19,3);
        $nn       = substr($codigo,22,9);
        $agencia  = substr($codigo,31,4);
        $conta    = substr($codigo,35,6);
        $zeros    = substr($codigo,41,3);

        // 1. Campo - composto pelo código do banco, código da moeda, carteira, dois primeiros dígitos
        // do noosso número e DAC (modulo10) deste campo
        $p1 = "$banco$moeda".$carteira[0].substr($carteira,-2).substr($nn,0,2);
        $dv_1 = $this->_modulo10($p1);
        $campo1 = substr($p1,0,5).'.'.substr($p1,-4).$dv_1;

        // 2. Campo - restante do Nosso Numero, DAC NN, três primeiros digitos da agenca + DAC
        $p1 = substr($nn,-7).substr($agencia,0,3);
        $dv_2 = $this->_modulo10($p1);
        $campo2 = substr($p1,0,5).'.'.substr($p1,-5).$dv_2;

        // 3. Campo composto por: último digito da agencia, conta, zeros + DAC
        $p1 = substr($agencia,-1).$conta.$zeros;
        $dv_3 = $this->_modulo10($p1);
        $campo3 = substr($p1,0,5).'.'.substr($p1,-5).$dv_3;

        // 4. Campo - digito verificador do codigo de barras
        $campo4 = $k;

        // 5. Campo composto pelo valor nominal pelo valor nominal do documento, sem
        // indicacao de zeros a esquerda e sem edicao (sem ponto e virgula). Quando se
        // tratar de valor zerado, a representacao deve ser 000 (tres zeros).
        $campo5 = $fator.$valor;

        return "$campo1 $campo2 $campo3 $campo4 $campo5"; 
    }
}
?>