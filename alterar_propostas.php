<?php
include "class/dbclasses.class.php";

$iREQ_AUT=1;
$aUSERS_PERM[] = TPUSER_ATENDENTE;
$aUSERS_PERM[] = TPUSER_DESPACHANTE;
$aUSERS_PERM[] = TPUSER_JURIDICO;
$aUSERS_PERM[] = TPUSER_ADMINISTRATIVO;
$aUSERS_PERM[] = TPUSER_USUARIOMASTER;
$aUSERS_PERM[] = TPUSER_ADMPREVI;

$pageTitle = "Alterar Cadastro";
include "lib/header.inc.php";

if($_GET['pagina'])
	$pagina_atual = $_GET['pagina'];
else
	$pagina_atual = 1;

?>

<p>&nbsp;</p>
<form name="filtro" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
	<br><b>Filtros</b>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
      <table cellpadding=0 cellspacing=5 border=0>
        <tr>
          <td align="right">Participante:</td>
          <td align="left"><input type="text" name="filtro_nome" value="<?php echo $_POST["filtro_nome"];?>" size="50"></td>
        </tr>
        <tr>
          <td align="right">Cód. Identificação:</td>
          <td align="left"><input type="text" name="filtro_matricula" onKeyDown='return teclasMatricula(this,event);' onKeyUp='return mascaraMatricula(this,event);'  maxlength='12'  value="<?php echo $_POST["filtro_matricula"];?>" size="50"></td>
        </tr>
        <tr>
        	<td align="right">&nbsp;</td>
          <td align="left">
          	<input type="image" name="btFiltrar" id="btFiltrar" src="images/buttons/bt_filtrar.gif" value="Filtrar" class="im" />&nbsp;
          	<a href="alterar_propostas.php"><img src="images/buttons/bt_limparfiltros.gif" alt="Limpar Filtros" class="im" /></a>
          </td>
        </tr>
      </table>
			</div>
			<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
		</div>
</form>
		<br><b>Propostas</b>
		<div class="quadroInterno">
			<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		  <div class="quadroInternoMeio">
			<?php
				$oProposta = new proposta();
				$aProposta = $oProposta->getListaProposta(false, true);
				$pagginas = $oProposta->getPaginasProposta();
				
				if($oProposta->_propostas > 1)
					$proposta_plural = "propostas";
				else
					$proposta_plural = "proposta";

				if (is_array($aProposta) && @count($aProposta) > 0) {
					?>
					<div class="tListDiv listScroll">
					<?php echo "Página $pagina_atual de $pagginas, total de $oProposta->_propostas $proposta_plural"; ?>
						<table style="width:687px;">
							<thead>
								<tr>
									<td class="alc">Data</td>
									<td class="alc">Proponente</td>
									<td class="alc">Matrícula</td>
									<td class="alc" width="200">E-mail</td>
									<td></td>
								</tr>
							</thead>
							<tbody>
							<?php
								$i=0;
								foreach($aProposta as $kLista=>$vLista){
									$i++;
									$rowspan = 1;
									foreach($vLista["proponentes"] as $kProp=>$vProp){
										?>
											<tr class="tL<?php echo $i%2 ? "1" : "2"; ?>">
												<?php if($kProp==0){ $rowspan = count($vLista["proponentes"]); } ?>
												<?php if($kProp==0){?><td class="alc" rowspan="<?php echo $rowspan;?>"><?php echo $utils->formataDataBRA($vLista["data_ppst"]);?></td><?php } ?>
												<td>
												<?php
												$dtppst=str_replace("-","",$vLista["data_ppst"]);
												if($dtppst<20080707){
													 echo "<font color='#CC0000'><i>".$vProp["usuario"][0]["nome_usua"]."</i></font>";
												}else{
													 echo $vProp["usuario"][0]["nome_usua"];
												}?></td>
												<td  align="center"><?php echo $utils->formataMatricula($vProp["usuario"][0]["id_lstn"]);?></td>
                                                <td class="ale"><?=$vProp["usuario"][0]["email_usua"];?></td>
												
												<td class="alc" ><a href="alterar_cadastro.php?usuario=<?=$vProp["usuario"][0]["cod_usua"];?>"><img src="images/buttons/bt_alterar.gif" border="0" /></a></td>
											</tr>
										<?php
									}
								}
							?>
							</tbody>
						</table>
					</div>
					<p align="center">
					<?php
						$oProposta->geraBarraProposta();
					?>
                   

	        <?php
				} else {
					echo("Nenhuma proposta encontrada.");
				}
			?>
  		    </div>
			<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
		</div>
<table>
	
	</table>
<?php
include "lib/footer.inc.php";
?>