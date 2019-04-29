<?php
$iREQ_AUT=1;
$aUSERS_PERM[]=4;

$pageTitle = "Transmissão de Arquivo";
include "lib/header.inc.php";

$oArquivo = new arquivo();

$aDadosArquivo909IN  = $oArquivo->getArquivo(ATHOSFILE909IN);
$aDadosArquivo906IN  = $oArquivo->getArquivo(ATHOSFILE906IN);
$aDadosArquivo906OUT = $oArquivo->getArquivo(ATHOSFILE906OUT);

$aDadosArquivo909INCompl  = $oArquivo->getArquivoConteudo($aDadosArquivo909IN[0]["cod_arqu"],$aDadosArquivo909IN[0]["ultimaremessa_arqu"]);
$aDadosArquivo906INCompl  = $oArquivo->getArquivoConteudo($aDadosArquivo906IN[0]["cod_arqu"],$aDadosArquivo906IN[0]["ultimaremessa_arqu"]);
$aDadosArquivo906OUTCompl = $oArquivo->getArquivoConteudo($aDadosArquivo906OUT[0]["cod_arqu"],$aDadosArquivo906OUT[0]["ultimaremessa_arqu"]);

$aEstatistica906 = $oArquivo->get906Estatistica($aDadosArquivo906OUT[0]["ultimaremessa_arqu"]);

$aDadosRegistros906 = $oArquivo->getArquivoRegistroRemessa($aDadosArquivo906OUTCompl[0]["cod_arqu"],$aDadosArquivo906IN[0]["ultimaremessa_arqu"]);

?>
<div class="quadroInterno">
	<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
	<div class="quadroInternoMeio">
		<p><b><u>Arquivo de Nomes (COFSP909.SAI)</u></b></p><br />
		<pre>
  Última remessa.....................: <? echo $aDadosArquivo909IN[0]["ultimaremessa_arqu"] ? str_pad($aDadosArquivo909IN[0]["ultimaremessa_arqu"],6,"0",STR_PAD_LEFT) : "Arquivo ainda não recebido"; ?> 
  Data da remessa....................: <? echo $aDadosArquivo909IN[0]["dtultimaremessa_arqu"] ? date("d/m/Y", strtotime($aDadosArquivo909IN[0]["dtultimaremessa_arqu"])) : "Arquivo ainda não recebido"; ?> 
  Registros processados com sucesso..: <? echo (int)$aDadosArquivo909INCompl[0]["regprocessados_arre"]; ?> 
  Registros processados com erro.....: <? echo (int)$aDadosArquivo909INCompl[0]["regerro_arre"]; ?> 
		</pre>

		
		<p><b><u>Arquivo de Envio de Propostas (COFSP906.ENT)</u></b></p><br />
		<pre>
  Última remessa.....................: <? echo $aDadosArquivo906OUT[0]["ultimaremessa_arqu"] ? str_pad($aDadosArquivo906OUT[0]["ultimaremessa_arqu"],6,"0",STR_PAD_LEFT) : "Arquivo ainda não enviado"; ?> 
  Data da remessa....................: <? echo $aDadosArquivo906OUT[0]["dtultimaremessa_arqu"] ? date("d/m/Y", strtotime($aDadosArquivo906OUT[0]["dtultimaremessa_arqu"])) : "Arquivo ainda não enviado"; ?> 
  Registros enviados.................: <? echo (int)$aEstatistica906[0]["registroerro"] + (int)$aEstatistica906[0]["registrosucesso"]; ?> 
		</pre>

		<p><b><u>Arquivo de Recebimento de Confirmação (COFSP906.SAI)</u></b></p><br />
		<pre>
  Última remessa.....................: <? echo $aDadosArquivo906IN[0]["ultimaremessa_arqu"] ? str_pad($aDadosArquivo906IN[0]["ultimaremessa_arqu"],6,"0",STR_PAD_LEFT) : "Arquivo ainda não recebido"; ?> 
  Data da remessa....................: <? echo $aDadosArquivo906IN[0]["dtultimaremessa_arqu"] ? date("d/m/Y", strtotime($aDadosArquivo906IN[0]["dtultimaremessa_arqu"])) : "Arquivo ainda não recebido"; ?> 
  Situação...........................: <? 
  
	switch ($aDadosArquivo906OUTCompl[0]["flgerro_arre"]) {
		case "S": 
			echo "ARQUIVO RECEBIDO COM ERRO DE ESTRUTURA"; 
		break;
		case "N": 
			echo "ARQUIVO RECEBIDO COM SUCESSO"; 
		break;
		case "R": 
			echo "ARQUIVO COM ERROS EM REGISTROS"; 
		break;
	}
  ?> 
  Confirmação dos registros..........: 
<?
    
	if (is_array($aDadosRegistros906) && @count($aDadosRegistros906) > 0) {
		foreach ($aDadosRegistros906 as $iDadoRegistro => $aDadoRegistro) {
			echo "     * Proposta: ".str_pad($aDadoRegistro["cod_ppst"],6,"0",STR_PAD_LEFT)." - Retorno: ".$aDadoRegistro["ocorrencia_arrg"]."\n";
		}
	}

    ?>
    </pre>
<br><br>
<table border="0">
	<tr>
		<td width="110"><b>Gerar Arquivo:</b>
		</td>
		<td width="597"><a href="cron/athosproc.php"><img src="images/buttons/bt_executar.GIF" width="98" height="21"></a>
		</td>
	</tr>
</table>	</div>
	
	<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>

<?php
include "lib/footer.inc.php";
?>