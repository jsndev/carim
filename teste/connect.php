<?php
if(!($con=mysql_connect('200.195.197.138', 'root','Ath0s!2012'))){	
echo '<font face=\'Tahoma\' color=\'black\' size=\'2\'>Não foi possivel estabelecer conexão com o Banco de Dados.</font>';
exit;
}

$db1=mysql_select_db('teste',$con);

echo $db1; 
?>