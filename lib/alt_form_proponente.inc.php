<?php

 $obrig="<span class='obrig'>*</span>";

	$aAltPpnt = array();
	if($acaoProposta=='altPpnt' || $acaoProposta=='dtsPpnt' || $acaoFgts=='salvar' || $prop_addexig==1 || $conj_addexig==1 || $fgts_addexig==1){
		if (is_array($aProposta["proponentes"]) && @count($aProposta["proponentes"]) > 0) {
			foreach($aProposta["proponentes"] as $kPpnt=>$vPpnt){
				if($vPpnt["cod_proponente"] == $_POST["frm_cod_ppnt"]){
					$aAltPpnt = $vPpnt;
				}
			}
		}
	}
if($financiamento > $vlmax)
{
	?>
	<script language="javascript">
		alert('Valor de Financiamento proposto maior que Limite Autorizado.');
	</script>
	
	<?php
}

function BDData($data)
{
	$dia=substr($data,0,2);
	$mes=substr($data,3,2);
	$ano=substr($data,6,4);
	$newdate=($ano."-".$mes."-".$dia);
	return $newdate;
}

  function formataDecimal($valor){
	$valor = str_replace(".", "",$valor);
	$valor=str_replace(",", ".",$valor);
    return $valor;
  }
	
$db->query="Select id_lstn from usuario where cod_usua='".$proponente."'";
$db->query();
if($db->qrcount>0)
{
 $participante=$db->qrdata[0]['id_lstn'];
}

$acaoFgts=$_POST['acaoFgts'];
$query = "Select * from fgts where cod_usua='".$proponente."'";
$result =mysql_query($query);
$linhas= mysql_num_rows($result);
$registro = mysql_fetch_array($result, MYSQL_ASSOC);
	
	$aAltPpnt["flgfgts_ppnt"] 	= 				$registro['FLAGUTILIZACAO'];
	$aAltPpnt["fgts"][0]["stimov_fgts"] = 		$registro['STATUSIMOV'];
	$aAltPpnt["fgts"][0]["municipio_fgts"] = 	$registro['NOMEMUNIBGE'];
	$aAltPpnt["fgts"][0]["codmunicipio_fgts"] = $registro['CODMUNIBGE'];
	$aAltPpnt["fgts"][0]["estado_fgts"]=		$registro['UFIBGE'];
	$aAltPpnt["fgts"][0]["qtcontas"]=			$registro['QTCONTAS'];
	$aAltPpnt["fgts"][0]["teste_fgts"]=		$registro['VALOPERACAO'];
	$flagvloper=$registro['VALOPERACAO'];
//echo "VALOR 1:".$aAltPpnt["fgts"][0]["teste_fgts"];
if($linhas>0)
{
	$dadosfgts=1;
}else
{
	$dadosfgts='';
}

$dadoscontafgts='';



$db->query = "Select * from contasfgts where cod_usua='".$proponente."'";
$db->query();
if($db->qrcount>0)
{
	$dadoscontafgts=1;
	$c=0;
	while($c<=$db->qrcount)
	{
		$aAltPpnt["fgts"][$c+1]["nometrab_fgts"] = 		 	 $db->qrdata[$c]['NOMETRAB'];
		$aAltPpnt["fgts"][$c+1]["dtnasctrab_fgts"] = 		 $utils->formataDataBRA($db->qrdata[$c]['DTNASCTRAB']);
		$aAltPpnt["fgts"][$c+1]["pis_fgts"] = 		 		 $db->qrdata[$c]['NUMPISPASEP'];
		$aAltPpnt["fgts"][$c+1]["sitconta_fgts"] = 	 		 $db->qrdata[$c]['SITUACAOCONTA'];
		$aAltPpnt["fgts"][$c+1]["contaemp_fgts"] =     		 $db->qrdata[$c]['CODCONTAEMP'];
		$aAltPpnt["fgts"][$c+1]["contatrab_fgts"]=	 		 $db->qrdata[$c]['CODCONTATRAB'];
		$aAltPpnt["fgts"][$c+1]["valordeb_fgts"]=		 	 $db->qrdata[$c]['VALORDEBITADO'];
		$aAltPpnt["fgts"][$c+1]["baseconta_fgts"]=	 		 $db->qrdata[$c]['SUREG'];
		$c++;
				
	}
}


if($_POST)
{	
	$aAltPpnt["flgfgts_ppnt"]=						$_POST['flgfgts_ppnt'];
	$aAltPpnt["fgts"][0]["stimov_fgts"] = 			$_POST['stimov_fgts'];
	$aAltPpnt["fgts"][0]["municipio_fgts"] = 		$_POST['municipio_fgts'];
	$aAltPpnt["fgts"][0]["codmunicipio_fgts"] = 	$_POST['codmunicipio_fgts'];
	$aAltPpnt["fgts"][0]["estado_fgts"]=			$_POST['estado_fgts'];
	$aAltPpnt["fgts"][0]["qtcontas"]=				$_POST['qtcontas_fgts'];
	$aAltPpnt["fgts"][0]["teste_fgts"]= 			$_POST['teste_fgts'];
	$c=1;
	while($c<=$aAltPpnt["fgts"][0]["qtcontas"])
	{
		$aAltPpnt["fgts"][$c]["nometrab_fgts"]=		$_POST['nometrab_fgts'.$c];
		$aAltPpnt["fgts"][$c]["dtnasctrab_fgts"]=	$_POST['dtnasctrab_fgts'.$c];
		$aAltPpnt["fgts"][$c]["pis_fgts"]=			$_POST['pis_fgts'.$c];
		$aAltPpnt["fgts"][$c]["sitconta_fgts"]=		$_POST['sitconta_fgts'.$c];
		$aAltPpnt["fgts"][$c]["contaemp_fgts"]=		$_POST['contaemp_fgts'.$c];
		$aAltPpnt["fgts"][$c]["contatrab_fgts"]=	$_POST['contatrab_fgts'.$c];
		$aAltPpnt["fgts"][$c]["valordeb_fgts"]=		formataDecimal($_POST['valordeb_fgts'.$c]);
		$aAltPpnt["fgts"][$c]["baseconta_fgts"]=	$_POST['baseconta_fgts'.$c];
		$c++;
	}
	//echo '$_POST:'.$_POST['teste_fgts'];

}


if($acaoFgts=='salvar'){

	 $valoper=str_replace(".","",$utils->formataFloat($aAltPpnt["fgts"][0]["teste_fgts"],2));
	 $valoper=str_replace(",",".",$valoper);
	if($dadosfgts=='')
	{
	 $query = "INSERT INTO fgts (
						COD_USUA, 
						FLAGUTILIZACAO, 
						UFIBGE, 
						CODMUNIBGE, 
						NOMEMUNIBGE, 
						STATUSIMOV, 
						VALOPERACAO, 
						QTCONTAS,
						TIPOFGTS) 
					  VALUES (
						'".$proponente."',
						'".$aAltPpnt["flgfgts_ppnt"]."',
						'".$aAltPpnt["fgts"][0]["estado_fgts"]."',
						'".$aAltPpnt["fgts"][0]["codmunicipio_fgts"]."',
						'".$aAltPpnt["fgts"][0]["municipio_fgts"]."',
						'".$aAltPpnt["fgts"][0]["stimov_fgts"]."',
						'".$valoper."',
						'".$aAltPpnt["fgts"][0]["qtcontas"]."',
						'proponente')";
						echo $query;
		$resultado=mysql_query($query);//$db->query() or die ("ERRO AO INSERIR DADOS DE FGTS");
	}else
	{
		$db->query = "UPDATE fgts SET 
						FLAGUTILIZACAO='".$aAltPpnt["flgfgts_ppnt"]."', 
						UFIBGE='".$aAltPpnt["fgts"][0]["estado_fgts"]."', 
						CODMUNIBGE='".$aAltPpnt["fgts"][0]["codmunicipio_fgts"]."', 
						NOMEMUNIBGE='".$aAltPpnt["fgts"][0]["municipio_fgts"]."', 
						STATUSIMOV='".$aAltPpnt["fgts"][0]["stimov_fgts"]."', 
						VALOPERACAO='".$valoper."',
						QTCONTAS='".$aAltPpnt["fgts"][0]["qtcontas"]."'
					WHERE
							cod_usua= '".$proponente."' 
					and
							tipofgts='proponente'";
							echo "<br><br><br>".$db->query;
		$db->query();// or die ("ERRO AO ATUALIZAR DADOS DE FGTS");
	}
	
	if($dadoscontafgts=='')
	{
	  $c=0;
	  echo "QT:".$aAltPpnt["fgts"][0]["qtcontas"];
	  while($c<$aAltPpnt["fgts"][0]["qtcontas"])
	  {	  	
		$aAltPpnt["fgts"][$c+1]["pis_fgts"]  = str_replace('.','',$aAltPpnt["fgts"][$c+1]["pis_fgts"]);
		$aAltPpnt["fgts"][$c+1]["pis_fgts"]  = str_replace('-','',$aAltPpnt["fgts"][$c+1]["pis_fgts"]);
		$db->query = "INSERT INTO contasfgts (
						COD_USUA,
						NOMETRAB,
						DTNASCTRAB, 
						NUMPISPASEP, 
						SITUACAOCONTA, 
						CODCONTAEMP, 
						CODCONTATRAB, 
						VALORDEBITADO, 
						SUREG, 
						TIPOFGTS) 
					  VALUES (
						'".$proponente."',
						'".$aAltPpnt["fgts"][$c+1]["nometrab_fgts"]."',
						'".BDData($aAltPpnt["fgts"][$c+1]["dtnasctrab_fgts"])."',
						'".$aAltPpnt["fgts"][$c+1]["pis_fgts"]."',
						'".$aAltPpnt["fgts"][$c+1]["sitconta_fgts"]."',
						'".$aAltPpnt["fgts"][$c+1]["contaemp_fgts"]."',
						'".$aAltPpnt["fgts"][$c+1]["contatrab_fgts"]."',
						'".str_replace(",",".",$aAltPpnt["fgts"][$c+1]["valordeb_fgts"])."',
						'".$aAltPpnt["fgts"][$c+1]["baseconta_fgts"]."',
						'proponente')";
		
		echo $db->query;
		$db->query();// or die ("ERRO AO INSERIR DADOS DE CONTA DE FGTS");
	    $finan=$financiamento;
		$finan =$finan-$aAltPpnt["fgts"][$c+1]["valordeb_fgts"];
		?>
		<?php
		$db->query="update proponente set vlfinsol_ppnt='".$finan."' where cod_proponente='".$proponente."'";
		echo "<br><br><br>".$db->query;
		$db->query();
		$c++;
	  }
	}elseif($dadoscontafgts==1)
	{
		$db->query ="DELETE FROM contasfgts WHERE cod_usua='".$proponente."'";
		$db->query();
	  $c=0;
	  while($c<$aAltPpnt["fgts"][0]["qtcontas"])
	  {	  		
		$aAltPpnt["fgts"][$c+1]["pis_fgts"]  = str_replace('.','',$aAltPpnt["fgts"][$c+1]["pis_fgts"]);
		$aAltPpnt["fgts"][$c+1]["pis_fgts"]  = str_replace('-','',$aAltPpnt["fgts"][$c+1]["pis_fgts"]);
		$db->query = "INSERT INTO contasfgts (
						COD_USUA, 
						NOMETRAB,
						DTNASCTRAB, 
						NUMPISPASEP, 
						SITUACAOCONTA, 
						CODCONTAEMP, 
						CODCONTATRAB, 
						VALORDEBITADO, 
						SUREG, 
						TIPOFGTS) 
					  VALUES (
						'".$proponente."',
						'".$aAltPpnt["fgts"][$c+1]["nometrab_fgts"]."',
						'".BDData($aAltPpnt["fgts"][$c+1]["dtnasctrab_fgts"])."',
						'".$aAltPpnt["fgts"][$c+1]["pis_fgts"]."',
						'".$aAltPpnt["fgts"][$c+1]["sitconta_fgts"]."',
						'".$aAltPpnt["fgts"][$c+1]["contaemp_fgts"]."',
						'".$aAltPpnt["fgts"][$c+1]["contatrab_fgts"]."',
						'".str_replace(",",".",$aAltPpnt["fgts"][$c+1]["valordeb_fgts"])."',
						'".$aAltPpnt["fgts"][$c+1]["baseconta_fgts"]."',
						'proponente')";
						echo $db->query;
		echo "<br><br><br>".$db->query;
		$db->query() or die (mysql_error());
		$c++;
	  }
	}
}

