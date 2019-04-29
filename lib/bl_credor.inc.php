<script language="JavaScript" type="text/javascript" src="./js/proposta_bl_pagamento.js"></script>
<script>
function offTR(_id){
	document.getElementById(_id).style.display = 'none';
}

function onTR(_id){
	if(navigator.appName=='Netscape'){
		document.getElementById(_id).style.display = 'table-row';
	}else{
		document.getElementById(_id).style.display = 'block';
	}
}
function trocouTemCredor(_obj){
	if(_obj.value=='S' && _obj.checked){
		camposCredor(true);
	}else{
		camposCredor(false);
	}
}

function camposCredor(_yn){
	
	if(_yn){
		onTR('dt_credor');
		onTR('dt_gravame');
		onTR('dt_reg');
		onTR('dt_cart');
		onTR('dt_rec');
		
	}else{
		offTR('dt_credor');
		offTR('dt_gravame');
		offTR('dt_reg');
		offTR('dt_cart');
		offTR('dt_rec');
		document.getElementById('crd_gravame').value='';
		document.getElementById('crd_reg').value='';
		document.getElementById('crd_cart').value='';
		document.getElementById('crd_rec').value='';
	}
}
</script>
<?
//echo $aProposta["situacao_ppst"];
$cod_ppst=$_GET['cod_proposta'];
$flagCredor='';

$db->query="Select * from credorquitante where cod_ppst='".$aProposta["cod_ppst"]."'";
$db->query();
if($db->qrcount>0)
{
	$flg_crd=  		$db->qrdata[0]['FLG_CRD'];
	$crd_gravame=  	$db->qrdata[0]['TPGRAVAME_CRD'];
	$crd_reg=  		$db->qrdata[0]['NRREGISTRO_CRD'];
	$crd_cart=  	$db->qrdata[0]['CARTORIO_CRD'];
	$crd_rec=  	    $db->qrdata[0]['RECURSOS_CRD'];
	$flagCredor= 1;
}
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
?>
			<?
				if(
						$cLOGIN->iLEVEL_USUA == TPUSER_ATENDENTE &&
						($aProposta["situacao_ppst"]==3 || $aProposta["situacao_ppst"]==5)	
					){
					?>

<a name="pagamento"></a>
<div class="bloco_include" id="bloco_pagamento">
	<div class="bloco_titulo">Qualificação de Credor Quitante</div>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt="" /></div>
		<div class="quadroInternoMeio">
		    		<table cellpadding=0 cellspacing=5 border=0 width="100%">
		    			<colgroup><col width="150" /><col width="*" /></colgroup>
							  <tr>
								<td align="right" valign="top">Credor Quitante:</td>
								<td align="left"  valign="top">		<?php
											$display_dados_credor = 'display:none;';
											foreach ($listas->getListaSN() as $k=>$v){
											$checked = ($flg_crd==$k)?'checked':'';
											print '<input type="radio" class="rd" name="flg_crd" id="flg_crd" value="'.$k.'" '.$checked.' onclick="trocouTemCredor(this);" /> <b>'.$v.'</b> &nbsp;&nbsp;';
							     		    $display_dados_credor   = ($flg_crd=='S')?'':'display:none;';
											 
											}
										  ?>
								</td>
							  </tr>
							  <tr id="dt_credor" style="<?php echo $display_dados_credor;?>">
							  	<td align="right">Credor:</td>
								<td align="left"><b>Caixa de Previdência dos Funcionários do Banco do Brasil</b></td>
							  </tr>
  							  <tr id="dt_gravame" style="<?php echo $display_dados_credor;?>">
							  	<td align="right">Gravame:</td>
								<td align="left"><input type="text" name="crd_gravame" id="crd_gravame" value="<?php echo $crd_gravame;?>" size="30"></td>
							  </tr>
							  <tr id="dt_reg" style="<?php echo $display_dados_credor;?>">
							  	<td align="right">Registro:</td>
								<td align="left"><input type="text" name="crd_reg" id="crd_reg" value="<?php echo $crd_reg;?>" size="30"></td>
							  </tr>
							  <tr id="dt_cart" style="<?php echo $display_dados_credor;?>">
							  	<td align="right">Cartório:</td>
								<td align="left"><input type="text" name="crd_cart" id="crd_cart" value="<?php echo $crd_cart;?>" size="60"></td>
							  </tr>
							  <tr id="dt_rec" style="<?php echo $display_dados_credor;?>">
							  	<td align="right">Recurso Liberado (R$):</td>
								<td align="left"><input type="text" name="crd_rec" id="crd_rec" value="<?php echo $utils->formataMoeda($crd_rec);?>" size="15" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraMoeda(this,event,'atualizaValoresProposta()',2);" ></td>
							  </tr>
		    		</table>
	  	<input type="hidden" name="hidden_dtpagtoboleto_ppst" id="hidden_dtpagtoboleto_ppst" value="<?=$utils->formataDataBRA($aProposta["dtpagtoboleto_ppst"]);?>" />
		</div>
		<div><img src="images/layout/subquadro_b.gif" alt="" /></div>
	</div>
</div>
<?
}
else
{
if($aProposta["situacao_ppst"] > 5)
{?>
<a name="pagamento"></a>
<div class="bloco_include" id="bloco_pagamento">
	<div class="bloco_titulo">Qualificação de Credor Quitante</div>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt="" /></div>
		<div class="quadroInternoMeio">
		    		<table cellpadding=0 cellspacing=5 border=0 width="100%">
		    			<colgroup><col width="150" /><col width="*" /></colgroup>
							  <tr>
								<td align="right" valign="top">Credor Quitante:</td>
								<td align="left"  valign="top">		<?php
								
										if($flg_crd=='S') echo "<b>Sim</b>"; else echo "<b>Não</b>";
											 
										  ?>
								</td>
							</tr>
							<?php 
							if($flg_crd=="S"){
							?>
							 <tr id="dt_credor">
							  	<td align="right">Credor:</td>
								<td align="left"><b>Caixa de Previdência dos Funcionários do Banco do Brasil</b></td>
							  </tr>
  							  <tr id="dt_gravame">
							  	<td align="right">Gravame:</td>
								<td align="left"><b><?php echo $crd_gravame;?></b></td>
							  </tr>
							  <tr id="dt_reg">
							  	<td align="right">Registro:</td>
								<td align="left"><b></b><?php echo $crd_reg;?></b></td>
							  </tr>
							  <tr id="dt_cart">
							  	<td align="right">Cartório:</td>
								<td align="left"><b><?php echo $crd_cart;?></b></td>
							  </tr>
							  <tr id="dt_cart">
							  	<td align="right">Recurso Liberado (R$):</td>
								<td align="left"><b><?php echo $utils->formataMoeda($crd_rec);?></b></td>
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
}
}?>
