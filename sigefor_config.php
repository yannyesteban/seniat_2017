<?php

include("../sigefor21/clases/cls_conexion.php");
include ("../sigefor21/clases/funciones.php");

$cn = new cls_conexion("localhost","root","", "sigefor");
//$cn2 = new cls_conexion("localhost", "yanny", "123", "seniat2");
$tablas = $cn->extraer_tablas();

$cn->ejecutar("SET FOREIGN_KEY_CHECKS=0");
foreach($tablas as $k => $t){
	$q="SELECT * FROM $t LIMIT 1";
	
	$result = $cn->ejecutar($q);	
	$cn->descrip_campos($result);
	
	$clave = "";
	for($i=0;$i<$cn->nro_campos;$i++){
		if($cn->campo[$i]->clave){
			
			$c = $cn->campo[$i]->campo;
			$clave  .= (($clave!="")?" AND ":"")." a.$c = seniat.$t.$c";
			
		}// end if
		
	}// next

	
	
	$q="	DELETE  seniat.$t
			FROM seniat.$t
			INNER JOIN sigefor.$t as a ON $clave ";
	$cn->ejecutar($q);	


	$q="	INSERT INTO seniat.$t 
				SELECT *
				FROM sigefor.$t
				
			";
			hr($q);
	$cn->ejecutar($q);	

}// next


?>