?>
<!-- AQUI COMECA bl_form_proponente.inc.php -->
<script language="javascript">

function atualfgts(_cod,_acao){
	if(document.getElementById('qtcontas_fgts').value>0){
	document.getElementById('frm_cod_ppnt').value = _cod;
	document.getElementById('acaoProposta').value = _acao;
	document.getElementById('proposta').action = 'corrigir.php?corrigir=sim#fgts';
	document.getElementById('proposta').submit();
	}
}

function Arredonda( valor , casas ){
   return Math.round( valor * Math.pow( 10 , casas ) ) / Math.pow( 10 , casas );
}

function resValorOper(){
totalres=0;
<?php
	$c=0;
	  while($c<$aAltPpnt["fgts"][0]["qtcontas"])
	  {	  		
echo "totalres=desformataMoeda(document.proposta.valordeb_fgts" . ($c+1) . ".value)+totalres;\n";
	  $c++;
	  }

?>
totalres=Arredonda(totalres,2);
document.getElementById('resultado').innerHTML ="R$ "+formataMoeda(totalres);
return formataMoeda(totalres);
}


function salvarFgts(_acao)
{
erroFgts=0;
msgErro=new Array();
	if(document.proposta.flgfgts_ppnt[0].checked==true){

			if(!(document.proposta.stimov_fgts[0].checked) && !(document.proposta.stimov_fgts[1].checked)){
			msgErro[erroFgts]="Voc� deve selecionar o status do im�vel.";
			erroFgts++
			}
			if(document.getElementById('municipio_fgts').value==0){
			msgErro[erroFgts]="Voc� deve selecionar o municipio da conta fgts.";
			erroFgts++;
			}
			if(document.getElementById('qtcontas_fgts').value<=0){
			msgErro[erroFgts]="N�mero de contas deve ser maior que zero.";
			erroFgts++;
			}
			
			if(erroFgts>0){
			msgAlert="Ocorreu o(s) seguinte(s) erro(s) no cadastro dos Dados de FGTS";
				for(i=0;i<erroFgts;i++){
				msgAlert+='\n' + (i+1) + ' - ' + msgErro[i];
				}
				alert(msgAlert);
			}
			else{
			erroContas=0;
			msgErroContas=new Array();
			b=0;
				for(a=1;a<=<?=$aAltPpnt["fgts"][0]["qtcontas"];?>;a++){
					if(document.getElementById('nometrab_fgts' + a).value==''){
					msgErroContas[erroContas]="Trabalhador n�o preenchido.";
					erroContas++;
					break;
					}
				}	
				
				for(a=1;a<=<?=$aAltPpnt["fgts"][0]["qtcontas"];?>;a++){
					if(document.getElementById('pis_fgts' + a).value==''){
					msgErroContas[erroContas]="N� de PIS/PASEP n�o preenchido.";
					erroContas++;
					break;
					}
				}	
				
				for(a=1;a<=<?=$aAltPpnt["fgts"][0]["qtcontas"];?>;a++){
					if(document.getElementById('contaemp_fgts' + a).value==''){
					msgErroContas[erroContas]="Conta Empregador n�o preenchido.";
					erroContas++;
					break;
					}
				}	
				
				for(a=1;a<=<?=$aAltPpnt["fgts"][0]["qtcontas"];?>;a++){
					if(document.getElementById('contatrab_fgts' + a).value==''){
					msgErroContas[erroContas]="Conta Trabalhador n�o preenchido.";
					erroContas++;
					break;
					}
				}
				
				for(a=1;a<=<?=$aAltPpnt["fgts"][0]["qtcontas"];?>;a++){
					if(document.getElementById('baseconta_fgts' + a).value==''){
					msgErroContas[erroContas]="Base Conta FGTS n�o preenchido.";
					erroContas++;
					break;
					}
				}
				
				for(a=1;a<=<?=$aAltPpnt["fgts"][0]["qtcontas"];?>;a++){
					if(document.getElementById('sitconta_fgts' + a).value==0){
					msgErroContas[erroContas]="Situa��o da Conta n�o preenchido.";
					erroContas++;
					break;
					}
				}
				
				for(a=1;a<=<?=$aAltPpnt["fgts"][0]["qtcontas"];?>;a++){
					if(!vDataStr(document.getElementById('dtnasctrab_fgts' + a).value)){
					msgErroContas[erroContas]="Data de Nascimento inv�lida.";
					erroContas++;
					break;
					}
					
				}
				
			
				if(erroContas>0){
				msgAlert="Ocorreu o(s) seguinte(s) erro(s) em Conta(s) de FGTS";
				
					for(i=0;i<erroContas;i++){
					msgAlert+='\n' + (i+1) + ' - ' + msgErroContas[i];
					}
					alert(msgAlert);
				}
				else{
				valorOper=resValorOper();
					if(confirm("Valor da Opera��o R$ " + valorOper + ". Deseja confirmar este valor?")){
						document.getElementById('acaoFgts').value ="salvar";
						document.getElementById('proposta').action = 'corrigir.php?corrigir=sim#fgts';
						document.getElementById('proposta').submit();
						return true;
					}
				}
			}
			
		}
		else{
		document.getElementById('acaoFgts').value ="salvar";
		document.getElementById('proposta').action = 'corrigir.php?corrigir=sim#fgts';
		document.getElementById('proposta').submit();
		return true;
		}

}

