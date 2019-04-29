<?php

$BD_SERVIDOR = "localhost";
$BD_NOME	= "carim"; //banco de dados
$BD_USUARIO	= "root";
$BD_SENHA	= "/c8119H!";
$conexao = mysql_connect($BD_SERVIDOR,$BD_USUARIO,$BD_SENHA) or die("ERRO: conexão não realizada");
mysql_select_db($BD_NOME) or die("ERRO: erro ao selecionar o banco de dados: ". mysql_error());

$query="SELECT NOME,COD_PPST FROM imagem WHERE ID='$_GET[id]'";
$result=mysql_query($query);
$registro = mysql_fetch_array($result, MYSQL_ASSOC);

$nomearquivo=$registro['NOME'];
$nomearquivomd5=md5($registro['NOME']);
$pasta=$registro['COD_PPST'];
$arquivo="$pasta/$nomearquivomd5";

header("Content-type: octet/stream");

//header("Content-Length:".filesize($arquivo));

// Nós estaremos enviando um PDF
header('Content-type: application/pdf');

// Será chamado downloaded.pdf
header("Content-Disposition: attachment; filename=$nomearquivo");

// A fonte do PDF é original.pdf
readfile("imagens_previ/$arquivo");

?>