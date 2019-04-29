<? if(count($aAltVend["vendjur"][0]["checklist"])>0){ ?>
	<script language="JavaScript" type="text/javascript" src="./js/proposta_bl_despachante.js"></script>
	<a name="vnpj_cklst"></a>
	<b>Check List do Vendedor</b>
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
			if(count($aAltVend["vendjur"][0]["checklist"])>0){
				$i=0;
				foreach($aAltVend["vendjur"][0]["checklist"] as $kckVjur=>$vckVjur){
					if( is_array($vckVjur) ){
						$i++;
						$dtValidade = false;
						if($vckVjur["dados"]["dtemissao_clpn"]){
							$dia = substr($vckVjur["dados"]["dtemissao_clpn"],8,2);
							$mes = substr($vckVjur["dados"]["dtemissao_clpn"],5,2);
							$ano = substr($vckVjur["dados"]["dtemissao_clpn"],0,4);
							$dtValidade = date("d/m/Y",mktime(0,0,0,$mes,($dia+$vckVjur["prazo_mndc"]),$ano));
						}
						
						$incp = ($cLOGIN->iLEVEL_USUA==TPUSER_PROPONENTE)?'_'.$aAltVend["cod_vend"]:''; // input name complement
						
						if($just_display){
							?>
							<tr class="tL<? echo $i%2 ? "1" : "2"; ?>">
								<td class="alc"><img src="images/layout/ck_<?=($vckVjur["dados"]["flgdespachante_clpn"]=='S'?"true":"false")?>.gif" /></td>
								<td class="alc"><img src="images/layout/ck_<?=($vckVjur["dados"]["flgatendente_clpn"]=='S'?"true":"false")?>.gif" /></td>
								<td><?=$vckVjur["documento_mndc"];?></td>
								<td class="alc"><?=$utils->formataDataBRA($vckVjur["dados"]["dtsolicitacao_clpn"]);?></td>
								<td class="alc"><?=$utils->formataDataBRA($vckVjur["dados"]["dtemissao_clpn"]);?></td>
								<td class="alc"><?=$vckVjur["prazo_mndc"];?> dias <?=(($dtValidade)?"<br> ".$dtValidade:'');?></td>
								<td><?=$vckVjur["dados"]["obs_clpn"];?></td>
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
							$onclickPpnt = " onclick=\"return validaCamposCkLstPpnt('vjr_ckl_doc_ck_prop_".$vckVjur["cod_mndc"].$incp."','vjr_ckl_doc_ck_atend_".$vckVjur["cod_mndc"].$incp."','vjr_ckl_doc_dt_ped_".$vckVjur["cod_mndc"].$incp."','vjr_ckl_doc_dt_emis_".$vckVjur["cod_mndc"].$incp."');\" ";
							$onclickAtnd = " onclick=\"return validaCamposCkLstAtnd('vjr_ckl_doc_ck_prop_".$vckVjur["cod_mndc"].$incp."','vjr_ckl_doc_ck_atend_".$vckVjur["cod_mndc"].$incp."','vjr_ckl_doc_dt_ped_".$vckVjur["cod_mndc"].$incp."','vjr_ckl_doc_dt_emis_".$vckVjur["cod_mndc"].$incp."');\" ";
							$onblurPedido  = ''; //" onblur=\"return validaCamposCkLstDtPedido('vjr_ckl_doc_ck_prop_".$vckVjur["cod_mndc"].$incp."','vjr_ckl_doc_ck_atend_".$vckVjur["cod_mndc"].$incp."','vjr_ckl_doc_dt_ped_".$vckVjur["cod_mndc"].$incp."','vjr_ckl_doc_dt_emis_".$vckVjur["cod_mndc"].$incp."');\" ";
							$onblurEmissao = ''; //" onblur=\"return validaCamposCkLstDtEmissao('vjr_ckl_doc_ck_prop_".$vckVjur["cod_mndc"].$incp."','vjr_ckl_doc_ck_atend_".$vckVjur["cod_mndc"].$incp."','vjr_ckl_doc_dt_ped_".$vckVjur["cod_mndc"].$incp."','vjr_ckl_doc_dt_emis_".$vckVjur["cod_mndc"].$incp."');\" ";
							?>
							<tr class="tL<? echo $i%2 ? "1" : "2"; ?>">
								<td class="alc"><input type="checkbox" class="ck" name="vjr_ckl_doc_ck_prop_<?=$vckVjur["cod_mndc"].$incp;?>" value="1" id="vjr_ckl_doc_ck_prop_<?=$vckVjur["cod_mndc"].$incp;?>" <?=($vckVjur["dados"]["flgdespachante_clpn"]=='S'?"checked":"")?> <?=$onclickPpnt;?> /></td>
								<td class="alc"><input type="checkbox" class="ck" name="vjr_ckl_doc_ck_atend_<?=$vckVjur["cod_mndc"].$incp;?>" value="1" id="vjr_ckl_doc_ck_atend_<?=$vckVjur["cod_mndc"].$incp;?>" <?=($vckVjur["dados"]["flgatendente_clpn"]=='S'?"checked":"")?> <?=($cLOGIN->iLEVEL_USUA!=2?$onclickFalse:$onclickAtnd)?> /></td>
								<td><?=$vckVjur["documento_mndc"];?></td>
								<td class="alc">
									<input type="hidden" name="clistvendjur_<?=$vckVjur["cod_mndc"].$incp;?>" value="<?=$vckVjur["cod_mndc"].$incp;?>" />
									<input type="text" style="width:60px;" name="vjr_ckl_doc_dt_ped_<?=$vckVjur["cod_mndc"].$incp;?>" id="vjr_ckl_doc_dt_ped_<?=$vckVjur["cod_mndc"].$incp;?>" value="<?=$utils->formataDataBRA($vckVjur["dados"]["dtsolicitacao_clpn"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10" <?=$onblurPedido;?> />
									<img src="images/buttons/calendario.gif" alt="Ver Calendário" class="cursorMao im" onclick="return showCalendar('vjr_ckl_doc_dt_ped_<?=$vckVjur["cod_mndc"].$incp;?>', 'dd/mm/y');" />
								</td>
								<td class="alc">
									<input type="text" style="width:60px;" name="vjr_ckl_doc_dt_emis_<?=$vckVjur["cod_mndc"].$incp;?>" id="vjr_ckl_doc_dt_emis_<?=$vckVjur["cod_mndc"].$incp;?>" value="<?=$utils->formataDataBRA($vckVjur["dados"]["dtemissao_clpn"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10" <?=$onblurEmissao;?> />
									<img src="images/buttons/calendario.gif" alt="Ver Calendário" class="cursorMao im" onclick="return showCalendar('vjr_ckl_doc_dt_emis_<?=$vckVjur["cod_mndc"].$incp;?>', 'dd/mm/y');" />
								</td>
								<td class="alc"><?=$vckVjur["prazo_mndc"];?> dias <?=(($dtValidade)?"<br> ".$dtValidade:'');?></td>
								<td><textarea style="width:100px; height:30px;" name="vjr_ckl_doc_desc_<?=$vckVjur["cod_mndc"].$incp;?>" id="_<?=$vckVjur["cod_mndc"].$incp;?>"><?=$vckVjur["dados"]["obs_clpn"];?></textarea></td>
							</tr>
						<? }else{ ?>
							<tr class="tL<? echo $i%2 ? "1" : "2"; ?>">
								<td class="alc"><input type="checkbox" class="ck" name="vjr_ckl_doc_ck_prop_<?=$vckVjur["cod_mndc"].$incp;?>" value="1" id="vjr_ckl_doc_ck_prop_[<?=$vckVjur["cod_mndc"].$incp;?>]" <?=($vckVjur["dados"]["flgdespachante_clpn"]=='S'?"checked":"")?> <?=$onclickFalse;?> /></td>
								<td class="alc"><input type="checkbox" class="ck" name="vjr_ckl_doc_ck_atend_<?=$vckVjur["cod_mndc"].$incp;?>" value="1" id="vjr_ckl_doc_ck_atend_[<?=$vckVjur["cod_mndc"].$incp;?>]" <?=($vckVjur["dados"]["flgatendente_clpn"]=='S'?"checked":"")?> <?=$onclickFalse;?> /></td>
								<td><?=$vckVjur["documento_mndc"];?></td>
								<td class="alc"><b><?=$utils->formataDataBRA($vckVjur["dados"]["dtsolicitacao_clpn"]);?></b></td>
								<td class="alc"><b><?=$utils->formataDataBRA($vckVjur["dados"]["dtemissao_clpn"]);?></b></td>
								<td class="alc"><?=$vckVjur["prazo_mndc"];?> dias <?=(($dtValidade)?"<br> ".$dtValidade:'');?></td>
								<td><?=$vckVjur["dados"]["obs_clpn"];?></td>
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
