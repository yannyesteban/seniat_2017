<?php
ini_set('max_execution_time', 600); 
function migrar($archivo_x, $tabla_x, $codactividad, $adjunto){
	if($archivo_x==""){
		return "ERROR";
	}// end if

	//$val = validar($archivo_x, $tabla_x, $codlistado_x);
	if($val!=""){
		//return utf8_decode($val);
	}

	//return $archivo_x;
	$conn_x = sgConnection();
	
	$fd = fopen ($archivo_x, "r");
	$eliminar = 0;//$eliminar_x;
	$tabla = $tabla_x;
	$titulo = "";
	$j=0;
	$error_gen=false;
	while (!feof($fd)) {
		$buffer = fgets($fd, 4096);
		
		$linea = explode(";",$buffer);
		$n = count($linea);
		$j++;
		if(/*$n>1 and */strlen($buffer)>1){
			if ($j==1){
				if ($eliminar=="1"){
					$query = "delete from $tabla";
					echo "$query<hr>";
					$conn_x->ejecutar($query);
				}// end if
				for($i=0;$i<$n;$i++){
					if ($i<$n-1)
						$titulo .= trim($linea[$i]).",";
					else
						$titulo .= trim($linea[$i]);
					$query1 = "INSERT INTO $tabla ($titulo, codactividad, adjunto) VALUES ";
				}// next
			}
			else{
				
				$query2="";
				for($i=0;$i<$n;$i++){
					if(trim($linea[$i])==""){
						$valor_y = "null";
					}else{
						$valor_y = "'".addslashes(trim($linea[$i]))."'";
					}
					
				
					if ($i<$n-1)
						$query2 .= $valor_y.",";
					else
						$query2 .= $valor_y;
					$query = "($query2,$codactividad, $adjunto)";
				}// next
				$query = $query1.$query;
				//echo $q."<br>$buffer<hr>";
				//echo "<br>".$query;
				$conn_x->ejecutar($query);
			
				if ($conn_x->errno>0){
					$error_gen=true;
					if($conn_x->errno=="1062"){
						$conn_x->errno = 0;
						$error_gen=true;
						//echo "<br>".$query;
						//echo "<hr><span style=\"background:#cccccc;\">Error: Los datos que intenta introducir ya existen</span>"; 
						}
					else{
						//echo "<hr>Error: No se pudo generar la Operación: $conn_x->errmsg"; 
					}// end if
					$conn_x->errno=0;
				}// end if
				unset($linea);
			}// end if
		}else{
			//echo 4444;
		}// end if
	}// end while
	fclose ($fd);
	if ($error_gen){
		return "Error";
	}else{
		
		$q="INSERT INTO actividad_participantes (codactividad, codpersona)
			SELECT DISTINCT a.codactividad, p.codpersona
			FROM adjuntos_detalle as a
			INNER JOIN personas as p ON p.cedula=a.cedula
			LEFT JOIN actividad_participantes as x ON p.codpersona = x.codpersona AND x.codactividad = a.codactividad
			WHERE adjunto = '$adjunto' AND x.codpersona IS NULL";
		$conn_x->ejecutar($q);
		//hr($q);
		return  "OK";
	}// end if

}// end function


