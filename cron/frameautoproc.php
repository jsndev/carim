<?php
$h_proc="14"; // aqui vc define a hora em que o arquivo athosproc.php será processado
$m_proc="30"; // aqui vc define o minuto em que o arquivo athosproc.php será processado

$BD_SERVIDOR = "localhost";
$BD_NOME	= "carim"; //banco de dados
$BD_USUARIO	= "root";
$BD_SENHA	= "/c8119H!";
$conexao = mysql_connect($BD_SERVIDOR,$BD_USUARIO,$BD_SENHA) or die("ERRO: conexão não realizada");
mysql_select_db($BD_NOME) or die("ERRO: erro ao selecionar o banco de dados: ". mysql_error());

$datahoje=date('Y-m-d',time());

$query="SELECT data,status FROM autoproc WHERE data='$datahoje'";
$result=mysql_query($query);
$rows=mysql_num_rows($result);

if($rows>0){
$tempoatual=time();
$datastr=$datahoje . "  $h_proc:$m_proc:00";
$tempoproc=strtotime($datastr);
$temporestante=$tempoproc-$tempoatual;

$processado=mysql_result($result,0,1);

if($processado=='N'){
	if($temporestante<-60){
	echo "<font color='#ff0000'>Prazo para processamento autom&aacute;tico do arquivo athosproc.php expirado.<br /> <a href='athosproc.php' target='_parent'>Clique aqui</a> caso queira processar o arquivo athosproc.php agora.</font>";
	}
	else if($temporestante>-60 AND $temporestante<=0){
	?>
	<script language="javascript">
	parent.location='athosproc.php?autoproc=sim';
	</script>
	<?php
	exit;
	}
	else{
	$h_restante=floor($temporestante/3600);
	echo " Faltam " . $h_restante . ":" . date('i',$temporestante) . ":" . date('s',$temporestante) . " para o processamento do arquivo athosproc.php";
	}
}
else{
	echo "Arquivo j&aacute; processado hoje";
}

}
else{
$query="INSERT INTO autoproc(data) VALUE('$datahoje')";
$result=mysql_query($query);
header("location: frameautoproc.php");
}
echo mysql_error();
?>