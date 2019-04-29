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
// @(#) $Id: class.boleto.php,v 1.1 2007/01/18 19:43:34 leonardo.kopp Exp $
//


/**
 * A classe "Boleto" cria um m�todo est�tico de cria��o de boletos para os
 * modelos espec�ficos, como boleto em imagem, HTML e at� PDF. Essa classe
 * abstrai a cria��o do boleto, para sempre usar o mesmo c�digo para criar
 * boletos diferentes, s� sendo necess�rio uma mudan�a num par�metro que ser�
 * passado ao objeto.
 *
 * Os diferentes modelos de boleto ficam num diret�rio pr�prio para favorecer
 * a manuten��o do pacote e tamb�m a simplicidade da pr�pria aplica��o.
 *
 * Boleto        A classe Boleto principal. Ela � somente uma classe simples 
 *               para chamar din�micamente o objeto correto do modelo de boleto.
 *
 * Boleto_Comum  A base para cada implementa��o de modelo de boleto. Possui
 * |             alguns m�todos que s�o compartilhados por alguns modelos.
 * |
 * +-Boleto_PDF  A implementa��o do modelo de boleto em formato PDF. Ela herda
 *               os m�todos do Boleto_Comum.
 *
 * @version  2
 * @author   Jo�o Prado Maia <jpm@phpbrasil.com>
 */

include_once(BOLETO_INC_PATH . "/class.grava_erro.php");

class Boleto
{
    /**
     * Checa os valores passados e cria o boleto com o modelo especificado
     *
     * Estrutura do array associativo que deve ser passado ao m�todo:
     *         "tipo"                => $HTTP_GET_VARS["tipo"],
     *         "vencimento"          => date("d/m/Y", time()+60*60*24*7),
     *         "nosso_numero"        => "961580786",
     *         "numero_documento"    => "",
     *         "codigo_barra"        => "",
     *         "data_documento"      => date("d/m/Y"),
     *         "valor_documento"     => "1250,00",
     *
     * Par�metros opcionais que normalmente s�o gravados no banco de dados:
     *
     *         "cgc_cpf"             => "",
     *         "codigo_banco"        => "",
     *         "agencia"             => "0436",
     *         "conta_cedente"       => "0404392",
     *         "sacado"              => "",
     *         "instrucoes_linha1"   => "",
     *         "instrucoes_linha2"   => "",
     *         "instrucoes_linha3"   => "",
     *         "instrucoes_linha4"   => "",
     *         "instrucoes_linha5"   => "",
     *
     * Par�metros normalmente n�o necess�rios:
     *
     *         "acrescimos"          => "",
     *         "valor_cobrado"       => "",
     *         "data_processamento"  => "",
     *         "especificacao_moeda" => "R$",
     *         "quantidade"          => "",
     *         "valor_moeda"         => "",
     *         "descontos"           => "",
     *         "deducoes"            => "",
     *         "multa"               => "",
     *
     * Par�metros necess�rios somente para o envio do boleto por email:
     *
     *         "boletomail"          => "sim",
     *         "remetente_nome"      => "Impleo.net - Suporte",
     *         "remetente_email"     => "joaopmaia@yahoo.com",
     *         "recipiente_nome"     => "Joao",
     *         "recipiente_email"    => "joaopmaia@yahoo.com",
     *         "assunto"             => "Boleto",
     *         "mensagem_texto"      => "O seu boleto vai atachado",
     *         "mensagem_html"       => "",
     *         "enviar_pdf"          => "sim", // funcionar� somente se 'tipo' for diferente de 'pdf'
     *         "servidor_smtp"       => "smtp.mail.yahoo.com",
     *         "servidor_http"       => ""
     *
     * @access  public
     * @param   int $id_boleto O ID do boleto, relacionando o banco de dados. 
     *                         Esse n�mero ser� algo conhecido pelo usu�rio pela
     *                         interface de administra��o.
     * @param   array $info Par�metros de cria��o do boleto. Muitos deles s�o na
     *                      verdade par�metros opcionais, e servem como um modo 
     *                      din�mico de se criar boletos, sem necessariamente 
     *                      modificar as op��es apropriadas pela interface de 
     *                      administra��o.
     * @return  void dependendo do modelo de boleto
     * @see     geraBoleto()
     */
    function geraBoleto($info, $id_boleto = "nulo")
    {
        if ((isset($info["boletomail"])) && ($info["boletomail"] == "sim")) {
            $arquivo_classe = BOLETO_INC_PATH . "boletos" . BOLETO_SEPARADOR . "class.email.php";
            if (!@include_once($arquivo_classe)) {
                GravaErro::grava("Classe n�o p�de ser inclu�da ('$arquivo_classe')", __FILE__, __LINE__);
            } else {
                $nome_classe = "Boleto_Email";
            }
        } elseif ((isset($info["tipo"])) && (!empty($info["tipo"]))) {
            $arquivo_classe = BOLETO_INC_PATH . "boletos" . BOLETO_SEPARADOR . "class." . strtolower($info["tipo"]) . ".php";
            if (!@include_once($arquivo_classe)) {
                GravaErro::grava("Classe n�o p�de ser inclu�da ('$arquivo_classe')", __FILE__, __LINE__);
            } else {
                $nome_classe = "Boleto_" . ucfirst($info["tipo"]);
            }
        }

        if (isset($nome_classe)) {
            if (!(@$objeto = new $nome_classe)) {
                GravaErro::grava("Classe inv�lida ('$nome_classe')", __FILE__, __LINE__);
            } else {
                $objeto->geraBoleto($id_boleto, $info);
            }
        } else {
            echo "<b>Erro: Por favor especifique o tipo de boleto.</b>";
            GravaErro::grava("Tipo desconhecido ('" . $info["tipo"] . "')", __FILE__, __LINE__);
        }
    }
}
?>