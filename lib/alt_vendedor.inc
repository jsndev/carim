<?php
$displayDetsVend = 'display:none;';
if($acaoProposta=='altVend'||$acaoProposta=='dtsVend'||$acaoProposta=='altSocio'||$acaoProposta=='dtsSocio'||$acaoProposta=='delSocio'||$acaoProposta=='saveSocio'){
	$displayDetsVend = '';
}

$botoesEdit = false;
if(
	( $cLOGIN->iLEVEL_USUA == TPUSER_PROPONENTE && $aProposta["situacao_ppst"] == 1) ||
	( $cLOGIN->iLEVEL_USUA == TPUSER_ATENDENTE && ($aProposta["situacao_ppst"]<10) )
){
	$botoesEdit = true;
}
$proc_vend='';
$db->query="Select flgproc_vproc, proc_vproc from vendprocurador where cod_ppst='".$aProposta["cod_ppst"]."'";
$db->query();
if($db->qrcount>0)
{
	$flgproc_vproc=$db->qrdata[0]['flgproc_vproc'];
	$proc_vproc=$db->qrdata[0]['proc_vproc'];
	$proc_vend=1;
}

if($_POST['flgproc_vproc'])
{
	$flgproc_vproc=$_POST['flgproc_vproc'];
	$proc_vproc=$_POST['proc_vproc'];
}

