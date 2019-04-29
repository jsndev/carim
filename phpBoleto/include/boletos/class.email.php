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
// |          Pablo Martins F. Costa <pablo@users.sourceforge.net>        |
// +----------------------------------------------------------------------+
//
// @(#) $Id: class.email.php,v 1.1 2007/01/18 19:43:35 leonardo.kopp Exp $
//


/**
 * A classe Boleto_Email � usada para gerar boletos din�micamente usando as
 * classes apropriadas de acordo com o modelo especificado. O email pode ser
 * enviado com o boleto em formato HTML sendo o conte�do do email, HTML com o 
 * boleto em formato Imagem ou HTML com o boleto em formato PDF anexado � 
 * mensagem. Existe uma op��o extra que adiciona uma c�pia do boleto em formato
 * PDF �s duas primeiras op��es de envio de email.
 *
 * @version  2
 * @author   Jo�o Prado Maia <jpm@phpbrasil.com>
 */

include_once(BOLETO_INC_PATH . "class.grava_erro.php");
require_once(BOLETO_INC_PATH . "boletos" . BOLETO_SEPARADOR . "class.comum.php");

class Boleto_Email extends Boleto_Comum
{
    /**
     * Objeto para guardar a refer�ncia ao objeto de gera��o de boleto
     * @var object
     */
    var $obj;

    /**
     * Objeto para guardar a refer�ncia ao objeto de gera��o de pacotes MIME
     * @var object
     */
    var $mime;

    /**
     * Vetor associativo com as op��es para gera��o do boleto
     * @var array
     */
    var $opcoes;

    /**
     * O conte�do bin�rio do documento PDF com o boleto
     * @var string
     */
    var $temp_pdf;

    /**
     * Constructor da classe. Ele simplesmente cria um objeto de gera��o de 
     * emails em formato MIME para ser usado depois.
     *
     * @access public
     * @return void
     */
    function Boleto_Email()
    {
        $arquivo_classe = BOLETO_INC_PATH . "class.html.mime.mail.php";
        if (!@include_once($arquivo_classe)) {
            Boleto_Email::_mostraErro("Classe n�o p�de ser inclu�da ('$arquivo_classe')", __FILE__, __LINE__);
        } else {
            $this->mime = new html_mime_mail("X-Mailer: phpBoleto v2\n");
        }
    }

    /**
     * M�todo p�blico usado para gerar o boleto de acordo com as op��es passadas
     * para o mesmo.
     *
     * @param $id_boleto int ID do boleto
     * @param $info array Vetor com as op��es para o modelo de boleto
     * @access public
     * @return void
     */
    function geraBoleto($id_boleto, $info)
    {
        $this->opcoes = $info;
        $this->_geraBoletoTemporario($id_boleto, $info);
        $this->_geraDadosEmail($id_boleto);
        $this->_enviaBoleto();
    }

