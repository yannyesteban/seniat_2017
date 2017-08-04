<?php 
//====================== Tablas de Configuracion del Sigefor

define ("C_CFG_ACCIONES","cfg_acciones");
define ("C_CFG_CAMPOS","cfg_campos");
define ("C_CFG_USR_ACC","cfg_usr_acc");
define ("C_CFG_USR_MEN","cfg_usr_men");
define ("C_CFG_USR_NAV","cfg_usr_nav");
define ("C_CFG_EST_MEN","cfg_est_men");
define ("C_CFG_ESTRUCTURAS","cfg_estructuras");
define ("C_CFG_FORMAS","cfg_formas");
define ("C_CFG_FORMULARIOS","cfg_formularios");
define ("C_CFG_GPO_USR","cfg_gpo_usr");
define ("C_CFG_GPO_EST","cfg_gpo_est");
define ("C_CFG_GPO_NAV","cfg_gpo_nav");
define ("C_CFG_GRUPOS","cfg_grupos");
define ("C_CFG_MEN_ACC","cfg_men_acc");
define ("C_CFG_MENUES","cfg_menues");
define ("C_CFG_NAV_BOT","cfg_nav_bot");
define ("C_CFG_NAVEGADORES","cfg_navegadores");
define ("C_CFG_USUARIOS","cfg_usuarios");
define ("C_CFG_CONSULTAS","cfg_consultas");
define ("C_CFG_CONS_CAMPOS","cfg_campos_cons");
define ("C_CFG_COMANDOS","cfg_comandos");
define ("C_CFG_PROCEDIMIENTOS","cfg_procedimientos");
define ("C_CFG_MODULOS","cfg_modulos");
define ("C_CFG_TEMAS","cfg_temas");

define ("C_CFG_PLANTILLAS","cfg_plantillas");
define ("C_CFG_PLA_PAN","cfg_pla_pan");
define ("C_CFG_ARTICULOS","cfg_articulos");

define ("C_CFG_PAGINAS","cfg_paginas");
define ("C_CFG_PAG_ART","cfg_pag_art");
define ("C_CFG_CATALOGOS","cfg_catalogos");

define ("C_CFG_REPORTES","cfg_reportes");
define ("C_CFG_CAMPOS_REP","cfg_campos_rep");




//============== Constantes para ****[cls_estructura.php]*** =============





define ("V_OBJETO","cfg_objeto_aux");
define ("V_PARAMETRO","cfg_parametro_aux");
define ("V_ACCION","cfg_accion_aux");
define ("V_MODO","cfg_modo_aux");
define ("V_REG","cfg_reg_aux");
define ("V_PAGINA","cfg_pagina_aux");
define ("V_PAG_FORM","cfg_pag_form_aux");
define ("V_VISTA","cfg_vista_aux");
define ("V_FORM","cfg_form_aux");
define ("V_EST","cfg_est_aux");
define ("V_CMD","cfg_prop_aux");
define ("V_PROC1","cfg_prmto1_aux");
define ("V_PROC2","cfg_prmto2_aux");
define ("V_SW","cfg_sw_aux");
define ("V_MES","cfg_mes_aux");
define ("V_PAG_FORM","cfg_pag_form_aux");
define ("V_VSESION","_FoRm_");

define ("C_VSF","VSF_SG_AUX");
define ("CFG_MENU_SEPARADOR","|");

define ("C_DEST_NO_APLICA","0");
define ("C_DEST_FORMULARIO","1");
define ("C_DEST_CONSULTA","2");
define ("C_DEST_BUSQUEDA","3");
define ("C_DEST_REPORTE","4");
define ("C_DEST_ARTICULO","5");
define ("C_DEST_PAGINA","6");
define ("C_DEST_ENLACE","7");
define ("C_DEST_IFRAME","8");
define ("C_DEST_SMENU","9");
define ("C_DEST_CMENU","10");
define ("C_DEST_VISTA","11");
define ("C_DEST_CATALOGO","14");
define ("C_DEST_COMANDO","20");
define ("C_DEST_REFERENCIA","30");

define ("C_DEST_NINGUNO","100");


