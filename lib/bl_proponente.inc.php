<?
$displayDetsPpnt = 'display:none;';

if($acaoProposta=='altPpnt'||$acaoProposta=='dtsPpnt' || $prop_addexig==1 || $conj_addexig==1 || $fgts_addexig==1){
	$displayDetsPpnt = '';
}

$somavVlIndFin=0;

$colunas = (FLG_PREVI)?'5':'4';
$botoesEdit = false;
if(
		($cLOGIN->iLEVEL_USUA == TPUSER_PROPONENTE && $aProposta["situacao_ppst"] == 1) ||
		($cLOGIN->iLEVEL_USUA == TPUSER_ATENDENTE && ($aProposta["situacao_ppst"]==3 || $aProposta["situacao_ppst"]==5) )
){ $botoesEdit = true; }

?>
<!-- AQUI COMECA bl_proponente.inc.php -->
<script language="JavaScript" type="text/javascript" src="./js/proposta_bl_proponente.js"></script>
<a name="proponente"></a>
<div class="bloco_include">
	<div class="bloco_titulo">Proponentes</div>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt="" /></div>
		<div class="quadroInternoMeio">
				<div id="div_add_ppnt" style="<?=$displayDetsPpnt;?>">
				<?
					if(
						($cLOGIN->iLEVEL_USUA == TPUSER_PROPONENTE && $aProposta["situacao_ppst"] == 1) ||
						($cLOGIN->iLEVEL_USUA == TPUSER_ATENDENTE  && ($aProposta["situacao_ppst"]==3 || $aProposta["situacao_ppst"]==5) )
					){
						?>
						<div class="grupoDados" style="margin:0px 0px 10px 0px;">
							<?
								$just_display = false;
								include "lib/bl_form_proponente.inc.php";
								$just_display = true;
							?>
			    	</div>
			    	<?
					}
				?>
	    	</div>
				<div class="tListDiv" style="width:auto;">
					<table style="width:704px;">
						<colgroup>
							<?
								$tamNome = ($botoesEdit)?((!FLG_PREVI)?539:494):519;
								$tamBots = ($botoesEdit)?((!FLG_PREVI)?75:50):25;
							?>
							<col width="<?=$tamNome;?>" />
							<col width="90" />
							<? if(FLG_PREVI){ ?><col width="70" /><? } ?>
							<col width="<?=$tamBots;?>" />
						</colgroup>
						<thead>
							<tr>
								<td>Nome</td>
								<td class="all">CPF</td>
								<? if(FLG_PREVI){ ?><td class="all">Matricula</td><? } ?>
								<td>&nbsp;</td>
							</tr>
						</thead>
						<tbody>
							<?
								$ippnt = 0;
								if (is_array($aProposta["proponentes"]) && @count($aProposta["proponentes"]) > 0) {
									foreach($aProposta["proponentes"] as $kPpnt=>$registroPpnt){
										 $ippnt++;
										$indic = 'transp.gif';
										$ind_alt = '';
										$flg_show_dets_ppnt = ($_POST["flg_show_dets_ppnt_".$ippnt]=='S')?'S':'N';
										$display_tr_ppnt = ($flg_show_dets_ppnt=='S')?'':'display:none;';
										
										$obrigs = $utils->camposObrigatoriosPpnt($registroPpnt);
										$alerta = '';
										if($obrigs!==true){
											$alerta .= '<input type="hidden" name="msg_erro_'.$ippnt.'" id="msg_erro_'.$ippnt.'" value="'.$obrigs.'" />';
											$alerta .= '<img src="images/mensagens/alerta.gif" alt="'.$obrigs.'" height="15" /> &nbsp;';
										}else{
											$errVal = $utils->procuraErroValidacaoPpnt($registroPpnt);
											if($errVal!==true){
												$alerta .= '<input type="hidden" name="msg_erro_'.$ippnt.'" id="msg_erro_'.$ippnt.'" value="'.$errVal.'" />';
												$alerta .= '<img src="images/mensagens/alerta.gif" alt="'.$errVal.'" height="15" /> &nbsp;';
											}else{
												$alerta .= '<input type="hidden" name="msg_erro_'.$ippnt.'" id="msg_erro_'.$ippnt.'" value="" />';
											}
										}
										
										?>
											<tr class="tL<? echo $ippnt%2 ? "1" : "2"; ?>">
												<td><?=$alerta.$registroPpnt["usuario"][0]["nome_usua"];?></td>
												<td class="all"><?=$utils->formataCPF($registroPpnt["cpf_ppnt"]);?></td>
												<? if(FLG_PREVI){ ?><td class="all"><?=$utils->formataMatricula($registroPpnt["usuario"][0]["id_lstn"]);?></td><? } ?>
												<td class="alr">
													<?
													if(
														( $cLOGIN->iLEVEL_USUA==TPUSER_PROPONENTE && $aProposta["situacao_ppst"]==1) ||
														( $cLOGIN->iLEVEL_USUA==TPUSER_ATENDENTE && ($aProposta["situacao_ppst"]==3 || $aProposta["situacao_ppst"]==5) )
													){
													?>
														<?
														if(!FLG_PREVI){ ?><img src="images/buttons/bt_excmini.gif"  alt="Excluir"  class="im" onclick="delPpnt('<?=$registroPpnt["cod_proponente"];?>','<?=$crypt->encrypt('delPpnt');?>','<?=$registroPpnt["usuario"][0]["nome_usua"];?>');" /><? } ?>
														<img src="images/buttons/bt_alterarmini.gif"  alt="Alterar"  class="im" onclick="altPpnt('<?=$registroPpnt["cod_proponente"];?>','<?=$crypt->encrypt('altPpnt');?>');" />
													<? } ?>
													<img src="images/buttons/bt_visualizarmini.gif" alt="Detalhes" class="im" onclick="showDetsPpnt(<?=$ippnt;?>);" />
												</td>
											</tr>
											<tr class="tL<? echo $ippnt%2 ? "1" : "2"; ?>">
												<td colspan="<?=$colunas;?>" class="all">
													<div><? include "lib/bl_valores_proponente.inc.php"; ?></div>
												</td>
											</tr>
											<tr class="tL<? echo $ippnt%2 ? "1" : "2"; ?>" id="tr_dets_ppnt_<?=$ippnt;?>" style="<?=$display_tr_ppnt;?>">
												<td colspan="<?=$colunas;?>" class="all">
													<input type="hidden" name="flg_show_dets_ppnt_<?=$ippnt;?>" id="flg_show_dets_ppnt_<?=$ippnt;?>" value="<?=$flg_show_dets_ppnt;?>" />
													<div><? include "lib/bl_detalhes_proponente.inc.php"; ?></div>
												</td>
											</tr>
										<?
									}
								}
							?>
						</tbody>
						<?
							if(
								!FLG_PREVI &&
								(
									($cLOGIN->iLEVEL_USUA == TPUSER_PROPONENTE && $aProposta["situacao_ppst"] == 1) ||
									($cLOGIN->iLEVEL_USUA == TPUSER_ATENDENTE && ($aProposta["situacao_ppst"]==3 || $aProposta["situacao_ppst"]==5) )
								)
							){
						?>
		    		<tfoot>
							<tr>
								<td colspan="6"><img src="images/buttons/bt_adicionar.gif" id="bt_add_vend" alt="Adicionar Vendedor" class="im" onClick="openFormAddPpnt();" /></td>
							</tr>
		    		</tfoot>
		    		<? } ?>
					</table>
					<input type="hidden" id="qtde_tr_ppnt" name="qtde_tr_ppnt" value="<?=$ippnt;?>" />
					<input type="hidden" id="frm_cod_ppnt" name="frm_cod_ppnt" value="<?=(($acaoProposta=='altPpnt'||$acaoProposta=='dtsPpnt')?$_POST["frm_cod_ppnt"]:'');?>" />
					  <input type="hidden" name="somavVlIndFin" id="somavVlIndFin" value="<?=$utils->formataMoeda($somavVlIndFin);?>"  />
				</div>
		</div>
		<div><img src="images/layout/subquadro_b.gif" alt="" /></div>
	</div>
