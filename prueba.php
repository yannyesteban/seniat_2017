<?php 
session_start();

require_once ("constantes.php");
require_once ("clases/sg_constantes.php");
require_once ("clases/cls_conexion.php");
require_once ("clases/funciones.php");
require_once ("clases/funciones_sg.php");


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

$vses = &$ses;

$vform = &$_GET;

?>