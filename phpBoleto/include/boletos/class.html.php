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
// @(#) $Id: class.html.php,v 1.1 2007/01/18 19:43:35 leonardo.kopp Exp $
//


/**
 * A classe Boleto_HTML � utilizada para gerar o boleto em formato HTML. Ela
 * � especialmente importante para usu�rios do pacote que n�o tem a op��o de
 * usar o m�dulo GD do PHP para gerar imagens, ou a op��o do m�dulo PDF do PHP
 * para gerar o boleto em PDF.
 *
 * @version  2
 * @author   Jo�o Prado Maia <jpm@phpbrasil.com>
 */

include_once(BOLETO_INC_PATH . "class.grava_erro.php");
require_once(BOLETO_INC_PATH . "boletos" . BOLETO_SEPARADOR . "class.comum.php");

class Boleto_HTML extends Boleto_Comum
{
    /**
     * Objeto guardando a refer�ncia ao objeto de manipula��o de arquivos INI
     * @var object
     */
    var $inidb;

    /**
     * Objeto contendo a refer�ncia para o objeto de acesso a banco de dados
     * @var object
     */
    var $db;

    /**
     * Vetor associativo com os valores configur�veis da gera��o de boletos
     * @var array
     */
    var $boleto;

    /**
     * Vetor associativo com os valores configur�veis pela interface de administra��o
     * @var array
     */
    var $config;

    /**
     * Vetor associativo com os valores configur�veis relacionados ao banco
     * @var array
     */
    var $banco;

    /**
     * Vetor associativo com os valores configur�veis pela passagem de par�metros ao script de gera��o de boletos
     * @var array
     */
    var $opcoes;

    /**
     * Vetor associativo contendo o arquivo de imagem usado para gerar o c�digo de barras
     * @var array
     */
    var $cores;

    /**
     * Representa��o num�rica do c�digo de barras
     * @var string
     */
    var $barra = "";

    /**
     * Caminho completo para o template HTML do boleto
     * @var string
     */
    var $template_filename = "";

    /**
     * Conte�do do template HTML
     * @var string
     */
    var $html_template;

    /**
     * Construtor da classe.
     *
     * @access  public
     * @return  void
     */
    function Boleto_HTML()
    {
        $arquivo_classe = BOLETO_INC_PATH . "class.ini.php";
        if (!@include_once($arquivo_classe)) {
            Boleto_HTML::_mostraErro("Classe n�o p�de ser inclu�da ('$arquivo_classe')", __FILE__, __LINE__);
        } else {
            $this->inidb = new File_Ini(BOLETO_CONF_PATH . "phpboleto.ini.php", "#");
            $this->template_filename = BOLETO_INC_PATH . "templates" . BOLETO_SEPARADOR . "template.html.php";
            $inidata = $this->inidb->getBlockValues("Admin Geral");
            $sistema = $inidata["BOLETO_SISTEMA"];
            $arquivo_classe = BOLETO_INC_PATH . "class.db.php";
            if (!@include_once($arquivo_classe)) {
                Boleto_HTML::_mostraErro("Classe n�o p�de ser inclu�da ('$arquivo_classe')", __FILE__, __LINE__);
            } else {
                $this->db = Boleto_DB::conectar($sistema);
            }
        }
    }

    /**
     * M�todo p�blico para a gera��o do boleto em formato HTML.
     *
     * @access  public
     * @param   $id_boleto int ID do boleto
     * @param   $info array Vetor com as op��es de cria��o do boleto
     * @return  void
     */
    function geraBoleto($id_boleto, $info)
    {
        // op��es vindo do formul�rio
        $this->opcoes = $info;
        $this->_checaParametrosMandatorios();

        $this->_pegaOpcoesBoleto($id_boleto);
        $this->_pegaOpcoesConfig($id_boleto);
        $this->_geraDadosBanco($id_boleto);

        $this->_abreTemplate();
        $this->_geraCores();
        $this->_geraBarraCodigo($this->banco["codigo_barras"]);
        $this->_geraDadosBoleto();
        $this->_mostraTemplate();
    }

