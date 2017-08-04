<?php

session_start();

require_once ("constantes.php");
$PATH = C_PATH;
require_once ("{$PATH}init.php");
include (C_PATH_CONFIGURACION."configuracion.php");
require_once ("{$PATH}clases/sg_configuracion.php");
include ("{$PATH}clases/sgConnection.php");
//require_once ("{$PATH}clases/cls_conexion.php");
require_once ("{$PATH}clases/funciones.php");
require_once ("{$PATH}clases/funciones_sg.php");
require_once ("{$PATH}clases/cls_reporte.php");
require_once ("{$PATH}clases/cls_documento.php");

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

$vses = &$ses;

$vform = &$_GET;

?>