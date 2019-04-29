<?php
include "./class/dbclasses.class.php";

$iREQ_AUT=1;
$aUSERS_PERM[] = TPUSER_ATENDENTE;
$aUSERS_PERM[] = TPUSER_DESPACHANTE;
$aUSERS_PERM[] = TPUSER_JURIDICO;
$aUSERS_PERM[] = TPUSER_ADMINISTRATIVO;
$aUSERS_PERM[] = TPUSER_USUARIOMASTER;
$aUSERS_PERM[] = TPUSER_ADMPREVI;

$pageTitle = "Lista de Participantes";
if($cLOGIN->iLEVEL_USUA!=''){

include "lib/header.inc.php";
  function formataD($data) {
		$dataTmp = "";
		if($data) {
		  $dataArray = split('[-\/\ ]',$data);
			$dataTmp = $dataArray[2].'/'.$dataArray[1].'/'.$dataArray[0];
		}
		return $dataTmp;
  }
  function formataF($valor,$desc=0) {
    // round
    $valor = str_replace('.',',',strval(round($valor,$desc)));
    $partes = split(',',$valor);
    $inteiro = '';
    $c = -1;
    for($i=strlen($partes[0]); $i >=0; $i--){
      if($c==3){ $inteiro = '.'.$inteiro; $c=0; }
      $inteiro = substr($partes[0],$i,1).$inteiro;
      $c++;
    }
    
    $output = $inteiro;
    
    if($desc > 0){
	    $fracao = substr($partes[1],0,$desc);
	    for($i=strlen($fracao); $i< $desc; $i++){
	      $fracao.='0';
	    }
	    $output .= ','.$fracao;
    }
    
    return $output;
  }

  function formataM($valor) {
    return formataF($valor,2);
  }
function formataMat($matricula) {
		$rgTmp = "";
		if ($matricula) {
		  $matricula = preg_replace("/\W/i","",$matricula);
		  $tam = strlen($matricula) - 1;
		  $tres=-1;
      for($i = $tam; $i >= 0; $i--){
        if($i==($tam-1)){ $rgTmp = '-'.$rgTmp; }
        if(($tres % 3 == 0)&&($tres > 0)){ $rgTmp = '.'.$rgTmp; }
        $rgTmp = substr($matricula, $i, 1).$rgTmp;
        $tres++;
      }
		}
		return $rgTmp;
  }
  function datacalculo($interval, $end_date, $start_date)
{
	// lowercase the interval string...
	$interval = strtolower($interval);
	
	//    set a couple of vars and do some conversions...
	list($end_month, $end_day, $end_year) = split("[/.,-]", $end_date);
	list($start_month, $start_day, $start_year) = split("[/.,-]", $start_date);
	
	//    convert our argument dates to Julian day counts
	$end_days = gregoriantojd($end_month, $end_day, $end_year);
	$start_days = gregoriantojd($start_month, $start_day, $start_year);
	
	// We also need to get the elapsed time since 00:00:00 today...that is
	// midnight, which will be added to the second, minute and hour counts for accuracy
	// first get the 24-hour time formatted without a leading zero on the hour
	// there will be NO correction here for the server's timezone...sorry!
	
	list($hours_today, $minutes_today, $seconds_today)= split(":", gmdate('G:i:s'));
	$seconds_add = (($hours_today * 3600) + ($minutes_today * 60) + $seconds_today);
	
	//    First the error checking...error printing is for debugging
	
	//  check for incomplete arguments
	if ((empty($interval)) || (empty($end_date)) || (empty($start_date)))
	{
	 print "<br /><b>Argument input error: all 3 arguments, INTERVAL, DATE and DATE are MANDATORY!</b>";
	 return -1;
	 exit;
	}
	//  check for incorrect interval arguments
	if (!ereg("[ymwdhnsa]", $interval) || strlen($interval) > 1)
	{
		print "<br /><b>Time interval input error: " . $interval . " is NOT a VALID INTERVAL!</b>";
		return -1;
		exit;
	}
	//  check for incorrect interval arguments
	if (!checkdate($end_month, $end_day, $end_year))
	{
		print "<br /><b>Erro de validação de data: " . $end_date . " não é uma data válida!</b>";
		return -1;
		exit;
	}
	//  check for incorrect interval arguments
	if (!checkdate($start_month, $start_day, $start_year))
	{
		print "<br /><b>Erro de validação de data: " . $start_date . " não é uma data válida!</b>";
		return -1;
		exit;
	}
	
	//    If we got here the arguments must be correct
	//    ...now, we will be counting in seconds, so we need to find the difference in seconds
	
	$days = ($end_days - $start_days);
	if ($days < 0)
		$days *= -1;
	
	$seconds = ($days * 86400) + $seconds_add;
	
	//setup our calculation vars
	$year_seconds = (31557600); //365.25 * 86400
	$month_seconds = (2630016); // 30.44 * 86400
	$week_seconds = (604800); // 7 * 86400
	$day_seconds = 86400;
	$hour_seconds = 3600;
	
	// switch on the interval arg
	switch($interval)
	{
		case 'y':     //  Years
			$diff = floor($days / 365.25);
			break;
	
		case 'm':     //  Months
			$diff = floor($days / 30.44);
			break;
	
		case 'w':     //  Weeks
			$diff = floor($days / 7);
			break;
	
		case 'd':     //  Days
			$diff = $days;
			break;
	
		case 'h':     //  Hours
			$diff = floor(($seconds / 3600) + $hours_today);
			break;
	
		case 'n':     //  Minutes
			$diff = floor(($seconds / 60) + $minutes_today);
			break;
	
		case 's':     //  Seconds
			$diff = floor(($seconds) + $seconds_today);
			break;
	
		case 'a':     
			// next days
			if ($day_seconds <= $seconds)
			{
				$buffer = floor($seconds / $day_seconds);
				$seconds -= ($buffer * $day_seconds);
				if (!empty($diff))
					$diff .= " ";
				if ($buffer > 1)
					$diff .= $buffer . " dias<br>";
				elseif ($buffer == 1)
					$diff .= $buffer . " dia<br>";
			}
	
				break;
	
		default:     //  Uh, oh....something unexpected happened???
			return -1;
			exit;
	}
	return $diff;
}
function calculo_expira($dt1,$dt2)
{
	$cdat=date("m,d,Y");
	$fdat=$dt2;
	$cdat_mes=substr($cdat,0,2);
	$cdat_dia=substr($cdat,3,2);
	$cdat_ano=substr($cdat,6,4);
	$cdata=($cdat_mes."/".$cdat_dia."/".$cdat_ano);
	$fdat_ano=substr($fdat,0,4);
	$fdat_mes=substr($fdat,5,2);
	$fdat_dia=substr($fdat,8,2);
	$fdata=($fdat_mes."/".$fdat_dia."/".$fdat_ano);
	$date_Now = $cdata;//$_POST[txdata1];
	$SelectedDate =$fdata; //$_POST[txdata2];
	if (strtotime($date_Now) > strtotime($SelectedDate))
	{
		$TotalDiff  = datacalculo("a", $date_Now, $SelectedDate);
	}
	elseif (strtotime($date_Now) < strtotime($SelectedDate))    //  the selected date must come after today???hmmm
	{
		$TotalDiff  = datacalculo("a", $SelectedDate, $date_Now);
	}
		// fix for any negative values...
	if ($yearDiff < 0) $yearDiff *= -1;
	if ($monDiff < 0) $monDiff *= -1;
	if ($dayDiff < 0) $dayDiff *= -1;
	if ($secDiff < 0) $secDiff *= -1;
	if ($hourDiff < 0) $hourDiff *= -1;
	// print out the results...
	$JDcount = gregoriantojd($selMonth, $selDay, $selYear);
	echo $TotalDiff;
}
function data_expira($data,$per)
{
	$data1=$data;
	$data_embarque = implode(preg_match("~\/~", $data1) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $data1) == 0 ? "-" : "/", $data1)));
	//Divide a string em um array com dia, mês e ano
	$partes=explode("/",$data1);
	//Período em dias que será adicionado(ou subtraindo)
	$periodo=$per;//n° de dias Ex: 180
	//Data modificada
	
	$data_embarque=date("Y-m-d",mktime(0,0,0,$partes[1] ,$partes[0] + $periodo ,$partes[2]));
	$datafinal= $data_embarque;
	//calculo_expira($data1,$datafinal);
	return $datafinal;
}

  ?><?php /*
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

<style type="text/css">
<!--
.style1 {
	font-size: 14px;
	font-weight: bold;
}
-->
</style>

<?php

		//$agenda = new agenda();
		
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
			$buscaAgenda = $agenda->getAgenda2();
			$utils = new utils();			
			//print_r($buscaAgenda);
			$buscaAgenda[0]['agendamento'] = $utils->formataDataBRA($buscaAgenda[0]['agendamento']);
		}
*/
?>

