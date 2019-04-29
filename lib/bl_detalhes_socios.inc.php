<table cellpadding=0 cellspacing=5 border=0 class="tb_dets_list">
	<colgroup><col width="180" /><col /></colgroup>
  <tr>
    <td align="right" valign="top">Nome do Sócio:</td>
    <td align="left"  valign="top"><b><?=$vSocio['nome_vjsoc'];?></b></td>
  </tr>
  <tr>
    <td align="right" valign="top">Nome do Sócio Abrev:</td>
    <td align="left"  valign="top"><b><?=$vSocio['nick_vjsoc'];?></b></td>
  </tr>
  <tr>
    <td align="right" valign="top">Tipo Logradouro:</td>
    <td align="left"  valign="top"><b><?=$vSocio['logradouro'][0]['desc_logr'];?></b></td>
  </tr>
  <tr>
    <td align="right" valign="top">Endereço:</td>
    <td align="left"  valign="top"><b><?=$vSocio['endereco_vjsoc'];?></b></td>
  </tr>
  <tr>
    <td align="right" valign="top">Num:</td>
    <td align="left"  valign="top"><b><?=$vSocio['nrendereco_vjsoc'];?></b></td>
  </tr>
  <tr>
    <td align="right" valign="top">Complemento:</td>
    <td align="left"  valign="top"><b><?=$vSocio['cpendereco_vjsoc'];?></b></td>
  </tr>
  <tr>
    <td align="right" valign="top">Bairro:</td>
    <td align="left"  valign="top"><b><?=$vSocio['bairro'][0]['nome_bairro'];?></b></td>
  </tr>
  <tr>
    <td align="right" valign="top">Cidade:</td>
    <td align="left"  valign="top"><b><?=$vSocio['municipio'][0]['nome_municipio'];?></b></td>
  </tr>
  <tr>
    <td align="right" valign="top">Estado:</td>
    <td align="left"  valign="top"><b><?=$vSocio['uf'][0]['nome_uf'];?></b></td>
  </tr>
  <tr>
    <td align="right" valign="top">CEP:</td>
    <td align="left"  valign="top"><b><?=$utils->formataCep($vSocio['cep_vjsoc']);?></b></td>
  </tr>
  <tr>
    <td align="right" valign="top">Telefone:</td>
    <td align="left"  valign="top"><b><?=$utils->formataTelefone($vSocio['telefone_vjsoc']);?></b></td>
  </tr>
  <tr>
    <td align="right" valign="top">CPF:</td>
    <td align="left"  valign="top"><b><?=$utils->formataCPF($vSocio['cpf_vjsoc']);?></b></td>
  </tr>
  <tr>
    <td align="right" valign="top">Sexo:</td>
    <td align="left"  valign="top"><b><?
    	$vTMP = $vSocio['sexo_vjsoc'];
    	$aTMP = $listas->getListaSexo($vTMP);
    	print $aTMP[$vTMP];
    ?></b></td>
  </tr>
  <tr>
    <td align="right" valign="top">Nacionalidade:</td>
    <td align="left"  valign="top"><b><?
    	$vTMP = $vSocio['cod_pais'];
    	$aTMP = $listas->getListaPais($vTMP);
    	print $aTMP[0]["nome_pais"];
    ?></b></td>
  </tr>
    <tr>
    <td align="right" valign="top">Estado Civil:</td>
    <td align="left"  valign="top"><b>
<?
    	$vTMP = $vSocio['cod_estciv'];
    	$aTMP = $listas->getListaECivil($vTMP);
    	print $aTMP[0]["desc_estciv"];
    ?>	</b>
    </td>
  </tr>
 <tr>
	<td align="right" valign="top">Profissão:</td>
	<td align="left"  valign="top"><b><?php echo $vSocio["cargo_vjsoc"];?></b></td>
  </tr>
</table>