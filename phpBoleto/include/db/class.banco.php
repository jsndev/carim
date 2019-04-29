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
// @(#) $Id: class.banco.php,v 1.1 2007/01/18 19:43:35 leonardo.kopp Exp $
//


/**
 * A classe Boleto_DB_Banco disponibiliza uma API para o
 * o uso de opera��es com o banco de dados, seja ele na
 * forma de um servidor de banco de dados completo como
 * o PostgreSQL ou at� numa interface mais simples de
 * arquivos INI. Essa API serve como um "wrapper" em
 * volta dos m�todos reais que lidam diretamente com esses
 * bancos de dados.
 *
 * @version  2
 * @author   Jo�o Prado Maia <jpm@phpbrasil.com>
 */

require_once(BOLETO_INC_PATH . "db" . BOLETO_SEPARADOR . "class.comum.php");

class Boleto_DB_Banco extends Boleto_DB_Comum
{
    var $dbh;
    var $inidb;
    var $ini_path;

    /**
     * Constructor da classe. Inicializa algumas variaveis e
     * conecta com o servidor de banco de dados utilizando
     * para isso o pacote PEAR::DB de abstra��o de acesso
     * a bancos de dados.
     *
     * @see     _pegaCaminhoConfiguracao(), _pegaConfiguracaoINI(), connect()
     */
    function Boleto_DB_Banco()
    {
        $this->_pegaCaminhoConfiguracao();
        $inidata = $this->_pegaConfiguracaoINI();
        require_once("DB.php");
        $dsn = array(
            "phptype"  => $inidata["BOLETO_DBTYPE"],
            "hostspec" => $inidata["BOLETO_DBHOST"],
            'database' => $inidata["BOLETO_DBNAME"],
            'username' => $inidata["BOLETO_DBUSER"],
            'password' => $inidata["BOLETO_DBPASS"]
        );
        $this->dbh = DB::connect($dsn);
        // consertando um bug do DB/common.php
        $this->dbh->prepare_maxstmt = 0;
    }

    /**
     * M�todo para pegar o nome do arquivo de layout do banco.
     *
     * @access  public
     * @param   string $nome_banco Nome extenso do banco
     * @return  string O nome do arquivo de layout
     * @see     getOne()
     */
    function pegaNomeLayout($nome_banco)
    {
        $stmt = "SELECT
                    layout
                 FROM
                    bancos
                 WHERE
                    nome='$nome_banco'";
        return $this->dbh->getOne($stmt);
    }

    /**
     * M�todo usado na interface de gera��o de boletos
     * para determinar as op��es editadas na interface
     * de administra��o.
     *
     * @access  public
     * @param   int $id_boleto ID do Boleto
     * @return  array Vetor associativo com o result-set
     * @see     getRow()
     */
    function pegaOpcoesBoleto($id_boleto)
    {
        $stmt = "SELECT 
                    A.nome AS nome_banco, 
                    A.codigo AS codigo_banco, 
                    A.uso_do_banco, 
                    B.cid, 
                    B.agencia, 
                    B.cedente, 
                    B.conta_cedente, 
                    B.especie_documento, 
                    B.codigo, 
                    B.sacado, 
                    B.cpf, 
                    B.local_pagamento, 
                    B.sacador, 
                    B.carteira, 
                    B.instrucoes_linha1, 
                    B.instrucoes_linha2, 
                    B.instrucoes_linha3, 
                    B.instrucoes_linha4, 
                    B.instrucoes_linha5 
                 FROM
                    bancos A, 
                    boletos B 
                 WHERE 
                    B.bnid=A.bnid AND 
                    B.bid=$id_boleto";
        $opcoes = $this->dbh->getRow($stmt, DB_FETCHMODE_ASSOC);
        if ($opcoes == false) {
            return "Boleto_Erro";
        } else {
            return $opcoes;
        }
    }

