<?php 

define("C_PATH_CLASES","clases/");

// ==================== cfg_actualizar ====================

// ==================== cfg_autentificacion ====================
define ("C_ERR_CLAVE_USUARIO","El Usuario o la Clave que introdujo es errónea");
define ("C_ERR_CLAVE","La Clave que introdujo es errónea");
define ("C_ERR_USUARIO","El Usuario que introdujo no esta registrado en el Sistema");
define ("C_ERR_USR_NO_GRUPO","El Usuario no pertenece a un Grupo autorizado");
define ("C_ERR_CLV_VENCIDA","La Clave no está Vigente");
define ("C_ERR_NO_SISTEMA","No esta autorizado para acceder a esta parte del Sistema");
define ("C_ERR_STATUS","Usted fue desincorporado del Sistema");

define ("C_AUT_EXPLICITA",1);
define ("C_AUT_MD5",0);
define ("C_AUT_MAX_ERROR","3");

// ==================== cfg_campos ====================

// ==================== cfg_consulta ====================
define("C_CON_CLASE_PATRON","");
define("C_CON_REG_PAGINA","10");
define("C_CON_PAG_BLOQUE","6");
define("C_CON_PAGINACION","si");

define("C_NAV_STD_INSERT","sg_insertar");
define("C_NAV_STD_UPDATE","sg_actualizar");
define("C_NAV_STD_CONSULTA","sg_consultar");
define("C_NAV_STD_PETICION","sg_std_peticion");

define("C_FORM_Q_NOMBRE","q");
define("C_FORM_Q_WIDTH","100%");
define("C_FORM_Q_TITULO","Buscar");
define("C_FORM_Q_EX_NOMBRE","q_ex");
define("C_FORM_Q_EX_TITULO","Exacto");
define("C_FORM_Q_BOTON_TITULO","Buscar");

