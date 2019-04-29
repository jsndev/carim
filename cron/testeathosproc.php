<?php

include "../class/dbclasses.class.php";
$oArquivo = new arquivo();
	$db->query="Select * from vendedor where cod_ppst='1219'";
					$db->query();
					
					if($db->qrcount>0)
					{
						$x=$db->qrcount;
						$v=0;
						while ($v<$x)
						{
							
							$query3="Select * from vendtelefone where cod_vend='".$db->qrdata[$v]['COD_VEND']."'";
							$result3 =mysql_query($query3);
							$linhas= mysql_num_rows($result3);
							$rg = mysql_fetch_array($result3, MYSQL_ASSOC);
							
							if($db->qrdata[$v]['TIPO_VEND']==1){
							$query="Select * from vendfis where cod_vend='".$db->qrdata[$v]['COD_VEND']."'";
							$result =mysql_query($query);
							$linhas= mysql_num_rows($result);
							$registro = mysql_fetch_array($result, MYSQL_ASSOC);
							$query2="Select * from vendfisconjuge where cod_vend='".$db->qrdata[$v]['COD_VEND']."'";
							$result2 =mysql_query($query2);
							$linhas= mysql_num_rows($result2);
							$reg = mysql_fetch_array($result2, MYSQL_ASSOC);
							}
							
							if($db->qrdata[$v]['TIPO_VEND']==2){
							$query="Select * from vendjur where cod_vend='".$db->qrdata[$v]['COD_VEND']."'";
							$result =mysql_query($query);
							$linhas= mysql_num_rows($result);
							$registro = mysql_fetch_array($result, MYSQL_ASSOC);
							$query2="Select * from vendjursocio where cod_vend='".$db->qrdata[$v]['COD_VEND']."'";
							$result2 =mysql_query($query2);
							$linhas= mysql_num_rows($result2);
							$reg = mysql_fetch_array($result2, MYSQL_ASSOC);
							}
							
		
				
								echo	'1 ' . ($db->qrdata[$v]['TIPO_VEND']		==1)  . "<br />";
								echo	'2 ' . ($registro["CPF_VFISICA"]   		!="")  . "<br />";
								echo	'3 ' . ($registro['SEXO_VFISICA']  		!="")  . "<br />";
								echo	'4 ' . ($registro["DTNASCIMENTO_VFISICA"]  !="")  . "<br />";
								echo	'5 ' . ($registro['COD_PAIS']   			!="")  . "<br />";
								echo	'6 ' . ($registro['NATUR_VFISICA']   		!="")  . "<br />";
								echo	'7 ' . ($registro['COD_TPDOC']   			!="")  . "<br />";
								echo	'8 ' . ($registro['NRRG_VFISICA']   		!="")  . "<br />";
								echo	'9 ' . ($registro['DTRG_VFISICA']   		!="")  . "<br />";
								echo	'10 ' . ($registro['ORGRG_VFISICA']   		!="")  . "<br />";
								echo	'11 ' . ($registro['COD_ESTCIV']   			!="")  . "<br />";
								echo	'12 ' . ($registro['NOMEPAI_VFISICA']   	!="")  . "<br />";
								echo	'13 ' . ($registro['NOMEMAE_VFISICA']   	!="")  . "<br />";
								echo	'14 ' . ($registro['COD_PROF']   			!="")  . "<br />";
								echo	'15 ' . ($registro['VLRENDA_VFISICA']   	!="")   . "<br />";
							
								
								echo $oArquivo->formatString($rg['TELEFONE_VNTL'],12);
								
				echo "<br />";
				$v++;
				}
				
				}
					
					
					
					
?>		



