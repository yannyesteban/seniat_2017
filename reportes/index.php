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

$rep->vses = &$ses;
$rep->vform = &$_GET;
//echo '<link rel="stylesheet" type="text/css" href="css/sg_reportes.css">';

$doc = new cls_documento;

if(defined('C_HOJA_CSS')){
	if(is_array(C_HOJA_CSS)){
		foreach(C_HOJA_CSS as $css){
			$doc->style($css); 
		}
	}else{
		$css = explode(",", C_HOJA_CSS);
		foreach($css as $k => $v){
			$doc->style(trim($v)); 
		}//next
	}
}// end if

if(defined('C_JAVASCRIPT')){
	if(is_array(C_JAVASCRIPT)){
		foreach(C_JAVASCRIPT as $js){
			if($js[1]){
				$doc->js_post($js[0]);
			}else{
				$doc->js($js[0]);
			}
		}
	}else{
		$js = explode(",", C_JAVASCRIPT);
		foreach($js as $k => $v){
			$doc->js_post(trim($v));
		}//next
	}
}// end if

$doc->class = $rep->vform["rep_nombre"];
$doc->title = $rep->titulo;
$doc->body = $rep->control($rep->vform["rep_nombre"]);

echo $doc->control();

?>