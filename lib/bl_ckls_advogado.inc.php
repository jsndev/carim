<?
	$displayDetsAdvCkls = 'display:none;';
	if($acaoProposta=='altCkAdv'){
		$displayDetsAdvCkls = '';
	}
?>

<? if(count($aProposta["checklistadvogado"])>0 || $cLOGIN->iLEVEL_USUA==7){ // ou advogado ?>
	<? if($cLOGIN->iLEVEL_USUA==TPUSER_JURIDICO && $aProposta["situacao_ppst"] == 4){ ?>
		<div id="div_add_ck_adv" style="<?=$displayDetsAdvCkls;?>">
			<div class="grupoDados" style="margin:0px 0px 10px 0px;">
				<? include "bl_detalhes_advogado.inc.php"; ?>
    	</div>
  	</div>
  <? } ?>
  <a name="advogado_cklst"></a>
	<b>Check List</b>
	<div class="tListDiv">
		<table>
			<colgroup>
				<? if($cLOGIN->iLEVEL_USUA==TPUSER_JURIDICO){ ?>
				<col width="25" /><col width="120" /><col width="120" />
				<col width="75" /><col width="75" /><col /><col /><col width="55" />
				<? }else{ ?>
				<col width="25" /><col width="120" /><col width="120" />
				<col width="95" /><col width="95" /><col /><col width="115" />
				<? } ?>
			</colgroup>
			<thead>
				<tr>
					<td>Atend</td>
					<td>Documento</td>
					<td>Entidades</td>
					<td class="alc">Dt Pedido</td>
					<td class="alc">Dt Emissão</td>
					<td class="alc">Obs Advog</td>
					<td class="alc">Obs Atend</td>
					<? if($cLOGIN->iLEVEL_USUA==TPUSER_JURIDICO){ ?><td></td><? } ?>
				</tr>
			</thead>
			<tbody>
			<?
			$onclickFalse = ' onclick="return false;" ';
			if(count($aProposta["checklistadvogado"])>0){
				$i=0;
				foreach($aProposta["checklistadvogado"] as $kckAdv=>$vckAdv){
					if( is_array($vckAdv) ){
						$i++;
						if(
							( $aProposta["situacao_ppst"] == 1 || $aProposta["situacao_ppst"] == 3 || $aProposta["situacao_ppst"] == 5 ) &&
							($cLOGIN->iLEVEL_USUA==TPUSER_ATENDENTE) // atendente
						){
							$onclickAtnd = " onclick=\"return validaCamposCkLstAtnd(false,'adv_ckl_doc_ck_atend_".$vckAdv["cod_clad"]."','adv_ckl_doc_dt_ped_".$vckAdv["cod_clad"]."','adv_ckl_doc_dt_emis_".$vckAdv["cod_clad"]."');\" ";
							$onblurPedido  = ''; //" onblur=\"return validaCamposCkLstDtPedido(false,'adv_ckl_doc_ck_atend_".$vckAdv["cod_clad"]."','adv_ckl_doc_dt_ped_".$vckAdv["cod_clad"]."','adv_ckl_doc_dt_emis_".$vckAdv["cod_clad"]."');\" ";
							$onblurEmissao = ''; //" onblur=\"return validaCamposCkLstDtEmissao(false,'adv_ckl_doc_ck_atend_".$vckAdv["cod_clad"]."','adv_ckl_doc_dt_ped_".$vckAdv["cod_clad"]."','adv_ckl_doc_dt_emis_".$vckAdv["cod_clad"]."');\" ";
							?>
							<tr class="tL<? echo $i%2 ? "1" : "2"; ?>">
								<td class="alc"><input type="checkbox" class="ck" name="adv_ckl_doc_ck_atend_<?=$vckAdv["cod_clad"];?>" value="1" id="adv_ckl_doc_ck_atend_[<?=$vckAdv["cod_clad"];?>]" <?=($vckAdv["flgatendente_clad"]=='S'?"checked":"")?> <?=($cLOGIN->iLEVEL_USUA!=2?$onclickFalse:$onclickAtnd)?> /></td>
								<td><?=$vckAdv["documento_clad"];?></td>
								<td><?=$vckAdv["entidade_clad"];?></td>
								<td class="alc">
									<input type="hidden" name="clistadvogado_<?=$vckAdv["cod_clad"];?>" value="<?=$vckAdv["cod_clad"];?>" />
									<input type="text" style="width:60px;" name="adv_ckl_doc_dt_ped_<?=$vckAdv["cod_clad"];?>" id="adv_ckl_doc_dt_ped_<?=$vckAdv["cod_clad"];?>" value="<?=$utils->formataDataBRA($vckAdv["dtsolicitacao_clad"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10" <?=$onblurPedido;?> />
									<img src="images/buttons/calendario.gif" alt="Ver Calendário" class="cursorMao im" onclick="return showCalendar('adv_ckl_doc_dt_ped_<?=$vckAdv["cod_clad"];?>', 'dd/mm/y');" />
								</td>
								<td class="alc">
									<input type="text" style="width:60px;" name="adv_ckl_doc_dt_emis_<?=$vckAdv["cod_clad"];?>" id="adv_ckl_doc_dt_emis_<?=$vckAdv["cod_clad"];?>" value="<?=$utils->formataDataBRA($vckAdv["dtemissao_clad"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10" <?=$onblurEmissao;?> />
									<img src="images/buttons/calendario.gif" alt="Ver Calendário" class="cursorMao im" onclick="return showCalendar('adv_ckl_doc_dt_emis_<?=$vckAdv["cod_clad"];?>', 'dd/mm/y');" />
								</td>
								<td><?=$vckAdv["obsadvogado_clad"];?></td>
								<td><textarea style="width:110px; height:30px;" name="adv_ckl_doc_desc_<?=$vckAdv["cod_clad"];?>" id="adv_ckl_doc_desc_<?=$vckAdv["cod_clad"];?>"><?=$vckAdv["obsatendente_clad"];?></textarea></td>
							</tr>
						<? }else{ ?>
							<tr class="tL<? echo $i%2 ? "1" : "2"; ?>">
								<td class="alc"><input type="checkbox" class="ck" name="adv_ckl_doc_ck_atend_<?=$vckAdv["cod_clad"];?>" value="1" id="adv_ckl_doc_ck_atend_[<?=$vckAdv["cod_clad"];?>]" <?=($vckAdv["flgatendente_clad"]=='S'?"checked":"")?> <?=$onclickFalse;?> /></td>
								<td><?=$vckAdv["documento_clad"];?></td>
								<td><?=$vckAdv["entidade_clad"];?></td>
								<td class="alc"><?=$utils->formataDataBRA($vckAdv["dtsolicitacao_clad"]);?></td>
								<td class="alc"><?=$utils->formataDataBRA($vckAdv["dtemissao_clad"]);?></td>
								<td><?=$vckAdv["obsadvogado_clad"];?></td>
								<td><?=$vckAdv["obsatendente_clad"];?></td>
								<? if($cLOGIN->iLEVEL_USUA==TPUSER_JURIDICO){ ?>
									<td>
										<img src="images/buttons/bt_excmini.gif"     id="bt_del_ck_adv" alt="Excluir Item" class="im" onclick="delCkAdv('<?=$vckAdv["cod_clad"];?>','<?=$crypt->encrypt('delCkAdv');?>','<?=$vckAdv["documento_clad"];?>');" />
										<img src="images/buttons/bt_alterarmini.gif" id="bt_alt_ck_adv" alt="Alterar Item" class="im" onclick="altCkAdv('<?=$vckAdv["cod_clad"];?>','<?=$crypt->encrypt('altCkAdv');?>');" />
									</td>
								<? } ?>
							</tr>
							</tr>
						<? }
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
			<? if($cLOGIN->iLEVEL_USUA==TPUSER_JURIDICO && $aProposta["situacao_ppst"] == 4){ ?>
  		<tfoot>
				<tr>
					<td colspan="8"><img src="images/buttons/bt_adicionar.gif" id="bt_add_ck_adv" alt="Adicionar Item" class="im" onClick="openFormAddCkAdv();" /></td>
				</tr>
  		</tfoot>
	  	<? } ?>
		</table>
		<input type="hidden" id="qtde_tr_ck_adv" name="qtde_tr_ck_adv" value="<?=$i;?>" />
		<input type="hidden" id="frm_cod_ck_adv" name="frm_cod_ck_adv" value="<?=(($acaoProposta=='altCkAdv')?$_POST["frm_cod_ck_adv"]:'');?>" />
	</div>
<? } ?>
