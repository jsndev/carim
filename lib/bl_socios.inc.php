<?
$displayDetsSocio = 'display:none;';
if($acaoProposta=='altSocio'||$acaoProposta=='dtsSocio'){
	$displayDetsSocio = '';
}
?>
<script language="JavaScript" type="text/javascript" src="./js/proposta_bl_socio.js"></script>
<a name="socios"></a>
<div style="padding-top:10px;"><b>Sócios</b></div>
<div class="grupoDados" style="margin:0px 0px 10px 0px;">
	<div id="div_add_socio" style="<?=$displayDetsSocio;?>">
		<? include "bl_form_socios.inc.php"; ?>
	</div>
	<div class="tListDiv" style="width:auto;">
		<table style="width:680px;">
			<colgroup>
				<col /><col width="150" /><col width="90" />
				<?
					if(
							($cLOGIN->iLEVEL_USUA == 1 && $aProposta["situacao_ppst"] == 1)
						||
							(
							$cLOGIN->iLEVEL_USUA == 2 &&
							$aProposta["situacao_ppst"] >= 2 &&
							$aProposta["situacao_ppst"] <= 3
							)
					){
				?><col width="170" /><? }else{ ?><col width="90" /><? } ?>
			</colgroup>
			<thead>
				<tr>
					<td>Sócio</td>
					<td>Nome Abreviado</td>
					<td>Telefone</td>
					<td></td>
				</tr>
			</thead>
  		<tbody id="tbody_lista_vend_socios">
				<?
					$i = 0;
					if (is_array($aAltVend["vendjursocios"]) && @count($aAltVend["vendjursocios"]) > 0) {
						foreach($aAltVend["vendjursocios"] as $kSocio=>$vSocio){
							$i++;
							$obrigs = $utils->camposObrigatoriosVendSocio($vSocio);
							$alerta = '';
							if($obrigs!==true){
								$alerta .= '<input type="hidden" name="msg_erro_socio_'.$i.'" id="msg_erro_socio_'.$i.'" value="'.$obrigs.'" />';
								$alerta .= '<img src="images/mensagens/alerta.gif" alt="'.$obrigs.'" height="15" /> &nbsp;';
							}else{
								$alerta .= '<input type="hidden" name="msg_erro_socio_'.$i.'" id="msg_erro_socio_'.$i.'" value="" />';
							}
							?>
								<tr class="tL<? echo $i%2 ? "1" : "2"; ?>">
									<td><?=$alerta.$vSocio["nome_vjsoc"];?></td>
									<td><?=$vSocio["nick_vjsoc"];?></td>
									<td><?=$vSocio["telefone_vjsoc"];?></td>
									<td class="alc">
										<?
											if(
													($cLOGIN->iLEVEL_USUA == 1 && $aProposta["situacao_ppst"] == 1)
												||
													(
													$cLOGIN->iLEVEL_USUA == 2 &&
													$aProposta["situacao_ppst"] >= 2 &&
													$aProposta["situacao_ppst"] <= 3
													)
											){
										?>
											<img src="images/buttons/bt_excluir.gif"  alt="Excluir"  class="im" onclick="delSocio('<?=$vSocio["cod_vjsoc"];?>','<?=$aAltVend["cod_vend"];?>','<?=$crypt->encrypt('delSocio');?>','<?=$vSocio["nome_vjsoc"];?>');" />
											<img src="images/buttons/bt_alterar.gif"  alt="Alterar"  class="im" onclick="altSocio('<?=$vSocio["cod_vjsoc"];?>','<?=$aAltVend["cod_vend"];?>','<?=$crypt->encrypt('altSocio');?>');" />
										<? }else{ ?>
											<img src="images/buttons/bt_detalhes.gif" alt="Detalhes" class="im" onclick="dtsSocio('<?=$vSocio["cod_vjsoc"];?>','<?=$aAltVend["cod_vend"];?>','<?=$crypt->encrypt('dtsSocio');?>');" />
										<? } ?>
									</td>
								</tr>
							<?
						}
					}
				?>
  		</tbody>
			<?
				if(
					(
						( $cLOGIN->iLEVEL_USUA == TPUSER_PROPONENTE && $aProposta["situacao_ppst"] == 1) ||
						( $cLOGIN->iLEVEL_USUA == TPUSER_ATENDENTE  && $aProposta["situacao_ppst"] >= 2 && $aProposta["situacao_ppst"] <= 3 )
					)
				){
			?>
  		<tfoot id="tfoot_lista_vend_socios">
				<tr>
					<td colspan="4"><img src="images/buttons/bt_adicionar.gif" id="bt_add_vend" alt="Adicionar Vendedor" class="im" onClick="openFormAddSocio();" /></td>
				</tr>
  		</tfoot>
  		<? } ?>
		</table>
		<input type="hidden" id="qtde_tr_socio" name="qtde_tr_socio" value="<?=$i;?>" />
		<input type="hidden" id="frm_cod_socio" name="frm_cod_socio" value="<?=(($acaoProposta=='altSocio')?$_POST["frm_cod_socio"]:'');?>" />
	</div>
</div>