    /**
     * M�todo usado na interface de gera��o de boletos
     * para determinar as op��es editadas na interface
     * de administra��o.
     *
     * @access  public
     * @param   int $id_config ID da configura��o
     * @return  array Vetor associativo com o result-set
     * @see     getRow()
     */
    function pegaOpcoesConfig($id_config)
    {
        $stmt = "SELECT * FROM config WHERE cid=$id_config";
        $opcoes = $this->dbh->getRow($stmt, DB_FETCHMODE_ASSOC);
        if ($opcoes == false) {
            return "Boleto_Erro";
        } else {
            return $opcoes;
        }
    }

    /**
     * M�todo usado pela interface de administra��o do phpBoleto
     * para listar os boletos existentes. Um vetor multi-
     * dimensional ser� retornado.
     *
     * @access  public
     * @return  array Vetor multi-dimensional com o result-set
     * @see     simpleQuery(), numRows(), fetchRow()
     */
    function listaBoletos()
    {
        $temp = array();

        $stmt = "SELECT
                    A.nome AS nome_banco,
                    B.titulo,
                    B.bid
                 FROM
                    bancos A,
                    boletos B
                 WHERE
                    A.bnid=B.bnid";
        $result = $this->dbh->simpleQuery($stmt);
        for ($i = 0; $i < $this->dbh->numRows($result); $i++) {
            $row = $this->dbh->fetchRow($result, DB_FETCHMODE_ASSOC);
            $temp[$i] = $row;
        }
        return $temp;
    }

    /**
     * M�todo usado pela interface de administra��o do phpBoleto
     * para listar os bancos existentes.
     *
     * @access  public
     * @return  array Vetor multi-dimensional com o result-set
     * @see     getAll()
     */
    function listaBancos()
    {
        $stmt = "SELECT bnid, layout, nome, codigo FROM bancos ORDER BY nome ASC";
        return $this->dbh->getAll($stmt, DB_FETCHMODE_ASSOC);
    }

    /**
     * M�todo usado pela interface de administra��o do phpBoleto
     * para listar as configura��es existentes.
     *
     * @access  public
     * @return  array Vetor multi-dimensional com o result-set
     * @see     getAll()
     */
    function listaConfiguracoes()
    {
        $stmt = "SELECT cid, titulo, enviar_email, enviar_pdf FROM config ORDER BY titulo ASC";
        return $this->dbh->getAll($stmt, DB_FETCHMODE_ASSOC);
    }

    /**
     * M�todo usado pela interface de administra��o do phpBoleto
     * para deletar boletos.
     *
     * @access  public
     * @param   array $boletos Vetor com IDs de boletos
     * @return  void
     * @see     simpleQuery()
     */
    function deletarBoletos($boletos)
    {
        $lista = implode(", ", $boletos);
        $stmt = "DELETE FROM boletos ";
        $stmt .= "WHERE bid IN ($lista)";
        $this->dbh->simpleQuery($stmt);
    }

    /**
     * M�todo usado pela interface de administra��o do phpBoleto
     * para deletar bancos.
     *
     * @access  public
     * @param   array $bancos Vetor com IDs de bancos
     * @return  void
     * @see     simpleQuery()
     */
    function deletarBancos($bancos)
    {
        $lista = implode(", ", $bancos);
        $stmt = "DELETE FROM bancos ";
        $stmt .= "WHERE bnid IN ($lista)";
        $this->dbh->simpleQuery($stmt);
    }

    /**
     * M�todo usado pela interface de administra��o do phpBoleto
     * para deletar configura��es.
     *
     * @access  public
     * @param   array $configs Vetor com IDs de configura��es
     * @return  void
     * @see     simpleQuery()
     */
    function deletarConfiguracoes($configs)
    {
        $lista = implode(", ", $configs);
        $stmt = "DELETE FROM config ";
        $stmt .= "WHERE cid IN ($lista)";
        $this->dbh->simpleQuery($stmt);
    }

