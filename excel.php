<?php
$cod_ppst=$_GET['cod_ppst'];
  function formataDataBRA($data) {
		$dataTmp = "";
		if($data) {
		  $dataArray = split('[-\/\ ]',$data);
			$dataTmp = $dataArray[2].'/'.$dataArray[1].'/'.$dataArray[0];
		}
		return $dataTmp;
  }
//Conexão ao Banco de dados local
$BD_SERVIDOR = "localhost";
$BD_NOME	= "carim"; //banco de dados
$BD_USUARIO	= "root";
$BD_SENHA	= "/c8119H!";
$conexao = mysql_connect($BD_SERVIDOR,$BD_USUARIO,$BD_SENHA) or die("ERRO: conexão não realizada");
	mysql_select_db($BD_NOME) or die("ERRO: erro ao selecionar o banco de dados: ". mysql_error());

//consulta sql
$SQL = "SELECT  obs_hist, dt_hist, cod_usua FROM `historico` where cod_ppst='".$cod_ppst."' and obs_hist <> 'Proposta alterada' and cod_usua is not NULL";
//echo $SQL;
$executa = mysql_query($SQL);

// definimos o tipo de arquivo
//gerar em word
//header("Content-type: application/msword");
//gerar em excel
header("Content-type: application/msexcel");
// Como será gravado o arquivo
//gerar em word
//header("Content-Disposition: attachment; filename=usuario.doc");
//gerar em excel
header("Content-Disposition: attachment; filename=historico.xls");
// montando a tabela
echo "<table border='1' bordercolor='#000000'>";
  echo "<tr>";
    echo "<td><b>Descrição</b></td>";
    echo "<td><b>Data</b></td>";
    echo "<td><b>Usuário</b></td>";
  echo "</tr>";
$i=1;
while ($rs = mysql_fetch_array($executa)){
	$query="SELECT  nome_usua FROM usuario where cod_usua='".$rs["cod_usua"]."'";
	$result=mysql_query($query);
	$reg=mysql_fetch_array($result,MYSQL_ASSOC);
  echo "<tr>";
    echo "<td>" .$rs["obs_hist"] . "</td>";
    echo "<td>" .formataDataBRA($rs["dt_hist"]). "</td>";
    echo "<td>" .$reg["nome_usua"] . "</td>";
  echo "</tr>";
  $i++;
}
echo "</table>"; 
?>



