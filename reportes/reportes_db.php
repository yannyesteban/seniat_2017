<?php
$tablas = "cfg_reportes,cfg_campos_rep";		
$t = explode (",",$tablas);
require_once ("constantes.php");
require_once ("cls_conexion2.php");
$cn = new cls_conexion;
$cn->query = "SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;";
//echo $cn->query;
$cn->ejecutar();
for ($i=count($t)-1;$i>=0;$i--){
	//echo $t[$i]."<br>";
	if($t[$i]=="")
		continue;
    $cn->query = "DROP TABLE IF EXISTS ".$t[$i];
    $cn->ejecutar();
}// next
foreach ($t as $tabla_x) {
    //$cn->query =  "DROP TABLE IF EXISTS $tabla_x ";
    //$cn->ejecutar();
	switch (trim($tabla_x)){
	//===================================================
	case "cfg_reportes":
		$cn->query = "
		CREATE TABLE cfg_reportes (
		  reporte varchar(30) not null,
		  titulo varchar(255),
		  clase varchar(30),
		  tipo int(4) default '0',
		  plantilla varchar(30),
		  query text,
		  parametros text,
		  expresiones text,
		  expresiones_reg text,
		  cortes text,
		  campos_busquedas text,
		  reg_pag int(4),		  
		  
		  estilo text,
		  propiedades text,

		  estilo_titulo text,
		  propiedades_titulo text,

		  estilo_corte text,
		  propiedades_corte text,

		  estilo_reg text,
		  propiedades_reg text,

		  estilo_pie_corte text,
		  propiedades_pie_corte text,

		  estilo_pie_inf text,
		  propiedades_pie_inf text,

		  PRIMARY KEY  (reporte)
		) TYPE=InnoDB;
		";
		break;
	case "cfg_campos_rep":
		$cn->query = "
		CREATE TABLE cfg_campos_rep (
		  reporte varchar(30) null,
		  tabla varchar(30) null,
		  campo varchar(30) not null,
		  titulo varchar(255),
		  clase varchar(30),
		  parametros text,
		  contar int(4) default 0,
		  sumar int(4) default 0,
		  promediar int(4) default 0,	
		  formato text,
		  html int(1),			  

		  estilo text,
		  propiedades text,

		  estilo_titulo text,
		  propiedades_titulo text,

		  estilo_reg text,
		  propiedades_reg text,

		  estilo_pie_corte text,
		  propiedades_pie_corte text,

		  estilo_pie_inf text,
		  propiedades_pie_inf text,
	  
		  PRIMARY KEY  (reporte,tabla,campo)
		) TYPE=InnoDB;
		";
		break;

	case "cfg_plantillas":
		$cn->query = "
		CREATE TABLE cfg_plantillas (
		  plantilla varchar(30) NOT NULL,
		  titulo varchar(100),
		  tipo int(1),
		  diagrama text,
		  status int(1) default '1',
		  PRIMARY KEY  (plantilla)
		) TYPE=InnoDB;		
		";
		break;
    default:
    	$cn->query = "SELECT 1";
    }// end switch
    $cn->ejecutar();
    echo "<hr>$tabla_x: ".$cn->query."<br><span style=\"background:#cccccc;\">Error: ".$cn->errmsg."</span>";
}// next
//**************************************************************************************
//**************************************************************************************
//**************************************************************************************
$t = explode (",",$tablas);
foreach ($t as $tabla_x) {
	$cn->query = "SELECT 1";
	switch (trim($tabla_x)){
	//===================================================
	case "cfg_reportes":
		$cn->query = "
		INSERT INTO cfg_reportes (reporte,titulo,clase,tipo,plantilla,query,parametros,expresiones,expresiones_reg,campos_busquedas,cortes,reg_pag) VALUES ('cfg_campos','Que Tiulo','','0','reporte_x','select formulario, tabla, campo, alias, titulo, tipo_ini, valor_ini ,tipo_fin ,valor_fin ,tipo, control from cfg_campos order by tabla,control','if:\'&PR_titulo\'==\'x\'\;then:titulo:Ferrari{exp=date(\'Y\')}\;else:titulo:Compaq{exp=time()}\;','a:0\;if:1>2\;then:titulo:reporte Uno\;else:titulo:@name &PR_titulo{exp=strlen(\'&PR_titulo\')}\;border:4\;mi_titulo:&PR_titulo\;','a:{exp=&EX_a+&control}\;','','tabla:Tabla de Formulario\;control:Tipo de Control\;','10000');
		INSERT INTO cfg_reportes (reporte,titulo,clase,tipo,plantilla,query,parametros,expresiones,expresiones_reg,campos_busquedas,cortes,reg_pag) VALUES ('inventario','s','','0','reporte_x','select * from inventario_donantes','','','','','',null); 		
		";
		break;		
	case "cfg_campos_rep":
		$cn->query = "
		INSERT INTO cfg_campos_rep (reporte,tabla,campo,titulo,parametros,clase,estilo,propiedades,estilo_reg,propiedades_reg,contar,sumar,promediar,formato,html) VALUES ('','cfg_campos','campo','Que campo','clase_titulo:k\;','prisma','color:orange\;','bordercolordark:#009999\;bordercolorlight:#FF0000\;onclick:alert(\'yanny esteban\')','','','0','0','0','','0');
		INSERT INTO cfg_campos_rep (reporte,tabla,campo,titulo,parametros,clase,estilo,propiedades,estilo_reg,propiedades_reg,contar,sumar,promediar,formato,html) VALUES ('','cfg_campos','tipo','TipoCT','clase_titulo:k\;if:3>2\;then:titulo:XXXXXX{exp=\'yanny &PR_clase\'.date(\'Y\')}\;else:titulo:YYYYYY\;prefijo:Bs.. \;sufijo: %.\;longitud:10\;decimales:3\;','prisma','','','if:&tipo==2\;then:color:green\;text-align:right\;if:&tipo==0\;then:color:purple\;','','0','0','0','','0');
		INSERT INTO cfg_campos_rep (reporte,tabla,campo,titulo,parametros,clase,estilo,propiedades,estilo_reg,propiedades_reg,contar,sumar,promediar,formato,html) VALUES ('','cfg_campos','titulo','XYZ','clase_titulo:ex\;','','','nowrap:nowrap\;','','if:&control==6\;then:class:ex|align:center|onmouseover:this.className=\'ex1\'|onmouseout:this.className=\'ex\'\;title:&EX_a\;','0','0','0','','0');
		INSERT INTO cfg_campos_rep (reporte,tabla,campo,titulo,parametros,clase,estilo,propiedades,estilo_reg,propiedades_reg,contar,sumar,promediar,formato,html) VALUES ('','inventario_donantes','fecha','Fecha de Donación','','ex','','','','','0','0','0','','0'); 
		";
		break;		
    default:
    	$cn->query = "SELECT 1";
    }// end switch
    $cn->ejecutar_m($cn->query);
    echo "<hr>$tabla_x: ".$cn->query."<br><span style=\"background:#cccccc;\">Error: ".$cn->errmsg."</span>";
}// next
$cn->query = "SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;";
//echo $cn->query;
$cn->ejecutar();
?>