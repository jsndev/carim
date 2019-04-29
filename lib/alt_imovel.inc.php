<script language="JavaScript" type="text/javascript" src="./js/proposta_bl_imovel.js"></script>
<script>
function somaAvaliacao()
{
	var sg=desformataMoeda(document.getElementById('vlavalsemgar_imov').value);
	var g= desformataMoeda(document.getElementById('vlavalgar_imov').value);
	document.getElementById('vlavaliacao_imov').value=formataMoeda(sg+g);
}
</script>
<a name="imovel"></a>
<div class="bloco_include">
	<div class="bloco_titulo">Dados do Imóvel</div>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
			<?
				if(1){
			?>
				<div style="float:left; width:500px;">
					<table cellpadding=0 cellspacing=5 border=0 id="tbEditImov">
		  			<tr>
		  			  <td align="right" valign="top">Logradouro:<? $utils->obrig('cod_logr_imov'); ?></td>
		  			  <td align="left">
					      <select name="cod_logr_imov" id="cod_logr_imov">
					        <option value="0" >-Selecione-</option>
					        <?
					        	foreach($listas->getListaLogradouro() as $k=>$v){
					    		  	$selected = ($aProposta["imovel"]["cod_logr"]==$v['cod_logr'])?'selected':'';
					     		    print '<option value="'.$v['cod_logr'].'" '.$selected.'>'.$v['desc_logr'].'</option>';
					        	}
					        ?>
					      </select>
		  			  </td>
		  			</tr>
		        <tr>
		          <td align="right" valign="top">Endereço:<? $utils->obrig('endereco_imov'); ?></td>
		          <td align="left"  valign="top"><input type="text" style="width:350px;" name="endereco_imov" id="endereco_imov" value="<?=$aProposta["imovel"]["endereco_imov"];?>" maxlength="50" /></td>
		        </tr>
		        <tr>
		          <td align="right" valign="top">Número:<? $utils->obrig('nrendereco_imov'); ?></td>
		          <td align="left"  valign="top"><input type="text" style="width:40px;" name="nrendereco_imov" id="nrendereco_imov" value="<?=$aProposta["imovel"]["nrendereco_imov"];?>" maxlength="6" onKeyDown="return teclasInt(this,event);" /></td>
		        </tr>
		        <tr>
		          <td align="right" valign="top">Complemento:</td>
		          <td align="left"  valign="top"><input type="text" style="width:150px;" name="cpendereco_imov" id="cpendereco_imov" value="<?=$aProposta["imovel"]["cpendereco_imov"];?>" maxlength="30" /></td>
		        </tr>
		        <tr>
		          <td align="right" valign="top">Estado:<? $utils->obrig('cod_uf_imov'); ?></td>
		          <td align="left"  valign="top">
					      <select name="cod_uf_imov" id="cod_uf_imov" onChange="getListaMunicipios_v2(this,'cod_municipio_imov');">
					        <option value="0" >-Selecione-</option>
					        <?
					        	foreach($listas->getListaUF() as $k=>$v){
					    		  	$selected = ($aProposta["imovel"]["cod_uf"]==$v['cod_uf'])?'selected':'';
					     		    print '<option value="'.$v['cod_uf'].'" '.$selected.'>'.$v['nome_uf'].'</option>';
					        	}
					        ?>
					      </select>
		            &nbsp;Cidade:<? $utils->obrig('cod_municipio_imov'); ?>
					      <select name="cod_municipio_imov" id="cod_municipio_imov" onChange="getListaDespachantes(this,'cod_uf_imov','cod_despachante_imov');">
					      	<option value="0" >-Selecione-</option>
					      	<?
					      		if($aProposta["imovel"]["cod_uf"]){
					          	foreach($listas->getListaMunicipio($aProposta["imovel"]["cod_uf"]) as $k=>$v){
					      		  	$selected = ($aProposta["imovel"]["cod_municipio"]==$v['cod_municipio'])?'selected':'';
					       		    print '<option value="'.$v['cod_municipio'].'" '.$selected.'>'.$v['nome_municipio'].'</option>';
					          	}
					      		}
					      	?>
					      </select>
		          </td>
		        </tr>
		        <tr>
		          <td align="right" valign="top">Bairro:<? $utils->obrig('cod_bairro_imov'); ?></td>
		          <td align="left"  valign="top">
					      <select name="cod_bairro_imov" id="cod_bairro_imov">
					        <option value="0" >-Selecione-</option>
					        <?
					        	foreach($listas->getListaBairro() as $k=>$v){
					    		  	$selected = ($aProposta["imovel"]["cod_bairro"]==$v['cod_bairro'])?'selected':'';
					     		    print '<option value="'.$v['cod_bairro'].'" '.$selected.'>'.$v['nome_bairro'].'</option>';
					        	}
					        ?>
					      </select>
		          </td>
		        </tr>
		        <tr>
		          <td align="right" valign="top">CEP:<? $utils->obrig('cep_imov'); ?></td>
		          <td align="left"  valign="top"><input type="text" style="width:100px;" name="cep_imov" id="cep_imov" value="<?=$utils->formataCep($aProposta["imovel"]["cep_imov"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraCEP(this,event);" maxlength="9" /></td>
		        </tr>
		  		</table>
		  	</div>

				<div style="float:left; width:200px;">
		    	<table cellpadding=0 cellspacing=5 border=0>
	    			<tr>
	    			  <td align="right"><input type="text" style="width:40px;" name="qtsala_imov" id="qtsala_imov" value="<?=(int)$aProposta["imovel"]["qtsala_imov"];?>" onKeyDown="return teclasInt(this,event);" maxlength="2" /></td>
	    			  <td align="left" valign="top"><? $utils->obrig('qtsala_imov'); ?> Salas</td>
	    			</tr>
	    			<tr>
	    			  <td align="right"><input type="text" style="width:40px;" name="qtquarto_imov" id="qtquarto_imov" value="<?=(int)$aProposta["imovel"]["qtquarto_imov"];?>" onKeyDown="return teclasInt(this,event);" maxlength="2" /></td>
	    			  <td align="left" valign="top"><? $utils->obrig('qtquarto_imov'); ?> Quartos</td>
	    			</tr>
	    			<tr>
	    			  <td align="right"><input type="text" style="width:40px;" name="qtbanh_imov" id="qtbanh_imov" value="<?=(int)$aProposta["imovel"]["qtbanh_imov"];?>" onKeyDown="return teclasInt(this,event);" maxlength="2" /></td>
	    			  <td align="left" valign="top"><? $utils->obrig('qtbanh_imov'); ?> Banheiros</td>
	    			</tr>
	    			<tr>
	    			  <td align="right"><input type="text" style="width:40px;" name="qtgarag_imov" id="qtgarag_imov" value="<?=(int)$aProposta["imovel"]["qtgarag_imov"];?>" onKeyDown="return teclasInt(this,event);" maxlength="2" /></td>
	    			  <td align="left" valign="top"><? $utils->obrig('qtgarag_imov'); ?> Garagens</td>
	    			</tr>
	    			<tr>
	    			  <td align="right"><input type="text" style="width:40px;" name="qtpavim_imov" id="qtpavim_imov" value="<?=(int)$aProposta["imovel"]["qtpavim_imov"];?>" onKeyDown="return teclasInt(this,event);" maxlength="2" /></td>
	    			  <td align="left" valign="top"><? $utils->obrig('qtpavim_imov'); ?> Pavimentos</td>
	    			</tr>
	    			<tr>
	    			  <td align="right"><input type="text" style="width:40px;" name="qtdepemp_imov" id="qtdepemp_imov" value="<?=(int)$aProposta["imovel"]["qtdepemp_imov"];?>" onKeyDown="return teclasInt(this,event);" maxlength="2" /></td>
	    			  <td align="left" valign="top"><? $utils->obrig('qtdepemp_imov'); ?> Dep Empreg</td>
	    			</tr>
	    		</table>
				</div>

				<div style="clear:both; height:30px;"></div>
								
				<div style="float:left; width:270px;">
	    		<table cellpadding=0 cellspacing=5 border=0>
	    			<tr>
	    			  <td align="right" valign="top">Matrícula do Imóvel (m²):<? $utils->obrig('matr_imov'); ?></td>
	    			  <td align="left"><input type="text" style="width:80px;" name="nrmatrgi_imov" id="nrmatrgi_imov" value="<?=$aProposta["imovel"]["nrmatrgi_imov"];?>" onKeyDown="return teclasInt(this,event);" onKeyUp="" maxlength="10" /></td>
	    			</tr>
					<tr>
	    			  <td align="right" valign="top">Área do Imóvel (m²):<? $utils->obrig('area_imov'); ?></td>
	    			  <td align="left"><input type="text" style="width:80px;" name="area_imov" id="area_imov" value="<?=$utils->formataFloat($aProposta["imovel"]["area_imov"],4);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraMoeda(this,event,null,4);" maxlength="14" /></td>
	    			</tr>
	    			<tr>
	    			  <td align="right" valign="top">Tipo de Imposto:<? $utils->obrig('tpimposto_imov'); ?></td>
	    			  <td align="left">
					      <select name="tpimposto_imov" id="tpimposto_imov">
					        <option value="0" >-Selecione-</option>
					        <?
					        	foreach($listas->getListaTipoImposto() as $k=>$v){
					    		  	$selected = ($aProposta["imovel"]["tpimposto_imov"]==$k)?'selected':'';
					     		    print '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
					        	}
					        ?>
					      </select>
              </td>
	    			</tr>
	    			<tr>
	    			  <td align="right" valign="top">Tipo do Imóvel:<? $utils->obrig('tipo_imov'); ?></td>
	    			  <td align="left">
		            <select name="tipo_imov" id="tipo_imov" onChange="trocouTipoImovel(this);">
					        <option value="0" >-Selecione-</option>
					        <?
					        	foreach($listas->getListaTipoImovel() as $k=>$v){
					    		  	$selected = ($aProposta["imovel"]["tipo_imov"]==$k)?'selected':'';
					     		    print '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
					     		    $display_dados_imovel = ($aProposta["imovel"]["tipo_imov"]=='E')?'':'display:none;';
					        	}
					        ?>
					      </select>
		        	</td>
	    			</tr>
	    			<tr id="tr_tipo_apart" style="<?=$display_dados_imovel;?>">
	    			  <td align="right" valign="top">Tipo de Apartamento:<? $utils->obrig('tipo_apartam'); ?></td>
	    			  <td align="left">
		            <select name="tipo_apartam" id="tipo_apartam">
					        <option value="0" >-Selecione-</option>
					        <?
					        	foreach($listas->getListaTipoApartam() as $k=>$v){
					    		  	$selected = ($aProposta["imovel"]["tpapto_imov"]==$k)?'selected':'';
					     		    print '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
					        	}
					        ?>
					      </select>
	    			  </td>
	    			</tr>
	    			<tr id="tr_area_util" style="<?=$display_dados_imovel;?>">
	    			  <td align="right" valign="top">Área Útil (m²):<? $utils->obrig('area_util'); ?></td>
	    			  <td align="left"><input type="text" style="width:80px;" name="area_util" id="area_util" value="<?=$utils->formataFloat($aProposta["imovel"]["areautil_imov"],4);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraMoeda(this,event,null,4);" maxlength="14" /></td>
	    			</tr>
	    			<tr id="tr_area_total" style="<?=$display_dados_imovel;?>">
	    			  <td align="right" valign="top">Área Total (m²):<? $utils->obrig('area_total'); ?></td>
	    			  <td align="left"><input type="text" style="width:80px;" name="area_total" id="area_total" value="<?=$utils->formataFloat($aProposta["imovel"]["areatotal_imov"],4);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraMoeda(this,event,null,4);" maxlength="14" /></td>
	    			</tr>
	    			<tr>
	    			  <td align="right" valign="top">Tipo Construção:<? $utils->obrig('tpconstrucao_imov'); ?></td>
	    			  <td align="left">
		            <select name="tpconstrucao_imov" id="tpconstrucao_imov">
					        <option value="0" >-Selecione-</option>
					        <?
					        	foreach($listas->getListaTipoConstrucao() as $k=>$v){
					    		  	$selected = ($aProposta["imovel"]["tpconstrucao_imov"]==$k)?'selected':'';
					     		    print '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
					        	}
					        ?>
					      </select>
              </td>
	    			</tr>
	    			<tr>
	    			  <td align="right" valign="top">Estado Conservação Imóvel:<? $utils->obrig('estconserv_imov'); ?></td>
	    			  <td align="left">
		            <select name="estconserv_imov" id="estconserv_imov">
					        <option value="0" >-Selecione-</option>
					        <?
					        	foreach($listas->getListaTipoConservacao() as $k=>$v){
					    		  	$selected = ($aProposta["imovel"]["estconserv_imov"]==$k)?'selected':'';
					     		    print '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
					        	}
					        ?>
					      </select>
              </td>
	    			</tr>
	    			<tr id="tr_cons_pred" style="<?=$display_dados_imovel;?>">
	    			  <td align="right" valign="top">Estado Conservação Prédio:<? $utils->obrig('estconspred_imov'); ?></td>
	    			  <td align="left">
		            <select name="estconspred_imov" id="estconspred_imov">
					        <option value="0" >-Selecione-</option>
					        <?
					        	foreach($listas->getListaTipoConservacao() as $k=>$v){
					    		  	$selected = ($aProposta["imovel"]["estconspred_imov"]==$k)?'selected':'';
					     		    print '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
					        	}
					        ?>
					      </select>
              </td>
	    			</tr>
	    			<tr>
	    			  <td align="right" valign="top">Andar:<? $utils->obrig('andar_imov'); ?></td>
	    			  <td align="left"  valign="top"><input type="text" style="width:40px;" name="andar_imov" id="andar_imov" value="<?=$aProposta["imovel"]["andar_imov"];?>" maxlength="5" /></td>
	    			</tr>
	    			<tr>
	    			  <td align="right" valign="top">Pavimento:<? $utils->obrig('pavimento_imov'); ?></td>
	    			  <td align="left"  valign="top"><input type="text" style="width:40px;" name="pavimento_imov" id="pavimento_imov" value="<?=$aProposta["imovel"]["pavimento_imov"];?>" maxlength="5" /></td>
	    			</tr>
	    			<tr id="tr_vagas_garagem" style="">
	    			  <td align="right" valign="top">N° de Vagas de Garagem:<? $utils->obrig('vagas_garagem_imov'); ?></td>
	    			  <td align="left"  valign="top"><input type="text" style="width:30px;" name="vagas_garagem_imov" id="vagas_garagem_imov" value="<?=(int)$aProposta["imovel"]["vagasapto_imov"];?>" maxlength="2" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraInt(this,event);" onfocus="setTmpVagasGaragem();" onblur='atualizaListaVagasGaragem();' /></td>
	    			</tr>
					</table>
					<input type="hidden" id="tmpObrigVagas" value='<? $utils->obrig('vagas_garagem'); ?>' />
				</div>
				
		  	<div style="float:left; width:430px;">
		  		<div style="float:right;">
			    	<table cellpadding=0 cellspacing=5 border=0>
		    			<tr>
		    			  <td align="right" valign="top">Isolado:<? $utils->obrig('isolado_imov'); ?></td>
		    			  <td align="left"  valign="bottom">
								  <?
								  	foreach ($listas->getListaSN() as $k=>$v){
					          	$checked = ($k==$aProposta["imovel"]["isolado_imov"])?'checked':'';
					          	print '<input type="radio" class="rd" name="isolado_imov" id="isolado_imov" value="'.$k.'" '.$checked.' /> '.$v.' &nbsp;&nbsp;';
								  	}
								  ?>
			    			</td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Condomínio:<? $utils->obrig('condominio_imov'); ?></td>
		    			  <td align="left"  valign="bottom">
								  <?
								  	foreach ($listas->getListaSN() as $k=>$v){
					          	$checked = ($aProposta["imovel"]["flgcondominio_imov"]==$k)?'checked':'';
					          	print '<input type="radio" class="rd" name="condominio_imov" id="condominio_imov" value="'.$k.'" '.$checked.' onclick="trocouCondominioImov(this);" /> '.$v.' &nbsp;&nbsp;';
					          	$display_dados_condominio = ($aProposta["imovel"]["flgcondominio_imov"]=='S')?'':'display:none;';
								  	}
								  ?>
			    			</td>
		    			</tr>
		    			<tr id="tr_imov_condom_nome" style="<?=$display_dados_condominio;?>">
		    			  <td align="right" valign="top">Nome do Condomínio:<? $utils->obrig('nome_condominio_imov'); ?></td>
		    			  <td align="left"  valign="top"><input type="text" style="width:200px;" name="nome_condominio_imov" id="nome_condominio_imov" value="<?=$aProposta["imovel"]["nomecondominio_imov"];?>" maxlength="50" /></td>
		    			</tr>
		    			<tr id="tr_imov_condom_tipo" style="<?=$display_dados_condominio;?>">
		    			  <td align="right" valign="top">Tipo Condomínio:<? $utils->obrig('tpcondominio_imov'); ?></td>
		    			  <td align="left">
			            <select name="tpcondominio_imov" id="tpcondominio_imov">
						        <option value="0" >-Selecione-</option>
						        <?
						        	foreach($listas->getListaTipoCondominio() as $k=>$v){
						    		  	$selected = ($aProposta["imovel"]["tpcondominio_imov"]==$k)?'selected':'';
						     		    print '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
						        	}
						        ?>
						      </select>
	              </td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Bloco:<? $utils->obrig('bloco_imov'); ?></td>
		    			  <td align="left"  valign="bottom">
								  <?
								  	foreach ($listas->getListaSN() as $k=>$v){
					          	$checked = ($aProposta["imovel"]["flgbloco_imov"]==$k)?'checked':'';
					          	print '<input type="radio" class="rd" name="bloco_imov" id="bloco_imov" value="'.$k.'" '.$checked.' onclick="trocouBlocoImov(this);" /> '.$v.' &nbsp;&nbsp;';
					          	$display_dados_bloco = ($aProposta["imovel"]["flgbloco_imov"]=='S')?'':'display:none;';
								  	}
								  ?>
								</td>
		    			</tr>
		    			<tr id="tr_imov_bloco_numero" style="<?=$display_dados_bloco;?>">
		    			  <td align="right" valign="top">Número do Bloco:<? $utils->obrig('numero_bloco_imov'); ?></td>
		    			  <td align="left"  valign="top"><input type="text" style="width:200px;" name="numero_bloco_imov" id="numero_bloco_imov" value="<?=$aProposta["imovel"]["numbloco_imov"];?>" maxlength="50" /></td>
		    			</tr>
		    			<tr id="tr_imov_bloco_edificio" style="<?=$display_dados_bloco;?>">
		    			  <td align="right" valign="top">Edifício:<? $utils->obrig('edificio_bloco_imov'); ?></td>
		    			  <td align="left"  valign="top"><input type="text" style="width:200px;" name="edificio_bloco_imov" id="edificio_bloco_imov" value="<?=$aProposta["imovel"]["edificio_imov"];?>" maxlength="50" /></td>
		    			</tr>
		    			<tr id="tr_imov_bloco_conjunto" style="<?=$display_dados_bloco;?>">
		    			  <td align="right" valign="top">Conjunto:<? $utils->obrig('conjunto_bloco_imov'); ?></td>
		    			  <td align="left"  valign="top"><input type="text" style="width:200px;" name="conjunto_bloco_imov" id="conjunto_bloco_imov" value="<?=$aProposta["imovel"]["conjunto_imov"];?>" maxlength="50" /></td>
		    			</tr>
			      	<tr>
			          <td align="right" valign="top">Imóvel Térreo:<? $utils->obrig('terreo_imov'); ?></td>
			          <td align="left"  valign="top">
			            <select name="terreo_imov" id="terreo_imov">
						        <option value="0" >-Selecione-</option>
						        <?
						        	foreach($listas->getListaImovelTerreo() as $k=>$v){
						    		  	$selected = ($aProposta["imovel"]["terreo_imov"]==$k)?'selected':'';
						     		    print '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
						        	}
						        ?>
						      </select>
			          </td>
			        </tr>
			      	<tr>
			          <td align="right" valign="top">Tipo de Moradia:<? $utils->obrig('tpmoradia_imov'); ?></td>
			          <td align="left"  valign="top">
			            <select name="tpmoradia_imov" id="tpmoradia_imov">
						        <option value="0" >-Selecione-</option>
						        <?
						        	foreach($listas->getListaTipoMoradia() as $k=>$v){
						    		  	$selected = ($aProposta["imovel"]["tpmoradia_imov"]==$k)?'selected':'';
						     		    print '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
						        	}
						        ?>
						      </select>
			          </td>
			        </tr>
			      </table>
	    		</div>
	    		<div style="float:right; margin-top:10px;">
		    		<table cellpadding=0 cellspacing=5 border=0>
		    			<tr>
		    			  <td align="right" valign="top">Aquisição do imóvel de pai ou mãe:</td>
		    			  <td align="left"  valign="bottom">
		    			  	<? $utils->obrig('aquispaimae_imov'); ?>
								  <?
								  	foreach ($listas->getListaSN() as $k=>$v){
					          	$checked = ($aProposta["imovel"]["aquispaimae_imov"]==$k)?'checked':'';
					          	print '<input type="radio" class="rd" name="aquispaimae_imov" id="aquispaimae_imov" value="'.$k.'" '.$checked.' onclick="trocouAquisPaiMae(this);" /> '.$v.' &nbsp;&nbsp;';
					          	$display_dados_paimae = ($aProposta["imovel"]["aquispaimae_imov"]=='S')?'':'display:none;';
								  	}
								  ?>
								</td>
		    			</tr>
		    			<tr id="tr_imov_aquis_paimae" style="<?=$display_dados_paimae;?>">
		    			  <td align="right" valign="top">Possui irmãos:</td>
		    			  <td align="left"  valign="bottom">
		    			  	<? $utils->obrig('possuiirmaos_imov'); ?>
								  <?
								  	foreach ($listas->getListaSN() as $k=>$v){
					          	$checked = ($aProposta["imovel"]["possuiirmaos_imov"]==$k)?'checked':'';
					          	print '<input type="radio" class="rd" name="possuiirmaos_imov" id="possuiirmaos_imov" value="'.$k.'" '.$checked.' /> '.$v.' &nbsp;&nbsp;';
								  	}
								  ?>
								</td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Imóvel tombado, desapropriado <br /> ou condenado por órgão público:</td>
		    			  <td align="left"  valign="bottom">
		    			  	<? $utils->obrig('tmbdspcndop_imov'); ?>
								  <?
								  	foreach ($listas->getListaSN() as $k=>$v){
					          	$checked = ($aProposta["imovel"]["tmbdspcndop_imov"]==$k)?'checked':'';
					          	print '<input type="radio" class="rd" name="tmbdspcndop_imov" id="tmbdspcndop_imov" value="'.$k.'" '.$checked.' /> '.$v.' &nbsp;&nbsp;';
								  	}
								  ?>
								</td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Imóvel incombustível:</td>
		    			  <td align="left"  valign="bottom">
		    			  	<? $utils->obrig('incomb_imov'); ?>
								  <?
								  	foreach ($listas->getListaSN() as $k=>$v){
					          	$checked = ($aProposta["imovel"]["incomb_imov"]==$k)?'checked':'';
					          	print '<input type="radio" class="rd" name="incomb_imov" id="incomb_imov" value="'.$k.'" '.$checked.' /> '.$v.' &nbsp;&nbsp;';
								  	}
								  ?>
								</td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Imóvel localizado em área rural ou favela:</td>
		    			  <td align="left"  valign="bottom">
		    			  	<? $utils->obrig('ruralfav_imov'); ?>
								  <?
								  	foreach ($listas->getListaSN() as $k=>$v){
					          	$checked = ($aProposta["imovel"]["ruralfav_imov"]==$k)?'checked':'';
					          	print '<input type="radio" class="rd" name="ruralfav_imov" id="ruralfav_imov" value="'.$k.'" '.$checked.' /> '.$v.' &nbsp;&nbsp;';
								  	}
								  ?>
								</td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Imóvel em construção:</td>
		    			  <td align="left"  valign="bottom">
		    			  	<? $utils->obrig('emconstr_imov'); ?>
								  <?
								  	foreach ($listas->getListaSN() as $k=>$v){
					          	$checked = ($aProposta["imovel"]["emconstr_imov"]==$k)?'checked':'';
					          	print '<input type="radio" class="rd" name="emconstr_imov" id="emconstr_imov" value="'.$k.'" '.$checked.' /> '.$v.' &nbsp;&nbsp;';
								  	}
								  ?>
								</td>
		    			</tr>
			    	</table>
		    	</div>
		    </div>

				<script language="JavaScript" type="text/javascript">
					var aTipoVaga  = Array();
					var aLocalVaga = Array();
					<?
						$c = 0;
						foreach ($listas->getTipoVaga() as $k=>$v){
							print "aTipoVaga[".$c."] = Array('".$k."','".$v."');\n";
							$c++;
						}
						$c = 0;
						foreach ($listas->getLocalVaga() as $k=>$v){
							print "aLocalVaga[".$c."] = Array('".$k."','".$v."');\n";
							$c++;
						}
					?>
				</script>
				<?
					$vg = 0;
					if( is_array($aProposta["imovelvagas"]) && @count($aProposta["imovelvagas"])>0 ){
						?>
						<div id="divVagasGaragemContainer" class="grupoDados" style="clear:both; margin-bottom:20px; ">
						<?
						foreach($aProposta["imovelvagas"] as $vk=>$vv){
							$vg++;
							?>
								<div class="divVagaGaragem">
									<b>Vaga <?=$vg;?></b>
									<table cellpadding=0 cellspacing=5 border=0>
										<colgroup><col width='220' /><col /></colgroup>
										<tr>
											<td align='right' valign='top'>Tipo de Vaga:<? $utils->obrig('vagas_garagem'); ?></td>
											<td align='left'>
											  <?
											  	foreach ($listas->getTipoVaga() as $k=>$v){
								          	$checked = ($vv["tpvaga_imvg"]==$k)?'checked':'';
								          	print '<input type="radio" class="rd" name="tipo_vaga_imov_'.$vg.'" id="tipo_vaga_imov_'.$vg.'" value="'.$k.'" '.$checked.' onclick="trocouTipoVaga(this,'.$vg.');" /> '.$v.' &nbsp;&nbsp;';
								          	$display_dados_vaga = ($vv["tpvaga_imvg"]=='I')?'':'display:none;';
											  	}
											  ?>
											</td>
										</tr>
										<tr>
											<td align='right' valign='top'>Local da Vaga:<? $utils->obrig('vagas_garagem'); ?></td>
											<td align='left'>
											  <?
											  	foreach ($listas->getLocalVaga() as $k=>$v){
								          	$checked = ($vv["local_imvg"]==$k)?'checked':'';
								          	print '<input type="radio" class="rd" name="local_vaga_imov_'.$vg.'" id="local_vaga_imov_'.$vg.'" value="'.$k.'" '.$checked.' /> '.$v.' &nbsp;&nbsp;';
											  	}
											  ?>
											</td>
										</tr>
										<tr>
											<td align='right' valign='top'>Área útil (m²):<? $utils->obrig('vagas_garagem'); ?></td>
											<td align='left'><input type='text' style='width:80px;' name='area_util_vaga_imov_<?=$vg;?>' id='area_util_vaga_imov_<?=$vg;?>' value='<?=$utils->formataFloat($vv["areautil_imvg"],4);?>' onKeyDown='return teclasInt(this,event);' onKeyUp='return mascaraMoeda(this,event,null,4);' maxlength='14' /></td>
										</tr>
										<tr>
											<td align='right' valign='top'>Área comum (m²):<? $utils->obrig('vagas_garagem'); ?></td>
											<td align='left'><input type='text' style='width:80px;' name='area_comum_vaga_imov_<?=$vg;?>' id='area_comum_vaga_imov_<?=$vg;?>' value='<?=$utils->formataFloat($vv["areacomum_imvg"],4);?>' onKeyDown='return teclasInt(this,event);' onKeyUp='return mascaraMoeda(this,event,null,4);' maxlength='14' /></td>
										</tr>
										<tr>
											<td align='right' valign='top'>Área total (m²):<? $utils->obrig('vagas_garagem'); ?></td>
											<td align='left'><input type='text' style='width:80px;' name='area_total_vaga_imov_<?=$vg;?>' id='area_total_vaga_imov_<?=$vg;?>' value='<?=$utils->formataFloat($vv["areatotal_imvg"],4);?>' onKeyDown='return teclasInt(this,event);' onKeyUp='return mascaraMoeda(this,event,null,4);' maxlength='14' /></td>
										</tr>
										<tr>
											<td align='right' valign='top'>Fração ideal (%):<? $utils->obrig('vagas_garagem'); ?></td>
											<td align='left'><input type='text' style='width:80px;' name='fracao_vaga_imov_<?=$vg;?>' id='fracao_vaga_imov_<?=$vg;?>' value='<?=$utils->formataFloat($vv["fracaoideal_imvg"],6);?>' onKeyDown='return teclasInt(this,event);' onKeyUp='return mascaraMoeda(this,event,null,6);' maxlength='10' /></td>
										</tr>
										<tr id='tr_num_contrib_vaga_imov_<?=$vg;?>' style='<?=$display_dados_vaga;?>'>
											<td align='right' valign='top'>Número do Contribuinte:<? $utils->obrig('vagas_garagem'); ?></td>
											<td align='left'><input type='text' style='width:160px;' name='num_contrib_vaga_imov_<?=$vg;?>' id='num_contrib_vaga_imov_<?=$vg;?>' value='<?=$vv["nrcontribuinte_imvg"];?>' maxlength='25' /></td>
										</tr>
										<tr id='tr_num_reg_vaga_imov_<?=$vg;?>' style='<?=$display_dados_vaga;?>'>
											<td align='right' valign='top'>Número de Registro:<? $utils->obrig('vagas_garagem'); ?></td>
											<td align='left'><input type='text' style='width:60px;' name='num_reg_vaga_imov_<?=$vg;?>' id='num_reg_vaga_imov_<?=$vg;?>' value='<?=$vv["nrregistro_imvg"];?>' maxlength='6' /></td>
										</tr>
										<tr id='tr_num_matr_vaga_imov_<?=$vg;?>' style='<?=$display_dados_vaga;?>'>
											<td align='right' valign='top'>Número de Matrícula:<? $utils->obrig('vagas_garagem'); ?></td>
											<td align='left'><input type='text' style='width:60px;' name='num_matr_vaga_imov_<?=$vg;?>' id='num_matr_vaga_imov_<?=$vg;?>' value='<?=$vv["nrmatricula_imvg"];?>' maxlength='6' /></td>
										</tr>
										<tr id='tr_num_oficio_vaga_imov_<?=$vg;?>' style='<?=$display_dados_vaga;?>'>
											<td align='right' valign='top'>Número do ofício do registro de imóveis:<? $utils->obrig('vagas_garagem'); ?></td>
											<td align='left'><input type='text' style='width:60px;' name='num_oficio_vaga_imov_<?=$vg;?>' id='num_oficio_vaga_imov_<?=$vg;?>' value='<?=$vv["nroficioregistro_imvg"];?>' maxlength='6' /></td>
										</tr>
										<tr id='tr_local_oficio_vaga_imov_<?=$vg;?>' style='<?=$display_dados_vaga;?>'>
											<td align='right' valign='top'>Local do ofício do registro de imóveis:<? $utils->obrig('vagas_garagem'); ?></td>
											<td align='left'><input type='text' style='width:60px;' name='local_oficio_vaga_imov_<?=$vg;?>' id='local_oficio_vaga_imov_<?=$vg;?>' value='<?=$vv["localoficio_imvg"];?>' maxlength='6' /></td>
										</tr>
									</table>
								</div>
							<?
						}
						?>
						</div>
						<?
					}else{
						?>
						<div id="divVagasGaragemContainer" class="grupoDados" style="clear:both; margin-bottom:20px; display:none;"></div>
						<?
					}
				?>
	    
				<div style="clear:both; height:10px;"></div>
				<? if($cLOGIN->iLEVEL_USUA==2){ ?>
				  <div class="grupoDados" style="width:450px;">
						<table width="419" border=0 cellpadding=0 cellspacing=5 id="tbEditAvalImov">
						<tr>
			  			  <td align="right" valign="top">Avaliação do Imóvel s/ Garagem (R$):<? $utils->obrig('vlavalsemgar_imov'); ?></td>
			  			  <td align="left"><input type="text" style="width:80px;" name="vlavalsemgar_imov" id="vlavalsemgar_imov" value="<?=$utils->formataMoeda($aProposta["imovel"]["vlavalsemgar_imov"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraMoeda(this,event);" maxlength="19" /></td>
			  			</tr>
						<tr>
			  			  <td align="right" valign="top">Avaliação da Garagem (R$):<? $utils->obrig('vlavalgar_imov'); ?></td>
			  			  <td align="left"><input type="text" style="width:80px;" name="vlavalgar_imov" id="vlavalgar_imov" value="<?=$utils->formataMoeda($aProposta["imovel"]["vlavalgar_imov"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraMoeda(this,event);" maxlength="19" onBlur="somaAvaliacao();" /></td>
			  			</tr>
						<tr>
			  			  <td align="right" valign="top">Avaliação Total do Imóvel (R$):<? $utils->obrig('vlavaliacao_imov'); ?></td>
			  			  <td align="left"><input type="text" style="width:80px;" name="vlavaliacao_imov" id="vlavaliacao_imov" value="<?=$utils->formataMoeda($aProposta["imovel"]["vlavaliacao_imov"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraMoeda(this,event);" maxlength="19" /></td>
			  			</tr>
			  			<tr>
			  			  <td align="right" valign="top">Data da Avaliação do Imóvel:<? $utils->obrig('dtavaliacao_imov'); ?></td>
			  			  <td align="left"><input type="text" style="width:80px;" name="dtavaliacao_imov" id="dtavaliacao_imov" value="<?=$utils->formataDataBRA($aProposta["imovel"]["dtavaliacao_imov"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10" /></td>
			  			</tr>
			  			<tr>
			  			  <td align="right" valign="top">Data de Aprovação do Imóvel:<? $utils->obrig('dtaprovacao_imov'); ?></td>
			  			  <td align="left"><input type="text" style="width:80px;" name="dtaprovacao_imov" id="dtaprovacao_imov" value="<?=$utils->formataDataBRA($aProposta["imovel"]["dtaprovacao_imov"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event,'verBtnAprovarImovel()');" onfocus="verBtnAprovarImovel();" onblur="verBtnAprovarImovel();" maxlength="10" /></td>
			          <td align="left" valign="top" style="padding-left:5px;">
			       	    <img name="btAprovarImov" id="btAprovarImov" src="images/buttons/bt_aprovar_sim.gif" alt="Aprovar Imóvel"  class="im" onClick="aprovarImovel('<?=$crypt->encrypt('aprovarImovel');?>');" style="visibility:hidden;" />
			       	    <? /*depois de voltar a resposta da previ verificar se é preciso
			       	    exibir botao "cancelar aprovação" que irá limpar o campo da
			       	    "data de aprovação" e deixará o form imovel aberto*/ ?>
			          </td>
			  			</tr>
			  		</table>
			  	</div>
		  	<? } ?>
		  	
		  	<div id="ckls_imov"><? if($cLOGIN->iLEVEL_USUA!=TPUSER_PROPONENTE){
			echo qd_exigencia('imov_exigencia','imov_BtExig','imov_addexig', 'imov_btsalvarexigencia',$_POST['imov_addexig'],$aProposta["cod_ppst"],'fimexigimov');}
			 include('alt_ckls_imovel.inc.php'); ?></div>
		  	
				<table cellpadding=0 cellspacing=5 border=0 style="margin-top:5px;">
					<? if($cLOGIN->iLEVEL_USUA==TPUSER_ATENDENTE){ ?>
						<tr>
							<td align="right" valign="top">Despachante:<? $utils->obrig('cod_despachante_imov'); ?></td>
							<td align="left"  valign="top">
						    	<select name="cod_despachante_imov" id="cod_despachante_imov" onChange="getListaMunicipiosDespachante(this,'cod_despachante_imov','tdImovelDespachanteDetalhes');">
							      <option value="0" >-Selecione-</option>
							      <?
							      	foreach($listas->getListaDespachantes($aProposta["imovel"]["cod_uf"],$aProposta["imovel"]["cod_municipio"]) as $kDesp=>$vDesp){
							  		  	$selected = ($aProposta["imovel"]["despachante_imov"]==$vDesp['cod_usua'])?'selected':'';
							   		    print '<option value="'.$vDesp['cod_usua'].'" '.$selected.'>'.$vDesp['nome_usua'].'</option>';
							      	}
							      	//getUsuario(cod_desp);
							      ?>
						    	</select>
						    </td><td align="left"  valign="top">
						    	<div id="tdImovelDespachanteDetalhes" class="grupoDados"> 
						    		<ul style="margin-left:25px;"> 
						    		<?
							    		$aMunicipios = $oUsuario->getListaMunicipiosDespachante($aProposta["imovel"]["despachante_imov"]);
							    		foreach($aMunicipios as $vMunicipios){
							    			print '<li>'. $vMunicipios['cod_uf'] .' - ' . $vMunicipios['nome_municipio'] . '</li>';
							    		}
							    	?>
						    		<ul> 
						    	</div> 
							</td>
						</tr>
					<? }else{ ?>
						<tr>
							<td align="right" valign="top">Despachante:<? $utils->obrig('cod_despachante_imov'); ?></td>
							<td align="left"  valign="top"><b><?
								$vTMP = $aProposta["imovel"]["despachante_imov"];
								$aTMP = $oUsuario->getUsuario($vTMP);
								print ($aTMP[0]["nome_usua"]!='')?$aTMP[0]["nome_usua"]:'Nenhum despachante associado';
							?></b></td>
						</tr>
					<? } ?>
				</table>
	  	<? }else{ ?>
				<div style="float:left; width:500px;">
					<table cellpadding=0 cellspacing=5 border=0>
		  			<tr>
		  			  <td align="right" valign="top">Logradouro:</td>
		  			  <td align="left"><b><?=$aProposta["imovel"]["logradouro"][0]["desc_logr"];?></b></td>
		  			</tr>
		        <tr>
		          <td align="right" valign="top">Endereço:</td>
		          <td align="left"><b><?=$aProposta["imovel"]["endereco_imov"];?></b></td>
		        </tr>
		        <tr>
		          <td align="right" valign="top">Número:</td>
		          <td align="left"><b><?=$aProposta["imovel"]["nrendereco_imov"];?></b></td>
		        </tr>
		        <tr>
		          <td align="right" valign="top">Complemento:</td>
		          <td align="left"><b><?=$aProposta["imovel"]["cpendereco_imov"];?></b></td>
		        </tr>
		        <tr>
		          <td align="right" valign="top">Bairro:</td>
		          <td align="left"><b><?=$aProposta["imovel"]["bairro"][0]["nome_bairro"];?></b></td>
		        </tr>
		        <tr>
		          <td align="right" valign="top">Cidade:</td>
		          <td align="left"><b><?=$aProposta["imovel"]["municipio"][0]["nome_municipio"];?></b></td>
		        </tr>
		        <tr>
		          <td align="right" valign="top">Estado:</td>
		          <td align="left"><b><?=$aProposta["imovel"]["uf"][0]["nome_uf"];?></b></td>
		        </tr>
		        <tr>
		          <td align="right" valign="top">CEP:</td>
		          <td align="left"><b><?=$utils->formataCep($aProposta["imovel"]["cep_imov"]);?></b></td>
		        </tr>
		  		</table>
		  	</div>

				<div style="float:left; width:200px;">
		    	<table cellpadding=0 cellspacing=5 border=0>
	    			<tr>
		          <td align="right"><b><?=(int)$aProposta["imovel"]["qtsala_imov"];?></b></td>
	    			  <td align="left" valign="top">Salas</td>
	    			</tr>
	    			<tr>
		          <td align="right"><b><?=(int)$aProposta["imovel"]["qtquarto_imov"];?></b></td>
	    			  <td align="left" valign="top">Quartos</td>
	    			</tr>
	    			<tr>
		          <td align="right"><b><?=(int)$aProposta["imovel"]["qtbanh_imov"];?></b></td>
	    			  <td align="left" valign="top">Banheiros</td>
	    			</tr>
	    			<tr>
		          <td align="right"><b><?=(int)$aProposta["imovel"]["qtgarag_imov"];?></b></td>
	    			  <td align="left" valign="top">Garagens</td>
	    			</tr>
	    			<tr>
		          <td align="right"><b><?=(int)$aProposta["imovel"]["qtpavim_imov"];?></b></td>
	    			  <td align="left" valign="top">Pavimentos</td>
	    			</tr>
	    			<tr>
		          <td align="right"><b><?=(int)$aProposta["imovel"]["qtdepemp_imov"];?></b></td>
	    			  <td align="left" valign="top">Dep Empreg</td>
	    			</tr>
	    		</table>
				</div>

				<div style="clear:both; height:30px;"></div>
								
				<div style="float:left; width:270px;">
	    		<table cellpadding=0 cellspacing=5 border=0>
	    			<tr>
	    			  <td align="right" valign="top">Matrícula do Imóvel:</td>
	    			  <td align="left"><?=$aProposta["imovel"]["nrmatrgi_imov"];?></td>
	    			</tr>
	    			
					<tr>
	    			  <td align="right" valign="top">Área do Imóvel:</td>
		          <td align="left"><b><?=$utils->formataFloat($aProposta["imovel"]["area_imov"],4);?> m²</b></td>
	    			</tr>
	    			<tr>
	    			  <td align="right" valign="top">Tipo de Imposto:</td>
		          <td align="left"><b><?
		          	$vTMP = $aProposta["imovel"]["tpimposto_imov"];
		          	$aTMP = $listas->getListaTipoImposto($vTMP);
		          	print $aTMP[$vTMP];
		          ?></b></td>
	    			</tr>
	    			<tr>
	    			  <td align="right" valign="top">Tipo do Imóvel:</td>
		          <td align="left"><b><?
		          	$vTMP = $aProposta["imovel"]["tipo_imov"];
		          	$aTMP = $listas->getListaTipoImovel($vTMP);
		          	print $aTMP[$vTMP];
		          	$display_dados_imovel = ($vTMP=='E')?'':'display:none;';
		          ?></b></td>
	    			</tr>
	    			<tr id="tr_tipo_apart" style="<?=$display_dados_imovel;?>">
	    			  <td align="right" valign="top">Tipo de Apartamento:</td>
		          <td align="left"><b><?
		          	$vTMP = $aProposta["imovel"]["tpapto_imov"];
		          	$aTMP = $listas->getListaTipoApartam($vTMP);
		          	print $aTMP[$vTMP];
		          ?></b></td>
	    			</tr>
	    			<tr id="tr_area_util" style="<?=$display_dados_imovel;?>">
	    			  <td align="right" valign="top">Área Útil:</td>
		          <td align="left"><b><?=$utils->formataFloat($aProposta["imovel"]["areautil_imov"],4);?> m²</b></td>
	    			</tr>
	    			<tr id="tr_area_total" style="<?=$display_dados_imovel;?>">
	    			  <td align="right" valign="top">Área Total:</td>
		          <td align="left"><b><?=$utils->formataFloat($aProposta["imovel"]["areatotal_imov"],4);?> m²</b></td>
	    			</tr>
	    			<tr>
	    			  <td align="right" valign="top">Tipo Construção:</td>
		          <td align="left"><b><?
		          	$vTMP = $aProposta["imovel"]["tpconstrucao_imov"];
		          	$aTMP = $listas->getListaTipoConstrucao($vTMP);
		          	print $aTMP[$vTMP];
		          ?></b></td>
	    			</tr>
	    			<tr>
	    			  <td align="right" valign="top">Estado Conservação Imóvel:</td>
		          <td align="left"><b><?
		          	$vTMP = $aProposta["imovel"]["estconserv_imov"];
		          	$aTMP = $listas->getListaTipoConservacao($vTMP);
		          	print $aTMP[$vTMP];
		          ?></b></td>
	    			</tr>
	    			<tr id="tr_cons_pred" style="<?=$display_dados_imovel;?>">
	    			  <td align="right" valign="top">Estado Conservação Prédio:</td>
		          <td align="left"><b><?
		          	$vTMP = $aProposta["imovel"]["estconspred_imov"];
		          	$aTMP = $listas->getListaTipoConservacao($vTMP);
		          	print $aTMP[$vTMP];
		          ?></b></td>
	    			</tr>
	    			<tr>
	    			  <td align="right" valign="top">Andar:</td>
		          <td align="left"><b><?=$aProposta["imovel"]["andar_imov"];?></b></td>
	    			</tr>
	    			<tr>
	    			  <td align="right" valign="top">Pavimento:</td>
		          <td align="left"><b><?=$aProposta["imovel"]["pavimento_imov"];?></b></td>
	    			</tr>
	    			<tr id="tr_vagas_garagem">
	    			  <td align="right" valign="top">N° de Vagas de Garagem:</td>
		          <td align="left"><b><?=(int)$aProposta["imovel"]["vagasapto_imov"];?></b></td>
	    			</tr>
					</table>
				</div>
				
		  	<div style="float:left; width:430px;">
		  		<div style="float:right; width:430px;">
			    	<table cellpadding=0 cellspacing=5 border=0>
		    			<tr>
		    			  <td align="right" valign="top">Isolado:</td>
			          <td align="left"><b><?
			          	$vTMP = $aProposta["imovel"]["isolado_imov"];
			          	$aTMP = $listas->getListaSN();
			          	print $aTMP[$vTMP];
			          ?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Condomínio:</td>
			          <td align="left"><b><?
			          	$vTMP = $aProposta["imovel"]["flgcondominio_imov"];
			          	$aTMP = $listas->getListaSN();
			          	print $aTMP[$vTMP];
			          	$display_dados_condominio = ($aProposta["imovel"]["flgcondominio_imov"]=='S')?'':'display:none;';
			          ?></b></td>
		    			</tr>
		    			<tr id="tr_imov_condom_nome" style="<?=$display_dados_condominio;?>">
		    			  <td align="right" valign="top">Nome do Condomínio:</td>
			          <td align="left"><b><?=$aProposta["imovel"]["nomecondominio_imov"];?></b></td>
		    			</tr>
		    			<tr id="tr_imov_condom_tipo" style="<?=$display_dados_condominio;?>">
		    			  <td align="right" valign="top">Tipo Condomínio:</td>
			          <td align="left"><b><?
			          	$vTMP = $aProposta["imovel"]["tpcondominio_imov"];
			          	$aTMP = $listas->getListaTipoCondominio($vTMP);
			          	print $aTMP[$vTMP];
			          ?></b></td>
			        </tr>
		    			<tr>
		    			  <td align="right" valign="top">Bloco:</td>
			          <td align="left"><b><?
			          	$vTMP = $aProposta["imovel"]["flgbloco_imov"];
			          	$aTMP = $listas->getListaSN();
			          	print $aTMP[$vTMP];
			          	$display_dados_bloco = ($aProposta["imovel"]["flgbloco_imov"]=='S')?'':'display:none;';
			          ?></b></td>
		    			</tr>
		    			<tr id="tr_imov_bloco_numero" style="<?=$display_dados_bloco;?>">
		    			  <td align="right" valign="top">Número do Bloco:</td>
			          <td align="left"><b><?=$aProposta["imovel"]["numbloco_imov"];?></b></td>
		    			</tr>
		    			<tr id="tr_imov_bloco_edificio" style="<?=$display_dados_bloco;?>">
		    			  <td align="right" valign="top">Edifício:</td>
			          <td align="left"><b><?=$aProposta["imovel"]["edificio_imov"];?></b></td>
		    			</tr>
		    			<tr id="tr_imov_bloco_conjunto" style="<?=$display_dados_bloco;?>">
		    			  <td align="right" valign="top">Conjunto:</td>
			          <td align="left"><b><?=$aProposta["imovel"]["conjunto_imov"];?></b></td>
		    			</tr>
			      	<tr>
			          <td align="right" valign="top">Imóvel Térreo:</td>
			          <td align="left"><b><?
			          	$vTMP = $aProposta["imovel"]["terreo_imov"];
			          	$aTMP = $listas->getListaImovelTerreo($vTMP);
			          	print $aTMP[$vTMP];
			          ?></b></td>
			        </tr>
			      	<tr>
			          <td align="right" valign="top">Tipo de Moradia:</td>
			          <td align="left"><b><?
			          	$vTMP = $aProposta["imovel"]["tpmoradia_imov"];
			          	$aTMP = $listas->getListaTipoMoradia($vTMP);
			          	print $aTMP[$vTMP];
			          ?></b></td>
			        </tr>
			      </table>
	    		</div>
	    		<div style="float:right; margin-top:10px; margin-bottom:10px; width:430px;">
		    		<table cellpadding=0 cellspacing=5 border=0>
		    			<tr>
		    			  <td align="right" valign="top">Aquisição do imóvel de pai ou mãe:</td>
			          <td align="left"><b><?
			          	$vTMP = $aProposta["imovel"]["aquispaimae_imov"];
			          	$aTMP = $listas->getListaSN();
			          	print $aTMP[$vTMP];
			          	$display_dados_paimae = ($aProposta["imovel"]["aquispaimae_imov"]=='S')?'':'display:none;';
			          ?></b></td>
		    			</tr>
		    			<tr id="tr_imov_aquis_paimae" style="<?=$display_dados_paimae;?>">
		    			  <td align="right" valign="top">Possui irmãos:</td>
			          <td align="left"><b><?
			          	$vTMP = $aProposta["imovel"]["possuiirmaos_imov"];
			          	$aTMP = $listas->getListaSN();
			          	print $aTMP[$vTMP];
			          ?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Imóvel tombado, desapropriado <br /> ou condenado por órgão público:</td>
			          <td align="left"><b><?
			          	$vTMP = $aProposta["imovel"]["tmbdspcndop_imov"];
			          	$aTMP = $listas->getListaSN();
			          	print $aTMP[$vTMP];
			          ?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Imóvel incombustível:</td>
			          <td align="left"><b><?
			          	$vTMP = $aProposta["imovel"]["incomb_imov"];
			          	$aTMP = $listas->getListaSN();
			          	print $aTMP[$vTMP];
			          ?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Imóvel localizado em área rural ou favela:</td>
			          <td align="left"><b><?
			          	$vTMP = $aProposta["imovel"]["ruralfav_imov"];
			          	$aTMP = $listas->getListaSN();
			          	print $aTMP[$vTMP];
			          ?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Imóvel em construção:</td>
			          <td align="left"><b><?
			          	$vTMP = $aProposta["imovel"]["emconstr_imov"];
			          	$aTMP = $listas->getListaSN();
			          	print $aTMP[$vTMP];
			          ?></b></td>
		    			</tr>
			    	</table>
		    	</div>
		    </div>

		    <?
		    	if( is_array($aProposta["imovelvagas"]) && @count($aProposta["imovelvagas"])>0 ){
		    		?>
							<div id="divVagasGaragemContainer" class="grupoDados" style="clear:both; margin:10px 0px; ">
								<?
									$vg = 0;
									foreach($aProposta["imovelvagas"] as $vk=>$vv){
										$vg++;
										?>
											<div class="divVagaGaragem">
												<b>Vaga <?=$vg;?></b>
												<table cellpadding=0 cellspacing=5 border=0>
													<colgroup><col width='220' /><col /></colgroup>
													<tr>
														<td align='right' valign='top'>Tipo de Vaga:</td>
									          <td align="left"><b><?
									          	$vTMP = $vv["tpvaga_imvg"];
									          	$aTMP = $listas->getTipoVaga($vTMP);
									          	print $aTMP[$vTMP];
									          	$display_dados_vaga = ($vv["tpvaga_imvg"]=='I')?'':'display:none';
									          ?></b></td>
													</tr>
													<tr>
														<td align='right' valign='top'>Local da Vaga:</td>
									          <td align="left"><b><?
									          	$vTMP = $vv["local_imvg"];
									          	$aTMP = $listas->getLocalVaga($vTMP);
									          	print $aTMP[$vTMP];
									          ?></b></td>
													</tr>
													<tr>
														<td align='right' valign='top'>Área útil:</td>
									          <td align="left"><b><?=$utils->formataFloat($vv["areautil_imvg"],4);?> m²</b></td>
													</tr>
													<tr>
														<td align='right' valign='top'>Área comum:</td>
									          <td align="left"><b><?=$utils->formataFloat($vv["areacomum_imvg"],4);?> m²</b></td>
													</tr>
													<tr>
														<td align='right' valign='top'>Área total:</td>
									          <td align="left"><b><?=$utils->formataFloat($vv["areatotal_imvg"],4);?> m²</b></td>
													</tr>
													<tr>
														<td align='right' valign='top'>Fração ideal:</td>
									          <td align="left"><b><?=$utils->formataFloat($vv["fracaoideal_imvg"],6);?> %</b></td>
													</tr>
													<tr id='tr_num_contrib_vaga_imov_<?=$vg;?>' style='<?=$display_dados_vaga;?>'>
														<td align='right' valign='top'>Número do Contribuinte:</td>
									          <td align="left"><b><?=$vv["nrcontribuinte_imvg"];?></b></td>
													</tr>
													<tr id='tr_num_reg_vaga_imov_<?=$vg;?>' style='<?=$display_dados_vaga;?>'>
														<td align='right' valign='top'>Número de Registro:</td>
									          <td align="left"><b><?=$vv["nrregistro_imvg"];?></b></td>
													</tr>
													<tr id='tr_num_matr_vaga_imov_<?=$vg;?>' style='<?=$display_dados_vaga;?>'>
														<td align='right' valign='top'>Número de Matrícula:</td>
									          <td align="left"><b><?=$vv["nrmatricula_imvg"];?></b></td>
													</tr>
													<tr id='tr_num_oficio_vaga_imov_<?=$vg;?>' style='<?=$display_dados_vaga;?>'>
														<td align='right' valign='top'>Número do ofício do registro de imóveis:</td>
									          <td align="left"><b><?=$vv["nroficioregistro_imvg"];?></b></td>
													</tr>
													<tr id='tr_local_oficio_vaga_imov_<?=$vg;?>' style='<?=$display_dados_vaga;?>'>
														<td align='right' valign='top'>Local do ofício do registro de imóveis:</td>
									          <td align="left"><b><?=$vv["localoficio_imvg"];?></b></td>
													</tr>
												</table>
											</div>
										<?
									}
								?>
							</div>
						<?
		    	}else{
		    		?>
		    		<div id="divVagasGaragemContainer" class="grupoDados" style="clear:both; margin:10px 0px; display:none;"></div>
		    		<?
		    	}
		    ?>
	    
				<div style="clear:both; height:10px;"></div>
				<? if($aProposta["imovel"]["dtavaliacao_imov"]){ ?>
				  <div class="grupoDados" style="width:475px;">
						<table cellpadding=0 cellspacing=5 border=0>
						<tr>
			  			  <td align="right" valign="top">Avaliação do Imóvel s/ Garagem (R$):</td>
			  			  <td align="left"><b><?=$utils->formataMoeda($aProposta["imovel"]["vlavalsemgar_imov"]);?></b></td>
			  			</tr>
						<tr>
			  			  <td align="right" valign="top">Avaliação da Garagem (R$):</td>
			  			  <td align="left"><b><?=$utils->formataMoeda($aProposta["imovel"]["vlavalgar_imov"]);?></b></td>
			  			</tr>
						<tr>
			  			  <td align="right" valign="top">Avaliação do Imóvel (R$):</td>
			  			  <td align="left" colspan=""><b><?=$utils->formataMoeda($aProposta["imovel"]["vlavaliacao_imov"]);?></b></td>
			  			</tr>
			  			<tr>
			  			  <td align="right" valign="top">Data da Avaliação do Imóvel:</td>
			  			  <td align="left" colspan="2"><b><?=$utils->formataDataBRA($aProposta["imovel"]["dtavaliacao_imov"]);?></b></td>
			  			</tr>
			  			<tr>
			  			  <td align="right" valign="top">Data de Aprovação do Imóvel:</td>
			  			  <td align="left"><b><?=$utils->formataDataBRA($aProposta["imovel"]["dtaprovacao_imov"]);?></b></td>
			             <td align="left" valign="top" style="padding-left:5px;">
						 <?
						 if( $cLOGIN->iLEVEL_USUA == TPUSER_ATENDENTE){
						 ?>
						 <img name="btCancImov" id="btCancImov" src="images/buttons/bt_cancelar.gif" alt="Cancelar Aprovação de Imóvel"  class="im" onClick="cancImovel('<?=$crypt->encrypt('CancImovel');?>')" />   
						 <input type="hidden" name="cancImov" id="cancImov" value="">
						 <? /*depois de voltar a resposta da previ verificar se é preciso
			       	    exibir botao "cancelar aprovação" que irá limpar o campo da
			       	    "data de aprovação" e deixará o form imovel aberto*/ }?>
			          </td>
			  			</tr>
			  		</table>
			  	</div>
		  	<? }?>
		  	
		  	<div id="ckls_imov"><? if($cLOGIN->iLEVEL_USUA!=TPUSER_PROPONENTE){
			echo qd_exigencia('imov_exigencia','imov_BtExig','imov_addexig', 'imov_btsalvarexigencia',$_POST['imov_addexig'],$aProposta["cod_ppst"],'fimexigimov');}
			 include('alt_ckls_imovel.inc.php'); ?></div>
		  	
				<table cellpadding=0 cellspacing=5 border=0 style="margin-top:5px;">
					<tr>
						<td align="right" valign="top">Despachante:<? $utils->obrig('cod_despachante_imov'); ?></td>
						<td align="left"  valign="top"><b><?
							$vTMP = $aProposta["imovel"]["despachante_imov"];
							$aTMP = $oUsuario->getUsuario($vTMP);
							print ($aTMP[0]["nome_usua"]!='')?$aTMP[0]["nome_usua"]:'Nenhum despachante associado';
						?></b></td>
					</tr>
				</table>
	  	<? } ?>
		</div>
		<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
	</div>
</div>