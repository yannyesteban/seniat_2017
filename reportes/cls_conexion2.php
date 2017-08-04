<?php
require_once("clases/sg_constantes.php");
require_once("constantes.php");
require_once("clases/funciones.php");
class descrip_campo{
	var $tabla = "";
	var $campo = "";
	var $aux = "";
	var $tipo = "";
	var $longitud = "";
	var $primary = false;
	var $index = false;
	var $serial = false;
	var $default = "";
	var $null = true;
	var $unique = false;
	var $meta = "";
	var $num = false;
}// end class
class cls_conexion{
	var $servidor = C_SERVIDOR;
    var $bdatos = C_BDATOS;
    var $usuario = C_USUARIO;
	var $password = C_PASSWORD;
	var $puerto = "3306";
	//======================
	var $conexion;
    var $estado = false;
	//======================
	var $query;
	var $result;
	//======================
	var $paginacion = C_NO;
	var $pagina = 1;
    var $reg_ini = C_REG_INI;
    var $reg_pag = C_REG_PAG;
	//======================
	var $filas_afectadas = 0;
	var $insert_id;
    var $reg_total = 0;
	var $nro_paginas = 0;
    var $nro_filas;
    var $nro_campos;
    //======================
	var $con_transaccion = C_NO;
	var $error=false;
    var $errno=0;
	var $errno_m;
    var $errmsg="";
	var $errabs = 0;
	var $mostrar_error = true;
    //======================
    var $con_descrip=false;
	var $taux = C_TABLA_AUX;
	var $ckeys = "";
	var $claves;
	var $es_clave;
	//==========================================================
	// Funcion constructora de la clase
	//===========================================================
	function cls_conexion($servidor="",$usuario="",$password="",$bdatos="",$puerto=""){
    	if ($servidor!=""){
			$this->servidor = $servidor;
        }// end if
    	if ($usuario!=""){
			$this->usuario = $usuario;
        }// end if
    	if ($password!=""){
			$this->password = $password;
        }// end if
    	if ($servidor!=""){
			$this->bdatos = $bdatos;
        }// end if
    	if ($puerto!=""){
			$this->puerto = $puerto;
        }// end if
	    $this->conexion = mysql_connect($this->servidor.":".$this->puerto,$this->usuario,$this->password) or die (C_ERROR_CNN_FALLIDA);
	    if ($this->bdatos!=""){
	        mysql_select_db($this->bdatos) or die (C_ERROR_BD_FALLIDA);
			$this->estado = true;
        }// end if
    }// end if
	//===========================================================
    function ejecutar($query_x=""){
		if (!$this->conexion){
			$this->cls_conexion();
		}// end if
		if ($this->con_transaccion==C_SI and $this->errabs>0){
			return false;
		}// end if
		if ($query_x!=""){
			$this->query = $query_x;
        }// end if
		$this->query = $this->hacer_query($this->query);
        $this->result = mysql_query($this->query);
		if (mysql_errno()>0){
			$this->errabs++;
	        $this->es_error(true);
	        return false;
        }// end if
        if ($this->es_select($this->result)){
        	$this->nro_filas = mysql_num_rows($this->result);
            $this->nro_campos = mysql_num_fields($this->result);
            $this->reg_total = $this->nro_filas;
	        if ($this->paginacion == C_SI 
						and is_numeric($this->pagina)
						and is_numeric($this->reg_pag) 
						and $this->reg_total > 0 
						and $this->reg_pag > 0
						and preg_match("/^([^\w]+|\s*)\bselect\b/i", $this->query)
						and !preg_match("/ limit\s+[0-9]/i", $this->query)){
				$this->nro_paginas = ceil($this->reg_total/$this->reg_pag);
				if($this->pagina > $this->nro_paginas){
					$this->pagina = $this->nro_paginas;
				}if($this->pagina<=0){
					$this->pagina = 1;
				}// end if
				$this->reg_ini = $this->reg_pag * ($this->pagina-1);
                $this->result = mysql_query($this->query." LIMIT $this->reg_ini,$this->reg_pag");
	        }// end if
        }else{
        	$this->filas_afectadas = mysql_affected_rows ();
            $this->insert_id = mysql_insert_id();
        }// end if
        return $this->result;
    }// end function
	//===========================================================
    function consultar($result_x=""){
		if(!$this->es_consulta){
			return false;
		}// end if
    	if ($result_x!=""){
			$this->result = $result_x;
        }// end if
		return mysql_fetch_array($this->result);
    }// end function
	//===========================================================
	function ejecutar_m($query_x=""){
		if ($query_x!=""){
			$this->query = $query_x;
        }// end if
		if (!$this->conexion){
			$this->cls_conexion();
		}// end if
		if ($this->con_transaccion==C_SI and $this->errabs>0){
			return false;
		}// end if
		$array = preg_split("/(?<!\\\)".C_SEP_Q."/",$this->query);
		$this->nro_query = count($array);
		for ($i=0; $i<$this->nro_query;$i++){
			$this->query_m[$i]=$array[$i];
			$this->result_m[$i] = mysql_query($array[$i]);
			$this->errno_m[$i] = mysql_errno();
			if (mysql_errno()>0){
				$this->errabs++;
			}// end if
		}// next
	}// end if
	//===========================================================
    function begin_trans(){
        mysql_query("BEGIN");
		$this->errabs = 0;
    }// end function
	//===========================================================
    function end_trans($tipo_x=C_COMMIT){
    	switch($tipo_x){
		case C_COMMIT:
        	$this->commit();
            break;
		case C_ROLLBACK:
        	$this->rollback();
            break;
		case C_IGNORAR_TRANS:
            // no hace nada
            break;
        }// end switch
		$this->errabs = 0;
    }// end function
	//===========================================================
    function rollback(){
        mysql_query("ROLLBACK");
		$this->errabs = 0;
    }// end function
	//===========================================================
    function commit(){
        mysql_query("COMMIT");
		$this->errabs = 0;
    }// end function
	//===========================================================
    function descrip_campos($result=""){
		if($result!=""){
			$this->result = $result;
		}// end if
		unset($this->tablas);
		$this->nro_campos = mysql_num_fields($this->result);
		$this->nro_filas = mysql_num_rows($this->result);
    	for ($i=0;$i< $this->nro_campos;$i++){
            $tabla = mysql_field_table($this->result,$i);
			$campo = mysql_field_name($this->result,$i);
			if($tabla != null and $tabla != ""){
				$aux = false;
			}else{
				$aux = true;
				$tabla = $this->taux;
			}// end if
			$this->campo[$tabla][$campo] = new descrip_campo;
			$this->campo[$i] = &$this->campo[$tabla][$campo];
			$this->campo[$tabla][$campo]->nombre = $campo;
			$this->campo[$tabla][$campo]->tabla = $tabla;
			$this->campo[$tabla][$campo]->aux = $aux;
			$this->campo[$tabla][$campo]->tipo = mysql_field_type($result,$i);
			$this->campo[$tabla][$campo]->longitud = mysql_field_len($result,$i);
			$this->campo[$tabla][$campo]->meta = $this->tipo_meta($this->campo[$tabla][$campo]->tipo);
			$this->campo[$tabla][$campo]->num = $i;
			if(!$aux){
				$this->tablas[$tabla]="1";
			}// end if
		}// next
		if(!$this->tablas){
			return true;
		}// end if
		$this->ckeys = "";	
		foreach ($this->tablas as $tabla => $v){
			$query = "DESCRIBE $tabla";
			$result = mysql_query($query);
			$nro_filas = mysql_num_rows($result);
			for ($i=0;$i<$nro_filas;$i++){
				$rs = mysql_fetch_row($result);
				$campo = $rs[0];
				$this->campo[$tabla][$campo]->default = $rs[4];
				$this->campo[$tabla][$campo]->null =  $rs[2];
				if($rs[3]=="PRI"){
					$this->campo[$tabla][$campo]->primary = true;
					$this->ckeys .= (($this->ckeys!="")?",":"").$tabla.".".$campo;
				}elseif($rs[3]=="UNI"){
					$this->campo[$tabla][$campo]->unique = true;
				}// end if
				if ($rs[5]=="auto_increment"){
					$this->campo[$tabla][$campo]->serial = true;
					$this->serial[$tabla] = C_CLAVE_SERIAL;
				}// end if
			}// next
		}// next			
    }// end function
	//===========================================================
	function es_select($result_x){
		if (substr($result_x,0,8)=="Resource"){
			$this->es_consulta = true;
			return true;
        }// end if
		$this->es_consulta = false;
        return false;
    }// end function
	//===========================================================
	function hacer_query($query_x){
		if(!preg_match("|[ ]+|", trim($query_x))){
			return "SELECT * FROM ".$query_x;
		}else{
			return $query_x;	
		}// end if
    }// end function
	//===========================================================
	function es_error($error=false){
		$this->error = false;
		if ($error==false){
			return true;
		}// end if
		$this->error = true;
        $this->errno = mysql_errno();
		$this->errmsg = msg_errores($this->errno,mysql_error());
		if ($this->mostrar_error){
			$errmsg=str_replace(chr(10),"",$this->errmsg." Query: $this->query");
			$errmsg=str_replace(chr(13)," ",$errmsg);
			alert($errmsg);
		}// end if
		return false;
	}// end function
	//===========================================================
	function show($msg){
		echo "<hr>$msg<hr>";
    }// end function
	//===========================================================
	function tipo_meta($tipo_x){
    	switch ($tipo_x){
        	case "int":
            	return C_TIPO_I;
        	case "string":
            	return C_TIPO_C;
        	case "blob":
            	return C_TIPO_X;
        	case "float":
            	return C_TIPO_N;
        	case "real":
            	return C_TIPO_N;
        	case "date":
        	case "timestamp":
            	return C_TIPO_D;
        	case "time":
            	return C_TIPO_T;
            default:
            	return C_TIPO_C;
        }// end switch
    }// end function
	//===========================================================
    function usar_bd($bd_x=""){
		if($bd_x!=""){
			$this->bdatos = $bd_x;
		}// end if
		mysql_select_db($this->bdatos);
	}// end fucntion
	//===========================================================
    function extraer_bdatos(){
	    $result_x = mysql_list_dbs();
		$i=0;
	    while ($rs=mysql_fetch_array($result_x)){
			$bdatos[$i] = $rs[0];
			$i++;
		}// end while
		return $bdatos;
    }// end function
	//===========================================================
    function extraer_tablas($db_x=""){
    	if ($db_x == ""){
    		$db_x = $this->bdatos;
        }// end if
	    $result_x = mysql_list_tables($db_x);
		$i=0;
	    while ($rs=mysql_fetch_array($result_x)){
			$tablas[$i] = $rs[0];
			$i++;
		}// end while
		mysql_select_db($this->bdatos);
       	return $tablas;
    }// end function
	//===========================================================
	function extraer_campos($tabla_x="",$db_x=""){
    	if ($tabla_x==""){
        	return false;
        }// end if
    	if ($db_x==""){
    		$db_x = $this->bdatos;
        }// end if
		$result_x = mysql_list_fields($db_x,$tabla_x);
        $nro_campos = mysql_num_fields($result_x);
        for ($i=0;$i<$nro_campos;$i++){
            $campos[$i] = mysql_field_name($result_x,$i);
        }// next
		mysql_select_db($this->bdatos);
        return $campos;
    }// end function
	//===========================================================
    function test($query_x=""){
    	if ($query_x==""){
			$query_x = $this->query;
        }// end if
		
		$this->paginacion = true;
		$this->reg_pag = 10;
        $result = $this->ejecutar($query_x);
		$cadena = "<table border='1'>";
        if (!$this->es_select($result)){
			return "consulta no valida";
        }// end if
        $this->descrip_campos($result);
       	$cadena .= "<tr>";
        for ($i=0;$i<$this->nro_campos;$i++){
            $cadena .= "<th>".$this->campo[$i]->nombre." ".$this->campo[$i]->meta.$this->campo[$i]->longitud."<br>(Dft:".$this->campo[$i]->default.")"."</th>";
        }// next
        $cadena .= "</tr>";
        while($this->arreglo = $this->consultar($result)){
        	$cadena .= "<tr>";
            for ($i=0;$i<$this->nro_campos;$i++){
	        	$cadena .= "<td>".$this->arreglo[$i]."</td>";
            }// next
            $cadena .= "</tr>";
        }// wend
        $cadena .= "</table>";
        return $cadena;
    }// end function
}// end class
function msg_errores($nro_error,$msg_error=""){
	switch ($nro_error){
	case "1216":
		return "Error: fallo en la restricciones de la tabla";
		break;
	case "1217":
		return "Error: No se pudo hacer la eliminación, existe una restricción en la tabla";
		break;
	case "1054":
		return "Error: columna desconocida en la consulta ejecutada";
		break;
	case "1062":
		return "Error: El registro que se intentó agregar ya existe";
		break;
	case "1146":
		return "Error: La tabla no existe";
		break;
	default:
		return $msg_error." N° de error: ".$nro_error;
	}// end switch
}// end function
/*
$cn = new cls_conexion;
$cn->query = "select * from cfg_botones ";
$cn->paginacion = C_SI;
$cn->reg_pag = 4;
$cn->pagina = "3";
$result = $cn->ejecutar();

$cn->descrip_campos($result);
echo $cn->test($cn->query);

$cn->query = "INSERT INTO prueba VALUES ('','10','yanny')";
//$result = $cn->ejecutar();
//hr($cn->insert_id,"green");
$cn->extraer_campos("cfg_tablas","habilitaduria");
$result = $cn->ejecutar("select * from prueba2");


$cn->extraer_tablas();
*/
?>