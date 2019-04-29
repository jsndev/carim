<script>

function trocouTemInvQuitante(_obj){
	if(_obj.value=='S' && _obj.checked){
		document.getElementById('dados_intvq').style.display = 'block';
		document.getElementById('checkflg_intv').value='S';
	}else{
		document.getElementById('dados_intvq').style.display = 'none';
		document.getElementById('checkflg_intv').value='N';
	}
	
}


</script>
<?
//echo $aProposta["situacao_ppst"];
$cod_ppst=$_GET['cod_proposta'];
$flagCredor='';

if(isset($_POST['flg_intv'])){
$flg_intv=$_POST['flg_intv'];
	if($flg_intv=='S'){
	$nome_intq=$_POST['nome_intq'];
	$nomeabr_intq=$_POST['nomeabr_intq'];
	$cod_logr_intq=$_POST['cod_logr_intq'];
	$endereco_intq=$_POST['endereco_intq'];
	$nrendereco_intq=$_POST['nrendereco_intq'];
	$cpendereco_intq=$_POST['cpendereco_intq'];
	$cod_bairro_intq=$_POST['cod_bairro_intq'];
	$cep_intq=$utils->limpaCep($_POST['cep_intq']);
	$cod_municipio_intq=$_POST['cod_municipio_intq'];
	$cod_uf_intq=$_POST['cod_uf_intq'];
	$telefone_intq=$utils->limpaTelefone($_POST['telefone_intq']);
	$cnpj_intq=$utils->limpaCnpj($_POST['cnpj_intq']);
	$vlsaldodev_intq=$utils->moeda2db($_POST['vlsaldodev_intq']);
	
	$db->query="Select count(*) as totalintvq from intvquitante where cod_ppst='".$aProposta["cod_ppst"]."'";
	$db->query();
	$totalintvq=$db->qrdata[0]['totalintvq'];
	
		if($totalintvq>0){
		$db->query="UPDATE intvquitante SET 	nome_intq='$nome_intq',nomeabr_intq='$nomeabr_intq',cod_logr='$cod_logr_intq',endereco_intq='$endereco_intq',nrendereco_intq='$nrendereco_intq',cpendereco_intq='$cpendereco_intq',cod_bairro='$cod_bairro_intq',cep_intq='$cep_intq',cod_municipio='$cod_municipio_intq',telefone_intq='$telefone_intq',cnpj_intq='$cnpj_intq',vlsaldodev_intq='$vlsaldodev_intq' WHERE cod_ppst='".$aProposta["cod_ppst"]."'";
		$db->query();
		}
		else{
		$db->query="INSERT INTO intvquitante SET cod_ppst='".$aProposta["cod_ppst"]."',nome_intq='$nome_intq',nomeabr_intq='$nomeabr_intq',cod_logr='$cod_logr_intq',endereco_intq='$endereco_intq',nrendereco_intq='$nrendereco_intq',cpendereco_intq='$cpendereco_intq',cod_bairro='$cod_bairro_intq',cep_intq='$cep_intq',cod_municipio='$cod_municipio_intq',telefone_intq='$telefone_intq',cnpj_intq='$cnpj_intq',vlsaldodev_intq='$vlsaldodev_intq'";
		$db->query();
		}
		echo mysql_error();
	
	$db->query="UPDATE proposta SET INTVQUITANTE_PPST='S' WHERE cod_ppst='".$aProposta["cod_ppst"]."'";
	$db->query();
	}
	else{
	$nome_intq='';
	$nomeabr_intq='';
	$cod_logr_intq='';
	$endereco_intq='';
	$nrendereco_intq='';
	$cpendereco_intq='';
	$cod_bairro_intq='';
	$cep_intq='';
	$cod_municipio_intq='';
	$cod_uf_intq='';
	$telefone_intq='';
	$cnpj_intq='';
	$vlsaldodev_intq='';
	$db->query="DELETE FROM intvquitante WHERE cod_ppst='".$aProposta["cod_ppst"]."'";
	$db->query();
	$db->query="UPDATE proposta SET INTVQUITANTE_PPST='N' WHERE cod_ppst='".$aProposta["cod_ppst"]."'";
	$db->query();
	}

}
else{
$db->query="Select INTVQUITANTE_PPST from proposta where cod_ppst='".$aProposta["cod_ppst"]."'";
$db->query();
$flg_intv=$db->qrdata[0]['INTVQUITANTE_PPST'];

	$db->query="Select * from intvquitante where cod_ppst='".$aProposta["cod_ppst"]."'";
	$db->query();
	$nome_intq=  	$db->qrdata[0]['NOME_INTQ'];
	$nomeabr_intq=  		$db->qrdata[0]['NOMEABR_INTQ'];
	$cod_logr_intq=  	$db->qrdata[0]['COD_LOGR'];
	$endereco_intq=  	    $db->qrdata[0]['ENDERECO_INTQ'];
	$nrendereco_intq=  		$db->qrdata[0]['NRENDERECO_INTQ'];
	$cpendereco_intq=  	$db->qrdata[0]['CPENDERECO_INTQ'];
	$cod_bairro_intq=  		$db->qrdata[0]['COD_BAIRRO'];
	$cep_intq=  	$db->qrdata[0]['CEP_INTQ'];
	$cod_municipio_intq=  	    $db->qrdata[0]['COD_MUNICIPIO'];
	$telefone_intq=  	$db->qrdata[0]['TELEFONE_INTQ'];
	$cnpj_intq=  		$db->qrdata[0]['CNPJ_INTQ'];
	$vlsaldodev_intq=  	$db->qrdata[0]['VLSALDODEV_INTQ'];
	
	$db->query="Select COD_UF,NOME_MUNICIPIO from municipio where COD_MUNICIPIO='".$cod_municipio_intq."'";
	$db->query();
	$cod_uf_intq=$db->qrdata[0]['COD_UF'];
	$nome_municipio_intq=$db->qrdata[0]['NOME_MUNICIPIO'];
}