    /**
     * M�todo usado pela interface de administra��o do phpBoleto
     * para popular o formul�rio de edi��o de boletos.
     *
     * @access  public
     * @param   int $bid ID do boleto
     * @return  array Vetor multi-dimensional com o result-set
     * @see     getRow()
     */
    function pegaDadosBoleto($bid)
    {
        $stmt = "SELECT
                    titulo,
                    bnid,
                    cid,
                    agencia,
                    cedente,
                    conta_cedente,
                    especie_documento,
                    codigo,
                    sacado,
                    cpf,
                    local_pagamento,
                    sacador,
                    carteira,
                    instrucoes_linha1,
                    instrucoes_linha2,
                    instrucoes_linha3,
                    instrucoes_linha4,
                    instrucoes_linha5
                 FROM
                    boletos
                 WHERE
                    bid=$bid";
        return $this->dbh->getRow($stmt, DB_FETCHMODE_ASSOC);
    }

    /**
     * M�todo usado pela interface de administra��o do phpBoleto
     * para popular o formul�rio de edi��o de bancos.
     *
     * @access  public
     * @param   int $bnid ID do banco
     * @return  array Vetor multi-dimensional com o result-set
     * @see     getRow()
     */
    function pegaDadosBanco($bnid)
    {
        $stmt = "SELECT * FROM bancos WHERE bnid=$bnid";
        return $this->dbh->getRow($stmt, DB_FETCHMODE_ASSOC);
    }

    /**
     * M�todo usado pela interface de administra��o do phpBoleto
     * para popular o formul�rio de edi��o de configura��es
     * personalizadas.
     *
     * @access  public
     * @param   int $cid ID da configura��o
     * @return  array Vetor multi-dimensional com o result-set
     * @see     getRow()
     */
    function pegaDadosConfiguracao($cid)
    {
        $stmt = "SELECT * FROM config WHERE cid=$cid";
        return $this->dbh->getRow($stmt, DB_FETCHMODE_ASSOC);
    }

