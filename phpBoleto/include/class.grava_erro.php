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
// @(#) $Id: class.grava_erro.php,v 1.1 2007/01/18 19:43:34 leonardo.kopp Exp $
//


/**
 * A classe GravaErro � usada para gravar eventuais erros no processo de 
 * gera��o de boletos. � especialmente interessante para gravar problemas e
 * us�-los como refer�ncia para poss�veis pedidos de suporte no software.
 *
 * @version  2
 * @author   Jo�o Prado Maia <jpm@phpbrasil.com>
 */

class GravaErro
{
    /**
     * M�todo usado para gravar uma mensagem de erro.
     *
     * @access  public
     * @param   string $mensagem Mensagem descrevendo o erro
     * @param   string $script Caminho completo para o script onde o erro ocorreu
     * @param   int $linha N�mero da linha onde o erro ocorreu
     * @return  void
     */
    function grava($mensagem = "", $script = "", $linha = "")
    {
        if (BOLETO_NOTIFICAR_ERRO === true) {
            GravaErro::_notificar($mensagem, $script, $linha);
        }

        GravaErro::_gravaParaArquivo($mensagem, $script, $linha);
    }

    /**
     * M�todo usado para notificar o webmaster / dono do site que um erro
     * ocorreu no processo de gera��o de boletos.
     *
     * @access  public
     * @param   string $mensagem Mensagem descrevendo o erro
     * @param   string $script Caminho completo para o script onde o erro ocorreu
     * @param   int $linha N�mero da linha onde o erro ocorreu
     * @return  void
     */
    function _notificar($mensagem = "desconhecido", $script = "desconhecido", $linha = "desconhecido")
    {
        $assunto = "phpBoleto v2 - Erro encontrado!";
        $msg = "Ol�,\n\n";
        $msg .= "Um erro foi encontrado em " . date("d/m/Y H:i:s") . " (" . time() . ") na linha '$linha' do script '$script'.\n\n";
        $msg .= "A mensagem de erro passada foi:\n\n";
        if ((is_array($mensagem)) && (count($mensagem) > 1)) {
            $msg .= "'" . $mensagem[0] . "'\n\n";
            $msg .= "Uma mensagem mais detalhada vai abaixo:\n\n";
            $msg .= "'" . $mensagem[1] . "'\n\n";
        } else {
            $msg .= "'$mensagem'\n\n";
        }
        $msg .= "Isso aconteceu na p�gina '" . $GLOBALS["PHP_SELF"] . "' pelo endere�o IP '" . getenv("REMOTE_ADDR") . "'.\n\n";
        $msg .= "Atenciosamente,\nClasse automatizada de grava��o de erros.";

        $lista_emails = explode(" ", BOLETO_NOTIFICAR_LISTA);
        foreach ($lista_emails as $email) {
            @mail($email, $assunto, $msg, "From: error_handler@" . $GLOBALS["SERVER_NAME"]);
        }
    }

    /**
     * M�todo usado para gravar uma mensagem de erro num arquivo padr�o, para
     * ser usado eventualmente como fonte de informa��es mais detalhadas num
     * pedido de suporte para o software.
     *
     * @access  public
     * @param   string $mensagem Mensagem descrevendo o erro
     * @param   string $script Caminho completo para o script onde o erro ocorreu
     * @param   int $linha N�mero da linha onde o erro ocorreu
     * @return  void
     */
    function _gravaParaArquivo($mensagem = "desconhecido", $script = "desconhecido", $linha = "desconhecido")
    {
        if (is_array($mensagem)) {
            $msg = "[" . date("D M d H:i:s Y") . "] Encontrado erro '" . $mensagem[0] . "' na linha '$linha' do script '$script'.\n";
            $msg .= "Mais detalhes:\n" . $mensagem[1] ."\n\n";
        } else {
            $msg = "[" . date("D M d H:i:s Y") . "] Encontrado erro '$mensagem' na linha '$linha' do script '$script'.\n";
        }
        $fp = @fopen(BOLETO_ERRORLOG_PATH, "a");
        @fwrite($fp, $msg);
        @fclose($fp);
    }
}
?>