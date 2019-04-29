<? if(count($aAltPpnt["checklist"])>0){ ?>
	<script language="JavaScript" type="text/javascript" src="./js/proposta_bl_despachante.js"></script>
	<a name="proponente_cklst"></a>
	<b>Check List do Proponente</b>
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
			if(count($aAltPpnt["checklist"])>0){
				$i=0;
				foreach($aAltPpnt["checklist"] as $kckPpnt=>$vckPpnt){
					if( is_array($vckPpnt) ){
						$i++;
						$dtValidade = false;
						if($vckPpnt["dados"]["dtemissao_clpn"]){
							$dia = substr($vckPpnt["dados"]["dtemissao_clpn"],8,2);
							$mes = substr($vckPpnt["dados"]["dtemissao_clpn"],5,2);
							$ano = substr($vckPpnt["dados"]["dtemissao_clpn"],0,4);
							$dtValidade = date("d/m/Y",mktime(0,0,0,$mes,($dia+$vckPpnt["prazo_mndc"]),$ano));
						}

						$incp = ($cLOGIN->iLEVEL_USUA==TPUSER_PROPONENTE)?'_'.$aAltPpnt["cod_proponente"]:''; // input name complement
						
						if(1){
							$onclickPpnt = " onclick=\"return validaCamposCkLstPpnt('ppn_ckl_doc_ck_prop_".$vckPpnt["cod_mndc"].$incp."','ppn_ckl_doc_ck_atend_".$vckPpnt["cod_mndc"].$incp."','ppn_ckl_doc_dt_ped_".$vckPpnt["cod_mndc"].$incp."','ppn_ckl_doc_dt_emis_".$vckPpnt["cod_mndc"].$incp."');\" ";
							$onclickAtnd = " onclick=\"return validaCamposCkLstAtnd('ppn_ckl_doc_ck_prop_".$vckPpnt["cod_mndc"].$incp."','ppn_ckl_doc_ck_atend_".$vckPpnt["cod_mndc"].$incp."','ppn_ckl_doc_dt_ped_".$vckPpnt["cod_mndc"].$incp."','ppn_ckl_doc_dt_emis_".$vckPpnt["cod_mndc"].$incp."');\" ";
							$onblurPedido  = ''; //" onblur=\"return validaCamposCkLstDtPedido('ppn_ckl_doc_ck_prop_".$vckPpnt["cod_mndc"].$incp."','ppn_ckl_doc_ck_atend_".$vckPpnt["cod_mndc"].$incp."','ppn_ckl_doc_dt_ped_".$vckPpnt["cod_mndc"].$incp."','ppn_ckl_doc_dt_emis_".$vckPpnt["cod_mndc"].$incp."');\" ";
							$onblurEmissao = ''; //" onblur=\"return validaCamposCkLstDtEmissao('ppn_ckl_doc_ck_prop_".$vckPpnt["cod_mndc"].$incp."','ppn_ckl_doc_ck_atend_".$vckPpnt["cod_mndc"].$incp."','ppn_ckl_doc_dt_ped_".$vckPpnt["cod_mndc"].$incp."','ppn_ckl_doc_dt_emis_".$vckPpnt["cod_mndc"].$incp."');\" ";
							?>
							<tr class="tL<? echo $i%2 ? "1" : "2"; ?>">
								<td class="alc"><input type="checkbox" class="ck" name="ppn_ckl_doc_ck_prop_<?=$vckPpnt["cod_mndc"].$incp;?>" value="1" id="ppn_ckl_doc_ck_prop_<?=$vckPpnt["cod_mndc"].$incp;?>" <?=($vckPpnt["dados"]["flgdespachante_clpn"]=='S'?"checked":"")?> <?=$onclickPpnt;?> /></td>
								<td class="alc"><input type="checkbox" class="ck" name="ppn_ckl_doc_ck_atend_<?=$vckPpnt["cod_mndc"].$incp;?>" value="1" id="ppn_ckl_doc_ck_atend_<?=$vckPpnt["cod_mndc"].$incp;?>" <?=($vckPpnt["dados"]["flgatendente_clpn"]=='S'?"checked":"")?> <?=($cLOGIN->iLEVEL_USUA!=2?$onclickFalse:$onclickAtnd)?> /></td>
								<td><?=$vckPpnt["documento_mndc"];?></td>
								<td class="alc">
									<input type="hidden" name="clistproponente_<?=$vckPpnt["cod_mndc"].$incp;?>" value="<?=$vckPpnt["cod_mndc"].$incp;?>" />
									<input type="text" style="width:60px;" name="ppn_ckl_doc_dt_ped_<?=$vckPpnt["cod_mndc"].$incp;?>" id="ppn_ckl_doc_dt_ped_<?=$vckPpnt["cod_mndc"].$incp;?>" value="<?=$utils->formataDataBRA($vckPpnt["dados"]["dtsolicitacao_clpn"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10" <?=$onblurPedido;?> />
									<img src="images/buttons/calendario.gif" alt="Ver Calendário" class="cursorMao im" onclick="return showCalendar('ppn_ckl_doc_dt_ped_<?=$vckPpnt["cod_mndc"].$incp;?>', 'dd/mm/y');" />
								</td>
								<td class="alc">
									<input type="text" style="width:60px;" name="ppn_ckl_doc_dt_emis_<?=$vckPpnt["cod_mndc"].$incp;?>" id="ppn_ckl_doc_dt_emis_<?=$vckPpnt["cod_mndc"].$incp;?>" value="<?=$utils->formataDataBRA($vckPpnt["dados"]["dtemissao_clpn"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10" <?=$onblurEmissao;?> />
									<img src="images/buttons/calendario.gif" alt="Ver Calendário" class="cursorMao im" onclick="return showCalendar('ppn_ckl_doc_dt_emis_<?=$vckPpnt["cod_mndc"].$incp;?>', 'dd/mm/y');" />
								</td>
								<td class="alc"><?=$vckPpnt["prazo_mndc"];?> dias <?=(($dtValidade)?"<br> ".$dtValidade:'');?></td>
								<td><textarea style="width:100px; height:30px;" name="ppn_ckl_doc_desc_<?=$vckPpnt["cod_mndc"].$incp;?>" id="_<?=$vckPpnt["cod_mndc"].$incp;?>"><?=$vckPpnt["dados"]["obs_clpn"];?></textarea></td>
							</tr>
						<? }else{ ?>
							<tr class="tL<? echo $i%2 ? "1" : "2"; ?>">
								<td class="alc"><input type="checkbox" class="ck" name="ppn_ckl_doc_ck_prop_<?=$vckPpnt["cod_mndc"].$incp;?>" value="1" id="ppn_ckl_doc_ck_prop_[<?=$vckPpnt["cod_mndc"].$incp;?>]" <?=($vckPpnt["dados"]["flgdespachante_clpn"]=='S'?"checked":"")?> <?=$onclickFalse;?> /></td>
								<td class="alc"><input type="checkbox" class="ck" name="ppn_ckl_doc_ck_atend_<?=$vckPpnt["cod_mndc"].$incp;?>" value="1" id="ppn_ckl_doc_ck_atend_[<?=$vckPpnt["cod_mndc"].$incp;?>]" <?=($vckPpnt["dados"]["flgatendente_clpn"]=='S'?"checked":"")?> <?=$onclickFalse;?> /></td>
								<td><?=$vckPpnt["documento_mndc"];?></td>
								<td class="alc"><b><?=$utils->formataDataBRA($vckPpnt["dados"]["dtsolicitacao_clpn"]);?></b></td>
								<td class="alc"><b><?=$utils->formataDataBRA($vckPpnt["dados"]["dtemissao_clpn"]);?></b></td>
								<td class="alc"><?=$vckPpnt["prazo_mndc"];?> dias <?=(($dtValidade)?"<br> ".$dtValidade:'');?></td>
								<td><?=$vckPpnt["dados"]["obs_clpn"];?></td>
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