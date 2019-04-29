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
// @(#) $Id: class.grava_erro.php,v 1.1 2007/01/18 19:43:34 leonardo.kopp Exp $
//


/**
 * A classe GravaErro é usada para gravar eventuais erros no processo de 
 * geração de boletos. É especialmente interessante para gravar problemas e
 * usá-los como referência para possíveis pedidos de suporte no software.
 *
 * @version  2
 * @author   João Prado Maia <jpm@phpbrasil.com>
 */

class GravaErro
{
    /**
     * Método usado para gravar uma mensagem de erro.
     *
     * @access  public
     * @param   string $mensagem Mensagem descrevendo o erro
     * @param   string $script Caminho completo para o script onde o erro ocorreu
     * @param   int $linha Número da linha onde o erro ocorreu
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
     * Método usado para notificar o webmaster / dono do site que um erro
     * ocorreu no processo de geração de boletos.
     *
     * @access  public
     * @param   string $mensagem Mensagem descrevendo o erro
     * @param   string $script Caminho completo para o script onde o erro ocorreu
     * @param   int $linha Número da linha onde o erro ocorreu
     * @return  void
     */
    function _notificar($mensagem = "desconhecido", $script = "desconhecido", $linha = "desconhecido")
    {
        $assunto = "phpBoleto v2 - Erro encontrado!";
        $msg = "Olá,\n\n";
        $msg .= "Um erro foi encontrado em " . date("d/m/Y H:i:s") . " (" . time() . ") na linha '$linha' do script '$script'.\n\n";
        $msg .= "A mensagem de erro passada foi:\n\n";
        if ((is_array($mensagem)) && (count($mensagem) > 1)) {
            $msg .= "'" . $mensagem[0] . "'\n\n";
            $msg .= "Uma mensagem mais detalhada vai abaixo:\n\n";
            $msg .= "'" . $mensagem[1] . "'\n\n";
        } else {
            $msg .= "'$mensagem'\n\n";
        }
        $msg .= "Isso aconteceu na página '" . $GLOBALS["PHP_SELF"] . "' pelo endereço IP '" . getenv("REMOTE_ADDR") . "'.\n\n";
        $msg .= "Atenciosamente,\nClasse automatizada de gravação de erros.";

        $lista_emails = explode(" ", BOLETO_NOTIFICAR_LISTA);
        foreach ($lista_emails as $email) {
            @mail($email, $assunto, $msg, "From: error_handler@" . $GLOBALS["SERVER_NAME"]);
        }
    }

    /**
     * Método usado para gravar uma mensagem de erro num arquivo padrão, para
     * ser usado eventualmente como fonte de informações mais detalhadas num
     * pedido de suporte para o software.
     *
     * @access  public
     * @param   string $mensagem Mensagem descrevendo o erro
     * @param   string $script Caminho completo para o script onde o erro ocorreu
     * @param   int $linha Número da linha onde o erro ocorreu
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