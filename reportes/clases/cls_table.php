<?php
define ("C_MODO_VECTOR",1);
define ("C_MODO_MATRIZ",2);
define ("C_MODO_INNER_HTML",1);
define ("C_MODO_INNER_TEXT",2);
define ("C_TEXT_DEFAULT","&nbsp;");
class table_property{
    var $id = "";
    var $name = "";
	var $rows = 0;
	var $cols = 0;
    var $rowspan = "";
    var $colspan = "";
    var $span = "";
    var $scope = "";
    var $width = "";
    var $height = "";
    var $class = "";
    var $style = "";
    var $align = "";
    var $valign = "";
    var $char = "";
    var $bgcolor = "";
    var $background = "";
    var $nowrap = "";
    var $title = "";
    var $value = "";
    var $type = "td";
    var $border ="";
    var $onclick = "";
    var $ondblclick = "";
    var $onmousedown = "";
    var $onmouseup = "";
    var $onmouseover = "";
    var $onmousemove = "";
    var $onmouseout = "";
    var $onkeypress = "";
    var $onkeydown = "";
    var $onkeyup = "";
    var $text = C_TEXT_DEFAULT;
    var $hide = false;
    var $frame = "";
    var $rules = "";
	var $cellspacing = "";
	var $cellpadding = "";
	var $property = "";
    var $tag = "";
    //======================================
    function doit(){
		$cad = "";
        $cad .= ($this->id != "")?" id=\"$this->id\"":"";
        $cad .= ($this->name != "")?" name=\"$this->name\"":"";
        $cad .= ($this->rowspan != "")?" rowspan=\"$this->rowspan\"":"";
        $cad .= ($this->colspan != "")?" colspan=\"$this->colspan\"":"";
        $cad .= ($this->span != "")?" span=\"$this->span\"":"";
        $cad .= ($this->scope != "")?" scope=\"$this->scope\"":"";
        $cad .= ($this->width != "")?" width=\"$this->width\"":"";
        $cad .= ($this->height != "")?" height=\"$this->height\"":"";
        $cad .= ($this->class != "")?" class=\"$this->class\"":"";
        $cad .= ($this->style != "")?" style=\"$this->style\"":"";
        $cad .= ($this->align != "")?" align=\"$this->align\"":"";
        $cad .= ($this->valign != "")?" valign=\"$this->valign\"":"";
        $cad .= ($this->char != "")?" char=\"$this->char\"":"";
        $cad .= ($this->bgcolor != "")?" bgcolor=\"$this->bgcolor\"":"";
        $cad .= ($this->background != "")?" background=\"$this->background\"":"";
        $cad .= ($this->nowrap  != "")?" nowrap":"";
        $cad .= ($this->title != "")?" title=\"$this->title\"":"";
        $cad .= ($this->border != "")?" border=\"$this->border\"":"";
        $cad .= ($this->onclick != "")?" onclick=\"$this->onclick\"":"";
        $cad .= ($this->ondblclick != "")?" ondblclick=\"$this->ondblclick\"":"";
        $cad .= ($this->onmousedown != "")?" onmousedown=\"$this->onmousedown\"":"";
        $cad .= ($this->onmouseup != "")?" onmouseup=\"$this->onmouseup\"":"";
        $cad .= ($this->onmouseover != "")?" onmouseover=\"$this->onmouseover\"":"";
        $cad .= ($this->onmousemove != "")?" onmousemove=\"$this->onmousemove\"":"";
        $cad .= ($this->onmouseout != "")?" onmouseout=\"$this->onmouseout\"":"";
        $cad .= ($this->onkeypress != "")?" onkeypress=\"$this->onkeypress\"":"";
        $cad .= ($this->onkeydown != "")?" onkeydown=\"$this->onkeydown\"":"";
        $cad .= ($this->onkeyup != "")?" onkeyup=\"$this->onkeyup\"":"";
        $cad .= ($this->cellspacing != "")?" cellspacing=\"$this->cellspacing\"":"";
        $cad .= ($this->cellpadding != "")?" cellpadding=\"$this->cellpadding\"":"";
        $cad .= ($this->frame != "")?" frame=\"$this->frame\"":"";
        $cad .= ($this->rules != "")?" rules=\"$this->rules\"":"";
        $cad .= ($this->property != "")?" $this->property":"";
        return $cad."";
    }// end function
}// end class
class cls_table extends table_property{
	var $caption;
	var $text;
	var $mode_text = C_MODO_MATRIZ;
	var $mode_inner = C_MODO_INNER_HTML;
    var $cellspacing = "2";
	var $cellpadding = "2";
// ====================================
	function merge_cells($f1,$c1,$f2,$c2){
		$n_filas = $f2-$f1+1;
		$n_columnas = $c2-$c1+1;
		for($i=$f1;$i<=$f2;$i++){
			for($j=$c1;$j<=$c2;$j++){
				if ($i!=$f1 or $j!=$c1){
					$this->cell[$i][$j]->hide = true;
				}// end if
			}// next $j
		}// next $i
		$this->cell[$f1][$c1]->rowspan = ($n_filas>1)?$n_filas:"";
		$this->cell[$f1][$c1]->colspan .= ($n_columnas>1)?$n_columnas:"";
	}// end function
// ====================================
	function merge_row($f1,$c1=-1,$celdas_x=-1){
    	if ($celdas_x==-1)
        	$c2=$this->cols-1;
        else
        	$c2 = $c1+$celdas_x-1;
        if ($c1==-1)
        	$c1=0;
		$this->merge_cells($f1,$c1,$f1,$c2);
    }// end function
// ====================================
	function merge_col($f1,$c1=-1,$celdas_x=-1){
    	if ($celdas_x==-1)
        	$f2 = $this->rows-1;
        else
        	$f2 = $f1+$celdas_x-1;
        if ($c1==-1){
        	$c1 = $f1;
        	$f1 = 0;
        }// end if
		$this->merge_cells($f1,$c1,$f2,$c1);
    }// end function
// ====================================
    function range(&$obj_x,$prop_x,$valor_x,$f1,$c1,$f2=-1,$c2=-1){
    	if ($f2==-1)
        	$f2 = $this->rows-1;
    	if ($c2==-1)
        	$c2 = $this->cols-1;
		$n_filas = $f2-$f1+1;
		$n_columnas = $c2-$c1+1;
		for($i=$f1;$i<=$f2;$i++){
			for($j=$c1;$j<=$c2;$j++){
            	eval ("\$obj_x[\$i][\$j]->".$prop_x."= \$valor_x;");
			}// next $j
		}// next $i
	}// end function
// ====================================
	function range_cells($prop_x,$valor_x,$f1,$c1,$f2=-1,$c2=-1){
    	$this->range($this->cell,$prop_x,$valor_x,$f1,$c1,$f2,$c2);
    }// end function
// ====================================
    function range_rows(&$obj_x,$prop_x,$valor_x,$f1=-1,$f2=-1){
		$n_filas = $f2-$f1+1;
		$n_columnas = $c2-$c1+1;
		for($i=$f1;$i<=$f2;$i++){
			for($j=$c1;$j<=$c2;$j++){
            	eval ("\$obj_x[\$i][\$j]->".$prop_x."= \$valor_x;");
			}// next $j
		}// next $i
	}// end function
// ====================================
	function header_cells($f1,$c1,$f2=-1,$c2=-1){
		$this->range($this->cell,"type","th",$f1,$c1,$f2,$c2);
    }// end function
// ====================================
	function header_row($f1,$c1=-1,$celda_x=-1){
    	if ($celda_x==-1)
        	$c2=$this->cols-1;
        if ($c1==-1)
        	$c1=0;
		$this->range($this->cell,"type","th",$f1,$c1,$f1,$c2);
    }// end function
// ====================================
	function header_col($f1,$c1=-1,$celdas_x=-1){
    	if ($celdas_x==-1)
        	$f2 = $this->rows-1;
        else
        	$f2 = $f1+$celdas_x-1;
        if ($c1==-1){
        	$c1 = $f1;
        	$f1 = 0;
        }// end if
		$this->range($this->cell,"type","th",$f1,$c1,$f2,$c1);
    }// end function
// ====================================
	function set_thead($f1=-1,$f2=-1){
    	if ($f1==-1){
			$f1=0;
        }// end if
    	if ($f2==-1 or $f1== $f2){
        	$f2=$f1;
            $this->thead[$f1]->tag = "b";
            }
        else{
        	$this->thead[$f1]->tag = "i";
        	$this->thead[$f2]->tag = "f";
        }// end if
    }// end function
// ====================================
	function set_tbody($f1=-1,$f2=-1){
    	if ($f1==-1){
			$f1=1;
            $f2=$this->rows-1;
        }// end if
    	if ($f2==-1 or $f1== $f2){
        	$f2=$f1;
            $this->tbody[$f1]->tag = "b";
            }
        else{
        	$this->tbody[$f1]->tag = "i";
        	$this->tbody[$f2]->tag = "f";
        }// end if
    }// end function
// ====================================
	function set_tfoot($f1=-1,$f2=-1){
    	if ($f1==-1){
			$f1=$this->rows-1;
        }// end if
    	if ($f2==-1 or $f1== $f2){
        	$f2=$f1;
            $this->tfoot[$f1]->tag = "b";
            }
        else{
        	$this->tfoot[$f1]->tag = "i";
        	$this->tfoot[$f2]->tag = "f";
        }// end if
    }// end function
// ====================================
	function set_colgroup($c1,$c2){
		$n_columnas = $c2-$c1+1;
        $this->colgroup[$c1]->span = ($n_columnas>1)?$n_columnas:"";
        $this->colgroup[$c1]->tag = "1";
    }// end function
// ====================================
	function cls_table($f,$c=-1){
		if ($c==-1){
			$c = $f;
			$f = 0;
		}// end if
    	$this->rows = $f;
        $this->cols = $c;
        $this->caption = new table_property;
        $this->caption->text = "";
		for ($i=0;$i<$f;$i++){
            $this->row[$i] = new table_property;
            $this->thead[$i] = new table_property;
            $this->tbody[$i] = new table_property;
            $this->tfoot[$i] = new table_property;
            for ($j=0;$j<$c;$j++){
				$this->cell[$i][$j]=new table_property;
            }//next
        }// next
        for ($j=0;$j<$c;$j++){
            $this->col[$j]=new table_property;
            $this->colgroup[$j]=new table_property;
        }//next
    }// end function
	function create_row($c=-1){
		if ($c==-1){
			$c=$this->cols;
			}
		else{
			$this->cols = $c;
		}// end if
		$i = $this->rows;
		$this->row[$i] = new table_property;
		$this->thead[$i] = new table_property;
		$this->tbody[$i] = new table_property;
		$this->tfoot[$i] = new table_property;
		for ($j=0;$j<$c;$j++){
			$this->cell[$i][$j]=new table_property;
        }// next
		/*
        for ($j=0;$j<$c;$j++){
            $this->col[$j]=new table_property;
            $this->colgroup[$j]=new table_property;
        }//next
		*/
		$this->rows++;
	}// end function
	function create_col(){
		$i = $this->cols;
		for ($j=0;$j<$this->rows;$j++){
			$this->cell[$j][$i]=new table_property;
        }// next
		$this->col[$i]=new table_property;
		$this->colgroup[$i]=new table_property;
		$this->cols++;
	}// end function
// ====================================
    function control(){
    	$k=0;
		$cad = "\n<table";
        $cad .= $this->doit();
        $cad .= ">";
        if ($this->caption->text != ""){
	        $cad .= "\n<caption".$this->caption->doit().">".$this->caption->text."</caption>";
        }// end if
        $n_cols = 0;
		for($j=0;$j<$this->cols;$j++){
            if ($this->colgroup[$j]->tag=="1"){
                if ($this->colgroup[$j]->span!=""){
					$n_cols += $this->colgroup[$j]->span;
	                }
                else{
                	$n_cols++;
                }// end if
                $cad .= "\n<colgroup".$this->colgroup[$j]->doit()."></colgroup>";

            }// end if
        }// next
        if($n_cols>0){
        	$cad .= "\n<colgroup span=\"".($this->cols - $n_cols)."\"></colgroup>";
        }// next
		for ($i=0;$i<$this->rows;$i++){
        	$cad .= ($this->thead[$i]->tag=="i" or $this->thead[$i]->tag=="b")?"\n<thead".$this->thead[$i]->doit().">":"";
        	$cad .= ($this->tbody[$i]->tag=="i" or $this->tbody[$i]->tag=="b")?"\n<tbody".$this->tbody[$i]->doit().">":"";
        	$cad .= ($this->tfoot[$i]->tag=="i" or $this->tfoot[$i]->tag=="b")?"\n<tfoot".$this->tfoot[$i]->doit().">":"";
            $cad .= "\n<tr".$this->row[$i]->doit().">";
            for ($j=0;$j<$this->cols;$j++){
            	if (!$this->cell[$i][$j]->hide){
	                $cad .= "\n\t<".$this->cell[$i][$j]->type;
	                $cad .= $this->cell[$i][$j]->doit();
                    $cad .= $this->col[$j]->doit();
	                $cad .= ">";
                    if ($this->mode_text==C_MODO_VECTOR){
	                    $valor_x = (isset($this->text[$k]))?$this->text[$k]:C_TEXT_DEFAULT;
	                    }
                    else{
		                $valor_x = $this->cell[$i][$j]->text;
                    }// end if
                    $valor_x = ($this->mode_inner==C_MODO_INNER_HTML)?$valor_x:htmlentities($valor_x);
                    $cad .= $valor_x;
	                $cad .= "</".$this->cell[$i][$j]->type.">";
                    $k++;
                }// end if
            }//next
            $cad .= "\n</tr>";
            $cad .= ($this->tfoot[$i]->tag=="f" or $this->tfoot[$i]->tag=="b")?"\n</tfoot>":"";
            $cad .= ($this->tbody[$i]->tag=="f" or $this->tbody[$i]->tag=="b")?"\n</tbody>":"";
            $cad .= ($this->thead[$i]->tag=="f" or $this->thead[$i]->tag=="b")?"\n</thead>":"";
        }// next
        $cad .= "\n</table>";
        return $cad;
    }// end function
}// end class
/*
$t = new cls_table(10,4);
$t->border="5";

$t->create_row();
$t->merge_row(1);
$t->merge_row(3);
$t->merge_row(5,1,3);
//$t->cell[0][3]->hide = false;
echo $t->control();

*/


