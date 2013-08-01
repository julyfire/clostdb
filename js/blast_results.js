function getSeq(){
	var f=document.forms['getSeqAlignment'];
	var c=0;
	for(var i=0;i<f.elements.length;i++)
		if(f.elements[i].type=='checkbox' && f.elements[i].checked==true){
			c=1	
			break;
		}
	if(c){
		loading();		
		f.submit();		
	}
	else
		alert("No sequence is selected!");
}
