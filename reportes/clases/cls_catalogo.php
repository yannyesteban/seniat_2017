<?php
/*****************************************************************
creado: 02/07/2007
modificado: 11/07/2007
por: Yanny Nuñez
*****************************************************************/
define ("C_CAT_FILAS",0);
define ("C_CAT_COLUMNAS",3);
define ("C_CAT_PAGINACION",false);
define ("C_CAT_REG_PAG",6);
define ("C_CAT_REG_GRUPO",3);
define ("C_CAT_ID_FILA","fila_rep");
define ("C_CAT_ID_COLUMNA","columna_rep");
define ("C_CAT_ID_DETALLE","detalle_rep");

define ("C_CAT_EXTENDER_SI","1");
define ("C_CAT_EXTENDER_VACIO","2");
define ("C_CAT_EXTENDER_NO","0");

define ("CAT_NRO_ITEM","NRO_ITEM");
define ("CAT_NRO_ITEM_GPO","NRO_ITEM_GPO");
define ("CAT_NRO_ITEM_DET","NRO_ITEM_DET");

define ("CAT_REG_PAGINA","REG_PAGINA");
define ("CAT_REG_TOTALES","REG_TOTALES");
define ("CAT_NRO_PAGINAS","NRO_PAGINAS");
//===========================================================
class cls_catalogo{
	var $catalogo = "";
	var $titulo = "";
	var $tipo = "";
	var $clase = "";
	var $query = "";
	var $parametros = "";
	var $expresiones = "";
	var $expresiones_det = "";
	var $plantilla = "";
	var $navegador = "";
	var $busqueda = "";
	var $campos_busquedas = "";
	var $reg_pag = C_CAT_REG_PAG;
	var $pag_bloque = "";
	var $reg_grupo = C_CAT_REG_GRUPO;
	var $cfg_catalogos = C_CFG_CATALOGOS;
	var $cfg_plantillas = C_CFG_PLANTILLAS;
	var $cfg_articulos = C_CFG_ARTICULOS;
	
	var $con_comillas = true;

	var $id_fila = C_CAT_ID_FILA;
	var $id_columna = C_CAT_ID_COLUMNA;
	var $id_detalle = C_CAT_ID_DETALLE;
	
	var $filas = C_CAT_FILAS;
	var $columnas = C_CAT_COLUMNAS;

	var $paginacion = C_CAT_PAGINACION;
	var $extender = C_CAT_EXTENDER_NO;
	