    /**
     * M�todo usado para gerar o boleto e grav�-lo num arquivo tempor�rio. Tanto
     * no caso de modelo de boletos em formato de Imagem e PDF, o pr�prio 
     * boleto precisa ser gravado no servidor para poder ser anexado ou at�
     * inclu�do dentro da mensagem HTML (inline).
     *
     * @param $id_boleto int ID do boleto
     * @param $info array Vetor com as op��es para o modelo de boleto
     * @access private
     * @return void
     */
    function _geraBoletoTemporario($id_boleto, $info)
    {
        $arquivo_classe = BOLETO_INC_PATH . "boletos" . BOLETO_SEPARADOR . "class." . strtolower($info["tipo"]) . ".php";
        include_once($arquivo_classe);
        $nome_classe = "Boleto_" . ucfirst($info["tipo"]);
        $this->obj = new $nome_classe;

        if ($info["tipo"] == "imagem") {
            $this->obj->_checaFormatosDisponiveis();
            // op��es vindo do formul�rio
            $this->obj->opcoes = $info;
            $this->obj->_pegaOpcoesBoleto($id_boleto);
            $this->obj->_pegaOpcoesConfig($id_boleto);
            // gera dados espec�ficos do banco escolhido
            $this->obj->_geraDadosBanco($id_boleto);
            $this->obj->_abreImagem();
            $this->obj->_geraCores();
            $this->obj->_geraBarraCodigo($this->obj->banco["codigo_barras"]);
            $this->obj->_geraDadosBoleto();

            $temp_imagem = $this->_gravaImagemTemporaria($this->obj->config["imagem_tipo"], $this->obj->formatos, $this->obj->imagem);
            $fp = fopen($temp_imagem, "rb");
            $conteudo = fread($fp, filesize($temp_imagem));
            fclose($fp);
            $this->_removeImagemTemporaria($temp_imagem);

            $this->opcoes["mensagem_html"] = "<html>\n<body>\n<img src=\"$temp_imagem\">\n</body>\n</html>";
            $this->mime->add_html_image($conteudo, $temp_imagem, 'image/' . strtolower($this->obj->config["imagem_tipo"]));
            $this->obj->_mostraImagem($this->obj->imagem);

        } elseif ($info["tipo"] == "html") {
            // op��es vindo do formul�rio
            $this->obj->opcoes = $info;
            $this->obj->_pegaOpcoesBoleto($id_boleto);
            $this->obj->_pegaOpcoesConfig($id_boleto);
            $this->obj->_geraDadosBanco($id_boleto);
            $this->obj->_abreTemplate();
            $this->obj->_geraCores();
            $this->obj->_geraBarraCodigo($this->obj->banco["codigo_barras"]);
            $this->obj->_geraDadosBoleto();
            $this->opcoes["mensagem_html"] = $this->obj->html_template;

            $this->mime->add_html_image(fread(fopen(BOLETO_IMAGE_PATH . "barra_branca.gif", "rb"), filesize(BOLETO_IMAGE_PATH . "barra_branca.gif")), "imagens/barra_branca.gif", 'image/gif');
            $this->mime->add_html_image(fread(fopen(BOLETO_IMAGE_PATH . "barra_preta.gif", "rb"), filesize(BOLETO_IMAGE_PATH . "barra_preta.gif")), "imagens/barra_preta.gif", 'image/gif');
            $this->obj->_mostraTemplate();

        } elseif ($info["tipo"] == "pdf") {
            $this->temp_pdf = $this->_geraDadosPDF($this->obj, $id_boleto);
            $this->obj->_mostraPDF($this->temp_pdf);
        }
    }

    /**
     * M�todo usado para gerar propriedades internas da classe, que ser�o 
     * utilizados depois para configurar as op��es de envio do email.
     *
     * @param $id_boleto int ID do boleto
     * @access private
     * @return void
     */
    function _geraDadosEmail($id_boleto)
    {
        // adiciona as partes de texto da mensagem
        if (empty($this->opcoes["mensagem_html"])) {
            $this->mime->set_body($this->opcoes["mensagem_texto"]);
        } else {
            $this->mime->add_html($this->opcoes["mensagem_html"], $this->opcoes["mensagem_texto"]);
        }
        // op��o de adicionar uma copia do boleto em formato pdf como um arquivo anexado
        if (($this->opcoes["tipo"] != "pdf") && ($this->opcoes["enviar_pdf"] == "sim")) {
            include_once(BOLETO_INC_PATH . "boletos" . BOLETO_SEPARADOR . "class.pdf.php");
            $obj = new Boleto_PDF;
            $conteudo = $this->_geraDadosPDF($obj, $id_boleto);
            $this->mime->add_attachment($conteudo, "boleto.pdf", "application/octet-stream");
        }
        if ($this->opcoes["tipo"] == "pdf") {
            $this->mime->add_attachment($this->temp_pdf, "boleto.pdf", "application/octet-stream");
        }
        $this->mime->build_message();
    }

