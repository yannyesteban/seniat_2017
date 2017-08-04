<?php
//==========================================================================
function es_nombre($para_x){
	if(!preg_match("|[ ]+|", trim($para_x))){
		return true;
	}// end if
	return false;
}// end function
//==========================================================================
function medida($q){
	if(preg_match("/([\d]+)\s*(%|px)?/",$q,$c)){
		return $c;
	}// end if
	return false;
}// end function

//==========================================================================
function alert($msg_x){
	$cad = "\n<script language=\"javascript1.2\" type=\"text/javascript\">";
	$cad .= "\n\talert(\"$msg_x\")";
	$cad .= "\n</script>";
	echo $cad;
}// end function
//==========================================================================
function hr($msg_x,$color_x="green"){
	if ($color_x==""){
		echo "<hr>$msg_x<hr>";
	}else{
		echo "<hr><span style=\"color:$color_x;font-family:tahoma;font-size:9pt;font-weight:bold;\">$msg_x</span><hr>";
	}// end if
	
}// end function
//==========================================================================
function br($msg_x,$color_x=""){
	if ($color_x==""){
		echo "<br>$msg_x";
	}else{
		echo "<br><b><span style=\"color:$color_x\">$msg_x</span></b>";
	}// end if
	
}// end function
//==========================================================================
function vm($vector){
	echo "<hr>";
	foreach($vector as $index => $valor){
		echo "<br>Matriz: $index = $valor";	
	
	}// next
	echo "<hr>";

}// end if
//==========================================================================
function vmm($vector){
	echo "<hr>";
	foreach($vector as $index => $valor){
		foreach($valor as $index2 => $valor2){
			echo "<br>Matriz: $index - $index2 : $valor2";	
		}// next
	
	}// next
	echo "<hr>";

}// end if

//==========================================================================
function c_mes($mes_x){
	$mes = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	return $mes[$mes_x-1];

}// end function

//==========================================================================

function exp_split($simb,$q=""){
	if($q==""){
		return false;
	}// end if
	$q = preg_replace("|(?<!\\\)".$simb."|","sGsimBolo",$q);
	$q = preg_replace("|(\\\\".$simb.")|",$simb,$q);
	return preg_split("|(?<!\\\)"."sGsimBolo"."|",$q);
}// end function
//==========================================================================





function extraer_var2 ($cadena_x=""){
	if($cadena_x==""){
		return false;
	}// end if
	$gpo = explode(C_SEP_L,$cadena_x);
	for($i=0;$i<count($gpo);$i++){
		$aux = explode(C_SEP_TITULOS,$gpo[$i]);
		if(count($aux)>1){
		
			$grupo[$aux[0]]=$aux[1];
		}else{
			$grupo[$aux[0]]=$aux[0];
		}// end if
	}// next
	return $grupo;
}// end function	
//==========================================================================
function extraer_var($cadena_x=""){
	if($cadena_x==""){ 
		return false;
	}// end if
	$gpo = explode(C_SEP_L,$cadena_x);
	return $gpo;
}// end function
//==========================================================================
function formato_fecha($fecha_x=""){
	$f = explode("-",$fecha_x);
	if(count($f)>1 and $f[2]!="00"){
	    return $f[2]."/".$f[1]."/".$f[0];
	}else if (strlen($fecha_x)==8){
		return substr($fecha_x,6,2)."/".substr($fecha_x,4,2)."/".substr($fecha_x,0,4);
	}else{
		return "";
	}// end if
}// end function
//==========================================================================
function extraer_para($para_x){
    if ($para_x=="" or $para_x == null){
        return false;
    }// end if
	$aux = preg_split("|(?<!\\\)".C_SEP_Q."|",$para_x);
    foreach($aux as $id => $q){
		$aux2 = preg_split("|(?<!\\\)".C_SEP_V."|",$q);
        if($aux2[1]!=null){
			$aux2[1] = str_replace("\\".C_SEP_Q,C_SEP_Q,$aux2[1]);
			$aux2[1] = str_replace("\\".C_SEP_V,C_SEP_V,$aux2[1]);
			$para[trim($aux2[0])]=$aux2[1];
        }else if($q!=""){
			$q = str_replace("\\".C_SEP_Q,C_SEP_Q,$q);
			$q = str_replace("\\".C_SEP_V,C_SEP_V,$q);
            $para[C_VAR_QUERY] = $q;
        }// end if
    }// next
    return $para;
}//end function
//==========================================================================
function leer_var($q,$matriz,$simb,$con_comillas=true,$estricto=true){
	if ($q==""){
		return "";
    }// end if
	$exp = "/(?<![\w])(?<!\\\)".$simb."([\w]+)/";
	$comilla = ($con_comillas)?"'":"";
    $val_def = ($estricto)?"\\$simb$1":$comilla.$comilla;
	while(preg_match($exp,$q,$c)){
		if (isset($matriz[$c[1]])){
			$q = preg_replace($exp,$comilla.$matriz[$c[1]].$comilla,$q,1);
		}else{
			$q = preg_replace($exp,$val_def,$q,1);
		}// end if
	}// end while
	$q = str_replace("\\".$simb,$simb,$q);
	return $q;
}// end function
//==========================================================================
function eval_prop($q){
	if ($q=="" or $q==null)
		return $q;
	$exp = "/if:((?:\\\;|[^;])+)/";
	$exp2 = "/then:((?:\\\;|[^;])+)/";
	$exp3 = "/else:((?:\\\;|[^;])+)/";
    $w="";
	if(preg_match_all($exp, $q, $c)){
		foreach($c[1] as $k =>$x){
			if(preg_match_all($exp2, $q, $th)){
				eval("(\$eva = ($x));");
				if ($eva) {
                	$w .= $th[1][$k].";";
               	}else if(preg_match_all($exp3, $q, $th)){
	            	$w .= $th[1][$k].";";
				}// end if
            }// end if
		}//next
	}// end if
	$q =  preg_replace("/(if:((?:\\\;|[^;])+);*)/","",$q);
	$q =  preg_replace("/(then:((?:\\\;|[^;])+);*)/","",$q);
	$q =  preg_replace("/(else:((?:\\\;|[^;])+);*)/","",$q);
	$q = $w.$q;
	$q = preg_replace("/(?<!\\\)".C_SEP_P."/",C_SEP_Q,$q);
	$q = str_replace("\\".C_SEP_P,C_SEP_P,$q);
    return $w.$q;
}// end if
//==========================================================================
function extraer_spara($para_x){
    if ($para_x=="" or $para_x == null){
        return false;
    }// end if
	$para = preg_split("|(?<!\\\)".C_SEP_PP."|",$para_x);
    $simb = str_replace("\\","",C_SEP_PP);
    return preg_replace("/\\\\".C_SEP_PP."/",$simb,$para);
}//end function
//==========================================================================
function reparar_sep($q,$sep = C_SEP_Q){
	if ($q==""){
		return "";
    }// end if
	$patron= "{[^$sep]$|$sep+}";
	$q = preg_replace($patron,$sep,trim($q));
	return $q;
}// end if
//==========================================================================
?>