<?php 
function pg_migrar($tabla, $archivo, $eliminar, $sep=";", $decode){
	$nerrores=0;
	$fila = 0;
	$cn = new cls_conexion;
	$query="";

	
	if ($eliminar=="1"){
		$query = "TRUNCATE $tabla";
		$cn->ejecutar($query);
		
	}else if($eliminar=="2"){
		$query = "DELETE FROM $tabla";
		$cn->ejecutar($query);
		
	}// end if
	
	$query = "SELECT * FROM $tabla LIMIT 0";
	$result=$cn->ejecutar($query);
	$cn->descrip_campos($result);
	$cfg = $cn->campo[$tabla];
	//print_r($cfg["padre"] );
	$t=array();
	if (($gestor = fopen($archivo, "r")) !== FALSE) {
		
		while (($datos = fgetcsv($gestor, 0, $sep, '"')) !== FALSE) {
			
			$aux=array();
			foreach($datos as $k => $v){
				//$t[$k]=trim($t[$k]);

				$aux[] = "'".pg_escape_string($v)."'";
				
			}// next
			$query = "INSERT INTO contribuyentes2 (id,cedula,rif,nombres,apellidos,email,profesion,estado,municipio,parroquia,vencimiento, upd) VALUES ". "(".implode(",",$aux).")";
			$cn->ejecutar($query);
			/*
			if ($cn->errno>0){
				$nerrores++;
				//hr($query);
				//hr($cn->errmsg_o,"red");
				//fclose($gestor);
				//exit;	
				$cn->errno=0;
				
			}// end if
			*/
		}// end while
		fclose($gestor);
	}
	$fila--;
	hr("registros= $fila, insertados correctamente, errores: $nerrores");
	exit;
}// end if
?>