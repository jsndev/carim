<?
include "./class/dbclasses.class.php";

$iREQ_AUT=1;
$aUSERS_PERM[] = TPUSER_ATENDENTE;
$aUSERS_PERM[] = TPUSER_DESPACHANTE;
$aUSERS_PERM[] = TPUSER_JURIDICO;
$aUSERS_PERM[] = TPUSER_ADMINISTRATIVO;
$aUSERS_PERM[] = TPUSER_USUARIOMASTER;
$aUSERS_PERM[] = TPUSER_ADMPREVI;

$pageTitle = "Ver Agenda";

?>

<p>
  <?

		$agenda = new agenda();
		$buscaAgenda = $agenda->getAgenda3();
		$utils = new utils();			
		//print_r($buscaAgenda);
		$buscaAgenda[0]['agendamento'] = $utils->formataDataBRA($buscaAgenda[0]['agendamento']);		
		
?>
<p>
    <strong>Agenda</strong></p>
</p>
<div class="quadroInterno"><div class="quadroInternoMeio">
<form action="<?=$_SERVER["PHP_SELF"];?>" method="post" name="agenda">
            <table width="371" border="0">
              <tr>
                <td width="107"><strong>Nome</strong></td>
              <td width="254" align="left">
                  <?=$buscaAgenda[0]['nome'];?>                </td>
              </tr>
              <tr>
                <td><strong>Agendamento</strong></td>
                <td align="left"><?=$buscaAgenda[0]['agendamento'];?></td>
              </tr>
              <tr>
                <td><strong><br />
                Hist&oacute;rico</strong></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="2" align="left" valign="top"><p><?=nl2br($buscaAgenda[0]['historico']);?></p>
                  <p>
                    <label></label>
</p></td>
              </tr>
            </table>  
    </form>
            </div>
  </div>
