<?php
$tabla="contribuyentes2";
$archivo="C:\www\seniat_2014\_rif\c0_01.txt";
$eliminar="0";
$separador=";";
$decode=false;

include ("C:\www\seniat_2014\constantes.php");
include ("C:\www\sigefor21\clases\sg_configuracion.php");
include ("C:\www\sigefor21\clases\\funciones.php");
include ("C:\www\sigefor21\clases\\funciones_sg.php");
include ("C:\www\sigefor21\clases\cls_conexion.php");
include ("cls_migrador2.php");
$aa=array();
$aa[0]="C:\www\seniat_2014\_rif\c0_02.txt";
$aa[1]="C:\www\seniat_2014\_rif\c0_03.txt";
$aa[2]="C:\www\seniat_2014\_rif\c0_04.txt";
$aa[3]="C:\www\seniat_2014\_rif\c0_05.txt";
foreach($aa as $k => $v){
	pg_migrar($tabla, $v, $eliminar, $separador, $decode);
}

?>