<?php
include "./class/dbclasses.class.php";
$db->query="Select * from proposta where situacao_ppst <= 11";
$db->query();
$i=0;
while($i<$db->qrcount)
{
	if(mkdir ("imagens_previ/".$db->qrdata[$i]['COD_PPST'], 0777))
	{
		echo "Pasta /".$db->qrdata[$i]['COD_PPST']."/ criada para a proposta ".$db->qrdata[$i]['COD_PPST']."<br>";
	}
	$i++;
}


?>