<form name="filtro" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
<script language="JavaScript" src="./js/diversos.js"></script>
	<br><b>Filtros</b>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
      <table cellpadding=0 cellspacing=5 border=0>
           <tr>
          <td align="right">Cód. Identificação:</td>
          <td align="left"><input type="text" name="filtro_mat" onKeyDown='return teclasMatricula(this,event);' onKeyUp='return mascaraMatricula(this,event);'  maxlength='12'  value="<?php echo $_POST["filtro_matricula"];?>" size="50"></td>
        </tr>
        <tr>
        	<td align="right">&nbsp;</td>
          <td align="left">
          	<input type="image" name="btFiltrar" id="btFiltrar" src="images/buttons/bt_filtrar.gif" value="Filtrar" class="im" />&nbsp;
          	<a href="lista_expiradas.php"><img src="images/buttons/bt_limparfiltros.gif" alt="Limpar Filtros" class="im" /></a>
          </td>
        </tr>
      </table>
	  </div>
			<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>

		<br>
		<?php
		//Limito a busqueda 
$TAMANHO_PAGINA = 100; 

//examino a página a mostrar e o inicio do registo a mostrar 
$pagina = $_GET["pagina"]; 
if (!$pagina) { 
   $inicio = 0; 
   $pagina=1; 
} 
else {
   $inicio = ($pagina - 1) * $TAMANHO_PAGINA; 
} 
//vejo o número total de campos que há na tabela com essa busqueda 
$ssql = "select * from listadenomes where status_pp='E' " . $criterio; 
$rs = mysql_query($ssql); 
$num_total_registos = mysql_num_rows($rs); 
//calculo o total de páginas 
$total_paginas = ceil($num_total_registos / $TAMANHO_PAGINA); 

