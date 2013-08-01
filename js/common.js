
function loading(){
	document.getElementById('top').style.display='block';
	document.getElementById('below').style.display='block';
}

function selectBox(toggle,o){
	toggle.checked?selectAll(o):selectNull(o);
}
 
function selectAll(name){
	var f=document.forms[name];
	for(var i=0;i<f.elements.length;i++)
		if(f.elements[i].type=="checkbox")
      	f.elements[i].checked=true;
}
   
   
function selectNull(name){
	var f=document.forms[name];
    for(var i=0;i<f.elements.length;i++)
    	if(f.elements[i].type=="checkbox")
			f.elements[i].checked=false;
}

function getCheckboxValue(checkbox){
	if(!checkbox.length&&checkbox.type.toLowerCase()=='checkbox')
		return (checkbox.checked)?checkbox.value:'';
	if(checkbox[0].tagName.toLowerCase()!='input'||checkbox[0].type.toLowerCase()!='checkbox')
		return '';
	var val=[];
	var len=checkbox.length;
	for(i=0;i<len;i++)
		if(checkbox[i].checked)
			val[val.length]=checkbox[i].value;
	return (val.length)?val:'';
}
/*
$(function(){
   $("ul.dropdown li").hover(function(){
        $(this).addClass("hover");
        $('ul:first',this).css('visibility', 'visible');
    
    }, function(){
   $(this).removeClass("hover");
   $('ul:first',this).css('visibility', 'hidden');
});
$("ul.dropdown li ul li:has(ul)").find("a:first").append(" &raquo; ");
});
*/
