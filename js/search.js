
function openPopDiv(id){
	var popDiv=document.getElementById(id);
	if(popDiv.style.display == "block")
		popDiv.style.display = "none";
	else{
		popDiv.style.display = "block";
		var item=id.match(/(map).*(\d)/);
		if(item){
			toMap(item[1]+item[2],popDiv);
				
		}
	}
}

function toFile(name){
	//document.rl.action="tofile.php";
	//document.rl.submit();
	var ids=getCheckboxValue(document.rl.elements['id[]'])
	if(ids){
		ids=ids.join(',');
		document.forms[name].ids.value=ids;
		document.forms[name].submit();
		return true;
	}
	else{
		if(window.confirm('You DO NOT select any record.\n Do you want to download ALL '+all+' records?')){
      	document.forms[name].ids.value=ids;
			document.forms[name].submit();
         return true;
      }
	}
	return false;
}

function toMap(id,pop){
	var ids=getCheckboxValue(document.rl.elements['id[]'])
	if(ids){
		idlist=ids.join(',');
		initMap(id);
		return true;
	}
	else{
		if(window.confirm('You DO NOT select any record.\n Do you want to download ALL '+all+' records?')){
      	idlist='';
      	initMap(id);
         return true;
      }
	}
	pop.style.display = "none";
	return false;
}

document.onmousedown = function(e){
	var r1=getElementSize('savePanel1');
	var s1=document.getElementById('savePanel1');	
	var r2=getElementSize('savePanel2');
	var s2=document.getElementById('savePanel2');
	var r3=getElementSize('mapPanel1');
	var s3=document.getElementById('mapPanel1');
	var r4=getElementSize('mapPanel2');
	var s4=document.getElementById('mapPanel2');
	
	var r=[r1,r2,r3,r4];
	var s=[s1,s2,s3,s4];
	
	var pos=getMousePos(e);
	var x=pos.x;
	var y=pos.y;
	
	for(var i=0;i<r.length;i++){
		if(s[i].style.display=="block" && !(r[i].minx<x && x<r[i].maxx && r[i].miny<y && y<r[i].maxy)){
			var opened=s[i];
			setTimeout(function () {opened.style.display='none';},200);//延迟200ms以避开按钮单击事件
		}
	}
};
