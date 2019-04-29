<?php
$iREQ_AUT=1;
$aUSERS_PERM[]=4;
ob_start();

$pageTitle = "Alterar Despachante";
include "lib/header.inc.php";

if ((int)$_GET["cod_usua"] > 0) {
	ob_end_flush();
} else {
	ob_end_clean();
	header("Location: adm_despachantes.php");
	exit();
}

$oUsuario = new usuario();
$oRegiao = new regiao();
$mensagem = new mensagens();
$oDespachante = new despachante();

$pageAction = $crypt->decrypt($_POST["ac"]);

$dadosUsuario = $oUsuario->getUsuario($_GET["cod_usua"]);
$_POST["email"] = $dadosUsuario[0]["email_usua"];

if ($pageAction == "alterar") {
	$oUsuario->beginTransaction();
	mt_srand(mktime());
	$sTmpPassword = substr(md5(mt_rand(0,mktime())),0,8);
	
	$dadosUsuario[0]["nome_usua"] = $_POST["nome"];
	$dadosUsuario[0]["flgstatus_usua"] = (string)($_POST["ativo"] == "s" ? "1" : "0");
	
	$bInsercao = $oUsuario->updUsuario($dadosUsuario[0]);
	if ($oUsuario->getErrNo() == 0) {
		$bErroRegiao = false;
		$oUsuario->delRegiaoDespachante($_GET["cod_usua"]);
		$iCodUsua = $_GET["cod_usua"];
		
		$desp_nome = $_POST['nome'];
		$desp_email = $_POST['email'];
		$desp_contato_dpct = $_POST['contato_dpct'];
		$desp_telcel_dpct = utils::limpaNumeros($_POST['telcel_dpct']);
		$desp_telcom_dpct = utils::limpaNumeros($_POST['telcom_dpct']);
		$desp_ramal_dpct = $_POST['ramal_dpct'];
		$desp_telfax_dpct = utils::limpaNumeros($_POST['telfax_dpct']);
		$desp_cod_logr = $_POST['cod_logr'];
		$desp_endereco_dpct = $_POST['endereco_dpct'];
		$desp_nrendereco_dpct = $_POST['nrendereco_dpct'];
		$desp_cpendereco_dpct = $_POST['cpendereco_dpct'];
		$desp_cod_bairro = $_POST['cod_bairro'];
		$desp_cod_uf = $_POST['cod_uf'];
		$desp_cod_municipio = $_POST['cod_municipio'];
		$desp_cpfcnpj_dpct = $_POST['cpfcnpj_dpct'];
		$desp_nrbanco_dpct = $_POST['nrbanco_dpct'];
		$desp_nragencia_dpct = $_POST['nragencia_dpct'];
		$desp_nrcc_dpct = $_POST['nrcc_dpct'];
		$desp_nrdvcc_dpct = $_POST['nrdvcc_dpct'];
		$desp_obs_dpct = $_POST['obs_dpct'];
		
		$oDespachante = new despachante();
		
		$oDespachante->atualizarPk($iCodUsua,$desp_nome,$desp_contato_dpct,$desp_telcel_dpct,$desp_telcom_dpct,$desp_ramal_dpct,$desp_telfax_dpct,$desp_email,$desp_endereco_dpct,$desp_nrendereco_dpct,$desp_cpendereco_dpct,$desp_cod_logr,$desp_cod_bairro,$desp_cod_uf,$desp_cod_municipio,$desp_cpfcnpj_dpct,$desp_nrbanco_dpct,$desp_nragencia_dpct,$desp_nrcc_dpct,$desp_nrdvcc_dpct,$desp_obs_dpct);
		if ($oDespachante->errno != "0" || $oDespachante->qrcount == "0") {
			$oDespachante->inserir($iCodUsua,$desp_nome,$desp_contato_dpct,$desp_telcel_dpct,$desp_telcom_dpct,$desp_ramal_dpct,$desp_telfax_dpct,$desp_email,$desp_endereco_dpct,$desp_nrendereco_dpct,$desp_cpendereco_dpct,$desp_cod_logr,$desp_cod_bairro,$desp_cod_uf,$desp_cod_municipio,$desp_cpfcnpj_dpct,$desp_nrbanco_dpct,$desp_nragencia_dpct,$desp_nrcc_dpct,$desp_nrdvcc_dpct,$desp_obs_dpct);
		}
		if (@count($_SESSION["regiaoDespachante"]) > 0 && is_array($_SESSION["regiaoDespachante"])) {
			foreach ($_SESSION["regiaoDespachante"] as $iCodRegi) {
				$oUsuario->addRegiaoDespachante($iCodRegi, $iCodUsua);
				if ($oUsuario->getErrNo() != 0) {
					$bErroRegiao = true;
				}
			}
		}

		if ($bErroRegiao) {
			$mensagem->setMensagem("Houve um erro ao inserir as regiões do despachante. Tente novamente.", MSG_ERRO);
			$oUsuario->rollbackTransaction();
		} else {
			$mensagem->setMensagem("Despachante alterado com sucesso.", MSG_SUCESSO);
		}
	} elseif ($oUsuario->getErrNo() == DB_ERR_UNIQUE) {
		$oUsuario->rollbackTransaction();
		$mensagem->setMensagem("Já existe ou existiu um despachante cadastrado com esse endereço de e-mail.", MSG_ERRO);
	} else {
		$oUsuario->rollbackTransaction();
		$mensagem->setMensagem("Houve um erro ao atualizar os dados do despachante. Favor tentar novamente.", MSG_ERRO);
	}
	$oUsuario->commitTransaction();
} elseif ($pageAction == "addRegiao" && $_POST["regiao"] != "") {
	if (!@in_array($_POST["regiao"], $_SESSION["regiaoDespachante"])) {
		$_SESSION["regiaoDespachante"][] = $_POST["regiao"];
	}
} elseif (eregi("^([0-9]+)_del$", $pageAction)) {
	$idxToDel = eregi_replace("^([0-9]+)_del$", "\\1", $pageAction);
	unset($_SESSION["regiaoDespachante"][$idxToDel]);
}

