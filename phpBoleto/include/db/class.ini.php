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
// @(#) $Id: class.ini.php,v 1.1 2007/01/18 19:43:35 leonardo.kopp Exp $
//


/**
 * A classe Boleto_DB_Ini implementa a API de acesso a bancos
 * de dados do phpBoleto para arquivos INI. Essa caracter�stica
 * � especialmente importante para usu�rios que n�o tem a op��o
 * de utilizar servidores de bancos de dados para armazenamento
 * das informa��es do phpBoleto.
 *
 * @version  2
 * @author   Jo�o Prado Maia <jpm@phpbrasil.com>
 */

require_once(BOLETO_INC_PATH . "db" . BOLETO_SEPARADOR . "class.comum.php");

class Boleto_DB_Ini extends Boleto_DB_Comum
{
    var $dbh;
    var $inidb;
    var $ini_path;

    /**
     * Constructor da classe.
     *
     * @see     _pegaCaminhoConfiguracao(), _pegaConfiguracaoINI()
     */
    function Boleto_DB_Ini()
    {
        $this->_pegaCaminhoConfiguracao();
        $inidata = $this->_pegaConfiguracaoINI();
    }

    /**
     * M�todo para converter strings de formato ASCII para c�digo
     * bin�rio.
     *
     * @access  private
     * @param   string $ascii Texto em formato ASCII
     * @return  string Valor em bin�rio do texto em formato ASCII
     */
    function _asc2bin($ascii)
    {
        $binary = "";
        while (strlen($ascii) > 0) {
            $byte = "";
            $i = 0;
            $byte = substr($ascii, 0, 1);
            while ($byte != chr($i)) {
                $i++;
            }
            $byte = base_convert($i, 10, 2);
            // This is an endian (architexture) specific line, you may need to alter it.
            $byte = str_repeat("0", (8 - strlen($byte))) . $byte;
            $ascii = substr($ascii, 1);
            $binary = "$binary$byte";
        }
        return $binary;
    }

    /**
     * M�todo para criar um objeto de acesso a arquivos INI
     * de acordo com o tipo de arquivo INI a ser usado.
     *
     * @access  private
     * @param   string $tipo Tipo do arquivo INI
     * @return  object 
     * @see     File_Ini()
     */
    function _conectaINI($tipo)
    {
        return new File_Ini($this->ini_path . $tipo . ".ini.php", "#");
    }

    /**
     * M�todo para determinar o pr�ximo ID a ser usado na grava��o
     * ao arquivo INI.
     *
     * @access  private
     * @param   object $db Objeto de acesso a banco de dados (API)
     * @return  int Pr�ximo ID a ser usado no arquivo INI
     * @see     getBlocknames()
     */
    function _pegaProximoID($db)
    {
        $blocos = $db->getBlocknames();
        sort($blocos);
        if (count($blocos) > 0) {
            return ($blocos[count($blocos) - 1] + 1);
        } else {
            return 0;
        }
    }

