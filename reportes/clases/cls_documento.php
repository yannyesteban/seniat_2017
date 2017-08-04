<?php
define("C_MODO_AGREGAR","1");
define("C_MODO_VINCULAR","2");
define("C_MODO_DOC","1");
define("C_MODO_ECHO","2");
class cls_documento{
	var $title = "";
    var $body = "";
    var $meta = "";
	var $doctype = "";//"<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"\n\"http://www.w3.org/TR/html4/loose.dtd\">";
	var $http_equiv = "";//"\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">";
    var $keywords = "";
    var $description = "";
	var $refresh = "";
    var $style_value = "";
	var $js_value = "";
	var $include = "";
	var $include_value = "";
	var $archivo_value = "";
	var $msg_value = "";
	var $js_value_post = "";
	var $onclick_value = "";
	//====================== Métodos y Funciones
	function onclick($evento_x){
		if($evento_x==""){
			return "";
		}// end if
		$this->onclick_value .= "\n\t".$evento_x;
	}// end function
	function style($style_x,$tipo_x=C_MODO_VINCULAR){
		if($style_x==""){
			return false;
		}// end if
		if($tipo_x==C_MODO_VINCULAR){
			$this->style_value .= "\n<link href=\"$style_x\" rel=\"stylesheet\" type=\"text/css\">";
			}
		else{
			$this->style_value .= "\n<style type=\"text/css\">\n$style_x\n</style>";
		}// end if
	}// end function
	function js($js_x,$tipo_x=C_MODO_VINCULAR){
		if($js_x==""){
			return false;
		}// end if
		if($tipo_x==C_MODO_VINCULAR){
			$this->js_value .= "\n<script language=\"JavaScript1.2\" src=\"$js_x\" type=\"text/javascript\"></script>";
			}
		else{
			$this->js_value .= $this->jscript($js_x);
		}// end if
	}// end function
	function jscript($script_x){
		if($script_x==""){
			return false;
		}// end if
		return "\n<script language=\"JavaScript1.2\" type=\"text/javascript\">\n$script_x\n</script>";
	}// end if
	function js_post($js_x="",$tipo_x=C_MODO_VINCULAR){
		if($js_x==""){
			return false;
		}// end if
		if($tipo_x==C_MODO_VINCULAR){
			$this->js_value_post .= "\n<script language=\"JavaScript1.2\" src=\"$js_x\" type=\"text/javascript\"></script>";
			}
		else{
			$this->js_value_post .= $this->jscript($js_x);
		}// end if
	}// end function
	function msgbox($msg_x=""){
		if ($msg_x == "")
			return false;
		$this->msg_value .= $this->jscript("alert('$msg_x')");
	}// end if
	function incluir($archivo_x,$path_x="http://localhost/"){
		$this->archivo_value = $archivo_x;
		$this->include_value = $path_x."/".$archivo_x;
	}// end function
    function control($tipo_x=C_MODO_DOC){
		if ($tipo_x==C_MODO_DOC){	
			$doc = $this->doctype;
			$doc .= "<html>";
			$doc .= "\n<head>";
			$doc .= $this->http_equiv;
			if ($this->keywords!=""){
				$doc .= "\n<meta name=\"keywords\" content=\"$this->keywords\">";
			}// end if
			if ($this->description!=""){
				$doc .= "\n<meta name=\"description\" content=\"$this->description\">";
			}// end if
			if ($this->refresh!=""){
				$doc .= "\n<meta http-equiv=\"refresh\" content=\"$this->refresh\">";
			}// end if
			if ($this->title!=""){
				$doc .= "\n<title>$this->title</title>";
			}// end if
			$doc .= $this->style_value;
			$doc .= $this->js_value;
			$doc .= "\n</head>";
			$doc .= "\n<body>";
			if ($this->include_value!=""){
				$doc .= file_get_contents($this->include_value);
			}// end if
			$doc .= $this->body;
			$doc .= "\n</body>";
			if($this->onclick_value!=""){
				$aux = "document.onclick = function (){";
				$aux .= $this->onclick_value;
				$aux .= "\n}// end function ";
				$this->js_post($aux,C_MODO_AGREGAR);
			}// end if

			$doc .= $this->js_value_post;
			$doc .= $this->msg_value;
			$doc .= "\n</html>";
			return $doc;
		}
		elseif ($tipo_x==C_MODO_ECHO){
			echo $this->doctype;
			echo "\n<html>";
			echo "\n<head>";
			echo $this->http_equiv;
			if ($this->keywords!=""){
				echo "\n<meta name=\"keywords\" content=\"$this->keywords\">";
			}// end if
			if ($this->description!=""){
				echo "\n<meta name=\"description\" content=\"$this->description\">";
			}// end if
			if ($this->refresh!=""){
				echo "\n<meta http-equiv=\"refresh\" content=\"$this->refresh\">";
			}// end if
			if ($this->title!=""){
				echo "\n<title>$this->title</title>";
			}// end if
			echo $this->style_value;

			
			echo $this->js_value;
			echo "\n</head>";
			echo "\n<body>";
			if($this->onclick_value!=""){
				$aux = "document.onclick = function (){";
				$aux .= $this->onclick_value;
				$aux .= "\n}// end function ";
				$this->js_post($aux,C_MODO_AGREGAR);
			}// end if
			if ($this->archivo_value!=""){
				require_once($this->archivo_value);
			}// end if
			echo "\n</body>";
			echo $this->js_value_post;
			echo $this->msg_value;
			echo "\n</html>";
		}// end if
    }// end function
}// end class
/*
$doc = new cls_doc_html;
$doc->title = "yanny";
$doc->body = "Esta guía proporciona información completa y basada en tareas acerca de todas las funciones de Dreamweaver.
La guía contiene las siguientes secciones:
Aspectos básicos de Dreamweaver
Utilización de los sitios de Dreamweaver
Diseño de páginas
Adición de contenido a las páginas
Utilización del código de las páginas
Preparación para crear sitios dinámicos
Creación de páginas dinámicas
Desarrollo rápido de aplicaciones
Apéndices
";
//$doc->refresh ="10;url=http://www.google.com";
$doc->style("samples/sample4.css");
$doc->style ("samples/sample2.css");
$doc->js("validar.js");
$doc->js("validar2.js");
$doc->js("validar3.js");
$doc->js("alert(1)",C_MODO_AGREGAR);
$doc->js_post("alert('Que Cosas')",C_MODO_AGREGAR);
$doc->js("validar5.js");
$doc->msgbox("Esteban Jimenez");
$doc->msgbox("Yanny Niñez");
$doc->incluir("imagen.php");
$doc->control(C_MODO_ECHO);
*/
?>