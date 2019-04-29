		</div>
		<div id="lFimQuadro"><img src="images/layout/rodape_quadro.gif" alt=" " /></div>
	</div>
<?php
if($cLOGIN->bOK == 1) {
?>
	<div id="boxUserInfo">
		<p id="boxUserInfoName">Logado como: <b><?php echo $cLOGIN->cUSUARIO; ?></b></p>
		<p id="boxUserInfoSair"><a href="desloga.php"><img src="images/layout/bot_sair.gif" alt="Sair" /></a></p>
	</div>
<?php
}
?>
	<div id="lRodape"><img src="images/rodape.jpg" alt=" " /></div>
</div>
<div class="menuMask" id="menuMask" onmouseover="menuIn('');"><img id="menuMaskImg" height="400" src="images/layout/transp.gif" width="778" border="0" alt=" " /></div>
<?php
/* FINANCIAMENTO */
if($cLOGIN->bOK == 1) {
?>
	<div class="subMenu" id="mn_financiamento_sub">
		<table cellSpacing="0" cellPadding="0" border="0">
			<colgroup>
				<col width="120"></col>
			</colgroup>
			<tr>
				<td><img height="1" src="images/layout/transp.gif" width="2" alt=" " /></td>
				<td style="background: url(images/layout/sub_menu_borda_dir.gif)" rowspan="4"><img height="1" src="images/layout/transp.gif" width="2" alt=" " /></td>
			</tr>
			<?php
				if ($cLOGIN->iLEVEL_USUA==1) {
				?>
					<tr>
						<td class="subMenuItemOut cursorMao cBlue" onmouseover="subMenuIn(this);" onclick="goPage('proposta.php');"
							onmouseout="subMenuOut(this);"><img style="vertical-align: middle" src="images/sub_ico/dummy.gif" border="0" alt=" " />Proposta</td>
					</tr>
				<?php
				}elseif($cLOGIN->iLEVEL_USUA==2){
					?>
					<tr>
						<td class="subMenuItemOut cursorMao cBlue" onmouseover="subMenuIn(this);" onclick="goPage('lista_propostas.php');"
							onmouseout="subMenuOut(this);"><nobr><img style="vertical-align: middle" src="images/sub_ico/dummy.gif" border="0" alt=" " />Listar Propostas</td>
					</tr>
					<?php
				}elseif($cLOGIN->iLEVEL_USUA==3){
					?>
					<tr>
						<td class="subMenuItemOut cursorMao cBlue" onmouseover="subMenuIn(this);" onclick="goPage('lista_propostas.php');"
							onmouseout="subMenuOut(this);"><nobr><img style="vertical-align: middle" src="images/sub_ico/dummy.gif" border="0" alt=" " />Listar Propostas</td>
					</tr>
					<?php
				}elseif($cLOGIN->iLEVEL_USUA==6){
					?>
					<tr>
						<td class="subMenuItemOut cursorMao cBlue" onmouseover="subMenuIn(this);" onclick="goPage('lista_propostas.php');"
							onmouseout="subMenuOut(this);"><nobr><img style="vertical-align: middle" src="images/sub_ico/dummy.gif" border="0" alt=" " />Listar Propostas</td>
					</tr>
					<?php
				}elseif($cLOGIN->iLEVEL_USUA==7){
					?>
					<tr>
					<td class="subMenuItemOut cursorMao cBlue" onmouseover="subMenuIn(this);" onclick="goPage('lista_propostas.php');"
							onmouseout="subMenuOut(this);"><nobr><img style="vertical-align: middle" src="images/sub_ico/dummy.gif" border="0" alt=" " />Listar Propostas</td>
					</tr>
					<?php
				}elseif($cLOGIN->iLEVEL_USUA==8){
					?>
                    <tr>
						<td class="subMenuItemOut cursorMao cBlue" onmouseover="subMenuIn(this);" onclick="goPage('lista_assistente.php');"
							onmouseout="subMenuOut(this);"><nobr><img style="vertical-align: middle" src="images/sub_ico/dummy.gif" border="0" alt=" " />Lista de Fases</td>
					</tr>
					<tr>
						<td class="subMenuItemOut cursorMao cBlue" onmouseover="subMenuIn(this);" onclick="goPage('alterar_propostas.php');"
							onmouseout="subMenuOut(this);"><nobr><img style="vertical-align: middle" src="images/sub_ico/dummy.gif" border="0" alt=" " />Alterar Dados cadastrais</td>
					</tr>
					<?php
				}elseif($cLOGIN->iLEVEL_USUA==9){
					?>
					<tr>
						<td class="subMenuItemOut cursorMao cBlue" onmouseover="subMenuIn(this);" onclick="goPage('lista_propostas.php');"
							onmouseout="subMenuOut(this);"><nobr><img style="vertical-align: middle" src="images/sub_ico/dummy.gif" border="0" alt=" " />Listar Propostas</td>
					</tr>
					<?php
    		}
			?>
		</table>
	</div>
	<?php
}
/* GERENCIAMENTO */
if($cLOGIN->bOK == 1) {
	if($cLOGIN->iLEVEL_USUA==4){
?>
	<div class="subMenu" id="mn_gerenciamento_sub">
		<table cellSpacing="0" cellPadding="0" border="0">
			<colgroup>
				<col width="150"></col>
			</colgroup>
			<tr>
				<td><img height="1" src="images/layout/transp.gif" width="2" alt=" " /></td>
				<td style="background: url('images/layout/sub_menu_borda_dir.gif'); background-repeat: repeat-y;" rowspan="70"><img height="1" src="images/layout/transp.gif" width="2" alt=" " /></td>
			</tr>
			<tr>
				<td class="subMenuItemOut cursorMao cBlue" onmouseover="subMenuIn(this);" onclick="goPage('adm_categorias.php');"
					onmouseout="subMenuOut(this);"><img style="vertical-align: middle" src="images/sub_ico/dummy.gif" border="0" alt=" " />Categorias</td>
			</tr>
			<tr>
				<td class="subMenuItemOut cursorMao cBlue" onmouseover="subMenuIn(this);" onclick="goPage('adm_informativos.php');"
					onmouseout="subMenuOut(this);"><img style="vertical-align: middle" src="images/sub_ico/dummy.gif" border="0" alt=" " />Informativos</td>
			</tr>
			<tr>
				<td class="subMenuItemOut cursorMao cBlue" onmouseover="subMenuIn(this);" onclick="goPage('adm_templates.php');"
					onmouseout="subMenuOut(this);"><img style="vertical-align: middle" src="images/sub_ico/dummy.gif" border="0" alt=" " />Templates</td>
			</tr>
			<tr>
				<td class="subMenuItemOut cursorMao cBlue" onmouseover="subMenuIn(this);" onclick="goPage('adm_conteudos.php');"
					onmouseout="subMenuOut(this);"><img style="vertical-align: middle" src="images/sub_ico/dummy.gif" border="0" alt=" " />Conte�dos</td>
			</tr>
			<tr>
				<td class="subMenuItemOut cursorMao cBlue" onmouseover="subMenuIn(this);" onclick="goPage('adm_regioes.php');"
					onmouseout="subMenuOut(this);"><img style="vertical-align: middle" src="images/sub_ico/dummy.gif" border="0" alt=" " />Regi�es</td>
			</tr>
<?php/*
			<tr>
				<td class="subMenuItemOut cursorMao cBlue" onmouseover="subMenuIn(this);" onclick="goPage('adm_entidades.php');"
					onmouseout="subMenuOut(this);"><img style="vertical-align: middle" src="images/sub_ico/dummy.gif" border="0" alt=" " />Entidades</td>
			</tr>
			
			<tr>
				<td class="subMenuItemOut cursorMao cBlue" onmouseover="subMenuIn(this);" onclick="goPage('adm_documentos.php');"
					onmouseout="subMenuOut(this);"><img style="vertical-align: middle" src="images/sub_ico/dummy.gif" border="0" alt=" " />Documentos</td>
			</tr>
*/?>
			<tr>
				<td class="subMenuItemOut cursorMao cBlue" onmouseover="subMenuIn(this);" onclick="goPage('adm_relatorio.php');"
					onmouseout="subMenuOut(this);"><img style="vertical-align: middle" src="images/sub_ico/dummy.gif" border="0" alt=" " />Relat�rio</td>
			</tr>
			<tr>
				<td class="subMenuItemOut cursorMao cBlue" onmouseover="subMenuIn(this);" onclick="goPage('adm_despachantes.php');"
					onmouseout="subMenuOut(this);"><img style="vertical-align: middle" src="images/sub_ico/dummy.gif" border="0" alt=" " />Despachantes</td>
			</tr>
			
<?php/*
			<tr>
				<td class="subMenuItemOut cursorMao cBlue" onmouseover="subMenuIn(this);" onclick=""
					onmouseout="subMenuOut(this);"><img style="vertical-align: middle" src="images/sub_ico/dummy.gif" border="0" alt=" " />Avaliadores</td>
			</tr>
*/?>
			<tr>
				<td class="subMenuItemOut cursorMao cBlue" onmouseover="subMenuIn(this);" onclick="goPage('adm_taxas.php');"
					onmouseout="subMenuOut(this);"><img style="vertical-align: middle" src="images/sub_ico/dummy.gif" border="0" alt=" " />Taxas</td>
			</tr>
			<tr>
				<td class="subMenuItemOut cursorMao cBlue" onmouseover="subMenuIn(this);" onclick="goPage('adm_advogados.php');"
					onmouseout="subMenuOut(this);"><img style="vertical-align: middle" src="images/sub_ico/dummy.gif" border="0" alt=" " />Advogados</td>
			</tr>
			<tr>
				<td class="subMenuItemOut cursorMao cBlue" onmouseover="subMenuIn(this);" onclick="goPage('adm_atendentes.php');"
					onmouseout="subMenuOut(this);"><img style="vertical-align: middle" src="images/sub_ico/dummy.gif" border="0" alt=" " />Atendentes</td>
			</tr>
            			<tr>
				<td class="subMenuItemOut cursorMao cBlue" onmouseover="subMenuIn(this);" onclick="goPage('adm_proponentes.php');"
					onmouseout="subMenuOut(this);"><img style="vertical-align: middle" src="images/sub_ico/dummy.gif" border="0" alt=" " />Proponentes</td>
			</tr>
			<tr>
				<td class="subMenuItemOut cursorMao cBlue" onmouseover="subMenuIn(this);" onclick="goPage('adm_assistentes.php');"
					onmouseout="subMenuOut(this);"><img style="vertical-align: middle" src="images/sub_ico/dummy.gif" border="0" alt=" " />Assis. Administrativos</td>
			</tr>
			<tr>
				<td class="subMenuItemOut cursorMao cBlue" onmouseover="subMenuIn(this);" onclick="goPage('adm_administradores.php');"
					onmouseout="subMenuOut(this);"><img style="vertical-align: middle" src="images/sub_ico/dummy.gif" border="0" alt=" " />Administradores</td>
			</tr>
			<tr>
				<td class="subMenuItemOut cursorMao cBlue" onmouseover="subMenuIn(this);" onclick="goPage('adm_contratantes.php');"
					onmouseout="subMenuOut(this);"><img style="vertical-align: middle" src="images/sub_ico/dummy.gif" border="0" alt=" " />Contratantes</td>
			</tr>
			<tr>
				<td class="subMenuItemOut cursorMao cBlue" onmouseover="subMenuIn(this);" onclick="goPage('userfinanceiro.php');"
					onmouseout="subMenuOut(this);"><img style="vertical-align: middle" src="images/sub_ico/dummy.gif" border="0" alt=" " />Financeiros</td>
			</tr>
			<tr>
				<td class="subMenuItemOut cursorMao cBlue" onmouseover="subMenuIn(this);" onclick="goPage('adm_municipios.php');"
					onmouseout="subMenuOut(this);"><img style="vertical-align: middle" src="images/sub_ico/dummy.gif" border="0" alt=" " />Munic�pios</td>
			</tr>

			<tr>
				<td class="subMenuItemOut cursorMao cBlue" onmouseover="subMenuIn(this);" onclick="goPage('adm_minutas.php');"
					onmouseout="subMenuOut(this);"><img style="vertical-align: middle" src="images/sub_ico/dummy.gif" border="0" alt=" " />Minuta de contrato</td>
			</tr>
			<tr>
				<td class="subMenuItemOut cursorMao cBlue" onmouseover="subMenuIn(this);" onclick="goPage('adm_monitarquivos.php');"
					onmouseout="subMenuOut(this);"><img style="vertical-align: middle" src="images/sub_ico/dummy.gif" border="0" alt=" " />Arquivos Batch</td>
			</tr>
		</table>
	</div>
<?php
	}
}
/* FERRAMENTAS */
if(!($cLOGIN->bOK == 1 && ($cLOGIN->iLEVEL_USUA==3 || $cLOGIN->iLEVEL_USUA==4 || $cLOGIN->iLEVEL_USUA==9))) {
	?>
	<div class="subMenu" id="mn_ferramentas_sub">
		<table cellSpacing="0" cellPadding="0" border="0">
			<colgroup>
				<col width="120"></col>
			</colgroup>
			<tr>
				<td><img height="1" src="images/layout/transp.gif" width="2" alt=" " /></td>
				<td style="background: url(images/layout/sub_menu_borda_dir.gif)" rowspan="7"><img height="1" src="images/layout/transp.gif" width="2" alt=" " /></td>
			</tr>
			<?php
				if(!($cLOGIN->bOK==1 && $cLOGIN->iLEVEL_USUA>1)){
			?>
			<tr>
				<td class="subMenuItemOut cursorMao cBlue" onmouseover="subMenuIn(this);" onclick="goPage('simulador.php');"
					onmouseout="subMenuOut(this);"><img style="vertical-align: middle" src="images/sub_ico/dummy.gif" border="0" alt=" " />Simulador</td>
			</tr>
			<tr>
				<td class="subMenuItemOut cursorMao cBlue" onmouseover="subMenuIn(this);" onclick="goPage('contato.php');"
					onmouseout="subMenuOut(this);"><img style="vertical-align: middle" src="images/sub_ico/dummy.gif" border="0" alt=" " />Fale Conosco</td>
			</tr>
			<?php
				}
			?>
			<tr>
				<td class="subMenuItemOut cursorMao cBlue" onmouseover="subMenuIn(this);" onclick="openChat(<?php echo $cLOGIN->iLEVEL_USUA;?>);" onmouseout="subMenuOut(this);"><img style="vertical-align: middle" src="images/sub_ico/dummy.gif" border="0" alt=" " />Chat</td>
			</tr>
			<tr>
				<td class="subMenuItemOut cursorMao cBlue" onmouseover="subMenuIn(this);" onclick="goPage('cadastro_bairro.php');" onmouseout="subMenuOut(this);">
					<img style="vertical-align: middle" src="images/sub_ico/dummy.gif" border="0" alt=" " />Bairro (Add)</td>
			</tr>
		</table>
	</div>
	<?php
}
?>
<div class="mainMenu" id="mainMenu">
	<table cellSpacing="0" cellPadding="0" border="0" align="right">
		<tr>
			<td class="cursorMao" id="mn_home" onmouseover="menuIn(this);" onclick="goPage('index.php');">
				<img src="images/buttons/bot_home_out.gif" border="0" alt="Home" />
			</td>
