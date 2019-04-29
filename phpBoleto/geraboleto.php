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
// @(#) $Id: geraboleto.php,v 1.1 2007/01/18 19:43:34 leonardo.kopp Exp $
//
error_reporting(E_ALL);
ini_set("include_path", ".");
include_once("./include/pre.php");
include_once(BOLETO_INC_PATH . "class.boleto.php");

$boleto = new Boleto;

$info = array(
    "tipo"                => $HTTP_POST_VARS["tipo"], // opcional
    "vencimento"          => implode("/", $HTTP_POST_VARS["vencimento"]), // opcional
    "nosso_numero"        => $HTTP_POST_VARS["nosso_numero"],
    "numero_documento"    => "",
    "codigo_barra"        => "",
    "data_documento"      => date("d/m/Y"), // opcional
    "valor_documento"     => $HTTP_POST_VARS["valor"],
    /* Campos opcionais que podem ser gravados no banco de dados */
    "cgc_cpf"             => $HTTP_POST_VARS["cpf"],
    "agencia"             => $HTTP_POST_VARS["agencia"], // não coloque o dígito verificador
    "conta_cedente"       => $HTTP_POST_VARS["conta_cedente"],
    "sacado"              => implode(" ", $HTTP_POST_VARS["sacado"]),
    "instrucoes_linha1"   => $HTTP_POST_VARS["instrucoes_linha1"],
    "instrucoes_linha2"   => $HTTP_POST_VARS["instrucoes_linha2"],
    "instrucoes_linha3"   => $HTTP_POST_VARS["instrucoes_linha3"],
    "instrucoes_linha4"   => $HTTP_POST_VARS["instrucoes_linha4"],
    "instrucoes_linha5"   => $HTTP_POST_VARS["instrucoes_linha5"],
    /* Campos normalmente não necessários */
    "acrescimos"          => "",
    "valor_cobrado"       => "",
    "data_processamento"  => "",
    "especificacao_moeda" => "R$",
    "quantidade"          => "",
    "valor_moeda"         => "",
    "descontos"           => "",
    "deducoes"            => "",
    "multa"               => "",
    "demons1"             => $HTTP_POST_VARS["demons1"],
    "demons2"             => $HTTP_POST_VARS["demons2"],
    "demons3"             => $HTTP_POST_VARS["demons3"],
    "demons4"             => $HTTP_POST_VARS["demons4"],
    /* Campos para o envio do boleto por email */
    "boletomail"          => $HTTP_POST_VARS["boletomail"],
    "remetente_nome"      => $HTTP_POST_VARS["remetente_nome"],
    "remetente_email"     => $HTTP_POST_VARS["remetente_email"],
    "recipiente_nome"     => $HTTP_POST_VARS["recipiente_nome"],
    "recipiente_email"    => $HTTP_POST_VARS["recipiente_email"],
    "assunto"             => $HTTP_POST_VARS["assunto"],
    "mensagem_texto"      => $HTTP_POST_VARS["mensagem"],
    "mensagem_html"       => $HTTP_POST_VARS["mensagem"],
    "enviar_pdf"          => $HTTP_POST_VARS["enviar_pdf"], // funcionará somente se 'tipo' for diferente de 'pdf'
    "servidor_smtp"       => $HTTP_POST_VARS["servidor_smtp"],
    "servidor_http"       => $HTTP_POST_VARS["servidor_http"]
);
$boleto->geraBoleto($info, $HTTP_POST_VARS["bid"]);
?>