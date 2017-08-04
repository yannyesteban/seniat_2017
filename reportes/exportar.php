<?php
$file_name = "report";
if(isset($_GET["rep_nombre"])){
	$file_name = $_GET["rep_nombre"];
}

header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Disposition: attachment; filename=rep_{$file_name}.xls");
include("index.php");

?>