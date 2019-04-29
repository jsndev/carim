function salvarBlocoPagamento(){
	if(document.getElementById('bloco_pagamento')){
		if(document.getElementById('flgboletoavalpago_ppst')){
			if( document.getElementById('flgboletoavalpago_ppst').checked || !vVazio('dtpagtoboleto_ppst') ){
				if(!vCheck('flgboletoavalpago_ppst','a Confirmação de Pagamento')) return false;
				if(!vData('dtpagtoboleto_ppst','a Data de Pagamento')) return false;
				if( comparaDatasId('dtpagtoboleto_ppst','frm_data_ppst')==3 ){
					alert('A Data de Pagamento do Boleto não pode ser menor que a Data de Cadastramento da Proposta!');
			  	foco('dtpagtoboleto_ppst');
			  	return false;
				}
				if( comparaDatasId('dtpagtoboleto_ppst','')==1 ){
					alert('A Data de Pagamento do Boleto deve ser menor ou igual a Data Atual!');
					foco('dtpagtoboleto_ppst');
					return false;
				}
			}
		}
	}
	return true;
}

