<?
//print 'acho q falta o banco do phpBoleto :D';
include "class/dbclasses.class.php";
include_once("phpBoleto/include/pre.php");
include_once("phpBoleto/include/class.boleto.php");

//$valor = $oParametros->listaValoresBoleto($imov_uf);
$valor = '5000';//$utils->formataMoeda($valor);
$matricula = '45454';
$proponente = 'afasf';


$boleto = new Boleto;
$info = array(
    "tipo"                => "html",
    "vencimento"          => date("d/m/Y", time()+60*60*24*7), // opcional
    "nosso_numero"        => $matricula,
    "numero_documento"    => "",
    "codigo_barra"        => "",
    "data_documento"      => date("d/m/Y"), // opcional
    "valor_documento"     => $valor,
    "sacado"              => $proponente,
);
$boleto->geraBoleto($info, 1);
?> 