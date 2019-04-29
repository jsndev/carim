<?php




	/*	if(!$_POST){
		}*/
		if($_POST['qintq']=='salvar'){
			$db->query="Update intvquitante set qualificacao_intq='".$_POST['teste']."' where cod_ppst='".$aProposta["cod_ppst"]."'";
			$db->query();
			$db->query="Insert into historico (COD_PPST, DT_HIST, OBS_HIST, TIPO_HIST, COD_USUA) values ('".$aProposta["cod_ppst"]."',now(),'Qualificação do Interveniente Quitante inseridas.','1','".$cLOGIN->iID."')";
			$db->query();
		}
		?>
		<script>
		function SalvarQualificacaoIntq()
		{
			document.getElementById('qintq').value="salvar"
			document.getElementById('proposta').action += "#quadro_iq";
			return true;
		}
		function InserirQualificacaoIntq()
		{
			document.getElementById('proposta').action += "&qualIntq=Y#quadro_iq";
			return true;
		}

		</script>
		<div class="bloco_include" id="bloco_pagamento">
			<div class="bloco_titulo">Qualificação de Interveniente Quitante</div>
			<div class="quadroInterno">
				<div><img src="images/layout/subquadro_t.gif" alt="" /></div>
				<div class="quadroInternoMeio">
                <a name="quadro_iq"></a>
<?php 
if((($cLOGIN->iLEVEL_USUA == TPUSER_JURIDICO && $aProposta["situacao_ppst"]<=8)) && $_GET['qualIntq']=='Y'){ 
?>		
		<?php 
			$db->query="Select qualificacao_intq from intvquitante where cod_ppst='".$aProposta["cod_ppst"]."'";
			$db->query();
			if($db->qrcount>0)
			{
				$_POST['teste']=$db->qrdata[0]['qualificacao_intq'];
				$qualintq=$db->qrdata[0]['qualificacao_intq'];
			}
	
	
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
					
					<p align="right">
                    
					<input type="image" name="SvQIntq" id="SvQIntq" class="im" src="images/buttons/bt_salvar.gif" onClick="return SalvarQualificacaoIntq();" ></p>
					<input type="hidden" name="qintq" id="qintq" value="">
<?php
}elseif(($aProposta["dtaprovacao_ppst"]!='' && $aProposta["situacao_ppst"]>8) || $_GET['qualIntq']!='Y')
{
			$db->query="Select qualificacao_intq from intvquitante where cod_ppst='".$aProposta["cod_ppst"]."'";
			$db->query();
			if($db->qrcount>0)
			{
				$qualintq=$db->qrdata[0]['qualificacao_intq'];
			}
			if($qualintq!=''){
				echo $qualintq;
			}else{
				echo "<font color=\"#CC0000\"><b>Não foi inserida nenhuma qualificação para o Interveniente Quitante.</b></font>";
			}
	if(($cLOGIN->iLEVEL_USUA == TPUSER_JURIDICO && $aProposta["situacao_ppst"]<=8)){		
?>
        <p class="alr">                
            <input type="image" name="QIntq" id="QIntq" class="im" src="images/buttons/bt_alterar.gif" onClick="return InserirQualificacaoIntq();" >
        </p>
<?
	}
}
?>
            	</div>
				<div><img src="images/layout/subquadro_b.gif" alt="" /></div>
			</div>
		</div>