/*
if($_POST)
{
	$flg_crd=  			$_POST['flg_crd'];
	$crd_gravame=  		$_POST['crd_gravame'];
	$crd_reg=  			$_POST['crd_reg'];
	$crd_cart=  		$_POST['crd_cart'];
	$crd_rec=  			$_POST['crd_rec'];
	$crd_rec=str_replace(".","",$crd_rec);
	$crd_rec=str_replace(",",".",$crd_rec);
}
if ($acaoProposta=='salvar')
{

	if($flagCredor=='')
	{
		$db->query="INSERT INTO	credorquitante (
							FLG_CRD,
							TPGRAVAME_CRD,
							NRREGISTRO_CRD,
							CARTORIO_CRD,
							RECURSOS_CRD,
							COD_PPST)
					VALUES(
							'".$flg_crd."',
							'".$crd_gravame."',
							'".$crd_reg."',
							'".$crd_cart."',
							'".$crd_rec."',
							'".$aProposta["cod_ppst"]."')";
		$db->query();
	}else
	{
		$db->query="UPDATE credorquitante SET 
							FLG_CRD='".$flg_crd."',
							TPGRAVAME_CRD='".$crd_gravame."',
							NRREGISTRO_CRD='".$crd_reg."',
							CARTORIO_CRD='".$crd_cart."',
							RECURSOS_CRD='".$crd_rec."',
							COD_PPST='".$aProposta["cod_ppst"]."'
					 WHERE
					 	 cod_ppst='".$aProposta["cod_ppst"]."'";
		$db->query();
	}
		
}
*/
?>

			<?
				if(
						$cLOGIN->iLEVEL_USUA == TPUSER_ATENDENTE &&
						($aProposta["situacao_ppst"]<=3)	
					){
					?>

<a name="pagamento"></a>
<div class="bloco_include" id="bloco_pagamento">
	<div class="bloco_titulo">Qualificação do Interveniente Quitante</div>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt="" /></div>
		<div class="quadroInternoMeio">
		    		<table cellpadding=0 cellspacing=0 border=0 width="100%">
							  <tr>
								<td align="left" valign="top">Interveniente Quitante:&nbsp;&nbsp;
<input type="radio" class="rd" name="flg_intv" id="flg_intv" value="S" <?=($flg_intv=='S')?'checked':'';?> onclick="trocouTemInvQuitante(this);" /> <b>Sim</b> &nbsp;&nbsp;<input type="radio" class="rd" name="flg_intv" id="flg_intv" value="N" <?=($flg_intv=='N')?'checked':'';?> onclick="trocouTemInvQuitante(this);" /><b>Não</b>
											<?php
							     		    $display_dados_intvq   = ($flg_intv=='S')?'':'display:none;';
										  ?>
                                          <input type="hidden" name="checkflg_intv" id="checkflg_intv" value="<?=$flg_intv;?>" />
								</td>
							  </tr>
							  							  <tr>
								<td align="left" valign="top" >&nbsp;</td>
							  </tr>
							  <tr id="dados_intvq" style="<?php echo $display_dados_intvq;?>">
							  	<td>
								
							<table cellpadding=0 cellspacing=5 border=0 width="100%">
									<tr>
									<td align="right" valign="top" width="150">Nome: <span class="obrig"> *</span></td>
									<td align="left"  valign="top"><input type="text" style="width:300px;" name="nome_intq" id="nome_intq" value="<?php echo $nome_intq;?>" maxlength="70"></td>
								</tr>
								 <tr>
									  <td align="right" valign="top">Nome Abreviado: <span class="obrig"> *</span></td>
									  <td align="left"  valign="top"><input type="text" style="width:150px;" name="nomeabr_intq" id="nomeabr_intq" value="<?php echo $nomeabr_intq;?>" maxlength="15"></td>
								</tr>
								  <tr>
									<td align="right" valign="top">Tipo Logradouro: <span class="obrig"> *</span></td>
									<td align="left"  valign="top">
									  <select name="cod_logr_intq" id="cod_logr_intq">
										<option value="0" >-Selecione-</option>
										<?php
											foreach($listas->getListaLogradouro() as $k=>$v){
												$selected = ($cod_logr_intq==$v['cod_logr'])?'selected':'';
												print '<option value="'.$v['cod_logr'].'" '.$selected.'>'.$v['desc_logr'].'</option>';
											}
										?>
									  </select>
									</td>
								  </tr>
								<tr>
								  <td align="right" valign="top">Endereço: <span class="obrig"> *</span></td>
								  <td align="left"  valign="top"><input type="text" style="width:350px;" name="endereco_intq" id="endereco_intq" value="<?php echo $endereco_intq;?>" maxlength="40"></td>
								</tr>
								<tr>
								  <td align="right" valign="top">Num: <span class="obrig"> *</span></td>
								  <td align="left"  valign="top"><input type="text" style="width:40px;" name="nrendereco_intq" id="nrendereco_intq" value="<?php echo $nrendereco_intq;?>" maxlength="6" onKeyDown="return teclasInt(this,event);"></td>
								</tr>
								<tr>
								  <td align="right" valign="top">Complemento:</td>
								  <td align="left"  valign="top"><input type="text" style="width:150px;" name="cpendereco_intq" id="cpendereco_intq" value="<?php echo $cpendereco_intq;?>" maxlength="15"></td>
								</tr>
								  <tr>
									<td align="right" valign="top">Bairro: <span class="obrig"> *</span></td>
									<td align="left"  valign="top">
									  <select name="cod_bairro_intq" id="cod_bairro_intq">
										<option value="0" >-Selecione-</option>
										<?php
											foreach($listas->getListaBairro() as $k=>$v){
												$selected = ($cod_bairro_intq==$v['cod_bairro'])?'selected':'';
												print '<option value="'.$v['cod_bairro'].'" '.$selected.'>'.$v['nome_bairro'].'</option>';
											}
										?>
									  </select>
									</td>
								  </tr>
								<tr>
								  <td align="right" valign="top">CEP: <span class="obrig"> *</span></td>
								  <td align="left"  valign="top"><input type="text" style="width:150px;" name="cep_intq" id="cep_intq" value="<?php echo $utils->formataCep($cep_intq);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraCEP(this,event);" maxlength="9"></td>
								</tr>
									<tr>
									<td align="right" valign="top">Estado: <span class="obrig"> *</span></td>
									<td align="left"  valign="top">
									  <select name="cod_uf_intq" id="cod_uf_intq" onChange="getListaMunicipios_v2(this,'cod_municipio_intq');">
										<option value="0" >-Selecione-</option>
										<?php
											foreach($listas->getListaUF() as $k=>$v){
												$selected = ($cod_uf_intq==$v['cod_uf'])?'selected':'';
												print '<option value="'.$v['cod_uf'].'" '.$selected.'>'.$v['nome_uf'].'</option>';
											}
										?>
									  </select>
									  &nbsp;Cidade: <span class="obrig"> *</span>
									  <select name="cod_municipio_intq" id="cod_municipio_intq">
										<option value="0" >-Selecione-</option>
										<?php
											if($cod_uf_intq){
												foreach($listas->getListaMunicipio($cod_uf_intq) as $k=>$v){
													$selected = ($cod_municipio_intq==$v['cod_municipio'])?'selected':'';
													print '<option value="'.$v['cod_municipio'].'" '.$selected.'>'.$v['nome_municipio'].'</option>';
												}
											}
										?>
									  </select>
									</td>
								  </tr>
								<tr>
								  <td align="right" valign="top">Telefone: <span class="obrig"> *</span></td>
								  <td align="left"  valign="top"><input type="text" style="width:100px;" name="telefone_intq" id="telefone_intq" value="<?php echo $utils->formataTelefone($telefone_intq);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraTEL(this,event);" maxlength="13"></td>
								</tr>
								<tr>
								  <td align="right" valign="top">CNPJ: <span class="obrig"> *</span></td>
								  <td align="left"  valign="top"><input type="text" style="width:150px;" name="cnpj_intq" id="cnpj_intq" value="<?php echo $utils->formataCnpj($cnpj_intq);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraCNPJ(this,event);"  maxlength="18"></td>
								</tr>
								<tr>
									<td align="right" valign="top">Valor do saldo devedor (R$): <span class="obrig"> *</span></td>
									<td align="left"  valign="top"><input type="text" style="width:80px;" name="vlsaldodev_intq" id="vlsaldodev_intq" value="<?php echo $utils->formataMoeda($vlsaldodev_intq);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraMoeda(this,event,null,2);" maxlength="12" /></td>
								</tr>
								</table>
								
								
								</td>
							  </tr>
		    		</table>
		</div>
		<div><img src="images/layout/subquadro_b.gif" alt="" /></div>
	</div>
</div>
<?
}
else
{
?>
<a name="pagamento"></a>
<div class="bloco_include" id="bloco_pagamento">
	<div class="bloco_titulo">Qualificação de Interveniente Quitante</div>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt="" /></div>
		<div class="quadroInternoMeio">
		    		<table cellpadding=0 cellspacing=5 border=0 width="100%">
		    			<colgroup><col width="150" /><col width="*" /></colgroup>
							  <tr>
								<td align="right" valign="top">Interveniente Quitante:	</td><td><?php
								
										if($flg_intv=='S') echo "<b>Sim</b>"; else echo "<b>Não</b>";
											 
										  ?>
								</td>
							</tr>
							<?php 
							if($flg_intv=="S"){
							?>
							 <tr>
									<td align="right" valign="top" width="150">Nome: </td><td><strong><?php echo $nome_intq;?></strong></td>
								</tr>
								 <tr>
									  <td align="right" valign="top">Nome Abreviado: </td><td><strong><?php echo $nomeabr_intq;?></strong></td>
								</tr>
								  <tr>
									<td align="right" valign="top">Tipo Logradouro: </td>
									<td align="left"  valign="top"><strong>
										<?php
                                        $db->query="Select DESC_LOGR from logradouro where COD_LOGR='".$cod_logr_intq."'";
										$db->query();
										echo $db->qrdata[0]['DESC_LOGR'];
                                        ?></strong>
									</td>
								  </tr>
								<tr>
								  <td align="right" valign="top">Endereço: </td>
								  <td align="left"  valign="top"><strong><?php echo $endereco_intq;?></strong></td>
								</tr>
								<tr>
								  <td align="right" valign="top">Num: </td>
								  <td align="left"  valign="top"><strong><?php echo $nrendereco_intq;?></strong></td>
								</tr>
								<tr>
								  <td align="right" valign="top">Complemento:</td>
								  <td align="left"  valign="top"><strong><?php echo $cpendereco_intq;?></strong></td>
								</tr>
								  <tr>
									<td align="right" valign="top">Bairro: </td>
									<td align="left"  valign="top">
										<strong><?php
                                        $db->query="Select NOME_BAIRRO from bairro where COD_BAIRRO='".$cod_bairro_intq."'";
										$db->query();
										echo $db->qrdata[0]['NOME_BAIRRO'];
                                        ?></strong>
									</td>
								  </tr>
								<tr>
								  <td align="right" valign="top">CEP: </td>
								  <td align="left"  valign="top"><strong><?php echo $utils->formataCep($cep_intq);?></strong></td>
								</tr>
									<tr>
									<td align="right" valign="top">Município: </td>
									<td align="left"  valign="top">
										<strong><?php
										echo $nome_municipio_intq . " - " . $cod_uf_intq;
                                        ?></strong>
									</td>
								  </tr>
								<tr>
								  <td align="right" valign="top">Telefone: </td>
								  <td align="left"  valign="top"><strong><?php echo $utils->formataTelefone($telefone_intq);?></strong></td>
								</tr>
								<tr>
								  <td align="right" valign="top">CNPJ: </td>
								  <td align="left"  valign="top"><strong><?php echo $utils->formataCnpj($cnpj_intq);?></strong></td>
								</tr>
								<tr>
									<td align="right" valign="top">Valor do saldo devedor (R$): </td>
									<td align="left"  valign="top"><strong><?php echo $utils->formataMoeda($vlsaldodev_intq);?></strong></td>
								</tr>
                                <?php
                                }
								?>

		    		</table>
		</div>
		<div><img src="images/layout/subquadro_b.gif" alt="" /></div>
	</div>
</div>

<?

}?>
