<?php
class style_property{
	function doit(){
		$cad = "";
		foreach($this as $property => $value){
			$property = str_replace("_","-",$property);
	        eval("\$cad .= \"$property:$value;\";");
		}// next
        if ($cad!=""){
			$cad = preg_replace("|;+|",";",$cad);
        	return " style=\"".$cad."\"";
        }else{
        	return "";
		}// end if
    }// end function
}// end class
class cls_element_html{
    var $accept = "";
    var $accept_charset = "";
    var $accesskey = "";
    var $action = "";
    var $alt = "";
	var $align = "";
    var $checked = false;
    var $class = "";
    var $cols ="";
    var $disabled = false;
    var $enctype = "";
	var $frameborder = "";
    var $height = "";
	var $hspace = "";
    var $href = "";
    var $id = "";
    var $inner_html = "";
    var $inner_text = "";
    var $ismap = "";
    var $label = "";
	var $longdesc = "";
    var $maxlength = "";
    var $method = "";
    var $multiple = false;
    var $name = "";
    var $onblur = "";
    var $onchange = "";
    var $onclick = "";
    var $ondblclick = "";
    var $onfocus = "";
    var $onkeydown = "";
    var $onkeypress = "";
    var $onkeyup = "";
    var $onmousedown = "";
    var $onmousemove = "";
    var $onmouseout = "";
    var $onmouseover = "";
    var $onmouseup = "";
    var $onreset = "";
    var $onselect = "";
    var $onsubmit = "";
	var $readonly = false;
    var $reset = "";
    var $rows = "";
    var $selected = false;
	var $scrolling = "";
    var $size = "";
    var $src = "";
    var $style = "";
    var $tabindex = "";
    var $tag = "";
	var $target = "";
    var $title = "";
    var $type = "";
    var $usemap = "";
    var $value = "";
	var $vspace = "";
    var $width = "";
	var $property = "";
    //======================================
	function cls_element_html($tipo_x="",$nombre_x=""){
		$this->type = $tipo_x;
		$this->name = $nombre_x;
		$this->style = new style_property;
    }// end function
    //======================================
    function doit(){
    	$cad = "";
        $cad .= ($this->id != "")?" id=\"$this->id\"":"";
        $cad .= ($this->name != "")?" name=\"$this->name\"":"";
        $cad .= ($this->class != "")?" class=\"$this->class\"":"";
	    switch (strtolower($this->type)){
	    case "form":
	        $cad .= ($this->accept != "")?" accept=\"$this->accept\"":"";
	        $cad .= ($this->action != "")?" action=\"$this->action\"":"";
	        $cad .= ($this->enctype != "")?" enctype=\"$this->enctype\"":"";
	        $cad .= ($this->method != "")?" method=\"$this->method\"":"";
	        $cad .= ($this->reset != "")?" reset=\"$this->reset\"":"";
	        $cad .= ($this->onreset != "")?" onreset=\"$this->onreset\"":"";
	        $cad .= ($this->onsubmit != "")?" onsubmit=\"$this->onsubmit\"":"";
	        $cad .= ($this->accept_charset != "")?" accept-charset=\"$this->accept_charset\"":"";
	        $cad .= ($this->target != "")?" target=\"$this->target\"":"";
            break;
	    case "button":
	    case "checkbox":
	    case "file":
	    case "hidden":
	    case "image":
	    case "password":
	    case "radio":
	    case "radiobutton":
	    case "reset":
	    case "submit":
	    case "text":
			$cad .= ($this->type != "")?" type=\"$this->type\"":"";
	        $cad .= ($this->src != "")?" src=\"$this->src\"":"";
	        $cad .= ($this->maxlength != "")?" maxlength=\"$this->maxlength\"":"";
	        $cad .= ($this->checked)?" checked":"";
	        $cad .= ($this->size != "")?" size=\"$this->size\"":"";
	        $cad .= ($this->value != "")?" value=\"$this->value\"":" value=\"\"";
	        break;
	    case "textarea":
	        $cad .= ($this->rows != "")?" rows=\"$this->rows\"":"";
	        $cad .= ($this->cols != "")?" cols=\"$this->cols\"":"";
	        break;
	    case "select":
	        $cad .= ($this->multiple)?" multiple":"";
	        $cad .= ($this->size != "")?" size=\"$this->size\"":"";
	        break;
	    case "optgroup":
	        $cad .= ($this->label != "")?" label=\"$this->label\"":"";
	        break;
	    case "option":
	        $cad .= ($this->selected)?" selected":"";
	        $cad .= " value=\"$this->value\"";
	        break;
	    case "img":
	        $cad .= ($this->src != "")?" src=\"$this->src\"":"";
	        $cad .= ($this->longdesc != "")?" longdesc=\"$this->longdesc\"":"";
	        $cad .= ($this->width != "")?" width=\"$this->width\"":"";
	        $cad .= ($this->height != "")?" height=\"$this->height\"":"";
			$cad .= ($this->align != "")?" align=\"$this->align\"":"";
	        break;
	    case "iframe":
	        $cad .= ($this->src != "")?" src=\"$this->src\"":"";
	        $cad .= ($this->frameborder != "")?" frameborder=\"$this->frameborder\"":"";
	        $cad .= ($this->width != "")?" width=\"$this->width\"":"";
	        $cad .= ($this->height != "")?" height=\"$this->height\"":"";
	        $cad .= ($this->hspace != "")?" hspace=\"$this->hspace\"":"";
	        $cad .= ($this->vspace != "")?" vspace=\"$this->vspace\"":"";
	        $cad .= ($this->scrolling != "")?" scrolling=\"$this->scrolling\"":"";
	        break;
	    case "a":
	        $cad .= ($this->href != "")?" href=\"$this->href\"":"";
	        $cad .= ($this->target != "")?" target=\"$this->target\"":"";
	    }// end switch
        $aux = $this->style->doit();
        $cad .= ($aux!="")?$aux:"";
        $cad .= ($this->accesskey != "")?" accesskey=\"$this->accesskey\"":"";
        $cad .= ($this->alt != "")?" alt=\"$this->alt\"":"";
        $cad .= ($this->ismap != "")?" ismap=\"$this->ismap\"":"";
        $cad .= ($this->tabindex != "")?" tabindex=\"$this->tabindex\"":"";
        $cad .= ($this->title != "")?" title=\"$this->title\"":"";
        $cad .= ($this->usemap != "")?" usemap=\"$this->usemap\"":"";
        $cad .= ($this->onblur != "")?" onblur=\"$this->onblur\"":"";
        $cad .= ($this->onchange != "")?" onchange=\"$this->onchange\"":"";
        $cad .= ($this->onclick != "")?" onclick=\"$this->onclick\"":"";
        $cad .= ($this->ondblclick != "")?" ondblclick=\"$this->ondblclick\"":"";
        $cad .= ($this->onfocus != "")?" onfocus=\"$this->onfocus\"":"";
        $cad .= ($this->onkeydown != "")?" onkeydown=\"$this->onkeydown\"":"";
        $cad .= ($this->onkeypress != "")?" onkeypress=\"$this->onkeypress\"":"";
        $cad .= ($this->onkeyup != "")?" onkeyup=\"$this->onkeyup\"":"";
        $cad .= ($this->onmousedown != "")?" onmousedown=\"$this->onmousedown\"":"";
        $cad .= ($this->onmousemove != "")?" onmousemove=\"$this->onmousemove\"":"";
        $cad .= ($this->onmouseover != "")?" onmouseover=\"$this->onmouseover\"":"";
		$cad .= ($this->onmouseout != "")?" onmouseout=\"$this->onmouseout\"":"";
        $cad .= ($this->onmouseup != "")?" onmouseup=\"$this->onmouseup\"":"";
        $cad .= ($this->onselect != "")?" onselect=\"$this->onselect\"":"";
        $cad .= ($this->property != "")?" $this->property":"";
		$cad .= ($this->disabled)?" disabled":"";
        $cad .= ($this->readonly)?" readonly":"";
        return $cad."";
    }// end function
    function control(){
		switch (strtolower($this->type)){
			case "button":
			case "checkbox":
            case "file":
			case "hidden":
			case "image":
            case "password":
			case "radio":
			case "radiobutton":
			case "reset":
			case "submit":
			case "text":
                $cad = "\n<input".$this->doit().">".$this->label;
				break;
			case "textarea":
                $cad = "\n<textarea".$this->doit().">".$this->value."</textarea>";
                break;
            case "a":
			case "select":
            case "option":
            case "optgroup":
                $cad = "\n<$this->type".$this->doit().">".$this->inner_html."</$this->type>";
                break;
            case "img":
            case "hr":
                $cad = "\n<$this->type".$this->doit().">";
                break;
			default:
                $cad = "\n<$this->type".$this->doit().">".$this->inner_html."</$this->type>";
				break;
		}// end switch
        return $cad;

    }// end function

}// end class
/*
$a = new cls_elemento_html();
$a->onclick ="alert(1)";
//$a->label = "es unchecke";
$a->type = "checkbox";
 $a->name = "nombre";
$a->value = "yanny";
$a->style->background = "yellow";
$a->href = "http://www.google.com";
$a->src = "imagenes/images(11).jpg";
$a->inner_html = "GooGle";
$a->style->color = "red";
echo $a->control();
$a->name = "apellido";
$a->id = "apellido";
//$a->target = "_blank";
$a->width = "200px";
$a->type ="option";
$a->inner_html ="uno";
$c = $a->control().$a->control().$a->control().$a->control().$a->control();
$a->type = "select";
//$a->style->position = "absolute";
//$a->style->top = "250";
$a->inner_html = $c;
echo $a->control();
echo "<hr>";
$a->type ="text";

$a->style->color = "red";
//$a->style->height = "100px";
//$a->style->width = "200px";
$a->style->background = "yellow";
$a->style->cursor = "pointer";

//$a->class = "tres";
$a->inner_html = "yanny";
echo $a->control();
echo "";

$textbox1 = new cls_elemento_html();
$textbox1->type = "text";
$textbox1->name = "email";
$textbox1->value = "yannyesteban@hotmail.com";
echo $textbox1->control();
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
</style>
"

*/
?>