echo "<b>Página ".$pagina." de ".$total_paginas."</b>";

//ponho o número de registos total, o tamanho de página e a página que se mostra 
//echo "Número de registos encontrados: " . $num_total_registos . "<br>"; 
//echo "Mostram-se páginas de " . $TAMANHO_PAGINA . " registos cada uma<br>"; 
//echo "A mostrar a página " . $pagina . " de " . $total_paginas . "<p>"; 


		?>
		<div class="quadroInterno">
			<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
			<div class="quadroInternoMeio"><p align="center" class="style1">Participantes Expirados</p>
			<?php
				$filtros = '';
				if(!empty($_POST["filtro_mat"])) {
					$f_prop_mat = mysql_real_escape_string(preg_replace('/\D/i','',$_POST["filtro_mat"]));
					$f_query_mat=" and id_lstn='".mysql_real_escape_string($f_prop_mat)."' ";
					$filtros .= ' [Matrícula:'.$_POST["filtro_mat"].'] ';
				}


				$cLOGIN->insert_log(1,1,'Visualização da Lista de propostas'.$filtros);
			$db->query="select 
								id_lstn,
								dtconvocacao
							from 
								listadenomes
							where 
								status_pp='E'	
							$f_query_mat LIMIT " . $inicio . "," . $TAMANHO_PAGINA."";
				$db->query();
				//echo $db->query;
				if($db->qrcount>0) {
				
					?>
					<div class="tListDiv listScroll" style="overflow:auto ">
						<table>
							<colgroup>
								<col width="100" />
								<col width="110"/>
								<col  width="70"/>
								<col width="70" />
								<col  width="50"/>
								<col width="50" />
								<col width="50" />
								
							</colgroup>
							<thead>
								<tr>
									<td align="center">Matrícula</td>
									<td align="center">Prazo para Expirar</td>
									<td align="center">Convocado em:</td>
									<td align="center">Expirado em:</td>
								</tr>
							</thead>
							<tbody>
							<?php
								$aux_loop = 0;
								$aux_id = 0;
							
								for($i=0; $i<$db->qrcount; $i++){
									$aux_loop++;
									$query = "SELECT * FROM usuario WHERE id_lstn='".$db->qrdata[$i]['id_lstn']."' LIMIT 1";
									$result =mysql_query($query);
									$registro = mysql_fetch_array($result, MYSQL_ASSOC);
									$linhas= mysql_num_rows($result);
									$aux_id = $db->qrdata[$i]['id_lstn'];
									if($linhas==0)
									{
										
										$d++;
									
									?>
										<tr class="tL<?php echo $i%2 ? "1" : "2"; ?>">
											<td align="center"><?php echo formataMat($db->qrdata[$i]['id_lstn']); ?></td>
											<td align="center"><?php  ?> 120 dias</td>
											<td align="center"><?php  echo formataD($db->qrdata[$i]['dtconvocacao']); ?> </td>
											<td align="center"><?php echo formataD(data_expira(formataD($db->qrdata[$i]['dtconvocacao']),120)); ?></td>
										</tr>
									<?php
									}
										
								}
							?>
							</tbody>
						</table>
					</div>
					<?php
				} else {
					echo("Nenhuma proposta encontrada.");
				}

			?>
  		</div>
			<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
		</div>
		<p align="center">