function validar($archivo_x, $tabla_x, $codlistado_x){


		if(isset($_GET["cfg_ins_aux"]) or isset($_POST["cfg_ins_aux"])){
			$ins = $_POST["cfg_ins_aux"];
			$aut = $_SESSION["VSES"][$ins]["SS_AUT"];
			$ses = &$_SESSION["VSES"][$ins];
		
		}else{	
			$aut = false;
		
		}
		if(!$aut){
			return "No tiene autorizacion";
		}// end if


		$cod_institucion_x = $ses["cod_institucion_x"];
		$institucion_x = $ses["institucion_formadora_x"];
		
		$cn = new cls_conexion;
		$query="SELECT codprofesion
		FROM profesiones_instituciones
		WHERE cod_institucion=$cod_institucion_x";
	

		
		$result = $cn->ejecutar($query);
		$codprof = array();
		while($rs=$cn->consultar($result)){
			$codprof[$rs["codprofesion"]] = 1;	
		
		}
//print_r($codprof);

	
	$fd = fopen ($archivo_x, "r");
	$titulo = "";
	$j=0;
	$error_gen=false;
	$mensajes = "";
	while (!feof($fd)) {
		$buffer = fgets($fd, 4096);
		$linea = explode(";",$buffer);
		$n = count($linea);
		$j++;
		
		if($n>1 and strlen($buffer)>1){
			if ($j==1){
				for($i=0;$i<$n;$i++){
					$titulos[$i]=trim($linea[$i]);
				}// next
			}
			else{
				$query2="";
				for($i=0;$i<$n;$i++){
					
					$valor = trim($linea[$i]);

					switch($titulos[$i]){
					case "venext":
						if($valor==""){
							$mensaje .= "El campo nacionalidad(venext) es obligatorio, error en la línea $j del archivo. ";
						}
						if($valor!="V" and $valor!="E"){
							$mensaje .= "El valor del campo nacionalidad(venext) debe ser únicamente V o E, correspondiente a V = Venezolano o E=Extranjero, error en la línea $j del archivo. ";
						}
					
						break;
					case "cedula":
						if($valor==""){
							$mensaje .= "El campo cédula(cedula) es obligatorio, error en la línea $j del archivo.  ";
						}
					
						break;
					case "primer_nombre":
						if($valor==""){
							$mensaje .= "El campo Primer Nombre(\"primer_nombre\") es obligatorio, error en la línea $j del archivo. ";
						}
						break;
					case "segundo_nombre":
						break;
					case "primer_apellido":
						if($valor==""){
							$mensaje .= "El campo Primer Apellido(\"primer_apellido\") es obligatorio, error en la línea $j del archivo. ";
						}
						break;
					case "segundo_apellido":
						break;
					case "fecha_nacimiento":
						if($valor==""){
							$mensaje .= "El campo Fecha de Nacimiento(\"fecha_nacimiento\") es obligatorio, error en la línea $j del archivo. ";
						}
						if(!preg_match("/^\d{4}\-\d{2}\-\d{2}$/", trim($valor))){
							$mensaje .= "El campo Fecha de Nacimiento(\"fecha_nacimiento\") no tiene un formato valido, ejm. formato válido 1980-10-31, error en la línea $j del archivo. ";
						}// end if
										
					
						break;
					case "codprofesion":
						if($valor==""){
							$mensaje .= "El campo Código de Profesión(\"codprofesion\") es obligatorio, error en la línea $j del archivo. ";
						}
						if($codprof[$valor]!="1"){
							$mensaje .= "El campo Código de Profesión(\"codprofesion\") no está autorizado para esta Institución, error en la línea $j del archivo. ";
						}
					
						break;
					case "fecha_grado":
				
						if($valor==""){
							$mensaje .= "El campo Fecha de Grado(\"fecha_grado\") es obligatorio, error en la línea $j del archivo. ";
						}
						if(!preg_match("/^\d{4}\-\d{2}\-\d{2}$/", trim($valor))){
							$mensaje .= "El campo Fecha de Grado(\"fecha_grado\") no tiene un formato válido, ejm. formato válido 1980-10-31, error en la línea $j del archivo. ";
						}// end if
						break;
					case "condicion":
						if($valor==""){
							$mensaje .= "El campo Codición (condicion) es obligatorio, error en la línea $j del archivo. ";
						}
						if($valor!="1" and $valor!="2"){
							$mensaje .= "El valor del campo Codición (condicion) debe ser únicamente 1 o 2, correspondiente a 1=Graduado en Venezuela o 2=Graduado en el Extranjero, error en la línea $j del archivo. ";
						}
						break;
					}// end switch
				}// next
				unset($linea);
			}// end if
		}// end if
	}// end while
	fclose ($fd);
	return $mensaje;
	if ($error_gen){
		return $mensaje;
	}else{
		return  "<hr>el POCESO DE IMPORTACIÓN DE ARCHIVO DE (DE LA) $institucion_x FUE REALIZADO CORRECTAMENTE";
	}// end if

}// end function

