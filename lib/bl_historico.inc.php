<?
$sAcao = $crypt->decrypt($_POST['acaoProposta']);
if ($sAcao == "addEvento") {


	$apto = $_POST['apto'];
	$motivo = isset($_POST['motivo']) ? $_POST['motivo'] : null;

	$uploaddir =  __DIR__ . "/../upload/analises/";

	if ( !is_dir( $uploaddir ) ) {
		mkdir($uploaddir, 0777, true);
		chown($uploaddir, 'root');
	}

	$path_arquivo = null;
	$nome_arquivo = null;
	if ( !empty( $_FILES['arquivo'] )) {

		$extensao = strtolower(end(explode('.', $_FILES['arquivo']['name'])));
		$path_arquivo = md5( $_POST['frm_cod_ppst'] . date("Y-m-d H:i:s") . $_FILES['arquivo']['name']);
		$nome_arquivo = $_FILES['arquivo']['name'];
		if ( copy( $_FILES['arquivo']['tmp_name'], $uploaddir . $path_arquivo . '-' . $nome_arquivo ) ) {
			echo "Arquivo válido e enviado com sucesso.\n";
		}
		$path_arquivo = $path_arquivo . '-' . $nome_arquivo;
	}

	$oHistorico = new historico();
	$oHistorico->inserir(
		$_POST['frm_cod_ppst'],
		date("Y-m-d H:i:s"),
		(!empty($_POST['novo_evento'])) ? $_POST['novo_evento'] : "&nbsp;",
		'2',
		$cLOGIN->iID,
		$nome_arquivo,
		$path_arquivo,
		$apto,
		$motivo
	);

/**
 * atuliza o motivo na proposta com a data da atualizacao para o controle de prazo
 */
$propostaDAO = new proposta();
$propostaDAO->atualizarHistoricoMotivo($_POST['frm_cod_ppst'], $motivo);
}

/**
 * ADICIONAR DOCUMENTOS
 */
if ($sAcao == "addDocumento") {


	$tipo = $_POST['tipo'];
	$subtipo = $_POST['subtipo'];

	//vendedor
	if ($tipo == 4) {
		$tipo = $_POST['tipofilho'];
	}

	$uploaddir =  __DIR__ . "/../upload/documentos/";

	if ( !is_dir( $uploaddir ) ) {
		mkdir($uploaddir, 0777, true);
		chown($uploaddir, 'root');
	}

	$path_arquivo = null;
	$nome_arquivo = null;
	if ( !empty( $_FILES['arquivo_documento'] )) {

		$extensao = strtolower(end(explode('.', $_FILES['arquivo_documento']['name'])));
		$hash_arquivo = md5( $_POST['frm_cod_ppst'] . date("Y-m-d H:i:s") . $_FILES['arquivo_documento']['name']) . '.' . $extensao;
		$nome_arquivo = $_FILES['arquivo_documento']['name'];

		if ( copy( $_FILES['arquivo_documento']['tmp_name'], $uploaddir . $hash_arquivo ) ) {
			echo "Arquivo válido e enviado com sucesso.\n";
		}
	}

	$documentoDAO = new documentoDAO();
	$documentoDAO->inserir(
		$_POST['frm_cod_ppst'],
		$cLOGIN->iID,
		$tipo,
		$subtipo,
		$nome_arquivo,
		$hash_arquivo);
}
?>
<script language="JavaScript" type="text/javascript" src="./js/proposta_bl_historico.js"></script>


<!-- BLOCO DE CONTRATO -->
<div class="bloco_include">
	<a name="documentos"></a>
	<div class="bloco_titulo">Contrato</div>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
			<div class="tListDiv listScroll" style="width:auto;">
				<textarea name="contrato_field" id="contrato_field">

				</textarea>
			</div>
		</div>
		<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
	</div>
</div>