    /**
     * M�todo usado pela interface de administra��o para adicionar
     * novos boletos ao banco de dados.
     *
     * @access  public
     * @return  void
     * @see     simpleQuery()
     */
    function adicionarBoleto()
    {
        global $HTTP_POST_VARS;

        $stmt = "INSERT INTO boletos
                    (bnid, cid, titulo, agencia, cedente, conta_cedente, especie_documento, codigo, sacado,
                    cpf, local_pagamento, sacador, carteira, instrucoes_linha1,
                    instrucoes_linha2, instrucoes_linha3, instrucoes_linha4, instrucoes_linha5)
                 VALUES
                    (" . $HTTP_POST_VARS["bnid"] . ", " . $HTTP_POST_VARS["cid"] . ", '" .
                    rodaSlashes($HTTP_POST_VARS["titulo"]) . "', '" . rodaSlashes($HTTP_POST_VARS["agencia"]) . "', '" . 
                    rodaSlashes($HTTP_POST_VARS["cedente"]) . "', '" . rodaSlashes($HTTP_POST_VARS["conta_cedente"]) . "', '" .
                    rodaSlashes($HTTP_POST_VARS["especie_documento"]) . "', '" . rodaSlashes($HTTP_POST_VARS["codigo"]) . "', '" .
                    rodaSlashes($HTTP_POST_VARS["sacado"]) . "', '" . rodaSlashes($HTTP_POST_VARS["cpf"]) . "', '" .
                    rodaSlashes($HTTP_POST_VARS["local_pagamento"]) . "', '" . rodaSlashes($HTTP_POST_VARS["sacador"]) . "', '" .
                    rodaSlashes($HTTP_POST_VARS["carteira"]) . "', '" . rodaSlashes($HTTP_POST_VARS["instrucoes_linha1"]) . "', '" .
                    rodaSlashes($HTTP_POST_VARS["instrucoes_linha2"]) . "', '" . rodaSlashes($HTTP_POST_VARS["instrucoes_linha3"]) . "', '" .
                    rodaSlashes($HTTP_POST_VARS["instrucoes_linha4"]) . "', '" . rodaSlashes($HTTP_POST_VARS["instrucoes_linha5"]) . "')";
        $this->dbh->simpleQuery($stmt);
    }

    /**
     * M�todo usado pela interface de administra��o para adicionar
     * novos bancos ao banco de dados.
     *
     * @access  public
     * @return  void
     * @see     simpleQuery()
     */
    function adicionarBanco()
    {
        global $HTTP_POST_VARS;

        $stmt = "INSERT INTO bancos
                    (layout, nome, codigo, uso_do_banco)
                 VALUES
                    ('" . rodaSlashes($HTTP_POST_VARS["layout"]) . "', '" . 
                    rodaSlashes($HTTP_POST_VARS["nome"]) . "', '" . 
                    rodaSlashes($HTTP_POST_VARS["codigo"]) . "', '" . 
                    rodaSlashes($HTTP_POST_VARS["uso_do_banco"]) . "')";
        $this->dbh->simpleQuery($stmt);
    }

    /**
     * M�todo usado pela interface de administra��o para adicionar
     * novas configura��es ao banco de dados.
     *
     * @access  public
     * @return  void
     * @see     simpleQuery()
     */
    function adicionarConfiguracao()
    {
        global $HTTP_POST_VARS;

        $stmt = "INSERT INTO config
                    (titulo, enviar_email, remetente, remetente_email, assunto, servidor_smtp, servidor_http,
                    imagem_tipo, usar_truetype, enviar_pdf, mensagem_texto, mensagem_html)
                 VALUES
                    ('" . rodaSlashes($HTTP_POST_VARS["titulo"]) . "', ". $HTTP_POST_VARS["enviar_email"] . ", '" . rodaSlashes($HTTP_POST_VARS["remetente"]) . "', '" . 
                    rodaSlashes($HTTP_POST_VARS["remetente_email"]) . "', '" . rodaSlashes($HTTP_POST_VARS["assunto"]) . "', '" . 
                    rodaSlashes($HTTP_POST_VARS["servidor_smtp"]) . "', '" . rodaSlashes($HTTP_POST_VARS["servidor_http"]) . "', '" . 
                    rodaSlashes($HTTP_POST_VARS["imagem_tipo"]) . "', " . $HTTP_POST_VARS["usar_truetype"] . ", " . $HTTP_POST_VARS["enviar_pdf"] . ", '" . 
                    rodaSlashes($HTTP_POST_VARS["mensagem_texto"]) . "', '" . rodaSlashes($HTTP_POST_VARS["mensagem_html"]) . "')";
        $this->dbh->simpleQuery($stmt);
    }

    /**
     * M�todo usado pela interface de administra��o para modificar
     * os dados espec�ficos de um boleto.
     *
     * @access  public
     * @return  void
     * @see     simpleQuery()
     */
    function atualizarBoleto()
    {
        global $HTTP_POST_VARS;

        $stmt = "UPDATE
                    boletos
                 SET
                    bnid=" . $HTTP_POST_VARS["bnid"] . ",
                    cid=" . $HTTP_POST_VARS["cid"] . ",
                    titulo='" . rodaSlashes($HTTP_POST_VARS["titulo"]) . "',
                    agencia='" . rodaSlashes($HTTP_POST_VARS["agencia"]) . "',
                    cedente='" . rodaSlashes($HTTP_POST_VARS["cedente"]) . "',
                    conta_cedente='" . rodaSlashes($HTTP_POST_VARS["conta_cedente"]) . "',
                    especie_documento='" . rodaSlashes($HTTP_POST_VARS["especie_documento"]) . "',
                    codigo='" . rodaSlashes($HTTP_POST_VARS["codigo"]) . "',
                    sacado='" . rodaSlashes($HTTP_POST_VARS["sacado"]) . "',
                    cpf='" . rodaSlashes($HTTP_POST_VARS["cpf"]) . "',
                    local_pagamento='" . rodaSlashes($HTTP_POST_VARS["local_pagamento"]) . "',
                    sacador='" . rodaSlashes($HTTP_POST_VARS["sacador"]) . "',
                    carteira='" . rodaSlashes($HTTP_POST_VARS["carteira"]) . "',
                    instrucoes_linha1='" . rodaSlashes($HTTP_POST_VARS["instrucoes_linha1"]) . "',
                    instrucoes_linha2='" . rodaSlashes($HTTP_POST_VARS["instrucoes_linha2"]) . "',
                    instrucoes_linha3='" . rodaSlashes($HTTP_POST_VARS["instrucoes_linha3"]) . "',
                    instrucoes_linha4='" . rodaSlashes($HTTP_POST_VARS["instrucoes_linha4"]) . "',
                    instrucoes_linha5='" . rodaSlashes($HTTP_POST_VARS["instrucoes_linha5"]) . "'
                 WHERE
                    bid=" . $HTTP_POST_VARS["bid"];
        $this->dbh->simpleQuery($stmt);
    }

    /**
     * M�todo usado pela interface de administra��o para modificar
     * os dados espec�ficos de um banco.
     *
     * @access  public
     * @return  void
     * @see     simpleQuery()
     */
    function atualizarBanco()
    {
        global $HTTP_POST_VARS;

        $stmt = "UPDATE
                    bancos
                 SET
                    layout='" . rodaSlashes($HTTP_POST_VARS["layout"]) . "',
                    nome='" . rodaSlashes($HTTP_POST_VARS["nome"]) . "',
                    codigo='" . rodaSlashes($HTTP_POST_VARS["codigo"]) . "',
                    uso_do_banco='" . rodaSlashes($HTTP_POST_VARS["uso_do_banco"]) . "'
                 WHERE
                    bnid=" . $HTTP_POST_VARS["bnid"];
        $this->dbh->simpleQuery($stmt);
    }

    /**
     * M�todo usado pela interface de administra��o para modificar
     * os dados espec�ficos de uma configura��o.
     *
     * @access  public
     * @return  void
     * @see     simpleQuery()
     */
    function atualizarConfiguracao()
    {
        global $HTTP_POST_VARS;

        $stmt = "UPDATE
                    config
                 SET
                    titulo='" . rodaSlashes($HTTP_POST_VARS["titulo"]) . "',
                    enviar_email=" . $HTTP_POST_VARS["enviar_email"] . ",
                    remetente='" . rodaSlashes($HTTP_POST_VARS["remetente"]) . "',
                    remetente_email='" . rodaSlashes($HTTP_POST_VARS["remetente_email"]) . "',
                    assunto='" . rodaSlashes($HTTP_POST_VARS["assunto"]) . "',
                    servidor_smtp='" . rodaSlashes($HTTP_POST_VARS["servidor_smtp"]) . "',
                    servidor_http='" . rodaSlashes($HTTP_POST_VARS["servidor_http"]) . "',
                    imagem_tipo='" . rodaSlashes($HTTP_POST_VARS["imagem_tipo"]) . "',
                    usar_truetype=" . $HTTP_POST_VARS["usar_truetype"] . ",
                    enviar_pdf=" . $HTTP_POST_VARS["enviar_pdf"] . ",
                    mensagem_texto='" . rodaSlashes($HTTP_POST_VARS["mensagem_texto"]) . "',
                    mensagem_html='" . rodaSlashes($HTTP_POST_VARS["mensagem_html"]) . "'
                 WHERE
                    cid=" . $HTTP_POST_VARS["cid"];
        $this->dbh->simpleQuery($stmt);
    }
}
?>