function new_obj_ajax(){
	var req = false
	if(window.XMLHttpRequest) {
		req = new XMLHttpRequest();
	}else if(window.ActiveXObject) {
		req = new ActiveXObject("Microsoft.XMLHTTP");
	}// end if	
    return req;
}
function exec_ajax(file_x,id_x,param, p){
	var xa = new_obj_ajax()
	
	var rnd =  Math.random()*100000
	var ele=null
	
	var xml_id = null
	var xml_html = null
	var xml_script = null
	var xml_message = null
	var xml_mode = null;

	var html = null
	var script = null
	var id = null
	var mode = 0;
	var message = null
	
	var div = null
	var INS = document.forms[0].cfg_ins_aux.value;
	//alert(xa.onreadystatechange)
	//ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
     xa.open("GET", file_x+"?p="+p+"&"+param+"&rnd="+rnd+"&cfg_ins_aux="+INS,true);
        xa.onreadystatechange=function(){
			if (xa.readyState == 4){
				xmldoc = xa.responseXML;
				var n = xmldoc.getElementsByTagName('panel').length
				for(var i=0;i<n;i++){
					ele = xmldoc.getElementsByTagName('panel').item(i)
					
					xml_id = ele.getElementsByTagName('id').item(0)
					xml_mode = ele.getElementsByTagName('mode').item(0)
					xml_html = ele.getElementsByTagName('html').item(0)
					xml_script = ele.getElementsByTagName('script').item(0)
					xml_message = ele.getElementsByTagName('message').item(0)
					
					if(xml_id.childNodes.item(0).wholeText){
						id = xml_id.childNodes.item(0).wholeText
					}else{
						id = xml_id.childNodes.item(0).data
					}// end if
					if(xml_mode.childNodes.item(0).wholeText){
						mode = xml_mode.childNodes.item(0).wholeText
					}else{
						mode = xml_mode.childNodes.item(0).data
					}// end if
					if(xml_html.childNodes.item(0).wholeText){
						html = xml_html.childNodes.item(0).wholeText
					}else{
						html = xml_html.childNodes.item(0).data
					}// end if
					if(xml_script.childNodes.item(0).wholeText){
						script = xml_script.childNodes.item(0).wholeText
					}else{
						script = xml_script.childNodes.item(0).data
					}// end if
					if(xml_message.childNodes.item(0).wholeText){
						message = xml_message.childNodes.item(0).wholeText
					}else{
						message = xml_message.childNodes.item(0).data
					}// end if
					//alert(id)
					
					
					if(id != "" && document.getElementById(id)){
						div = document.getElementById(id)
						if(mode==1){
							var s = document.createElement('span');
							s.innerHTML = html
							div.appendChild(s);
						
						}else if(mode==2){
							var s = document.createElement('span');
							s.innerHTML = html
							div.insertBefore(s, div.firstChild)
						}else{
							div.innerHTML = html
						}
						
						
					}// end if
					eval(script)
				}// next
			}// end if
        }// end function
        xa.send(null)
		return xa
}// end function
