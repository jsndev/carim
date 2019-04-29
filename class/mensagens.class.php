<?

define("MSG_ALERTA", 1);
define("MSG_SUCESSO", 2);
define("MSG_ERRO", 3);

class mensagens {

	var $mensagem;
	var $tipo;
	
	function mensagens($mensagem = "", $tipo = "") {
		$this->setMensagem($mensagem, $tipo);
	}
	
	function setMensagem($mensagem = "", $tipo = "") {
		$this->mensagem = $mensagem;
		$this->tipo = $tipo;
	}
	
	function haveMensagem() {
		return ($this->mensagem != "" && $this->tipo != "") ? true : false;
	}
	
	function getMensagem() {
		return $this->mensagem;
	}
	
	function getTipoMensagem() {
		return $this->tipo;
	}
	
	function getMessageBox() {
		$bufferRetorno = "";
		
		if ($this->haveMensagem()) {
			if ($this->getTipoMensagem() == MSG_ALERTA) {
				$bufferRetorno .= '
					<div style="vertical-align: middle; padding: 5px; margin: 5px; border: 1px solid #797512; background-color: #EEEDD6; color: #797512; font-weight: bold;">
						<img src="images/mensagens/alerta.gif" alt="Alerta" style="vertical-align: middle;" />
						'.$this->getMensagem().'
					</div>
				';
			} elseif ($this->getTipoMensagem() == MSG_ERRO) {
				$bufferRetorno .= '
					<div style="vertical-align: middle; padding: 5px; margin: 5px; border: 1px solid #CA1D1D; background-color: #EBCACA; color: #CA1D1D; font-weight: bold;">
						<img src="images/mensagens/erro.gif" alt="Erro" style="vertical-align: middle;" />
						'.$this->getMensagem().'
					</div>
				';
			} elseif ($this->getTipoMensagem() == MSG_SUCESSO) {
				$bufferRetorno .= '
					<div style="vertical-align: middle; padding: 5px; margin: 5px; border: 1px solid #13632C; background-color: #CBE5CF; color: #13632C; font-weight: bold;">
						<img src="images/mensagens/sucesso.gif" alt="Sucesso" style="vertical-align: middle;" />
						'.$this->getMensagem().'
					</div>
				';
			}
		}
		
		return $bufferRetorno;
	}
}
?>