	var $pagina = 1;
	//===========================================================
	var $vses = array();
	var $vform = array();
	var $vpara = array();
	var $vreg = array();
	var $vexp = array();
	//===========================================================
	function control($catalogo_x=""){
		if($catalogo_x!=""){
			$this->catalogo = $catalogo_x;
		}// end if
		$cn = new cls_conexion;
		$cn->query = "	SELECT $this->cfg_catalogos.*,$this->cfg_plantillas.diagrama 
						FROM $this->cfg_catalogos 
						INNER JOIN $this->cfg_plantillas ON $this->cfg_plantillas.plantilla = $this->cfg_catalogos.plantilla
						WHERE catalogo = '$this->catalogo'";
		$result = $cn->ejecutar();
		if ($rs = $cn->consultar($result)){
			$this->vpara = &$rs;
			$this->catalogo = $rs["catalogo"];
			$this->titulo = $rs["titulo"];
			$this->tipo = $rs["tipo"];
			$this->clase = $rs["clase"];
			$this->query = $rs["query"];
			$this->parametros = $rs["parametros"];
			$this->expresiones = $rs["expresiones"];
			$this->expresiones_det = $rs["expresiones_det"];
			$this->plantilla = $rs["plantilla"];
			$this->navegador = $rs["navegador"];
			$this->busqueda = $rs["busqueda"];
			$this->campos_busquedas = $rs["campos_busquedas"];
			if($rs["reg_pag"]>0)
				$this->reg_pag = $rs["reg_pag"];
			if($rs["pag_bloque"]>0)
				$this->pag_bloque = $rs["pag_bloque"];
			if($rs["reg_grupo"]>0)
				$this->reg_grupo = $rs["reg_grupo"];
			$this->diagrama = $rs["diagrama"];
			//===========================================================
			//$this->parametros = $this->evaluar_todo($this->parametros);
			
			if($prop = extraer_para($this->parametros)){
				$this->vpara = array_merge($this->vpara,$prop);
				foreach($prop as $para => $valor){
					eval("\$this->$para=\"$valor\";");
				}// next
			}// end if
			//===========================================================
			$this->expresiones = $this->evaluar_todo($this->expresiones);			
			if($prop = extraer_para($this->expresiones)){
				$this->vexp = $prop;
			}// end if
			$this->expresiones_det = $this->evaluar_var_exp($this->expresiones_det);
			//===========================================================
			if($this->sin_comillas=="si"){
				$this->con_comillas = false;
			}// end if
			//===========================================================
			$this->query = $this->evaluar_todo($this->query,$this->con_comillas);
			if ($this->query=="" or $this->query==null){
				$this->query = "SELECT * FROM $this->catalogo ";
			}else if($this->query!="" && !preg_match("|[ ]+|", trim($this->query))){
				$this->query = "SELECT * FROM $this->query ";
			}// end if	
			$this->diagrama = $this->evaluar_var($this->diagrama);
			$this->diagrama = str_replace("--titulo--",$this->titulo,$this->diagrama);
			$this->diagrama = str_replace("--clase--",$this->clase,$this->diagrama);		

			$this->fila_rep = extraer_patron($this->diagrama,$this->id_fila);
			$this->columna_rep = extraer_patron($this->diagrama,$this->id_columna);	
			if($this->q_detalle!=""){
				$con_detalle = true;
				$detalle_x = extraer_spara($this->q_detalle);
				$nro_det = count($detalle_x);
				for($i=0;$i<$nro_det;$i++){
				
					$this->detalle_rep[$i] = extraer_patron($this->diagrama,$this->id_detalle."_".($i+1));
				}// next
			}else{
				$con_detalle = false;
			}// next
			if($this->paginacion){
				$cn->reg_pag = $this->reg_pag;
				$cn->pagina = $this->pagina;
				$cn->paginacion = C_SI;
			}// end if
			$result2 = $cn->ejecutar($this->query);
			$this->nro_registros = $cn->nro_filas;
			$this->vexp[CAT_REG_PAGINA] = $cn->nro_filas;
			$this->vexp[CAT_REG_TOTALES] = $cn->reg_total;
			$this->vexp[CAT_NRO_PAGINAS] = $cn->nro_paginas;

			if($this->filas == "0"){
				$this->filas = ceil($cn->nro_filas/$this->columnas);
			}// end if
			if($this->reg_grupo>0){
				$filas_grupo = ceil($this->reg_grupo/$this->columnas);
			}else{
				$filas_grupo = 0;
			}// end if
			$cn->descrip_campos($result2);
			$celdas = "";
			$aux = "";
			$nro_item=0;
			$nro_item_grupo = 1;
			$this->vexp[CAT_NRO_ITEM] = &$nro_item;
			$this->vexp[CAT_NRO_ITEM_GPO] = &$nro_item_grupo;
			$this->vexp[CAT_NRO_ITEM_DET] = 0;
			for($f=0;$f<$this->filas;$f++){
				$celdas = "";
				for($c=0;$c<$this->columnas;$c++){
					$linea_x = $this->columna_rep;
					if($rs = $cn->consultar($result2)){
						$this->vreg = &$rs;
						$nro_item++;
						//===========================================================
						if($this->expresiones_det!=""){
							$this->vexp = array_merge($this->vexp, extraer_para($this->evaluar_exp($this->expresiones_det)));
						}// end if
						$linea_x = $this->evaluar_exp($linea_x);
						if($con_detalle){
							foreach($detalle_x as $n => $q_detalle){
								//hr($q_detalle);
								$q_detalle= $this->evaluar_todo($q_detalle);
								//hr($q_detalle,"red");
								$linea_det = $this->crear_detalle($q_detalle,$this->detalle_rep[$n]);
								$linea_x = "\n".formar_diagrama($linea_x,$this->id_detalle."_".($n+1),$linea_det);
							}// next
						}// end if
						for($i=0;$i<$cn->nro_campos;$i++){
							$linea_x = str_replace("{=".$cn->campo[$i]->nombre."}",$rs[$i],$linea_x);
						}// next
					}else if($this->extender==C_CAT_EXTENDER_SI){
						$linea_x = $this->evaluar_exp($linea_x);
						for($i=0;$i<$cn->nro_campos;$i++){
							$linea_x = str_replace("{=".$cn->campo[$i]->nombre."}","&nbsp;",$linea_x);
						}// next
					}else if($this->extender==C_CAT_EXTENDER_VACIO){
						$linea_x = inner_html($linea_x,$this->id_columna,"&nbsp;");
					}else{
						$linea_x = "";
					}// end if
					$celdas .= "\n\t".$linea_x;
				}// next
				$lineas .= "\n".formar_diagrama($this->fila_rep,$this->id_columna,$celdas);
				if( ($filas_grupo > 0 and (($f+1) % $filas_grupo) ==0) or ($f+1==$this->filas)){
					$diagrama_x = $this->evaluar_exp($this->diagrama);
					$aux .= formar_diagrama($diagrama_x,$this->id_fila,$lineas);
					$lineas = "";
					$nro_item_grupo++;
				}// end if
			}// next
		}// end if
		return $aux;
	}// end function            
	//===========================================================
	function crear_detalle($q,$linea_x){
		$q = $this->evaluar_exp($q);
		$cn = new cls_conexion;
		$cn->query = $q;
		$result = $cn->ejecutar();
		$cn->descrip_campos($result);
		$linea = "";
		$j = 0;
		while ($rs = $cn->consultar()){
			//hr($rs[0].".......");
			$this->vexp[CAT_NRO_ITEM_DET] = $j++;
			$this->vreg = array_merge($this->vreg,$rs);
			$linea_y = $this->evaluar_exp($linea_x);
			for($i=0;$i<$cn->nro_campos;$i++){
				$linea_y = str_replace("{=".$cn->campo[$i]->nombre."}",$rs[$i],$linea_y);
			}// next
			$linea .= $linea_y;
		}// end while
		return $linea;
	}// end if
	//===========================================================
	function evaluar_todo($q="",$con_comillas=false){
		if($q==""){
			return "";
		}// end if
		$q = leer_var($q,$this->vreg,C_IDENT_VAR_REG,$con_comillas);
		$q = leer_var($q,$this->vpara,C_IDENT_VAR_PARA,$con_comillas);
		$q = leer_var($q,$this->vform,C_IDENT_VAR_FORM,$con_comillas);
		$q = leer_var($q,$this->vses,C_IDENT_VAR_SES,$con_comillas);
		$q = eval_expresion($q);
		$q = eval_prop($q);
		return $q;
	}// end function
	//===========================================================
	function evaluar_var($q="",$con_comillas=false){
		if($q==""){
			return "";
		}// end if
		$q = leer_var($q,$this->vpara,C_IDENT_VAR_PARA,$con_comillas);
		$q = leer_var($q,$this->vreg,C_IDENT_VAR_REG,$con_comillas);
		$q = leer_var($q,$this->vform,C_IDENT_VAR_FORM,$con_comillas);
		$q = leer_var($q,$this->vses,C_IDENT_VAR_SES,$con_comillas);
		return $q;
	}// end function
	//===========================================================
	function evaluar_var_exp($q="",$con_comillas=false){
		if($q==""){
			return "";
		}// end if
		$q = leer_var($q,$this->vreg,C_IDENT_VAR_REG,$con_comillas);		
		$q = leer_var($q,$this->vpara,C_IDENT_VAR_PARA,$con_comillas);		
		$q = leer_var($q,$this->vform,C_IDENT_VAR_FORM,$con_comillas);
		$q = leer_var($q,$this->vses,C_IDENT_VAR_SES,$con_comillas);
		return $q;
	}// end function
	//===========================================================
	function evaluar_exp($q="",$con_comillas=false){
		if($q==""){
			return "";
		}// end if
		$q = leer_var($q,$this->vexp,C_IDENT_VAR_EXP,$con_comillas,true);
		$q = leer_var($q,$this->vreg,C_IDENT_VAR_REG,$con_comillas,true);
		$q = eval_expresion($q);
		$q = eval_prop($q);
		return $q;
	}// end function
	//===========================================================
}// end class
?>