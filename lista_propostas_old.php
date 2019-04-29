<?php
include "class/dbclasses.class.php";

$iREQ_AUT=1;
$aUSERS_PERM[] = TPUSER_ATENDENTE;
$aUSERS_PERM[] = TPUSER_DESPACHANTE;
$aUSERS_PERM[] = TPUSER_JURIDICO;
$aUSERS_PERM[] = TPUSER_ADMINISTRATIVO;
$aUSERS_PERM[] = TPUSER_USUARIOMASTER;
$aUSERS_PERM[] = TPUSER_ADMPREVI;

$pageTitle = "Propostas";
include "lib/header.inc.php";

if($_GET['pagina'])
	$pagina_atual = $_GET['pagina'];
else
	$pagina_atual = 1;

?>
<script language="JavaScript" src="./js/diversos.js"></script>
<script language="JavaScript" src="js/CalendarPopup.js"></script>
	<SCRIPT LANGUAGE="JavaScript">
	var cal = new CalendarPopup("testdiv1");
	cal.setMonthNames('Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro');
	cal.setDayHeaders('D','S','T','Q','Q','S','S');
	cal.setWeekStartDay(1);
	cal.setTodayText("Hoje");
	</SCRIPT>
<SCRIPT LANGUAGE="JavaScript">document.write(getCalendarStyles());</SCRIPT>

<DIV ID="testdiv1" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></DIV>

<p>
  <?php

		$agenda = new agenda();
		
		//caso não exista item na agenda, insre
		
		
		//inserir - quando tiver id
		if($_POST['ID_LSTN'])
		{		
			if($agenda->insert())
				echo 'Agenda inserida com sucesso.';
		}
		//exibir
		else
		{
			$buscaAgenda = $agenda->getAgenda();
			$utils = new utils();			
			//print_r($buscaAgenda);
			$buscaAgenda[0]['agendamento'] = $utils->formataDataBRA($buscaAgenda[0]['agendamento']);
		}
	

?>
</p>
<p><b>Agenda</b></p>
<table width="664" border="0">
  <tr>
    <td width="15%" bgcolor="#ECEEEB">Participante</td>
    <td width="12%" bgcolor="#ECEEEB">Cod. Ident. </td>
    <td width="11%" bgcolor="#ECEEEB">Data</td>
    <td width="21%" bgcolor="#ECEEEB">Atendente</td>
    <td width="23%" bgcolor="#ECEEEB">Proposta</td>
  </tr>
  
  <?php
  	$oAgenda = new agenda();
	
	$oAgenda->Conferir();
	
	if(isset($_POST['vend_flag']))
		$dia = $oAgenda->listarVendedor();
	else
		$dia = $oAgenda->getAgendaDia();
		
	
	$n = 0;
	
	while(isset($dia[$n])){
	
		if($dia[$n]['COD_PPST'] == 0)
			$dia[$n]['COD_PPST'] = 'Não Cadastrada';
			
		$dataTmp = $utils->formataDataBRA($dia[$n]['agendamento']);
	
	$dataTmp_at = date("d/m/y");
    $var_dia_at = substr($dataTmp_at, 0, 2); // retorna 13;
    $var_mes_at = substr($dataTmp_at, 3, 2); // retorna 04;
    $var_ano_at = substr($dataTmp_at, 6, 4); // retorna 04;
	$timestamp_at = mktime(0, 0, 0, $var_mes_at, $var_dia_at, $var_ano_at);

    $var_dia = substr($dataTmp, 0, 2); // retorna 13;
    $var_mes = substr($dataTmp, 3, 2); // retorna 04;
    $var_ano = substr($dataTmp, 6, 4); // retorna 04;
	$timestamp = mktime(0, 0, 0, $var_mes, $var_dia, $var_ano);
	
	//significa que ainda não foi
	if($timestamp > $timestamp_at)
		$timestamp_res = "não foi";
	else
		$timestamp_res = "já foi";	
	  	
	
  ?>
  
  <tr>
    <td><a href="ver_agenda.php?id=<?=$dia[$n]['ID_LSTN']; ?>" target="_blank"><?=$dia[$n]['nome']; ?></a></td>
    <td><?=$utils->formataMatricula($dia[$n]['ID_LSTN']); ?></td>
    <td><?=$utils->formataDataBRA($dia[$n]['agendamento']); ?></td>
    <td><?=$dia[$n]['atendente']; ?></td>
    <td><?=$dia[$n]['COD_PPST']; ?></td>
  </tr>
  
  <?php
  	$n++;
	
  	}
  ?>
