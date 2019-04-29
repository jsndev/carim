<?
include "./class/dbclasses.class.php";

$iREQ_AUT=1;
$aUSERS_PERM[] = TPUSER_ATENDENTE;
$aUSERS_PERM[] = TPUSER_DESPACHANTE;
$aUSERS_PERM[] = TPUSER_JURIDICO;
$aUSERS_PERM[] = TPUSER_ADMINISTRATIVO;
$aUSERS_PERM[] = TPUSER_USUARIOMASTER;
$aUSERS_PERM[] = TPUSER_ADMPREVI;

$pageTitle = "Propostas";
include "lib/header.inc.php";

if($_GET['pagina'])
	$pagina_atual = $_GET['pagina'];
else
	$pagina_atual = 1;

?>


<DIV ID="testdiv1" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></DIV>


<form name="filtro" method="post" action="<?=$_SERVER["PHP_SELF"];?>">
	<br><b>Filtros</b>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
      <table cellpadding=0 cellspacing=5 border=0>
        <tr>
          <td align="right">Nome:</td>
          <td align="left"><input type="text" name="filtro_nome" value="<?=$_POST["filtro_nome"];?>" size="50"></td>
        </tr>
        <tr>
          <td align="right">Matricula:</td>
          <td align="left"><input type="text" name="filtro_matricula" value="<?=$_POST["filtro_matricula"];?>" size="50"></td>
        </tr>
        <tr>
          <td align="right">CPF:</td>
          <td align="left"><input type="text" name="filtro_cpf" value="<?=$_POST["filtro_cpf"];?>" size="50" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraCPF(this,event);" maxlength="14"></td>
        </tr>
        <tr>
        	<td align="right">&nbsp;</td>
          <td align="left">
          	<input type="image" name="btFiltrar" id="btFiltrar" src="images/buttons/bt_filtrar.gif" value="Filtrar" class="im" />&nbsp;
          	<a href="lista_propostas.php"><img src="images/buttons/bt_limparfiltros.gif" alt="Limpar Filtros" class="im" /></a>
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
			<?
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
					<? echo "Página $pagina_atual de $pagginas, total de $oProposta->_propostas $proposta_plural"; ?>
						<table style="width:687px;">
							<colgroup>
								<col width="70" /><col /><col width="90" /><col width="100" /><col /><col width="50" />
							</colgroup>
							<thead>
								<tr>
									<td class="alc">Data</td>
									<td class="alc">Proponente</td>
									<td class="alc">Matricula</td>
									<td class="alc">CPF</td>
									<td class="alc">Situação</td>
									<td></td>
								</tr>
							</thead>
							<tbody>
							<?
								$i=0;
								foreach($aProposta as $kLista=>$vLista){
									$i++;
									$rowspan = 1;
									foreach($vLista["proponentes"] as $kProp=>$vProp){
										?>
											<tr class="tL<? echo $i%2 ? "1" : "2"; ?>">
												<? if($kProp==0){ $rowspan = count($vLista["proponentes"]); } ?>
												<? if($kProp==0){?><td class="alc" rowspan="<?=$rowspan;?>"><?=$utils->formataDataBRA($vLista["data_ppst"]);?></td><? } ?>
												<td><?=$vProp["usuario"][0]["nome_usua"];?></td>
												<td class="alr"><?=$utils->formataMatricula($vProp["usuario"][0]["id_lstn"]);?></td>
												<td class="alr"><?=$utils->formataCPF($vProp["cpf_ppnt"]);?></td>
												<? if($kProp==0){?><td class="alc" rowspan="<?=$rowspan;?>"><?=$aSIT_PPST[$vLista["situacao_ppst"]];?></td><? } ?>
												<? if($kProp==0){?><td class="alc" rowspan="<?=$rowspan;?>"><a href='proposta.php?cod_proposta=<?=$vLista['cod_ppst'];?>'>abrir</a></td><? } ?>
											</tr>
										<?
									}
								}
							?>
							</tbody>
						</table>
					</div>
					<p align="center">
					<?
						$oProposta->geraBarraProposta();
					?>
                    <p>&nbsp;
		    </p>
                    <br />
	        

	        <?
				} else {
					echo("Nenhuma proposta encontrada.");
				}
			?>
  		    </div>
			<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
		</div>
<table>
		<tr>
			<td>
					<a href="lista_limites.php"><img src="images/buttons/bt_prop_sem.png"></a>
			</td>
		</tr>
	</table>
<?
include "lib/footer.inc.php";
?>