function verifica_limite(){
	//alert(document.getElementById('vlfinsol_ppnt').value);
	//alert(document.getElementById('vlmax').value);
	if(document.getElementById('vlfinsol_ppnt').value > document.getElementById('vlmax').value)
	{
		if (confirm('Valor de Entrada Insuficiente. Confirma?')){;
		return true;}else{
    		document.getElementById('vlentrada_ppnt').value=''; 
			return false;
		}
	}
	
}
</script>
<input type="hidden" name="vlmax" id="vlmax" value="<?php echo $utils->formataMoeda($vlmax);?>">
 <table cellpadding=0 cellspacing=5 border=0 id="tbEditPpnt">
  <tr>
    <td align="right" valign="top">Nome:</td>
    <td align="left"  valign="top"><b><input type="text" name="nome_ppnt" id="nome_ppnt" value="<?php echo $aAltPpnt["usuario"][0]["nome_usua"];?>" size="50" /></b></td>
  </tr>
  <?php if(FLG_PREVI){ ?>
  <tr>
    <td align="right" valign="top">Matr�cula:</td>
    <td align="left"  valign="top"><b><?php echo $aAltPpnt["usuario"][0]["id_lstn"];?></b></td>
  </tr>
  <?php } ?>
  <tr>
    <td align="right" valign="top">E-Mail:</td>
    <td align="left"  valign="top"><b><input type="text" name="email_usua" id="email_usua" value="<?php echo $aAltPpnt["usuario"][0]["email_usua"];?>" size="35" /></b></td>
  </tr>
  <tr>
    <td align="right" valign="top">CPF:<?php $utils->obrig('cpf_ppnt'); ?></td>
    <td align="left"  valign="top"><input type="text" style="width:150px;" name="cpf_ppnt" id="cpf_ppnt" value="<?php echo $utils->formataCPF($aAltPpnt["cpf_ppnt"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraCPF(this,event);" maxlength="14" /></td>
  </tr>
  <tr>
	<td align="right" valign="top">RG:<?php $utils->obrig('nrrg_ppnt'); ?></td>
	<td align="left"  valign="top">
		<input type="text" style="width:150px;" name="nrrg_ppnt" id="nrrg_ppnt" value="<?php echo $aAltPpnt["nrrg_ppnt"];?>" onKeyDown="return teclasRG(this,event);" onKeyUp="return mascaraRG(this,event);" maxlength="13">&nbsp;
		Emiss�o:<?php $utils->obrig('dtrg_ppnt'); ?>
		<input type="text" style="width:80px;" name="dtrg_ppnt" id="dtrg_ppnt" value="<?php echo $utils->formataDataBRA($aAltPpnt["dtrg_ppnt"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10">&nbsp;
		�rg�o Emissor:<?php $utils->obrig('orgrg_ppnt'); ?>
		<input type="text" style="width:80px;" name="orgrg_ppnt" id="orgrg_ppnt" value="<?php echo $aAltPpnt["orgrg_ppnt"];?>" maxlength="10">
	</td>
 </tr>
 <?php if(!FLG_PREVI){ ?>
	  <tr>
	    <td align="right" valign="top">Composi��o de Renda (%):<?php $utils->obrig('compos_renda_ppnt'); ?></td>
	    <td align="left"  valign="top"><input type="text" style="width:80px;" name="compos_renda_ppnt" id="compos_renda_ppnt" value="<?php echo $utils->formataFloat($aAltPpnt["compos_renda_ppnt"],2);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraMoeda(this,event,null,2);" maxlength="6" /></td>
	  </tr>
	<?php }else{ ?>
	  <tr>
	    <td align="right" valign="top">Parcela individual da Compra (R$):<?php $utils->obrig('vlcompra_ppnt'); ?></td>
	    <td align="left"  valign="top"><input type="text" style="width:80px;" name="vlcompra_ppnt" id="vlcompra_ppnt" value="<?php echo $utils->formataMoeda($aAltPpnt["vlcompra_ppnt"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraMoeda(this,event,'atualizaValoresProposta()',2);" maxlength="12" /></td>
	  </tr>
	  <tr>
	    <td align="right" valign="top">Entrada individual (R$):<?php $utils->obrig('vlentrada_ppnt'); ?></td>
	    <td align="left"  valign="top"><input type="text" style="width:80px;" name="vlentrada_ppnt" id="vlentrada_ppnt" value="<?php echo $utils->formataMoeda($aAltPpnt["vlentrada_ppnt"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraMoeda(this,event,'atualizaValoresProposta()',2);" maxlength="12" onBlur="verifica_limite();" /></td>
	  </tr>
	  <tr>
	    <td align="right" valign="top">Sinal individual (R$):<?php $utils->obrig('vlsinal_ppnt'); ?></td>
	    <td align="left"  valign="top"><input type="text" style="width:80px;" name="vlsinal_ppnt" id="vlsinal_ppnt" value="<?php echo $utils->formataMoeda($aAltPpnt["vlsinal_ppnt"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraMoeda(this,event,'atualizaValoresProposta()',2);" maxlength="12" /></td>
	  </tr>
	  <tr>
	    <td align="right" valign="top">Valor individual do Financiamento:</td>
	    <td align="left"  valign="top"><input type="text" name="vlfinsol_ppnt" id="vlfinsol_ppnt" value="<?php echo $utils->formataFloat($aAltPpnt["vlfinsol_ppnt"],2);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraMoeda(this,event,'atualizaValoresProposta()',2);" maxlength="12" />	    	
	    </td>
	  </tr>
	  <tr>
	    <td align="right" valign="top">Presta��o (R$):</td>
	    <td align="left"  valign="top">
      	<input type="radio" class="rd" name="sel_tipo_ppnt_finan" id="sel_tipo_ppnt_finan" value="1" onclick="selecionaPpntTipoFinan();" <?php echo (($aAltPpnt["vlprestsol_ppnt"])?'checked':'');?> />
      	<span id="spnPpntParcela" <?php echo (($aAltPpnt["vlprestsol_ppnt"])?'':'style="display:none"');?>>
      		<?php $utils->obrig('vlprestsol_ppnt'); ?>
      		<input type="text" name="vlprestsol_ppnt" id="vlprestsol_ppnt" style="width:80px;" value="<?php echo $utils->formataMoeda($aAltPpnt["vlprestsol_ppnt"]);?>" maxlength="12" onKeyDown="return teclasFloat(this,event);" onKeyUp="return mascaraMoeda(this,event);" onFocus="this.select();" />
        </span>
      </td>
	  </tr>
	  <tr>
	    <td align="right" valign="top">Prazo (Meses):</td>
	    <td align="left"  valign="top">
      	<input type="radio" class="rd" name="sel_tipo_ppnt_finan" id="sel_tipo_ppnt_finan" value="2" onclick="selecionaPpntTipoFinan();" <?php echo (($aAltPpnt["przfinsol_ppnt"])?'checked':'');?> />
      	<span id="spnPpntPrazo" <?php echo (($aAltPpnt["vlprestsol_ppnt"])?'style="display:none"':'');?>>
      		<?php $utils->obrig('przfinsol_ppnt'); ?>
      		<input type="text" name="przfinsol_ppnt" id="przfinsol_ppnt" style="width:40px;" value="<?php echo $utils->formataFloat($aAltPpnt["przfinsol_ppnt"]);?>" maxlength="3" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraInt(this,event);" onFocus="this.select();" />
        </span>
	    </td>
	  </tr>
	<?php } ?>
  
  <tr>
    <td align="right" valign="top">Data de Nascimento:<?php $utils->obrig('dtnascimento_ppnt'); ?></td>
    <td align="left"  valign="top"><input type="text" style="width:80px;" name="dtnascimento_ppnt" id="dtnascimento_ppnt" value="<?php echo $utils->formataDataBRA($aAltPpnt["dtnascimento_ppnt"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10"></td>
  </tr>
    <tr>
      <td align="right" valign="top">Sexo:<?php $utils->obrig('sexo_ppnt'); ?></td>
      <td align="left"  valign="top">
			  <?php
			  	foreach ($listas->getListaSexo() as $k=>$v){
          	$checked = ($aAltPpnt["sexo_ppnt"]==$k)?'checked':'';
          	print '<input type="radio" class="rd" name="sexo_ppnt" id="sexo_ppnt" value="'.$k.'" '.$checked.' /> '.$v.' &nbsp;&nbsp;';
			  	}
			  ?>
			</td>
    </tr>
    <tr>
      <td align="right" valign="top">Nacionalidade:<?php $utils->obrig('nacional_ppnt'); ?></td>
      <td align="left"  valign="top">
				<select name="nacional_ppnt" id="nacional_ppnt">
					<option value="0" >-Selecione-</option>
					<?php
          	foreach($listas->getListaPais() as $k=>$v){
      		  	$selected = ($aAltPpnt["nacional_ppnt"]==$v['cod_pais'])?'selected':'';
       		    print '<option value="'.$v['cod_pais'].'" '.$selected.'>'.$v['nome_pais'].'</option>';
          	}
          ?>
        </select>
      </td>
    </tr>
  <tr>
    <td align="right" valign="top">Estado Civil:<?php $utils->obrig('cod_estciv_ppnt'); ?></td>
    <td align="left"  valign="top">
      <select name="cod_estciv_ppnt" id="cod_estciv_ppnt" onchange="trocouEstadoCivilProp(this);">
        <option value="0" >-Selecione-</option>
        <?php
        	$display_dados_estciv = 'display:none;';
        	foreach($listas->getListaECivil() as $k=>$v){
    		  	$selected = ($aAltPpnt["cod_estciv"]==$v['cod_estciv'])?'selected':'';
     		    print '<option value="'.$v['cod_estciv'].'" '.$selected.'>'.$v['desc_estciv'].'</option>';
     		    $display_dados_estciv   = ($aAltPpnt["cod_estciv"]==2)?'':'display:none;';
     		    $display_dados_estciv_2 = ($aAltPpnt["cod_estciv"]==2)?'':'display:none;';
				$display_dados_estciv_3 = ($aAltPpnt["cod_estciv"]!=2 && $aAltPpnt["cod_estciv"]!='')?'':'display:none;';
        	}
        ?>
      </select>
    </td>
  </tr>
  <tr id="tr_casam_dt" style="<?php echo $display_dados_estciv;?>">
    <td align="right" valign="top">Data do Casamento:<?php $utils->obrig('dtcasamento_ppcj'); ?></td>
    <td align="left"  valign="top"><input type="text" style="width:80px;" name="dtcasamento_ppcj" id="dtcasamento_ppcj" value="<?php echo $utils->formataDataBRA($aAltPpnt["conjuge"][0]["dtcasamento_ppcj"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10"></td>
  </tr>
  <tr id="tr_regime_bens" style="<?php echo $display_dados_estciv_2;?>">
    <td align="right" valign="top">Regime de Bens:<?php $utils->obrig('regimebens_ppcj'); ?></td>
    <td align="left"  valign="top">
      <select name="regimebens_ppcj" id="regimebens_ppcj" onchange="trocouRegimeDeBensProp(this);">
        <option value="0" >-Selecione-</option>
        <?php
        	$display_dets_regime_bens = 'display:none;';
        	foreach($listas->getListaRegimeBens() as $k=>$v){
    		  	$selected = ($aAltPpnt["conjuge"][0]["regimebens_ppcj"]==$k)?'selected':'';
     		    print '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
     		    $display_dets_regime_bens = ($aAltPpnt["conjuge"][0]["regimebens_ppcj"]==1 || $aAltPpnt["conjuge"][0]["regimebens_ppcj"]==3 || $aAltPpnt["conjuge"][0]["regimebens_ppcj"]==5)?'':'display:none;';
     		    $display_dets_regime_bens = ($aAltPpnt["cod_estciv"]==2)?$display_dets_regime_bens:'display:none;';
        	}
        ?>
      </select>
    </td>
  </tr>
  <tr id="tr_uniao_estavel" style="<?php echo $display_dados_estciv_3;?>">
    <td align="right" valign="top">Vive em Uni�o Est�vel:<?php $utils->obrig('flgescritura_ppnt'); ?></td>
    <td align="left"  valign="top">		<?php
				foreach ($listas->getListaSN() as $k=>$v){
          	$checked = ($aAltPpnt["flguniest_ppnt"]==$k)?'checked':'';
			print '<input type="radio" class="rd" name="flguniest_ppnt" id="flguniest_ppnt" value="'.$k.'" '.$checked.' onclick="trocouUniaoEstavel(this);" /> '.$v.' &nbsp;&nbsp;';
     		    $display_dets_escritura = ($aAltPpnt["flguniest_ppnt"]=='S')?'':'display:none;';
				}
			  ?>
</td>
  </tr>
    </tr>
  <tr id="tr_escritura" style="<?php echo $display_dets_escritura;?>">
    <td align="right" valign="top">Possui Escritura:<?php $utils->obrig('flguniest_ppnt'); ?></td>
    <td align="left"  valign="top">		<?php
				foreach ($listas->getListaSN() as $k=>$v){
          	$checked = ($aAltPpnt["flgescritura_ppnt"]==$k)?'checked':'';
			print '<input type="radio" class="rd" name="flgescritura_ppnt" id="flgescritura_ppnt" value="'.$k.'" '.$checked.' onclick="trocouEscritura(this);" /> '.$v.' &nbsp;&nbsp;';
     		    $display_dets_regime_bens = ($aAltPpnt["conjuge"][0]["regimebens_ppcj"]==1 || $aAltPpnt["conjuge"][0]["regimebens_ppcj"]==3 || $aAltPpnt["conjuge"][0]["regimebens_ppcj"]==5 || $aAltPpnt["flgescritura_ppnt"]=='S')?'':'display:none;';
				}
			  ?>
