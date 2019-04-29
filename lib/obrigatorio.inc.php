<?
/* usar: <? $utils->obrig('id_campo_xyz'); ?> */
$obrigatorio = array();
$obrigatorio[1] = array(
/* Proposta ---------------------------------------------------------------------------------- */
'valorcompra_ppst','vlprestsol_ppst','przfinsol_ppst',//'valorseguro_ppst','valormanutencao_ppst',
/* Proponente -------------------------------------------------------------------------------- */
'vlcompra_ppnt','vlprestsol_ppnt','przfinsol_ppnt',//'vlentrada_ppnt','vlsinal_ppnt',
'cpf_ppnt','compos_renda_ppnt','dtnascimento_ppnt','cod_estciv_ppnt',//'dtcasamento_ppcj',
//'regimebens_ppcj','data_pcpa','locallavracao_pcpa','livro_pcpa','folha_pcpa','numeroregistro_pcpa',
'cod_logr_ppnt','endereco_ppnt','nrendereco_ppnt','cod_uf_ppnt',
'cod_municipio_ppnt','cod_bairro_ppnt','cep_ppnt','flgproc_ppnt','telefone_ppnt_1','tipotelefone_ppnt_1',
/* Proponente - empresa ---------------------------------------------------------------------- */
//'empresa_ppnt','dtadmissaoemp_ppnt','enderecoemp_ppnt','nrenderecoemp_ppnt','estadoemp_ppnt',
//'cidadeemp_ppnt','bairroemp_ppnt','telefoneemp_ppnt','cargoemp_ppnt','salarioemp_ppnt',
/* Proponente - conjuge ---------------------------------------------------------------------- */
'nome_ppcj','cpf_pccj',//'prop_conjuge_nacionalidade','nrrg_ppcj','dtrg_ppcj','orgrg_ppcj',
//'flgtrabalha_ppcj','empresa_ppcj','dtadmissaoemp_ppcj','enderecoemp_ppcj','numeroemp_ppcj',
//'estadoemp_ppcj','cidadeemp_ppcj','bairroemp_ppcj','telefoneemp_ppcj','cargoemp_ppcj','salarioemp_ppcj',
/* Proponente - devedor solidario ------------------------------------------------------------ */
//'nome_devsol','nick_devsolnick_devsol','logr_devsol','endereco_devsol','nrendereco_devsol','uf_devsol',
//'municipio_devsol','bairro_devsol','cep_devsol','telefone_devsol','sexo_devsol',//'cpf_devsol','pais_devsol',
/* Pagamento --------------------------------------------------------------------------------- */
//'flgboletoavalpago_ppst','dtpagtoboleto_ppst',
/* Imovel ------------------------------------------------------------------------------------ */
'cod_logr_imov','endereco_imov','nrendereco_imov','cod_uf_imov','cod_municipio_imov','cod_bairro_imov','cep_imov',
//'qtsala_imov','qtquarto_imov','qtbanh_imov','qtgarag_imov','qtpavim_imov','qtdepemp_imov',
//'area_imov','tpimposto_imov','tipo_imov','tipo_apartam','area_util','area_comum','area_total',
//'tpconstrucao_imov','estconserv_imov','estconspred_imov','andar_imov','pavimento_imov',
//'vagas_garagem_imov','isolado_imov','condominio_imov','nome_condominio_imov','tpcondominio_imov',
//'bloco_imov','numero_bloco_imov','edificio_bloco_imov','conjunto_bloco_imov','terreo_imov',
//'tpmoradia_imov','tmbdspcndop_imov','incomb_imov','ruralfav_imov','emconstr_imov','vagas_garagem',
//'vlavaliacao_imov','dtavaliacao_imov','dtaprovacao_imov',
/* Vendedores -------------------------------------------------------------------------------- */
'vend_tipo','vend_nome','vend_nabrev',//'vend_porcentagem',
'vend_cpf',//'vend_sexo','vend_nasc','vend_nacion','vend_natural','vend_tpdoc','vend_rg',
//'vend_dtrg','vend_orgrg','vend_civil','vend_dtcasamento_ppcj','vend_regimebens_ppcj',
//'vend_data_pcpa','vend_locallavracao_pcpa','vend_livro_pcpa','vend_folha_pcpa',
//'vend_numeroregistro_pcpa','vend_npai','vend_nmae','vend_profiss','vend_rendim','vend_inss',
'vend_cnpj',//'vend_pispasep','vend_cofins','vend_csll','vend_cnae',
//'vend_logr','vend_ender','vend_num','cod_uf_vend','cod_municipio_vend','cod_bairro_vend',
//'vend_cep','vend_fone_1','vend_nrcc','vend_nrag',
/* Vendedores - conjuge ---------------------------------------------------------------------- */
//'vend_nome_ppcj','vend_cod_pais_ppcj','vend_nrrg_ppcj','vend_dtrg_ppcj','vend_orgrg_ppcj',
//'vend_cpf_pccj','vend_flgtrabalha_ppcj','vend_empresa_ppcj','vend_dtadmissaoemp_ppcj',
//'vend_enderecoemp_ppcj','vend_numeroemp_ppcj','vend_estadoemp_ppcj','vend_cidadeemp_ppcj',
//'vend_bairroemp_ppcj','vend_telefoneemp_ppcj','vend_cargoemp_ppcj','vend_salarioemp_ppcj',
/* Vendedores - socios ----------------------------------------------------------------------- */
//'vend_s_nome','vend_s_nabrev','vend_s_logr','vend_s_ender','vend_s_num','vend_s_uf',
//'vend_s_cidade','vend_s_bairro','vend_s_cep','vend_s_fone','vend_s_cpf','vend_s_sexo',
//'vend_s_nacion','prop_agendam','prop_remessa',
);
$obrigatorio[2] = array(
/* Proposta ---------------------------------------------------------------------------------- */
'valorcompra_ppst','vlprestsol_ppst','przfinsol_ppst','dtapresdoc_ppst','valorseguro_ppst','valormanutencao_ppst',
/* Proponente -------------------------------------------------------------------------------- */
'vlcompra_ppnt','vlprestsol_ppnt','przfinsol_ppnt',//'vlentrada_ppnt','vlsinal_ppnt',
'cpf_ppnt','compos_renda_ppnt','dtnascimento_ppnt','sexo_ppnt','nacional_ppnt','nrrg_ppnt','orgrg_ppnt','dtrg_ppnt','cod_estciv_ppnt','dtcasamento_ppcj',
'regimebens_ppcj','data_pcpa','locallavracao_pcpa','livro_pcpa','folha_pcpa',
'numeroregistro_pcpa','habens_pcpa','habenscart_pcpa','habensloccart_pcpa','habensdata_pcpa','cod_logr_ppnt','endereco_ppnt','nrendereco_ppnt','cod_uf_ppnt',
'cod_municipio_ppnt','cod_bairro_ppnt','cep_ppnt','telefone_ppnt_1','tipotelefone_ppnt_1',
/* Proponente - empresa ---------------------------------------------------------------------- */
'empresa_ppnt','dtadmissaoemp_ppnt','enderecoemp_ppnt','nrenderecoemp_ppnt','estadoemp_ppnt',
'cidadeemp_ppnt','bairroemp_ppnt','telefoneemp_ppnt','cargoemp_ppnt','salarioemp_ppnt',
/* Proponente - conjuge ---------------------------------------------------------------------- */
'nome_ppcj','prop_conjuge_nacionalidade','nrrg_ppcj','dtrg_ppcj','orgrg_ppcj','cpf_pccj',
'flgtrabalha_ppcj','empresa_ppcj','dtadmissaoemp_ppcj','enderecoemp_ppcj','numeroemp_ppcj',
'estadoemp_ppcj','cidadeemp_ppcj','bairroemp_ppcj','telefoneemp_ppcj','cargoemp_ppcj','salarioemp_ppcj',
/* Proponente - devedor solidario ------------------------------------------------------------ */
'flgdevsol_ppnt','nome_devsol','nick_devsolnick_devsol','logr_devsol','endereco_devsol','nrendereco_devsol','uf_devsol',
'municipio_devsol','bairro_devsol','cep_devsol','telefone_devsol','cpf_devsol','sexo_devsol','pais_devsol',
/* Pagamento --------------------------------------------------------------------------------- */
'flgboletoavalpago_ppst','dtpagtoboleto_ppst',
/* Imovel ------------------------------------------------------------------------------------ */
'area_imov','tpimposto_imov','tipo_imov','tipo_apartam','area_util','area_comum','area_total',
'tpconstrucao_imov','estconserv_imov','estconspred_imov',//'andar_imov','pavimento_imov',
'vagas_garagem_imov','isolado_imov','condominio_imov','nome_condominio_imov','tpcondominio_imov',
'bloco_imov','numero_bloco_imov','edificio_bloco_imov','conjunto_bloco_imov','terreo_imov',
'tpmoradia_imov','aquispaimae_imov','possuiirmaos_imov','tmbdspcndop_imov','incomb_imov',
'ruralfav_imov','emconstr_imov','vagas_garagem',
'cod_logr_imov','endereco_imov','nrendereco_imov','cod_uf_imov','cod_municipio_imov','cod_bairro_imov','cep_imov',
'qtsala_imov','qtquarto_imov','qtbanh_imov','qtgarag_imov','qtpavim_imov','qtdepemp_imov',
'vlavalsemgar_imov','vlavalgar_imov','vlavaliacao_imov','dtavaliacao_imov','dtaprovacao_imov',
/* Vendedores -------------------------------------------------------------------------------- */
'vend_tipo','vend_nome','vend_nabrev','vend_porcentagem',
'vend_cpf','vend_sexo','vend_nasc','vend_nacion','vend_natural','vend_tpdoc','vend_rg',
'vend_dtrg','vend_orgrg','vend_civil','vend_dtcasamento_ppcj','vend_regimebens_ppcj',
'vend_data_pcpa','vend_locallavracao_pcpa','vend_livro_pcpa','vend_folha_pcpa',
'vend_numeroregistro_pcpa','vend_npai','vend_nmae','vend_profiss','vend_rendim',//'vend_inss',
'vend_cnpj','vend_pispasep','vend_cofins','vend_csll','vend_cnae',
'vend_logr','vend_ender','vend_num','cod_uf_vend','cod_municipio_vend','cod_bairro_vend',
'vend_cep','vend_fone_1','vend_nrcc','vend_nrag',
/* Vendedores - conjuge ---------------------------------------------------------------------- */
'vend_nome_ppcj','vend_cod_pais_ppcj','vend_nrrg_ppcj','vend_dtrg_ppcj','vend_orgrg_ppcj',
'vend_cpf_pccj','vend_flgtrabalha_ppcj','vend_empresa_ppcj','vend_dtadmissaoemp_ppcj',
'vend_enderecoemp_ppcj','vend_numeroemp_ppcj','vend_estadoemp_ppcj','vend_cidadeemp_ppcj',
'vend_bairroemp_ppcj','vend_telefoneemp_ppcj','vend_cargoemp_ppcj','vend_salarioemp_ppcj',
/* Vendedores - socios ----------------------------------------------------------------------- */
'vend_s_nome','vend_s_nabrev','vend_s_logr','vend_s_ender','vend_s_num','vend_s_uf',
'vend_s_cidade','vend_s_bairro','vend_s_cep','vend_s_fone','vend_s_cpf','vend_s_sexo',
'vend_s_nacion','prop_agendam','prop_remessa',
);
?>