<?php					//mostro os diferentes índices das páginas, se é que há várias páginas 
if ($total_paginas > 1){ 
	
   $pagina_ant = $pagina - 1;	
   $pagina_prox = $pagina + 1;
	
   if($pagina > 1)
   	echo "<a href='lista_limites.php?pagina=" . $pagina_ant . "&criterio=" . $txt_criterio . "&d=".$d."'> << </a> ";
    
   for ($i=1;$i<=$total_paginas;$i++){ 
      if ($pagina == $i) 
         //se mostro o índice da página actual, não coloco link 
         echo $pagina . " "; 
      else 
         //se o índice não corresponde com a página mostrada actualmente, coloco o link para ir a essa página 
         echo "<a href='lista_limites.php?pagina=" . $i . "&criterio=" . $txt_criterio . "&d=".$d."'>" . $i . "</a> "; 
   }
   
   if($pagina != $total_paginas)
   	echo "<a href='lista_limites.php?pagina=" . $pagina_prox . "&criterio=" . $txt_criterio . "&d=".$d."'> >> </a> ";
   
} 
?></p> </form>
            <?php /*
            	if($aux_loop == 1) {
            ?>
                    <strong>Agenda</strong>
		    </p>
		    <br />
		    <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post" name="agenda">
            <table width="537" border="0">
              <tr>
                <td width="103">Participante</td>
                <td width="418"><label>
                  <input name="agenda_nome" type="text" id="agenda_nome" value="<?php echo $buscaAgenda[0]['nome'];?>" size="40" maxlength="40" />
                  <input type="hidden" name="ID_LSTN" id="ID_LSTN" value="<?php echo $db->qrdata[0]['id_lstn'];?>" />
                  <input type="hidden" name="agenda_atendente" id="agenda_atendente" value="<?php echo $cLOGIN->cUSUARIO;?>" />
                </label></td>
              </tr>
              <tr>
                <td>Agendamento</td>
                <td><input name="agenda_agendamento" type="text" id="agenda_agendamento" value="<?php echo $buscaAgenda[0]['agendamento']; ?>" /> 
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
                  <textarea name="agenda_historico" cols="90" rows="15" id="agenda_historico"><?php echo $buscaAgenda[0]['historico'];?>
                  </textarea>
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
            	}*/
            ?>            

	<table>
		<tr>
			<td width="176">
					<b>Total de Participantes:</b> <?php echo $num_total_registos;?>
			</td>
			<td width="540" align="right">
					<a href="lista_propostas.php"><img src="images/buttons/bt_voltar.gif"></a>
			</td>
		</tr>
	</table>
<?php
}else
{
	header("Location:restrita.php");
}

include "lib/footer.inc.php";
?>