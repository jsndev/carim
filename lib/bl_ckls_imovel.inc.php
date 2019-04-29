<? if(count($aProposta["imovel"]["checklist"])>0){ ?>
	<script
		src="https://code.jquery.com/jquery-2.2.4.min.js"
		integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
		crossorigin="anonymous"></script>
	<script language="JavaScript" type="text/javascript" src="./js/proposta_bl_despachante.js"></script>


	<script>

		jQuery(function () {

			jQuery('#all_atend').attr('disabled', 'disabled');

			jQuery('#all_ok').click(function () {

				$(':checkbox.ok').prop('checked', this.checked);

				jQuery('.ok').each(function(i, value){
					jQuery(value).click();
					jQuery(value).click();
				});

				jQuery('#all_atend').attr('disabled', 'disabled');
				if (this.checked) {
					jQuery('#all_atend').removeAttr('disabled');
				}
			});

			jQuery('#all_atend').click(function () {

				$(':checkbox.atend').prop('checked', this.checked);

				jQuery('.atend').each(function(i, value){
					jQuery(value).click();
					jQuery(value).click();
				});
			});


		})
	</script>
	<br /><br />
	<a name="imovel_cklst"></a>
	<b>Check List do Imóvel</b>
	<div class="tListDiv">
		<table id="ckLstImovel">
			<colgroup>
				<col width="25" /><col width="25" /><col width="240" />
				<col width="100" /><col width="100" /><col width="70" /><col />
			</colgroup>
			<thead>
				<tr>
					<td style="width: 38px;">
						<input type="checkbox" class="ck" value="1" id="all_ok" />
						OK
					</td>
					<td style="width: 53px;">
						<input type="checkbox" class="ck" value="1" id="all_atend" />
						Atend
					</td>
					<td>Documento</td>
					<td style="width: 103px;" class="alc">Dt Pedido</td>
					<td style="width: 103px;" class="alc">Dt Emissão</td>
					<td class="alc">Valid</td>
					<td class="alc">Obs</td>
				</tr>
			</thead>
			<tbody>
			<?
			$onclickFalse = ' onclick="return false;" ';
			if(count($aProposta["imovel"]["checklist"])>0){
				$i=0;
				foreach($aProposta["imovel"]["checklist"] as $kckImov=>$vckImov){
					if( is_array($vckImov) ){
						$i++;
						$dtValidade = false;
						if($vckImov["dados"]["dtemissao_clim"]){
							$dia = substr($vckImov["dados"]["dtemissao_clim"],8,2);
							$mes = substr($vckImov["dados"]["dtemissao_clim"],5,2);
							$ano = substr($vckImov["dados"]["dtemissao_clim"],0,4);
							$dtValidade = date("d/m/Y",mktime(0,0,0,$mes,($dia+$vckImov["prazo_mndc"]),$ano));
						}
						
						if(
							(
								$aProposta["situacao_ppst"] == 1 ||
								$aProposta["situacao_ppst"] == 3 ||
								$aProposta["situacao_ppst"] == 5
							)
							&&
							(
							// proponente
							  ($cLOGIN->iLEVEL_USUA==1 && $aProposta["imovel"]["despachante_imov"]=='') ||
							// atendente  
								$cLOGIN->iLEVEL_USUA==2 ||
							// despachante
								($cLOGIN->iLEVEL_USUA==6 && $aProposta["imovel"]["despachante_imov"]==$cLOGIN->iID)
							)
						){
							$onclickPpnt = " onclick=\"return validaCamposCkLstPpnt('imv_ckl_doc_ck_prop_".$vckImov["cod_mndc"]."','imv_ckl_doc_ck_atend_".$vckImov["cod_mndc"]."','imv_ckl_doc_dt_ped_".$vckImov["cod_mndc"]."','imv_ckl_doc_dt_emis_".$vckImov["cod_mndc"]."');\" ";
							$onclickAtnd = " onclick=\"return validaCamposCkLstAtnd('imv_ckl_doc_ck_prop_".$vckImov["cod_mndc"]."','imv_ckl_doc_ck_atend_".$vckImov["cod_mndc"]."','imv_ckl_doc_dt_ped_".$vckImov["cod_mndc"]."','imv_ckl_doc_dt_emis_".$vckImov["cod_mndc"]."');\" ";
							$onblurPedido  = ''; //" onblur=\"return validaCamposCkLstDtPedido('imv_ckl_doc_ck_prop_".$vckImov["cod_mndc"]."','imv_ckl_doc_ck_atend_".$vckImov["cod_mndc"]."','imv_ckl_doc_dt_ped_".$vckImov["cod_mndc"]."','imv_ckl_doc_dt_emis_".$vckImov["cod_mndc"]."');\" ";
							$onblurEmissao = ''; //" onblur=\"return validaCamposCkLstDtEmissao('imv_ckl_doc_ck_prop_".$vckImov["cod_mndc"]."','imv_ckl_doc_ck_atend_".$vckImov["cod_mndc"]."','imv_ckl_doc_dt_ped_".$vckImov["cod_mndc"]."','imv_ckl_doc_dt_emis_".$vckImov["cod_mndc"]."');\" ";
							?>
							<tr class="tL<? echo $i%2 ? "1" : "2"; ?>">
								<td class="alc"><input type="checkbox" class="ck ok" name="imv_ckl_doc_ck_prop_<?=$vckImov["cod_mndc"];?>" value="1" id="imv_ckl_doc_ck_prop_<?=$vckImov["cod_mndc"];?>" <?=($vckImov["dados"]["flgdespachante_clim"]=='S'?"checked":"")?> <?=$onclickPpnt;?> /></td>
								<td class="alc"><input type="checkbox" class="ck atend" name="imv_ckl_doc_ck_atend_<?=$vckImov["cod_mndc"];?>" value="1" id="imv_ckl_doc_ck_atend_<?=$vckImov["cod_mndc"];?>" <?=($vckImov["dados"]["flgatendente_clim"]=='S'?"checked":"")?> <?=($cLOGIN->iLEVEL_USUA!=2?$onclickFalse:$onclickAtnd)?> /></td>
								<td><?=$vckImov["documento_mndc"];?></td>
								<td class="alc">
									<input type="hidden" name="clistimovel_<?=$vckImov["cod_mndc"];?>" value="<?=$vckImov["cod_mndc"];?>" />
									<input type="text" style="width:60px;" name="imv_ckl_doc_dt_ped_<?=$vckImov["cod_mndc"];?>" id="imv_ckl_doc_dt_ped_<?=$vckImov["cod_mndc"];?>" value="<?=$utils->formataDataBRA($vckImov["dados"]["dtsolicitacao_clim"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10" <?=$onblurPedido;?> />
									<img src="images/buttons/calendario.gif" alt="Ver Calend?rio" class="cursorMao im" onclick="return showCalendar('imv_ckl_doc_dt_ped_<?=$vckImov["cod_mndc"];?>', 'dd/mm/y');" />
								</td>
								<td class="alc">
									<input type="text" style="width:60px;" name="imv_ckl_doc_dt_emis_<?=$vckImov["cod_mndc"];?>" id="imv_ckl_doc_dt_emis_<?=$vckImov["cod_mndc"];?>" value="<?=$utils->formataDataBRA($vckImov["dados"]["dtemissao_clim"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10" <?=$onblurEmissao;?> />
									<img src="images/buttons/calendario.gif" alt="Ver Calend?rio" class="cursorMao im" onclick="return showCalendar('imv_ckl_doc_dt_emis_<?=$vckImov["cod_mndc"];?>', 'dd/mm/y');" />
								</td>
								<td class="alc"><?=$vckImov["prazo_mndc"];?> dias <?=(($dtValidade)?"<br> ".$dtValidade:'');?></td>
								<td><textarea style="width:130px; height:30px;" name="imv_ckl_doc_desc_<?=$vckImov["cod_mndc"];?>" id="_<?=$vckImov["cod_mndc"];?>"><?=$vckImov["dados"]["obs_clim"];?></textarea></td>
							</tr>
						<? }else{ ?>
							<tr class="tL<? echo $i%2 ? "1" : "2"; ?>">
								<td class="alc"><input type="checkbox" class="ck" name="imv_ckl_doc_ck_prop_<?=$vckImov["cod_mndc"];?>" value="1" id="imv_ckl_doc_ck_prop_[<?=$vckImov["cod_mndc"];?>]" <?=($vckImov["dados"]["flgdespachante_clim"]=='S'?"checked":"")?> <?=$onclickFalse;?> /></td>
								<td class="alc"><input type="checkbox" class="ck" name="imv_ckl_doc_ck_atend_<?=$vckImov["cod_mndc"];?>" value="1" id="imv_ckl_doc_ck_atend_[<?=$vckImov["cod_mndc"];?>]" <?=($vckImov["dados"]["flgatendente_clim"]=='S'?"checked":"")?> <?=$onclickFalse;?> /></td>
								<td><?=$vckImov["documento_mndc"];?></td>
								<td class="alc"><b><?=$utils->formataDataBRA($vckImov["dados"]["dtsolicitacao_clim"]);?></b></td>
								<td class="alc"><b><?=$utils->formataDataBRA($vckImov["dados"]["dtemissao_clim"]);?></b></td>
								<td class="alc"><?=$vckImov["prazo_mndc"];?> dias <?=(($dtValidade)?"<br> ".$dtValidade:'');?></td>
								<td><?=$vckImov["dados"]["obs_clim"];?></td>
							</tr>
						<?
						}
					}
				}
			} else {
				?>
				<tr class="tL2">
					<td colspan="8" align="center"> N?o existem documentos cadastrados para o munic?pio do Im?vel.</td>
				</tr>
				<?
			}
			?>
			</tbody>
		</table>
	</div>
	<?
		if($aProposta["imovel"]["municipio"][0]["obschecklist_municipio"]){
			print '<div class="obs_cklist"><b>Observa??es: </b>'.$aProposta["imovel"]["municipio"][0]["obschecklist_municipio"].'</div>';
		}
	?>
	
<? } ?>