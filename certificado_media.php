<?php
include("reportes/config.php");
require('../fpdf181/fpdf.php');

include('../phpqrcode/qrlib.php');

ini_set('default_charset', "utf-8");

$codactividad = $vses["codactividad_x"];
$codcertificado = $vform["codcertificado"];
$cedula = $vform["cedula"];

if(isset($vform["reg"])){
	$reg = $vform["reg"];
}else{
	$reg = "";
}

//$reg="codpersona=3;codpersona=4;codpersona=6";

$cad = "";
if($reg){
	$filtro = explode(";",$reg);
	foreach($filtro as $k => $v){
		$aux = explode("=",$v);

		$cad .= (($cad!="")?",":"").$aux[1];

	}// next
}


$subtitulo = utf8_decode("por su participación en el curso");
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
$modalidad = "";

$cn = sgConnection();

$cn->query = "
	SELECT * FROM configuraciones WHERE status=1 ORDER BY id desc LIMIT 1";
$cn->ejecutar();
if($rs=$cn->consultar()){
	$nombre_firma1 = $rs["nombre_firma"];
	$cargo_firma1 = $rs["cargo_firma"];
}// end if


$cn->query = "
	SELECT c.*,m.materia,es.estado as ciudad,duracion, a.codactividad, mo.tipo,
	
	date_format(fecha_ini, '%d/%m/%Y') as fecha_ini, date_format(fecha_fin, '%d/%m/%Y') as fecha_fin
	FROM certificados as c
	INNER JOIN actividades as a ON a.codactividad=c.codactividad
	INNER JOIN estados as es ON es.codestado = a.codestado
	INNER JOIN materias as m ON m.codmateria = a.codmateria
	INNER JOIN modalidades as mo ON mo.codmodalidad = a.codmodalidad
	WHERE id='$codcertificado'

";
$cn->ejecutar();
$subtitulo_p = false;
if($rs=$cn->consultar()){
	foreach($rs as $k => $v){
		$rs[$k] = utf8_decode($v);
		//$v = utf8_decode($v);
	}
	
	$codactividad = $rs["codactividad"];
	$ciudad = $rs["ciudad"];
	$duracion = $rs["duracion"];
	$fecha_ini = $rs["fecha_ini"];
	$fecha_fin = $rs["fecha_fin"];
	
	if($rs["subtitulo"]){
		$subtitulo_p = true;
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
	}else if ($fecha_ini != $fecha_fin){
		$lugar_fecha = $ciudad.", del $fecha_ini al $fecha_fin";
	}else{
		$lugar_fecha = $ciudad.", $fecha_ini";
	}// end if	
	
	if($rs["nombre_firma1"]){
		$nombre_firma1 = ucwords(strtolower($rs["nombre_firma1"]));
	}// end if	
	if($rs["cargo_firma1"]){
		$cargo_firma1 = $rs["cargo_firma1"];
	}// end if	
	$modalidad = $rs["tipo"];

	$nombre_firma2 = ucwords(strtolower($rs["nombre_firma2"]));
	$cargo_firma2 = $rs["cargo_firma2"];

	 	 	 	 	 	 	 	 	 	 	 	 	
}// end while


$cn->query = "

SELECT personas.codpersona, personas.cedula,CONCAT(IFNULL(nombre_1,''),  ' ', IFNULL(apellido_1,'') )  as nombre, 
CASE WHEN d2.codpersona IS NOT NULL THEN d2.cargo ELSE c.cargo END as cargo, 
CASE WHEN d2.codpersona IS NOT NULL THEN d2.dependencia ELSE d.dependencia END as dependencia, telefono, email,

CASE WHEN (CASE tc.aprobacion
WHEN 1 THEN CASE WHEN calificacion>=15 THEN true ELSE false END
ELSE true END) AND (
CASE tc.asistencia
WHEN 1 THEN CASE WHEN avg(aa.asistencia)*100.00>=90 THEN true ELSE false END
ELSE true END) THEN 'Si' ELSE 'No' END as certificado,tc.aprobacion,tc.asistencia,
ta.codtipo_actividad, ta.tipo_actividad, ta.genero 

FROM personas
INNER JOIN actividad_participantes as a ON a.codpersona=personas.codpersona 
INNER JOIN actividades as ac ON ac.codactividad=a.codactividad
INNER JOIN tipos_certificaciones as tc ON tc.codtipo_certificacion=codcondicion_cert
INNER JOIN asistencias as s ON s.codactividad = a.codactividad 
INNER JOIN asistencias_detalle as aa ON aa.codasistencia = s.codasistencia AND aa.codpersona=a.codpersona
INNER JOIN tipos_actividades  as ta ON ta.codtipo_actividad = ac.codtipo_actividad

LEFT JOIN funcionarios as f ON f.codpersona = personas.codpersona
LEFT JOIN cargos as c ON c.codcargo = f.codcargo
LEFT JOIN dependencias as d ON d.coddependencia = f.coddependencia
LEFT JOIN otras_dependencias as d2 ON d2.codpersona = personas.codpersona
WHERE a.codactividad=$codactividad
AND
/*
	SELECT CONCAT(IFNULL(nombre_1,''),  ' ', IFNULL(apellido_1,'') )  as nombre, cedula
	FROM personas as p
	INNER JOIN actividad_participantes ap ON ap.codpersona = p.codpersona
	INNER JOIN actividades as a ON a.codactividad = ap.codactividad
	
	WHERE a.codactividad=$codactividad
	AND */";
	
	if($cad!=""){
		$cn->query .= "personas.codpersona in ($cad)";
	}else{
		$cn->query .= "('$cedula'='' or '$cedula'!='' AND '$cedula'=personas.cedula)";
		
	}// end if
	$cn->query .= "
		
		GROUP BY personas.codpersona
		HAVING certificado='Si'
		ORDER BY f.coddependencia_p, personas.codpersona
		";
	

