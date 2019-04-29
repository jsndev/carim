<?
ob_start();
include "./class/dbclasses.class.php";

if ($_GET["arquivo"]) {
	header('Content-Disposition: attachment; filename="'.$_GET["origname"].'"');
	readfile("./files/".$_GET["arquivo"]);
} else {
	ob_end_clean();
	header("Location: index.php");
	exit();
}
ob_end_flush();
?>