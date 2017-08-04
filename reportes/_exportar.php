<?php
session_start();
require_once ("constantes.php");
require_once ("clases/sg_constantes.php");
require_once ("clases/cls_conexion.php");
require_once ("clases/funciones.php");
require_once ("clases/funciones_sg.php");
require_once ("clases/cls_reporte.php");
require_once ("clases/cls_documento.php");
$rep = new cls_reporte;
$ins = "";
if(isset($_GET["cfg_ins_aux"]) or isset($_POST["cfg_ins_aux"])){
	$ins = $_GET["cfg_ins_aux"];
	$aut = $_SESSION["VSES"][$ins]["SS_AUT"];
	$ses = &$_SESSION["VSES"][$ins];

}else{	
	$aut = false;

}
if(!$aut){
	echo "no tiene autorizacion";
	exit;
}// end if

$rep->vses = &$ses;

$rep->vform = &$_GET;


$doc = new cls_documento;

$nombre = $rep->vform["rep_nombre"];
$doc->title = $rep->titulo;

header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Disposition: attachment; filename=rep_$nombre.xls");
//$doc->body ='<link rel="stylesheet" type="text/css" href="css/sg_reportes.css">';
$doc->body = $rep->control($rep->vform["rep_nombre"]);
echo $doc->control(); 
?>