function importar_pers($archivo_x, $codactividad, $codfrente, $codsubfrente, $ins){

	$aut = $_SESSION["VSES"][$ins]["SS_AUT"];
	$ses = &$_SESSION["VSES"][$ins];
	
	
	$vses = &$ses;
	
	$vform = &$_GET;
	$fd = fopen ($archivo_x, "r");
	$cn = sgConnection();
	$j=0;
	$band=false;
	$del=";";
	$msg_error="";
	$cad_error = "";
	$error_grave = 0;
	$n_error_grave = 0;	
	$cn->begin_trans();
	
	
	
	while (!feof($fd)) {
		$buffer = fgets($fd, 4096);
		if($j==0){
			$bdel = stripos($buffer, ";");
			if($bdel>0){
				$del= ";";
			
			}else{
				$del= ",";
			}
			
			
		}// end if
		
		$d = explode($del,trim($buffer));
		$n = count($linea);
		$j++;
		//print_r($d);
		//hr($j);exit;
		
		if($j>1){
			//$codpersona=$d[0];
			//$nacionalidad=$d[0];
			$cedula=trim($d[0]);
			//$pasaporte=$d[0];
			$nombre_1=trim(strtoupper($d[1]));
			$apellido_1=trim(strtoupper($d[2]));
			//$fecha_nac=$d[0];
			//$sexo=$d[0];
			$direccion=$d[3];
			$telefono=trim($d[4]);
			$celular=trim($d[5]);
			$email=trim($d[6]);
			$dependencia=trim(strtoupper($d[7]));
			$cargo=trim(strtoupper($d[8]));			
			
			if($cedula=="" and $nombre_1=="" and $apellido_1==""){
				
				continue;	
				
			}
			
			if($cedula==""){
				if($n_error_grave<=100){
					$cad_error .= "<br>(Línea $j), no se especificó la Cédula";
					$error_grave = 1;
				}
				$n_error_grave++;
			}// end if


			if($nombre_1==""){
				if($n_error_grave<=100){
					$cad_error .= "<br>(Línea $j) Registro con Cédula = $cedula, no se especificó el Nombre";
					$error_grave = 1;
				}// end if
				$n_error_grave++;
			}// end if
			if($apellido_1==""){
				if($n_error_grave<=100){
					$cad_error .= "<br>(Línea $j) Registro con Cédula = $cedula, no se especificó el Apellido";
					$error_grave = 1;
				}// end if
				$n_error_grave++;
			}// end if
			
			if($email==""){
				
				if($n_error_grave<=100){
					
					$cad_error .= "<br>(Línea $j) Registro con Cédula = $cedula, no se especificó el Correo Electrónico";
					$error_grave = 1;
						
					
				}
				$n_error_grave++;
				
			}elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				if($n_error_grave<=100){
					$cad_error .= "<br>(Línea $j) Registro con Cédula = $cedula, el Correo Electrónico ($email) es INVÁLIDO";
					$error_grave = 1;	
					

					
				}
				$n_error_grave++;
			}// end if
			if($dependencia==""){
				if($n_error_grave<=100){
					$cad_error .= "<br>(Línea $j) Registro con Cédula = $cedula, no se especificó la Dependencia";
					$error_grave = 1;
				}// end if
				$n_error_grave++;
			}// end if
			
			
			//$tipo=$d[0];

			$cn->meta_error=0;
			$q = "INSERT INTO personas 
					( nacionalidad, cedula, nombre_1, apellido_1, direccion, telefono, celular, email, tipo) 
					VALUES ('V', '$cedula', UCASE('$nombre_1'), UCASE('$apellido_1'), '$direccion', '$telefono', '$celular', '$email', 2);";
			$cn->ejecutar($q);
			
			
			if($cn->meta_error==4){
				//hr(utf8_decode("La persona de cédula: $cedula, ya se encuentra registrada"));
				$msg_error .= "\\n".utf8_decode("La persona de cédula\: $cedula, ya se encuentra registrada");
				$q="SELECT codpersona FROM personas WHERE cedula='$cedula'";
				$result = $cn->ejecutar($q);
				if($rs=$cn->consultar($result)){
					$codpersona = $rs["codpersona"];
				}// end if
			}else{
				$codpersona = $cn->insert_id;
				$q = "INSERT INTO otras_dependencias (codpersona, dependencia, cargo) VALUES ('$codpersona', UCASE('$dependencia'), UCASE('$cargo'));";				
				$cn->ejecutar($q);
			}// end if
			
			
			
			
			$q="INSERT INTO actividad_participantes (codactividad, codpersona, codfrente, codsubfrente) 
			VALUES ('$codactividad', '$codpersona', '$codfrente', '$codsubfrente')";
			$cn->ejecutar($q);
		}// end if
		if ($cn->errno > 0){
			$msg= "Error, algunos registros ya estaban en la base de datos";
		}else{
			$msg=  "Importación Correcta";
		}// end if

	}// end while
	fclose ($fd);
	$vses["imp_error_grave"] = $error_grave;
	if($error_grave==1){
		$cn->rollback();
		return utf8_decode($cad_error);
	}else{
		$cn->commit();
	}// end if	
	
	return utf8_decode($msg);
}// end function




