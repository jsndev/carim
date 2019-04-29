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
// +----------------------------------------------------------------------+
//
// @(#) $Id: class.pdf.php,v 1.1 2007/01/18 19:43:35 leonardo.kopp Exp $
//

include_once(BOLETO_INC_PATH . "class.grava_erro.php");
require_once(BOLETO_INC_PATH . "boletos" . BOLETO_SEPARADOR . "class.comum.php");

class Boleto_PDF extends Boleto_Comum
{
    var $pdf;
    var $obj;
    var $temp_imagem;

    function Boleto_PDF()
    {
        $arquivo_classe = BOLETO_INC_PATH . "boletos" . BOLETO_SEPARADOR . "class.imagem.php";
        if (!@include_once($arquivo_classe)) {
            $this->_mostraErro("Classe não pôde ser incluída ('$arquivo_classe')", __FILE__, __LINE__);
        } else {
            $this->obj = new Boleto_Imagem;
        }
    }

    function geraBoleto($id_boleto, $info)
    {
        $this->obj->_checaFormatosDisponiveis();
        // opções vindo do formulário
        $this->obj->opcoes = $info;

        $this->obj->_pegaOpcoesBoleto($id_boleto);
        $this->obj->_pegaOpcoesConfig($id_boleto);
        $this->obj->_geraDadosBanco($id_boleto);

        $this->obj->_abreImagem();
        $this->obj->_geraCores();
        $this->obj->_geraBarraCodigo($this->obj->banco["codigo_barras"]);
        $this->obj->_geraDadosBoleto();

        // checa pela extensão PDFLib agora
        //   - Se o PHP não tiver o suporte ao PDFLib, usaremos a classe R&OS pdf class
        //     disponível em http://ros.co.nz/pdf/
        if (!@function_exists("pdf_show")) {
            $this->temp_imagem = $this->_gravaImagemTemporaria("jpeg", $this->obj->formatos, $this->obj->imagem);
            // muitos 'warnings' aparecendo por causa dessa classe.. ignorando eles por enquanto
            $antigo = error_reporting(0);
            @include_once(BOLETO_INC_PATH . "class.pdf.php");
            $this->pdf =& new Cpdf(array(0,0,640,560));
            $this->pdf->addJpegFromFile($this->temp_imagem,0,0,640,560);
            $conteudo = $this->pdf->output();
            // pronto, podemos mostrar os 'warnings' novamente
            error_reporting($antigo);
            $subdir = $this->_gravaPDF($conteudo);
            $this->_mostraPDF($subdir);
            $this->_removeImagemTemporaria($this->temp_imagem);
        } else {
            $this->temp_imagem = $this->_gravaImagemTemporaria($this->obj->config["imagem_tipo"], $this->obj->formatos, $this->obj->imagem);
            $this->_abrePDF();
            $this->_insereImagem();
            $dados = $this->_fechaPDF();
            $subdir = $this->_gravaPDF($dados);
            $this->_mostraPDF($subdir);
            $this->_removeImagemTemporaria($this->temp_imagem);
        }
    }

    function _abrePDF()
    {
        $this->pdf = pdf_new();
        pdf_open_file($this->pdf);
        pdf_begin_page($this->pdf, 640, 560);
    }

    function _insereImagem()
    {
        $im = pdf_open_image_file($this->pdf, $this->obj->config["imagem_tipo"], $this->temp_imagem);
        pdf_place_image($this->pdf, $im, 0, 0, 1);
        pdf_close_image($this->pdf, $im);
    }

    function _fechaPDF()
    {
        pdf_end_page($this->pdf);
        pdf_close($this->pdf);
        return pdf_get_buffer($this->pdf);
    }

    function _gravaPDF($conteudo)
    {
        $subdir = time() . "_" . md5(microtime());
        $pdf_path = BOLETO_TEMP_PATH . $subdir;
        // checa se o diretorio ja existe
        if (!@file_exists($pdf_path)) {
            @mkdir($pdf_path, 0777);
            @chmod($pdf_path, 0777);
        }
        $pdf_document = $pdf_path . "/boleto.pdf";
        $fp = @fopen($pdf_document, "w");
        @fwrite($fp, trim($conteudo));
        @fclose($fp);
        @chmod($pdf_document, 0777);
        return $subdir;
    }

    function _mostraPDF($subdir)
    {
        global $HTTP_REFERER;
        // adivinhação problemática ? só o tempo dirá...
        if ((strstr($HTTP_REFERER, "admin/revisar_boleto.php")) || (strstr($HTTP_REFERER, "admin/reemissao.php"))) {
            $rel_url = "../temp/";
        } else {
            $rel_url = "temp/";
        }
        $pdf_link = $rel_url . $subdir . "/boleto.pdf";
        $template = join("", file(BOLETO_INC_PATH . "templates/template.pdf.php"));
        $template = str_replace("%LINHA%", $this->obj->banco["linha_digitavel"], $template);
        $template = str_replace("%VDOC%", $this->obj->opcoes["valor_documento"], $template);
        $template = str_replace("%PDF_LINK%", $pdf_link, $template);
        echo $template;
        exit;
    }

    function _mostraErro($erro, $script, $linha)
    {
        GravaErro::grava($erro, $script, $linha);
        Boleto_PDF::_abrePDF();
        pdf_show($this->pdf, $erro);
        $dados = Boleto_PDF::_fechaPDF();
        Boleto_PDF::_mostraPDF($dados);
    }
}
?>