</td>
  </tr>
	<tr id="tr_regime_bens_data" style="<?php echo $display_dets_regime_bens;?>">
		<td align="right" valign="top">Data:<?php $utils->obrig('data_pcpa'); ?></td>
		<td align="left"  valign="top"><input type="text" style="width:80px;" name="data_pcpa" id="data_pcpa" value="<?php echo $utils->formataDataBRA($aAltPpnt["conjugepacto"][0]["data_pcpa"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10"></td>
	</tr>
	<tr id="tr_regime_bens_lavrado" style="<?php echo $display_dets_regime_bens;?>">
		<td align="right" valign="top">Lavrado no:<?php $utils->obrig('locallavracao_pcpa'); ?></td>
		<td align="left"  valign="top"><input type="text" style="width:300px;" name="locallavracao_pcpa" id="locallavracao_pcpa" value="<?php echo $aAltPpnt["conjugepacto"][0]["locallavracao_pcpa"];?>" maxlength="70"></td>
	</tr>
	<tr id="tr_regime_bens_livro" style="<?php echo $display_dets_regime_bens;?>">
		<td align="right" valign="top">Livro:<?php $utils->obrig('livro_pcpa'); ?></td>
		<td align="left"  valign="top"><input type="text" style="width:300px;" name="livro_pcpa" id="livro_pcpa" value="<?php echo $aAltPpnt["conjugepacto"][0]["livro_pcpa"];?>" maxlength="70"></td>
	</tr>
	<tr id="tr_regime_bens_fls" style="<?php echo $display_dets_regime_bens;?>">
		<td align="right" valign="top">Fls.:<?php $utils->obrig('folha_pcpa'); ?></td>
		<td align="left"  valign="top"><input type="text" style="width:300px;" name="folha_pcpa" id="folha_pcpa" value="<?php echo $aAltPpnt["conjugepacto"][0]["folha_pcpa"];?>" maxlength="70"></td>
	</tr>
	<tr id="tr_regime_bens_nreg" style="<?php echo $display_dets_regime_bens;?>">
		<td align="right" valign="top">N�mero do Registro:<?php $utils->obrig('numeroregistro_pcpa'); ?></td>
		<td align="left"  valign="top"><input type="text" style="width:300px;" name="numeroregistro_pcpa" id="numeroregistro_pcpa" value="<?php echo $aAltPpnt["conjugepacto"][0]["numeroregistro_pcpa"];?>" maxlength="70"></td>
	</tr>
	<tr id="tr_regime_bens_flgbens" style="<?php echo $display_dets_regime_bens;?>">
		<td align="right" valign="top">H� Bens:<?php $utils->obrig('habens_pcpa'); ?></td>
		<td align="left"  valign="top">			  
		<?php
			  	$display_dets_flgbens = ($aAltPpnt["conjugepacto"][0]["habens_pcpa"]=='S' && $aAltPpnt["cod_estciv"]==2)?'':'display:none;';
			  	foreach ($listas->getListaSN() as $k=>$v){
          	$checked = ($aAltPpnt["conjugepacto"][0]["habens_pcpa"]==$k)?'checked':'';
			print '<input type="radio" class="rd" name="habens_pcpa" id="habens_pcpa" value="'.$k.'" '.$checked.' onclick="trocouHaBens(this);" /> '.$v.' &nbsp;&nbsp;';
			  	}
			  ?>
		</td>
	</tr>
	<tr id="tb_dets_habens_cart" style="<?php echo $display_dets_flgbens;?>">
		<td align="right" valign="top">Cartorio:<?php $utils->obrig('habenscart_pcpa'); ?></td>
		<td align="left"  valign="top"><input type="text" style="width:15px;" name="habenscart_pcpa" id="habenscart_pcpa" value="<?php echo $aAltPpnt["conjugepacto"][0]["habenscart_pcpa"];?>" maxlength="3">� Cart�rio de Registro de Im�veis de <?php $utils->obrig('habensloccart_pcpa'); ?><input type="text" style="width:150px;" name="habensloccart_pcpa" id="habensloccart_pcpa" value="<?php echo $aAltPpnt["conjugepacto"][0]["habensloccart_pcpa"];?>" maxlength="60"> </td>
	</tr>
	<tr id="tb_dets_habens_data" style="<?php echo $display_dets_flgbens;?>">
		<td align="right" valign="top">Data de Registro:<?php $utils->obrig('habensdata_pcpa'); ?></td>
		<td align="left"  valign="top"><input type="text" style="width:80px;" name="habensdata_pcpa" id="habensdata_pcpa" value="<?php echo $utils->formataDataBRA($aAltPpnt["conjugepacto"][0]["habensdata_pcpa"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10"></td>
	</tr>
  <tr>
    <td align="right" valign="top">Tipo Logradouro:<?php $utils->obrig('cod_logr_ppnt'); ?></td>
    <td align="left"  valign="top">
      <select name="cod_logr_ppnt" id="cod_logr_ppnt">
        <option value="0" >-Selecione-</option>
        <?php
        	foreach($listas->getListaLogradouro() as $k=>$v){
    		  	$selected = ($aAltPpnt["cod_logr"]==$v['cod_logr'])?'selected':'';
     		    print '<option value="'.$v['cod_logr'].'" '.$selected.'>'.$v['desc_logr'].'</option>';
        	}
        ?>
      </select>
    </td>
  </tr>
  <tr>
    <td align="right" valign="top">Endere�o:<?php $utils->obrig('endereco_ppnt'); ?></td>
    <td align="left"  valign="top"><input type="text" style="width:350px;" name="endereco_ppnt" id="endereco_ppnt" value="<?php echo $aAltPpnt["endereco_ppnt"];?>" maxlength="100"></td>
  </tr>
  <tr>
    <td align="right" valign="top">N�mero:<?php $utils->obrig('nrendereco_ppnt'); ?></td>
    <td align="left"  valign="top"><input type="text" style="width:40px;" name="nrendereco_ppnt" id="nrendereco_ppnt" value="<?php echo $aAltPpnt["nrendereco_ppnt"];?>" maxlength="6" ></td>
  </tr>
  <tr>
    <td align="right" valign="top">Complemento:</td>
    <td align="left"  valign="top"><input type="text" style="width:150px;" name="cpendereco_ppnt" id="cpendereco_ppnt" value="<?php echo $aAltPpnt["cpendereco_ppnt"];?>" maxlength="60"></td>
  </tr>
  <tr>
    <td align="right" valign="top">Estado:<?php $utils->obrig('cod_uf_ppnt'); ?></td>
    <td align="left"  valign="top">
      <select name="cod_uf_ppnt" id="cod_uf_ppnt" onChange="getListaMunicipios_v2(this,'cod_municipio_ppnt');">
        <option value="0" >-Selecione-</option>
        <?php
        	foreach($listas->getListaUF() as $k=>$v){
    		  	$selected = ($aAltPpnt["cod_uf"]==$v['cod_uf'])?'selected':'';
     		    print '<option value="'.$v['cod_uf'].'" '.$selected.'>'.$v['nome_uf'].'</option>';
        	}
        ?>
      </select>
      &nbsp;Cidade:<?php $utils->obrig('cod_municipio_ppnt'); ?>
      <select name="cod_municipio_ppnt" id="cod_municipio_ppnt">
      	<option value="0" >-Selecione-</option>
      	<?php
      		if($aAltPpnt["cod_uf"]){
          	foreach($listas->getListaMunicipio($aAltPpnt["cod_uf"]) as $k=>$v){
      		  	$selected = ($aAltPpnt["cod_municipio"]==$v['cod_municipio'])?'selected':'';
       		    print '<option value="'.$v['cod_municipio'].'" '.$selected.'>'.$v['nome_municipio'].'</option>';
          	}
      		}
      	?>
      </select>
    </td>
  </tr>
  <tr>
    <td align="right" valign="top">Bairro:<?php $utils->obrig('cod_bairro_ppnt'); ?></td>
    <td align="left"  valign="top">
      <select name="cod_bairro_ppnt" id="cod_bairro_ppnt">
        <option value="0" >-Selecione-</option>
        <?php
        	foreach($listas->getListaBairro() as $k=>$v){
    		  	$selected = ($aAltPpnt["cod_bairro"]==$v['cod_bairro'])?'selected':'';
     		    print '<option value="'.$v['cod_bairro'].'" '.$selected.'>'.$v['nome_bairro'].'</option>';
        	}
        ?>
      </select>
    </td>
  </tr>
  <tr>
    <td align="right" valign="top">CEP:<?php $utils->obrig('cep_ppnt'); ?></td>
    <td align="left"  valign="top"><input type="text" style="width:150px;" name="cep_ppnt" id="cep_ppnt" value="<?php echo $utils->formataCep($aAltPpnt["cep_ppnt"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraCEP(this,event);" maxlength="9"></td>
  </tr>
  <tr>
    <td align="right" valign="top">Telefones:</td>
    <td align="left"  valign="top">
      <?php for($itels=1; $itels<=3;$itels++){
        $aTelefone = $aAltPpnt["telefones"][$itels-1];
        ?>
        <input type="text" style="width:100px;" name="telefone_ppnt_<?php echo $itels;?>" id="telefone_ppnt_<?php echo $itels;?>" value="<?php echo $utils->formataTelefone($aTelefone["TELEFONE_PPTL"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraTEL(this,event);" maxlength="13">
        <select name="tipotelefone_ppnt_<?php echo $itels;?>" id="tipotelefone_ppnt_<?php echo $itels;?>">
          <option value="0" >-Selecione-</option>
          <?php
          	foreach($listas->getListaTipoTelefone() as $k=>$v){
      		  	$selected = ($aTelefone["TIPO_PPTL"]==$k)?'selected':'';
       		    print '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
          	}
          ?>
        </select>
        <?php if($itels==1){ echo $utils->obrig('telefone_ppnt_1'); } ?>
        <br />
      <?php } ?>
    </td>
  </tr>
  <tr>
    <td align="right" valign="top">E-Mail:<?php $utils->obrig('email_ppnt'); ?></td>
    <td align="left"  valign="top"><input type="text" style="width:300px;" name="email_ppnt" id="email_ppnt" value="<?php echo $aAltPpnt["email_ppnt"];?>" maxlength="255"></td>
  </tr>
  <tr>
    <td align="right" valign="top">Profiss�o:<?php $utils->obrig('profissao_ppnt'); ?></td>
    <td align="left"  valign="top"><input type="text" style="width:150px;" name="profissao_ppnt" id="profissao_ppnt" value="<?php echo $aAltPpnt["profissao_ppnt"];?>" maxlength="255"></td>
  </tr>
	<tr align="right" valign="top">
		<td align="right" valign="top">Procurador:<?php $utils->obrig('flgproc_ppnt'); ?></td>
		<td align="left"  valign="top">			  
		<?php
			  	$display_dets_flgproc = ($aAltPpnt["flgproc_ppnt"]=='S')?'':'display:none;';
				
			  	foreach ($listas->getListaSN() as $k=>$v){
          	$checked = ($aAltPpnt["flgproc_ppnt"]==$k)?'checked':'';
			print '<input type="radio" class="rd" name="flgproc_ppnt" id="flgproc_ppnt" value="'.$k.'" '.$checked.' onclick="trocouProcurador(this);" /> '.$v.' &nbsp;&nbsp;';
			  	}
			  ?>
		</td>
	</tr>
	<tr id="tb_dets_proc" style="<?php echo $display_dets_flgproc;?>">
		<td align="right" valign="top">Dados do Procurador:<?php $utils->obrig('proc_ppnt'); ?></td>
		<td align="left"  valign="top"><textarea cols="90" rows="7" name="proc_ppnt" id="proc_ppnt"><?php if($aAltPpnt["proc_ppnt"]==''){echo "Sr(a). (nome), (nacionalidade), (estado civil), (profiss�o), portador da c�dula de identidade RG n� (n�mero/�rg�o emissor) e inscrito no CPF/MF sob n� (n�mero), residente e domiciliado na (rua), (n�), (complemento), (cidade/UF), nos termos da procura��o lavrada no  ___�. Tabelionato de Notas de (cidade), �s fls. (n�) do livro (n�), datada de (DD/MM/AAAA), cujo traslado � parte integrante do presente contrato.";}else{echo $aAltPpnt["proc_ppnt"];}?></textarea></td>
	</tr>