    /**
     * M�todo utilizado para chamar uma fun��o da API de acesso a banco de dados
     * para pegar as op��es do boleto especificado.
     *
     * @access  private
     * @param   $id_boleto int ID do boleto
     * @return  void
     */
    function _pegaOpcoesBoleto($id_boleto)
    {
        // checagem por um boleto avulso
        if ($id_boleto == "nulo") {
            $this->boleto = &$this->opcoes;
        } else {
            $this->boleto = $this->db->pegaOpcoesBoleto($id_boleto);
            if ($this->boleto === false) {
                $this->_mostraErro("Erro: ID do boleto n�o p�de ser encontrado.", __FILE__, __LINE__);
            }
        }
        $this->opcoes["agencia"] = $this->_inicializar($this->opcoes["agencia"], $this->boleto["agencia"]);
        $this->opcoes["conta_cedente"] = $this->_inicializar($this->opcoes["conta_cedente"], $this->boleto["conta_cedente"]);
        $this->opcoes["data_documento"] = $this->_inicializar($this->opcoes["data_documento"], date("d/m/Y"));
        $this->opcoes["vencimento"] = $this->_inicializar($this->opcoes["vencimento"], date("d/m/Y", time()+60*60*24*7));
        $this->opcoes["numero_documento"] = $this->_inicializar($this->opcoes["numero_documento"], "");
    }

    /**
     * M�todo utilizado para chamar uma fun��o da API de acesso a banco de dados
     * para pegar as op��es do boleto especificado.
     *
     * @access  private
     * @param   $id_boleto int ID do boleto
     * @return  void
     */
    function _pegaOpcoesConfig($id_boleto)
    {
        // checagem por um boleto avulso
        if ($id_boleto == "nulo") {
            $this->config = &$this->opcoes;
        } else {
            $this->config = $this->db->pegaOpcoesConfig($this->boleto["cid"]);
            if ($this->config === false) {
                $this->_mostraErro("Erro: ID da configura��o n�o p�de ser encontrado.", __FILE__, __LINE__);
            }
        }

        // adiciona as variaveis da barra de codigos
        if (!$this->largura_barra) {
            @$this->config["tamanho_fino"] = 1;
            @$this->config["tamanho_largo"] = 3;
        } else {
            @$this->config["tamanho_fino"] = $this->largura_barra;
            @$this->config["tamanho_largo"] = ($this->largura_barra * 2) + 1;
        }
    }

    /**
     * M�todo usado para pegar os valores configur�veis do Banco.
     *
     * @access  private
     * @return  void
     */
    function _geraDadosBanco($id_boleto)
    {
        if ($id_boleto == "nulo") {
            $layout = $this->opcoes["layout"];
        } else {
            $layout = $this->db->pegaNomeLayout($this->boleto["nome_banco"]);
        }
        $arquivo = BOLETO_INC_PATH . "boletos" . BOLETO_SEPARADOR . "bancos" . BOLETO_SEPARADOR . $layout;
        if (!@include_once($arquivo)) {
            $this->_mostraErro("Classe n�o p�de ser inclu�da ('$arquivo')", __FILE__, __LINE__);
        } else {
            $nome_classe = $this->_pegaLayoutClasse();
            $obj = new $nome_classe;
            $this->banco = $obj->geraDadosBanco($this->opcoes);
        }
    }

    /**
     * M�todo usado para substituir os 'placeholders' com o texto correto.
     *
     * @access  private
     * @param   string $chave Chave que ser� substitu�da
     * @param   string $texto Novo texto para ser usado na substitui��o
     * @return  void
     */
    function _escreveTexto($chave, $texto)
    {
        $tag = "%" . $chave . "%";
        $this->html_template = str_replace($tag, $texto, $this->html_template);
    }