</table>
<form id="form1" name="form1" method="post" action="">
  <label>  
  <select name="agenda_atendente" id="agenda_atendente">
  <?php
  	$n = 0;  
  	$atendentes = $oAgenda->listarAtendentes();
	
	while(isset($atendentes[$n])){
		
		echo '"<option value="';
		echo $atendentes[$n][NOME_USUA];
		echo '">';
		echo $atendentes[$n][NOME_USUA];
		echo '</option>';
		
		$n++;	
	}
  ?>
  
  </select>
  
  <br />
  <input type="submit" name="button2" id="button2" value="Exibir todos itens da agenda" />
  </label>
  <input name="vend_flag" type="hidden" id="vend_flag" value="1" />
</form>
<p>&nbsp;</p>
<form name="filtro" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
	<br><b>Filtros</b>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
      <table cellpadding=0 cellspacing=5 border=0>
        <tr>
          <td align="right">Participante:</td>
          <td align="left"><input type="text" name="filtro_nome" value="<?php echo $_POST["filtro_nome"];?>" size="50"></td>
        </tr>
        <tr>
          <td align="right">Cód. Identificação:</td>
          <td align="left"><input type="text" name="filtro_matricula" onKeyDown='return teclasMatricula(this,event);' onKeyUp='return mascaraMatricula(this,event);'  maxlength='12'  value="<?php echo $_POST["filtro_matricula"];?>" size="50"></td>
        </tr>
        <tr>
          <td align="right">CPF:</td>
          <td align="left"><input type="text" name="filtro_cpf" value="<?php echo $_POST["filtro_cpf"];?>" size="50" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraCPF(this,event);" maxlength="14"></td>
        </tr>
        <tr>
          <td align="right">Vendedor:</td>
          <td align="left"><input type="text" name="filtro_vendedor" value="<?php echo $_POST["filtro_vendedor"];?>" size="50"></td>
        </tr>
                <tr>
          <td align="right">Responsável de:</td>
          <td align="left">
<input type="radio" name="filtro_locresp" class="rd" value="S" <?=((isset($_POST["filtro_locresp"])) AND $_POST["filtro_locresp"]=='S')?'checked':'';?> /> SP &nbsp;&nbsp;&nbsp; <input type="radio" name="filtro_locresp" value="C" class="rd" <?=((isset($_POST["filtro_locresp"])) AND $_POST["filtro_locresp"]=='C')?'checked':'';?> /> C
          </td>
        </tr>
        <tr>
        	<td align="right">&nbsp;</td>
          <td align="left">
          	<input type="image" name="btFiltrar" id="btFiltrar" src="images/buttons/bt_filtrar.gif" value="Filtrar" class="im" />&nbsp;
          	<a href="lista_propostas.php"><img src="images/buttons/bt_limparfiltros.gif" alt="Limpar Filtros" class="im" /></a>
          </td>
        </tr>
      </table>
			</div>
			<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
		</div>