<!-- BLOCO DE ANEXOS -->
<div class="bloco_include">
	<a name="documentos"></a>
	<div class="bloco_titulo">Documentos</div>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
			<div class="tListDiv listScroll" style="width:auto;">
				<table style="width:687px;">
					<colgroup>
						<col width="150" /><col width="120" /><col />
					</colgroup>
					<thead>
					<tr>
						<td>Data</td>
						<td>Tipo</td>
						<td>Sub-Tipo</td>
						<td style="width: 150px;">Arquivo</td>
						<td>Usuário</td>
					</tr>
					</thead>
					<tbody>
					<?
					$i = 0;
					$documentoDAO = new documentoDAO();
					$aDocumentos = $documentoDAO->listarPorProposta($aProposta['cod_ppst']);
					if (is_array($aDocumentos) && @count($aDocumentos) > 0) {
						foreach($aDocumentos as $kHist=>$vHist){
							$i++;

							$tipo  			= $vHist["tipo"];
							$subtipo  		= $vHist["subtipo"];
							$parent_id  	= $vHist["parent_id"];
							$usuario 		= $vHist["nome_usuario"];
							$data 			= $vHist["data_criacao"];
							$arquivo  		= $vHist["nome_arquivo"];
							$path_arquivo   = $vHist["hash_arquivo"];

							if (!empty($parent_id) && $parent_id == 4) {
								$tipo = 'Vendedor > ' . $tipo;
							}
							?>
							<tr class="tL<? echo $i%2 ? "1" : "2"; ?>">
								<td style="white-space:nowrap;"><? echo $data; ?></td>
								<td style="white-space:nowrap;"><? echo $tipo; ?></td>
								<td style="white-space:nowrap;"><? echo $subtipo; ?></td>
								<td><a href="/upload/documentos/<?php echo $path_arquivo ?>" target="_blank" download><?=$arquivo;?></a></td>
								<td style="white-space:nowrap;"><? echo $usuario; ?></td>
							</tr>
							<?
						}
					}
					?>
					</tbody>
				</table>
			</div>
			<? include "lib/bl_add_documento.inc.php"; ?>
		</div>
		<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
	</div>
</div>

<!-- BLOCO DE HISTORICO -->
<div class="bloco_include">
	<a name="historico"></a>
	<div class="bloco_titulo">Histórico</div>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
			<div class="tListDiv listScroll" style="width:auto;">
				<table style="width:687px;">
					<colgroup>
						<col width="150" /><col width="120" /><col />
					</colgroup>
					<thead>
						<tr>
							<td>Usuário</td>
							<td>Data</td>
							<td>Motivo</td>
							<td>Descrição</td>
							<td>Apto</td>
							<td style="width: 150px;">Arquivo</td>
						</tr>
					</thead>
					<tbody>
						<?
							$i = 0;
							$oHistorico = new historico();
							$aHistorico = $oHistorico->listarPorProposta($aProposta['cod_ppst']);
							if (is_array($aHistorico) && @count($aHistorico) > 0) {
								foreach($aHistorico as $kHist=>$vHist){
									$i++;
									$tipo  = $vHist["tipo_hist"];
									$motivo  = $vHist["motivo"];
									
									$usuario = $vHist["nome_usua"].' ('.$vHist["descr_level_usua"].')';
									
									if ($vHist["cod_usua"]) {
										$oUsuario = new usuario();
										$aDadosUsuario = $oUsuario->pesquisarPk($vHist["cod_usua"]);
										$usuario = $aDadosUsuario[0]["nome_usua"]." (".$aTIPOSUSER[$aDadosUsuario[0]["level_usua"]].")";
									} else {
										$usuario = "SISTEMA";
									}

									$estilo  = ($tipo==2)?' class="hist2" ':' class="hist1" ';
									$hist_data = $utils->formataDataHora($vHist["dt_hist"]);
									$hist_obs  = $vHist["obs_hist"];
									$arquivo  = $vHist["arquivo_anexo"];
									$path_arquivo  = $vHist["path_arquivo_anexo"];
									$hist_apto  = $vHist["apto"];

									if ( $hist_apto == 'SIM') {
										$apto = 'APTO';
									} else if ( $hist_apto == 'NAO') {
										$apto = 'NÃO APTO';
									} else {
										$apto = 'NÃO INFORMADO';
									}

									if($tipo==4){ 
										$aObsParts = explode('|',$hist_obs);
										$hist_obs = $aObsParts[0];
										$vHist["cod_chat"] = $aObsParts[1];
										$hist_obs .= ' &nbsp; <img src="images/buttons/lupa.gif" class="cursorMao" onClick="openChat(3,\''.$vHist["cod_chat"].'\');" alt="" />';
									}
									if($tipo==5){ $estilo = ' class="pfinal" '; $hist_obs = '<b>Parecer Final:</b><br>'.$hist_obs; }
									?>
										<tr class="tL<? echo $i%2 ? "1" : "2"; ?>">
											<td <?=$estilo;?> style="white-space:nowrap;"><? echo $usuario; ?></td>
											<td <?=$estilo;?>><?=$hist_data;?></td>
											<td <?=$estilo;?>><?=$motivo;?></td>
											<td <?=$estilo;?>><?=$hist_obs;?></td>
											<td <?=$estilo;?>><?=$apto;?></td>
											<td <?=$estilo;?>><a href="/upload/analises/<?php echo $path_arquivo ?>" target="_blank" download><?=$arquivo;?></a></td>
										</tr>
									<?
								}
							}
						?>
					</tbody>
				</table>
			</div>
			<? include "lib/bl_add_evento.inc.php"; ?>
		</div>
		<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
	</div>
</div>