    /**
     * M�todo usado pela interface de administra��o para
     * adicionar novos boletos.
     *
     * @access  public
     * @return  void
     * @see     _conectaINI(), _pegaProximoID(), enableCache(), _asc2bin(), setIniValuesArray(), save()
     */
    function adicionarBoleto()
    {
        global $HTTP_POST_VARS;

        $boleto_db = $this->_conectaINI("boletos");
        $proximo_id = $this->_pegaProximoID($boleto_db);
        $boleto_db->enableCache("On");
        $itens = array(
            "bnid"              => $this->_asc2bin($HTTP_POST_VARS["bnid"]),
            "cid"               => $this->_asc2bin($HTTP_POST_VARS["cid"]),
            "titulo"            => $this->_asc2bin($HTTP_POST_VARS["titulo"]),
            "agencia"           => $this->_asc2bin($HTTP_POST_VARS["agencia"]),
            "cedente"           => $this->_asc2bin($HTTP_POST_VARS["cedente"]),
            "conta_cedente"     => $this->_asc2bin($HTTP_POST_VARS["conta_cedente"]),
            "especie_documento" => $this->_asc2bin($HTTP_POST_VARS["especie_documento"]),
            "codigo"            => $this->_asc2bin($HTTP_POST_VARS["codigo"]),
            "sacado"            => $this->_asc2bin($HTTP_POST_VARS["sacado"]),
            "cpf"               => $this->_asc2bin($HTTP_POST_VARS["cpf"]),
            "local_pagamento"   => $this->_asc2bin($HTTP_POST_VARS["local_pagamento"]),
            "sacador"           => $this->_asc2bin($HTTP_POST_VARS["sacador"]),
            "carteira"          => $this->_asc2bin($HTTP_POST_VARS["carteira"]),
            "instrucoes_linha1" => $this->_asc2bin($HTTP_POST_VARS["instrucoes_linha1"]),
            "instrucoes_linha2" => $this->_asc2bin($HTTP_POST_VARS["instrucoes_linha2"]),
            "instrucoes_linha3" => $this->_asc2bin($HTTP_POST_VARS["instrucoes_linha3"]),
            "instrucoes_linha4" => $this->_asc2bin($HTTP_POST_VARS["instrucoes_linha4"]),
            "instrucoes_linha5" => $this->_asc2bin($HTTP_POST_VARS["instrucoes_linha5"])
        );
        $boleto_db->setIniValuesArray($proximo_id, $itens);
        $boleto_db->save();
    }

    /**
     * M�todo usado pela interface de administra��o para
     * adicionar novos bancos.
     *
     * @access  public
     * @return  void
     * @see     _conectaINI(), _pegaProximoID(), enableCache(), _asc2bin(), setIniValuesArray(), save()
     */
    function adicionarBanco()
    {
        global $HTTP_POST_VARS;

        $banco_db = $this->_conectaINI("bancos");
        $proximo_id = $this->_pegaProximoID($banco_db);
        $banco_db->enableCache("On");
        $itens = array(
            "layout"       => $this->_asc2bin($HTTP_POST_VARS["layout"]),
            "nome"         => $this->_asc2bin($HTTP_POST_VARS["nome"]),
            "codigo"       => $this->_asc2bin($HTTP_POST_VARS["codigo"]),
            "uso_do_banco" => $this->_asc2bin($HTTP_POST_VARS["uso_do_banco"])
        );
        $banco_db->setIniValuesArray($proximo_id, $itens);
        $banco_db->save();
    }

    /**
     * M�todo usado pela interface de administra��o para
     * adicionar novas configura��es personalizadas.
     *
     * @access  public
     * @return  void
     * @see     _conectaINI(), _pegaProximoID(), enableCache(), _asc2bin(), setIniValuesArray(), save()
     */
    function adicionarConfiguracao()
    {
        global $HTTP_POST_VARS;

        $boleto_db = $this->_conectaINI("configs");
        $proximo_id = $this->_pegaProximoID($boleto_db);
        $boleto_db->enableCache("On");
        $itens = array(
            "titulo"          => $this->_asc2bin($HTTP_POST_VARS["titulo"]),
            "enviar_email"    => $this->_asc2bin($HTTP_POST_VARS["enviar_email"]),
            "remetente"       => $this->_asc2bin($HTTP_POST_VARS["remetente"]),
            "remetente_email" => $this->_asc2bin($HTTP_POST_VARS["remetente_email"]),
            "assunto"         => $this->_asc2bin($HTTP_POST_VARS["assunto"]),
            "servidor_smtp"   => $this->_asc2bin($HTTP_POST_VARS["servidor_smtp"]),
            "servidor_http"   => $this->_asc2bin($HTTP_POST_VARS["servidor_http"]),
            "imagem_tipo"     => $this->_asc2bin($HTTP_POST_VARS["imagem_tipo"]),
            "usar_truetype"   => $this->_asc2bin($HTTP_POST_VARS["usar_truetype"]),
            "enviar_pdf"      => $this->_asc2bin($HTTP_POST_VARS["enviar_pdf"]),
            "mensagem_texto"  => $this->_asc2bin($HTTP_POST_VARS["mensagem_texto"]),
            "mensagem_html"   => $this->_asc2bin($HTTP_POST_VARS["mensagem_html"])
        );
        $boleto_db->setIniValuesArray($proximo_id, $itens);
        $boleto_db->save();
    }