if($_POST['hdproc_vend']=='salvar')
{
	if($proc_vend==''){
		$db->query="Insert into vendprocurador (cod_ppst,flgproc_vproc,proc_vproc) values ('".$aProposta["cod_ppst"]."','".$flgproc_vproc."','".$proc_vproc."')";
		$db->query();
	}else{
		$db->query="Update vendprocurador set cod_ppst='".$aProposta["cod_ppst"]."',flgproc_vproc='".$flgproc_vproc."',proc_vproc='".$proc_vproc."' where cod_ppst='".$aProposta["cod_ppst"]."'";
		$db->query();
	}
}
?>
<script language="JavaScript" type="text/javascript" src="./js/proposta_bl_vendedor.js"></script>
<a name="vendedor"></a>
<div class="bloco_include">
	<div class="bloco_titulo">Dados do Vendedor</div>	
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
			<div id="div_add_vend" style="<?php echo $displayDetsVend;?>">
				<div class="grupoDados" style="margin:0px 0px 10px 0px;">
					<?php
						$just_display = false;
						include "bl_form_vendedor.inc.php";
						$just_display = true;
					?>
	    	</div>
    	</div>
			<div class="tListDiv" style="width:auto;">
				<?php
					$total_percentualvenda_vend = 100;
					if( is_array($aProposta["vendedores"]) && @count($aProposta["vendedores"]) > 1 ){
						$total_percentualvenda_vend = 0;
						foreach($aProposta["vendedores"] as $kVend=>$vVend){
							$total_percentualvenda_vend += $vVend["percentualvenda_vend"];
						}
					}
				?>
				<table style="width:704px;">
					<colgroup>
						<col width="<?php echo (($botoesEdit)?389:439);?>" />
						<col width="150" />
						<col width="90" />
						<col width="<?php echo (($botoesEdit)?75:25);?>" />
					</colgroup>
					<thead>
						<tr>
							<td>Vendedor</td>
							<td>Nome Abreviado</td>
							<td>Tipo</td>
							<td></td>
						</tr>
					</thead>
					<tbody>
						<?php
							$ivnd = 0;
							if (is_array($aProposta["vendedores"]) && @count($aProposta["vendedores"]) > 0) {
								foreach($aProposta["vendedores"] as $kVend=>$vVend){
									$ivnd++;
									$flg_show_dets_vend = ($_POST["flg_show_dets_vend_".$ivnd]=='S')?'S':'N';
									$display_tr_vend = ($flg_show_dets_vend=='S')?'':'display:none;';

									$obrigs = $utils->camposObrigatoriosVend($vVend);
									$alerta = '';
									if($total_percentualvenda_vend != 100){
										$obrigs = 'A soma do percentual de venda dos vendedores deve ser 100%';
									}
									if($obrigs!==true){
										$alerta .= '<input type="hidden" name="msg_erro_vend_'.$ivnd.'" id="msg_erro_vend_'.$ivnd.'" value="'.$obrigs.'" />';
										$alerta .= '<img src="images/mensagens/alerta.gif" alt="'.$obrigs.'" height="15" /> &nbsp;';
									}else{
										$alerta .= '<input type="hidden" name="msg_erro_vend_'.$ivnd.'" id="msg_erro_vend_'.$ivnd.'" value="" />';
									}

									?>
										<tr class="tL<?php echo $ivnd%2 ? "1" : "2"; ?>">
											<td><?php echo $alerta.$vVend["nome_vend"];?></td>
											<td><?php echo $vVend["nick_vend"];?></td>
											<td>
												<input type="hidden" name="nome_vend_<?php echo $ivnd;?>" id="nome_vend_<?php echo $ivnd;?>" value="<?php echo $vVend["nome_vend"];?>" />
												<?php
													if($vVend["tipo_vend"]==1) echo "Pessoa Física";
													if($vVend["tipo_vend"]==2) echo "Pessoa Juríca";
												?>
											</td>
											<td class="alc">
												<?php
													if(
														( $cLOGIN->iLEVEL_USUA == TPUSER_PROPONENTE && $aProposta["situacao_ppst"] == 1) ||
														( $cLOGIN->iLEVEL_USUA == TPUSER_ATENDENTE  && ($aProposta["situacao_ppst"]==3 || $aProposta["situacao_ppst"]==5) )
													){
												?>
													<img src="images/buttons/bt_excmini.gif"  alt="Excluir"  class="im" onclick="delVend('<?php echo $vVend["cod_vend"];?>','<?php echo $crypt->encrypt('delVend');?>','<?php echo $vVend["nome_vend"];?>');" />
													<img src="images/buttons/bt_alterarmini.gif"  alt="Alterar"  class="im" onclick="altVend('<?php echo $vVend["cod_vend"];?>','<?php echo $crypt->encrypt('altVend');?>');" />
												<?php } ?>
												<img src="images/buttons/bt_visualizarmini.gif" alt="Detalhes" class="im" onclick="showDetsVend(<?php echo $ivnd;?>);" />
												<img src="images/buttons/bt_alterarmini.gif"  alt="Alterar"  class="im" onclick="altVend('<?php echo $vVend["cod_vend"];?>','<?php echo $crypt->encrypt('altVend');?>');" />
											</td>
										</tr>
										<tr class="tL<?php echo $ivnd%2 ? "1" : "2"; ?>" id="tr_dets_vend_<?php echo $ivnd;?>" style="<?php echo $display_tr_vend;?>">
											<td colspan="4">
												<input type="hidden" name="flg_show_dets_vend_<?php echo $ivnd;?>" id="flg_show_dets_vend_<?php echo $ivnd;?>" value="<?php echo $flg_show_dets_vend;?>" />
												<?php include "bl_detalhes_vendedor.inc.php"; ?>
											</td>
										</tr>
									<?php
								}
							}
						?>
					</tbody>
					<?php
						if(
							( $cLOGIN->iLEVEL_USUA == TPUSER_PROPONENTE && $aProposta["situacao_ppst"] == 1) ||
							( $cLOGIN->iLEVEL_USUA == TPUSER_ATENDENTE  && ($aProposta["situacao_ppst"]==3 || $aProposta["situacao_ppst"]==5) )
						){
					?>
	    		<tfoot>
						<tr>
							<td colspan="4"><img src="images/buttons/bt_adicionar.gif" id="bt_add_vend" alt="Adicionar Vendedor" class="im" onClick="openFormAddVend();" /></td>
						</tr>
	    		</tfoot>
	    		<?php } ?>
				</table>
				<input type="hidden" id="qtde_tr_vend" name="qtde_tr_vend" value="<?php echo $ivnd;?>" />
				<input type="hidden" id="frm_cod_vend" name="frm_cod_vend" value="<?php echo (($acaoProposta=='altVend'||$acaoProposta=='altSocio'||$acaoProposta=='dtsVend'||$acaoProposta=='dtsSocio')?$_POST["frm_cod_vend"]:'');?>" />
			</div><br>
			<?php 
		if($aProposta["situacao_ppst"]<8){?>
			<b>Procurador do Vendedor:</b>
				<div id="div_proc" class="grupoDados" style="clear:both;">
				<table cellpadding=0 cellspacing=5 border=0 class="tb_dets_list">
	<tr align="right" valign="top">
		<td align="right" valign="top">Procurador:<?php $utils->obrig('flgproc_vproc'); ?></td>
		<td align="left"  valign="top">			  
		<?php
			  	$display_dets_flgproc_vend = ($flgproc_vproc=='S')?'':'display:none;';
				?>
			<input type="radio" class="rd" name="flgproc_vproc" id="flgproc_vproc" value="S" <?php if($flgproc_vproc=='S') echo "checked";?>  onclick="trocouProcuradorVend(this);" /> Sim &nbsp;&nbsp;
			<input type="radio" class="rd" name="flgproc_vproc" id="flgproc_vproc" value="N" <?php if($flgproc_vproc=='N') echo "checked";?>  onclick="trocouProcuradorVend(this);" /> Não &nbsp;&nbsp;
		</td>
	</tr>
	<tr id="tb_dets_proc_vend" style="<?php echo $display_dets_flgproc_vend;?>">
		<td align="right" valign="top">Dados do Procurador:<?php $utils->obrig('proc_ppnt'); ?></td>
		<td align="left"  valign="top"><textarea cols="90" rows="7" name="proc_vproc" id="proc_vproc"><?php if($proc_vproc==''){echo "Sr(a). (nome), (nacionalidade), (estado civil), (profissão), portador da cédula de identidade RG n° (número/órgão emissor) e inscrito no CPF/MF sob n° (número), residente e domiciliado na (rua), (n°), (complemento), (cidade/UF), nos termos da procuração lavrada no  ___°. Tabelionato de Notas de (cidade), às fls. (n°) do livro (n°), datada de (DD/MM/AAAA), cujo traslado é parte integrante do presente contrato.";}else{echo $proc_vproc;}?></textarea></td>
	</tr>
	<tr>
		<td></td>
		<td align="right"><input type="image" value="salvar" class="im" alt="Salvar Informações de Procurador" src="images/buttons/bt_salvar.gif" onClick="SalvarProcVend();">
		<input type="hidden" name="hdproc_vend" id="hdproc_vend" value=""></td>
	</tr>
 				</table>
				</div>
			<?php
			}else{
			?>
				<table cellpadding=0 cellspacing=5 border=0 class="tb_dets_list">
				<tr align="right" valign="top">
					<td align="right" valign="top">Procurador:</td>
					<td align="left"  valign="top"><b>			  
					<?php if($flgproc_vproc=='S') echo "Sim";?><?php if($flgproc_vproc=='N' || $flgproc_vproc=='') echo "Não";?></b></td>
				</tr>
				<?php
				if($flgproc_vproc=='S'){
				?>
				<tr id="tb_dets_proc_vend">
					<td align="right" valign="top">Dados do Procurador:</td>
					<td align="left"  valign="top"><?php echo $proc_vproc;?></td>
				</tr>
				<?php
				}
				?>
 				</table>
			<?php
			}
			?>
		</div>
		<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
	</div>
</div>
