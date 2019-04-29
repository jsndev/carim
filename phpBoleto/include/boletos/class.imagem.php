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
// @(#) $Id: class.imagem.php,v 1.1 2007/01/18 19:43:35 leonardo.kopp Exp $
//


/**
 * A classe Boleto_Imagem é utilizada para gerar o boleto em formato de imagem. Ela
 * pode verificar automaticamente os formatos de imagem disponíveis na configuração
 * do PHP e ativar ou não o uso desses formatos.
 *
 * Nota: Por problemas nas novas versões do GD, recomendamos que o formato JPEG
 * seja usado.
 *
 * @version  2
 * @author   João Prado Maia <jpm@phpbrasil.com>
 */

include_once(BOLETO_INC_PATH . "class.grava_erro.php");
require_once(BOLETO_INC_PATH . "boletos" . BOLETO_SEPARADOR . "class.comum.php");

class Boleto_Imagem extends Boleto_Comum
{
    var $imagem;
    var $tipo_imagem_padrao = "png";
    var $formatos;
    var $cores = array();
    var $versao_gd;

    // valores de configuracao
    var $inidb;
    var $db;
    var $boleto;
    var $config;
    var $banco;
    var $opcoes;

    // valores fixos do boleto
    var $barra_x = 13;
    var $barra_y = 470;
    var $distancia_superior = 17;
    var $distancia_lateral = 1;
    var $truetype_fonte = "";
    var $truetype_tamanho;
    var $fonte_tamanho = 1;

    function Boleto_Imagem()
    {
        $arquivo_classe = BOLETO_INC_PATH . "class.ini.php";
        if (!@include_once($arquivo_classe)) {
            Boleto_Imagem::_mostraErro("Classe não pôde ser incluída ('$arquivo_classe')", __FILE__, __LINE__);
        } else {
            $this->inidb = new File_Ini(BOLETO_CONF_PATH . "phpboleto.ini.php", "#");
            $this->truetype_fonte = BOLETO_FONT_PATH . "arial.ttf";
            $inidata = $this->inidb->getBlockValues("Admin Geral");
            $sistema = $inidata["BOLETO_SISTEMA"];
            $arquivo_classe = BOLETO_INC_PATH . "class.db.php";
            if (!@include_once($arquivo_classe)) {
                Boleto_Imagem::_mostraErro("Classe não pôde ser incluída ('$arquivo_classe')", __FILE__, __LINE__);
            } else {
                $this->db = Boleto_DB::conectar($sistema);
            }
        }
    }

    function geraBoleto($id_boleto, $info)
    {
        $this->_checaFormatosDisponiveis();
        // opções vindo do formulário
        $this->opcoes = $info;
        $this->_checaParametrosMandatorios();

        $this->_pegaOpcoesBoleto($id_boleto);
        $this->_pegaOpcoesConfig($id_boleto);
        // gera dados específicos do banco escolhido
        $this->_geraDadosBanco($id_boleto);

        $this->_abreImagem();
        $this->_geraCores();
        $this->_geraBarraCodigo($this->banco["codigo_barras"]);
        $this->_geraDadosBoleto();
        $this->_mostraImagem($this->imagem);
    }

    function _pegaOpcoesBoleto($id_boleto)
    {
        // checagem por um boleto avulso
        if ($id_boleto == "nulo") {
            $this->boleto = &$this->opcoes;
        } else {
            $this->boleto = $this->db->pegaOpcoesBoleto($id_boleto);
            if ($this->boleto === false) {
                $this->_mostraImagemErro("Erro: ID do boleto não pôde ser encontrado.");
            }
        }

        if ((!isset($this->opcoes["agencia"])) || (empty($this->opcoes["agencia"]))) {
            $this->opcoes["agencia"] = $this->boleto["agencia"];
        }
        if ((!isset($this->opcoes["conta_cedente"])) || (empty($this->opcoes["conta_cedente"]))) {
            $this->opcoes["conta_cedente"] = $this->boleto["conta_cedente"];
        }
        $this->opcoes["data_documento"] = $this->_inicializar($this->opcoes["data_documento"], date("d/m/Y"));
        $this->opcoes["vencimento"] = $this->_inicializar($this->opcoes["vencimento"], date("d/m/Y", time()+60*60*24*7));
        $this->opcoes["numero_documento"] = $this->_inicializar($this->opcoes["numero_documento"], "");
    }