if (!isset($_POST["ac"])) {
	$dadosUsuario = $oUsuario->getUsuario($_GET["cod_usua"]);
	$dadosDespachante = $oDespachante->pesquisarPk($_GET["cod_usua"]);
	$_POST["nome"] = $dadosUsuario[0]["nome_usua"];
	$_POST["email"] = $dadosUsuario[0]["email_usua"];
	$_POST["ativo"] = (string)$dadosUsuario[0]["flgstatus_usua"] == "1" ? "s" : "n";
	
	$_POST['contato_dpct'] 		= $dadosDespachante[0]['contato_dpct'];
	$_POST['telcel_dpct'] 		= utils::formataTelefone($dadosDespachante[0]['telcel_dpct']);
	$_POST['telcom_dpct'] 		= utils::formataTelefone($dadosDespachante[0]['telcom_dpct']);
	$_POST['ramal_dpct'] 		= $dadosDespachante[0]['ramal_dpct'];
	$_POST['telfax_dpct'] 		= utils::formataTelefone($dadosDespachante[0]['telfax_dpct']);
	$_POST['cod_logr'] 			= $dadosDespachante[0]['cod_logr'];
	$_POST['endereco_dpct'] 	= $dadosDespachante[0]['endereco_dpct'];
	$_POST['nrendereco_dpct'] 	= $dadosDespachante[0]['nrendereco_dpct'];
	$_POST['cpendereco_dpct'] 	= $dadosDespachante[0]['cpendereco_dpct'];
	$_POST['cod_bairro'] 		= $dadosDespachante[0]['cod_bairro'];
	$_POST['cod_uf'] 			= $dadosDespachante[0]['cod_uf'];
	$_POST['cod_municipio'] 	= $dadosDespachante[0]['cod_municipio'];
	$_POST['cpfcnpj_dpct'] 		= $dadosDespachante[0]['cpfcnpj_dpct'];
	$_POST['nrbanco_dpct'] 		= $dadosDespachante[0]['nrbanco_dpct'];
	$_POST['nragencia_dpct'] 	= $dadosDespachante[0]['nragencia_dpct'];
	$_POST['nrcc_dpct'] 		= $dadosDespachante[0]['nrcc_dpct'];
	$_POST['nrdvcc_dpct'] 		= $dadosDespachante[0]['nrdvcc_dpct'];
	$_POST['obs_dpct'] 			= $dadosDespachante[0]['obs_dpct'];
	
	$aDadosRegiaoDespachante = $oUsuario->getRegiaoDespachante($_GET["cod_usua"]);
	if (is_array($aDadosRegiaoDespachante) && @count($aDadosRegiaoDespachante) > 0) {
		foreach ($aDadosRegiaoDespachante as $aTmpDadosRegiaoDespachante) {
			$_SESSION["regiaoDespachante"][] = $aTmpDadosRegiaoDespachante["cod_regi"];
		}
	}
}

