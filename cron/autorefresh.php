<html>
<body topmargin="0" leftmargin="0" onLoad="disparar();">
<script>

function atualizar() {
parent.frameautoproc.location='frameautoproc.php';
}

function disparar() {
intervalo = window.setInterval(atualizar, 10000);
}
</script>
  </body>
</html>