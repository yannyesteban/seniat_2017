<?php
include("../cbdatos.php");

define ("C_MODULO_PRINCIPAL","cef");
define ("C_EST_DEFAULT","");
define ("C_SG_USUARIO","user");
define ("C_SG_CLAVE","123");

define ("C_PATH","../../sigefor2017/");
define ("C_PATH_SEVIAN","../../../sevian/");
define ("C_PATH_CONFIGURACION","../../sigefor2017/");
define ("C_PATH_IMAGENES","../imagenes/");
define ("C_PATH_CSS","../css/");
define ("C_PATH_PLANTILLAS","plantillas/");
define ("C_PATH_ARCHIVOS","archivos/");
define ("C_PATH_GRAFICO","../../sigefor20/pchart/pChart/");
define ("C_PATH_REPORTES","");
define ("C_PANEL_DEFAULT","4");
define ("C_PANEL_DEBUG","8");

$PATH = C_PATH;
$PATH_SEVIAN = C_PATH_SEVIAN;

define ("C_HOJA_CSS","

	../css/seniat_2017.css,
		
	{$PATH_SEVIAN}css/sgMenu_.css,
	{$PATH_SEVIAN}css/sgWindow.css,
	{$PATH_SEVIAN}css/sgCalendar.css,
	{$PATH_SEVIAN}css/selectText.css,
	{$PATH_SEVIAN}css/sgTab.css,
	
");

define ("C_JAVASCRIPT","

	{$PATH_SEVIAN}_js/_sgQuery.js,

	{$PATH_SEVIAN}js/drag.js,
	{$PATH_SEVIAN}js/sgWindow.js,
	{$PATH_SEVIAN}js/sgMenu.js,
	{$PATH_SEVIAN}js/sgCalendar.js,
	{$PATH_SEVIAN}js/selectText.js,
	{$PATH_SEVIAN}js/sgTab.js,
	{$PATH}js/sgTipsPopup.js,
	{$PATH}js/datePicker.js,

  ");


define ("C_PLANTILLA_DEF","0");//0=Default, 1=Diagrama, 2=Archivo

define ("C_TEMA_DEFAULT","primavera");
define ("C_CLASE_DEFAULT","sigefor");

define("C_METODO","POST");//GET,POST,''
?>