    /**
     * M�todo usado para gerar o texto corretamente nos 'placeholders' do
     * template HTML do boleto.
     *
     * @access  private
     * @return  void
     */
    function _geraDadosBoleto()
    {
        $this->_escreveTexto("HTMLFONT", "Arial");
        $this->_escreveTexto("BOLETO_IMAGE_URL", BOLETO_IMAGE_URL);
        // Banco
        $this->_escreveTexto("BANK", ucfirst($this->boleto["nome_banco"]));
        // C�digo do Banco
        $this->_escreveTexto("BANKCODE", $this->banco["codigo_banco"]);
        // Uso do banco
        $this->_escreveTexto("USOBC", $this->boleto["uso_do_banco"]);
        // Linha digitavel
        $this->_escreveTexto("LINHA", $this->banco["linha_digitavel"]);
        // cedente
        $this->_escreveTexto("CDTE", $this->boleto["cedente"]);
        // vencimento
        $this->_escreveTexto("VCTO", $this->opcoes["vencimento"]);
        // nosso numero
        $this->_escreveTexto("NNUM", $this->banco["nosso_numero"]);
        // numero documento
        $this->_escreveTexto("NDOC", $this->opcoes["numero_documento"]);
        // especie documento
        $this->_escreveTexto("EDOC", $this->boleto["especie_documento"]);
        // data do documento
        $this->_escreveTexto("DDOC", $this->opcoes["data_documento"]);
        // agencia/codigo cedente
        $this->_escreveTexto("AGCOD", $this->banco["agencia_codigo"]);
        // Valor do documento
        $this->_escreveTexto("VDOC", $this->opcoes["valor_documento"]);
        // Acrescimos
        $this->_escreveTexto("ACRES", $this->_inicializar($this->opcoes["acrescimos"], ""));
        // Valor cobrado
        $this->_escreveTexto("VCOBR", $this->_inicializar($this->opcoes["valor_cobrado"], ""));
        // Sacado
        $this->_escreveTexto("SACADO", nl2br($this->_inicializar($this->opcoes["sacado"], $this->boleto["sacado"])));
        // CPF
        $this->_escreveTexto("CPF", $this->_inicializar($this->opcoes["cgc_cpf"], $this->boleto["cpf"]));
        // Local de Pagamento
        $this->_escreveTexto("LP", $this->boleto["local_pagamento"]);
        // Sacador
        $this->_escreveTexto("SACADOR", $this->boleto["sacador"]);
        // Aceite
        $this->_escreveTexto("ACT", "");
        // data do processamento
        $this->_escreveTexto("DPROC", $this->_inicializar($this->opcoes["data_processamento"], ""));
        // carteira
        $this->_escreveTexto("CART", $this->boleto["carteira"]);
        //  Especificacao moeda
        $this->_escreveTexto("ESPMOED", $this->_inicializar($this->opcoes["especificacao_moeda"], "R$"));
        // quantidade
        $this->_escreveTexto("QTDE", $this->_inicializar($this->opcoes["quantidade"], ""));
        // valor da moeda
        $this->_escreveTexto("VMOED", $this->_inicializar($this->opcoes["valor_moeda"], ""));
        // descontos
        $this->_escreveTexto("DESC", $this->_inicializar($this->opcoes["descontos"], ""));
        // Deducoes
        $this->_escreveTexto("DDC", $this->_inicializar($this->opcoes["deducoes"], ""));
        // mora / multa
        $this->_escreveTexto("MULTA", $this->_inicializar($this->opcoes["multa"], ""));
        // instrucoes
        $this->_escreveTexto("INSTR1", $this->_inicializar($this->opcoes["instrucoes_linha1"], $this->boleto["instrucoes_linha1"]));
        $this->_escreveTexto("INSTR2", $this->_inicializar($this->opcoes["instrucoes_linha2"], $this->boleto["instrucoes_linha2"]));
        $this->_escreveTexto("INSTR3", $this->_inicializar($this->opcoes["instrucoes_linha3"], $this->boleto["instrucoes_linha3"]));
        $this->_escreveTexto("INSTR4", $this->_inicializar($this->opcoes["instrucoes_linha4"], $this->boleto["instrucoes_linha4"]));
        $this->_escreveTexto("INSTR5", $this->_inicializar($this->opcoes["instrucoes_linha5"], $this->boleto["instrucoes_linha5"]));
        // barra de c�digos
        $this->_escreveTexto("BAR", $this->barra);
        // demonstrativo
        $this->_escreveTexto("DEMONS1", $this->_inicializar($this->opcoes["demons1"], ""));
        $this->_escreveTexto("DEMONS2", $this->_inicializar($this->opcoes["demons2"], ""));
        $this->_escreveTexto("DEMONS3", $this->_inicializar($this->opcoes["demons3"], ""));
        $this->_escreveTexto("DEMONS4", $this->_inicializar($this->opcoes["demons4"], ""));
        // campos desativados para manter o padrao entre os formatos de boleto
        $this->_escreveTexto("ENDERECO", "");
        $this->_escreveTexto("CEP", "");
        $this->_escreveTexto("BAIRRO", "");
        $this->_escreveTexto("ESTADO", "");
    }

