<?php
define ("MAIL_USERNAME","cef");
define ("MAIL_PASSWORD","Cefseniat20151");
define ("MAIL_NAME","Centro de Estudios Fiscales");

include("reportes/config.php");
//require_once('../PHPMailer_v5.1/class.phpmailer.php');
//require_once('../PHPMail/class.phpmailer.php');
require_once('../PHPMailer-5.2.23/PHPMailerAutoload.php');
//include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Ejemplo de estilos con CSS</title>
<style type="text/css">
  body { font-family:Arial, Helvetica, sans-serif; font-size:10px}
  h1 { color: red;  font-family: Arial;   font-size: medium;  }
  p  { color: gray; font-family: Verdana; font-size: small; }
</style>
</head>
 
<body>

<?php
$mail             = new PHPMailer();

$mail->IsSMTP(); // telling the class to use SMTP
$mail->Host       = "172.16.16.91"; // SMTP server
$mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
                                           // 1 = errors and messages
                                           // 2 = messages only
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->SMTPSecure = "";
//$mail->SMTPSecure = "ssl";//"ssl";                 // sets the prefix to the servier
//$mail->Host       = "172.16.16.91";      // sets GMAIL as the SMTP server
//$mail->Port       = 465;                   // set the SMTP port for the GMAIL server
$mail->Port       = 25;                   // set the SMTP port for the GMAIL server
$mail->Username   = MAIL_USERNAME;  // GMAIL username
$mail->Password   = MAIL_PASSWORD;            // GMAIL password
$mail->From = MAIL_USERNAME;
$mail->FromName = MAIL_NAME;
$mail->SetFrom("cef@seniat.gob.ve", MAIL_NAME);
/*$mail­>AddReplyTo("cef@seniat.gob.ve","Centro de Estudios Fiscales");*/

	
	
	
	
$mail->ClearAddresses();
$mail->MsgHTML("TEST");
$mail->AddAddress("yovanyu@gmail.com", "Yovanny Urbina");
$mail->Send();






$codactividad = $vses["codactividad_x"];
$codcertificado = $vform["codcertificado"];

$cedula = "";	
if(isset($vform["cedula"])){
	$cedula = $vform["cedula"];
	
}
	
	

$reg = $vform["reg"];
//$reg="codpersona=3;codpersona=4;codpersona=6";
$filtro = explode(";",$reg);
$cad = "";
foreach($filtro as $k => $v){
	
	$aux = explode("=",$v);
	
	$cad .= (($cad!="")?",":"").$aux[1];
	
}// next

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
$fondo = "";

$cn = sgConnection();;
$cn2 = sgConnection();;

$cn->query = "
	SELECT c.*,m.materia,ciudad,duracion, a.codactividad, mo.tipo, fo.ruta as fondo,
	
	date_format(fecha_ini, '%d/%m/%Y') as fecha_ini, date_format(fecha_fin, '%d/%m/%Y') as fecha_fin
	FROM certificados as c
	INNER JOIN actividades as a ON a.codactividad=c.codactividad
	INNER JOIN materias as m ON m.codmateria = a.codmateria
	INNER JOIN modalidades as mo ON mo.codmodalidad = a.codmodalidad
	LEFT JOIN fondos as fo ON fo.codfondo = c.codfondo
	WHERE id='$codcertificado'

";
$cn->ejecutar();
$subtitulo_p = false;
if($rs=$cn->consultar()){
	$codactividad = $rs["codactividad"];
	$ciudad = $rs["ciudad"];
	$duracion = $rs["duracion"];
	$fecha_ini = $rs["fecha_ini"];
	$fecha_fin = $rs["fecha_fin"];
	$fondo = $rs["fondo"];
	
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
ta.codtipo_actividad, ta.tipo_actividad, ta.genero , personas.email, envio_cert

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
		ORDER BY personas.cedula
		";
	

$cn->ejecutar();
/*$mail->AddAddress('cef@seniat.gob.ve', 'CEF');
$mail->Send();*/

echo "<table align= center border=1 cellpadding=5 cellspacing=0><tr><th colspan=6>Estatus de env&iacute;o de Certificados</th></tr><tr><th colspan=6>Actividad: {$titulo} c&oacute;digo: $codactividad</th></tr><tr ><td>&nbsp;</td><th>Participante</th> <th>Cedula</th><th>Correo</th><th>Estatus </th>";
$numero = 0;




while($rs=$cn->consultar()){
	$mail->ClearAddresses();
	$numero = $numero + 1;
	$cedula = $rs["cedula"];
	$codpersona = $rs["codpersona"];
	$email = $rs["email"];
	$nombre = $rs["nombre"];
	

	echo "<tr><td>".$numero."</td><td>".$nombre."</td> <td>".$cedula."</td><td>$email</td>";
	if($rs["envio_cert"]!=""){
		echo "<td> <font color=red><b> No enviado, Ya hab&iacute;a sido enviado  </b> </font></td></tr>";
		continue;	
	}// end if
	


	if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		echo utf8_decode("<td> <font color=red><b> No enviado, Correo electr&oacute;nico inv&aacute;lido ($email)  </b> </font></td></tr>");
		continue;	
	}// end if
	

	$mail->Subject    = "Certificado. Actividad: ".$titulo.", realizada en ". $ciudad ." el ".$fecha_ini."";

$body = "<p>Se&ntilde;or(a) ".$nombre.", reciba un cordial saludo de parte del equipo que conforma el Centro de Estudios Fiscales (CEF).</p><p> En este sentido, se le informa que el certificado de la actividad de capacitaci&oacute;n denominada: <b>".$titulo."</b>, realizada  en ". $ciudad ." el ".$fecha_ini.", se encuentra disponible para descargarse a trav&eacute;s del siguiente enlace:</p>";
	$body.= "<a href='http://cef.seniat.gob.ve/cefsacac/certificado/index.php?codcertificado=$codcertificado&codpersona=$codpersona'>Descargar Certificado Digital. Actividad: ".$titulo." </a>";
	$body.="<p>Saludos cordiales</p><p> Atentamente, </p>
<br><br><br><center><p>Lic. Astrid Quintana G&oacute;mez <br>Jefa de la Oficina Centro de Estudios Fiscales (CEF) <br>Servicio Nacional Integrado de Administraci&oacute;n Aduanera y Tributaria<br>(SENIAT)</center>";
	//hr($body);	
	$mail->MsgHTML($body);
	//$mail->Body = $body;
	
	$mail->AddAddress($email, $nombre);

	
	//$mail->AddAttachment("images/phpmailer.gif");      // attachment
	//$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment
	//continue;
//	$mail->Send(); 	
	if($mail->Send()) {
		$cn2->query="UPDATE actividad_participantes SET envio_cert=now() WHERE codactividad='$codactividad' and codpersona='$codpersona'";
		$cn2->ejecutar();
		echo "<td><font color=blue >Enviado Correctamente </font></td>";
	}else {

		echo "<td> <font color=red><b> No enviado, Error con el servicio de Correo </b> </font> </td>";

	}// end if
	
	
	
}// end while

echo "</tr></table>";





?>
</body>
</html>