function importar_func($archivo_x, $codmigracion, $operacion="e"){

	$aut = $_SESSION["VSES"][$ins]["SS_AUT"];
	$ses = &$_SESSION["VSES"][$ins];
	
	
	$vses = &$ses;
	
	$vform = &$_GET;
	$fd = fopen ($archivo_x, "r");
	$cn = sgConnection();
	$j=0;
	$band=false;
	$del=";";
	$msg_error="";
	$cad_error = "";
	$error_grave = 0;
	$n_error_grave = 0;	
	$cn->begin_trans();
	while (!feof($fd)) {
		$buffer = fgets($fd, 4096);
		if($j==0){
			$bdel = stripos($buffer, ";");
			if($bdel>0){
				$del= ";";
			
			}else{
				$del= ",";
			}
			
			
		}// end if
		
		$d = explode($del,trim($buffer));
		$n = count($linea);
		$j++;
		$debug="";
		if($j>1){
			//$codpersona=$d[0];
			//$nacionalidad=$d[0];
			$cedula=trim($d[0]);
			//$pasaporte=$d[0];
			$nombre=trim(strtoupper($d[1]));
			$apellido=trim(strtoupper($d[2]));
			
			$cargo=trim(strtoupper($d[3]));
			$cod_dependencia=trim(strtoupper($d[4]));
			$dependencia=trim(strtoupper($d[5]));
			$fecha_nac=trim($d[6]);
			$sexo=trim(strtoupper($d[7]));
			$correo=trim(strtolower($d[8]));
			$telefono=trim($d[9]);
			$fecha_ingreso=trim($d[10]);
			//$fecha_nac=$d[0];
			//$sexo=$d[0];
				
			
			if($cedula=="" and $nombre=="" and $apellido==""){
				
				continue;	
				
			}
			/*
			if($cedula==""){
				if($n_error_grave<=100){
					$cad_error .= "<br>(Línea $j), no se especificó la Cédula";
					$error_grave = 1;
				}
				$n_error_grave++;
			}// end if


			if($nombre==""){
				if($n_error_grave<=100){
					$cad_error .= "<br>(Línea $j) Registro con Cédula = $cedula, no se especificó el Nombre";
					$error_grave = 1;
				}// end if
				$n_error_grave++;
			}// end if
			if($apellido==""){
				if($n_error_grave<=100){
					$cad_error .= "<br>(Línea $j) Registro con Cédula = $cedula, no se especificó el Apellido";
					$error_grave = 1;
				}// end if
				$n_error_grave++;
			}// end if
			
			if($correo==""){
				
				if($n_error_grave<=100){
					
					$cad_error .= "<br>(Línea $j) Registro con Cédula = $cedula, no se especificó el Correo Electrónico";
					$error_grave = 1;
						
					
				}
				$n_error_grave++;
				
			}elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				if($n_error_grave<=100){
					$cad_error .= "<br>(Línea $j) Registro con Cédula = $cedula, el Correo Electrónico ($email) es INVÁLIDO";
					$error_grave = 1;	
					

					
				}
				$n_error_grave++;
			}// end if
			if($dependencia==""){
				if($n_error_grave<=100){
					$cad_error .= "<br>(Línea $j) Registro con Cédula = $cedula, no se especificó la Dependencia";
					$error_grave = 1;
				}// end if
				$n_error_grave++;
			}// end if
			
			
			//$tipo=$d[0];
*/
			$cn->meta_error=0;
			$q = "INSERT INTO personas_mg (cedula, nombre, apellido, cargo, cod_dependencia, dependencia, fecha_nac, sexo, correo, telefono, fecha_ingreso, codmigracion) VALUES ('$cedula', '$nombre', '$apellido', '$cargo', '$cod_dependencia', '$dependencia', '$fecha_nac', '$sexo', '$correo', '$telefono', '$fecha_ingreso', $codmigracion);";
			
			
			$cn->ejecutar($q);
			
			
		}// end if
		
		
		if ($cn->errno > 0){
			$msg= "Error, algunos registros ya estaban en la base de datos";
		}else{
			$msg=  "Importación Correcta";
		}// end if

	}// end while
	


	$resultado = "";
	
	
	
	
	if($operacion=="e" or $operacion=="c" or $operacion=="m"){
		$q = "
			SELECT pm.cargo
			FROM personas_mg as pm
			LEFT JOIN cargos as c ON c.cargo = pm.cargo
			WHERE  c.cargo IS NULL AND codmigracion=$codmigracion
			GROUP BY pm.cargo";
		$result = $cn->ejecutar($q);
		$t = new cls_table(1);
		$f=0;
		while($rs=$cn->consultar($result)){
			$cargo = $rs["cargo"];
			$t->create_row();
			$t->cell[$f][0]->text = $cargo;
			$f++;
		}// end if				
		if($f>0){
			$resultado .="<div class=\"migracion_div\">Lista de Cargos que serán Agregados para poder Importar<div>";
			$resultado .=$t->control();
		}// end if	
	}// end if	

	if($operacion=="c" or $operacion=="m"){
		$q = "
			INSERT INTO cargos (cargo)
			SELECT pm.cargo
			FROM personas_mg as pm
			LEFT JOIN cargos as c ON c.cargo = pm.cargo
			WHERE  c.cargo IS NULL
			GROUP BY pm.cargo";		
		$result = $cn->ejecutar($q);
		$resultado .="<br><div class=\"migracion_div\">Migración de Cargos fue Realizada<div>";
		
	}// end if	

		
	if($operacion=="e" or $operacion=="d" or $operacion=="m"){
		$q="
			SELECT
			substr(cod_dependencia,1,5) as coddependencia, CONCAT('*REV* ', pm.dependencia) as dependencia, CONCAT('*REV* ', pm.dependencia) as dependencia1, substr(cod_dependencia,1,3) as padre, 2 as nivel
			FROM personas_mg as pm
			LEFT JOIN dependencias as d2 ON d2.coddependencia = substr(cod_dependencia,1,5)
			WHERE  d2.dependencia IS NULL AND codmigracion=$codmigracion
			GROUP BY 1";
		$result = $cn->ejecutar($q);
		$t = new cls_table(5);
		$f=0;
		$t->create_row();
		$t->cell[0][0]->text = "coddependencia";
		$t->cell[0][1]->text = "dependencia";
		$t->cell[0][2]->text = "dependencia1";
		$t->cell[0][3]->text = "padre";
		$t->cell[0][4]->text = "nivel";		
		
		
		while($rs=$cn->consultar($result)){
			$coddependencia = $rs["coddependencia"];
			$dependencia = $rs["dependencia"];
			$dependencia1 = $rs["dependencia1"];
			$padre = $rs["padre"];
			$nivel = $rs["nivel"];
			$t->create_row();
			$t->cell[$f+1][0]->text = $coddependencia;
			$t->cell[$f+1][1]->text = $dependencia;
			$t->cell[$f+1][2]->text = $dependencia1;
			$t->cell[$f+1][3]->text = $padre;
			$t->cell[$f+1][4]->text = $nivel;
			$f++;
		}// end if				
		if($f>0){
			$resultado .="<br><div class=\"migracion_div\">Lista de Dependencias que serán Agregados para poder Importar<div>";
			$resultado .=$t->control();
		}// end if	
		

		$q="
			SELECT cod_dependencia as coddependencia,pm.dependencia,pm.dependencia as dependencia1, substr(cod_dependencia,1,5) as padre, 3 as nivel
			
			FROM personas_mg as pm
			LEFT JOIN dependencias as d ON d.coddependencia = pm.cod_dependencia
			LEFT JOIN dependencias as d2 ON d2.coddependencia = substr(cod_dependencia,1,5)
			
			WHERE  d.dependencia IS NULL AND codmigracion=$codmigracion
			GROUP BY pm.dependencia";
		$result = $cn->ejecutar($q);
		$t = new cls_table(5);
		$f=0;
		$t->create_row();
		$t->cell[0][0]->text = "coddependencia";
		$t->cell[0][1]->text = "dependencia";
		$t->cell[0][2]->text = "dependencia1";
		$t->cell[0][3]->text = "padre";
		$t->cell[0][4]->text = "nivel";		
		
		
		while($rs=$cn->consultar($result)){
			$coddependencia = $rs["coddependencia"];
			$dependencia = $rs["dependencia"];
			$dependencia1 = $rs["dependencia1"];
			$padre = $rs["padre"];
			$nivel = $rs["nivel"];
			$t->create_row();
			$t->cell[$f+1][0]->text = $coddependencia;
			$t->cell[$f+1][1]->text = $dependencia;
			$t->cell[$f+1][2]->text = $dependencia1;
			$t->cell[$f+1][3]->text = $padre;
			$t->cell[$f+1][4]->text = $nivel;
			$f++;
		}// end if				
		if($f>0){
			$resultado .="<br><div class=\"migracion_div\">Lista de SUB-Dependencias que serán Agregados para poder Importar<div>";
			$resultado .=$t->control();
		}// end if	

		
		
		//echo utf8_decode($resultado);
	}// end if
	

	if($operacion=="d" or $operacion=="m"){
		$q = "
			INSERT INTO dependencias (coddependencia, dependencia, dependencia1, padre, nivel)
			
			SELECT
			substr(cod_dependencia,1,5) as coddependencia, CONCAT('*REV* ', pm.dependencia) as dependencia, CONCAT('*REV* ', pm.dependencia) as dependencia1, substr(cod_dependencia,1,3) as padre, 2 as nivel
			FROM personas_mg as pm
			LEFT JOIN dependencias as d2 ON d2.coddependencia = substr(cod_dependencia,1,5)
			WHERE  d2.dependencia IS NULL AND codmigracion=$codmigracion
			GROUP BY 1";		
		$result = $cn->ejecutar($q);
		$resultado .="<br><div class=\"migracion_div\">Migracion de las Dependencias fue Realizada<div>";


		$q = "
			INSERT INTO dependencias (coddependencia, dependencia, dependencia1, padre, nivel)
			
			SELECT cod_dependencia as coddependencia,pm.dependencia,pm.dependencia as dependencia1, substr(cod_dependencia,1,5) as padre, 3 as nivel
			FROM personas_mg as pm
			LEFT JOIN dependencias as d ON d.coddependencia = pm.cod_dependencia
			LEFT JOIN dependencias as d2 ON d2.coddependencia = substr(cod_dependencia,1,5)
			WHERE  d.dependencia IS NULL AND codmigracion=$codmigracion
			GROUP BY pm.dependencia";		
		$result = $cn->ejecutar($q);
		$resultado .="<br><div class=\"migracion_div\">Migracion de las SUB-Dependencias fue Realizada<div>";

		
	}// end if	
	if($operacion=="m"){
		
		
		
		$q = "
			INSERT INTO personas (nacionalidad, cedula, nombre_1, apellido_1, fecha_nac, sexo, telefono, email, upd)
			SELECT 'V', pm.cedula, nombre, apellido, pm.fecha_nac, pm.sexo, pm.telefono, pm.correo, 100000+$codmigracion
			FROM personas_mg as pm
			LEFT JOIN personas as p ON p.cedula=pm.cedula
			WHERE p.cedula IS NULL AND codmigracion=$codmigracion";		
		$result = $cn->ejecutar($q);
		$resultado .="<br><div class=\"migracion_div\">Migracion a la Tabla Personas fue Realizada<div>";

		$q = "
			INSERT INTO funcionarios (codpersona, coddependencia_p, coddependencia, codcargo, fecha_ingreso)

			SELECT p.codpersona, substr(cod_dependencia,1,5) as coddependencia_p, cod_dependencia as coddependencia, c.codcargo, pm.fecha_ingreso
			FROM personas as p
			INNER JOIN personas_mg as pm ON pm.cedula=p.cedula AND codmigracion=$codmigracion
			INNER JOIN cargos as c ON c.cargo = pm.cargo
			LEFT JOIN funcionarios as f ON f.codpersona=p.codpersona
			WHERE codmigracion=$codmigracion AND f.codpersona IS NULL";		
		$result = $cn->ejecutar($q);
		$resultado .="<br><div class=\"migracion_div\">Migracion a la Tabla Funcionario fue Realizada<div>";
		
		
		$q="
		
			UPDATE personas_mg
			
			INNER JOIN personas as p ON p.cedula=personas_mg.cedula
			INNER JOIN cargos as c ON c.cargo = personas_mg.cargo
			
			SET personas_mg.codcargo =c.codcargo, personas_mg.codpersona =p.codpersona
			WHERE codmigracion=$codmigracion
			
			";
		$result = $cn->ejecutar($q);
		$resultado .="<br><div class=\"migracion_div\">Calculando los Cambios de Cargos y Dependencias en los Funcionarios<div>";

		$q="
			UPDATE personas as p
			INNER JOIN funcionarios as f ON p.codpersona = f.codpersona
			SET tipo=1
			
			WHERE p.tipo=2;";
		$result = $cn->ejecutar($q);
		$resultado .="<br><div class=\"migracion_div\">Se cambio el tipo en la Tabla Persona de 2 (externo) a 1 (funcionario)<div>";


		$q="
		
			update funcionarios as f
			INNER JOIN personas_mg as pm ON pm.codpersona=f.codpersona
			SET f.codcargo = pm.codcargo, f.coddependencia = cod_dependencia, f.coddependencia_p = substr(cod_dependencia,1,5)
			WHERE pm.codcargo IS NOT NULL AND codmigracion=$codmigracion
			";
		$result = $cn->ejecutar($q);
		$resultado .="<br><div class=\"migracion_div\">Los Cargos y Dependencias en los Funcionarios fueron Actualizados<div>";



	}// end if	

	
	
	$q="UPDATE migraciones SET resultado = '$resultado' WHERE codmigracion=$codmigracion";		
	$cn->ejecutar($q);


	
	
	
	fclose ($fd);
	
	
	
	
	
	$vses["imp_error_grave"] = $error_grave;
	if($error_grave==1){
		$cn->rollback();
		return utf8_decode($cad_error);
	}else{
		$cn->commit();
	}// end if	
	
	return utf8_decode($msg);
}// end function
?>