$aListaRegioesAtribuidas = (@count($_SESSION["regiaoDespachante"]) > 0) ? $_SESSION["regiaoDespachante"] : false;
$aListaRegiao = $oRegiao->getListaRegiao();
?>
<script language="javascript" type="text/javascript" src="js/cadastroDespachante.js"></script>
<script language="javascript" type="text/javascript" src="js/ajaxapi.js"></script>
<script language="JavaScript" type="text/javascript" src="js/diversos.js"></script>
<form method="post" action="<? echo $php_self; ?>?k=<? echo $crypt->encrypt("cod_usua=".$_GET["cod_usua"]); ?>" class="formPadrao" name="frm1" id="frm1">
<input type="hidden" name="ac" value="<? echo $crypt->encrypt("alterar");?>" />
<div class="quadroInterno">
	<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
	<div class="quadroInternoMeio">
		<? echo $mensagem->getMessageBox(); ?>
		<table cellpadding="0" cellspacing="2" class="tbForm">
			<colgroup>
				<col width="70"></col>
				<col width="630"></col>
			</colgroup>
			<tr>
				<td>Nome: </td>
				<td><input type="text" name="nome" id="nome" value="<? echo $_POST["nome"]; ?>" /></td>
			</tr>
			<tr>
				<td>Email: </td>
				<td><b><? echo $_POST["email"]; ?></b></td>
			</tr>
			
			<tr>
				<td>Contato: </td>
				<td><input type="text" name="contato_dpct" id="contato_dpct" value="<? echo $_POST["contato_dpct"]; ?>" /></td>
			</tr>
			<tr>
				<td>Celular: </td>
				<td><input style="width:120px;" type="text" name="telcel_dpct" id="telcel_dpct" value="<? echo $_POST["telcel_dpct"]; ?>" onkeydown="return teclasInt(this,event);" onkeyup="return mascaraTEL(this,event);" maxlength="13" /></td>
			</tr>
			<tr>
				<td>Telefone Comercial: </td>
				<td>
					<input style="width:120px;" type="text" name="telcom_dpct" id="telcom_dpct" value="<? echo $_POST["telcom_dpct"]; ?>" onkeydown="return teclasInt(this,event);" onkeyup="return mascaraTEL(this,event);" maxlength="13" />
					ramal: <input style="width:120px;" maxlength="20" type="text" name="ramal_dpct" id="ramal_dpct" value="<? echo $_POST["ramal_dpct"]; ?>" />
				</td>
			</tr>
			<tr>
				<td>Fax: </td>
				<td><input style="width:120px;" type="text" name="telfax_dpct" id="telfax_dpct" value="<? echo $_POST["telfax_dpct"]; ?>" onkeydown="return teclasInt(this,event);" onkeyup="return mascaraTEL(this,event);" maxlength="13" /></td>
			</tr>
			<tr>
				<td>Tipo de endereço: </td>
				<td>
			      <select name="cod_logr" id="cod_logr">
			        <option value="0" >-Selecione-</option>
			        <?
			        	foreach($listas->getListaLogradouro() as $k=>$v){
			    		  	$selected = ($_POST["cod_logr"]==$v['cod_logr'])?'selected':'';
			     		    print '<option value="'.$v['cod_logr'].'" '.$selected.'>'.$v['desc_logr'].'</option>';
			        	}
			        ?>
			      </select>
				</td>
			</tr>
			<tr>
				<td>Endereço: </td>
				<td><input type="text" name="endereco_dpct" id="endereco_dpct" value="<? echo $_POST["endereco_dpct"]; ?>" /></td>
			</tr>
			<tr>
				<td>Número: </td>
				<td><input style="width:120px;" maxlength="20" type="text" name="nrendereco_dpct" id="nrendereco_dpct" value="<? echo $_POST["nrendereco_dpct"]; ?>" /></td>
			</tr>
			<tr>
				<td>Complemento: </td>
				<td><input type="text" name="cpendereco_dpct" id="cpendereco_dpct" value="<? echo $_POST["cpendereco_dpct"]; ?>" /></td>
			</tr>
			<tr>
				<td>Bairro: </td>
				<td>
				      <select name="cod_bairro" id="cod_bairro">
				        <option value="0" >-Selecione-</option>
				        <?
				        	foreach($listas->getListaBairro() as $k=>$v){
				    		  	$selected = ($_POST["cod_bairro"]==$v['cod_bairro'])?'selected':'';
				     		    print '<option value="'.$v['cod_bairro'].'" '.$selected.'>'.$v['nome_bairro'].'</option>';
				        	}
				        ?>
				      </select>
				</td>
			</tr>
			<tr>
				<td>Estado: </td>
				<td>
			      <select name="cod_uf" id="cod_uf" onChange="getListaMunicipios_v2(this,'cod_municipio');">
			        <option value="0" >-Selecione-</option>
			        <?
			        	foreach($listas->getListaUF() as $k=>$v){
			    		  	$selected = ($_POST["cod_uf"]==$v['cod_uf'])?'selected':'';
			     		    print '<option value="'.$v['cod_uf'].'" '.$selected.'>'.$v['nome_uf'].'</option>';
			        	}
			        ?>
			      </select>
				</td>
			</tr>
			<tr>
				<td>Cidade: </td>
				<td>
			      <select name="cod_municipio" id="cod_municipio">
			      	<option value="0" >-Selecione-</option>
			      	<?
			      		if($_POST["cod_uf"]){
				          	foreach($listas->getListaMunicipio($_POST["cod_uf"]) as $k=>$v){
				      		  	$selected = ($_POST["cod_municipio"]==$v['cod_municipio'])?'selected':'';
				       		    print '<option value="'.$v['cod_municipio'].'" '.$selected.'>'.$v['nome_municipio'].'</option>';
				          	}
			      		}
			      	?>
			      </select>
				</td>
			</tr>
			<tr>
				<td>CPF/CNPJ: </td>
				<td><input style="width:120px;" maxlength="20" type="text" name="cpfcnpj_dpct" id="cpfcnpj_dpct" value="<? echo $_POST["cpfcnpj_dpct"]; ?>" /></td>
			</tr>
			<tr>
				<td>Número do Banco: </td>
				<td><input style="width:120px;" maxlength="20" type="text" name="nrbanco_dpct" id="nrbanco_dpct" value="<? echo $_POST["nrbanco_dpct"]; ?>" /></td>
			</tr>
			<tr>
				<td>Agência: </td>
				<td><input style="width:120px;" maxlength="20" type="text" name="nragencia_dpct" id="nragencia_dpct" value="<? echo $_POST["nragencia_dpct"]; ?>" /></td>
			</tr>
			<tr>
				<td>Conta Corrente: </td>
				<td><input style="width:120px;" maxlength="20" type="text" name="nrcc_dpct" id="nrcc_dpct" value="<? echo $_POST["nrcc_dpct"]; ?>" /></td>
			</tr>
			<tr>
				<td>Dígito Verificador: </td>
				<td><input style="width:120px;" maxlength="20" type="text" name="nrdvcc_dpct" id="nrdvcc_dpct" value="<? echo $_POST["nrdvcc_dpct"]; ?>" /></td>
			</tr>
			<tr>
				<td>Observações: </td>
				<td><textarea name="obs_dpct" id="obs_dpct"><? echo $_POST["obs_dpct"]; ?></textarea></td>
			</tr>

			<tr>
				<td>Regiões de atendimento: </td>
				<td>
					<select name="regiao" id="regiao">
						<option value="">selecione</option>
