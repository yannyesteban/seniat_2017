<?php
//http://dryicons.com/search/?s=delete#icons
//192.168.1.21= ABQIAAAAml-0tN6FjvUc1i5fI211pBQiKmPLFMIZ4lltuP33arqbu8X2xxS0Vq0sa2ZZmiO_OvfAm-IcYknjPw
//bestsecurity.com= ABQIAAAAml-0tN6FjvUc1i5fI211pBSpJJVqxjGV-4jny1bVcy8DiYObKBT88rKnedEv2bzdWQfjbU3pVp_VZg
//localhost=ABQIAAAAml-0tN6FjvUc1i5fI211pBT2yXp_ZAY8_ufC3CFXhHIE1NvwkxR33lIsJKUuxAaKcBnVUMAWdEic0w
session_start();
include ("constantes.php");


include(C_PATH."init.php");

ini_set('default_charset', SS_CHARSET);
//echo ini_get('default_charset');phpinfo();exit;
$tiempo_inicio = microtime(true);
$debug = "";


define("SS_PATH_QUERY", "SS_PATH_QUERY");
define ("SS_INS", "SS_INS");
define ("SS_PATH","SS_PATH");

date_default_timezone_set(SC_DATE_TIME_ZONE);
include (C_PATH_CONFIGURACION."configuracion.php");
//define ("C_IP","webcar.bestsecurity.com");
//define ("C_IP","http://192.168.1.21/webcar");
include (C_PATH."clases/sg_configuracion.php");
include (C_PATH."clases/funciones.php");
//include (C_PATH."clases/cls_conexion.php");
include (C_PATH."clases/sgConnection.php");
include (C_PATH."clases/cls_table.php");
include (C_PATH."clases/cls_element_html.php");

//$ajax = $_GET["ajax"];
$html = "";
$script = "";
$debug = "";

$ins = "";



if(isset($_GET["cfg_ins_aux"]) or isset($_POST["cfg_ins_aux"])){
	$ins = $_GET["cfg_ins_aux"];
	$aut = $_SESSION["VSES"][$ins]["SS_AUT"];
	$ses = &$_SESSION["VSES"][$ins];
}else{	
	$aut = false;
}// end if
if(!$aut){
	echo "no tiene autorizacion";
	exit;
}// end if



//$ses["SS_SERVER_ADDR"]="http://web.bestsecurity.com/";
class xml_doc{
	var $vses = array();
	var $vreq = array();
	var $panel = array();
	function __construct() {
		//return;
		$ins = "";
		if(isset($_GET["cfg_ins_aux"])){
			$ins = $_GET["cfg_ins_aux"];
			$aut = $_SESSION["VSES"][$ins]["SS_AUT"];
			$ses = &$_SESSION["VSES"][$ins];
		}else{	
			$aut = false;
		}// end if
		if(!$aut){
			//echo "no tiene autorizacion";
			exit;
		}// end if
		$this->vses = &$ses;
		$this->vreq = &$_GET;
    }// end function	
	public function eval_req($req=""){
		$val = explode(",", $req);
		foreach($val as $k => $v){
			switch($v){
			case "buscar_supervisor":
				$this->panel[] = buscar_supervisor();
				break;
			case "xxxx":
				$this->panel[] = xxx();
				break;
			}// end switch
			
			
			
		}// next
		return $this->xml();
	}// end function
	
	public function xml(){
		$xml ="<?xml version='1.0' encoding='iso-8859-1'?>";
		$xml .= "\n<webcar>";
		foreach($this->panel as $k => $v){
			
			
			if(!isset($v["mode"])){
				$v["mode"] = "";
			}
			if(!isset($v["message"])){
				$v["message"] = "";
			}
			if(!isset($v["debug"])){
				$v["debug"] = "";
			}
			
			
			//$v["debug"]="";
			$xml .= "\n<panel>";
			$xml .= "\n\t<id><![CDATA[$v[id]]]></id>";
			$xml .= "\n\t<mode><![CDATA[$v[mode]]]></mode>";
			$xml .= "\n\t<html><![CDATA[$v[html]]]></html>";
			$xml .= "\n\t<script><![CDATA[$v[script]]]></script>";
			$xml .= "\n\t<message><![CDATA[$v[message]]]></message>";
			$xml .= "\n\t<debug><![CDATA[$v[debug]]]></debug>";
			$xml .= "\n</panel>";	
		}// next
		$xml .= "\n</webcar>";
		return $xml;
	}// end function 
	
}// end class
/**/
header('Content-type: text/xml');
$c = new xml_doc();
//echo $c->eval_req("cargar_eventos");

echo $c->eval_req($_GET["p"]);
//$c->eval_req($_GET["p"]);
exit;


function buscar_supervisor(){
	$ses = &$GLOBALS["ses"];
	$cedula = addslashes($_GET["cedula"]);
	$cn = sgConnection();
	$cn->query = "	
	
	
		SELECT personas.codpersona, cedula, nombre_1, apellido_1 ,
		cargo, dependencia, telefono, email
		
		FROM personas 
		
		INNER JOIN funcionarios as f ON f.codpersona = personas.codpersona
		INNER JOIN cargos as c ON c.codcargo = f.codcargo
		INNER JOIN dependencias as d ON d.coddependencia = f.coddependencia		
		
		WHERE cedula='$cedula'";
	$result = $cn->ejecutar();
	$script = "";

	if($rs=$cn->consultar($result)){
		$codpersona = addslashes($rs["codpersona"]);
		$cedula = addslashes($rs["cedula"]);
		$nombre_1 = addslashes($rs["nombre_1"]);
		$apellido_1 = addslashes($rs["apellido_1"]);

		$dependencia = addslashes($rs["dependencia"]);
		$cargo = addslashes($rs["cargo"]);
		$telefono = addslashes($rs["telefono"]);
		$email = addslashes($rs["email"]);

		
		$script .= "\ndocument.forms[0].cedula.value='$cedula';";
		$script .= "\ndocument.forms[0].codpersona_sup.value='$codpersona';";
		$script .= "\ndocument.forms[0].nombre_1.value='$nombre_1';";
		$script .= "\ndocument.forms[0].apellido_1.value='$apellido_1';";
		
		$script .= "\ndocument.forms[0].dependencia.value='$dependencia';";
		$script .= "\ndocument.forms[0].cargo.value='$cargo';";
		$script .= "\ndocument.forms[0].telefono.value='$telefono';";
		$script .= "\ndocument.forms[0].email.value='$email';";
		
		$script .= "\ndocument.forms[0].buscar_cedula.value='';";
		
		
	}else{		
		$script .= "alert('El Funcionario no fue encontrado')";
		$script .= "\ndocument.forms[0].buscar_cedula.focus();";
	}// end while
	$aux["html"] = "";
	$aux["script"] = $script;
	$aux["id"] = "page_warning";
	//$aux["script"] = "alert('hola a Nadie')";
	return $aux;	
	
	
}// end function


?>