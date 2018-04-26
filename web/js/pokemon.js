 function $GET(script,data,method,target,callback){
 		var xHttp = null;
			xHttp = new XMLHttpRequest();
			xHttp.onreadystatechange=function(){
				if(this.readyState==4 && this.status==200){
					var s=JSON.parse(xHttp.responseText/*.replace(/\&#039;/g,'"')*/);
					if(target )eval(`${target}= s`);//une variable à laquelle on affecte le JSON de retour
					if(callback ){callback=callback.split(/\s/g).join('();');eval(`${callback}()`)};
				}
			}

		xHttp.open(method,script+"/"+data,true); 
		xHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

	  xHttp.send();

 }
 function upGrade(pokemon){
	 $GET('/uppokemon',pokemon,"GET",null,null)
	 
 }