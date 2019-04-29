<?php

//$iREQ_AUT=1;
//$aUSERS_PERM[]=8;
$pageTitle = "Processando Imagem";
include "lib/header.inc.php";
include("lib/calendar.php");
$mensagem = new mensagens();
$forms = new forms();
$renova_login=$_GET['login'];
$cod_ppst=$_GET['ps'];
$type=$_GET['type'];
$acaoProposta = $crypt->decrypt($_POST["acaoProposta"]);
$acaoProposta='status';
// Pasta de destino das fotos
$Destino = '/var/www/html/carim/imagens_previ/'.$cod_ppst.'/';
// Obtém dados do upload
$Fotos = $_FILES['fotos'];
// Contagem de fotos enviadas
$Conta = 0;




if (!file_exists($Destino)) {
	mkdir($Destino, 0777);
}

// Itera sobre as enviadas e processa as validações e upload
for($i = 0; $i < sizeof($Fotos); $i++)
{
    // Passa valores da iteração atual
    $Nome    = md5($Fotos['name'][$i]);
    $Tamanho = $Fotos['size'][$i];
    $Tipo    = $Fotos['type'][$i];
    $Tmpname = $Fotos['tmp_name'][$i];
	

    // Verifica se tem arquivo enviado
    if($Tamanho > 0 && strlen($Nome) > 1)
    {
     
     $Destino .= $Nome;
     //echo $Destino;
	 //umask(0000);  	
            //echo aki;
			// Caminho completo de destino da foto
            //$Caminho = $Destino . $Nome;
			//umask(0000);
            // Tudo OK! Move o upload!
            if(move_uploaded_file($Tmpname, $Destino))
            {
            	chmod ($Destino, 0777);
                $db->query="INSERT INTO imagem (CATEGORIA,NOME,COD_PPST) VALUES ('".$type."','".$Fotos['name'][$i]."','".$cod_ppst."')";
				//echo $db->query;
				$db->query();
				echo '<div style="vertical-align: middle; padding: 5px; margin: 5px; border: 1px solid #13632C; background-color: #CBE5CF; color: #13632C; font-weight: bold;">
			          <img src="images/mensagens/sucesso.gif" alt="Sucesso" style="vertical-align: middle;" />Foto ' . ($i+1) . ' enviada.</div>';
                
                // Faz contagem de enviada com sucesso
                $Conta++;
            }            
            else // Erro no envio
            {
                // $i+1 porque $i começa em zero
				echo '
					<div style="vertical-align: middle; padding: 5px; margin: 5px; border: 1px solid #CA1D1D; background-color: #EBCACA; color: #CA1D1D; font-weight: bold;">
						<img src="images/mensagens/erro.gif" alt="Erro" style="vertical-align: middle;" />
						Não foi possível enviar a Foto ' . ($i+1) . '</div>
				';
            }
    }
}

if($Conta) // Imagens foram enviadas, ok!
{
    echo '<div style="vertical-align: middle; padding: 5px; margin: 5px; border: 1px solid #13632C; background-color: #CBE5CF; color: #13632C; font-weight: bold;">
			          <img src="images/mensagens/sucesso.gif" alt="Sucesso" style="vertical-align: middle;" />Foi(am) enviada(s) ' . $Conta . ' foto(s).</div>';
}
else // Nenhuma imagem enviada, faz alguma ação
{
    echo '<div style="vertical-align: middle; padding: 5px; margin: 5px; border: 1px solid #797512; background-color: #EEEDD6; color: #797512; font-weight: bold;">
						<img src="images/mensagens/alerta.gif" alt="Alerta" style="vertical-align: middle;" />
						Você não enviou fotos!
					</div>
				';
	//echo 'Você não enviou fotos!';
}
include "lib/footer.inc.php";
?>