</div>
<!-- aki TERMINA bl_proponente.inc.php -->
<script language="JavaScript">
//window.onload=function(){
document.getElementById('resultadoSomaparcaprovada').innerHTML ="R$ " + '<?=$utils->formataMoeda($somaparcaprovada);?>';
document.getElementById('resultadoPrazofinanciamento').innerHTML ='<?=$prazoFinanciamento;?> meses';
//}
</script>
<?php
if(sizeof($erroFinanciamento)>0){
?>
	<script language="javascript">
<?php
if(sizeof($erroFinanciamento)==1){
	$mensagemErro=($ippnt==1)?'Valor de Financiamento proposto maior que Limite Autorizado.':'Valor de Financiamento proposto de ' . $erroFinanciamento[0] . ' �  maior que Limite Autorizado.';
?>
		alert('<?=$mensagemErro;?>');
<?php
	}else{
for($y=0;$y<sizeof($erroFinanciamento);$y++){
	if($y==0){
	$lista.=$erroFinanciamento[$y];
	}
	else if($y==(sizeof($erroFinanciamento)-1)){
	$lista.=' e ' . $erroFinanciamento[$y];
	}
	else{
	$lista.=', ' . $erroFinanciamento[$y];
	}
}

?>
		alert('Valor de Financiamento proposto de <?=$lista;?> �  maior que Limite Autorizado.');
<?php
	}
?>
	</script>
	
<?php
}
?>