</form>
		<br><b>Propostas</b>
		<div class="quadroInterno">
			<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		  <div class="quadroInternoMeio">
			<?php
				$oProposta = new proposta();
				$aProposta = $oProposta->getListaProposta(false, true);
				$pagginas = $oProposta->getPaginasProposta();
				
				if($oProposta->_propostas > 1)
					$proposta_plural = "propostas";
				else
					$proposta_plural = "proposta";

				if (is_array($aProposta) && @count($aProposta) > 0) {
					?>
					<div class="tListDiv listScroll">
					<?php echo "Página $pagina_atual de $pagginas, total de $oProposta->_propostas $proposta_plural"; ?>
						<table style="width:687px;">
							<colgroup>
								<col width="70" /><col /><col width="90" /><col width="100" /><col /><col width="50" />
							</colgroup>
							<thead>
								<tr>
									<td class="alc">Data</td>
									<td class="alc">Proponente</td>
									<td class="alc">C&oacute;d. Ident. </td>
									<td class="alc">CPF</td>
									<td class="alc">Situação</td>
									<td></td>
								</tr>
							</thead>
							<tbody>
							<?php
								$i=0;
								foreach($aProposta as $kLista=>$vLista){
									$i++;
									$rowspan = 1;
									foreach($vLista["proponentes"] as $kProp=>$vProp){
										?>
											<tr class="tL<?php echo $i%2 ? "1" : "2"; ?>">
												<?php if($kProp==0){ $rowspan = count($vLista["proponentes"]); } ?>
												<?php if($kProp==0){?><td class="alc" rowspan="<?php echo $rowspan;?>"><?php echo $utils->formataDataBRA($vLista["data_ppst"]);?></td><?php } ?>
												<td>
												<?php
												$dtppst=str_replace("-","",$vLista["data_ppst"]);
												if($dtppst<20080707){
													 echo "<font color='#CC0000'><i>".$vProp["usuario"][0]["nome_usua"]."</i></font>";
												}else{
													 echo $vProp["usuario"][0]["nome_usua"];
												}?></td>
												<td class="alr"><?php echo $utils->formataMatricula($vProp["usuario"][0]["id_lstn"]);?></td>
												<td class="alr"><?php echo $utils->formataCPF($vProp["cpf_ppnt"]);?></td>
												<?php if($kProp==0){?><td class="alc" rowspan="<?php echo $rowspan;?>"><?php echo $aSIT_PPST[$vLista["situacao_ppst"]];?></td><?php } ?>
												<?php if($kProp==0){?><td class="alc" rowspan="<?php echo $rowspan;?>"><a href='proposta.php?cod_proposta=<?php echo $vLista['cod_ppst'];?>'>abrir</a></td><?php } ?>
											</tr>
										<?php
									}
								}
							?>
							</tbody>
						</table>
					</div>
					<p align="center">
					<?php
						$oProposta->geraBarraProposta();
					?>
                    <p>&nbsp;
		    </p>
            <?php
            	if($oProposta->_propostas == 1) {
            ?>
                    <strong>Agenda</strong>
		    </p>
		    <br />
		    <form action="<?=$_SERVER["PHP_SELF"];?>" method="post" name="agenda">
            <table width="537" border="0">
              <tr>
                <td width="103">Participante</td>
              <td width="418"><label>
                  <input name="agenda_nome" type="text" id="agenda_nome" value="<?=$vProp["usuario"][0]["nome_usua"];?>" size="40" maxlength="40" />
                  <input type="hidden" name="ID_LSTN" id="ID_LSTN" value="<?=$vProp["usuario"][0]["id_lstn"];?>" />
                  <input type="hidden" name="agenda_atendente" id="agenda_atendente" value="<?=$cLOGIN->cUSUARIO;?>" />
                </label></td>
              </tr>
              <tr>
                <td>Agendamento</td>
                <td><input type="text" name="agenda_agendamento" id="agenda_agendamento" value="<?=$buscaAgenda[0]['agendamento'];?>" /> 
                  <A HREF="#"
   onClick="cal.select(document.forms['agenda'].agenda_agendamento,'anchor1','dd/MM/yyyy'); return false;"
   NAME="anchor1" ID="anchor1">calend&aacute;rio</A></td>
              </tr>
              <tr>
                <td>Hist&oacute;rico</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="2"><p>
                  <textarea name="agenda_historico" cols="100" rows="4" id="agenda_historico"><?=$buscaAgenda[0]['historico'];?></textarea>
                </p>
                  <p>
                    <label>
                    <input type="submit" name="button" id="button" value="Cadastrar na Agenda" />
                    </label>
</p></td>
              </tr>
            </table>  
            </form>
            <?php
            	}
            ?>            
		    <br />
	        </p>

	        <?php
				} else {
					echo("Nenhuma proposta encontrada.");
				}
			?>
  		    </div>
			<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
		</div>
<table>
		<tr>
			<td>
					<a href="lista_limites.php"><img src="images/buttons/bt_prop_sem.png"></a>
			</td>
			<td>
					<a href="lista_expiradas.php"><img src="images/buttons/bt_prop_exp.png"></a>
			</td>
			<td>
					<a href="lista_remetidas.php"><img src="images/buttons/bt_prop_rem.png"></a>
			</td>
<?php
			if($cLOGIN->iLEVEL_USUA == TPUSER_ATENDENTE){?>
			<td>
					<a href="asdmt_lista_propccb.php"><img src="images/buttons/contratos_emitir.gif"></a>
			</td>
			<?php
			}
			?>
		</tr>
	</table>
<?php
include "lib/footer.inc.php";
?>