<?
// limpando cache e falando pro brownser nao gravar cache.
header("Cache-Control: no-cache, must-revalidate"); 
header("Pragma: no-cache"); 

// setando sesscao
session_start(); // setando sessao

# tempo para expirar a seção (login) - em segundos
$iTEMPO_EXPIRAR=5400; 
//$iTEMPO_EXPIRAR=5; 

// diretórios dos modulos
$aMODULESDIR[0]="";
$aMODULESDIR[1]="login";

// Nome dos dos modulos
$aMODULESNAME[0]="";
$aMODULESNAME[1]="Login";


// include de arquivos padrões
include_once("class/db.class.php");
include_once("class/login.class.php");

// instanciando e logando banco de dados
$cBD = New database;

// instanciando login
$cLOGIN = New login;
?>