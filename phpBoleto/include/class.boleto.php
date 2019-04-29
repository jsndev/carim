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
// @(#) $Id: class.boleto.php,v 1.1 2007/01/18 19:43:34 leonardo.kopp Exp $
//


/**
 * A classe "Boleto" cria um método estático de criação de boletos para os
 * modelos específicos, como boleto em imagem, HTML e até PDF. Essa classe
 * abstrai a criação do boleto, para sempre usar o mesmo código para criar
 * boletos diferentes, só sendo necessário uma mudança num parâmetro que será
 * passado ao objeto.
 *
 * Os diferentes modelos de boleto ficam num diretório próprio para favorecer
 * a manutenção do pacote e também a simplicidade da própria aplicação.
 *
 * Boleto        A classe Boleto principal. Ela é somente uma classe simples 
 *               para chamar dinâmicamente o objeto correto do modelo de boleto.
 *
 * Boleto_Comum  A base para cada implementação de modelo de boleto. Possui
 * |             alguns métodos que são compartilhados por alguns modelos.
 * |
 * +-Boleto_PDF  A implementação do modelo de boleto em formato PDF. Ela herda
 *               os métodos do Boleto_Comum.
 *
 * @version  2
 * @author   João Prado Maia <jpm@phpbrasil.com>
 */

include_once(BOLETO_INC_PATH . "/class.grava_erro.php");

class Boleto
{
    /**
     * Checa os valores passados e cria o boleto com o modelo especificado
     *
     * Estrutura do array associativo que deve ser passado ao método:
     *         "tipo"                => $HTTP_GET_VARS["tipo"],
     *         "vencimento"          => date("d/m/Y", time()+60*60*24*7),
     *         "nosso_numero"        => "961580786",
     *         "numero_documento"    => "",
     *         "codigo_barra"        => "",
     *         "data_documento"      => date("d/m/Y"),
     *         "valor_documento"     => "1250,00",
     *
     * Parâmetros opcionais que normalmente são gravados no banco de dados:
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
     * Parâmetros normalmente não necessários:
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
     * Parâmetros necessários somente para o envio do boleto por email:
     *
     *         "boletomail"          => "sim",
     *         "remetente_nome"      => "Impleo.net - Suporte",
     *         "remetente_email"     => "joaopmaia@yahoo.com",
     *         "recipiente_nome"     => "Joao",
     *         "recipiente_email"    => "joaopmaia@yahoo.com",
     *         "assunto"             => "Boleto",
     *         "mensagem_texto"      => "O seu boleto vai atachado",
     *         "mensagem_html"       => "",
     *         "enviar_pdf"          => "sim", // funcionará somente se 'tipo' for diferente de 'pdf'
     *         "servidor_smtp"       => "smtp.mail.yahoo.com",
     *         "servidor_http"       => ""
     *
     * @access  public
     * @param   int $id_boleto O ID do boleto, relacionando o banco de dados. 
     *                         Esse número será algo conhecido pelo usuário pela
     *                         interface de administração.
     * @param   array $info Parâmetros de criação do boleto. Muitos deles são na
     *                      verdade parâmetros opcionais, e servem como um modo 
     *                      dinâmico de se criar boletos, sem necessariamente 
     *                      modificar as opções apropriadas pela interface de 
     *                      administração.
     * @return  void dependendo do modelo de boleto
     * @see     geraBoleto()
     */
    function geraBoleto($info, $id_boleto = "nulo")
    {
        if ((isset($info["boletomail"])) && ($info["boletomail"] == "sim")) {
            $arquivo_classe = BOLETO_INC_PATH . "boletos" . BOLETO_SEPARADOR . "class.email.php";
            if (!@include_once($arquivo_classe)) {
                GravaErro::grava("Classe não pôde ser incluída ('$arquivo_classe')", __FILE__, __LINE__);
            } else {
                $nome_classe = "Boleto_Email";
            }
        } elseif ((isset($info["tipo"])) && (!empty($info["tipo"]))) {
            $arquivo_classe = BOLETO_INC_PATH . "boletos" . BOLETO_SEPARADOR . "class." . strtolower($info["tipo"]) . ".php";
            if (!@include_once($arquivo_classe)) {
                GravaErro::grava("Classe não pôde ser incluída ('$arquivo_classe')", __FILE__, __LINE__);
            } else {
                $nome_classe = "Boleto_" . ucfirst($info["tipo"]);
            }
        }

        if (isset($nome_classe)) {
            if (!(@$objeto = new $nome_classe)) {
                GravaErro::grava("Classe inválida ('$nome_classe')", __FILE__, __LINE__);
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