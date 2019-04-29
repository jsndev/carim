<? 
if($aAltPpnt["checklistfgts"]){ ?>
	<script language="JavaScript" type="text/javascript" src="./js/proposta_bl_despachante.js"></script>
	<a name="conjuge_cklst"></a>
	<b>Check List de FGTS</b>
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
			if(count($aAltPpnt["checklistfgts"])>0){
				$i=0;
				foreach($aAltPpnt["checklistfgts"] as $kckPpfg=>$vckPpfg){
					if( is_array($vckPpfg) ){
						$i++;
						$dtValidade = false;
						if($vckPpfg["dados"]["dtemissao_clfg"]){
							$dia = substr($vckPpfg["dados"]["dtemissao_clfg"],8,2);
							$mes = substr($vckPpfg["dados"]["dtemissao_clfg"],5,2);
							$ano = substr($vckPpfg["dados"]["dtemissao_clfg"],0,4);
							$dtValidade = date("d/m/Y",mktime(0,0,0,$mes,($dia+$vckPpfg["prazo_mndc"]),$ano));
						}
						
						$incp = ($cLOGIN->iLEVEL_USUA==TPUSER_PROPONENTE)?'_'.$aAltPpnt["cod_proponente"]:''; // input name complement
						
						if($just_display){
							?>
							<tr class="tL<? echo $i%2 ? "1" : "2"; ?>">
								<td class="alc"><img src="images/layout/ck_<?=($vckPpfg["dados"]["flgdespachante_clfg"]=='S'?"true":"false")?>.gif" /></td>
								<td class="alc"><img src="images/layout/ck_<?=($vckPpfg["dados"]["flgatendente_clfg"]=='S'?"true":"false")?>.gif" /></td>
								<td><?=$vckPpfg["documento_mndc"];?></td>
								<td class="alc"><?=$utils->formataDataBRA($vckPpfg["dados"]["dtsolicitacao_clfg"]);?></td>
								<td class="alc"><?=$utils->formataDataBRA($vckPpfg["dados"]["dtemissao_clfg"]);?></td>
								<td class="alc"><?=$vckPpfg["prazo_mndc"];?> dias <?=(($dtValidade)?"<br> ".$dtValidade:'');?></td>
								<td><?=$vckPpfg["dados"]["obs_clfg"];?></td>
							</tr>
							<?
						}elseif(
							( $aProposta["situacao_ppst"] == 1 || $aProposta["situacao_ppst"] == 3 || $aProposta["situacao_ppst"] == 5 ) &&
							( $cLOGIN->iLEVEL_USUA == TPUSER_PROPONENTE || $cLOGIN->iLEVEL_USUA == TPUSER_ATENDENTE)
						){
							$onclickPpnt = " onclick=\"return validaCamposCkLstPpnt('pfg_ckl_doc_ck_prop_".$vckPpfg["cod_mndc"].$incp."','pfg_ckl_doc_ck_atend_".$vckPpfg["cod_mndc"].$incp."','pfg_ckl_doc_dt_ped_".$vckPpfg["cod_mndc"].$incp."','pfg_ckl_doc_dt_emis_".$vckPpfg["cod_mndc"].$incp."');\" ";
							$onclickAtnd = " onclick=\"return validaCamposCkLstAtnd('pfg_ckl_doc_ck_prop_".$vckPpfg["cod_mndc"].$incp."','pfg_ckl_doc_ck_atend_".$vckPpfg["cod_mndc"].$incp."','pfg_ckl_doc_dt_ped_".$vckPpfg["cod_mndc"].$incp."','pfg_ckl_doc_dt_emis_".$vckPpfg["cod_mndc"].$incp."');\" ";
							$onblurPedido  = ''; //" onblur=\"return validaCamposCkLstDtPedido('pfg_ckl_doc_ck_prop_".$vckPpfg["cod_mndc"].$incp."','pfg_ckl_doc_ck_atend_".$vckPpfg["cod_mndc"].$incp."','pfg_ckl_doc_dt_ped_".$vckPpfg["cod_mndc"].$incp."','pfg_ckl_doc_dt_emis_".$vckPpfg["cod_mndc"].$incp."');\" ";
							$onblurEmissao = ''; //" onblur=\"return validaCamposCkLstDtEmissao('pfg_ckl_doc_ck_prop_".$vckPpfg["cod_mndc"].$incp."','pfg_ckl_doc_ck_atend_".$vckPpfg["cod_mndc"].$incp."','pfg_ckl_doc_dt_ped_".$vckPpfg["cod_mndc"].$incp."','pfg_ckl_doc_dt_emis_".$vckPpfg["cod_mndc"].$incp."');\" ";
							?>
							<tr class="tL<? echo $i%2 ? "1" : "2"; ?>">
								<td class="alc"><input type="checkbox" class="ck" name="pfg_ckl_doc_ck_prop_<?=$vckPpfg["cod_mndc"].$incp;?>" value="1" id="pfg_ckl_doc_ck_prop_<?=$vckPpfg["cod_mndc"].$incp;?>" <?=($vckPpfg["dados"]["flgdespachante_clfg"]=='S'?"checked":"")?> <?=$onclickPpnt;?> /></td>
								<td class="alc"><input type="checkbox" class="ck" name="pfg_ckl_doc_ck_atend_<?=$vckPpfg["cod_mndc"].$incp;?>" value="1" id="pfg_ckl_doc_ck_atend_<?=$vckPpfg["cod_mndc"].$incp;?>" <?=($vckPpfg["dados"]["flgatendente_clfg"]=='S'?"checked":"")?> <?=($cLOGIN->iLEVEL_USUA!=2?$onclickFalse:$onclickAtnd)?> /></td>
								<td><?=$vckPpfg["documento_mndc"];?></td>
								<td class="alc">
									<input type="hidden" name="clistproponentefgts_<?=$vckPpfg["cod_mndc"].$incp;?>" value="<?=$vckPpfg["cod_mndc"].$incp;?>" />
									<input type="text" style="width:57px;" name="pfg_ckl_doc_dt_ped_<?=$vckPpfg["cod_mndc"].$incp;?>" id="pfg_ckl_doc_dt_ped_<?=$vckPpfg["cod_mndc"].$incp;?>" value="<?=$utils->formataDataBRA($vckPpfg["dados"]["dtsolicitacao_clfg"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10" <?=$onblurPedido;?> />
									<img src="images/buttons/calendario.gif" alt="Ver Calendário" class="cursorMao im" onclick="return showCalendar('pfg_ckl_doc_dt_ped_<?=$vckPpfg["cod_mndc"].$incp;?>', 'dd/mm/y');" />
								</td>
								<td class="alc">
									<input type="text" style="width:57px;" name="pfg_ckl_doc_dt_emis_<?=$vckPpfg["cod_mndc"].$incp;?>" id="pfg_ckl_doc_dt_emis_<?=$vckPpfg["cod_mndc"].$incp;?>" value="<?=$utils->formataDataBRA($vckPpfg["dados"]["dtemissao_clfg"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10" <?=$onblurPedido;?> />
									<img src="images/buttons/calendario.gif" alt="Ver Calendário" class="cursorMao im" onclick="return showCalendar('pfg_ckl_doc_dt_emis_<?=$vckPpfg["cod_mndc"].$incp;?>', 'dd/mm/y');" />
								</td>
								<td class="alc"><?=$vckPpfg["prazo_mndc"];?> dias <?=(($dtValidade)?"<br> ".$dtValidade:'');?></td>
								<td><textarea style="width:90px; height:30px;" name="ppc_ckl_doc_desc_<?=$vckPpfg["cod_mndc"].$incp;?>" id="_<?=$vckPpfg["cod_mndc"].$incp;?>"><?=$vckPpfg["dados"]["obs_clfg"];?></textarea></td>
							</tr>
										
						<? }else{ ?>
							<tr class="tL<? echo $i%2 ? "1" : "2"; ?>">
								<td class="alc"><input type="checkbox" class="ck" name="pfg_ckl_doc_ck_prop_<?=$vckPpfg["cod_mndc"].$incp;?>" value="1" id="pfg_ckl_doc_ck_prop_[<?=$vckPpfg["cod_mndc"].$incp;?>]" <?=($vckPpfg["dados"]["flgdespachante_clfg"]=='S'?"checked":"")?> <?=$onclickFalse;?> /></td>
								<td class="alc"><input type="checkbox" class="ck" name="pfg_ckl_doc_ck_atend_<?=$vckPpfg["cod_mndc"].$incp;?>" value="1" id="pfg_ckl_doc_ck_atend_[<?=$vckPpfg["cod_mndc"].$incp;?>]" <?=($vckPpfg["dados"]["flgatendente_clfg"]=='S'?"checked":"")?> <?=$onclickFalse;?> /></td>
								<td><?=$vckPpfg["documento_mndc"];?></td>
								<td class="alc"><b><?=$utils->formataDataBRA($vckPpfg["dados"]["dtsolicitacao_clfg"]);?></b></td>
								<td class="alc"><b><?=$utils->formataDataBRA($vckPpfg["dados"]["dtemissao_clfg"]);?></b></td>
								<td class="alc"><?=$vckPpfg["prazo_mndc"];?> dias <?=(($dtValidade)?"<br> ".$dtValidade:'');?></td>
								<td><b><?=$vckPpfg["dados"]["obs_clfg"];?></b></td>
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
			</table><table>
			<tr class="tL2">
						<td colspan="8" align="right"><img src="images/buttons/bt_salvar.gif"   id="bt_save_ppnt"   alt="Salvar Proponente" class="im" onClick="savePpnt('<?=$crypt->encrypt('savePpnt');?>');" /></td>
							</tr>	</table>			
	</div>
	<?
		if($aAltPpnt["municipio"][0]["obschecklist_municipio"]){
			print '<div class="obs_cklist"><b>Observações: </b>'.$aAltPpnt["municipio"][0]["obschecklist_municipio"].'</div>';
		}
	?>
<? } ?>
