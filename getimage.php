<?
ob_start();
include "./class/dbclasses.class.php";

if ($_GET["img"]) {
	readfile("./files/".$_GET["img"]);
} else {
	ob_end_clean();
	header("Location: index.php");
	exit();
}
ob_end_flush();
?>