    /**
     * M�todo usado pela interface de administra��o para
     * atualizar os dados de boletos.
     *
     * @access  public
     * @return  void
     * @see     _conectaINI(), _pegaProximoID(), enableCache(), _asc2bin(), setIniValuesArray(), save()
     */
    function atualizarBoleto()
    {
        global $HTTP_POST_VARS;

        $boleto_db = $this->_conectaINI("boletos");
        $boleto_db->enableCache("On");
        $itens = array(
            "bnid"              => $this->_asc2bin($HTTP_POST_VARS["bnid"]),
            "cid"               => $this->_asc2bin($HTTP_POST_VARS["cid"]),
            "titulo"            => $this->_asc2bin($HTTP_POST_VARS["titulo"]),
            "agencia"           => $this->_asc2bin($HTTP_POST_VARS["agencia"]),
            "cedente"           => $this->_asc2bin($HTTP_POST_VARS["cedente"]),
            "conta_cedente"     => $this->_asc2bin($HTTP_POST_VARS["conta_cedente"]),
            "especie_documento" => $this->_asc2bin($HTTP_POST_VARS["especie_documento"]),
            "codigo"            => $this->_asc2bin($HTTP_POST_VARS["codigo"]),
            "sacado"            => $this->_asc2bin($HTTP_POST_VARS["sacado"]),
            "cpf"               => $this->_asc2bin($HTTP_POST_VARS["cpf"]),
            "local_pagamento"   => $this->_asc2bin($HTTP_POST_VARS["local_pagamento"]),
            "sacador"           => $this->_asc2bin($HTTP_POST_VARS["sacador"]),
            "carteira"          => $this->_asc2bin($HTTP_POST_VARS["carteira"]),
            "instrucoes_linha1" => $this->_asc2bin($HTTP_POST_VARS["instrucoes_linha1"]),
            "instrucoes_linha2" => $this->_asc2bin($HTTP_POST_VARS["instrucoes_linha2"]),
            "instrucoes_linha3" => $this->_asc2bin($HTTP_POST_VARS["instrucoes_linha3"]),
            "instrucoes_linha4" => $this->_asc2bin($HTTP_POST_VARS["instrucoes_linha4"]),
            "instrucoes_linha5" => $this->_asc2bin($HTTP_POST_VARS["instrucoes_linha5"])
        );
        $boleto_db->setIniValuesArray($HTTP_POST_VARS["bid"], $itens);
        $boleto_db->save();
    }

    /**
     * M�todo usado pela interface de administra��o para
     * atualizar os dados de bancos.
     *
     * @access  public
     * @return  void
     * @see     _conectaINI(), _pegaProximoID(), enableCache(), _asc2bin(), setIniValuesArray(), save()
     */
    function atualizarBanco()
    {
        global $HTTP_POST_VARS;

        $banco_db = $this->_conectaINI("bancos");
        $banco_db->enableCache("On");
        $itens = array(
            "layout"       => $this->_asc2bin($HTTP_POST_VARS["layout"]),
            "nome"         => $this->_asc2bin($HTTP_POST_VARS["nome"]),
            "codigo"       => $this->_asc2bin($HTTP_POST_VARS["codigo"]),
            "uso_do_banco" => $this->_asc2bin($HTTP_POST_VARS["uso_do_banco"])
        );
        $banco_db->setIniValuesArray($HTTP_POST_VARS["bnid"], $itens);
        $banco_db->save();
    }