define ("C_DEST_FORMA","100");
define ("C_DEST_DEFAULT","101");
define ("C_DEST_VACIO","102");

define ("C_ITEM_SUBMIT","1");
define ("C_ITEM_BUTTON","2");
define ("C_ITEM_RESET","3");




//============== Constantes para ****[cls_navegador.php]*** =============
define ("C_NAV_WIDTH","100%");
define ("C_NAV_ALIGN","right");
define ("C_NAV_ESTILO","");

//============== Constantes para ****[cls_formulario.php]*** =============
define ("C_ANCHO_FORM","100%");
define ("C_ANCHO_TITULOS","30%");
define ("C_ANCHO_DATOS","70%");
define ("C_TITULO_FORM","Formulario");
define ("C_BORDER_FORM","0");
define ("C_CELLSPACING_FORM","2");
define ("C_CELLPADDING_FORM","2");
define ("C_TABLA_AUX","_ttt_");
define ("C_MODO_INSERT","1");
define ("C_MODO_UPDATE","2");
define ("C_MODO_DELETE","3");
define ("C_MODO_CONSULTA","4");
define ("C_MODO_BUSQUEDA","5");
define ("C_ACCION_NINGUNA","0");
define ("C_ACCION_GUARDAR","1");
define ("C_ACCION_SESSION","2");
define ("C_ACCION_VALIDAR","4");
define ("C_ACCION_SALIR","5");
define ("C_ACCION_EN_SESION","6");
define ("C_ACCION_GRID_AGREGAR","7");
define ("C_ACCION_GUARDAR_AGREGAR","8");

define ("C_MODO_TOTAL","0");
define ("C_MODO_SELECTIVO","1");

define ("C_NAV_INSERT","sg_std_insert");
define ("C_NAV_UPDATE","sg_std_update");
define ("C_NAV_CONSULTA","sg_std_consulta");

define ("C_NAV_SEP_NINGUNO","0");
define ("C_NAV_SEP_LINEA","1");
define ("C_NAV_SEP_DBLLINEA","2");
define ("C_NAV_SEP_REGLA","3");
define ("C_NAV_SEP_ESPACIO","4");
define ("C_NAV_SEP_DBLESPACIO","5");
define ("C_NAV_SEP_TRIESPACIO","6");




//============== Constantes para ****[cfg_campos.php]*** =============
define("C_NORMAL","0");
define("C_TEXTO","1");
define("C_LISTA","2");
define("C_MULTIPLE","3");
define("C_FECHA","4");
define("C_HORA","5");
define("C_ESPECIAL","6");
define("C_NINGUNO","7");

define("C_TIPO_NORMAL","0");
define("C_TIPO_TEXTO","1");
define("C_TIPO_CLAVE","2");
define("C_TIPO_OCULTO","3");
define("C_TIPO_AREA","4");
define("C_TIPO_ETIQUETA","5");
define("C_TIPO_COMBOS","6");
define("C_TIPO_OPCIONES","7");
define("C_TIPO_MCOMBOS","8");
define("C_TIPO_SET","9");
define("C_TIPO_CALENDARIO","10");
define("C_TIPO_FECHA_TEXTO","11");
define("C_TIPO_FECHA_COMBO","12");
define("C_TIPO_HORA_TEXTO","13");
define("C_TIPO_ARCHIVO","14");
define("C_TIPO_IMAGEN","15");
define("C_TIPO_LISTA_SET","16");
define("C_TIPO_GRID","17");
define("C_TIPO_CESTA","18");
define("C_TIPO_NINGUNO","19");









define("C_TIPO_DEFAULT","0");
define("C_TIPO_EXPRESION","1");
define("C_TIPO_CALCULO","2");
define("C_TIPO_VISTA","1");
define("C_TIPO_CONSULTA","2");

//============== Constantes para ****[cfg_campos.php]*** =============

define("C_CLAVE_NORMAL","0");
define("C_CLAVE_SERIAL","1");

//============== Constantes para ****[cfg_formularios.php]*** =============
//define ("C_TABLA_CFG","cfg_formularios");
//============== Constantes para ****[cls_controles.php]*** =============

