<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Reporte</title>
</head>
<link rel="stylesheet" type="text/css" href="css/especial.css">
<body>
<form action="" method="post" name="form1"  id="form1">
  <input name="pag_ini" type="text" class="especial" id="pag_ini" />
  <input type="text" name="pag_fin" class="especial" id="pag_fin" />
  <input type="button" class="green3" name="ver" id="ver" value="Mostrar" onclick="parent.document.getElementById('mainFrame').src='index2.php?pagina_ini='+pag_ini.value+'&pagina_fin='+pag_fin.value" />
  <input type="button" class="green3" value="Mostrar Todas" name="mostrar_todas" onclick="parent.document.getElementById('mainFrame').src='index2.php?mostrar_todas=1'" /> 
  &nbsp;&nbsp;&nbsp;<input type="button" class="green3" name="imprimir" id="imprimir" value="Imprimir" onclick="parent.myprint()"/>
</form>
</body>
</html>