    /**
     * M�todo para criar o vetor de cores / imagens usado na gera��es dos boletos
     * em HTML.
     *
     * @access  private
     * @return  void
     */
    function _geraCores()
    {
        $this->cores["preto"] = BOLETO_IMAGE_URL . "barra_preta.gif";
        $this->cores["branco"] = BOLETO_IMAGE_URL . "barra_branca.gif";
    }

    /**
     * M�todo usado na gera��o de barras, uma por vez.
     *
     * @access  private
     * @param   string $acao A��o que deve ser tomada
     * @return  void
     */
    function _geraCodigo($acao)
    {
        if ($acao == "comeco") {
            $this->_adicionaBarra($this->cores["preto"], $this->config["tamanho_fino"]);
            $this->_adicionaBarra($this->cores["branco"], $this->config["tamanho_fino"]);
            $this->_adicionaBarra($this->cores["preto"], $this->config["tamanho_fino"]);
            $this->_adicionaBarra($this->cores["branco"], $this->config["tamanho_fino"]);
        } elseif ($acao == "fim"){	
            $this->_adicionaBarra($this->cores["preto"], $this->config["tamanho_largo"]);
            $this->_adicionaBarra($this->cores["branco"], $this->config["tamanho_fino"]);
            $this->_adicionaBarra($this->cores["preto"], $this->config["tamanho_fino"]);
            $this->_adicionaBarra($this->cores["branco"], $this->config["tamanho_largo"]);
        } else {
            $this->_mostraErro("A��o desconhecida '$acao'", __FILE__, __LINE__);
        }
    }

    /**
     * M�todo usado para gerar as barras do c�digo de barras a partir de uma
     * representa��o num�rica da mesma.
     *
     * @access  private
     * @param   int $numero Representa��o num�rica do c�digo de barras
     * @return  void
     */
    function _geraBarraCodigo($numero)
    {
        $this->_geraCodigo('comeco');
        for ($i = 0; $i < strlen($numero); $i = $i+2) {
            $codigo = substr($numero, $i, 2);
            $this->_geraBarra($codigo);
        }
        $this->_geraCodigo('fim');
    }

    /**
     * M�todo usado para concatenar a imagem da barra no conte�do da vari�vel
     * que ser� substitu�da eventualmente no template HTML do boleto.
     *
     * @param   string $imagem_barra A URL da imagem da barra
     * @param   int $largura Largura da barra
     * @return  void
     */
    function _adicionaBarra($imagem_barra, $largura)
    {
        $this->barra .= "<img SRC=\"" . $imagem_barra . "\" width=\"" . $largura . "\" height=\"" . $this->altura_barra . "\">";
    }

    /**
     * M�todo usado para pegar o conte�do original do template HTML do boleto.
     *
     * @access  private
     * @return  void
     */
    function _abreTemplate()
    {
        $this->html_template = join("", file($this->template_filename));
    }

    /**
     * M�todo usado para imprimir na tela o template HTML.
     *
     * @access  private
     * @return  void
     */
    function _mostraTemplate()
    {
        echo $this->html_template;
    }

    /**
     * M�todo usado para gravar uma mensagem de erro num arquivo padr�o, para
     * ser usado eventualmente como fonte de informa��es mais detalhadas num
     * pedido de suporte para o software.
     *
     * @access  public
     * @param   string $erro Mensagem descrevendo o erro
     * @param   string $script Caminho completo para o script onde o erro ocorreu
     * @param   int $linha N�mero da linha onde o erro ocorreu
     * @return  void
     */
    function _mostraErro($erro, $script, $linha)
    {
        GravaErro::grava($erro, $script, $linha);
        echo "<b>$erro</b>";
        exit;
    }
}
?>