    function _pegaOpcoesConfig($id_boleto)
    {
        // checagem por um boleto avulso
        if ($id_boleto == "nulo") {
            $this->config = &$this->opcoes;
        } else {
            $this->config = $this->db->pegaOpcoesConfig($this->boleto["cid"]);
            if ($this->config === false) {
                $this->_mostraImagemErro("Erro: ID da configuração não pôde ser encontrado.");
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

    function _geraDadosBanco($id_boleto)
    {
        if ($id_boleto == "nulo") {
            $layout = $this->opcoes["layout"];
        } else {
            $layout = $this->db->pegaNomeLayout($this->boleto["nome_banco"]);
        }
        $arquivo = BOLETO_INC_PATH . "boletos" . BOLETO_SEPARADOR . "bancos" . BOLETO_SEPARADOR . $layout;
        if (!@include_once($arquivo)) {
            $this->_mostraErro("Classe não pôde ser incluída ('$arquivo')", __FILE__, __LINE__);
        } else {
            $nome_classe = $this->_pegaLayoutClasse();
            $obj = new $nome_classe;
            $this->banco = $obj->geraDadosBanco($this->opcoes);
        }
    }

    function _escreveTexto($base_x, $base_y, $comprimento, $altura, $texto, $alinhamento = "esquerda", $vertice_direito = 0, $tamanho_fonte = "")
    {
        // checa pelo suporte a TTF
        if (!($info = @ImageTTFBBox($tamanho_fonte, 0, $this->truetype_fonte, $texto))) {
            @ImageString($this->imagem, $this->fonte_tamanho, ($base_x + $this->distancia_lateral), ($base_y + $this->distancia_superior), $texto, $this->cores["preto"]);
        } else {
            if ($tamanho_fonte == "") {
                $tamanho_fonte = $this->truetype_tamanho;
            }
            // repete até conseguir um valor apropriado para o tamanho da fonte
            while (1) {
                $info = @ImageTTFBBox($tamanho_fonte, 0, $this->truetype_fonte, $texto);
                if ((($info[2] - $info[0]) > $comprimento) || ((abs($info[7]) - $info[1]) > $altura)) {
                    $tamanho_fonte -= 0.5;
                    continue;
                } else {
                    @ImageTTFText($this->imagem, $tamanho_fonte, 0, ($base_x + $this->distancia_lateral), ($base_y + $this->distancia_superior), $this->cores["preto"], $this->truetype_fonte, $texto);
                    break;
                }
            }
        }
    }

    function _geraDadosBoleto()
    {
        if ($this->versao_gd == "2") {
            $altura = 11;
            $this->truetype_tamanho = 9;
        } else {
            if (stristr(PHP_OS, "win")) {
                $altura = 10;
                $this->truetype_tamanho = 11;
            } else {
                $altura = 12;
                $this->truetype_tamanho = 9;
            }
        }
        /* Banco */
        $this->_escreveTexto(0, 190, 153, 30, ucfirst($this->boleto["nome_banco"]), "esquerda", 159, 50);
        $this->_escreveTexto(0, 13, 153, 30, ucfirst($this->boleto["nome_banco"]), "esquerda", 159, 50);
        /* codigo do Banco */
        $this->_escreveTexto(163, 13, 63, 50, $this->banco["codigo_banco"], "centro", 230, 40);
        $this->_escreveTexto(163, 190, 63, 50, $this->banco["codigo_banco"], "centro", 230, 40);
        /* Uso do banco */
        $this->_escreveTexto(0, 278, 94, $altura, $this->boleto["uso_do_banco"]);
        /* Linha digitavel */
        $this->_escreveTexto(234, 190, 500, 12, $this->banco["linha_digitavel"], "direita", 640, 20);
        /* cedente */
        $this->_escreveTexto(0, 36, 480, $altura, $this->boleto["cedente"]);
        $this->_escreveTexto(0, 235, 480, $altura, $this->boleto["cedente"]);
        /* vencimento */
        $this->_escreveTexto(484, 36, 160, $altura, $this->opcoes["vencimento"], "direita", 640);
        $this->_escreveTexto(482, 213, 160, $altura, $this->opcoes["vencimento"], "direita", 640);
        /* nosso numero */
        $this->_escreveTexto(0, 58, 123, $altura, $this->banco["nosso_numero"]);
        $this->_escreveTexto(481, 257, 160, $altura, $this->banco["nosso_numero"], "direita", 640);
        /* numero documento */
        $this->_escreveTexto(125, 58, 137, $altura, $this->opcoes["numero_documento"], "centro", 263);
        $this->_escreveTexto(96, 257, 137, $altura, $this->opcoes["numero_documento"], "centro", 254);
        /* especie documento */
        $this->_escreveTexto(290, 58, 22, $altura, $this->boleto["especie_documento"], "centro", 341);
        $this->_escreveTexto(268, 257, 22, $altura, $this->boleto["especie_documento"], "centro", 321);
        /* data do documento */
        $this->_escreveTexto(344, 58, 137, $altura, $this->opcoes["data_documento"], "centro", 481);
        $this->_escreveTexto(0, 257, 95, $altura, $this->opcoes["data_documento"]);
        /* agencia/codigo cedente */
        $this->_escreveTexto(483, 58, 160, $altura, $this->banco["agencia_codigo"], "direita", 640);
        $this->_escreveTexto(481, 235, 160, $altura, $this->banco["agencia_codigo"], "direita", 640);
        /* Valor do documento */
        $this->_escreveTexto(0, 80, 143, $altura, $this->opcoes["valor_documento"]);
        $this->_escreveTexto(481, 279, 160, $altura, $this->opcoes["valor_documento"], "direita", 640);
        /* Acrescimos */
        $this->_escreveTexto(319, 80, 158, $altura, $this->_inicializar($this->opcoes["acrescimos"], ""), "centro", 481);
        $this->_escreveTexto(481, 367, 160, $altura, $this->_inicializar($this->opcoes["acrescimos"], ""), "direita", 640);
        /* Valor cobrado */
        $this->_escreveTexto(483, 80, 160, $altura, $this->_inicializar($this->opcoes["valor_cobrado"], ""), "direita", 640);
        $this->_escreveTexto(481, 389, 160, $altura, $this->_inicializar($this->opcoes["valor_cobrado"], ""), "direita", 640);
        /* Sacado */
        $this->_escreveTexto(0, 102, 540, $altura, $this->_inicializar($this->opcoes["sacado"], $this->boleto["sacado"]));
        $this->_escreveTexto(0, 415, 635, $altura, $this->_inicializar($this->opcoes["sacado"], $this->boleto["sacado"]));
        /* CPF */
        $this->_escreveTexto(550, 102, 88, $altura, $this->_inicializar($this->opcoes["cgc_cpf"], $this->boleto["cpf"]));
        /* Local de Pagamento */
        $this->_escreveTexto(0, 213, 480, $altura, $this->boleto["local_pagamento"]);
        /* Sacador */
        $this->_escreveTexto(0, 433, 635, $altura, $this->boleto["sacador"]);
        /* Aceite */
        $this->_escreveTexto(323, 257, 50, $altura, "", "centro", 384);
        /* data do processamento */
        $this->_escreveTexto(387, 257, 90, $altura, $this->_inicializar($this->opcoes["data_processamento"], ""), "centro", 479);
        /* carteira */
        $this->_escreveTexto(97, 279, 58, $altura, $this->boleto["carteira"], "centro", 158);
        /*  Especificacao moeda */
        $this->_escreveTexto(187, 279, 16, $altura, $this->_inicializar($this->opcoes["especificacao_moeda"], "R$"), "centro", 235);
        /* quantidade */
        $this->_escreveTexto(237, 279, 121, $altura, $this->_inicializar($this->opcoes["quantidade"], ""), "centro", 359);
        /* valor da moeda */
        $this->_escreveTexto(362, 279, 116, $altura, $this->_inicializar($this->opcoes["valor_moeda"], ""), "centro", 479);
        /* descontos */
        $this->_escreveTexto(481, 301, 160, $altura, $this->_inicializar($this->opcoes["descontos"], ""), "direita", 640);
        /* Deducoes */
        $this->_escreveTexto(149, 80, 166, $altura, $this->_inicializar($this->opcoes["deducoes"], ""), "centro", 316);
        $this->_escreveTexto(481, 323, 160, $altura, $this->_inicializar($this->opcoes["deducoes"], ""), "direita", 640);
        /* mora / multa */
        $this->_escreveTexto(481, 345, 160, $altura, $this->_inicializar($this->opcoes["multa"], ""), "direita", 640);
        /* instrucoes */
        $this->_escreveTexto(0, 305, 480, $altura, $this->_inicializar($this->opcoes["instrucoes_linha1"], $this->boleto["instrucoes_linha1"]));
        $this->_escreveTexto(0, 325, 480, $altura, $this->_inicializar($this->opcoes["instrucoes_linha2"], $this->boleto["instrucoes_linha2"]));
        $this->_escreveTexto(0, 345, 480, $altura, $this->_inicializar($this->opcoes["instrucoes_linha3"], $this->boleto["instrucoes_linha3"]));
        $this->_escreveTexto(0, 365, 480, $altura, $this->_inicializar($this->opcoes["instrucoes_linha4"], $this->boleto["instrucoes_linha4"]));
        $this->_escreveTexto(0, 385, 480, $altura, $this->_inicializar($this->opcoes["instrucoes_linha5"], $this->boleto["instrucoes_linha5"]));
    }

    function _geraCores()
    {
        $this->cores["preto"] = ImageColorAllocate($this->imagem, 0, 0, 0);
        $this->cores["branco"] = ImageColorAllocate($this->imagem, 255, 255, 255);
    }

    function _geraCodigo($acao)
    {
        if ($acao == "comeco") {
            $this->_adicionaBarra($this->cores["preto"], $this->config["tamanho_fino"]);
            $this->_adicionaBarra($this->cores["branco"], $this->config["tamanho_fino"]);
            $this->_adicionaBarra($this->cores["preto"], $this->config["tamanho_fino"]);
            $this->_adicionaBarra($this->cores["branco"], $this->config["tamanho_fino"]);
        } elseif ($acao == "fim") {
            $this->_adicionaBarra($this->cores["preto"], $this->config["tamanho_largo"]);
            $this->_adicionaBarra($this->cores["branco"], $this->config["tamanho_fino"]);
            $this->_adicionaBarra($this->cores["preto"], $this->config["tamanho_fino"]);
            $this->_adicionaBarra($this->cores["branco"], $this->config["tamanho_largo"]);
        }
    }

    function _geraBarraCodigo($numero)
    {
        $this->_geraCodigo('comeco');
        for ($i = 0; $i < strlen($numero); $i = $i+2) {
            $codigo = substr($numero, $i, 2);
            $this->_geraBarra($codigo);
        }
        $this->_geraCodigo('fim');
    }

    function _adicionaBarra($cor, $largura)
    {
        ImageFilledRectangle($this->imagem, $this->barra_x, $this->barra_y, ($this->barra_x + $largura), ($this->barra_y + $this->altura_barra), $cor);
        $this->barra_x += $largura;
    }

    function _abreImagem()
    {
        if (empty($this->config["imagem_tipo"])) {
            $this->config["imagem_tipo"] = $this->tipo_imagem_padrao;
        }
        $nome_func = "ImageCreateFrom" . strtoupper($this->config["imagem_tipo"]);
        $this->imagem = @$nome_func($this->_caminhoImagem());
        // checa se a imagem nao foi criada
        if ($this->imagem == "") {
            $this->_mostraErro("Imagem não pôde ser criada.", __FILE__, __LINE__);
        }
    }

    function _caminhoImagem()
    {
        return BOLETO_IMAGE_PATH . "boleto." . strtolower($this->config["imagem_tipo"]);
    }

    function _mostraImagemErro($erro = "Erro criando imagem")
    {
        $im = ImageCreate(400, 30);
        $bgc = ImageColorAllocate($im, 255, 255, 255);
        $tc = ImageColorAllocate($im, 0, 0, 0);
        ImageFilledRectangle($im, 0, 0, 150, 30, $bgc);
        ImageString($im, 3, 5, 5, $erro, $tc);
        $this->_mostraImagem($im);
        exit;
    }

    /**
     * Método usado para gravar uma mensagem de erro num arquivo padrão, para
     * ser usado eventualmente como fonte de informações mais detalhadas num
     * pedido de suporte para o software.
     *
     * @access  public
     * @param   string $erro Mensagem descrevendo o erro
     * @param   string $script Caminho completo para o script onde o erro ocorreu
     * @param   int $linha Número da linha onde o erro ocorreu
     * @return  void
     */
    function _mostraErro($erro, $script, $linha)
    {
        if (@count($this->formatos) > 0) {
            GravaErro::grava($erro, $script, $linha);
            $this->_mostraImagemErro($erro);
        } else {
            GravaErro::grava("Não existe suporte a nenhum formato de imagem (erro original: '$erro')", $script, $linha);
        }
    }

    function _mostraImagem($im)
    {
        if ((isset($this->config["imagem_tipo"])) && (!empty($this->config["imagem_tipo"]))) {
            $sufixo = strtoupper($this->config["imagem_tipo"]);
        } else {
            $this->config["imagem_tipo"] = "png";
            $sufixo = "png";
        }
        if ((isset($this->formatos[$this->config["imagem_tipo"]])) && ($this->formatos[$this->config["imagem_tipo"]])) {
            $nome_func = "Image" . $sufixo;
        } else {
            $this->_mostraErro("PHP sem suporte a imagens $sufixo", __FILE__, __LINE__);
        }
        header("Content-Type: image/" . $this->config["imagem_tipo"]);
        header("Content-Description: Gerado por phpBoleto");
        $nome_func($im);
        ImageDestroy($im);
    }

    function _checaFormatosDisponiveis()
    {
        if (ImageTypes() & IMG_GIF) {
            $this->formatos["gif"] = 1;
        }
        if (ImageTypes() & IMG_PNG) {
            $this->formatos["png"] = 1;
        }
        if (ImageTypes() & IMG_JPG) {
            $this->formatos["jpeg"] = 1;
        }
        if (ImageTypes() & IMG_WBMP) {
            $this->formatos["wbmp"] = 1;
        }

        $new_gd = 'GD Version</b></td><td align="left">2.0';
        $old_gd = 'GD Version</b></td><td align="left">1.6.2';
        // hack horroroso para pegar a versao do GD...
        ob_start();
        phpinfo();
        $conteudo = ob_get_contents();
        ob_end_clean();
        if (stristr($conteudo, $new_gd)) {
            $this->versao_gd = "2";
        } else {
            $this->versao_gd = "1";
        }
    }
}
?>
