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
// @(#) $Id: class.svg.php,v 1.1 2007/01/18 19:43:35 leonardo.kopp Exp $
//

include_once(BOLETO_INC_PATH . "class.grava_erro.php");
require_once(BOLETO_INC_PATH . "boletos" . BOLETO_SEPARADOR . "class.comum.php");

class Boleto_SVG extends Boleto_Comum
{
    var $svg;
    var $obj;
    var $temp_imagem;

    function Boleto_SVG()
    {
        $arquivo_classe = BOLETO_INC_PATH . "boletos" . BOLETO_SEPARADOR . "class.imagem.php";
        if (@include_once($arquivo_classe)) {
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

        $this->temp_imagem = $this->_gravaImagemTemporaria($this->obj->config["imagem_tipo"], $this->obj->formatos, $this->obj->imagem);
        $this->_mostraTemplate();
    }

    function _mostraTemplate()
    {
        global $PHP_SELF;
        $tpl_path = BOLETO_INC_PATH . "templates" . BOLETO_SEPARADOR . "template.svg.php";
        $template = join("", file($tpl_path));
        $svg_img_name = basename($this->temp_imagem);
        $template = str_replace("%IMG_FILENAME%", $svg_img_name, $template);
        $template = str_replace("%LINHA%", $this->obj->banco["linha_digitavel"], $template);
        $template = str_replace("%VDOC%", $this->obj->opcoes["valor_documento"], $template);
        // se alguém tiver uma idéia melhor para fazer isso, estou aberto a sugestões
        // p.s.: só envie sugestões se entender o problema daqui :)
        if (stristr($PHP_SELF, "revisar_boleto.php")) {
            $template = str_replace("%BOLETO_URL%", "../", $template);
        } elseif (stristr($PHP_SELF, "geraboleto.php")) {
            $template = str_replace("%BOLETO_URL%", "", $template);
        }
        echo $template;
        exit();
    }
}
?>