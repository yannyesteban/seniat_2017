<?php 
/*****************************************************************
creado: 01/07/2007
modificado: 11/07/2007
por: Yanny Nuñez
*****************************************************************/
require ("cfg_reporte.php");
require ("cfg_campos_rep.php");
class cls_reporte extends cfg_reporte{
	var $nro_niveles = 0;
	var $nro_campos;
	var $nro_filas;
	var $no_total = array();
	
	var $texp = array();
	var $tcampo = array();
	//===========================================================
	function control($reporte_x){
		if($reporte_x!=""){
			$this->reporte = $reporte_x;
		}// end if
		if($this->ejecutar($this->reporte)){
		}// end if;
		$cn = &$this->conexion;

		$this->result = $cn->ejecutar($this->query);
		$this->nro_campos = $cn->nro_campos;
		$this->nro_filas = $cn->nro_filas;
		$cn->descrip_campos($this->result);

		$this->cfg = new cfg_campos_rep;
		$this->cfg->conexion = &$this->conexion;
		$this->cfg->elem = &$cn->campo;
		$this->cfg->vses = &$this->vses;
		$this->cfg->vform = &$this->vform;
		
		$this->cfg->ejecutar($this->reporte);
		$this->elem = &$cn->campo;
		$this->campo = &$this->cfg->campo;

		switch ($this->tipo){
		case C_REPORTE_PATRON:
			$ele_form = $this->form_patron();
			break;
		case C_REPORTE_DISENO:
			$ele_form = $this->form_diseno();
			break;
		case C_REPORTE_ARCHIVO:
			$ele_form = $this->form_archivo();
			break;
		case C_REPORTE_NORMAL:
		case C_REPORTE_RAPIDO:	
		default:
			$ele_form = $this->rep_rapido();
			break;		
		}// end switch
		return $ele_form;
	}// end function
	//===========================================================
	function rep_rapido(){
		$this->cuerpo_informe = extraer_patron($this->diagrama,"cuerpo_informe");
		
		$this->enc_pag = extraer_patron($this->diagrama,"enc_pag");
		$this->cel_enc_pag = extraer_patron($this->diagrama,"cel_enc_pag");

		$this->enc_cte = extraer_patron($this->diagrama,"enc_cte");
		$this->cel_enc_cte = extraer_patron($this->diagrama,"cel_enc_cte");

		$this->enc_det = extraer_patron($this->diagrama,"enc_det");
		$this->cel_enc_det = extraer_patron($this->diagrama,"cel_enc_det");

		$this->detalle = extraer_patron($this->diagrama,"detalle");
		$this->cel_detalle = extraer_patron($this->diagrama,"cel_detalle");

		$this->pie_cte = extraer_patron($this->diagrama,"pie_cte");
		$this->cel_pie_cte = extraer_patron($this->diagrama,"cel_pie_cte");

		$this->pie_pag = extraer_patron($this->diagrama,"pie_pag");
		$this->cel_pie_pag = extraer_patron($this->diagrama,"cel_pie_pag");

		$this->pie_inf = extraer_patron($this->diagrama,"pie_inf");
		$this->cel_pie_inf = extraer_patron($this->diagrama,"cel_pie_inf");
		//===========================================================
		$cn = &$this->conexion;
		$con_clase_pie_corte = false;
		$con_clase_corte = false;
		//===========================================================
		if($this->cortes != ""){
			$this->nro_niveles = count($this->corte);
			for($n=0;$n<$this->nro_niveles;$n++){
				$this->ele_corte[$n] = 0;
				$this->corte[$this->corte[$n]] = "1";
				eval("\$clase_corte = \$this->clase_corte_".$n.";");
				if ($clase_corte != ""){
					$con_clase_corte = true;
				}// end if
				eval("\$clase_pie_corte = \$this->clase_pie_corte_".$n.";");
				if ($clase_pie_corte != ""){
					$con_clase_pie_corte = true;
				}// end if
			}// next
		}// end if		
		//===========================================================
		if($this->totalizar_exp!=""){
			$this->texp = extraer_variables($this->totalizar_exp);
			$this->nro_texp = count($this->texp);
			for($n=0;$n<=$this->nro_niveles;$n++){
				foreach($this->texp as $e => $v){
					$this->total[$e][$n]=$v;
					$this->emin[$e][$n]=false;
					$this->emax[$e][$n]=false;
				}// next
			}// next
		}// end if
		$cel_enc_pag = "";
		$cel_enc_det = "";
		$cel_detalle = "";
		//===========================================================
		if($this->totales!=""){
			$this->tcampo = extraer_bandera($this->totales);
			$this->nro_tcampo = count($this->tcampo);
			for($n=0;$n<=$this->nro_niveles;$n++){
				foreach($this->tcampo as $e => $v){
					$this->sumar[$e][$n]=0;
					$this->min[$e][$n]=false;
					$this->max[$e][$n]=false;
					$this->es_sumar[$e]=1;
				}// next
			}// next
			$cel_pie_cte = "";
			$cel_pie_pag = "";
			$cel_pie_inf = "";
		}// end if
		//===========================================================
		if($this->no_totalizar!=""){
			$this->no_total = extraer_bandera($this->no_totalizar);
		}// end if
		//===========================================================
		if($this->clase_titulo!=""){
			$clase_titulo_f = "class:".$this->clase_titulo.";";
		}else{
			$clase_titulo_f = "";
		}// end if
		if($this->clase_detalle!=""){
			$clase_detalle_f = "class:".$this->clase_detalle.";";
		}else{
			$clase_detalle_f = "";
		}// end if
		//===========================================================
		$eval_fila = false;
		$eval_pie_cte = false;
		for($i=0;$i<$this->nro_campos;$i++){
			if($this->elem[$i]->oculto=="si"){
				continue;
			}// end if
			//===========================================================
			if($this->elem[$i]->clase_titulo != ""){
				$clase_titulo = "class:".$this->elem[$i]->clase_titulo.";";
			}else{
				$clase_titulo = "";
			}// end if
			$cel_enc_pag_x = $this->cel_enc_pag;
			$propiedades_x = $clase_titulo_f.$this->propiedades_titulo.$clase_titulo.$this->elem[$i]->propiedades_titulo;
			$estilo_x = $this->estilo_titulo.$this->elem[$i]->estilo_titulo;
			$propiedades_x = $this->evaluar_prop($propiedades_x,$estilo_x);
			$cel_enc_pag_x = str_replace("--propiedades--",$propiedades_x,$cel_enc_pag_x);
			$cel_enc_pag .= "\n\t".str_replace("--campo--",$this->elem[$i]->titulo,$cel_enc_pag_x);
			//===========================================================
			$eval_celda[$i] = false;
			if($this->elem[$i]->propiedades_det!="" or $this->elem[$i]->estilo_det!="" or $this->elem[$i]->clase_detalle!="" 
				or $this->elem[$i]->propiedades!="" or $this->elem[$i]->estilo!=""){
				$cel_detalle_x = str_replace("--propiedades--","--propiedades_".$this->elem[$i]->nombre."--",$this->cel_detalle);
				$eval_celda[$i] = true;
			}else if($this->propiedades_det!="" or $this->estilo_det!=""){
				$cel_detalle_x = $this->cel_detalle;
				$eval_fila = true;
			}else{
				$propiedades_x = $clase_detalle_f.$this->propiedades;
				$estilo_x = $this->estilo;
				$propiedades_x = $this->evaluar_prop($propiedades_x,$estilo_x);
				$cel_detalle_x = str_replace("--propiedades--",$propiedades_x,$this->cel_detalle);
			}// end if
			$cel_detalle .= "\n\t".str_replace("--campo--","{=".$this->elem[$i]->nombre."}",$cel_detalle_x);
			//===========================================================
			$cel_enc_det .= "\n\t".str_replace("--campo--",$this->elem[$i]->titulo,$this->cel_enc_det);
			if($this->totales!=""){
				if($this->es_sumar[$this->elem[$i]->nombre]>0){
					$valor_x = "{=".$this->elem[$i]->nombre."}";
				}else{
					$valor_x = "&nbsp;";
				}// end if
				//===========================================================
				$eval_celda_cte[$i] = false;
				if($this->elem[$i]->propiedades_pie_corte!="" or $this->elem[$i]->estilo_pie_corte!="" or $this->elem[$i]->clase_pie_corte!="" 
					or $this->elem[$i]->propiedades!="" or $this->elem[$i]->estilo!=""){
					$cel_pie_cte_x = str_replace("--propiedades--","--propiedades_".$this->elem[$i]->nombre."--",$this->cel_pie_cte);
					$eval_celda_cte[$i] = true;
				}else{
					$cel_pie_cte_x = $this->cel_pie_cte;
					$eval_pie_cte = true;
				}// end if
				//===========================================================
				$cel_pie_inf_x = str_replace("--propiedades--","--propiedades_".$this->elem[$i]->nombre."--",$this->cel_pie_inf);
				$cel_pie_cte .= "\n\t".str_replace("--campo--",$valor_x,$cel_pie_cte_x);
				$cel_pie_pag .= "\n\t".str_replace("--campo--",$valor_x,$this->cel_pie_pag);
				$cel_pie_inf .= "\n\t".str_replace("--campo--",$valor_x,$cel_pie_inf_x);			
			}// end if
		}// next
		$this->enc_pag = "\n".formar_diagrama($this->enc_pag,"cel_enc_pag",$cel_enc_pag);
		$enc_det = "\n".formar_diagrama($this->enc_det,"cel_enc_det",$cel_enc_det);
		$detalle = "\n".formar_diagrama($this->detalle,"cel_detalle",$cel_detalle);
		if($this->totales!=""){
			$pie_cte = "\n".formar_diagrama($this->pie_cte,"cel_pie_cte",$cel_pie_cte);
			$pie_pag = "\n".formar_diagrama($this->pie_pag,"cel_pie_pag",$cel_pie_pag);
			$pie_inf = "\n".formar_diagrama($this->pie_inf,"cel_pie_inf",$cel_pie_inf);
		}// end if
		$enc_cte = str_replace("--colspan--",$this->nro_campos,$this->enc_cte);
		//===========================================================
		if(!$this->mostrar_todas){
			if($this->pagina_ini=="" or ($this->pagina_ini!="" and $this->pagina_ini<=0)){
				$this->pagina_ini = 1;
			}// end if
			if($this->pagina_fin=="" or $this->pagina_fin==0 or  $this->pagina_fin<=0){
				$this->pagina_fin = 1000000;
			}// end if
		}// end if		
		//===========================================================	
		$this->vexp["NRO_CORTES"] = $this->nro_niveles;
		$this->vexp["NRO_CAMPOS"] = $this->nro_campos;
		$this->vexp["NRO_REGISTROS"] = $this->nro_filas;
		$this->vexp["PAGINA_INI"] = $this->pagina_ini;
		$this->vexp["TOTAL_PAG_INF"] = "--TOTAL_PAG_INF--";
		$this->linea_actual=0;
		$this->fin_informe = false;
		$valor_ant = array();
		$lineas_enc = array();
		$reg_actual = 0;
		$rs_x = $cn->consultar($this->result);
		//===========================================================
		while ($rs = $rs_x){
			$rs_x = $cn->consultar($this->result);
			$this->vreg = $rs;
			$detalle_x = $detalle;
 			$linea_enc_cte_1 = array();
			$linea_enc_cte = array();
			$linea_pie_cte = array();
			$reg_actual++;
			$this->vexp["REG_ACTUAL"] = $reg_actual;
			//===========================================================
			for($n=0;$n<=$this->nro_niveles;$n++){
				$this->ele_corte[$n]++;
				$this->vexp["TT_NRO_ELE_".$n] = $this->ele_corte[$n];
				foreach($this->texp as $e => $v){
					$valor = $this->vexp[$e];
					if(!$this->emin[$e][$n] or $this->emin[$e][$n]>$valor){
						$this->emin[$e][$n] = $valor;
					}// end if
					if(!$this->emax[$e][$n] or $this->emax[$e][$n]<$valor){
						$this->emax[$e][$n] = $valor;
					}// end if
					$this->total[$e][$n] += $this->vexp[$e];
					$this->vexp["TT_".$e] = $this->total[$e][$n];
					$this->vexp["TT_".$e."_".($n+1)] = $this->total[$e][$n];
					$this->vexp["TT_".$e."_".$this->corte[$n]] = $this->total[$e][$n];
					$this->vexp["MD_".$e] = $this->total[$e][$n]/$this->vexp["TT_NRO_ELE_".$n];
					$this->vexp["MD_".$e."_".($n+1)] = $this->total[$e][$n]/$this->vexp["TT_NRO_ELE_".$n];
					$this->vexp["MD_".$e."_".$this->corte[$n]] = $this->total[$e][$n]/$this->vexp["TT_NRO_ELE_".$n];
					$this->vexp["MN_".$e] = $this->emin[$e][$n];
					$this->vexp["MN_".$e."_".($n+1)] = $this->emin[$e][$n];
					$this->vexp["MN_".$e."_".$this->corte[$n]] = $this->emin[$e][$n];
					$this->vexp["MX_".$e] = $this->emax[$e][$n];
					$this->vexp["MX_".$e."_".($n+1)] = $this->emax[$e][$n];
					$this->vexp["MX_".$e."_".$this->corte[$n]] = $this->emax[$e][$n];
				}// next
				//===========================================================
				foreach($this->tcampo as $e => $v){
					$valor = $rs[$e];
					if($this->campo[$e]->valor!=""){
						$valor = $this->vexp[$this->campo[$e]->valor];
						$rs[$this->campo[$e]->num] = $valor;
					}// end if
					if(!$this->min[$e][$n] or $this->min[$e][$n]>$valor){
						$this->min[$e][$n] = $valor;
					}// end if
					if(!$this->max[$e][$n] or $this->max[$e][$n]<$valor){
						$this->max[$e][$n] = $valor;
					}// end if
					$this->sumar[$e][$n] += $valor;
					$this->vexp["TT_".$e] = $this->sumar[$e][$n];
					$this->vexp["TT_".$e."_".($n+1)] = $this->sumar[$e][$n];
					$this->vexp["TT_".$e."_".$this->corte[$n]] = $this->sumar[$e][$n];
					$this->vexp["MD_".$e] = $this->sumar[$e][$n]/$this->vexp["TT_NRO_ELE_".$n];
					$this->vexp["MD_".$e."_".($n+1)] = $this->sumar[$e][$n]/$this->vexp["TT_NRO_ELE_".$n];
					$this->vexp["MD_".$e."_".$this->corte[$n]] = $this->sumar[$e][$n]/$this->vexp["TT_NRO_ELE_".$n];
					$this->vexp["MN_".$e] = $this->min[$e][$n];
					$this->vexp["MN_".$e."_".($n+1)] = $this->min[$e][$n];
					$this->vexp["MN_".$e."_".$this->corte[$n]] = $this->min[$e][$n];
					$this->vexp["MX_".$e] = $this->max[$e][$n];
					$this->vexp["MX_".$e."_".($n+1)] = $this->max[$e][$n];
					$this->vexp["MX_".$e."_".$this->corte[$n]] = $this->max[$e][$n];
				}// next
			}// next
			//===========================================================
			if($this->expresiones_det!=""){
				$this->vexp = array_merge($this->vexp, extraer_para($this->evaluar_exp($this->expresiones_det)));
			}// end if
			//===========================================================
			if($this->propiedades_det!=""){
				$propiedades_det_x = $clase_detalle_f.$this->propiedades.$this->evaluar_exp($this->propiedades_det);
			}else{
				$propiedades_det_x = $clase_detalle_f.$this->propiedades;
			}// end if
			if($this->estilo_det!=""){
				$estilo_det_x = $this->estilo.$this->evaluar_exp($this->estilo_det);
			}else{
				$estilo_det_x = $this->estilo;
			}// end if
			if ($eval_fila){
				$propiedades_x = $this->evaluar_prop($propiedades_det_x ,$estilo_det_x );
				$detalle_x = str_replace("--propiedades--",$propiedades_x,$detalle_x);
			}// end if
			//===========================================================
			for($j=0;$j<$this->nro_campos;$j++){
				$this->vexp["CAMPO_NRO"]=$j;
				$this->vexp["CAMPO_NOMBRE"] = $this->elem[$j]->nombre;
				$valor = $rs[$j];
				if($this->elem[$j]->valor!=""){
					$valor = $this->vexp[$this->elem[$j]->valor];
				}// end if
				if($this->elem[$j]->omitir_repetidos=="si" and $valor == $valor_ant[$j] and !$reset){
					$valor=$this->elem[$j]->valor_repetidos;
				}else{
					$valor_ant[$j]=$valor;
				}// end if
				$valor = $this->evaluar_campo($valor,$j);
				if($this->elem[$j]->sufijo!=""){
					$valor = $valor.$this->elem[$j]->sufijo;
				}// end if
				if($this->elem[$j]->prefijo!=""){
					$valor = $this->elem[$j]->prefijo.$valor;
				}// end if
				if($this->elem[$j]->clase_detalle!=""){
					$clase_detalle = "class:".$this->elem[$j]->clase_detalle.";";
				}else{
					$clase_detalle = "";
				}// end if
				if($eval_celda[$j]){
					$propiedades_x = $propiedades_det_x.$clase_detalle.$this->elem[$j]->propiedades.$this->evaluar_exp($this->elem[$j]->propiedades_det);
					$estilo_x = $estilo_det_x.$this->elem[$j]->estilo.$this->evaluar_exp($this->elem[$j]->estilo_det);
					$propiedades_x = $this->evaluar_prop($propiedades_x ,$estilo_x);
					$detalle_x = str_replace("--propiedades_".$this->elem[$j]->nombre."--",$propiedades_x,$detalle_x);
				}// end if
				$detalle_x = str_replace("{=".$this->elem[$j]->nombre."}",($valor!=="")?$valor:"&nbsp;",$detalle_x);
			}// next
			//===========================================================
			$linea_detalle = $detalle_x;
			$reset = false;
			$nro_lineas_corte_1 = 0;
			$nro_lineas_corte = 0;
			$nro_lineas_pie_corte = 0;
			for($n=0;$n<$this->nro_niveles;$n++){
				if(($rs[$this->corte[$n]] != $rs_x[$this->corte[$n]]) or $reg_actual==1 or $reset){
					$this->vexp["CORTE_NOMBRE"] = $this->corte[$n];
					$this->vexp["CORTE_NIVEL"] = $n;
					$this->vexp["CORTE_TITULO"] = $this->corte_titulo[$n];
					if($reg_actual==1){
						$this->vexp["CORTE"] = $rs[$this->corte[$n]];
						$linea_enc_cte_1[$nro_lineas_corte_1] = $this->crear_enc_cte($enc_cte,$n);
						$nro_lineas_corte_1++;
					}// end if
					$this->vexp["CORTE"] = $rs_x[$this->corte[$n]];
		 			if($reg_actual <$this->nro_filas and ($rs[$this->corte[$n]] != $rs_x[$this->corte[$n]] or $reset)){
						$reset = true;
						$linea_enc_cte[$nro_lineas_corte] = $this->crear_enc_cte($enc_cte,$n);
						$nro_lineas_corte++;
					}// end if
					//===========================================================
					if($this->totales !=""){
						if(!$this->no_total[$this->corte[$n]] and ($reset or $reg_actual == $this->nro_filas)){
							$pie_cte_y = $pie_cte;
							eval("\$clase_pie_cte_n = \$this->clase_pie_cte_".$n.";");
							if ($clase_pie_cte_n !=""){
								$clase_pie_cte_f = "class:".$clase_pie_cte_n.";";
							}else if($this->clase_pie_corte!=""){
								$clase_pie_cte_f = "class:".$this->clase_pie_corte."_".$n.";";
							}else{
								$clase_pie_cte_f = "";
							}// end if
							if($this->propiedades_pie_corte!=""){
								$propiedades_y = $clase_pie_cte_f.$this->propiedades.$this->evaluar_exp($this->propiedades_pie_corte);
							}else{
								$propiedades_y = $clase_pie_cte_f.$this->propiedades;
							}// end if
							if($this->estilo_pie_corte!=""){
								$estilo_y = $this->estilo.$this->evaluar_exp($this->estilo_pie_corte);
							}else{
								$estilo_y = $this->estilo;
							}// end if
							$propiedades_x = $this->evaluar_prop($propiedades_y,$estilo_y);
							$pie_cte_y = str_replace("--propiedades--",$propiedades_x,$pie_cte_y);
							for($j=0;$j<$this->nro_campos;$j++){		
								if($eval_celda_cte[$j]){
									if($this->elem[$j]->clase_pie_corte!=""){
										$clase_pie_corte_c = "class:".$this->elem[$j]->clase_pie_corte.";";
									}else{
										$clase_pie_corte_c = "";
									}// end if
									$propiedades_x = $propiedades_y.$clase_pie_corte_c.$this->elem[$j]->propiedades.$this->evaluar_exp($this->elem[$j]->propiedades_pie_corte);
									$estilo_x = $estilo_y.$this->elem[$j]->estilo.$this->evaluar_exp($this->elem[$j]->estilo_pie_corte);					
									$propiedades_x = $this->evaluar_prop($propiedades_x,$estilo_x);
									$pie_cte_y = str_replace("--propiedades_".$this->elem[$j]->nombre."--",$propiedades_x,$pie_cte_y);
								}// end if
								if($this->tcampo[$this->elem[$j]->nombre]){
									$valor = $this->vexp["TT_".$this->elem[$j]->nombre."_".($n+1)];
									eval("\$valor_corte=\$this->elem[\$j]->valor_corte_".($n+1).";");
									if($valor_corte!=""){
										$valor = $this->vexp[trim($valor_corte)];
									}else{
										switch($this->elem[$j]->totalizar){
										case 2:
											$valor =  $this->vexp["MD_".$this->elem[$j]->nombre."_".($n+1)];
											break;
										case 3:
											$valor = $this->vexp["TT_NRO_ELE_".$n];
											break;
										case 4:
											$valor = $this->vexp["MN_".$this->elem[$j]->nombre."_".($n+1)];
											break;
										case 5:
											$valor = $this->vexp["MX_".$this->elem[$j]->nombre."_".($n+1)];
											break;
										}// end switch
									}// end if
									$valor = $this->evaluar_campo($valor,$j);
									if($this->elem[$j]->sufijo_corte!=""){
										$valor = $valor.$this->elem[$j]->sufijo_corte;
									}// end if
									if($this->elem[$j]->prefijo_corte!=""){
										$valor = $this->elem[$j]->prefijo_corte.$valor;
									}// end if
									$pie_cte_y = str_replace("{=".$this->elem[$j]->nombre."}",$valor ,$pie_cte_y)	;
								}// end if								
							}// next
							$linea_pie_cte[$nro_lineas_pie_corte] = $pie_cte_y;
							$nro_lineas_pie_corte++;
						}// end if
						if($reset){
							foreach($this->texp as $e => $v){
								$this->total[$e][$n] = $this->texp[$e];
								$this->emin[$e][$n] = false;
								$this->emax[$e][$n] = false;
							}// next
							foreach($this->tcampo as $e => $v){
								$this->sumar[$e][$n] = 0;
								$this->min[$e][$n] = false;
								$this->max[$e][$n] = false;
							}// next
							$this->ele_corte[$n]=0;
						}// end if
					}// end if
				}// end if
			}// next
			if($reg_actual==1){
				for($i=0;$i<count($linea_enc_cte_1);$i++){
					$this->crear_pagina($linea_enc_cte_1[$i]);
				}// next
			}// end if
			$this->crear_pagina($linea_detalle);
			for($i=count($linea_pie_cte)-1;$i>=0;$i--){
				$this->crear_pagina($linea_pie_cte[$i]);
			}// next
			for($i=0;$i<count($linea_enc_cte);$i++){
				$this->crear_pagina($linea_enc_cte[$i]);
			}// next
		}// end while
		
		$nro_filas_blanco = $this->lineas_pag-($this->nro_filas%$this->lineas_pag);
		$r="";
		$detalle_blanco = preg_replace("/{=(\w+)}/","&nbsp;",$detalle);
		for($fb=0;$fb<$nro_filas_blanco;$fb++){
			$r .= $detalle_blanco;
		}// next
			$this->crear_pagina($r,false);
		//===========================================================
		if($this->totales !=""){	
			$pie_inf_x = $pie_inf;
			if($this->clase_pie_inf!=""){
				$clase_pie_inf_f = "class:".$this->clase_pie_inf.";";
			}else{
				$clase_pie_inf_f = "";
			}// end if
			for($j=0;$j<$this->nro_campos;$j++){		
				if($this->elem[$j]->clase_pie_inf!=""){
					$clase_pie_inf_c = "class:".$this->elem[$j]->clase_pie_inf.";";
				}else{
					$clase_pie_inf_c = "";
				}// end if
				$propiedades_x = $clase_pie_inf_f.$this->propiedades.$this->propiedades_pie_inf.
								 $clase_pie_inf_c.$this->elem[$j]->propiedades.$this->evaluar_exp($this->elem[$j]->propiedades_pie_inf);
				$estilo_x = $this->estilo.$this->estilo_pie_inf.$this->elem[$j]->estilo.$this->evaluar_exp($this->elem[$j]->estilo_pie_inf);					
				$propiedades_x = $this->evaluar_prop($propiedades_x,$estilo_x);
				$pie_inf_x = str_replace("--propiedades_".$this->elem[$j]->nombre."--",$propiedades_x,$pie_inf_x);
				if($this->tcampo[$this->elem[$j]->nombre]){
					$valor = $this->vexp["TT_".$this->elem[$j]->nombre];
					if($this->elem[$j]->valor_informe!=""){
						$valor = $this->vexp[trim($this->elem[$j]->valor_informe)];
					}else{
						switch($this->elem[$j]->totalizar){
						case 2:
							$valor =  $this->vexp["MD_".$this->elem[$j]->nombre."_".($n+1)];
							break;
						case 3:
							$valor = $this->vexp["TT_NRO_ELE_".$n];
							break;
						case 4:
							$valor = $this->vexp["MN_".$this->elem[$j]->nombre."_".($n+1)];
							break;
						case 5:
							$valor = $this->vexp["MX_".$this->elem[$j]->nombre."_".($n+1)];
							break;
						}// end switch
					}// end if
					$valor = $this->evaluar_campo($valor,$j);
					if($this->elem[$j]->sufijo_informe!=""){
						$valor = $valor.$this->elem[$j]->sufijo_informe;
					}// end if
					if($this->elem[$j]->prefijo_informe!=""){
						$valor = $this->elem[$j]->prefijo_informe.$valor;
					}// end if
					$pie_inf_x = str_replace("{=".$this->elem[$j]->nombre."}",$valor ,$pie_inf_x)	;
				}// end if
			}// next
			$this->crear_pagina($pie_inf_x);
		}// end if
		//===========================================================
		$this->crear_pagina("",true);
		$this->pag_informe = str_replace("--TOTAL_PAG_INF--",$this->vexp["NRO_PAG_INF"],$this->pag_informe);	
		$this->total_paginas = $this->vexp["NRO_PAG_INF"];
		if($this->pagina_fin > $this->total_paginas){
			$this->pagina_fin = $this->total_paginas;
		}// end if
		return $this->pag_informe;		
	}// end fucntion
	//===========================================================
	function crear_pagina($linea="",$fin_informe=false){
		if($linea!=""){
			$this->linea_actual++;
			$this->vexp["NRO_LINEA_ACTUAL"]=$this->linea_actual;
			$this->lineas .= $linea;
		}// end if
		if(@($this->linea_actual % $this->lineas_pag==0) or $fin_informe){
			$this->nro_pag_inf++;
			$this->vexp["NRO_PAG_INF"]=$this->nro_pag_inf;
			if($this->mostrar_pagina($this->nro_pag_inf)){
				if($this->nro_pag_inf==1 or $this->nro_pag_inf==$this->pagina_ini){
					$diagrama_x = str_replace("--salto_pagina--","",$this->diagrama);
				}else{
					$diagrama_x = str_replace("--salto_pagina--","page-break-before:always",$this->diagrama);
				}// end if
				$this->pag_informe .= inner_html($this->evaluar_exp($diagrama_x),"cuerpo_informe",  $this->enc_pag   .   $this->lineas);
			}// end if
			$this->lineas = "";
		}// end if
	}// end function 
	//===========================================================
	function mostrar_pagina($pagina){
		if($this->mostrar_todas or ($pagina >= $this->pagina_ini and $pagina <= $this->pagina_fin)){	
			return true;
		}// end if
		return false;
	}// end if
	//===========================================================
	function crear_enc_cte($enc_cte,$n){
		$enc_cte_y = $enc_cte;
		$enc_cte_x = "\n".$this->evaluar_exp($enc_cte_y);
		eval("\$clase_corte_n = \$this->clase_corte_".$n.";");
		if ($clase_corte_n !=""){
			$clase_corte = "class:".$clase_corte_n.";";
		}else if($this->clase_corte!=""){
			$clase_corte = "class:".$this->clase_corte."_".$n.";";
		}else{
			$clase_corte = "";
		}// end if
		$propiedades_x = $clase_corte.$this->propiedades.$this->evaluar_exp($this->propiedades_corte);
		$estilo_x = $this->estilo.$this->evaluar_exp($this->estilo_corte);
		$propiedades_x = $this->evaluar_prop($propiedades_x,$estilo_x);
		$enc_cte_x = str_replace("--propiedades--",$propiedades_x,$enc_cte_x);
		return $enc_cte_x;
	}// end function
	//===========================================================
	function evaluar_prop($propiedades,$estilo){
		$propiedades_x = "";	
		if($estilo != ""){
			$propiedades_x .= "style=\"".reparar_sep($estilo)."\" ";
		}// end if
		if($prop = extraer_para($propiedades)){
			foreach($prop as $para => $val){
				if ($val==""){
					continue;
				}// end if
				$propiedades_x .= "$para=\"$val\" ";
			}// next
		}// end if
		return $propiedades_x;
	}// end function
	//===========================================================
	function evaluar_campo($valor,$j){
	
		if($this->elem[$j]->formato!=""){
			$valor = sprintf($this->elem[$j]->formato,$valor);
				hr($valor."...".$this->elem[$j]->formato);
		}else{
			if ($this->elem[$j]->sin_formato!="si"){
				switch ($this->elem[$j]->meta){
				case C_TIPO_D:
					$valor = formato_fecha($valor);
					break;
				case C_TIPO_N:
				case $this->elem[$j]->numerico=="si";
					if(is_numeric($valor)){
						if($this->elem[$j]->decimales!=""){
							$decimales = $this->elem[$j]->decimales;
						}else{
							$decimales = $this->decimales;
						}//end if
						$valor = number_format($valor,$decimales,$this->sep_decimal,$this->sep_mil);
					}// end if
					break;
				case C_TIPO_I:
				case $this->elem[$j]->entero=="si";
					if(is_numeric($valor)){
						if($this->elem[$j]->decimales!=""){
							$decimales = $this->elem[$j]->decimales;
						}else{
							$decimales = 0;
						}//end if
						$valor = number_format($valor,$decimales,$this->sep_decimal,$this->sep_mil);
					}// end if
					break;
				default:
					break;
				}// end switch
			}// end if				
		}// end if				
		if($this->elem[$j]->html=="si"){
			$valor = htmlentities($valor);
		}// end if
		if($this->elem[$j]->ancho!=""){
			$valor = substr($valor,0,$this->elem[$j]->ancho);
		}// end if
		return $valor;
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