define("C_CTRL_TEXT","text");
define("C_CTRL_PASSWORD","password");
define("C_CTRL_HIDDEN","hidden");
define("C_CTRL_TEXTAREA","textarea");
define("C_CTRL_SELECT","select");
define("C_CTRL_MULTIPLE","multiple");
define("C_CTRL_RADIO","radio");
define("C_CTRL_CHECKBOX","checkbox");
define("C_CTRL_SET","set");
define("C_CTRL_LABEL","label");
define("C_CTRL_FILE","file");

define("C_CTRL_CESTA","cesta");
define("C_CTRL_GRID","grid");
define("C_CTRL_SET2","set2");
define("C_CTRL_DATE_TEXT","date_text");

define("C_AREA_COLS",30);
define("C_AREA_ROWS",6);
define("C_RADIO_COLS",3);
define("C_CHECK_COLS",2);
define("C_MAXSIZE",40);

//============== Constantes para ****[cls_controles.php]*** =============
define ("C_GRID_AGREGAR","Agregar");
define ("C_GRID_SUBIR","Subir");
define ("C_GRID_BAJAR","Bajar");
define ("C_GRID_ELIMINAR","Eliminar");
define ("C_GRID_EDITAR","Editar");

//============== Basicas
define ("C_SI",true);
define ("C_NO",false);

//============== Transacciones
define ("C_COMMIT",1);
define ("C_ROLLBACK",2);
define ("C_IGNORAR_TRANS",0);

//============== Tipos Metas:
define("C_TIPO_I","I");
define("C_TIPO_C","C");
define("C_TIPO_X","X");
define("C_TIPO_N","N");
define("C_TIPO_D","D");
define("C_TIPO_T","T");

//============== Parametros iniciales para la consulta
define("C_CFG_CAMPOS_VIST_NO","0");
define("C_CFG_CAMPOS_VIST_SI","1");
define("C_CFG_CAMPOS_VIST_FORM","2");

define("C_REG_INI",0);
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
define ("C_PIE_PAGINA","<hr>Página <b>@CONS_PAGINA</b> de <b>@CONS_NRO_PAG</b>. Total de Registros: <b>@CONS_REGISTROS</b>");
define ("C_PIE_PAGINA2","<hr>Ir a: ");
define ("C_CTL_PAGINA","cfg_pagina_aux");
define ("C_CTL_PANEL","cfg_panel_aux");
define ("C_CTL_FORM","document.forms[0]");
define ("C_CTL_Q","q");
define ("C_CTL_QEX","qex2");
define ("C_CTL_MALLA","_GRID_Id_auX");

define ("C_CTL_BUSCAR","ctl_buscar");
define ("C_CTL_TIT_BUSCAR","Buscar");



define("C_SEP_L",",");
define("C_SEP_Q",";");
define("C_SEP_V",":");
define("C_SEP_E","=");
define("C_SEP_P","\|");
define("C_SEP_PP","~");
define("C_VAR_QUERY","q");

define("C_SEP_GRUPOS",";");
define("C_SEP_TITULOS",":");
define("C_SEP_REGISTROS","||");
define("C_SEP_CAMPOS","|");

define ("C_SEP_DECIMAL",",");
define ("C_SEP_MIL",".");
define("C_REP_DECIMALES",4);

define("C_IDENT_VAR_FORM","#");
define("C_IDENT_VAR_SES","@");
define("C_IDENT_VAR_REG","&");
define("C_IDENT_VAR_PARA","&PR_");
define("C_IDENT_VAR_EXP","&EX_");
//====================================================================
define("C_PROP_DESHABILITADO","deshabilitado");
define("C_PROP_SOLO_LECTURA","solo_lectura");
define("C_PROP_NO_EDITABLE","no_editable");
define("C_PROP_SI","si");
define("C_PROP_NO","no");

//====================================================================
define ("C_ERROR_UPLOAD","Error no se pudo guardar el archivo que se intento adjuntar");
define ("C_DIR_UPLOAD","uploads");
define ("C_ERROR_METODO_INVALIDO","No está autorizado a entrar al sistema");
?>