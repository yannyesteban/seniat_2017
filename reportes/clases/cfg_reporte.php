<?php
/*****************************************************************
creado: 01/07/2007
modificado: 11/07/2007
por: Yanny Nuñez
*****************************************************************/
define("C_REP_CLASE_PATRON","");
define("C_REP_LINEAS_PAG","40");
define("C_REP_MSG_ERR_NO_PAGINAS","La(s) pagina(s) solicitada(s) está(n) fuera de rango");
require_once("cls_conexion.php");
//===========================================================
class cfg_reporte{
	var $reporte = "";
	var $titulo = "";
	var $clase = C_REP_CLASE_PATRON;
	var $tipo = "";
	var $plantilla = "";
	var $query = "";
	var $parametros = "";
	var $expresiones = "";
	var $expresiones_det = "";
	var $cortes = "";
	var $totales = "";
	var $totalizar = "";
	var $campos_busquedas = "";
	var $lineas_pag = 1000000;//C_REP_LINEAS_PAG;
 	var $estilo = ""; 
	var $propiedades = "";
	var $estilo_titulo = "";
	var $propiedades_titulo = "";
	var $estilo_corte = "";
	var $propiedades_corte = "";
	var $estilo_det = "";
	var $propiedades_det = "";
	var $estilo_pie_corte = "";
	var $propiedades_pie_corte = "";
	var $estilo_pie_inf = "";
	var $propiedades_pie_inf = "";
	//===========================================================
	var $clase_titulo = "";
	var $clase_pagina = "";
	var $clase_corte = "";
	var $clase_detalle = "";
	var $clase_pie_corte = "";
	var $clase_pie_pagina = "";
	var $clase_pie_inf = "";
	var $corte_titulo = "";
	//===========================================================
	var $con_comillas = true;	
	var $sin_comillas = false;	
	var $sep_decimal = C_SEP_DECIMAL;	
	var $sep_mil = C_SEP_MIL;	
	var $decimales = C_REP_DECIMALES;
	//===========================================================
	var $mostrar_todas = true;
	var $pagina_ini = "1";
	var $pagina_fin = "";
	var $msg_err_no_pagina = C_REP_ERR_NO_PAGINAS;
	//===========================================================
	var $configurado = false;
	var $conexion = false;
	//===========================================================
	var $cfg_reportes = C_CFG_REPORTES;
	var $cfg_consultas = C_CFG_CONSULTAS;
	var $cfg_plantillas = C_CFG_PLANTILLAS;
	//===========================================================
	var $vses = array();
	var $vform = array();
	var $vpara = array();
	var $vreg = array();
	var $vexp = array();
	//===========================================================
	function ejecutar($reporte_x=""){
		if($reporte_x!=""){
			$this->reporte = $reporte_x;
		}// end if
		//===========================================================
		if(!$this->conexion){
			$this->conexion = new cls_conexion;
		}// end if
		$cn = &$this->conexion;
		$cn->query = "	SELECT reporte, $this->cfg_reportes.titulo, clase, $this->cfg_reportes.tipo, $this->cfg_reportes.plantilla, 
						query, parametros, expresiones, expresiones_det, cortes, totales, totalizar,
						campos_busquedas, lineas_pag, 
						estilo, propiedades, estilo_titulo, propiedades_titulo, estilo_corte, propiedades_corte, 
						estilo_det, propiedades_det, estilo_pie_corte, propiedades_pie_corte, 
						estilo_pie_inf, propiedades_pie_inf, 
						$this->cfg_plantillas.diagrama
						FROM $this->cfg_reportes
						LEFT JOIN $this->cfg_plantillas ON $this->cfg_plantillas.plantilla = $this->cfg_reportes.plantilla
						WHERE reporte = '$this->reporte' ";
						/*
						UNION
						SELECT consulta as reporte,titulo, clase,'0' as tipo,'' as plantilla, 
						query,  parametros,'' as expresiones, prop_fila as expresiones_det, '' as cortes,'' as totales, '0' as totalizar,
						campos_busqueda,  reg_bloque as lineas_pag,
						'' as estilo, '' as propiedades, '' as estilo_titulo, '' as propiedades_titulo, '' as estilo_corte, '' as propiedades_corte, 
						'' as estilo_det, '' as propiedades_det, '' as estilo_pie_corte, '' as propiedades_pie_corte, 
						'' as estilo_pie_inf, '' as propiedades_pie_inf, 
						'' as diagrama
						FROM $this->cfg_consultas
						WHERE consulta = '$this->reporte'
						";
						*/
		$result = $cn->ejecutar();
		if($rs=$cn->consultar($result)){
			//===========================================================
			$this->reporte = &$rs["reporte"];
			$this->titulo = &$rs["titulo"];
			if($rs["clase"]!=""){
				$this->clase = &$rs["clase"];
			}// end if
			$this->tipo = &$rs["tipo"];
			$this->plantilla = &$rs["plantilla"];
			$this->query = &$rs["query"];
			$this->parametros = &$rs["parametros"];
			$this->expresiones = &$rs["expresiones"];
			$this->expresiones_det = &$rs["expresiones_det"];
			$this->cortes = &$rs["cortes"];
			$this->totales = &$rs["totales"];
			$this->totalizar = &$rs["totalizar"];
			$this->campos_busquedas = &$rs["campos_busquedas"];
			if($rs["lineas_pag"]){
				$this->lineas_pag = &$rs["lineas_pag"];
			}// end if
			$this->estilo = &$rs["estilo"];
			$this->propiedades = &$rs["propiedades"];
			$this->estilo_titulo = &$rs["estilo_titulo"];
			$this->propiedades_titulo = &$rs["propiedades_titulo"];
			$this->estilo_corte = &$rs["estilo_corte"];
			$this->propiedades_corte = &$rs["propiedades_corte"];
			$this->estilo_det = &$rs["estilo_det"];
			$this->propiedades_det = &$rs["propiedades_det"];
			$this->estilo_pie_corte = &$rs["estilo_pie_corte"];
			$this->propiedades_pie_corte = &$rs["propiedades_pie_corte"];
			$this->estilo_pie_inf = &$rs["estilo_pie_inf"];
			$this->propiedades_pie_inf = &$rs["propiedades_pie_inf"];
			$this->diagrama = &$rs["diagrama"];
			//===========================================================
			$this->vpara = &$rs;
			//===========================================================
			$this->parametros = $this->evaluar_todo($this->parametros);
			if($prop = extraer_para($this->parametros)){
				$this->vpara = array_merge($this->vpara,$prop);
				foreach($prop as $para => $valor){
					eval("\$this->$para=\"$valor\";");
				}// next
			}// end if
			//===========================================================
			if($this->q_maestro != ""){
				$cn->ejecutar($this->q_maestro);
				$result = $cn->ejecutar();
				if($rs_x = $cn->consultar($result)){
					$this->vreg = &$rs_x;
				}// end if
			}// end if
			//===========================================================
			$this->expresiones = $this->evaluar_todo($this->expresiones);			
			if($prop = extraer_para($this->expresiones)){
				$this->vexp = $prop;
			}// end if
			//===========================================================
			if($this->sin_comillas == "si"){
				$this->con_comillas = false;
			}// end if
			//===========================================================
			$this->query = $this->evaluar_todo($this->query, $this->con_comillas);
			if ($this->query=="" or $this->query==null){
				$this->query = "SELECT * FROM $this->reporte ";
			}else if($this->query!="" && !preg_match("|[ ]+|", trim($this->query))){
				$this->query = "SELECT * FROM $this->query ";
			}// end if	
			//===========================================================
			if($this->clase!=""){
				if($this->clase_titulo==""){
					$this->clase_titulo = $this->clase."_rpt_titulo";
				}// end if
				if($this->clase_pagina==""){
					$this->clase_pagina = $this->clase."_rpt_pagina";
				}// end if
				if($this->clase_corte==""){
					$this->clase_corte = $this->clase."_rpt_corte";
				}// end if
				if($this->clase_detalle==""){
					$this->clase_detalle = $this->clase."_rpt_detalle";
				}// end if
				if($this->clase_pie_corte==""){
					$this->clase_pie_corte = $this->clase."_rpt_pie_corte";
				}// end if
				if($this->clase_pie_pagina==""){
					$this->clase_pie_pagina = $this->clase."_rpt_pie_pagina";
				}// end if
				if($this->clase_pie_inf==""){
					$this->clase_pie_inf = $this->clase."_rpt_pie_inf";
				}// end if
			}// end if
			//===========================================================
			$this->expresiones_det = $this->evaluar_var_exp($this->expresiones_det);
			$this->campos_busquedas = $this->evaluar_todo($this->campos_busquedas);			
			$this->cortes = $this->evaluar_todo($this->cortes);	
			$this->corte = $this->extraer_corte($this->cortes,$this->corte_titulo);

			$this->estilo = $this->evaluar_todo($this->estilo);
			$this->propiedades = $this->evaluar_todo($this->propiedades);
			$this->estilo_titulo = $this->estilo.$this->evaluar_todo($this->estilo_titulo);
			$this->propiedades_titulo = $this->propiedades.$this->evaluar_todo($this->propiedades_titulo);
			
			$this->estilo_corte = $this->evaluar_var_exp($this->estilo_corte);	
			$this->propiedades_corte = $this->evaluar_var_exp($this->propiedades_corte);	

			$this->estilo_det = $this->evaluar_var_exp($this->estilo_det);	
			$this->propiedades_det = $this->evaluar_var_exp($this->propiedades_det);	
			$this->estilo_pie_corte = $this->evaluar_var_exp($this->estilo_pie_corte);	
			$this->propiedades_pie_corte = $this->evaluar_var_exp($this->propiedades_pie_corte);	
			$this->estilo_pie_inf = $this->evaluar_var_exp($this->estilo_pie_inf);	
			$this->propiedades_pie_inf = $this->evaluar_var_exp($this->propiedades_pie_inf);	
			$this->diagrama = $this->evaluar_var($this->diagrama);
			//===========================================================
			$this->configurado = true;
			return true;
		}// end if	
		$this->query = "SELECT * FROM $this->reporte ";
		return false;
	}// end function
	//===========================================================
	function extraer_corte($cadena_x="",&$titulo){
		if($cadena_x==""){
			return false;
		}// end if
		$gpo = explode(C_SEP_GRUPOS,$cadena_x);
		$i=0;
		foreach($gpo as $k => $v){
			if($v=="" or $v==null){
				continue;
			}// end if
			$aux = explode(C_SEP_TITULOS,$v);
			$corte[$i] = trim($aux[0]);
			$titulo[$i]=$aux[1];
			$i++;
		}// next
		return $corte;
	}// end function
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
		return $q.C_SEP_Q;
	}// end function
	//===========================================================
	function evaluar_var($q="",$con_comillas=false){
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
	function evaluar_var_exp($q="",$con_comillas=false){
		if($q==""){
			return "";
		}// end if
		$q = leer_var($q,$this->vreg,C_IDENT_VAR_REG,$con_comillas);		
		$q = leer_var($q,$this->vpara,C_IDENT_VAR_PARA,$con_comillas);		
		$q = leer_var($q,$this->vform,C_IDENT_VAR_FORM,$con_comillas);
		$q = leer_var($q,$this->vses,C_IDENT_VAR_SES,$con_comillas);
		return $q.C_SEP_Q;
	//===========================================================
	}// end function	
}// end class
?>