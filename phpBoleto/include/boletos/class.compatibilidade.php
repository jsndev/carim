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
// @(#) $Id: class.compatibilidade.php,v 1.1 2007/01/18 19:43:34 leonardo.kopp Exp $
//


/**
 * A classe Boleto_Compatibilidade � usada para configurar manualmente valores
 * que precisam funcionar compativelmente em v�rias configura��es do PHP, como
 * o tamanho de fontes. Entre a vers�o 1.62 e 2.01 da biblioteca GD ocorreram
 * mudan�as que tornaram os valores para o tamanho das fontes errados, fazendo
 * o texto parecer menor do que era na vers�o 1.62.
 *
 * @version  2
 * @author   Jo�o Prado Maia <jpm@phpbrasil.com>
 */

class Boleto_Compatibilidade
{
    /**
     * M�todo usado para checar pela vers�o atual da biblioteca GD e retornar
     * o tamanho apropriado para as v�rias partes do boleto.
     *
     * @access  public
     * @return  array Vetor associativo com o tamanho da fonte para as v�rias partes do boleto
     */
    function pegaValoresTamanhoFonte()
    {
        if (!@function_exists("ImageColorClosestAlpha")) {
            // vers�o GD menor que 2.01
            $tamanhos = array(
                "nome_banco"          => 26,
                "codigo_banco"        => 26,
                "uso_do_banco"        => "",
                "linha_digitavel"     => 15,
                "cedente"             => "",
                "vencimento"          => "",
                "nosso_numero"        => "",
                "numero_documento"    => "",
                "especie_documento"   => "",
                "data_documento"      => "",
                "agencia_codigo"      => "",
                "valor_documento"     => "",
                "acrescimos"          => "",
                "valor_cobrado"       => "",
                "sacado"              => "",
                "cpf"                 => "",
                "local_pagamento"     => "",
                "sacador"             => "",
                "data_processamento"  => "",
                "carteira"            => "",
                "especificacao_moeda" => "",
                "quantidade"          => "",
                "valor_moeda"         => "",
                "descontos"           => "",
                "deducoes"            => "",
                "multa"               => "",
                "instrucoes_linha1"   => "",
                "instrucoes_linha2"   => "",
                "instrucoes_linha3"   => "",
                "instrucoes_linha4"   => "",
                "instrucoes_linha5"   => ""
            );
        } else {
            // vers�o GD 2.01 ou maior
            $tamanhos = array(
                "nome_banco"          => 21,
                "codigo_banco"        => 19,
                "uso_do_banco"        => "",
                "linha_digitavel"     => 10.8,
                "cedente"             => "",
                "vencimento"          => "",
                "nosso_numero"        => "",
                "numero_documento"    => "",
                "especie_documento"   => "",
                "data_documento"      => "",
                "agencia_codigo"      => "",
                "valor_documento"     => "",
                "acrescimos"          => "",
                "valor_cobrado"       => "",
                "sacado"              => "",
                "cpf"                 => "",
                "local_pagamento"     => "",
                "sacador"             => "",
                "data_processamento"  => "",
                "carteira"            => "",
                "especificacao_moeda" => "",
                "quantidade"          => "",
                "valor_moeda"         => "",
                "descontos"           => "",
                "deducoes"            => "",
                "multa"               => "",
                "instrucoes_linha1"   => "",
                "instrucoes_linha2"   => "",
                "instrucoes_linha3"   => "",
                "instrucoes_linha4"   => "",
                "instrucoes_linha5"   => ""
            );
        }
        return $tamanhos;
    }

    /**
     * M�todo usado para retornar o tamanho padr�o para fontes TrueType.
     *
     * @access  public
     * @return  int Tamanho padr�o para fontes TrueType
     */
    function pegaTamanhoTruetypePadrao()
    {
        if (!@function_exists("ImageColorClosestAlpha")) {
            return 12;
        } else {
            return 10;
        }
    }
}
?>