    /**
     * M�todo usado pela interface de administra��o para
     * atualizar os dados de configura��es personalizadas.
     *
     * @access  public
     * @return  void
     * @see     _conectaINI(), _pegaProximoID(), enableCache(), _asc2bin(), setIniValuesArray(), save()
     */
    function atualizarConfiguracao()
    {
        global $HTTP_POST_VARS;

        $boleto_db = $this->_conectaINI("configs");
        $boleto_db->enableCache("On");
        $itens = array(
            "titulo"          => $this->_asc2bin($HTTP_POST_VARS["titulo"]),
            "enviar_email"    => $this->_asc2bin($HTTP_POST_VARS["enviar_email"]),
            "remetente"       => $this->_asc2bin($HTTP_POST_VARS["remetente"]),
            "remetente_email" => $this->_asc2bin($HTTP_POST_VARS["remetente_email"]),
            "assunto"         => $this->_asc2bin($HTTP_POST_VARS["assunto"]),
            "servidor_smtp"   => $this->_asc2bin($HTTP_POST_VARS["servidor_smtp"]),
            "servidor_http"   => $this->_asc2bin($HTTP_POST_VARS["servidor_http"]),
            "imagem_tipo"     => $this->_asc2bin($HTTP_POST_VARS["imagem_tipo"]),
            "usar_truetype"   => $this->_asc2bin($HTTP_POST_VARS["usar_truetype"]),
            "enviar_pdf"      => $this->_asc2bin($HTTP_POST_VARS["enviar_pdf"]),
            "mensagem_texto"  => $this->_asc2bin($HTTP_POST_VARS["mensagem_texto"]),
            "mensagem_html"   => $this->_asc2bin($HTTP_POST_VARS["mensagem_html"])
        );
        $boleto_db->setIniValuesArray($HTTP_POST_VARS["cid"], $itens);
        $boleto_db->save();
    }

    /**
     * M�todo para pegar o nome do arquivo de layout do banco.
     *
     * @access  public
     * @param   string $nome_banco Nome extenso do banco
     * @return  string O nome do arquivo de layout
     * @see     _conectaINI(), getAllBlockValues()
     */
    function pegaNomeLayout($nome_banco)
    {
        $banco_db = $this->_conectaINI("bancos");
        $bnid_array = $banco_db->getAllBlockValues("bnid", "bin2asc");
        for ($i = 0; $i < count($bnid_array); $i++) {
            if ($bnid_array[$i]["nome"] == $nome_banco) {
                $layout = $bnid_array[$i]["layout"];
                break;
            }
        }
        return $layout;
    }

    /**
     * M�todo usado pela interface de administra��o para popular
     * o formul�rio de edi��o de boletos.
     *
     * @access  public
     * @param   int $bid ID do boleto
     * @return  array Vetor com os valores convertidos de bin�rio para ASCII
     * @see     _conectaINI(), getBlockValues()
     */
    function pegaDadosBoleto($bid)
    {
        $boleto_db = $this->_conectaINI("boletos");
        return $boleto_db->getBlockValues($bid, "bin2asc");
    }

    /**
     * M�todo usado pela interface de administra��o para popular
     * o formul�rio de edi��o de bancos.
     *
     * @access  public
     * @param   int $bnid ID do banco
     * @return  array Vetor com os valores convertidos de bin�rio para ASCII
     * @see     _conectaINI(), getBlockValues()
     */
    function pegaDadosBanco($bnid)
    {
        $banco_db = $this->_conectaINI("bancos");
        return $banco_db->getBlockValues($bnid, "bin2asc");
    }

    /**
     * M�todo usado pela interface de administra��o para popular
     * o formul�rio de edi��o de configura��es personalizadas.
     *
     * @access  public
     * @param   int $cid ID da configura��o
     * @return  array Vetor com os valores convertidos de bin�rio para ASCII
     * @see     _conectaINI(), getBlockValues()
     */
    function pegaDadosConfiguracao($cid)
    {
        $config_db = $this->_conectaINI("configs");
        return $config_db->getBlockValues($cid, "bin2asc");
    }

    /**
     * M�todo usado pelas classes de gera��o de boletos para
     * determinar as op��es selecionadas na interface de
     * administra��o.
     *
     * @access  public
     * @param   int $id_boleto ID do boleto
     * @return  array Vetor com os valores convertidos de bin�rio para ASCII
     * @see     _conectaINI(), getBlockValues()
     */
    function pegaOpcoesBoleto($id_boleto)
    {
        $boleto_db = $this->_conectaINI("boletos");
        $banco_db = $this->_conectaINI("bancos");

        $bid_array = $boleto_db->getBlockValues($id_boleto, "bin2asc");
        if (count($bid_array) == 0) {
            return "Boleto_Erro";
        } else {
            $bnid = $bid_array["bnid"];
            $bn_array = $banco_db->getBlockValues($bnid, "bin2asc");
            if (count($bn_array) == 0) {
                return "Boleto_Erro";
            } else {
                $bid_array["nome_banco"] = $bn_array["nome"];
                $bid_array["codigo_banco"] = $bn_array["codigo"];
                $bid_array["uso_do_banco"] = $bn_array["uso_do_banco"];
                return $bid_array;
            }
        }
    }

