<?php
$tmpMT_ini = microtime();

include "./class/dbclasses.class.php";

if ($_POST) {

    /**
     *  REQUISICAO AJAX
     */
    if ($_POST['atualizar_envio_previ'] == true) {

        if (!isset($_POST['enviar_previ']) || !isset($_POST['proposta'])) {
            echo json_encode(['success' => false, 'message' => 'Alguns parametros não foram preenchidos. Entre em contato com o suporte.']); die;
        }

        $enviar_previ = $_POST['enviar_previ'];
        $cod_ppst = $_POST['proposta'];

        $oProposta = new proposta();
        $oProposta->atualizarEnvioPrevi($cod_ppst, $enviar_previ);

        echo json_encode(['success' => true, 'message' => 'Registro atualizado com sucesso.']); die;

    }

}

$iREQ_AUT=1;
$aUSERS_PERM[] = TPUSER_PROPONENTE;
$aUSERS_PERM[] = TPUSER_ATENDENTE;
$aUSERS_PERM[] = TPUSER_DESPACHANTE;
$aUSERS_PERM[] = TPUSER_JURIDICO;
$aUSERS_PERM[] = TPUSER_ADMINISTRATIVO;
$aUSERS_PERM[] = TPUSER_USUARIOMASTER;

$pageTitle = "Proposta";
include "lib/header.inc.php";

$oProposta     = new proposta();
$oUsuario      = new usuario();
$oMensagem     = new mensagens();
$oAgenda	   = new agenda();

$aProposta   = $oProposta->saveProposta();

if($cLOGIN->iLEVEL_USUA==1){
	$aProposta   = $oProposta->getProposta($cLOGIN->iCODPPST);
}else{
	$aProposta   = $oProposta->getProposta(($_POST["frm_cod_ppst"] ? $_POST["frm_cod_ppst"] : $_GET["cod_proposta"]));
}
$acaoProposta = $crypt->decrypt($_POST["acaoProposta"]);


// cada "iLEVEL_USUA" carrega um "js" especifico
switch($cLOGIN->iLEVEL_USUA){
	case 1: $fileJS = 'proposta_level1.js'; break; // Proponente
	case 2: $fileJS = 'proposta_level2.js'; break; // Atendente
	default: $fileJS = 'proposta_level2.js';
}

if(!isset($_GET['cod_proposta']))
{
	//para atualizar o código da proposta na agenda
	$oAgenda->putCodProposta($aProposta["cod_ppst"]);
	//para transpotar o histórico
	//$oAgenda->transpotar($aProposta["cod_ppst"]);
}

$db->query="Select DTAPROVACAO_PPST from proposta where cod_ppst='".$aProposta["cod_ppst"]."'";
$db->query();
if($db->qrcount>0)
{
	$dtaprov_ppst=$db->qrdata[0]['DTAPROVACAO_PPST'];
}
$db->query="Select cod_proponente, vlfinsol_ppnt from proponente where cod_ppst='".$aProposta["cod_ppst"]."'";
$db->query();
if($db->qrcount>0)
{
	$proponente=$db->qrdata[0]['cod_proponente'];
	$financiamento=$db->qrdata[0]['vlfinsol_ppnt'];
}


$db->query="Select id_lstn from usuario where cod_usua='".$proponente."'";
$db->query();
$id_lstn=$db->qrdata[0]['id_lstn'];

$db->query="Select vlmaxfinan, status_pp from listadenomes where id_lstn='".$id_lstn."'";
//echo $db->query;
$db->query();
$vlmax=$db->qrdata[0]['vlmaxfinan'];
$st_pp=$db->qrdata[0]['status_pp'];


