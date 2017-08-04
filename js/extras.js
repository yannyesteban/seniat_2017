// JavaScript Document


(function(){
	
	if(document.getElementById("_rand_aux")){
		var 
			date = new Date(),
			rnd =  date.getDate()+date.getTime() ;
		document.getElementById("_rand_aux").value=rnd;	
		
		
	}

	
}());       
function option_check(ele){
	var value = ele.value
	var f = ele.form
	var n = f.elements.length
	var name = ele.name
	for(var i in f.elements){
		if(f.elements[i].type=="radio" && f.elements[i].name!=name){
			if(f.elements[i].value === value){
				f.elements[i].checked = true	
			}

		}// end if
		
	}// next
	
} 