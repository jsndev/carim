<?
header("Content-Type: text/html; charset=iso-8859-1");
?>
<?php

$BD_SERVIDOR = "localhost";
$BD_NOME	= "carim"; //banco de dados
$BD_USUARIO	= "root";
$BD_SENHA	= "/c8119H!";
$conexao = mysql_connect($BD_SERVIDOR,$BD_USUARIO,$BD_SENHA) or die("ERRO: conexão não realizada");
	mysql_select_db($BD_NOME) or die("ERRO: erro ao selecionar o banco de dados: ". mysql_error());
	
	#____________________________________ Formata Data Brasil _________________________________________
  function formataDataBRA($data) {
		$dataTmp = "";
		if($data) {
		  $dataArray = split('[-\/\ ]',$data);
			$dataTmp = $dataArray[2].'/'.$dataArray[1].'/'.$dataArray[0];
		}
		return $dataTmp;
  }
	
$sql="SELECT proposta.cod_ppst,proposta.data_ppst,parametro.TITULO_PARAM,usuario.nome_usua,usuario.id_lstn FROM proposta,proponente,usuario,parametro WHERE proposta.data_ppst>='2009-01-01' AND proposta.cod_ppst=proponente.cod_ppst AND proponente.cod_proponente=usuario.cod_usua AND proposta.situacao_ppst<11 AND parametro.valor_param=proposta.situacao_ppst AND parametro.TIPO_PARAM='status da proposta' ORDER BY data_ppst ASC";

$res=mysql_query($sql);
echo 'TOTAL: '.mysql_num_rows($res);
echo mysql_error();
?>
<table width="750" border="1" cellpadding="4">
  <tr bgcolor="#999999"  style='color:#FFFFFF;font-weight:bold;font-variant:small-caps'>
    <td  width="100" align="center">CI</td>
	<td  width="75" align="center">Data</td>
	<td width="300">Proponente</td>
	    <td width="200">Status</td>

	<td align="center" width="75">Código da Proposta</td>
  </tr>
<?php
$a=0;
	while ($vet=mysql_fetch_array($res)){
	$a++;
	 $resto=$a%2;
	 
	 if($resto)  $color='#dddddd';
	 else $color='#FFFFFF';
?>
  <tr bgcolor="<?=$color;?>">
    <td  align="center"><?=$vet['id_lstn'];?></td>
	<td align="center"><?=formataDataBRA($vet['data_ppst']);?></td>
	<td><?=$vet['nome_usua'];?></td>
	    <td><?=$vet['TITULO_PARAM'];?></td>

    <td align="center"><?=$vet['cod_ppst'];?></td>
  </tr>
<?php		
		}
?>	
</table>