    /**
     * M�todo usado para gerar o boleto em formato PDF para ser anexado ao
     * email sendo enviado.
     *
     * @param $obj object Objeto utilizado para executar os m�todos privados
     * @param $id_boleto int ID do boleto
     * @access private
     * @return void
     */
    function _geraDadosPDF($obj, $id_boleto)
    {
        $obj->obj->_checaFormatosDisponiveis();
        // op��es vindo do formul�rio
        $obj->obj->opcoes = $this->opcoes;

        $obj->obj->_pegaOpcoesBoleto($id_boleto);
        $obj->obj->_pegaOpcoesConfig($id_boleto);
        $obj->obj->_geraDadosBanco($id_boleto);

        $obj->obj->_abreImagem();
        $obj->obj->_geraCores();
        $obj->obj->_geraBarraCodigo($obj->obj->banco["codigo_barras"]);
        $obj->obj->_geraDadosBoleto();

        // checa pela extens�o PDFLib agora
        //   - Se o PHP n�o tiver o suporte ao PDFLib, usaremos a classe R&OS pdf class
        //     dispon�vel em http://ros.co.nz/pdf/
        if (!@function_exists("pdf_show")) {
            $obj->temp_imagem = $obj->_gravaImagemTemporaria("jpeg", $obj->obj->formatos, $obj->obj->imagem);
            // muitos 'warnings' aparecendo por causa dessa classe.. ignorando eles por enquanto
            $antigo = error_reporting(0);
            @include_once(BOLETO_INC_PATH . "class.pdf.php");
            $obj->pdf =& new Cpdf(array(0,0,640,560));
            $obj->pdf->addJpegFromFile($obj->temp_imagem,0,0,640,560);
            $dados = $obj->pdf->output();
            // pronto, podemos mostrar os 'warnings' novamente
            error_reporting($antigo);
            $obj->_removeImagemTemporaria($obj->temp_imagem);
        } else {
            $obj->temp_imagem = $obj->_gravaImagemTemporaria($obj->obj->config["imagem_tipo"], $obj->obj->formatos, $obj->obj->imagem);
            $obj->_abrePDF();
            $obj->_insereImagem();
            $dados = $obj->_fechaPDF();
            $obj->_removeImagemTemporaria($obj->temp_imagem);
        }
        return $dados;
    }

    /**
     * M�todo utilizado para enviar a mensagem gerada pela classe por email. Ele
     * ir� checar automaticamente pelo sistema operacional e enviar o email 
     * por SMTP se for plataforma Windows ou caso contr�rio usando a fun��o 
     * mail().
     *
     * @access private
     * @return void
     */
    function _enviaBoleto()
    {
        // checando por plataformas windows
        if (strstr(strtolower(PHP_OS), "win")) {
            include_once(BOLETO_INC_PATH . 'class.smtp.php');
            $smtp = new smtp_class;
            $smtp->host_name = $this->opcoes["servidor_smtp"];
            $smtp->localhost = $this->opcoes["servidor_http"];

            $to = array($this->opcoes["recipiente_email"]);
            $headers = array(
              'To: "' . $this->opcoes["recipiente_nome"] . '" <' . $this->opcoes["recipiente_email"] . '>',
              'From: "' . $this->opcoes["remetente_nome"] . '" <' . $this->opcoes["remetente_email"] . '>'
            );
            $this->mime->smtp_send($smtp, $this->opcoes["remetente_email"], $to, $this->opcoes["assunto"], $headers);

        } else {
            $this->mime->send($this->opcoes["recipiente_nome"], $this->opcoes["recipiente_email"], $this->opcoes["remetente_nome"], $this->opcoes["remetente_email"], $this->opcoes["assunto"]);
        }
    }

    /**
     * M�todo usado para gravar uma mensagem de erro.
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
    }
}
?>