    /**
     * M�todo usado pelas classes de gera��o de boletos para
     * determinar as op��es selecionadas na interface de
     * administra��o.
     *
     * @access  public
     * @param   int $id_config ID da configura��o
     * @return  array Vetor com os valores convertidos de bin�rio para ASCII
     * @see     _conectaINI(), getBlockValues()
     */
    function pegaOpcoesConfig($id_config)
    {
        $config_db = $this->_conectaINI("configs");
        $cfg_array = $config_db->getBlockValues($id_config, "bin2asc");
        if (count($cfg_array) == 0) {
            return "Boleto_Erro";
        } else {
            return $cfg_array;
        }
    }

    /**
     * M�todo usado pela interface de administra��o para
     * listar os boletos dispon�veis no banco de dados.
     *
     * @access  public
     * @return  array Vetor com os valores convertidos de bin�rio para ASCII
     * @see     _conectaINI(), getBlockValues()
     */
    function listaBoletos()
    {
        $boleto_db = $this->_conectaINI("boletos");
        $banco_db = $this->_conectaINI("bancos");

        $bid_array = $boleto_db->getAllBlockValues("bid", "bin2asc");
        for ($i = 0; $i < count($bid_array); $i++) {
            $bnid = $bid_array[$i]["bnid"];
            $bn_array = $banco_db->getBlockValues($bnid, "bin2asc");
            $bid_array[$i]["nome_banco"] = $bn_array["nome"];
        }
        return $bid_array;
    }

    /**
     * M�todo usado pela interface de administra��o para
     * listar os bancos dispon�veis no banco de dados.
     *
     * @access  public
     * @return  array Vetor com os valores convertidos de bin�rio para ASCII
     * @see     _conectaINI(), getBlockValues()
     */
    function listaBancos()
    {
        $banco_db = $this->_conectaINI("bancos");
        return $banco_db->getAllBlockValues("bnid", "bin2asc");
    }

    /**
     * M�todo usado pela interface de administra��o para
     * listar as configura��es personalizadas dispon�veis no 
     * banco de dados.
     *
     * @access  public
     * @return  array Vetor com os valores convertidos de bin�rio para ASCII
     * @see     _conectaINI(), getBlockValues()
     */
    function listaConfiguracoes()
    {
        $config_db = $this->_conectaINI("configs");
        return $config_db->getAllBlockValues("cid", "bin2asc");
    }

    /**
     * M�todo usado pela interface de administra��o para
     * deletar boletos dispon�veis no banco de dados.
     *
     * @access  public
     * @param   array $boletos Vetor com a lista de boletos
     * @return  void
     * @see     _conectaINI(), removeBlocks()
     */
    function deletarBoletos($boletos)
    {
        $boleto_db = $this->_conectaINI("boletos");
        $boleto_db->removeBlocks($boletos);
    }

    /**
     * M�todo usado pela interface de administra��o para
     * deletar bancos dispon�veis no banco de dados.
     *
     * @access  public
     * @param   array $bancos Vetor com a lista de bancos
     * @return  void
     * @see     _conectaINI(), removeBlocks()
     */
    function deletarBancos($bancos)
    {
        $banco_db = $this->_conectaINI("bancos");
        $banco_db->removeBlocks($bancos);
    }

    /**
     * M�todo usado pela interface de administra��o para
     * deletar configura��es personalizadas dispon�veis no 
     * banco de dados.
     *
     * @access  public
     * @param   array $configs Vetor com a lista de configura��es
     * @return  void
     * @see     _conectaINI(), removeBlocks()
     */
    function deletarConfiguracoes($configs)
    {
        $config_db = $this->_conectaINI("configs");
        $config_db->removeBlocks($configs);
    }
}
?>