<? if(count($aAltPpnt["checklistconjuge"])>0){ ?>
	<script language="JavaScript" type="text/javascript" src="./js/proposta_bl_despachante.js"></script>
	<a name="conjuge_cklst"></a>
	<b>Check List do Cônjuge</b>
	<div class="tListDiv">
		<table>
			<colgroup>
				<col width="25" /><col width="25" /><col width="240" />
				<col width="95" /><col width="95" /><col width="65" /><col />
			</colgroup>
			<thead>
				<tr>
					<td>OK</td>
					<td>Atend</td>
					<td>Documento</td>
					<td class="alc">Dt Pedido</td>
					<td class="alc">Dt Emissão</td>
					<td class="alc">Validade</td>
					<td class="alc">Observações</td>
				</tr>
			</thead>
			<tbody>
			<?
			$onclickFalse = ' onclick="return false;" ';
			if(count($aAltPpnt["checklistconjuge"])>0){
				$i=0;
				foreach($aAltPpnt["checklistconjuge"] as $kckPpcj=>$vckPpcj){
					if( is_array($vckPpcj) ){
						$i++;
						$dtValidade = false;
						if($vckPpcj["dados"]["dtemissao_clpc"]){
							$dia = substr($vckPpcj["dados"]["dtemissao_clpc"],8,2);
							$mes = substr($vckPpcj["dados"]["dtemissao_clpc"],5,2);
							$ano = substr($vckPpcj["dados"]["dtemissao_clpc"],0,4);
							$dtValidade = date("d/m/Y",mktime(0,0,0,$mes,($dia+$vckPpcj["prazo_mndc"]),$ano));
						}
						
						$incp = ($cLOGIN->iLEVEL_USUA==TPUSER_PROPONENTE)?'_'.$aAltPpnt["cod_proponente"]:''; // input name complement
						
						if($just_display){
							?>
							<tr class="tL<? echo $i%2 ? "1" : "2"; ?>">
								<td class="alc"><img src="images/layout/ck_<?=($vckPpcj["dados"]["flgdespachante_clpc"]=='S'?"true":"false")?>.gif" /></td>
								<td class="alc"><img src="images/layout/ck_<?=($vckPpcj["dados"]["flgatendente_clpc"]=='S'?"true":"false")?>.gif" /></td>
								<td><?=$vckPpcj["documento_mndc"];?></td>
								<td class="alc"><?=$utils->formataDataBRA($vckPpcj["dados"]["dtsolicitacao_clpc"]);?></td>
								<td class="alc"><?=$utils->formataDataBRA($vckPpcj["dados"]["dtemissao_clpc"]);?></td>
								<td class="alc"><?=$vckPpcj["prazo_mndc"];?> dias <?=(($dtValidade)?"<br> ".$dtValidade:'');?></td>
								<td><?=$vckPpcj["dados"]["obs_clpc"];?></td>
							</tr>
							<?
						}elseif(
							( $aProposta["situacao_ppst"] == 1 || $aProposta["situacao_ppst"] == 3 || $aProposta["situacao_ppst"] == 5 ) &&
							( $cLOGIN->iLEVEL_USUA == TPUSER_PROPONENTE || $cLOGIN->iLEVEL_USUA == TPUSER_ATENDENTE)
						){
							$onclickPpnt = " onclick=\"return validaCamposCkLstPpnt('ppc_ckl_doc_ck_prop_".$vckPpcj["cod_mndc"].$incp."','ppc_ckl_doc_ck_atend_".$vckPpcj["cod_mndc"].$incp."','ppc_ckl_doc_dt_ped_".$vckPpcj["cod_mndc"].$incp."','ppc_ckl_doc_dt_emis_".$vckPpcj["cod_mndc"].$incp."');\" ";
							$onclickAtnd = " onclick=\"return validaCamposCkLstAtnd('ppc_ckl_doc_ck_prop_".$vckPpcj["cod_mndc"].$incp."','ppc_ckl_doc_ck_atend_".$vckPpcj["cod_mndc"].$incp."','ppc_ckl_doc_dt_ped_".$vckPpcj["cod_mndc"].$incp."','ppc_ckl_doc_dt_emis_".$vckPpcj["cod_mndc"].$incp."');\" ";
							$onblurPedido  = ''; //" onblur=\"return validaCamposCkLstDtPedido('ppc_ckl_doc_ck_prop_".$vckPpcj["cod_mndc"].$incp."','ppc_ckl_doc_ck_atend_".$vckPpcj["cod_mndc"].$incp."','ppc_ckl_doc_dt_ped_".$vckPpcj["cod_mndc"].$incp."','ppc_ckl_doc_dt_emis_".$vckPpcj["cod_mndc"].$incp."');\" ";
							$onblurEmissao = ''; //" onblur=\"return validaCamposCkLstDtEmissao('ppc_ckl_doc_ck_prop_".$vckPpcj["cod_mndc"].$incp."','ppc_ckl_doc_ck_atend_".$vckPpcj["cod_mndc"].$incp."','ppc_ckl_doc_dt_ped_".$vckPpcj["cod_mndc"].$incp."','ppc_ckl_doc_dt_emis_".$vckPpcj["cod_mndc"].$incp."');\" ";
							?>
							<tr class="tL<? echo $i%2 ? "1" : "2"; ?>">
								<td class="alc"><input type="checkbox" class="ck" name="ppc_ckl_doc_ck_prop_<?=$vckPpcj["cod_mndc"].$incp;?>" value="1" id="ppc_ckl_doc_ck_prop_<?=$vckPpcj["cod_mndc"].$incp;?>" <?=($vckPpcj["dados"]["flgdespachante_clpc"]=='S'?"checked":"")?> <?=$onclickPpnt;?> /></td>
								<td class="alc"><input type="checkbox" class="ck" name="ppc_ckl_doc_ck_atend_<?=$vckPpcj["cod_mndc"].$incp;?>" value="1" id="ppc_ckl_doc_ck_atend_<?=$vckPpcj["cod_mndc"].$incp;?>" <?=($vckPpcj["dados"]["flgatendente_clpc"]=='S'?"checked":"")?> <?=($cLOGIN->iLEVEL_USUA!=2?$onclickFalse:$onclickAtnd)?> /></td>
								<td><?=$vckPpcj["documento_mndc"];?></td>
								<td class="alc">
									<input type="hidden" name="clistproponenteconjuge_<?=$vckPpcj["cod_mndc"].$incp;?>" value="<?=$vckPpcj["cod_mndc"].$incp;?>" />
									<input type="text" style="width:57px;" name="ppc_ckl_doc_dt_ped_<?=$vckPpcj["cod_mndc"].$incp;?>" id="ppc_ckl_doc_dt_ped_<?=$vckPpcj["cod_mndc"].$incp;?>" value="<?=$utils->formataDataBRA($vckPpcj["dados"]["dtsolicitacao_clpc"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10" <?=$onblurPedido;?> />
									<img src="images/buttons/calendario.gif" alt="Ver Calendário" class="cursorMao im" onclick="return showCalendar('ppc_ckl_doc_dt_ped_<?=$vckPpcj["cod_mndc"].$incp;?>', 'dd/mm/y');" />
								</td>
								<td class="alc">
									<input type="text" style="width:57px;" name="ppc_ckl_doc_dt_emis_<?=$vckPpcj["cod_mndc"].$incp;?>" id="ppc_ckl_doc_dt_emis_<?=$vckPpcj["cod_mndc"].$incp;?>" value="<?=$utils->formataDataBRA($vckPpcj["dados"]["dtemissao_clpc"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10" <?=$onblurPedido;?> />
									<img src="images/buttons/calendario.gif" alt="Ver Calendário" class="cursorMao im" onclick="return showCalendar('ppc_ckl_doc_dt_emis_<?=$vckPpcj["cod_mndc"].$incp;?>', 'dd/mm/y');" />
								</td>
								<td class="alc"><?=$vckPpcj["prazo_mndc"];?> dias <?=(($dtValidade)?"<br> ".$dtValidade:'');?></td>
								<td><textarea style="width:90px; height:30px;" name="ppc_ckl_doc_desc_<?=$vckPpcj["cod_mndc"].$incp;?>" id="_<?=$vckPpcj["cod_mndc"].$incp;?>"><?=$vckPpcj["dados"]["obs_clpc"];?></textarea></td>
							</tr>
						<? }else{ ?>
							<tr class="tL<? echo $i%2 ? "1" : "2"; ?>">
								<td class="alc"><input type="checkbox" class="ck" name="ppc_ckl_doc_ck_prop_<?=$vckPpcj["cod_mndc"].$incp;?>" value="1" id="ppc_ckl_doc_ck_prop_[<?=$vckPpcj["cod_mndc"].$incp;?>]" <?=($vckPpcj["dados"]["flgdespachante_clpc"]=='S'?"checked":"")?> <?=$onclickFalse;?> /></td>
								<td class="alc"><input type="checkbox" class="ck" name="ppc_ckl_doc_ck_atend_<?=$vckPpcj["cod_mndc"].$incp;?>" value="1" id="ppc_ckl_doc_ck_atend_[<?=$vckPpcj["cod_mndc"].$incp;?>]" <?=($vckPpcj["dados"]["flgatendente_clpc"]=='S'?"checked":"")?> <?=$onclickFalse;?> /></td>
								<td><?=$vckPpcj["documento_mndc"];?></td>
								<td class="alc"><b><?=$utils->formataDataBRA($vckPpcj["dados"]["dtsolicitacao_clpc"]);?></b></td>
								<td class="alc"><b><?=$utils->formataDataBRA($vckPpcj["dados"]["dtemissao_clpc"]);?></b></td>
								<td class="alc"><?=$vckPpcj["prazo_mndc"];?> dias <?=(($dtValidade)?"<br> ".$dtValidade:'');?></td>
								<td><b><?=$vckPpcj["dados"]["obs_clpc"];?></b></td>
							</tr>
						<?
						}
					}
				}
			} else {
				?>
				<tr class="tL2">
					<td colspan="8" align="center"> Não existem documentos cadastrados para o município do Imóvel.</td>
				</tr>
				<?
			}
			?>
			</tbody>
		</table>
	</div>
	<?
		if($aAltPpnt["municipio"][0]["obschecklist_municipio"]){
			print '<div class="obs_cklist"><b>Observações: </b>'.$aAltPpnt["municipio"][0]["obschecklist_municipio"].'</div>';
		}
	?>
<? } ?>