</table>

<?php if($aAltPpnt["cpf_ppnt"] != ""){ if($cLOGIN->iLEVEL_USUA!=TPUSER_PROPONENTE){
echo qd_exigencia('prop_exigencia','prop_BtExig','prop_addexig', 'prop_btsalvarexigencia',$_POST['prop_addexig'],$aProposta["cod_ppst"],'fimexigprop');}
?><div id="ckls_ppnt"><?php include('bl_ckls_proponente.inc.php'); ?></div><?php } ?>
<?php /*
<div id="div_empresa" class="grupoDados">
	<b>Dados Profissionais</b>
	<table cellpadding=0 cellspacing=5 border=0 style="margin-top:5px;">
		<tr>
			<td align="right" valign="top">Empresa:<?php $utils->obrig('empresa_ppnt'); ?></td>
			<td align="left"  valign="top"><input type="text" style="width:120px;" name="empresa_ppnt" id="empresa_ppnt" value="<?php echo $aAltPpnt["profissao"][0]["empresa_pppf"];?>" maxlength="80"></td>
		</tr>
		<tr>
			<td align="right" valign="top">Data de Admiss�o:<?php $utils->obrig('dtadmissaoemp_ppnt'); ?></td>
			<td align="left"  valign="top"><input type="text" style="width:80px;" name="dtadmissaoemp_ppnt" id="dtadmissaoemp_ppnt" value="<?php echo $utils->formataDataBRA($aAltPpnt["profissao"][0]["dtadmissao_pppf"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10"></td>
		</tr>
	  <tr>
	    <td align="right" valign="top">Endere�o:<?php $utils->obrig('enderecoemp_ppnt'); ?></td>
	    <td align="left"  valign="top"><input type="text" style="width:350px;" name="enderecoemp_ppnt" id="enderecoemp_ppnt" value="<?php echo $aAltPpnt["profissao"][0]["enderecoemp_pppf"];?>" maxlength="100"></td>
	  </tr>
	  <tr>
	    <td align="right" valign="top">N�mero:<?php $utils->obrig('nrenderecoemp_ppnt'); ?></td>
	    <td align="left"  valign="top"><input type="text" style="width:40px;" name="nrenderecoemp_ppnt" id="nrenderecoemp_ppnt" value="<?php echo $aAltPpnt["profissao"][0]["numeroemp_pppf"];?>" maxlength="6"></td>
	  </tr>
	  <tr>
	    <td align="right" valign="top">Complemento:</td>
	    <td align="left"  valign="top"><input type="text" style="width:150px;" name="cpenderecoemp_ppnt" id="cpenderecoemp_ppnt" value="<?php echo $aAltPpnt["profissao"][0]["complementoemp_pppf"];?>" maxlength="60"></td>
	  </tr>
	  <tr>
	    <td align="right" valign="top">Estado:<?php $utils->obrig('estadoemp_ppnt'); ?></td>
	    <td align="left"  valign="top">
	      <select name="estadoemp_ppnt" id="estadoemp_ppnt" onChange="getListaMunicipios_v2(this,'cidadeemp_ppnt');">
	        <option value="0" >-Selecione-</option>
	        <?php
	        	foreach($listas->getListaUF() as $k=>$v){
	    		  	$selected = ($aAltPpnt["profissao"][0]["estado_pppf"]==$v['cod_uf'])?'selected':'';
	     		    print '<option value="'.$v['cod_uf'].'" '.$selected.'>'.$v['nome_uf'].'</option>';
	        	}
	        ?>
	      </select>
	      &nbsp;Cidade:<?php $utils->obrig('cidadeemp_ppnt'); ?>
	      <select name="cidadeemp_ppnt" id="cidadeemp_ppnt">
	      	<option value="0" >-Selecione-</option>
	      	<?php
	      		if($aAltPpnt["profissao"][0]["estado_pppf"]){
	          	foreach($listas->getListaMunicipio($aAltPpnt["cod_uf"]) as $k=>$v){
	      		  	$selected = ($aAltPpnt["profissao"][0]["cidade_pppf"]==$v['cod_municipio'])?'selected':'';
	       		    print '<option value="'.$v['cod_municipio'].'" '.$selected.'>'.$v['nome_municipio'].'</option>';
	          	}
	      		}
	      	?>
	      </select>
	    </td>
	  </tr>
	  <tr>
	    <td align="right" valign="top">Bairro:<?php $utils->obrig('bairroemp_ppnt'); ?></td>
	    <td align="left"  valign="top">
	      <select name="bairroemp_ppnt" id="bairroemp_ppnt">
	        <option value="0" >-Selecione-</option>
	        <?php
	        	foreach($listas->getListaBairro() as $k=>$v){
	    		  	$selected = ($aAltPpnt["profissao"][0]["bairro_pppf"]==$v['cod_bairro'])?'selected':'';
	     		    print '<option value="'.$v['cod_bairro'].'" '.$selected.'>'.$v['nome_bairro'].'</option>';
	        	}
	        ?>
	      </select>
	    </td>
	  </tr>
		<tr>
			<td align="right" valign="top">Telefone:<?php $utils->obrig('telefoneemp_ppnt'); ?></td>
			<td align="left"  valign="top"><input type="text" style="width:100px;" name="telefoneemp_ppnt" id="telefoneemp_ppnt" value="<?php echo $utils->formataTelefone($aAltPpnt["profissao"][0]["telefone_pppf"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraTEL(this,event);" maxlength="13"></td>
		</tr>
		<tr>
			<td align="right" valign="top">Cargo:<?php $utils->obrig('cargoemp_ppnt'); ?></td>
			<td align="left"  valign="top"><input type="text" style="width:80px;" name="cargoemp_ppnt" id="cargoemp_ppnt" value="<?php echo $aAltPpnt["profissao"][0]["cargo_pppf"];?>" maxlength="60"></td>
		</tr>
		<tr>
			<td align="right" valign="top">Sal�rio (R$):<?php $utils->obrig('salarioemp_ppnt'); ?></td>
			<td align="left"  valign="top"><input type="text" name="salarioemp_ppnt" id="salarioemp_ppnt" style="width:80px;" value="<?php echo $utils->formataMoeda($aAltPpnt["profissao"][0]["salario_pppf"]);?>" maxlength="20" onKeyDown="return teclasFloat(this,event);" onKeyUp="return mascaraMoeda(this,event);" onFocus="this.select();" /></td>
		</tr>
	</table>
</div>*/
$var="display:none;";?>
<div id="div_conjuje" class="grupoDados" style="<?php if($aAltPpnt['cod_estciv']==2 || $aAltPpnt['flguniest_ppnt']=='S' ) {echo '';} else {echo $var;}?>">
	<b>Dados do C�njuge</b>
	<table cellpadding=0 cellspacing=5 border=0 style="margin-top:5px;">
		<tr>
			<td align="right" valign="top">Nome:<?php $utils->obrig('nome_ppcj'); ?></td>
			<td align="left"  valign="top"><input type="text" style="width:300px;" name="nome_ppcj" id="nome_ppcj" value="<?php echo $aAltPpnt["conjuge"][0]["nome_ppcj"];?>" maxlength="70"></td>
		</tr>
		<tr>
			<td align="right" valign="top">Nacionalidade:<?php $utils->obrig('prop_conjuge_nacionalidade'); ?></td>
			<td align="left"  valign="top">
				<select name="prop_conjuge_nacionalidade" id="prop_conjuge_nacionalidade">
					<option value="0" >-Selecione-</option>
					<?php
          	foreach($listas->getListaPais() as $k=>$v){
      		  	$selected = ($aAltPpnt["conjuge"][0]["cod_pais"]==$v['cod_pais'])?'selected':'';
       		    print '<option value="'.$v['cod_pais'].'" '.$selected.'>'.$v['nome_pais'].'</option>';
          	}
          ?>
        </select>
			</td>
		</tr>
	  <tr>
		<td align="right" valign="top">Estado Civil:<?php $utils->obrig('cod_estciv_ppnt'); ?></td>
		<td align="left"  valign="top">
		  <select name="prop_conjuge_estciv" id="prop_conjuge_estciv" onchange="trocouEstadoCivilProp(this);">
			<option value="0" >-Selecione-</option>
			<?php
				foreach($listas->getListaECivil() as $k=>$v){
					$selected = ($aAltPpnt["conjuge"][0]["cod_estciv"]==$v['cod_estciv'])?'selected':'';
					print '<option value="'.$v['cod_estciv'].'" '.$selected.'>'.$v['desc_estciv'].'</option>';
				}
			?>
		  </select>
		</td>
	  </tr>
		<tr>
			<td align="right" valign="top">RG:<?php $utils->obrig('nrrg_ppcj'); ?></td>
			<td align="left"  valign="top">
				<input type="text" style="width:150px;" name="nrrg_ppcj" id="nrrg_ppcj" value="<?php echo $aAltPpnt["conjuge"][0]["nrrg_ppcj"];?>" onKeyDown="return teclasRG(this,event);" onKeyUp="return mascaraRG(this,event);" maxlength="13">&nbsp;
				Emiss�o:<?php $utils->obrig('dtrg_ppcj'); ?>
				<input type="text" style="width:80px;" name="dtrg_ppcj" id="dtrg_ppcj" value="<?php echo $utils->formataDataBRA($aAltPpnt["conjuge"][0]["dtrg_ppcj"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10">&nbsp;
				�rg�o Emissor:<?php $utils->obrig('orgrg_ppcj'); ?>
				<input type="text" style="width:80px;" name="orgrg_ppcj" id="orgrg_ppcj" value="<?php echo $aAltPpnt["conjuge"][0]["orgrg_ppcj"];?>" maxlength="10">
			</td>
		</tr>
		<tr>
			<td align="right" valign="top">CPF:<?php $utils->obrig('cpf_pccj'); ?></td>
			<td align="left"  valign="top"><input type="text" style="width:150px;" name="cpf_pccj" id="cpf_pccj" value="<?php echo $utils->formataCPF($aAltPpnt["conjuge"][0]["cpf_pccj"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraCPF(this,event);" maxlength="14" /></td>
		</tr>
		<tr>
			<td align="right" valign="top">Trabalha atualmente:<?php $utils->obrig('flgtrabalha_ppcj'); ?></td>
			<td align="left"  valign="top">
			  <?php
			  if($aAltPpnt["conjuge"][0]["flgtrabalha_ppcj"]=='S'){
			  	$display_dets_trab_conj = '';}else{$display_dets_trab_conj='display:none;';}
				foreach ($listas->getListaSN() as $k=>$v){
          	$checked = ($aAltPpnt["conjuge"][0]["flgtrabalha_ppcj"]==$k)?'checked':'';
          	print '<input type="radio" class="rd" name="flgtrabalha_ppcj" id="flgtrabalha_ppcj" value="'.$k.'" '.$checked.' onclick="trocouTrabConj(this);" /> '.$v.' &nbsp;&nbsp;';
			  	}
			  ?>
			</td>
		</tr>
		
		<tr id="tr_conj_trab_titulo" style="<?php echo $display_dets_trab_conj;?>">
			<td align="left" valign="top" colspan="2" style="padding-top:10px;"><b>Dados Profissionais do C�njuge</b></td>
		</tr><?php /*
		<tr id="tr_conj_trab_empresa" style="<?php echo $display_dets_trab_conj;?>">
			<td align="right" valign="top">Empresa:<?php $utils->obrig('empresa_ppcj'); ?></td>
			<td align="left"  valign="top"><input type="text" style="width:80px;" name="empresa_ppcj" id="empresa_ppcj" value="<?php echo $aAltPpnt["conjuge"][0]["empresa_ppcj"];?>" maxlength="80"></td>
		</tr>
		<tr id="tr_conj_trab_admissao" style="<?php echo $display_dets_trab_conj;?>">
			<td align="right" valign="top">Data de Admiss�o:<?php $utils->obrig('dtadmissaoemp_ppcj'); ?></td>
			<td align="left"  valign="top"><input type="text" style="width:80px;" name="dtadmissaoemp_ppcj" id="dtadmissaoemp_ppcj" value="<?php echo $utils->formataDataBRA($aAltPpnt["conjuge"][0]["dtadmissaoemp_ppcj"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10"></td>
		</tr>
    <tr id="tr_conj_trab_endereco" style="<?php echo $display_dets_trab_conj;?>">
      <td align="right" valign="top">Endere�o:<?php $utils->obrig('enderecoemp_ppcj'); ?></td>
      <td align="left"  valign="top"><input type="text" style="width:350px;" name="enderecoemp_ppcj" id="enderecoemp_ppcj" value="<?php echo $aAltPpnt["conjuge"][0]["enderecoemp_ppcj"];?>" maxlength="100"></td>
    </tr>
    <tr id="tr_conj_trab_end_num" style="<?php echo $display_dets_trab_conj;?>">
      <td align="right" valign="top">N�mero:<?php $utils->obrig('numeroemp_ppcj'); ?></td>
      <td align="left"  valign="top"><input type="text" style="width:40px;" name="numeroemp_ppcj" id="numeroemp_ppcj" value="<?php echo $aAltPpnt["conjuge"][0]["numeroemp_ppcj"];?>" maxlength="6"></td>
    </tr>
    <tr id="tr_conj_trab_compl" style="<?php echo $display_dets_trab_conj;?>">
      <td align="right" valign="top">Complemento:</td>
      <td align="left"  valign="top"><input type="text" style="width:150px;" name="complementoemp_ppcj" id="complementoemp_ppcj" value="<?php echo $aAltPpnt["conjuge"][0]["complementoemp_ppcj"];?>" maxlength="60"></td>
    </tr>
    <tr id="tr_conj_trab_estado" style="<?php echo $display_dets_trab_conj;?>">
      <td align="right" valign="top">Estado:<?php $utils->obrig('estadoemp_ppcj'); ?></td>
      <td align="left"  valign="top">
        <select name="estadoemp_ppcj" id="estadoemp_ppcj" onChange="getListaMunicipios_v2(this,'cidadeemp_ppcj');">
          <option value="0" >-Selecione-</option>
          <?php
          	foreach($listas->getListaUF() as $k=>$v){
      		  	$selected = ($aAltPpnt["conjuge"][0]["estadoemp_ppcj"]==$v['cod_uf'])?'selected':'';
       		    print '<option value="'.$v['cod_uf'].'" '.$selected.'>'.$v['nome_uf'].'</option>';
          	}
          ?>
        </select>
        &nbsp;Cidade:<?php $utils->obrig('cidadeemp_ppcj'); ?>
        <select name="cidadeemp_ppcj" id="cidadeemp_ppcj">
        	<option value="0" >-Selecione-</option>
        	<?php
        		if($aAltPpnt["conjuge"][0]["estadoemp_ppcj"]){
            	foreach($listas->getListaMunicipio($aAltPpnt["conjuge"][0]["estadoemp_ppcj"]) as $k=>$v){
        		  	$selected = ($aAltPpnt["conjuge"][0]["cidadeemp_ppcj"]==$v['cod_municipio'])?'selected':'';
         		    print '<option value="'.$v['cod_municipio'].'" '.$selected.'>'.$v['nome_municipio'].'</option>';
            	}
        		}
        	?>
        </select>
      </td>
    </tr>
    <tr id="tr_conj_trab_bairro" style="<?php echo $display_dets_trab_conj;?>">
      <td align="right" valign="top">Bairro:<?php $utils->obrig('bairroemp_ppcj'); ?></td>
      <td align="left"  valign="top">
        <select name="bairroemp_ppcj" id="bairroemp_ppcj">
          <option value="0" >-Selecione-</option>
          <?php
          	foreach($listas->getListaBairro() as $k=>$v){
      		  	$selected = ($aAltPpnt["conjuge"][0]["bairroemp_ppcj"]==$v['cod_bairro'])?'selected':'';
       		    print '<option value="'.$v['cod_bairro'].'" '.$selected.'>'.$v['nome_bairro'].'</option>';
          	}
          ?>
        </select>
      </td>
    </tr>
		<tr id="tr_conj_trab_telefone" style="<?php echo $display_dets_trab_conj;?>">
			<td align="right" valign="top">Telefone:<?php $utils->obrig('telefoneemp_ppcj'); ?></td>
			<td align="left"  valign="top"><input type="text" style="width:100px;" name="telefoneemp_ppcj" id="telefoneemp_ppcj" value="<?php echo $utils->formataTelefone($aAltPpnt["conjuge"][0]["telefoneemp_ppcj"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraTEL(this,event);" maxlength="13"></td>
		</tr>
		*/?>
		<tr id="tr_conj_trab_cargo" style="<?php echo $display_dets_trab_conj;?>">
			<td align="right" valign="top">Profiss�o:<?php $utils->obrig('cargoemp_ppcj'); ?></td>
			<td align="left"  valign="top"><input type="text" style="width:150px;" name="cargoemp_ppcj" id="cargoemp_ppcj" value="<?php echo $aAltPpnt["conjuge"][0]["cargoemp_ppcj"];?>" maxlength="60"></td>
		</tr><?php /*
		<tr id="tr_conj_trab_salario" style="<?php echo $display_dets_trab_conj;?>">
			<td align="right" valign="top">Sal�rio (R$):<?php $utils->obrig('salarioemp_ppcj'); ?></td>
			<td align="left"  valign="top"><input type="text" name="salarioemp_ppcj" id="salarioemp_ppcj" style="width:80px;" value="<?php echo $utils->formataMoeda($aAltPpnt["conjuge"][0]["salarioemp_ppcj"]);?>" maxlength="20" onKeyDown="return teclasFloat(this,event);" onKeyUp="return mascaraMoeda(this,event);" onFocus="this.select();" /></td>
		</tr*/?>
	</table>
	<?php if($aAltPpnt["cod_estciv"]=='2' || $aAltPpnt["cod_estciv"]=='99' ){ if($cLOGIN->iLEVEL_USUA!=TPUSER_PROPONENTE>=3){
	echo qd_exigencia('conj_exigencia','conj_BtExig','conj_addexig', 'conj_btsalvarexigencia',$_POST['conj_addexig'],$aProposta["cod_ppst"],'fimexigconj');}
	 ?><div id="ckls_ppcj"><?php include('bl_ckls_conjuge.inc.php'); ?></div><?php } ?>
