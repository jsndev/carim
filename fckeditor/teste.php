<?php

		if($_POST['qimov']=='salvar'){
			$db->query="Update imovel set qualificacao_imov='".$_POST['teste']."' where cod_ppst='".$aProposta["cod_ppst"]."'";
			$db->query();
			$db->query="Insert into historico (COD_PPST, DT_HIST, OBS_HIST, TIPO_HIST, COD_USUA) values ('".$aProposta["cod_ppst"]."',now(),'Qualificação do Imóvel inseridas.','1','".$cLOGIN->iID."')";
			$db->query();
		}
		?>
		<script>
		function SalvarQualificacao()
		{
			document.getElementById('qimov').value="salvar"
			document.getElementById('proposta').action += "#quadro_im";
			return true;
		}
		
		function InserirQualificacaoImov()
		{
			document.getElementById('proposta').action += "&qualimov=Y#quadro_im";
			return true;
		}
        
        </script>
		<br>
		<div class="bloco_include" id="bloco_pagamento">
			<div class="bloco_titulo">Qualificação do Imóvel</div>
			<div class="quadroInterno">
				<div><img src="images/layout/subquadro_t.gif" alt="" /></div>
				<div class="quadroInternoMeio">
                <a name="quadro_im"></a>
                
<?php 
if(($cLOGIN->iLEVEL_USUA == TPUSER_JURIDICO && $aProposta["situacao_ppst"]<6) && $aProposta["dtaprovacao_ppst"]=='' && $_GET['qualimov']=='Y'){ 
?>		
		<?php 
		//if(!$_POST){
			$db->query="Select qualificacao_imov from imovel where cod_ppst='".$aProposta["cod_ppst"]."'";
			$db->query();
			if($db->qrcount>0)
			{
				$_POST['teste']=$db->qrdata[0]['qualificacao_imov'];
				$qualimov=$db->qrdata[0]['qualificacao_imov'];
			}
		//}
	
		include("fckeditor.php") ;
		?>
		<?php
		// Automatically calculates the editor base path based on the _samples directory.
		// This is usefull only for these samples. A real application should use something like this:
		// $oFCKeditor->BasePath = '/fckeditor/' ;	// '/fckeditor/' is the default value.
		$sBasePath = $_SERVER['PHP_SELF'] ;
		$sBasePath = substr( $sBasePath, 0, strpos( $sBasePath, "_samples" ) ) ;
		
		$oFCKeditor = new FCKeditor('FCKeditor1') ;
		$oFCKeditor->BasePath	= $sBasePath ;
		//$oFCKeditor->Value		= '' ;
		$oFCKeditor->Create() ;
		?>
        
        
					<br>
					<p>&nbsp;&nbsp;<i>Use <b>(m2)</b> para substituir <b>(m<sup>2</sup>)</b></i></p>
					<p align="right">
					<input type="image" name="SvQImov" id="SvQImov" class="im" src="images/buttons/bt_salvar.gif" onClick="return SalvarQualificacao();" ></p>
					<input type="hidden" name="qimov" id="qimov" value="">
		
<?php
}elseif(($aProposta["dtaprovacao_ppst"]!='' && $aProposta["situacao_ppst"]>=6) || $_GET['qualimov']!='Y')
{
			$db->query="Select qualificacao_imov from imovel where cod_ppst='".$aProposta["cod_ppst"]."'";
			$db->query();
			if($db->qrcount>0)
			{
				$qualimov=$db->qrdata[0]['qualificacao_imov'];
			}
 
			if($qualimov!=''){
				echo $qualimov;
			}else{
				echo "<font color=\"#CC0000\"><b>Não foi inserida nenhuma qualificação para o Imóvel.</b></font>";
			}
	if(($cLOGIN->iLEVEL_USUA == TPUSER_JURIDICO && $aProposta["situacao_ppst"]<6) && $aProposta["dtaprovacao_ppst"]==''){		
?>
	<p class="alr">                
		<input type="image" name="QImov" id="QImov" class="im" src="images/buttons/bt_alterar.gif" onClick="return InserirQualificacaoImov();" >
	</p>
<?
	}

}
?>

        		</div>
				<div><img src="images/layout/subquadro_b.gif" alt="" /></div>
			</div>
		</div>
