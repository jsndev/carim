<?
$iREQ_AUT=1;
$aUSERS_PERM[]=10;
$pageTitle = "Contrato configurações";
include "lib/header.inc.php";
echo $_POST['excluir'];

if(isset($_POST['acao'])){
	if($_POST['acao']=='excluir'){
	$db->query="DELETE FROM contrato_config WHERE ID_CONTC='$_POST[cod_proc]'";
	$db->query();
	}
	else{
	$db->query="UPDATE contrato_config SET PADRAO_CONTC='0'";
	$db->query();
	$db->query="UPDATE contrato_config SET PADRAO_CONTC='1' WHERE ID_CONTC='$_POST[cod_proc]'";
	$db->query();
	}
echo mysql_error();
}

?>

	<strong>Procuradores cadastrados</strong>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
	<a href='cad_dados_procurador.php'><img src="images/buttons/incluirprocurador.jpg" height="17" width="100" /></a><br /><br />
		<?
			$db->query="SELECT ID_CONTC,NOME_CONTC,PADRAO_CONTC FROM contrato_config ORDER BY NOME_CONTC";
			$db->query();
				
				if($db->qrcount>0) {
					?>
					<div class="tListDiv listScroll">
						<table>
							<thead>
								<tr>
									<td width="250">Nome</td>
                                    <td align="center" width="70">Status</td>
									<td></td>
								</tr>
							</thead>
							<tbody>
							<?
								for($i=0; $i<$db->qrcount; $i++){
									$estilo = '';
									$estilo = ' class="bold"';
									$lnk_acao = 'abrir';
									?>
										<tr class="tL<? echo $i%2 ? "1" : "2"; ?>">
											<td><?=$db->qrdata[$i]['NOME_CONTC'];?></td>
                                            <td align="center"><?=($db->qrdata[$i]['PADRAO_CONTC']=='0')?'Inativo':'Padrão';?></td>
											<td> <a href='ed_dados_procurador.php?cod_proc=<?=$db->qrdata[$i]['ID_CONTC'];?>'><img src="images/buttons/bt_alterar.gif" /></a>	
											<?php
											if($db->qrdata[$i]['PADRAO_CONTC']=='0'){
											?>
											&nbsp;&nbsp;<a href='javascript:agir(<?=$db->qrdata[$i]['ID_CONTC'];?>,"definir_padrao")'><img src="images/buttons/bot_defpadrao.gif" /></a>&nbsp;&nbsp;<a href='javascript:agir(<?=$db->qrdata[$i]['ID_CONTC'];?>,"excluir")'><img src="images/buttons/bt_excluir.gif" /></a>	
											<?php
											}
											?>
											</td></tr>
									<?
								}
							?>
							</tbody>
						</table>
					</div>
					<?
				} else {
					echo("Nenhun procurador cadastrado");
				}
			?>
	  </div>
			<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>
<form name='formulario' method="post">
<input type="hidden" value="" name="acao" />
<input type="hidden" value="" name="cod_proc" />
</form>
<script language="javascript">
function agir(proc,acao){
	if(acao=="excluir"){
		if(confirm("Você tem certeza que deseja excluir este procurador")){
		document.formulario.cod_proc.value=proc;
		document.formulario.acao.value=acao;
		document.formulario.submit();
		}
	}	
	else{
		document.formulario.cod_proc.value=proc;
		document.formulario.acao.value=acao;
		document.formulario.submit();
	}
}
</script>
<?
include "lib/footer.inc.php";
?>