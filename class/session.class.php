<?php

class session {
	function session() {
		@session_start();
	}
	
	function setMessage($message) {
		$_SESSION["message"] = $message;
	}
	
	function getMessage($clearMessage = false) {
		$returnMessage = $_SESSION["message"];
		if ($clearMessage) {
			unset($_SESSION["message"]);
		}
		return $returnMessage;
	}
	
}

?>