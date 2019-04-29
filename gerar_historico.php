<?php 
if($_POST['cod_ppst']!='')
{
	echo "<script>window.location='excel.php?cod_ppst=".$_POST['cod_ppst']."'</script>";
}
?>
<form name="historico" method="post" action="gerar_historico.php">
COD_PPST: <input type="text" name="cod_ppst" id="cod_ppst" value="<?php echo $_POST['cod_ppst'];?>">
<input type="submit" value="Gerar Excel">

</form>