//hr($cn->query);


$pdf = new FPDF();
$pdf->AddFont('MURPHYS','','MURPHYS.php');
$pdf->AddFont('GOTHICB','','GOTHICB.php');
$pdf->AddFont('GOTHIC','','GOTHIC.php');
$pdf->AddFont('BodoniTwelveITCTTBoldItalic','','BOD12BI_.php');
$pdf->AddFont('FranklinGothicDemi','','FranklinGothicDemi.php');

$cn->ejecutar();
$linea = 0;
while($rs=$cn->consultar()){
	
	foreach($rs as $k => $v){
		$rs[$k] = utf8_decode($v);
		//$v = utf8_decode($v);
	}
	
	
	//$rs = array_map("aux_utf8_encode", $rs);//array_map(utf8_decode())
	
	if($linea % 2 ==0){
		$delta = 0;
	}else{
		$delta = 130;
	}// end if
	
	
	$s1="";$s2="";
	if($rs["aprobacion"]==1){
		$s1 = utf8_decode("aprobación");	
		if($rs["genero"]=="M"){
			$s2 = "del";// al
		}else{
			$s2 = "de la";	//a la
		}
	}else{
		$s1 = "asistencia";	
		if($rs["genero"]=="M"){
			$s2 = "al";// al
		}else{
			$s2 = "a la";	//a la
		}
	}
	
	//echo "..".$rs["tipo_actividad"];exit;
	if($subtitulo_p==false){
		$subtitulo = "por la $s1 $s2 ".$rs["tipo_actividad"];
		
	}	
	if($linea % 2 ==0){
		$pdf->AddPage('Portrait','Letter');//,'Letter'
	}else{
		
	}// end if	
	
	
	$pdf->SetFont('FranklinGothicDemi','',14);
	
	$pdf->SetY(45+$delta);
	$pdf->Cell(0,24,(strtoupper(strtolower($rs["nombre"]))),0, 0, 'C');	
	
	$pdf->SetFont('Arial','',10);
	$pdf->SetY(55+$delta);
	$pdf->Cell(0,20,$subtitulo,0, 0, 'C');	

	$pdf->SetFont('GOTHIC','',14);
	$pdf->SetY(77+$delta);
	$pdf->MultiCell(0,8,$titulo,0,  'C');	

	$pdf->SetFont('Arial','',8);
	$pdf->SetY(82+$delta);
	$pdf->Cell(0,20,$duracion,0, 0, 'C');	


	$pdf->SetFont('Arial','',8);
	$pdf->SetY(86+$delta);
	$pdf->Cell(0,20,$lugar_fecha,0, 0, 'C');	
	if($modalidad==2){
		$image_file = 'imagenes/logo_cef_distancia.gif';
		$pdf->Image($image_file, 10, 155, 60, '', 'GIF', '', 'T', false, 300, '', false, false, 0, false, false, false);
	}// end if

	if($nombre_firma2==""){
		$pdf->SetFont('Arial','B',8);
		$pdf->SetY(104+$delta);
		$pdf->Cell(0,20,$nombre_firma1,0, 0, 'C');	
	
		$pdf->SetFont('Arial','',8);
		$pdf->SetY(108+$delta);
		$pdf->Cell(0,20,$cargo_firma1,0, 0, 'C');
		
	}else{
		$pdf->SetFont('Arial','B',8);
		$pdf->SetY(100+$delta);
		$pdf->SetX(0);
		$pdf->Cell(100,20,$nombre_firma1,0, 0, 'C');	

		$pdf->SetFont('Arial','',8);
		$pdf->SetY(104+$delta);
		$pdf->SetX(0);
		$pdf->Cell(100,20,$cargo_firma1,0, 0, 'C');

		$pdf->SetFont('Arial','B',8);
		$pdf->SetY(100+$delta);
		$pdf->SetX(105);
		$pdf->Cell(100,20,$nombre_firma2,0, 0, 'C');	

		$pdf->SetFont('Arial','',8);
		$pdf->SetY(104+$delta);
		$pdf->SetX(105);
		$pdf->Cell(100,20,$cargo_firma2,0, 0, 'C');
	}// end if
	
	$pdf->SetFont('Arial','',6);
	$pdf->SetY(116+$delta);
	$pdf->SetX(10);
	$pdf->Cell(100,10,utf8_decode("CI: ").$rs["cedula"],0, 0, 'L');		
	
	$pdf->SetFont('Arial','',6);
	$pdf->SetY(118+$delta);
	$pdf->SetX(10);
	$pdf->Cell(100,10,utf8_decode("Dep: ").$rs["dependencia"],0, 0, 'L');		

	
	$fecha = date("d/m/Y");
	$pdf->SetFont('Arial','',6);
	$pdf->SetY(116+$delta);
	$pdf->SetX(160);
	$pdf->Cell(100,10,utf8_decode("Número de Curso: ").$codactividad,0, 0, 'L');		
	
	$pdf->SetFont('Arial','',6);
	$pdf->SetY(118+$delta);
	$pdf->SetX(160);
	$pdf->Cell(100,10,utf8_decode("Fecha de Impresión: ").$fecha, 0, 0, 'L');		
	$linea++;
}// end while


$pdf->Output("I","certificado.pdf",true);




?>