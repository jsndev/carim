<?php
$pageTitle = "Cadastro";
include "lib/header.inc.php";
?>
<script language="JavaScript" src="./js/diversos.js"></script>
<script language="JavaScript" src="./js/cadastro.js"></script>
<?php
$db->query="select 
				nome_usua,
				email_usua
			from
				usuario where level_usua='5' order by nome_usua";
//echo $db->query;
$db->query();
?>
<div class="quadroInterno">
	<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
	<div class="quadroInternoMeio">
		<div class="tListDiv">
			<table>
				<colgroup>
					<col width="300" />
					<col width="300" />
					<col width="101" />
					<col width="1" />
					<col />
				</colgroup>
				<thead>
					<tr>
						<td>Nome</td>
						<td>Email</td>
						<td></td>
						<td></td>
					</tr>
				</thead>
				<tbody>
<?php

					for($i=0; $i<$db->qrcount; $i++)
					{?>
							<tr class="tL<?php echo $i%2 ? "1" : "2"; ?>">
							<td><?php echo $db->qrdata[$i]['nome_usua'];?>
							<td><?php echo $db->qrdata[$i]['email_usua']; ?></td>
							<td align="right"><img src="images/buttons/bt_alterar.gif" alt=" " /></td>
							<td align="right"><img src="images/buttons/bt_excluir.gif" alt=" " /></td>
							</tr><?php
										
					}
?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="5"><a href="cadfinanceiro.php"><img src="images/buttons/bt_adicionar.gif" alt=" " /></a></td>
					</tr>
				</tfoot>
			</table>
		</table>
	</div>
	</div>
	</div>


	<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>

<?php
include "lib/footer.inc.php";
?>