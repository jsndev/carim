<? if($cLOGIN->iLEVEL_USUA != TPUSER_ADMPREVI){ ?>
<div id="div_add_evento">
	<?php

		$historico = new historico();
		$motivos = $historico->listarMotivos();

	?>

	<div class="grupoDados" style="margin:10px 0px 0px 0px;">
		<table cellpadding=0 cellspacing=5 border=0>
			<tr>
				<td align="right" valign="top"><b>Apto:</b></td>
				<td align="left"  valign="top">
					<select id="apto" name="apto">
						<option value="NI">Não informado</option>
						<option value="SIM">Apto</option>
						<option value="NAO">Não Apto</option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right" valign="top"><b>Motivo:</b></td>
				<td align="left"  valign="top">
					<select id="motivo" name="motivo">
						<option value="NI">Selecione</option>
						<?php foreach ($motivos as $motivo) :?>
						<option value="<?php echo $motivo['id']; ?>"><?php echo $motivo['motivo']; ?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right" valign="top"><b>Arquivo:</b></td>
				<td align="left"  valign="top">
					<input type="file" name="arquivo" id="arquivo" />
				</td>
			</tr>
		  <tr>
		    <td align="right" valign="top"><b>Novo Evento:</b></td>
		    <td align="left"  valign="top"><textarea style="width:500px; height:60px;" name="novo_evento" id="novo_evento"></textarea></td>
		  </tr>
		  <tr>
		    <td align="right" valign="top">&nbsp;</td>
            <td align="left"  valign="top">
				<img name="btAddEvnt" id="btAddEvnt" src="images/buttons/bt_adicionar.gif" alt="Adicionar Evento" class="im" onClick="addEvento_2('<?=$crypt->encrypt('addEvento');?>');" />
            </td>
		  </tr>
		</table>
	</div>
</div>
<? } ?>