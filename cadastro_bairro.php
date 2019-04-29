<?php
	$pageTitle = "Cadastro de Bairro";
	include "lib/header.inc.php";

	if (isset($_POST['acao'])) {

		if ($_POST['acao'] == 'adicionar') {


			if (isset($_POST['bairro']) && !empty($_POST['bairro'])) {

				$db->query = "SELECT * FROM bairro WHERE upper(NOME_BAIRRO) = '". strtoupper($_POST['bairro']) . "'";
				$db->query();

				if ($db->qrcount) {
					echo "<p style='font-size: 15px; color: red;'>Bairro <b>" . $_POST['bairro'] . "</b> já existe</p>";
				} else {

					$db->query = "SELECT MAX( COD_BAIRRO ) as MAX FROM bairro";
					$db->query();

					$COD_BAIRRO = $db->qrdata[0]['MAX'] + 1;

					$db->query = "  INSERT INTO bairro (COD_BAIRRO, NOME_BAIRRO)
					  				VALUES ( ". $COD_BAIRRO .", '".$_POST["bairro"]."')";

					$db->query();

					echo "<p style='font-size: 15px; color: green;'>Bairro <b>". $_POST['bairro'] ."</b> adicionado com sucesso!";

					$_POST["cod_bairro"] = "";
					$_POST["bairro"] = "";
				}
			} else {
				echo "<p style='font-size: 15px; color: red;'>Campo <b>Nome Bairro</b> precisa ser preenchido.</p>";
			}


		}

	}

	if($cLOGIN->bOK == 1) {
		$conteudo = new conteudo();

		$dadosTree = $conteudo->getTree(); ?>

		<table cellpadding="0" cellspacing="0">
			<colgroup>
				<col width="188" style="background-color: #FFFFFF;"></col>
				<col width="540"></col>
			</colgroup>
			<tr>
				<td valign="top">
					<div style="width: 681px; padding: 22px; height: 400px; background-color: #EEEEEE; border: 1px solid #DDDDDD;">

						<form method="POST" action="cadastro_bairro.php">
							<input type="hidden" name="acao" id="acao" value="adicionar" />

							Nome Bairro:
							&nbsp;&nbsp;<input type="text" name="bairro" id="bairro" style="width: 300px;" value="<?php echo (isset($_POST['bairro']) && !emptY($_POST['bairro']) ? $_POST['bairro'] : "")?>"/>

							<br /><br />

							<button type="submit">Adicionar</button>
						</form>

					</div>
				</td>
			</tr>
		</table>

	<?php
	} else {
		echo "<br><br><br><center>Voc? precisa estar logado para acessar a Ajuda.</center><br><br><br>";
	}

	include "lib/footer.inc.php";