?>
<script language="JavaScript">
function dataHoje(){
return '<?=date('d/m/Y',time());?>'
}
</script>
	<script language="JavaScript" type="text/javascript" src="./js/diversos.js"></script>
	<script language="javascript" type="text/javascript" src="./js/ajaxapi.js"></script>
	<script language="JavaScript" type="text/javascript" src="./js/<?php echo $fileJS;?>"></script>
	<script language="JavaScript" type="text/javascript" src="./js/proposta_geral.js"></script>
	<script language="JavaScript">
		// instancia a FLG_PREVI em javascript ...
		var FLG_PREVI = <?php echo (FLG_PREVI)?'true':'false';?>;
		var DATA_ATUAL = '<?php echo $cLOGIN->dDATA;?>';
	</script>
	
	<?php /* debug -------------------------------------------------------------------- * / ?>
	<div style="border:3px double #FFF; background-color:#000; color:#FFF; margin-bottom:5px; padding:3px;">
		<?php $star='<font color="#FF8888">&#1645;</font>'; ?>
		iID:           <b style="color:#FF0;"><?php echo $cLOGIN->iID;?></b>                <?php echo $star;?>
		iLEVEL_USUA:   <b style="color:#FF0;"><?php echo $cLOGIN->iLEVEL_USUA;?></b>        <?php echo $star;?>
		COD_PPST:      <b style="color:#FF0;"><?php echo $aProposta["cod_ppst"];?></b>      <?php echo $star;?>
		SITUACAO_PPST: <b style="color:#FF0;"><?php echo $aProposta["situacao_ppst"];?></b> <?php echo $star;?>
		ACAO PROPOSTA: <b style="color:#FF0;"><?php echo $acaoProposta;?></b>               <?php echo $star;?>
		PREVI:         <b style="color:#FF0;"><?php echo ((FLG_PREVI)?'SIM':'NÃO');?></b>
	</div>
	<?php /* -------------------------------------------------------------------------- */ ?>
	
	<?php
	
		if( in_array($aProposta["situacao_ppst"],$aPROPOSTALISTA[$cLOGIN->iLEVEL_USUA]) ){
			?>
			<form name="proposta" id="proposta" method="post" action="proposta.php<?php echo (($cLOGIN->iLEVEL_USUA>1)?'?cod_proposta='.$aProposta["cod_ppst"]:'');?>">
				<input type="hidden" name="acaoProposta"  id="acaoProposta"  value="" />
				<input type="hidden" name="frm_cod_ppst"  id="frm_cod_ppst"  value="<?php echo $aProposta["cod_ppst"];?>" />
				<input type="hidden" name="frm_data_ppst" id="frm_data_ppst" value="<?php echo $utils->formataDataBRA($aProposta["data_ppst"]);?>" />
				<input type="hidden" name="trava_clistimovel"            id="trava_clistimovel"            value="<?php echo $aProposta["trava_clistimovel"];?>" />
				<input type="hidden" name="trava_clistproponente"        id="trava_clistproponente"        value="<?php echo $aProposta["trava_clistproponente"];?>" />
				<input type="hidden" name="trava_clistproponenteconjuge" id="trava_clistproponenteconjuge" value="<?php echo $aProposta["trava_clistproponenteconjuge"];?>" />
				<input type="hidden" name="trava_clistproponentefgts"    id="trava_clistproponentefgts"    value="<?php echo $aProposta["trava_clistproponentefgts"];?>" />
				<input type="hidden" name="trava_clistvendfis"           id="trava_clistvendfis"           value="<?php echo $aProposta["trava_clistvendfis"];?>" />
				<input type="hidden" name="trava_clistvendfisconjuge"    id="trava_clistvendfisconjuge"    value="<?php echo $aProposta["trava_clistvendfisconjuge"];?>" />
				<input type="hidden" name="trava_clistvendjur"           id="trava_clistvendjur"           value="<?php echo $aProposta["trava_clistvendjur"];?>" />
				<input type="hidden" name="checklistadvogado"            id="checklistadvogado"            value="<?php echo $aProposta["checklistadvogado"];?>" />
<!-- Até aqui está na proposta.php -->				

				
<script language="javascript">
function SalvarResponsavel(){

		if(document.getElementById('resp').value==0){
		alert('Por favor, informe o responsável pela pasta!');
		return false;
		}
		else if((!vCheck('locresp',   ' de onde é o responsável'))){
		return false;
		}
		else{
			document.getElementById('responsavel').value ="salvar";
			document.getElementById('proposta').submit();
			return true;
		}
}
function atualexig(ancora){
	document.getElementById('proposta').action += '#'+ancora;
	document.getElementById('proposta').submit();
}
function verificaexig()
{
	if(document.getElementById('descexig'))
	{
		if(document.getElementById('descexig').value=='')
		{
			alert('Preencha a Descrição da Exigencia')
			return false;
		}else
		{
			return true;
		}
	}else
	{
		return true;
	}		
}
function salvarExig(BtExig)
{
	//alert (BtExig);
	if(verificaexig())
	{
		document.getElementById(BtExig).value ="salvar";
	    document.getElementById('proposta').action += '#proponente';
		document.getElementById('proposta').submit();
  		return true;
	}else
	{
		return false;
}
}
</script>
<?php
if($_POST['responsavel']=='salvar')
{
	$db->query="Update proposta set resp_ppst='".$_POST['resp']."',locresp_ppst='".$_POST['locresp']."' where cod_ppst='".$aProposta["cod_ppst"]."'";
	$db->query();
}
$db->query="Select resp_ppst,locresp_ppst from proposta where cod_ppst='".$aProposta["cod_ppst"]."'";
$db->query();
$resp=$db->qrdata[0]['resp_ppst'];
$locresp=$db->qrdata[0]['locresp_ppst'];

if($st_pp=='I'){
echo '<div style="vertical-align: middle; padding: 5px; margin: 5px; border: 1px solid #CA1D1D; background-color: #EBCACA; color: #CA1D1D; font-weight: bold;"><img src="images/mensagens/erro.gif" alt="Erro" style="vertical-align: middle;" />Participante com Impedimento na Previ.</div>
				';}
function DataBRA($data)
{
		$datdia=	 substr($data,8,2);
		$datmes=	 substr($data,5,2);
		$datano=     substr($data,0,4);
		$dataBRA=$datdia."/".$datmes."/".$datano;
		return $dataBRA;
}

########################## BUSCA DATAS DE INICIO E FIM DE EXIGENCIA NO BANCO DE DADOS ######################

$db->query="Select * from proposta where cod_ppst='".$aProposta["cod_ppst"]."'";
//echo $db->query;
$db->query();
if($db->qrcount>0)
{
	$iniexigencia=		$db->qrdata[0]['DTINIEXIGENCIA_PPST'];
	//$fimexigencia=		$utils->formataDataBRA($db->qrdata[0]['DTFIMEXIGENCIA_PPST']);
	
}
$dtini=$iniexigencia;
//$dtfim=$fimexigencia;

############################ VARIAVEIS RECEBEM POST DE DATAS DE  INICIO E FIM DE EXIGENCIA  ############
if($_POST['fimexigprop']){$dtfim=$_POST['fimexigprop'];}
if($_POST['fimexigconj']){$dtfim=$_POST['fimexigconj'];}
if($_POST['fimexigfgts']){$dtfim=$_POST['fimexigfgts'];}
if($_POST['fimexigvend']){$dtfim=$_POST['fimexigvend'];}
if($_POST['fimexigimov']){$dtfim=$_POST['fimexigimov'];}
if($_POST['fimexigvendcj']){$dtfim=$_POST['fimexigvendcj'];}

//$dtfim=$_POST['fimexigprop'];

$prop_addexig=$_POST['prop_addexig'];
$prop_exig=$_POST['prop_BtExig'];
$conj_addexig=$_POST['conj_addexig'];
$conj_exig=$_POST['conj_BtExig'];
$fgts_addexig=$_POST['fgts_addexig'];
$fgts_exig=$_POST['fgts_BtExig'];
$vend_addexig=$_POST['vend_addexig'];
$vend_exig=$_POST['vend_BtExig'];
$vendcj_addexig=$_POST['vendcj_addexig'];
$vendcj_exig=$_POST['vendcj_BtExig'];
$imov_addexig=$_POST['imov_addexig'];
$imov_exig=$_POST['imov_BtExig'];
############################## SALVA DATAS DE EXIGENCIA NO BANCO DE DADOS ########################
if($prop_exig=='salvar' || $conj_exig=='salvar' || $fgts_exig=='salvar' || $vend_exig=='salvar' || $vendcj_exig=='salvar' || $imov_exig =='salvar')
{
	?>
	<script>
	//alert("<?php echo $iniexigencia?>");
	</script>
	<?php
	if($iniexigencia=='')
	{
		$db->query="Update proposta set dtiniexigencia_ppst= now() where cod_ppst='".$aProposta["cod_ppst"]."'";
		//echo $db->query;
		$db->query();
	}
	$addexig='';
	if($_POST['descexig']!='')
	{
		$db->query="Insert into historico (COD_PPST, DT_HIST, OBS_HIST, TIPO_HIST, COD_USUA) values ('".$aProposta["cod_ppst"]."',now(),'".$_POST[descexig]."','2','".$cLOGIN->iID."')";
		$db->query();
	}
	if($dtfim!='')
	{
		$datdia=	 substr($dtfim,0,2);
		$datmes=	 substr($dtfim,3,2);
		$datano=     substr($dtfim,6,4);
		$datafim= $datano."-".$datmes."-".$datdia;
		$db->query="Update proposta set dtfimexigencia_ppst='".$datafim."'where cod_ppst='".$aProposta["cod_ppst"]."'";
		$db->query();
	}else
	{
		$db->query="Update proposta set dtfimexigencia_ppst=NULL where cod_ppst='".$aProposta["cod_ppst"]."'";
		$db->query();
	}
$prop_addexig="";
$prop_exig="";
$conj_addexig="";
$conj_exig="";
$fgts_addexig="";
$fgts_exig="";
$vend_addexig="";
$vend_exig="";
$vendcj_addexig="";
$vendcj_exig="";
$imov_addexig="";
$imov_exig="";

}

?>
<?php
$hoje=date("d/m/Y");
				
############################ FUNÇÃO PARA EXIBIR EXIGENCIA #####################################
function qd_exigencia($ancora,$hd_botao,$flg_exigencia,$bt_exigencia,$prop_addexig,$cod_ppst,$fim_exig)
{
?>

<input type="hidden" name="<?php echo $hd_botao;?>" id="<?php echo $hd_botao;?>" value="">

<div id="div_exig" class="grupoDados" style="clear:both;">
<a name="<?php echo $ancora?>"></a>
<table cellpadding=0 cellspacing=5 border=0 class="tb_dets_list">
<?php
$query="Select  DTINIEXIGENCIA_PPST, DTFIMEXIGENCIA_PPST from proposta where cod_ppst='".$cod_ppst."'";
$result =mysql_query($query);
$linhas= mysql_num_rows($result);
$registro = mysql_fetch_array($result, MYSQL_ASSOC);
	$dt_iniexig=$registro['DTINIEXIGENCIA_PPST'];
	$dt_iniexig=$registro['DTFIMEXIGENCIA_PPST'];
	if($dt_iniexig!='')
	{
		$dt_iniexig=DataBRA($dt_iniexig);
	}else
	{
		echo "";
	}
	if($dt_fimexig!='')
	{
		$dt_fimexig=DataBRA($dt_fimexig);
	}else
	{
		echo "";
	}
if($cLOGIN->iLEVEL_USUA!=1)
{
//echo $dt_iniexig;
	if($registro['DTINIEXIGENCIA_PPST']!='' && $registro['DTFIMEXIGENCIA_PPST']=='')
	{
?>	
	<tr style="<?php echo $mostra;?>">
	  <td align="left" valign="top">Início de Exigência:&nbsp;&nbsp;&nbsp;<b><?php echo DataBRA($registro['DTINIEXIGENCIA_PPST']);?></b>
	  							   &nbsp;&nbsp;&nbsp; Fim de Exigência:&nbsp;&nbsp;&nbsp;<input type="text" name="<?php echo $fim_exig;?>" id="<?php echo $fim_exig;?>"  value="" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  </td>
	</tr>
	<tr style="<?php echo $mostra;?>">
	  <td align="left" valign="top"><hr></td>
	</tr>
<?php }
	if($registro['DTINIEXIGENCIA_PPST']!='' && $registro['DTFIMEXIGENCIA_PPST']!='')
	{
?>	
	<tr style="<?php echo $mostra;?>">
	  <td align="left" valign="top">Início de Exigência:&nbsp;&nbsp;&nbsp;<b><?php echo DataBRA($registro['DTINIEXIGENCIA_PPST']);?></b>
	  							   &nbsp;&nbsp;&nbsp; Fim de Exigência:&nbsp;&nbsp;&nbsp;<b><?php echo DataBRA($registro['DTFIMEXIGENCIA_PPST']);?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  </td>
	</tr>
<?php }

if($registro['DTINIEXIGENCIA_PPST']=='' && $registro['DTFIMEXIGENCIA_PPST']=='')
{
?>
	<tr >
	  <td align="left"><input <?php if ($prop_addexig==1){echo "checked";}?> type="radio" class="rd" name="<?php echo $flg_exigencia;?>" id="<?php echo $flg_exigencia;?>" value="1" onclick="atualexig('prop_exigencia');" >&nbsp;&nbsp;&nbsp;<b>Adicionar Exigência</b></td>
<?php 


if ($prop_addexig==1)
{?>
	</tr>
	<tr id="tb_dets_exig" style="">
	<td><textarea cols="120" name="descexig" id="descexig"></textarea>
	</td>
	</tr>
<?php
}
}
if($registro['DTFIMEXIGENCIA_PPST']==''){?>
<tr>
	  <td align="right" valign="top"><img src="images/buttons/bt_salvar.gif"   id="<?php echo $bt_exigencia;?>"   alt="Salvar Exigência" class="im" onClick="return salvarExig('<?php echo $hd_botao;?>');" /> </td>
	</tr> <?php 
	}
}?>	
	</table>
	</div>
	
	<?php	
}?>


<div>
	<script
		src="https://code.jquery.com/jquery-2.2.4.min.js"
		integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
		crossorigin="anonymous"></script>
	<script>
		jQuery(function(){

			jQuery('#enviar_previ').change(function(){

				enviar_previ = jQuery(this).val();
				proposta = jQuery('#codigo_da_proposta').val();

				jQuery.ajax({
					url: "proposta.php",
					type: "POST",
					data: {
						enviar_previ: enviar_previ,
						proposta: proposta,
                        atualizar_envio_previ: true
					},
					dataType: 'json',
					beforeSend: function(){
						jQuery('#message').html('Salvando...').show();
					},
					success: function(response) {

						if (response.success == false) {

							alert(response.message);

						} else {

							jQuery('#message').html(response.message)
							jQuery('#message').show();

							setTimeout(function(){
								jQuery('#message').hide();
							}, 5000);

						}
					}
				})

			});
		})
	</script>
	<p style="font-size: 12px; font-weight: bold;padding: 10px;">
		Enviar proposta para PREVI?
		&nbsp;
		<input type="hidden" id="codigo_da_proposta" value="<?php echo $aProposta['cod_ppst'] ?>" />
		<select style="width: 70px;" id="enviar_previ" name="enviar_previ">
			<option value="1" <?php if ($aProposta['enviar_previ'] == 1) echo "selected='selected'" ?>>Sim</option>
			<option value="0" <?php if ($aProposta['enviar_previ'] == 0) echo "selected='selected'" ?>>Nao</option>
		</select>
		&nbsp; <span id="message" style="color: green; display: none;"></span>
	</p>
</div>

<div class="bloco_include">
	<a name="assinatura"></a>
	<div class="bloco_titulo">&nbsp;</div>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
			
            
						<table width="442" border=0 cellpadding=0 cellspacing=5>
		        
		         	 <?php 
					 if($cLOGIN->iLEVEL_USUA != TPUSER_PROPONENTE){
					 ?>
                     <tr>
		         	 <td width="158" align="right" valign="top"><b>Responsável pela Pasta:</b></td>
					 <td width="269" align="left"  valign="top">
					 <select name="resp" id="resp">
					 <option value='0'>-- Selecione --</option>
					 <?php
					 	$db->query="Select nome_usua, cod_usua from usuario where level_usua='2' AND ativo = 1";
						$db->query();
						if($db->qrcount>0)
						{
							$i=0;
							while($i<$db->qrcount)
							{?>
								<option value="<?php echo $db->qrdata[$i]['cod_usua'];?>" <?=($resp==$db->qrdata[$i]['cod_usua'])?'selected':'';?>><?php echo $db->qrdata[$i]['nome_usua'];?></option>
								<?php
								$i++;
							}
						}
					 ?>
					 </select></td></tr>
                    <tr>
		         	 <td width="158" align="right" valign="top"><b>Responsável de:</b></td><td width="269" align="left"  valign="top"><input type="radio" name="locresp" class="rd" value="S" <?=($locresp=='S')?'checked':'';?> /> SP &nbsp;&nbsp;&nbsp; <input type="radio" name="locresp" value="C" class="rd" <?=($locresp=='C')?'checked':'';?> /> C</td></tr>
                    <tr>
		         	 <td colspan='2'  style="padding-left:100px">
					 <input type="image" value="Salvar" name="SalvarRp" id="SalvarRp" src="images/buttons/bt_salvar.gif" class="im" onClick="return SalvarResponsavel();">
					 <input type="hidden" name="responsavel" id="responsavel" value="">
					 </td>
                      </tr>
					 <?php
					 }
					 else{
					 ?>
                     <tr>
		         	 <td width="158" align="right" valign="top"><b>Responsável pela Pasta:</b></td>
					 <td width="269" align="left"  valign="top"><b><font color="#FF3333">
					 <?php 
					 	$db->query="Select nome_usua from usuario where cod_usua='".$resp."'";
						$db->query();
						echo $db->qrdata[0]['nome_usua'];
						?></font></b>
					 </td>
                     </tr>
                     <tr>
		         	 <td width="158" align="right" valign="top"><b>Responsável de:</b></td>
					 <td width="269" align="left"  valign="top"><b><font color="#FF3333">
						<?=($locresp=='S')?'SP':'C';?></font></b>
					 </td>
                     </tr>
					 <?php
					 }
					 ?>
				 
	      </table>
            
		</div>
		<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
	</div>
</div>
<br  />
<?php

					include "lib/calendar.php";
					include "lib/obrigatorio.inc.php";
					include "lib/bl_ancoras.inc.php";
					include "lib/bl_proposta.inc.php";
					include "lib/bl_proponente.inc.php";
					include "lib/bl_credor.inc.php";
					include "lib/bl_intvquitante.inc.php";
					//if($flg_intv=='S'){
					include "fckeditor/qualificacao_intvquitante.php";
					//}
					include "lib/bl_pagamento.inc.php";
					include "lib/bl_imovel.inc.php";
					include "fckeditor/teste.php";
					if($aProposta["tf_ppst"]!="S"){
					include "lib/bl_vendedor.inc.php";
					}
					//include "lib/bl_aprovproposta.inc.php";
					include "lib/bl_assinatura.inc.php";
					//include "lib/bl_contrato.inc.php";
					include "fckeditor2/infoadicionais.php";
					include "lib/bl_remessa.inc.php";
					include "lib/bl_parecer.inc.php";
					include "lib/bl_advogado.inc.php";
					include "lib/bl_botoes.inc.php";
					include "lib/bl_historico.inc.php";
				?>
			
			</form>
			<?php
		}elseif($cLOGIN->iLEVEL_USUA==1 && $cLOGIN->iCODPPST==''){
			$oMensagem->setMensagem("Nenhuma proposta associada ao usuário",MSG_ERRO);
			print '<div style="height:200px;">'.$oMensagem->getMessageBox().'</div>';
		}else{
			$oMensagem->setMensagem("Permissão negada para acessar esta proposta",MSG_ERRO);
			print '<div style="height:200px;">'.$oMensagem->getMessageBox().'</div>';
		}
		
		$tmpMT_fim = microtime();
	?>
	
<?php include "lib/footer.inc.php";