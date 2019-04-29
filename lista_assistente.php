<?php
include "class/dbclasses.class.php";

$iREQ_AUT=1;
$aUSERS_PERM[] = TPUSER_ATENDENTE;
$aUSERS_PERM[] = TPUSER_DESPACHANTE;
$aUSERS_PERM[] = TPUSER_JURIDICO;
$aUSERS_PERM[] = TPUSER_ADMINISTRATIVO;
$aUSERS_PERM[] = TPUSER_USUARIOMASTER;
$aUSERS_PERM[] = TPUSER_ADMPREVI;

$pageTitle = "Fases";
include "lib/header.inc.php";

if($_GET['pagina'])
	$pagina_atual = $_GET['pagina'];
else
	$pagina_atual = 1;

?>
<script language="JavaScript" src="./js/diversos.js"></script>
<script language="JavaScript" src="js/CalendarPopup.js"></script>

<form name="filtro" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
</form>
		<br><b>Lista de Fases</b>
		<div class="quadroInterno">
			<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
			<div class="quadroInternoMeio">
			<?
				$cLOGIN->insert_log(1,1,'Visualização da Lista de Assistente'.$filtros);
			$db->query="Select * from proposta where  situacao_ppst='3'";
			$db->query();
			if($db->qrcount>0)
			{
				$sit1=$db->qrcount;
			}
			$db->query="Select * from proposta where situacao_ppst='7' or situacao_ppst='8'";
			$db->query();
			if($db->qrcount>0)
			{
				$sit2=$db->qrcount;
			}
			$db->query="Select * from proposta where situacao_ppst=11";
			$db->query();
			if($db->qrcount>0)
			{
				$sit3=$db->qrcount;
			}
			$db->query="Select * from proposta where situacao_ppst=12 and DTREMESSACONTRATO_PPST is NULL";
			$db->query();
			if($db->qrcount>0)
			{
				$sit4=$db->qrcount;
			}
					?>
					<div class="tListDiv listScroll">
						<table>
							<colgroup>
								<col width="100" />
								<col width="50" />
								<col width="20" />
							</colgroup>
							<thead>
								<tr>
									<td align="center">Fases</td>
									<td align="center">Propostas</td>
									<td></td>
								</tr>
							</thead>
							<tbody>
							
										<tr class="tL<? echo $j%2 ? "1" : "2"; ?>">
											<td><b>Avaliação</b></td>
											<td align="center"<?=$estilo;?> ><b><?=$sit1;?></b></td>
											<td align="center"><a href='asdmt_lista_propav.php'>abrir</a></td>
										</tr>
										<tr class="tL<? echo $j%2 ? "1" : "2"; ?>">
											<td><b>Emissão de Contrato</b></td>
											<td align="center"<?=$estilo;?> ><b><?=$sit2;?></b></td>
											<td align="center"><a href='asdmt_lista_propccb.php'>abrir</a></td>
										</tr>
											<tr class="tL<? echo $j%2 ? "1" : "2"; ?>">
											<td><b>Finalizadas</b></td>
											<td align="center"<?=$estilo;?> ><b><?=$sit3;?></b></td>
											<td align="center"><?php if($_GET['f']=='sim') echo "<a href='lista_assistente.php'>fechar</a>"; else echo "<a href='lista_assistente.php?f=sim'>abrir</a>";?> </td>
										</tr>
										<?php 
										if($_GET['f']=='sim'){
										$i=1;
										?>
										<tr class="tL<? echo $i%2 ? "1" : "2"; ?>">
											<td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<a href="asdmt_lista_prop.php?sit=111">COM IMAGEM</a></td>
										</tr>
										<tr class="tL<? echo $i%2 ? "1" : "2"; ?>">
											<td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<a href="asdmt_lista_prop.php?sit=112">SEM IMAGEM</a></td>
										</tr>
										<?php
										}
										?>
											<tr class="tL<? echo $j%2 ? "1" : "2"; ?>">
											<td><b>Concluídas</b></td>
											<td align="center"<?=$estilo;?> ><b><?=$sit4;?></b></td>
											<td align="center"><a href='asdmt_lista_prop.php?sit=12'>abrir</a></td>
										</tr>
							</tbody>
						</table>
					</div>
					<?
			?>
  		</div>
			<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
		</div>
<?php
include "lib/footer.inc.php";
?>