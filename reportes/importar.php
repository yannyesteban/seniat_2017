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
$rep->vses = &$_SESSION[V_VSESION];
$rep->vform = &$_GET;
//echo '<link rel="stylesheet" type="text/css" href="css/sg_reportes.css">';

$doc = new cls_documento;
$doc->body = $rep->control($rep->vform["rep_nombre"]);
$nombre = $rep->vform["rep_nombre"];
$doc->title = $rep->titulo;
//echo $doc->control();



$nombre_archivo = 'prueba.xls';

$contenido = $doc->control();



// Asegurarse primero de que el archivo existe y puede escribirse sobre él.
//if (is_writable($nombre_archivo)) {

    // En nuestro ejemplo estamos abriendo $nombre_archivo en modo de adición.
    // El apuntador de archivo se encuentra al final del archivo, así que
    // allí es donde irá $contenido cuando llamemos fwrite().
    if (!$gestor = fopen($nombre_archivo, 'w')) {
         echo "No se puede abrir el archivo ($nombre_archivo)";
         exit;
    }

    // Escribir $contenido a nuestro arcivo abierto.
	
    if (fwrite($gestor, $contenido) === FALSE) {
       // echo "No se puede escribir al archivo ($nombre_archivo)";
        exit;
    }

    //echo "Éxito, se escribió ($contenido) al archivo ($nombre_archivo)";

    fclose($gestor);


//header('Content-type: application/xls');
//header ("location: prueba.xls");
//unlink($nombre_archivo);


header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Disposition: attachment; filename=rep_$nombre.xls");

//header("Content-type: application/vnd.ms-excel");
//header("Content-Disposition: attachment; filename=arx_fecha.xls");
//header("Pragma: no-cache");
//header("Expires: 0");

echo $contenido; 
?>