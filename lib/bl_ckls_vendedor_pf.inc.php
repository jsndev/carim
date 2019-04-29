<? if(count($aAltVend["vendfis"][0]["checklist"])>0){ ?>
	<script language="JavaScript" type="text/javascript" src="./js/proposta_bl_despachante.js"></script>
	<br /><br />
	<a name="vnpf_cklst"></a>
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
			if(count($aAltVend["vendfis"][0]["checklist"])>0){
				$i=0;
				foreach($aAltVend["vendfis"][0]["checklist"] as $kckVfis=>$vckVfis){
					if( is_array($vckVfis) ){
						$i++;
						$dtValidade = false;
						if($vckVfis["dados"]["dtemissao_clvf"]){
							$dia = substr($vckVfis["dados"]["dtemissao_clvf"],8,2);
							$mes = substr($vckVfis["dados"]["dtemissao_clvf"],5,2);
							$ano = substr($vckVfis["dados"]["dtemissao_clvf"],0,4);
							$dtValidade = date("d/m/Y",mktime(0,0,0,$mes,($dia+$vckVfis["prazo_mndc"]),$ano));
						}
						
						$incp = ($cLOGIN->iLEVEL_USUA==TPUSER_PROPONENTE)?'_'.$aAltVend["cod_vend"]:''; // input name complement
						
						if($just_display){
							?>
							<tr class="tL<? echo $i%2 ? "1" : "2"; ?>">
								<td class="alc"><img src="images/layout/ck_<?=($vckVfis["dados"]["flgdespachante_clvf"]=='S'?"true":"false")?>.gif" /></td>
								<td class="alc"><img src="images/layout/ck_<?=($vckVfis["dados"]["flgatendente_clvf"]=='S'?"true":"false")?>.gif" /></td>
								<td><?=$vckVfis["documento_mndc"];?></td>
								<td class="alc"><?=$utils->formataDataBRA($vckVfis["dados"]["dtsolicitacao_clvf"]);?></td>
								<td class="alc"><?=$utils->formataDataBRA($vckVfis["dados"]["dtemissao_clvf"]);?></td>
								<td class="alc"><?=$vckVfis["prazo_mndc"];?> dias <?=(($dtValidade)?"<br> ".$dtValidade:'');?></td>
								<td><?=$vckVfis["dados"]["obs_clvf"];?></td>
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
							$onclickPpnt = " onclick=\"return validaCamposCkLstPpnt('vdf_ckl_doc_ck_prop_".$vckVfis["cod_mndc"].$incp."','vdf_ckl_doc_ck_atend_".$vckVfis["cod_mndc"].$incp."','vdf_ckl_doc_dt_ped_".$vckVfis["cod_mndc"].$incp."','vdf_ckl_doc_dt_emis_".$vckVfis["cod_mndc"].$incp."');\" ";
							$onclickAtnd = " onclick=\"return validaCamposCkLstAtnd('vdf_ckl_doc_ck_prop_".$vckVfis["cod_mndc"].$incp."','vdf_ckl_doc_ck_atend_".$vckVfis["cod_mndc"].$incp."','vdf_ckl_doc_dt_ped_".$vckVfis["cod_mndc"].$incp."','vdf_ckl_doc_dt_emis_".$vckVfis["cod_mndc"].$incp."');\" ";
							$onblurPedido  = ''; //" onblur=\"return validaCamposCkLstDtPedido('vdf_ckl_doc_ck_prop_".$vckVfis["cod_mndc"].$incp."','vdf_ckl_doc_ck_atend_".$vckVfis["cod_mndc"].$incp."','vdf_ckl_doc_dt_ped_".$vckVfis["cod_mndc"].$incp."','vdf_ckl_doc_dt_emis_".$vckVfis["cod_mndc"].$incp."');\" ";
							$onblurEmissao = ''; //" onblur=\"return validaCamposCkLstDtEmissao('vdf_ckl_doc_ck_prop_".$vckVfis["cod_mndc"].$incp."','vdf_ckl_doc_ck_atend_".$vckVfis["cod_mndc"].$incp."','vdf_ckl_doc_dt_ped_".$vckVfis["cod_mndc"].$incp."','vdf_ckl_doc_dt_emis_".$vckVfis["cod_mndc"].$incp."');\" ";
							?>
							<tr class="tL<? echo $i%2 ? "1" : "2"; ?>">
								<td class="alc"><input type="checkbox" class="ck" name="vdf_ckl_doc_ck_prop_<?=$vckVfis["cod_mndc"].$incp;?>" value="1" id="vdf_ckl_doc_ck_prop_<?=$vckVfis["cod_mndc"].$incp;?>" <?=($vckVfis["dados"]["flgdespachante_clvf"]=='S'?"checked":"")?> <?=$onclickPpnt;?> /></td>
								<td class="alc"><input type="checkbox" class="ck" name="vdf_ckl_doc_ck_atend_<?=$vckVfis["cod_mndc"].$incp;?>" value="1" id="vdf_ckl_doc_ck_atend_<?=$vckVfis["cod_mndc"].$incp;?>" <?=($vckVfis["dados"]["flgatendente_clvf"]=='S'?"checked":"")?> <?=($cLOGIN->iLEVEL_USUA!=2?$onclickFalse:$onclickAtnd)?> /></td>
								<td><?=$vckVfis["documento_mndc"];?></td>
								<td class="alc">
									<input type="hidden" name="clistvendfis_<?=$vckVfis["cod_mndc"].$incp;?>" value="<?=$vckVfis["cod_mndc"].$incp;?>" />
									<input type="text" style="width:60px;" name="vdf_ckl_doc_dt_ped_<?=$vckVfis["cod_mndc"].$incp;?>" id="vdf_ckl_doc_dt_ped_<?=$vckVfis["cod_mndc"].$incp;?>" value="<?=$utils->formataDataBRA($vckVfis["dados"]["dtsolicitacao_clvf"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10" <?=$onblurPedido;?> />
									<img src="images/buttons/calendario.gif" alt="Ver Calendário" class="cursorMao im" onclick="return showCalendar('vdf_ckl_doc_dt_ped_<?=$vckVfis["cod_mndc"].$incp;?>', 'dd/mm/y');" />
								</td>
								<td class="alc">
									<input type="text" style="width:60px;" name="vdf_ckl_doc_dt_emis_<?=$vckVfis["cod_mndc"].$incp;?>" id="vdf_ckl_doc_dt_emis_<?=$vckVfis["cod_mndc"].$incp;?>" value="<?=$utils->formataDataBRA($vckVfis["dados"]["dtemissao_clvf"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10" <?=$onblurEmissao;?> />
									<img src="images/buttons/calendario.gif" alt="Ver Calendário" class="cursorMao im" onclick="return showCalendar('vdf_ckl_doc_dt_emis_<?=$vckVfis["cod_mndc"].$incp;?>', 'dd/mm/y');" />
								</td>
								<td class="alc"><?=$vckVfis["prazo_mndc"];?> dias <?=(($dtValidade)?"<br> ".$dtValidade:'');?></td>
								<td><textarea style="width:100px; height:30px;" name="vdf_ckl_doc_desc_<?=$vckVfis["cod_mndc"].$incp;?>" id="_<?=$vckVfis["cod_mndc"].$incp;?>"><?=$vckVfis["dados"]["obs_clvf"];?></textarea></td>
							</tr>
						<? }else{ ?>
							<tr class="tL<? echo $i%2 ? "1" : "2"; ?>">
								<td class="alc"><input type="checkbox" class="ck" name="vdf_ckl_doc_ck_prop_<?=$vckVfis["cod_mndc"].$incp;?>" value="1" id="vdf_ckl_doc_ck_prop_[<?=$vckVfis["cod_mndc"].$incp;?>]" <?=($vckVfis["dados"]["flgdespachante_clvf"]=='S'?"checked":"")?> <?=$onclickFalse;?> /></td>
								<td class="alc"><input type="checkbox" class="ck" name="vdf_ckl_doc_ck_atend_<?=$vckVfis["cod_mndc"].$incp;?>" value="1" id="vdf_ckl_doc_ck_atend_[<?=$vckVfis["cod_mndc"].$incp;?>]" <?=($vckVfis["dados"]["flgatendente_clvf"]=='S'?"checked":"")?> <?=$onclickFalse;?> /></td>
								<td><?=$vckVfis["documento_mndc"];?></td>
								<td class="alc"><b><?=$utils->formataDataBRA($vckVfis["dados"]["dtsolicitacao_clvf"]);?></b></td>
								<td class="alc"><b><?=$utils->formataDataBRA($vckVfis["dados"]["dtemissao_clvf"]);?></b></td>
								<td class="alc"><?=$vckVfis["prazo_mndc"];?> dias <?=(($dtValidade)?"<br> ".$dtValidade:'');?></td>
								<td><?=$vckVfis["dados"]["obs_clvf"];?></td>
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
