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
// @(#) $Id: class.banco.santander.php,v 1.1 2007/01/18 19:43:35 leonardo.kopp Exp $
//

require_once(BOLETO_INC_PATH . "boletos" . BOLETO_SEPARADOR . "bancos" . BOLETO_SEPARADOR . "class.banco.comum.php");

class Boleto_Banco_Santander extends Boleto_Banco_Comum
{
    function geraDadosBanco($info)
    {
        // Montagem do calculo para o nosso numero
        $agencia = substr($info["agencia"], 0, 3);
        $contacedente = substr($info["conta_cedente"], 0, 6);

        $nnstring = "$agencia$contacedente" . $info["nosso_numero"];
        $dv1 = $this->_modulo11($nnstring);
        $nnstring = "$nnstring$dv1";
        $dv2 = $this->_modulo11($nnstring);

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
        $nn = "$nnum$dv1$dv2";
        $moeda = "9";
        $codbank = 353;
        $agcod = $agencia . $contacedente;
        // 43 numeros para o calculo do digito verificador
        $dvcampo = "$codbank$moeda$fatorvcto$valor$agcod$nn" . "00000";

        $dv = $this->_modulo11($dvcampo);
        // Numero para o codigo de barras com 44 digitos
        $num = "$codbank$moeda$dv$fatorvcto$valor$agcod$nn" . "00000";

        // Devolve a linha digitavel
        $linha_digitavel = $this->_montaLinha($num);
        $codigo_banco = $this->_geraCodigoBanco($codbank);

        $p1 = substr($agcod, 0, 3);
        $p2 = substr($agcod, 3, 1);
        $p3 = substr($agcod, 4, 8); 
        $agcod = "$p1-$p2/$p3"; 

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