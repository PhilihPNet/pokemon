 var pokemons={ids:[]};

 function $GET(script,data,method,target,callback){
 		var xHttp = null;
			xHttp = new XMLHttpRequest();
			xHttp.onreadystatechange=function(){
				if(this.readyState==4 && this.status==200){
					var s=JSON.parse(xHttp.responseText);
					if(target )eval(`${target}= s`);
					if(callback ){
						callback=callback.split(/\s/g).join('(s);');
						eval(`${callback}(s)`);
					}
				}
			}

		xHttp.open(method,script+(data?"/"+data:""),true); 
		xHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

	  xHttp.send();
 }
 function upGrade(pokemon){
	 $GET('/uppokemon',pokemon,"GET",null,'upGraded')
	 
 }
  function upGraded(s){
	 for(var i in s)
		 if(document.getElementById('pokemon_'+i)){
			 if((document.getElementById('pokemon_'+i).tagName=="IMG"))
				document.getElementById('pokemon_'+i).src=s[i]
			 if(document.getElementById('pokemon_'+i).innerHTML)
				document.getElementById('pokemon_'+i).innerHTML=s[i];
		 }
 }
function evolution(){

var c=0;
	for(var pokemon in pokemons){
		if(pokemons[pokemon].evolution){
			var cont=document.getElementById('content').getElementsByClassName('container')[c];
			degreEvolution(pokemons[pokemon].evolution,cont.getElementsByClassName('pokemon_evolution_name')[0],'name');
			degreEvolution(pokemons[pokemon].evolution,cont.getElementsByClassName('pokemon_evolution_image')[0],'image');
		}c++
	}
}
 function degreEvolution(pokemon,target,attribut){
	var modele=target.cloneNode(true);
	var a=modele.getElementsByTagName('a')[0];
	a.innerHTML=attribut!='image'
					?'<span class="attribut pokemon_'+attribut+'">'+pokemon[attribut]+'</strong>'
					:'<img  class="attribut pokemon_image" src="'+pokemon[attribut]+'" alt="" /> '
	target.parentNode.appendChild(modele);
	a.getData=new Function( 'return JSON.parse("{"+this.getAttribute("data").replace(/\'/g,\'"\')+"}")');
	a.setData=new Function('attribut','valeur', 'var data=this.getData();data[attribut]=valeur;data=JSON.stringify(data).replace(/"/g,\'\\\'\').replace(/{|}/g,\'\');this.setAttribute(\'data\',data);return data;');
	a.setData('id',pokemon.id);

	if(!pokemon.evolution) return true;
			target.innerHTML+='<div id="pokemon_evolution_'+pokemon.evolution.id+'"></div>';
			degreEvolution(pokemon.evolution,target,attribut);

 }
 function go(a){
		a.getData=new Function( 'return JSON.parse("{"+this.getAttribute("data").replace(/\'/g,\'"\')+"}")');
		document.location.href=a.getData().path+'/'+a.getData().id;
 
 }
  function reinit(){
	  if(pokemons)for(var pokemon in pokemons){
	 $GET('/reinitialise',pokemons[pokemon].id_team,"GET",null,'upGraded');
	 break;
  }
}