/*
$a = new cls_table(10,10);
$a->border="";
$a->with="650px";
$a->set_thead();
$a->set_tbody(3,6);
$a->set_tfoot();
//$a->thead[0]->class = "cinco";
$a->thead[0]->class = "smile";
$a->tbody[3]->class = "tres";
$a->set_colgroup(0,1);
$a->set_colgroup(2,2);
$a->set_colgroup(3,4);
$a->colgroup[0]->class = "negro";
$a->colgroup[3]->class = "negro";

//$a->merge_row(0,0);

//$a->set_thead();
//$a->set_tbody();
//$a->set_tfoot();
$a->thead[0]->class = "smile";
$a->tbody[1]->class = "tres";
$a->tfoot[9]->class = "negro";

$a->frame = "box";
$a->rules = "groups";
//$a->cell[4][4]->nowrap = true;


for ($i=0;$i<$a->rows;$i++){
    for ($j=0;$j<$a->cols;$j++){
        $a->cell[$i][$j]->text="Yanny dv d d g gds: $i .. $j";
    }// next
}// next
//$a->colgroup[5]->class = "cinco";
//$a->merge_row(3,1,3);




//$t->create_row();
//$t->cell[0][2]->text = "prueba";
//$t->create_row();
//$t->cell[1][2]->text = "pruebaerewr";
//$t->create_row();
//$t->cell[2][2]->text = "pruebad fdsf d";
//$t->create_row();
//$a->cell[3][2]->text = "pruebafsdfsadf";



echo $a->control();
*/
/*



$t = new cls_table(6,6);
//$t->row[3]->class="smile";
//$t->row[4]->bgcolor="yellow";
//$t->cell[4][2]->class="tres";
$t->cell[2][2]->type="th";
//$t->cell[5][5]->bgcolor="red";
//$t->cell[3][3]->hide =true;
//$t->cell[3][2]->colspan="2";
//$t->mode_text=C_MODO_VECTOR;
for ($j=0;$j<$t->rows*$t->cols;$j++){
    $t->text[$j]="f: $j";
}// next
for ($i=0;$i<$t->rows;$i++){
    for ($j=0;$j<$t->cols;$j++){
        $t->cell[$i][$j]->text="f$i..c$j";
    }// next
}// next
$t->caption->text = "yanny esteban";
$t->caption->class = "smile";
$t->caption->align = "right";
//$t->bgcolor ="orange";
$t->border ="5";
//$t->class ="dos";
//$t->cell[2][4]->class ="tres";
//$t->cell[0][0]->align ="right";
//$t->col[4]->class ="smile";
//echo "<hr>".$t->col[4]["class"]."<hr>";
//$t->merge_cells(0,0,1,1);
//$t->merge_cells(3,2,3,3);
//$t->range($t->cell,"bgcolor","orange",0,0,3,3);
//$t->header_cells(3,3,5,5);
//$t->col[4]->bgcolor="red";
//$t->align ="center";
$t->header_row(5);
$t->merge_col(5);
$t->header_col(2,1,3);
$t->cell[2][2]->property="onclick=\"alert(1)\"";
$t->range_cells("bgcolor","violet",0,0,3,3);
//echo $t->control();
$a = new cls_table(10,10);
$a->border = 2;
//$a->cell[3][3]->onclick = "alert(1)";
for ($i=0;$i<$a->rows;$i++){
    for ($j=0;$j<$a->cols;$j++){
        $a->cell[$i][$j]->text="Yanny: $i .. $j";
    }// next
}// next

$a->set_thead(0,2);
$a->set_tbody(3);
$a->set_tfoot(5);
$a->thead[1]->class = "smile";
$a->tbody[3]->class = "tres";
$a->thead[0]->class = "cinco";
$a->merge_row(0,0);

$a->set_thead();
$a->set_tbody();
$a->set_tfoot();
$a->thead[0]->class = "smile";
$a->tbody[1]->class = "tres";
$a->tfoot[9]->class = "cinco";
//$a->colgroup[5]->class = "cinco";
//$a->merge_row(3,1,3);
$a->set_colgroup(0,1);
$a->set_colgroup(2,5);
$a->set_colgroup(6,9);
//$a->colgroup[0]->class="smile";
$a->frame = "hsides";
$a->rules = "groups";
$a->col[3]->class ="smile";
$a->row[4]->class ="smile";
$a->merge_cells(5,4,8,5);
$a->cell[5][4]->valign = "top";
//$a->mode_inner = C_MODO_INNER_TEXT;
echo $a->control();

echo "<style>
.uno{
color:red;
}
.dos{
color:blue;
}
.tres{
color:green;
}
.cuatro{
color:white;

}
.smile{
	background: Aqua;
	font-family: Tahoma;
	font-variant: small-caps;
}
.cinco{
	background: red;
	font-family: Tahoma;
    color:yellow;
	font-size: 10pt;
    font-weight:bold;
}
.negro{
	background: black;
	font-family: Tahoma;
    color:white;
	font-size: 10pt;
    font-weight:bold;
}

</style>";
*/
?>