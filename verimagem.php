<?php
//$iREQ_AUT=1;
//$aUSERS_PERM[]=9;
$pageTitle = "Imagens";
include "lib/header.inc.php";
include("lib/calendar.php");
$mensagem = new mensagens();
$forms = new forms();
$login=$_GET['login'];
$acaoProposta = $crypt->decrypt($_POST["acaoProposta"]);
$acaoProposta='status';
$cod_ppst=$_GET["cod"];
$del=$_GET['del'];
$img=$_GET['id'];
$nome=$_GET['name'];

if($del=="deletar"){
	$db->query="DELETE FROM imagem WHERE id='$img'";
	if($db->query()){
	echo "IMAGEM ".$nome." EXCLUÍDA COM SUCESSO";}
}

?>
<form action="verimagem.php" name="imagem" id="imagem" method="post">
<input type="hidden" name="del_img" id="del_img" value="">
<input type="hidden" name="id_img" id="id_img" value="">

<table border="1" cellspacing="0" bordercolor="#000000">
	<?php
	
	$db->query="Select nome, id from imagem where cod_ppst='".$cod_ppst."' and categoria='1'";
	$db->query();
	if($db->qrcount>0)
	{
		
		?>
	<tr>
	<td align="center" colspan="2"><b>Contrato</b></td></tr>
		<?php
		$i=0;
		while($i<$db->qrcount)
		{
		?><tr align="center">
			<td width="159" align="center"> <a href="imagens_previ/<?php echo $cod_ppst;?>/<?php echo $db->qrdata[$i]['nome'];?>" target="_blank">CONTRATO <?php echo $i+1;?></a></td>
			<td width="66" align="center"><a target="_blank" href="verimagem.php?cod_proposta=<?php echo  ($cod_ppst*$cod_ppst)*2;?>&cod=<?php echo $cod_ppst;?>&login=<?php echo $cLOGIN->cUSUARIO;?>&del=deletar&id=<?php echo $db->qrdata[$i]['id'];?>&name=<?php echo $db->qrdata[$i]['nome'];?>">Excluir</a></td>
	</tr>
		<?php
			$i++;
		}
	}
	?>
	</tr>
