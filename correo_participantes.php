<?php

require_once("send_email.php");

function correo_participantes($id, $plantilla= "plantilla_email_1"){
	$mail = new send_email;

	
	
	
	$cn = sgConnection();


	$cn->query = "
		SELECT titulo, diagrama
		FROM cfg_plantillas
		WHERE plantilla = '$plantilla' AND tipo=5 AND status=1
		";
	$cn->ejecutar();
	
	
	$titulo = "";
	$diagrama = "";
	
	if($rs=$cn->consultar()){
		 
		 $titulo = $rs["titulo"];
		 $diagrama = $rs["diagrama"];
		
	}	



	$cn->query = "
		SELECT c.*,date_format(now(), '%d/%m/%Y') as fecha_actual,
		date_format(a.fecha_ini, '%d/%m/%Y') as fecha_ini,
		date_format(a.fecha_fin, '%d/%m/%Y') as fecha_fin,
		actividad,materia, horario, ciudad
		
		FROM correos as c
				
				
		INNER JOIN actividades as a  ON a.codactividad= c.codactividad
		INNER JOIN materias as m ON m.codmateria=a.codmateria
		WHERE id = $id
		";
	$cn->ejecutar();
	$mail = new send_email;
	
	$asunto = "";
	$mensaje = "";
	if($rs=$cn->consultar()){
		 $asunto = $rs["asunto"];
		 $mensaje = $rs["mensaje"];
		 foreach($rs as $k => $v){
			$diagrama = str_replace("{=$k}", $v, $diagrama) ;
			 
		}
		 
		
	}	

	
	$cn->query = "
		SELECT a.codpersona,
		cedula, nombre_1, apellido_1, CASE a.status WHEN '1' THEN 'CHECKED' ELSE '' END valor_1, CASE a.status WHEN '0' THEN 'CHECKED' ELSE '' END valor_2, email
		FROM correo_detalle  a
		INNER JOIN personas as p ON p.codpersona=a.codpersona
		WHERE id_correo = $id AND a.status=1
		ORDER BY cedula LIMIT 1";
	$cn->ejecutar();
	$mail = new send_email;
	
	while($rs=$cn->consultar()){
		$correo = $rs["email"];
		$cedula = $rs["cedula"];
		$nombre = $rs["nombre_1"]." ".$rs["apellido_1"];
		$diagrama1 = str_replace("{=nombre}", $nombre, $diagrama) ;
		$diagrama1 = str_replace("{=cedula}", $cedula, $diagrama1) ;
		$diagrama1 = str_replace("{=correo}", $correo, $diagrama1) ;
		
		echo $diagrama1;
		//hr("$asunto $mensaje $correo $correo","red");
		
		
		 $mail->send($correo, $nombre, $asunto, $diagrama1);
		
		//hr( $mail->ErrorInfo, "blue");
	}	
	
	
	
}


if(isset($_GET["cfg_ins_aux"])){
	$ins = $_GET["cfg_ins_aux"];
	$req = $_GET;
}elseif(isset($_POST["cfg_ins_aux"])){
	$ins = $_POST["cfg_ins_aux"];
	$req = $_POST;
}
$aut = $_SESSION["VSES"][$ins]["SS_AUT"];
$ses = &$_SESSION["VSES"][$ins];

/* comentar la línea de abajo*/
correo_participantes($ses["correo_x"]);
if(isset($req["_rand_aux"])){
	if($ses["_rand_aux"] != $req["_rand_aux"]){
		$ses["_rand_aux"] = $req["_rand_aux"];
		// descomentar la línea de abajo...
		//correo_participantes($ses["correo_x"]);
		
	}else{
		echo "Listo...!";
		
	}	
	
	
	
}else{
	echo "Listo...!";	
	
}









?>