<?php
if( $cLOGIN->bOK == 1 ) {
	if( ($cLOGIN->iLEVEL_USUA<=3) || ($cLOGIN->iLEVEL_USUA>=6 && $cLOGIN->iLEVEL_USUA<=9)){
?>
			<td class="cursorMao" id="mn_financiamento" onmouseover="menuIn(this);" onclick="return false;">
				<img src="images/buttons/bot_financiamento_out.gif" border="0" alt="Financiamento" />
			</td>
<?php
	}
}
if($cLOGIN->bOK == 1) {
	if($cLOGIN->iLEVEL_USUA==4){
?>
			<td class="cursorMao" id="mn_gerenciamento" onmouseover="menuIn(this);" onclick="return false;">
				<img src="images/buttons/bot_gerenciamento_out.gif" border="0" alt="Gerenciamento" />
			</td>
<?php
	}
}

if(!($cLOGIN->bOK == 1 && ($cLOGIN->iLEVEL_USUA==3 || $cLOGIN->iLEVEL_USUA==4 || $cLOGIN->iLEVEL_USUA==9))) {
?>
			<td class="cursorMao" id="mn_ferramentas" onmouseover="menuIn(this);" onclick="return false;">
				<img src="images/buttons/bot_ferramentas_out.gif" border="0" alt="Ferramentas" />
			</td>
<?php
}
?>
			<td class="cursorMao" id="mn_help" onmouseover="menuIn(this);" onclick="goPage('ajuda.php');">
				<img src="images/buttons/bot_help_out.gif" border="0" alt="Ajuda" />
			</td>
		</tr>
	</table>