</div>
	
<div id="div_devedor" class="grupoDados">
	<b>Devedor Solid�rio</b>
	<table cellpadding=0 cellspacing=5 border=0>
		<colgroup><col width="150" /><col /></colgroup>
    <tr>
      <td align="right" valign="top">Possui Devedor Solid�rio:<?php $utils->obrig('flgdevsol_ppnt'); ?></td>
      <td align="left"  valign="top">
			  <?php
			  	$display_dets_dev_sol = ($aAltPpnt["flgdevsol_ppnt"]=='S')?'':'display:none;';
			  	foreach ($listas->getListaSN() as $k=>$v){
          	$checked = ($aAltPpnt["flgdevsol_ppnt"]==$k)?'checked':'';
          	print '<input type="radio" class="rd" name="flgdevsol_ppnt" id="flgdevsol_ppnt" value="'.$k.'" '.$checked.' onclick="trocouTemDevSol(this);" /> '.$v.' &nbsp;&nbsp;';
			  	}
			  ?>
      </td>
    </tr>
  </table>

  <?php
    if( !is_array($aAltPpnt["devsol"])) $aAltPpnt["devsol"] = array(0,1,2,3);
  ?>
  
	<table cellpadding=0 cellspacing=5 border=0 id="tb_dets_dev_sol" style="<?php echo $display_dets_dev_sol;?>">
		<colgroup><col width="150" /><col /></colgroup>
    <tr>
      <td align="right" valign="top">Nome:<?php $utils->obrig('nome_devsol'); ?></td>
      <td align="left"  valign="top"><input type="text" style="width:300px;" name="nome_devsol" id="nome_devsol" value="<?php echo $aAltPpnt["devsol"][0]["nome_devsol"]; ?>" maxlength="70"></td>
    </tr>
    <tr>
      <td align="right" valign="top">Nome Abreviado:<?php $utils->obrig('nick_devsolnick_devsol'); ?></td>
      <td align="left"  valign="top"><input type="text" style="width:150px;" name="nick_devsol" id="nick_devsol" value="<?php echo $aAltPpnt["devsol"][0]["nick_devsol"]; ?>" maxlength="15"></td>
    </tr>
    <tr>
      <td align="right" valign="top">Tipo Logradouro:<?php $utils->obrig('logr_devsol'); ?></td>
      <td align="left"  valign="top">
        <select name="logr_devsol" id="logr_devsol">
          <option value="0" >-Selecione-</option>
          <?php
          	foreach($listas->getListaLogradouro() as $k=>$v){
      		  	$selected = ($aAltPpnt["devsol"][0]["cod_logr"]==$v['cod_logr'])?'selected':'';
       		    print '<option value="'.$v['cod_logr'].'" '.$selected.'>'.$v['desc_logr'].'</option>';
          	}
          ?>
        </select>
      </td>
    </tr>
    <tr>
      <td align="right" valign="top">Endere�o:<?php $utils->obrig('endereco_devsol'); ?></td>
      <td align="left"  valign="top"><input type="text" style="width:350px;" name="endereco_devsol" id="endereco_devsol" value="<?php echo $aAltPpnt["devsol"][0]["endereco_devsol"];?>" maxlength="40"></td>
    </tr>
    <tr>
      <td align="right" valign="top">Num:<?php $utils->obrig('nrendereco_devsol'); ?></td>
      <td align="left"  valign="top"><input type="text" style="width:40px;" name="nrendereco_devsol" id="nrendereco_devsol" value="<?php echo $aAltPpnt["devsol"][0]["nrendereco_devsol"];?>" maxlength="6" onKeyDown="return teclasInt(this,event);"></td>
    </tr>
    <tr>
      <td align="right" valign="top">Complemento:</td>
      <td align="left"  valign="top"><input type="text" style="width:150px;" name="cpendereco_devsol" id="cpendereco_devsol" value="<?php echo $aAltPpnt["devsol"][0]["cpendereco_devsol"];?>" maxlength="15"></td>
    </tr>
    <tr>
      <td align="right" valign="top">Estado:<?php $utils->obrig('uf_devsol'); ?></td>
      <td align="left"  valign="top">
        <select name="uf_devsol" id="uf_devsol" onChange="getListaMunicipios_v2(this,'municipio_devsol');">
          <option value="0" >-Selecione-</option>
          <?php
          	foreach($listas->getListaUF() as $k=>$v){
      		  	$selected = ($aAltPpnt["devsol"][0]["cod_uf"]==$v['cod_uf'])?'selected':'';
       		    print '<option value="'.$v['cod_uf'].'" '.$selected.'>'.$v['nome_uf'].'</option>';
          	}
          ?>
        </select>
        &nbsp;Cidade:<?php $utils->obrig('municipio_devsol'); ?>
        <select name="municipio_devsol" id="municipio_devsol">
        	<option value="0" >-Selecione-</option>
        	<?php
        		if($aAltPpnt["devsol"][0]["cod_uf"]){
            	foreach($listas->getListaMunicipio($aAltPpnt["devsol"][0]["cod_uf"]) as $k=>$v){
        		  	$selected = ($aAltPpnt["devsol"][0]["cod_municipio"]==$v['cod_municipio'])?'selected':'';
         		    print '<option value="'.$v['cod_municipio'].'" '.$selected.'>'.$v['nome_municipio'].'</option>';
            	}
        		}
        	?>
        </select>
      </td>
    </tr>
    <tr>
      <td align="right" valign="top">Bairro:<?php $utils->obrig('bairro_devsol'); ?></td>
      <td align="left"  valign="top">
        <select name="bairro_devsol" id="bairro_devsol">
          <option value="0" >-Selecione-</option>
          <?php
          	foreach($listas->getListaBairro() as $k=>$v){
      		  	$selected = ($aAltPpnt["devsol"][0]["cod_bairro"]==$v['cod_bairro'])?'selected':'';
       		    print '<option value="'.$v['cod_bairro'].'" '.$selected.'>'.$v['nome_bairro'].'</option>';
          	}
          ?>
        </select>
      </td>
    </tr>
    <tr>
      <td align="right" valign="top">CEP:<?php $utils->obrig('cep_devsol'); ?></td>
      <td align="left"  valign="top"><input type="text" style="width:150px;" name="cep_devsol" id="cep_devsol" value="<?php echo $utils->formataCep($aAltPpnt["devsol"][0]["cep_devsol"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraCEP(this,event);" maxlength="9"></td>
    </tr>
    <tr>
      <td align="right" valign="top">Telefone:<?php $utils->obrig('telefone_devsol'); ?></td>
      <td align="left"  valign="top"><input type="text" style="width:100px;" name="telefone_devsol" id="telefone_devsol" value="<?php echo $utils->formataTelefone($aAltPpnt["devsol"][0]["telefone_devsol"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraTEL(this,event);" maxlength="13"></td>
    </tr>
    <tr>
      <td align="right" valign="top">CPF:<?php $utils->obrig('cpf_devsol'); ?></td>
      <td align="left"  valign="top"><input type="text" style="width:150px;" name="cpf_devsol" id="cpf_devsol" value="<?php echo $utils->formataCPF($aAltPpnt["devsol"][0]["cpf_devsol"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraCPF(this,event);" maxlength="14"></td>
    </tr>
    <tr>
      <td align="right" valign="top">Sexo:<?php $utils->obrig('sexo_devsol'); ?></td>
      <td align="left"  valign="top">
			  <?php
			  	foreach ($listas->getListaSexo() as $k=>$v){
          	$checked = ($aAltPpnt["devsol"][0]["sexo_devsol"]==$k)?'checked':'';
          	print '<input type="radio" class="rd" name="sexo_devsol" id="sexo_devsol" value="'.$k.'" '.$checked.' /> '.$v.' &nbsp;&nbsp;';
			  	}
			  ?>
      </td>
    </tr>
    <tr>
      <td align="right" valign="top">Nacionalidade:<?php $utils->obrig('pais_devsol'); ?></td>
      <td align="left"  valign="top">
        <select name="pais_devsol" id="pais_devsol">
					<option value="0" >-Selecione-</option>
					<?php
          	foreach($listas->getListaPais() as $k=>$v){
      		  	$selected = ($aAltPpnt["devsol"][0]["cod_pais"]==$v['cod_pais'])?'selected':'';
       		    print '<option value="'.$v['cod_pais'].'" '.$selected.'>'.$v['nome_pais'].'</option>';
          	}
          ?>
        </select>
      </td>
    </tr>
  </table>
