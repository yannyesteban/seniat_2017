<?php
include("../reportes/pdf.php");
require('../fpdf17/fpdf.php');

$codactividad = $vses["codactividad_x"];
$codcertificado = $vform["codcertificado"];
$cedula = $vform["cedula"];

$reg = $vform["reg"];
//$reg="codpersona=3;codpersona=4;codpersona=6";
$filtro = explode(";",$reg);
$cad = "";
foreach($filtro as $k => $v){
	
	$aux = explode("=",$v);
	
	$cad .= (($cad!="")?",":"").$aux[1];
	
}// next

$subtitulo = utf8_decode("por su participación como ponenete en el curso");
$titulo_alter = "";
$nombre_firma1 = "xxxx";
$cargo_firma1 = "xxxx";
$nombre_firma2 = "";
$cargo_firma2 = "";
$total_horas = "";
$lugar_fecha = "";
$materia = "";
$ciudad = "";
$duracion = "";
$fecha_ini = "";
$fecha_fin = "";


$cn = new cls_conexion;

$cn->query = "
	SELECT * FROM configuraciones WHERE status=1 ORDER BY id desc LIMIT 1";
$cn->ejecutar();
if($rs=$cn->consultar()){
	$nombre_firma1 = $rs["nombre_firma"];
	$cargo_firma1 = $rs["cargo_firma"];
}// end if


$cn->query = "
	SELECT c.*,m.materia,ciudad,duracion, a.codactividad,
	
	date_format(fecha_ini, '%d/%m/%Y') as fecha_ini, date_format(fecha_fin, '%d/%m/%Y') as fecha_fin
	FROM certificados as c
	INNER JOIN actividades as a ON a.codactividad=c.codactividad
	INNER JOIN materias as m On m.codmateria = a.codmateria
	INNER JOIN ciudades as ciu ON ciu.codciudad = a.codciudad
	WHERE id='$codcertificado'

";
$cn->ejecutar();
if($rs=$cn->consultar()){
	$codactividad = $rs["codactividad"];
	$ciudad = $rs["ciudad"];
	$duracion = $rs["duracion"];
	$fecha_ini = $rs["fecha_ini"];
	$fecha_fin = $rs["fecha_fin"];
	
	if($rs["subtitulo"]){
		$subtitulo = $rs["subtitulo"];
	}// end if
	
	if($rs["titulo_alter"]){
		$titulo = $rs["titulo_alter"];
	}else{
		$titulo = $rs["materia"];
	}// end if	
	
	if($rs["total_horas"]){
		$duracion = $rs["total_horas"];
	}else{
		$duracion = "Total horas: ".$duracion;
	}// end if	
	
	if($rs["lugar_fecha"]){
		$lugar_fecha = $rs["lugar_fecha"];
	}else{
		$lugar_fecha = $ciudad.", del $fecha_ini al $fecha_fin";
	}// end if	
	
	if($rs["nombre_firma1"]){
		$nombre_firma1 = $rs["nombre_firma1"];
	}// end if	
	if($rs["cargo_firma1"]){
		$cargo_firma1 = $rs["cargo_firma1"];
	}// end if	


	$nombre_firma2 = $rs["nombre_firma2"];
	$cargo_firma2 = $rs["cargo_firma2"];

	 	 	 	 	 	 	 	 	 	 	 	 	
}// end while


$cn->query = "
	SELECT CONCAT(IFNULL(nombre_1,''),  ' ', IFNULL(apellido_1,'') )  as nombre
	FROM personas as p
	INNER JOIN actividad_participantes ap ON ap.codpersona = p.codpersona
	INNER JOIN actividades as a ON a.codactividad = ap.codactividad
	
	WHERE a.codactividad=$codactividad
	AND ";
	
	if($cad!=""){
		$cn->query .= "p.codpersona in ($cad)";
	}else{
		$cn->query .= "('$cedula'='' or '$cedula'!='' AND '$cedula'=p.cedula)";
		
	}// end if
	


$pdf = new FPDF();

$cn->ejecutar();
while($rs=$cn->consultar()){
	$pdf->AddPage('Landscape','Letter');
	$pdf->SetFont('Arial','B',28);
	$pdf->SetY(80);
	$pdf->Cell(0,24,$rs["nombre"],0, 0, 'C');	
	
	$pdf->SetFont('Arial','',16);
	$pdf->SetY(100);
	$pdf->Cell(0,20,$subtitulo,0, 0, 'C');	

	$pdf->SetFont('Arial','',18);
	$pdf->SetY(114);
	$pdf->MultiCell(0,8,$titulo,0,  'C');	

	$pdf->SetFont('Arial','',12);
	$pdf->SetY(136);
	$pdf->Cell(0,20,$duracion,0, 0, 'C');	


	$pdf->SetFont('Arial','',12);
	$pdf->SetY(145);
	$pdf->Cell(0,20,$lugar_fecha,0, 0, 'C');	


	if($nombre_firma2==""){
		$pdf->SetFont('Arial','B',12);
		$pdf->SetY(160);
		$pdf->Cell(0,20,$nombre_firma1,0, 0, 'C');	
	
		$pdf->SetFont('Arial','',12);
		$pdf->SetY(170);
		$pdf->Cell(0,20,$cargo_firma1,0, 0, 'C');
		
	}else{
		$pdf->SetFont('Arial','B',12);
		$pdf->SetY(160);
		$pdf->SetX(0);
		$pdf->Cell(130,20,$nombre_firma1,0, 0, 'C');	

		$pdf->SetFont('Arial','',12);
		$pdf->SetY(170);
		$pdf->SetX(0);
		$pdf->Cell(130,20,$cargo_firma1,0, 0, 'C');

		$pdf->SetFont('Arial','B',12);
		$pdf->SetY(160);
		$pdf->SetX(145);
		$pdf->Cell(130,20,$nombre_firma2,0, 0, 'C');	

		$pdf->SetFont('Arial','',12);
		$pdf->SetY(170);
		$pdf->SetX(145);
		$pdf->Cell(130,20,$cargo_firma2,0, 0, 'C');
	}// end if
	$fecha = date("d/m/Y");
	$pdf->SetFont('Arial','',7);
	$pdf->SetY(183);
	$pdf->SetX(230);
	$pdf->Cell(100,10,utf8_decode("Fecha de Impresión: ").$fecha,0, 0, 'L');		
	$pdf->SetFont('Arial','',7);
	$pdf->SetY(185);
	$pdf->SetX(230);
	$pdf->Cell(100,10,utf8_decode("Número de Curso: ").$codactividad, 0, 0, 'L');		
	
}// end while



$pdf->Output();




?>