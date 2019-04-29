<?php

$headers ="Content-Type: text/html; charset=iso-8859-1\n"; 
$headers .= "From: fabio@contrathos.com.br";
$html="<b>teste</b>";
echo	mail("fabio.fapeli@gmail.com", "teste",$html, $headefrs);
	
?>