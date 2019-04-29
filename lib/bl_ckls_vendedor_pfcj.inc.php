<? if(count($aAltVend["vendfisconjuge"][0]["checklist"])>0){ ?>
	<script language="JavaScript" type="text/javascript" src="./js/proposta_bl_despachante.js"></script>
	<br /><br />
	<a name="vend_pfcj_cklst"></a>
	<b>Check List do Cônjuge</b>
	<div class="tListDiv">
		<table>
			<colgroup>
				<col width="25" /><col width="25" /><col width="240" />
				<col width="100" /><col width="100" /><col width="70" /><col />
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
			if(count($aAltVend["vendfisconjuge"][0]["checklist"])>0){
				$i=0;
				foreach($aAltVend["vendfisconjuge"][0]["checklist"] as $kckVncj=>$vckVncj){
					if( is_array($vckVncj) ){
						$i++;
						$dtValidade = false;
						if($vckVncj["dados"]["dtemissao_clvc"]){
							$dia = substr($vckVncj["dados"]["dtemissao_clvc"],8,2);
							$mes = substr($vckVncj["dados"]["dtemissao_clvc"],5,2);
							$ano = substr($vckVncj["dados"]["dtemissao_clvc"],0,4);
							$dtValidade = date("d/m/Y",mktime(0,0,0,$mes,($dia+$vckVncj["prazo_mndc"]),$ano));
						}
						
						$incp = ($cLOGIN->iLEVEL_USUA==TPUSER_PROPONENTE)?'_'.$aAltVend["cod_vend"]:''; // input name complement
						
						if($just_display){
							?>
							<tr class="tL<? echo $i%2 ? "1" : "2"; ?>">
								<td class="alc"><img src="images/layout/ck_<?=($vckVncj["dados"]["flgdespachante_clvc"]=='S'?"true":"false")?>.gif" /></td>
								<td class="alc"><img src="images/layout/ck_<?=($vckVncj["dados"]["flgatendente_clvc"]=='S'?"true":"false")?>.gif" /></td>
								<td><?=$vckVncj["documento_mndc"];?></td>
								<td class="alc"><?=$utils->formataDataBRA($vckVncj["dados"]["dtsolicitacao_clvc"]);?></td>
								<td class="alc"><?=$utils->formataDataBRA($vckVncj["dados"]["dtemissao_clvc"]);?></td>
								<td class="alc"><?=$vckVncj["prazo_mndc"];?> dias <?=(($dtValidade)?"<br> ".$dtValidade:'');?></td>
								<td><?=$vckVncj["dados"]["obs_clvc"];?></td>
							</tr>
							<?
						}elseif(
							( $aProposta["situacao_ppst"] == 1 || $aProposta["situacao_ppst"] == 3 || $aProposta["situacao_ppst"] == 5 ) &&
							(
							// proponente
							  ($cLOGIN->iLEVEL_USUA==1 && $aProposta["vendedores"][0]["despachante_vend"]=='') ||
							// atendente  
								$cLOGIN->iLEVEL_USUA==2 ||
							// despachante
								($cLOGIN->iLEVEL_USUA==6 && $aProposta["vendedores"][0]["despachante_vend"]==$cLOGIN->iID)
							)
						){
							$onclickPpnt = " onclick=\"return validaCamposCkLstPpnt('vfc_ckl_doc_ck_prop_".$vckVncj["cod_mndc"].$incp."','vfc_ckl_doc_ck_atend_".$vckVncj["cod_mndc"].$incp."','vfc_ckl_doc_dt_ped_".$vckVncj["cod_mndc"].$incp."','vfc_ckl_doc_dt_emis_".$vckVncj["cod_mndc"].$incp."');\" ";
							$onclickAtnd = " onclick=\"return validaCamposCkLstAtnd('vfc_ckl_doc_ck_prop_".$vckVncj["cod_mndc"].$incp."','vfc_ckl_doc_ck_atend_".$vckVncj["cod_mndc"].$incp."','vfc_ckl_doc_dt_ped_".$vckVncj["cod_mndc"].$incp."','vfc_ckl_doc_dt_emis_".$vckVncj["cod_mndc"].$incp."');\" ";
							$onblurPedido  = ''; //" onblur=\"return validaCamposCkLstDtPedido('vfc_ckl_doc_ck_prop_".$vckVncj["cod_mndc"].$incp."','vfc_ckl_doc_ck_atend_".$vckVncj["cod_mndc"].$incp."','vfc_ckl_doc_dt_ped_".$vckVncj["cod_mndc"].$incp."','vfc_ckl_doc_dt_emis_".$vckVncj["cod_mndc"].$incp."');\" ";
							$onblurEmissao = ''; //" onblur=\"return validaCamposCkLstDtEmissao('vfc_ckl_doc_ck_prop_".$vckVncj["cod_mndc"].$incp."','vfc_ckl_doc_ck_atend_".$vckVncj["cod_mndc"].$incp."','vfc_ckl_doc_dt_ped_".$vckVncj["cod_mndc"].$incp."','vfc_ckl_doc_dt_emis_".$vckVncj["cod_mndc"].$incp."');\" ";
							?>
							<tr class="tL<? echo $i%2 ? "1" : "2"; ?>">
								<td class="alc"><input type="checkbox" class="ck" name="vfc_ckl_doc_ck_prop_<?=$vckVncj["cod_mndc"].$incp;?>" value="1" id="vfc_ckl_doc_ck_prop_<?=$vckVncj["cod_mndc"].$incp;?>" <?=($vckVncj["dados"]["flgdespachante_clvc"]=='S'?"checked":"")?> <?=$onclickPpnt;?> /></td>
								<td class="alc"><input type="checkbox" class="ck" name="vfc_ckl_doc_ck_atend_<?=$vckVncj["cod_mndc"].$incp;?>" value="1" id="vfc_ckl_doc_ck_atend_<?=$vckVncj["cod_mndc"].$incp;?>" <?=($vckVncj["dados"]["flgatendente_clvc"]=='S'?"checked":"")?> <?=($cLOGIN->iLEVEL_USUA!=2?$onclickFalse:$onclickAtnd)?> /></td>
								<td><?=$vckVncj["documento_mndc"];?></td>
								<td class="alc">
									<input type="hidden" name="clistvendfisconjuge_<?=$vckVncj["cod_mndc"].$incp;?>" value="<?=$vckVncj["cod_mndc"].$incp;?>" />
									<input type="text" style="width:60px;" name="vfc_ckl_doc_dt_ped_<?=$vckVncj["cod_mndc"].$incp;?>" id="vfc_ckl_doc_dt_ped_<?=$vckVncj["cod_mndc"].$incp;?>" value="<?=$utils->formataDataBRA($vckVncj["dados"]["dtsolicitacao_clvc"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10" <?=$onblurPedido;?> />
									<img src="images/buttons/calendario.gif" alt="Ver Calendário" class="cursorMao im" onclick="return showCalendar('vfc_ckl_doc_dt_ped_<?=$vckVncj["cod_mndc"].$incp;?>', 'dd/mm/y');" />
								</td>
								<td class="alc">
									<input type="text" style="width:60px;" name="vfc_ckl_doc_dt_emis_<?=$vckVncj["cod_mndc"].$incp;?>" id="vfc_ckl_doc_dt_emis_<?=$vckVncj["cod_mndc"].$incp;?>" value="<?=$utils->formataDataBRA($vckVncj["dados"]["dtemissao_clvc"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10" <?=$onblurEmissao;?> />
									<img src="images/buttons/calendario.gif" alt="Ver Calendário" class="cursorMao im" onclick="return showCalendar('vfc_ckl_doc_dt_emis_<?=$vckVncj["cod_mndc"].$incp;?>', 'dd/mm/y');" />
								</td>
								<td class="alc"><?=$vckVncj["prazo_mndc"];?> dias <?=(($dtValidade)?"<br> ".$dtValidade:'');?></td>
								<td><textarea style="width:100px; height:30px;" name="vfc_ckl_doc_desc_<?=$vckVncj["cod_mndc"].$incp;?>" id="_<?=$vckVncj["cod_mndc"].$incp;?>"><?=$vckVncj["dados"]["obs_clvc"];?></textarea></td>
							</tr>
						<? }else{ ?>
							<tr class="tL<? echo $i%2 ? "1" : "2"; ?>">
								<td class="alc"><input type="checkbox" class="ck" name="vfc_ckl_doc_ck_prop_<?=$vckVncj["cod_mndc"].$incp;?>" value="1" id="vfc_ckl_doc_ck_prop_[<?=$vckVncj["cod_mndc"].$incp;?>]" <?=($vckVncj["dados"]["flgdespachante_clvc"]=='S'?"checked":"")?> <?=$onclickFalse;?> /></td>
								<td class="alc"><input type="checkbox" class="ck" name="vfc_ckl_doc_ck_atend_<?=$vckVncj["cod_mndc"].$incp;?>" value="1" id="vfc_ckl_doc_ck_atend_[<?=$vckVncj["cod_mndc"].$incp;?>]" <?=($vckVncj["dados"]["flgatendente_clvc"]=='S'?"checked":"")?> <?=$onclickFalse;?> /></td>
								<td><?=$vckVncj["documento_mndc"];?></td>
								<td class="alc"><b><?=$utils->formataDataBRA($vckVncj["dados"]["dtsolicitacao_clvc"]);?></b></td>
								<td class="alc"><b><?=$utils->formataDataBRA($vckVncj["dados"]["dtemissao_clvc"]);?></b></td>
								<td class="alc"><?=$vckVncj["prazo_mndc"];?> dias <?=(($dtValidade)?"<br> ".$dtValidade:'');?></td>
								<td><b><?=$vckVncj["dados"]["obs_clvc"];?></b></td>
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
		if($aAltVend["municipio"][0]["obschecklist_municipio"]){
			print '<div class="obs_cklist"><b>Observações: </b>'.$aAltVend["municipio"][0]["obschecklist_municipio"].'</div>';
		}
	?>
<? } ?>