<?php
if (@count($aListaRegiao) > 0 && is_array($aListaRegiao)) {
	foreach ($aListaRegiao as $aDadosRegiao) {
?>
						<option value="<?php echo $aDadosRegiao["cod_regi"]; ?>"><?php echo $aDadosRegiao["nome_regi"]; ?></option>
<?php
	}
}
?>
					</select>
					&nbsp;&nbsp;
					<a href="javascript:addRegiaoDespachante(document.frm1, '<? echo $crypt->encrypt("addRegiao");?>');"><img src="images/buttons/bt_adicionar.gif" alt=" " class="vAlMid" /></a>
					<br /><br />
					<div class="tListDiv">
						<table>
							<colgroup>
								<col />
								<col width="100" />
							</colgroup>
							<thead>
								<tr>
									<td>Região</td>
									<td class="alc">&nbsp;</td>
								</tr>
							</thead>
<?
if ((int)count($aListaRegioesAtribuidas) > 0 && is_array($aListaRegioesAtribuidas)) {
	$contador = 0;
?>
							<tbody>
<?
	foreach ($aListaRegioesAtribuidas as $idxDadosRegioesAtribuidas => $dadosRegioesAtribuidas) {
		$dadosRegiao = $oRegiao->getRegiao($dadosRegioesAtribuidas);
		$contador++;
?>
								<tr class="tL<? echo $contador%2 ? "1" : "2"; ?>">
									<td><? echo $dadosRegiao[0]["nome_regi"]; ?></td>
									<td class="alc"><a href="javascript:doAction(document.frm1,'<? echo $crypt->encrypt($idxDadosRegioesAtribuidas."_del"); ?>');"><img src="images/buttons/bt_excluir.gif" alt=" " /></a></td>
								</tr>
<?
	}
?>
							</tbody>
<?
}
?>
						</table>
					</div>
				</td>
			</tr>
			<tr>
				<td>Ativo: </td>
				<td><input type="checkbox" name="ativo" id="ativo" value="s" class="ck" <? echo $_POST["ativo"] == "s" ? "checked" : ""; ?> /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td class="alr"><img src="images/buttons/bt_voltar.gif" onclick="goPage('adm_despachantes.php')" alt="Cancelar e voltar" class="cursorMao" />&nbsp;<img src="images/buttons/bt_salvar.gif" onclick="validaDespachanteAlt(document.getElementById('frm1'));" alt="Inserir Despachante" class="cursorMao" /></td>
			</tr>
		</table>
	</div>
	<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>
</form>
<?php
include "lib/footer.inc.php";
?>