</div>
<?php/*--- <a href="matricula_erro.php">erro</a>*/?>

<div style="display:block;">
<?php
	/* D E B U G ----------------------------------------------------------------- * /
		print '<br /><br />';
		print '<hr />acaoProposta: <b>'.$acaoProposta.'</b>';
		//print '<hr /><pre>SESSION: '; print_r($_SESSION); print '</pre>';
		//print '<hr /><pre>GET: '; print_r($_GET); print '</pre>';
		print '<hr /><pre>POST: '; print_r($_POST); print '</pre>';
		print '<hr /><pre>aProposta: '; print_r($aProposta); print '</pre>';
		//print '<hr /><pre>cLOGIN: '; print_r($cLOGIN); print '</pre>';
		//print '<hr /><pre>obrig[N]: '; print_r($obrigatorio['N']); print '</pre>';
		//print '<hr />'; utils::printArray($__EXECQUERYS);
		print '<hr />'.$cLOGIN->iLEVEL_USUA;
	/* --------------------------------------------------------------------------- */
	//print 'ini: '.$tmpMT_ini.'<br>';
	//print 'fim: '.$tmpMT_fim.'<hr>';
	 
	$aIni = explode(' ',$tmpMT_ini);
	$aFim = explode(' ',$tmpMT_fim);
	
	$tmpMT_ini = $aIni[1]+$aIni[0];
	$tmpMT_fim = $aFim[1]+$aFim[0];
	
	//print 'ini: '.$tmpMT_ini.'<br>';
	//print 'fim: '.$tmpMT_fim.'<hr>';

	//print 'tempo: '.($tmpMT_fim - $tmpMT_ini).'<hr>';
?>
</div>
</body>
</html>