</div>

<?php if($acaoProposta=='altPpnt'){ ?>
	<div style="text-align:right; margin-top:10px;">
		<img src="images/buttons/bt_salvar.gif"   id="bt_save_ppnt"   alt="Salvar Proponente" class="im" onClick="corrigirPpnt('<?=$_POST["frm_cod_ppnt"];?>','<?php echo $crypt->encrypt('savePpnt');?>');" />
		<img src="images/buttons/bt_cancelar.gif" id="bt_cancel_ppnt" alt="Cancelar" class="im" onClick="cancelFormAddPpnt(true);" />
	</div>
<?php }else{ ?>
	<div style="text-align:right; margin-top:10px;">
		<img src="images/buttons/bt_adicionar.gif" id="bt_add_ppnt" alt="Adicionar Proponente" class="im" onClick="addPpnt('<?php echo $crypt->encrypt('addPpnt');?>');" />
		<img src="images/buttons/bt_cancelar.gif"  id="bt_cancel_ppnt" alt="Cancelar" class="im" onClick="cancelFormAddPpnt(true);" />
	</div>
<?php } ?><br>
<div id="div_fgts" class="grupoDados">
<a name="fgts"></a>
	<b>Dados de FGTS:</b>
	<table cellpadding=0 cellspacing=5 border=0>
		<colgroup><col width="150" /><col /></colgroup>
    <tr>
		<td colspan="4">
			<?php /*
			$db->query="Select * from retornoerro where participante='".$participante."'";
			//echo $db->query;
			$db->query();
			if($db->qrcount>0)
			{
				echo "FGTS RECUSADO<br><br>";
				$e=1;
				$f=$db->qrcount;
				
				while($e<=$f)
				{
					$cod[$e]=$db->qrdata[$e-1]['erro'];
					$db->query="Select * from erros where cod_erro='".$cod[$e]."'";
					$db->query();
					echo "C�DIGO DE RECUSA: ".$cod[$e]." (".$db->qrdata[0]['MSG_ERRO'].")<br>"; 
				}
			}*/
			?>
		</td>
	</tr>
    <tr>
      <td align="right" valign="top">Utilizar FGTS:<?php $utils->obrig('flgfgts_ppnt'); ?></td>
      <td align="left"  valign="top">
			  <?php
			  	$display_dets_fgts = ($aAltPpnt["flgfgts_ppnt"]=='S')?'':'display:none;';
			  	foreach ($listas->getListaSN() as $k=>$v){
          	$checked = ($aAltPpnt["flgfgts_ppnt"]==$k)?'checked':'';
			print '<input type="radio" class="rd" name="flgfgts_ppnt" id="flgfgts_ppnt" value="'.$k.'" '.$checked.' onclick="trocouTemFgts(this);" /> '.$v.' &nbsp;&nbsp;';
			  	}
			  ?>
      </td>
    </tr>
  </table>

  <?php
    if( !is_array($aAltPpnt["fgts"])) $aAltPpnt["fgts"] = array(0,1,2,3);
  ?>
  
	<table cellpadding=0 cellspacing=5 border=0 id="tb_dets_fgts" style="<?php echo $display_dets_fgts;?>">
		<colgroup><col width="150" /><col /></colgroup>
    <tr>
       <td width="163" align="right">Status do Im�vel:<?php echo $obrig; ?></td>
	   <td width="155" align="left">
	   	<?php $tipo_simulador=1; ?>
	     <input type="radio" class="rd" name="stimov_fgts" id="stimov_fgts" value="1" <?php if($aAltPpnt["fgts"][0]["stimov_fgts"]=='1'){echo "checked";}?> onClick="" > Novo &nbsp;&nbsp; 
         <input type="radio" class="rd" name="stimov_fgts" id="stimov_fgts" value="2" <?php if($aAltPpnt["fgts"][0]["stimov_fgts"]=='2'){echo "checked";}?>  onClick="" > Usado<br></tr>
    <tr>
       <td width="163" align="right">Estado: (IBGE) <?php echo $obrig; ?></td>
	   <td width="155" align="left">
	   	<?php $tipo_simulador=1; ?>
          	      <select name="estado_fgts" id="estado_fgts" onChange="pegausr(this.value);">
	        <option value="0" >-Selecione-</option>
	        <?php
	        	foreach($listas->getListaUF() as $k=>$v){
	    		  	$selected = ($aAltPpnt["fgts"][0]["estado_fgts"]==$v['cod_uf'])?'selected':'';
	     		    print '<option value="'.$v['cod_uf'].'" '.$selected.'>'.$v['nome_uf'].'</option>';
	        	}
	        ?>
	      </select>
