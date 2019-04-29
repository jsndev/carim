<?
include "./class/dbclasses.class.php";
$cLOGIN->desloga();
header ("Location: index.php?k=".$crypt->encrypt(time()));
?>