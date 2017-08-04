<?php
/*****************************************************************
creado: 01/07/2007
modificado: 11/07/2007
por: Yanny Nuñez
*****************************************************************/
class cfg_campos_rep{
	var $reporte = "";
	var $query = "";
	var $elem;
	var $campo;
	var $clase = "";
	var $nro_campos;
	//===========================================================
	var $conexion = false;
	//===========================================================
	var $cfg_campos_rep = C_CFG_CAMPOS_REP;
	//===========================================================
	var $vpara = array();
	var $vses = array();
	var $vform = array();
	//===========================================================
	function ejecutar($reporte_x="",$query_x=""){
		if($reporte_x!=""){
			$this->reporte = $reporte_x;
		}// end if
		if($query_x!=""){
			$this->query = $query_x;
		}// end if
		//===========================================================
		if(!$this->conexion){
			$this->conexion = new cls_conexion;
		}// en dif
		$cn = &$this->conexion;
		if($this->query != ""){
			$cn->query = $this->query;
			$result = $cn->ejecutar();
			$cn->descrip_campos($result);
			$this->elem = &$cn->campo;
		}// end if
		//===========================================================
		$this->nro_campos = $cn->nro_campos;
		$this->taux = $cn->taux;
		//===========================================================
		$cn->query = "	SELECT reporte, case tabla when '' then '$this->taux' else tabla end as tabla,
						campo, titulo, clase, parametros, totalizar, formato, html,
						estilo, propiedades, estilo_titulo, propiedades_titulo, estilo_det, propiedades_det, 
						estilo_pie_corte, propiedades_pie_corte, estilo_pie_inf, propiedades_pie_inf 							 
						FROM $this->cfg_campos_rep 
						WHERE reporte = '".$this->reporte."' ";
		if(is_array($cn->tablas)){
			$aux = "";
			foreach($cn->tablas as $tabla_x => $v){
				$aux .= (($aux!="")?",":"")."'".$tabla_x."'";
			}// next
			$query_x = "tabla IN (".$aux.")";
			$aux = "";
			if ($this->reporte!=""){
				$aux = "AND (reporte IS NULL OR reporte='')";
			}// end if
			$cn->query .= " OR ($query_x $aux) ORDER BY reporte, campo";
		}// next
		$result2 = $cn->ejecutar();
		while ($rs = $cn->consultar($result2)){
			$campo = $rs["campo"];
			$tabla = $rs["tabla"];
			//===========================================================
			$this->elem[$tabla][$campo]->reporte = $rs["reporte"]; 
			$this->elem[$tabla][$campo]->tabla = $rs["tabla"]; 
			$this->elem[$tabla][$campo]->campo = $rs["campo"];
			$this->elem[$tabla][$campo]->titulo = $rs["titulo"]; 
			if($rs["clase"]!=""){
				$this->elem[$tabla][$campo]->clase = $rs["clase"]; 
			}else{
				$this->elem[$tabla][$campo]->clase = $this->clase; 
			}// end if
			$this->elem[$tabla][$campo]->parametros = $rs["parametros"];
			$this->elem[$tabla][$campo]->totalizar = $rs["totalizar"]; 
			$this->elem[$tabla][$campo]->formato = $rs["formato"]; 
			$this->elem[$tabla][$campo]->html = $rs["html"];	

			$this->elem[$tabla][$campo]->estilo = $rs["estilo"]; 
			$this->elem[$tabla][$campo]->propiedades = $rs["propiedades"];

			$this->elem[$tabla][$campo]->estilo_titulo = $rs["estilo_titulo"]; 
			$this->elem[$tabla][$campo]->propiedades_titulo = $rs["propiedades_titulo"];

			$this->elem[$tabla][$campo]->estilo_det = $rs["estilo_det"]; 
			$this->elem[$tabla][$campo]->propiedades_det = $rs["propiedades_det"];

			$this->elem[$tabla][$campo]->estilo_pie_corte = $rs["estilo_pie_corte"]; 
			$this->elem[$tabla][$campo]->propiedades_pie_corte = $rs["propiedades_pie_corte"];

			$this->elem[$tabla][$campo]->estilo_pie_inf = $rs["estilo_pie_inf"]; 
			$this->elem[$tabla][$campo]->propiedades_pie_inf = $rs["propiedades_pie_inf"];
			//===========================================================
			$this->vpara = &$rs;
			//===========================================================
			$this->elem[$tabla][$campo]->parametros = $this->evaluar_todo($this->elem[$tabla][$campo]->parametros);
			if($prop = extraer_para($this->elem[$tabla][$campo]->parametros)){
				foreach($prop as $para => $valor){
					eval("\$this->elem[\$tabla][\$campo]->$para=\"$valor\";");
				}// next
			}// end if
			//===========================================================
			if($this->elem[$tabla][$campo]->clase!=""){
				if($this->elem[$tabla][$campo]->clase_titulo==""){
					$this->elem[$tabla][$campo]->clase_titulo = $this->elem[$tabla][$campo]->clase."_rpt_titulo";
				}// end if
				if($this->elem[$tabla][$campo]->clase_pagina==""){
					$this->elem[$tabla][$campo]->clase_pagina = $this->elem[$tabla][$campo]->clase."_rpt_pagina";
				}// end if
				if($this->elem[$tabla][$campo]->clase_corte==""){
					$this->elem[$tabla][$campo]->clase_corte = $this->elem[$tabla][$campo]->clase."_rpt_corte";
				}// end if
				if($this->elem[$tabla][$campo]->clase_detalle==""){
					$this->elem[$tabla][$campo]->clase_detalle = $this->elem[$tabla][$campo]->clase."_rpt_detalle";
				}// end if
				if($this->elem[$tabla][$campo]->clase_pie_corte==""){
					$this->elem[$tabla][$campo]->clase_pie_corte = $this->elem[$tabla][$campo]->clase."_rpt_pie_corte";
				}// end if
				if($this->elem[$tabla][$campo]->clase_pie_pagina==""){
					$this->elem[$tabla][$campo]->clase_pie_pagina = $this->elem[$tabla][$campo]->clase."_rpt_pie_pagina";
				}// end if
				if($this->elem[$tabla][$campo]->clase_pie_inf==""){
					$this->elem[$tabla][$campo]->clase_pie_inf = $this->elem[$tabla][$campo]->clase."_rpt_pie_inf";
				}// end if
			}// end if
			//===========================================================
			$this->elem[$tabla][$campo]->titulo = ($this->elem[$tabla][$campo]->titulo!="")?$this->elem[$tabla][$campo]->titulo:"&nbsp";
			$this->elem[$tabla][$campo]->formato = $this->evaluar_todo($this->elem[$tabla][$campo]->formato);

			$this->elem[$tabla][$campo]->estilo = $this->evaluar_todo($this->elem[$tabla][$campo]->estilo);
			$this->elem[$tabla][$campo]->propiedades = $this->evaluar_todo($this->elem[$tabla][$campo]->propiedades);

			$this->elem[$tabla][$campo]->estilo_titulo = $this->elem[$tabla][$campo]->estilo.$this->evaluar_todo($this->elem[$tabla][$campo]->estilo_titulo);
			$this->elem[$tabla][$campo]->propiedades_titulo = $this->elem[$tabla][$campo]->propiedades.$this->evaluar_todo($this->elem[$tabla][$campo]->propiedades_titulo);

			$this->elem[$tabla][$campo]->propiedades_det = $this->evaluar_var($this->elem[$tabla][$campo]->propiedades_det);
			$this->elem[$tabla][$campo]->estilo_det = $this->evaluar_var($this->elem[$tabla][$campo]->estilo_det);

			$this->elem[$tabla][$campo]->estilo_pie_corte = $this->evaluar_var($this->elem[$tabla][$campo]->estilo_pie_corte);
			$this->elem[$tabla][$campo]->propiedades_pie_corte = $this->evaluar_var($this->elem[$tabla][$campo]->propiedades_pie_corte);

			$this->elem[$tabla][$campo]->estilo_pie_inf = $this->evaluar_var($this->elem[$tabla][$campo]->estilo_pie_inf);
			$this->elem[$tabla][$campo]->propiedades_pie_inf = $this->evaluar_var($this->elem[$tabla][$campo]->propiedades_pie_inf);

			$this->elem[$tabla][$campo]->configurado = true;
			$this->campo[$campo] = &$this->elem[$tabla][$campo];
		}// end while
		return true;
	}// end fucntion
	//===========================================================
	function evaluar_todo($q="",$con_comillas=false){
		if($q==""){
			return "";
		}// end if
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
		$q = leer_var($q,$this->vpara,C_IDENT_VAR_PARA,$con_comillas);		
		$q = leer_var($q,$this->vform,C_IDENT_VAR_FORM,$con_comillas);
		$q = leer_var($q,$this->vses,C_IDENT_VAR_SES,$con_comillas);
		return $q.C_SEP_Q;
	}// end function
	//===========================================================
}// end class
?>