</tr>
       <td width="163" align="right">Municipio: (IBGE):<?php echo $obrig; ?></td>
	   <td width="440" align="left">
	   <div id="cidades">
	   <?php
			$query = "SELECT cod_municipio, municipio FROM ibge WHERE uf='".$aAltPpnt["fgts"][0]["estado_fgts"]."'";

	   ?>
	   		  <select name="municipio_fgts" onChange="">
				<option value="0">-Selecione-</option><?php
			$result =mysql_query($query);
			if (mysql_num_rows($result) > 0)
			{
				while($linhas = mysql_fetch_array($result, MYSQL_ASSOC))
				{
						$selected='';
						if($aAltPpnt["fgts"][0]["municipio_fgts"]==$linhas[municipio]){$aAltPpnt["fgts"][0]["codmunicipio_fgts"]=$linhas[cod_municipio];$selected="selected";}?>
						<option <?php echo $selected;?> value="<?php echo $linhas[municipio] ?>"><?php echo $linhas[municipio]?></option><?php
						$reg++;
				}
			}?>
			</select>
			</div>
			<input type="hidden" name="codmunicipio_fgts" id="codmunicipio_fgts" value="<?php echo $aAltPpnt["fgts"][0]["codmunicipio_fgts"];?>">

</tr>
	<tr>
	
	<tr>
      <td align="right" valign="top">Num. de Contas: <?php echo $obrig; ?></td>
      <td align="left"  valign="top"><input type="text" style="width:40px;" name="qtcontas_fgts" id="qtcontas_fgts" value="<?php echo $aAltPpnt["fgts"][0]["qtcontas"]; ?>" onBlur="atualfgts('<?php echo $registroPpnt["cod_proponente"];?>','<?php echo $crypt->encrypt('altPpnt');?>');" onKeyDown="return teclasInt(this,event);" onKeyUp="return teclasInt(this,event);" maxlength="2"></td>
    </tr>
<?php 
$c=1;
$vloperfgts=0;
while($c<=$aAltPpnt["fgts"][0]["qtcontas"])
{?>	
	<tr>
      <td align="center" valign="top" colspan="4"><b>Conta FGTS <?php echo $c;?></b></td>
    </tr>
	<tr>
      <td align="left" valign="top" colspan="4"><b><hr></b></td>
    </tr>
    <tr>
      <td align="right" valign="top">Trabalhador:  <?php echo $obrig; ?></td>
      <td align="left"  valign="top"><input type="text" style="width:200px;" name="nometrab_fgts<?php echo $c;?>" id="nometrab_fgts<?php echo $c;?>" value="<?php echo $aAltPpnt["fgts"][$c]["nometrab_fgts"]; ?>" maxlength="40"></td>
      <td align="right" valign="top">Dt. Nascimento:  <?php echo $obrig; ?></td>
      <td align="left"  valign="top"><input type="text" style="width:80px;" name="dtnasctrab_fgts<?php echo $c;?>" id="dtnasctrab_fgts<?php echo $c;?>" value="<?php echo $aAltPpnt["fgts"][$c]["dtnasctrab_fgts"]; ?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10"></td>
    </tr>
    <tr>
      <td align="right" valign="top">PIS/PASEP: <?php echo $obrig; ?></td>
      <td align="left"  valign="top"><input type="text" style="width:100px;" name="pis_fgts<?php echo $c;?>" id="pis_fgts<?php echo $c;?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraPIS(this,event);" value="<?php if($aAltPpnt["fgts"][$c]["pis_fgts"]==''){echo $utils->formataPIS($aAltPpnt["fgts"][1]["pis_fgts"]);}else{echo $utils->formataPIS($aAltPpnt["fgts"][$c]["pis_fgts"]);} ?>" maxlength="14"></td>
	  <td align="right" valign="top">Situa��o da Conta: <?php echo $obrig; ?></td>
	  <td align="left">
          <select name="sitconta_fgts<?php echo $c;?>" id="sitconta_fgts<?php echo $c;?>" >
		        <option  value="0">-Selecione-</option>
				<option <?php if ($aAltPpnt["fgts"][$c]["sitconta_fgts"]=='A') {echo "selected";} ?> value="A" >Ativa</option>
				<option <?php if ($aAltPpnt["fgts"][$c]["sitconta_fgts"]=='I') {echo "selected";} ?> value="I">Inativa</option>
				<option <?php if ($aAltPpnt["fgts"][$c]["sitconta_fgts"]=='P') {echo "selected";} ?> value="P">Plano Econ�mico</option>
		  </select></td>
    </tr>
    <tr>
      <td align="right" valign="top">Conta Empregador: </td>
      <td align="left"  valign="top"><?php echo $obrig; ?> <input type="text" style="width:100px;" name="contaemp_fgts<?php echo $c;?>" id="contaemp_fgts<?php echo $c;?>" value="<?php echo $aAltPpnt["fgts"][$c]["contaemp_fgts"]; ?>" maxlength="14"></td>
      <td align="right" valign="top">Conta Trabalhador: </td>
      <td align="left"  valign="top"> <?php echo $obrig; ?> <input type="text" style="width:100px;" name="contatrab_fgts<?php echo $c;?>" id="contatrab_fgts<?php echo $c;?>" value="<?php echo $aAltPpnt["fgts"][$c]["contatrab_fgts"]; ?>" maxlength="11"></td>
    </tr>
    <tr>
      <td align="right" valign="top">Valor a ser debitado: </td>
	  <?php
$vloperfgts+=$aAltPpnt["fgts"][$c]["valordeb_fgts"];
	  ?>
      <td align="left"  valign="top"> <?php echo $obrig; ?> <input type="text" style="width:100px;" name="valordeb_fgts<?php echo $c;?>"  onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraMoeda(this,event);" id="valordeb_fgts<?php echo $c;?>" value="<?php echo $utils->formataMoeda($aAltPpnt["fgts"][$c]["valordeb_fgts"]); ?>" maxlength="15" onblur="resValorOper();"></td>
      <td align="right" valign="top">Base Conta FGTS: </td>
      <td align="left"  valign="top"><?php echo $obrig; ?> <input type="text" style="width:100px;" name="baseconta_fgts<?php echo $c;?>" id="baseconta_fgts<?php echo $c;?>" value="<?php echo $aAltPpnt["fgts"][$c]["baseconta_fgts"]; ?>" maxlength="2"> (SUREG)</td>
    </tr><?php
$c++;
}
function calculofgts($qtcontas)
{
	$c=1;
	$somafgts='';
	while($c<=$qtcontas)
	{
		$_POST["valordeb_fgts".$c]=str_replace(",",".",$_POST["valordeb_fgts".$c]);
		$somafgts=$_POST["valordeb_fgts".$c]+$somafgts;
		$c++;
	}
	return $somafgts;
	
}
	?>

    <tr>
      <td align="right" valign="top" colspan="4"><b><hr></b></td>
    </tr>
    <tr>
      <td align="right" valign="top">Valor da Opera��o:</td>
      <td align="left"  valign="top" style="font-weight:bold; color:#600;"><div id='resultado'>R$ 
	  <?php 
echo  $utils->formataFloat($vloperfgts,2);
 ?></div></td>
      <input type="hidden" name="teste_fgts" id="teste_fgts" value="<?=$aAltPpnt["fgts"][0]["teste_fgts"];?>">
	</tr>
  </table>
</div>
	<div style="text-align:right; margin-top:10px;">
	    <input type="hidden" name="acaoFgts" id="acaoFgts" value="">
		<img src="images/buttons/bt_salvar.gif"   id="bt_save_fgts"   alt="Salvar Fgts" class="im" onClick="return salvarFgts();" />
		<img src="images/buttons/bt_cancelar.gif" id="bt_cancel_ppnt" alt="Cancelar" class="im" onClick="cancelFormAddPpnt(true);" />
	</div>
<!-- AQUI TERMINA bl_form_proponente.inc.php -->
<?php
 if($aAltPpnt["flgfgts_ppnt"]=='S' ){ if($cLOGIN->iLEVEL_USUA!=TPUSER_PROPONENTE){

echo qd_exigencia('fgts_exigencia','fgts_BtExig','fgts_addexig', 'fgts_btsalvarexigencia',$_POST['fgts_addexig'],$aProposta["cod_ppst"],'fimexigfgts');}

	 ?><div id="ckls_ppfg"><?php include('bl_ckls_fgts.inc.php'); ?></div><?php } ?>

