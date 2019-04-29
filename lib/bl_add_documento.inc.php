
<? if($cLOGIN->iLEVEL_USUA != TPUSER_ADMPREVI){ ?>
<div id="div_add_documento">
	<script>

		function addDocumentos(_acao){
			document.getElementById('proposta').action += "#documentos";
			document.getElementById('acaoProposta').value = _acao;
			document.getElementById('proposta').submit();
		}

		jQuery(function(){

			jQuery('#tipo').change(function () {

				jQuery("#subtipo option[remover='true']").remove();

				var tipo_id = jQuery(this).val();

				if (tipo_id == 4) {

					jQuery('#tr_tipo_filho').show();

				} else {

					jQuery('#tr_tipo_filho').hide();

					jQuery.ajax({
						url: 'proposta.php',
						type: 'POST',
						data: {
							acao: 'buscar_subtipos',
							tipo_id: tipo_id
						},
						success: function(response)
						{
							jQuery('#subtipo').append(response);
						}
					});
				}
			})

			jQuery('#tipofilho').change(function () {

				jQuery("#subtipo option[remover='true']").remove();

				var tipo_id = jQuery(this).val();

				jQuery('#tr_tipo_filho').show();

				jQuery.ajax({
					url: 'proposta.php',
					type: 'POST',
					data: {
						acao: 'buscar_subtipos',
						tipo_id: tipo_id
					},
					success: function(response)
					{
						jQuery('#subtipo').append(response);
					}
				});

			})

		})


	</script>
	<div class="grupoDados" style="margin:10px 0px 0px 0px;">

		<?php
			$documentoDAO = new documentoDAO();
			$tipos = $documentoDAO->buscarTipos();


			//vendedor
			$tiposfilhos = $documentoDAO->buscarTiposFilhos(4);
		?>
		<table cellpadding=0 cellspacing=5 border=0>
			<tr>
				<td align="right" valign="top"><b>Tipo:</b></td>
				<td align="left"  valign="top">
					<select id="tipo" name="tipo">
						<option value="">Selecione</option>
						<?php foreach ($tipos as $tipo) :?>
						<option value="<?php echo $tipo['id'] ?>"><?php echo $tipo['descricao'] ?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
			<tr style="display: none;" id="tr_tipo_filho">
				<td align="right" valign="top"><b>Tipo Pessoa:</b></td>
				<td align="left"  valign="top">
					<select id="tipofilho" name="tipofilho">
						<option value="">Selecione</option>
						<?php foreach ($tiposfilhos as $tipo) :?>
							<option value="<?php echo $tipo['id'] ?>"><?php echo $tipo['descricao'] ?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right" valign="top"><b>Sub-Tipo:</b></td>
				<td align="left"  valign="top">
					<select id="subtipo" name="subtipo">
						<option value="">Selecione um Tipo</option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right" valign="top"><b>Arquivo:</b></td>
				<td align="left"  valign="top">
					<input type="file" name="arquivo_documento" id="arquivo_documento" />
				</td>
			</tr>
		  <tr>
		    <td align="right" valign="top">&nbsp;</td>
            <td align="left"  valign="top">
				<img name="btAddEvnt" id="btAddEvnt" src="images/buttons/bt_adicionar.gif" alt="Adicionar Documento" class="im" onClick="addDocumentos('<?=$crypt->encrypt('addDocumento');?>');" />
            </td>
		  </tr>
		</table>
	</div>
</div>
<? } ?>