<?php
$iREQ_AUT=1;
$aUSERS_PERM[]=4;

$pageTitle = "Adicionar Despachante";
include "lib/header.inc.php";

$oUsuario = new usuario();
$oRegiao = new regiao();

$mensagem = new mensagens();

$pageAction = $crypt->decrypt($_POST["ac"]);
if ($pageAction == "adicionar") {
	$oUsuario->beginTransaction();
	mt_srand(mktime());
	$sTmpPassword = substr(md5(mt_rand(0,mktime())),0,8);
	
	$dadosUsuario["nome_usua"] = $_POST["nome"];
	$dadosUsuario["email_usua"] = $_POST["email"];
	$dadosUsuario["pwd_usua"] = md5($sTmpPassword);
	$dadosUsuario["level_usua"] = TPUSER_DESPACHANTE;
	$dadosUsuario["id_lstn"] = '';
	$dadosUsuario["flgstatus_usua"] = (string)($_POST["ativo"] == "s" ? "1" : "0");
	
	$bInsercao = $oUsuario->addUsuario($dadosUsuario);
	
	if ($oUsuario->getErrNo() == 0) {

		$iCodUsua = $oUsuario->getInsertId();
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
		
		$oDespachante->inserir($iCodUsua,$desp_nome,$desp_contato_dpct,$desp_telcel_dpct,$desp_telcom_dpct,$desp_ramal_dpct,$desp_telfax_dpct,$desp_email,$desp_endereco_dpct,$desp_nrendereco_dpct,$desp_cpendereco_dpct,$desp_cod_logr,$desp_cod_bairro,$desp_cod_uf,$desp_cod_municipio,$desp_cpfcnpj_dpct,$desp_nrbanco_dpct,$desp_nragencia_dpct,$desp_nrcc_dpct,$desp_nrdvcc_dpct,$desp_obs_dpct);

		$bErroRegiao = false;
		
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
		} elseif ($oDespachante->errno != "") {
			$mensagem->setMensagem("Houve um erro ao inserir o despachante. Tente novamente.", MSG_ERRO);
			$oUsuario->rollbackTransaction();
		} else {
			$email = new email();
			$email->setTo($dadosUsuario["email_usua"]);
			$email->setSubject("Sua senha de acesso ao sistema Contrathos");
		$sMensagem = "
			<p>Prezado(a) <b>".$dadosUsuario["nome_usua"]."</b>,</p>
			<p>Seja bem vindo ao site de contratação da Athos Gestão e Serviço Ltda para o Carim 2007. Você está recebendo abaixo os dados de acesso ao Sistema Contrathos.</p>
			<p>
				Login: <b>".$dadosUsuario["email_usua"]."</b><br />
				Senha: <b>".$sTmpPassword."</b>
			</p>
			<p>A senha foi gerada automaticamente e poderá ser alterada na tela de login a seguir: <a href=\"http://www.contrathos.athosgestao.com.br/carim\">www.contrathos.athosgestao.com.br/carim</a></p>
			<p>Atenciosamente,</p>
			<p>Equipe Contrathos</p>
		";
			
			$email->setMessage($sMensagem);
			$email->send();
	
			$mensagem->setMensagem("Despachante cadastrado com sucesso. Um e-mail foi enviado para o despachante contendo sua senha de acesso.", MSG_SUCESSO);

			unset($_POST);
			unset($_SESSION["regiaoDespachante"]);
		}
	} elseif ($oUsuario->getErrNo() == DB_ERR_UNIQUE) {
		$oUsuario->rollbackTransaction();
		$mensagem->setMensagem("Já existe ou existiu um despachante cadastrado com esse endereço de e-mail.", MSG_ERRO);
	} else {
		$oUsuario->rollbackTransaction();
		$mensagem->setMensagem("Houve um erro ao cadastrar o despachante. Favor tentar novamente.", MSG_ERRO);
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

$aListaRegioesAtribuidas = (@count($_SESSION["regiaoDespachante"]) > 0) ? $_SESSION["regiaoDespachante"] : false;
$aListaRegiao = $oRegiao->getListaRegiao();
?>
<script language="javascript" type="text/javascript" src="js/cadastroDespachante.js"></script>
<script language="javascript" type="text/javascript" src="js/ajaxapi.js"></script>
<script language="JavaScript" type="text/javascript" src="js/diversos.js"></script>
<form method="post" action="<? echo $php_self; ?>" class="formPadrao" name="frm1" id="frm1">
<input type="hidden" name="ac" value="<? echo $crypt->encrypt("adicionar");?>" />
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
				<td>
					<input type="text" name="email" id="email" value="<? echo $_POST["email"]; ?>" />
					<p class="warning" style="width: 300px;"><b>Importante:</b> a senha de acesso do usuário será automaticamente gerada e enviada ao endereço de e-mail informado. Certifique-se de que o endereço de e-mail esteja correto.</p>
				</td>
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
			    		  	$selected = ($aProposta["imovel"]["cod_uf"]==$v['cod_uf'])?'selected':'';
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
				<td class="alr"><img src="images/buttons/bt_voltar.gif" onclick="goPage('adm_despachantes.php')" alt="Cancelar e voltar" class="cursorMao" />&nbsp;<img src="images/buttons/bt_salvar.gif" onclick="validaDespachante(document.getElementById('frm1'));" alt="Inserir Despachante" class="cursorMao" /></td>
			</tr>
		</table>
	</div>
	<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>
</form>
<?php
include "lib/footer.inc.php";
?>