</table><br>
	<table border="1" cellspacing="0" bordercolor="#000000">
	<?php
	$db->query="Select nome, id from imagem where cod_ppst='".$cod_ppst."' and categoria='2'";
	$db->query();
	if($db->qrcount>0)
	{
		
		?>
	<tr>
	<td align="center" colspan="2"><b>Matricula</b></td></tr>
		<?php
		$i=0;
		while($i<$db->qrcount)
		{
		?><tr>
			<td width="159" align="center"><a target="_blank" href="imagens_previ/<?php echo $cod_ppst;?>/<?php echo $db->qrdata[$i]['nome'];?>">MATRÍCULA <?php echo $i+1;?></a></td>
			<td width="66" align="center"><a target="_blank"  href="verimagem.php?cod_proposta=<?php echo  ($cod_ppst*$cod_ppst)*2;?>&cod=<?php echo $cod_ppst;?>&login=<?php echo $cLOGIN->cUSUARIO;?>&del=deletar&id=<?php echo $db->qrdata[$i]['id'];?>&name=<?php echo $db->qrdata[$i]['nome'];?>">Excluir</a></td>
</tr>
			
		<?php
			$i++;
		}
	}
	?>
	</tr>
	</table><br>
	<table border="1" cellspacing="0" bordercolor="#000000">
	<?php
	$db->query="Select nome, id from imagem where cod_ppst='".$cod_ppst."' and categoria='3'";
	$db->query();
	if($db->qrcount>0)
	{
		
		?>
	<tr>
	<td align="center" colspan="2"><b>IPTU</b></td></tr>
		<?php
		$i=0;
		while($i<$db->qrcount)
		{
		?><tr>
			<td width="157" align="center"><a target="_blank" href="imagens_previ/<?php echo $cod_ppst;?>/<?php echo $db->qrdata[$i]['nome'];?>">IPTU<?php echo $i+1;?></a></td>
			<td width="69" align="center"><a target="_blank"  href="verimagem.php?cod_proposta=<?php echo  ($cod_ppst*$cod_ppst)*2;?>&cod=<?php echo $cod_ppst;?>&login=<?php echo $cLOGIN->cUSUARIO;?>&del=deletar&id=<?php echo $db->qrdata[$i]['id'];?>&name=<?php echo $db->qrdata[$i]['nome'];?>">Excluir</a></td>
		</tr>
		<?php
			$i++;
		}
	}
	?>
	</tr>
	</table><br>
	<table border="1" cellspacing="0" bordercolor="#000000">
	<?php
	$db->query="Select nome, id from imagem where cod_ppst='".$cod_ppst."' and categoria='4'";
	$db->query();
	if($db->qrcount>0)
	{
		
		?>
	<tr>
	<td align="center" colspan="2"><b>Inf. para Solicitação de Avaliação</b></td></tr>
		<?php
		$i=0;
		while($i<$db->qrcount)
		{
		?><tr>
			<td width="156" align="center"><a target="_blank" href="imagens_previ/<?php echo $cod_ppst;?>/<?php echo $db->qrdata[$i]['nome'];?>">INFORMAÇÕES<?php echo $i+1;?></a></td>
			<td width="71" align="center" bordercolor="#FFFFFF"><a target="_blank"  href="verimagem.php?cod_proposta=<?php echo  ($cod_ppst*$cod_ppst)*2;?>&cod=<?php echo $cod_ppst;?>&login=<?php echo $cLOGIN->cUSUARIO;?>&del=deletar&id=<?php echo $db->qrdata[$i]['id'];?>&name=<?php echo $db->qrdata[$i]['nome'];?>">Excluir</a></td>
		</tr>
		<?php
			$i++;
		}
	}
	?>
	</tr>
	</table><br>
	<table border="1" cellspacing="0" bordercolor="#000000">
	<?php
	$db->query="Select nome, id from imagem where cod_ppst='".$cod_ppst."' and categoria='5'";
	$db->query();
	if($db->qrcount>0)
	{
		
		?>
	<tr>
	<td align="center" colspan="2"><b>Laudo de Avaliação</b></td></tr>
		<?php
		$i=0;
		while($i<$db->qrcount)
		{
		?><tr>
			<td width="156" align="center"><a target="_blank" href="imagens_previ/<?php echo $cod_ppst;?>/<?php echo $db->qrdata[$i]['nome'];?>">LAUDO<?php echo $i+1;?></a></td>
			<td width="73" align="center"><a target="_blank"  href="verimagem.php?cod_proposta=<?php echo  ($cod_ppst*$cod_ppst)*2;?>&cod=<?php echo $cod_ppst;?>&login=<?php echo $cLOGIN->cUSUARIO;?>&del=deletar&id=<?php echo $db->qrdata[$i]['id'];?>&name=<?php echo $db->qrdata[$i]['nome'];?>">Excluir</a></td>
		</tr>
		<?php
			$i++;
		}
	}
	?>
	</tr>
    </table><br>
	<table border="1" cellspacing="0" bordercolor="#000000">
	<?php
	$db->query="Select nome, id from imagem where cod_ppst='".$cod_ppst."' and categoria='6'";
	$db->query();
	if($db->qrcount>0)
	{
		
		?>
	<tr>
	<td align="center" colspan="2"><b>Boleto e Comprov. de Transferência</b></td></tr>
		<?php
		$i=0;
		while($i<$db->qrcount)
		{
		?><tr>
			<td width="156" align="center"><a target="_blank" href="imagens_previ/<?php echo $cod_ppst;?>/<?php echo $db->qrdata[$i]['nome'];?>">BOLETO/TRANSFERÊNCIA <?php echo $i+1;?></a></td>
			<td width="72" align="center"><a target="_blank"  href="verimagem.php?cod_proposta=<?php echo  ($cod_ppst*$cod_ppst)*2;?>&cod=<?php echo $cod_ppst;?>&login=<?php echo $cLOGIN->cUSUARIO;?>&del=deletar&id=<?php echo $db->qrdata[$i]['id'];?>&name=<?php echo $db->qrdata[$i]['nome'];?>">Excluir</a></td>
		</tr>
		<?php
			$i++;
		}
	}
	?>
	</tr>
	</table><br>
	<table width="239" border="1" cellspacing="0" bordercolor="#000000">
	<?php
	$db->query="Select nome, id from imagem where cod_ppst='".$cod_ppst."' and categoria='7'";
	$db->query();
	if($db->qrcount>0)
	{
		
		?>
	<tr>
	<td align="center" colspan="2"><b>Ficha de Análise</b></td></tr>
		<?php
		$i=0;
		while($i<$db->qrcount)
		{
		?><tr>
			<td width="156" align="center"><a target="_blank" href="imagens_previ/<?php echo $cod_ppst;?>/<?php echo $db->qrdata[$i]['nome'];?>">FICHA DE ANÁLISE <?php echo $i+1;?></a></td>
			<td width="73" align="center"><a target="_blank"  href="verimagem.php?cod_proposta=<?php echo  ($cod_ppst*$cod_ppst)*2;?>&cod=<?php echo $cod_ppst;?>&login=<?php echo $cLOGIN->cUSUARIO;?>&del=deletar&id=<?php echo $db->qrdata[$i]['id'];?>&name=<?php echo $db->qrdata[$i]['nome'];?>">Excluir</a></td>
		</tr>
		<?php
			$i++;
		}
	}
	?>
	</tr>
</table><br>
	<table width="239" border="1" cellspacing="0" bordercolor="#000000">
	<?php
	$db->query="Select nome, id from imagem where cod_ppst='".$cod_ppst."' and categoria='8'";
	$db->query();
	if($db->qrcount>0)
	{
		
		?>
	<tr>
	<td align="center" colspan="2"><b>Outros</b></td></tr>
		<?php
		$i=0;
		while($i<$db->qrcount)
		{
		?><tr>
			<td width="156" align="center"><a target="_blank" href="imagens_previ/<?php echo $cod_ppst;?>/<?php echo $db->qrdata[$i]['nome'];?>">OUTROS<?php echo $i+1;?></a></td>
			<td width="73" align="center"><a target="_blank"  href="verimagem.php?cod_proposta=<?php echo  ($cod_ppst*$cod_ppst)*2;?>&cod=<?php echo $cod_ppst;?>&login=<?php echo $cLOGIN->cUSUARIO;?>&del=deletar&id=<?php echo $db->qrdata[$i]['id'];?>&name=<?php echo $db->qrdata[$i]['nome'];?>">Excluir</a></td>
		</tr>
		<?php
			$i++;
		}
	}
	?>
	</tr>
</table><br>
</form>
<?php
include "lib/footer.inc.php";
?><!-- web53410.mail.re2.yahoo.com compressed/chunked Tue Aug  7 04:35:47 PDT 2007 -->
