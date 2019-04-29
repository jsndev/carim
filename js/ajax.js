
function Ajax() {
  try {
    return new ActiveXObject("Microsoft.XMLHTTP");
  } catch(e) {
    try {
      return new ActiveXObject("Msxml2.XMLHTTP");
    } catch(ex) {
      try {
        return new XMLHttpRequest();
      } catch(exc) {
        return false;
      }
    }
  }
}


function pegausr(id) {
	ajax = Ajax();
	if(ajax){
		pagina = "cidades.php?id=" + id;
		ajax.open("GET", pagina, true);
		ajax.onreadystatechange = processRequest
	ajax.send(null);
	}
}

function processRequest() {
			if(ajax.readyState == 4){
				if(ajax.status == 200){
					var text=ajax.responseText;
					document.getElementById('cidades').innerHTML=text
				} else {
				<!-- ajax.statusText; -->
				}
			}
}

