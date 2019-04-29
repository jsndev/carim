<?php
$iREQ_AUT=1;
$aUSERS_PERM[]=10;

$pageTitle = "Contrato configurações";
include "lib/header.inc.php";




$result=mysql_query("Select * from empreendimento where  cod_emp='".$empListapg[$pagina]."'";);
while($regEmp = mysql_fetch_array($result,MYSQL_ASSOC))

?>
<form method="post" action="<? echo $php_self; ?>" class="formPadrao" name="frm1" id="frm1">
<input type="hidden" name="ac" value="" />
<div class="quadroInterno">
	<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
	<div class="quadroInternoMeio">
		<table cellpadding="0" cellspacing="2" class="tbForm">
			<tr>
				<td>Dados do procurador: </td>
				<td><textarea name="descricao" id="descricao"><? echo $_POST["descricao"]; ?></textarea></td>
			</tr>
			
			<tr>
				<td>&nbsp;</td>
				<td class="alr"><img src="images/buttons/bt_salvar.gif" onclick="" class="cursorMao" /></td>
			</tr>
		</table>
	</div>
	<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>
</form>
<?php
include "lib/footer.inc.php";
?>