define("C_CON_FORM_Q","<table width='&EX_FORM_Q_WIDTH' class='&EX_FORM_Q_CLASE' cellspacing='2' cellpadding='2' border='2'>
						<tr>
							<td class='&EX_FORM_Q_CLASE'><input name='&EX_FORM_Q_NOMBRE' type='text' value='&EX_FORM_Q_VALOR' size='30'>
							  <input class='&EX_FORM_Q_BOTON_CLASE' type='submit' value='&EX_FORM_Q_BOTON_TITULO' onclick=\"&EX_FORM_Q_BOTON_CLICK\">
						      <input class='&EX_FORM_Q_EX_CLASE' name='&EX_FORM_Q_EX_NOMBRE' type='checkbox' value='1' &EX_FORM_Q_EX_CHECKED>						      &EX_FORM_Q_EX_TITULO</td>
					    </tr>
						</table>");
// ==================== cls_control ====================
define("C_AREA_COLS",30);
define("C_AREA_ROWS",6);
define ("C_AREA_EXPANDIR","[»]");
define ("C_AREA_CONTRAER","[«]");

define("C_MAXSIZE",40);
define("C_LISTA_WIDTH","100%");
define("C_LISTA_BORDER","2");
define("C_LISTA_CELLSPACING","2");
define("C_LISTA_CELLPADDING","2");

define("C_LISTA_COLS","3");
define("C_CESTA_FILA","6");

// ==================== cls_menu ====================
define("C_MENU_IMG_IND",C_PATH_IMAGENES."menu_abajo_aqua.png");

// ==================== cls_paginador ====================
define("C_PAG_IMG_PAG_INI",C_PATH_IMAGENES."pag_ini_b.png");
define("C_PAG_IMG_PAG_FIN",C_PATH_IMAGENES."pag_fin_b.png");
define("C_PAG_IMG_PAG_ANT",C_PATH_IMAGENES."pag_ant_b.png");
define("C_PAG_IMG_PAG_SIG",C_PATH_IMAGENES."pag_sig_b.png");
define("C_PAG_TXT_PAG_INI","<<");
define("C_PAG_TXT_PAG_FIN",">>");
define("C_PAG_TXT_PAG_ANT","<");
define("C_PAG_TXT_PAG_SIG",">");
define("C_PAG_IR_A","ir a: ");

//============== Constantes para ****[cls_navegador.php]*** =============
define ("C_NAV_WIDTH","100%");
define ("C_NAV_ALIGN","right");
define ("C_NAV_ESTILO","");

//============== Constantes para ****[cls_formulario.php]*** =============
define ("C_ANCHO_FORM","100%");
define ("C_ANCHO_TITULOS","35%");
define ("C_ANCHO_DATOS","65%");
define ("C_TITULO_FORM","Formulario");
define ("C_BORDER_FORM","0");
define ("C_CELLSPACING_FORM","2");
define ("C_CELLPADDING_FORM","2");

define ("C_MODO_TOTAL","0");
define ("C_MODO_SELECTIVO","1");

define ("C_NAV_INSERT","sg_std_insert");
define ("C_NAV_UPDATE","sg_std_update");
define ("C_NAV_CONSULTA","sg_std_consulta");

//============== Constantes para ****[cls_controles.php]*** =============
define ("C_GRID_AGREGAR","Agregar");
define ("C_GRID_SUBIR","Subir");
define ("C_GRID_BAJAR","Bajar");
define ("C_GRID_ELIMINAR","Eliminar");
define ("C_GRID_EDITAR","Editar");


define ("C_REG_INI",0);
define ("C_REG_PAG","10");
define ("C_PAG_BLOQUE","6");



define ("C_PAG_INI"," [<<] ");
define ("C_PAG_FIN"," [>>] ");
define ("C_PAG_SIG"," [>] ");
define ("C_PAG_ANT"," [<] ");

define ("C_NAV_NINGUNO","0");
define ("C_NAV_GRAFICO","1");
define ("C_NAV_TEXTO","2");
define ("C_NAV_BOTONES","3");

define ("C_PAG_INI_IMG","imagenes/inicio2.gif");
define ("C_PAG_FIN_IMG","imagenes/fin2.gif");
define ("C_PAG_SIG_IMG","imagenes/siguiente2.gif");
define ("C_PAG_ANT_IMG","imagenes/anterior2.gif");

define ("C_PAG_INI_CLS","nav_ini");
define ("C_PAG_FIN_CLS","nav_fin");
define ("C_PAG_SIG_CLS","nav_sig");
define ("C_PAG_ANT_CLS","nav_ant");

define ("C_CONS_BUSQUEDA","Buscar por ");
define ("C_BUSQ_EXACTA","Busqueda Exacta ");

define ("C_CONS_CAR_PASSWORD","*****");

define ("C_PIE_PAGINA","Son: &EX_REG_ACTUALES Registros de: &EX_REG_TOTAL, Página: &EX_PAG_ACTUAL de &EX_NRO_PAGINAS");
define ("C_PIE_PAGINA2","<hr>Ir a: ");
define ("C_CTL_PAGINA","cfg_pagina_aux");
define ("C_CTL_PANEL","cfg_panel_aux");
define ("C_CTL_FORM","document.forms[0]");
define ("C_CTL_Q","q");
define ("C_CTL_QEX","qex2");
define ("C_CTL_MALLA","_GRID_Id_auX");

define ("C_CTL_BUSCAR","ctl_buscar");
define ("C_CTL_TIT_BUSCAR","Buscar");

define("C_VAR_QUERY","q");

define("C_SEP_GRUPOS",";");
define("C_SEP_TITULOS",":");
define("C_SEP_REGISTROS","||");
define("C_SEP_CAMPOS","|");

define ("C_SEP_DECIMAL",",");
define ("C_SEP_MIL",".");
define ("C_REP_DECIMALES",4);

//====================================================================
define ("C_ERROR_UPLOAD","Error no se pudo guardar el archivo que se intento adjuntar");
define ("C_DIR_UPLOAD","uploads");
define ("C_ERROR_METODO_INVALIDO","No está autorizado a entrar al sistema");
define ("C_ERROR_VALID_REG","Debe elegir por lo menos un registro");

define("C_SF_WIDTH","400px");
?>