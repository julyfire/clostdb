

function changeView(){
	//document.oneseq.submit();
	
	var format='';
	var radio=document.oneseq.view;
	for(var i=0;i<radio.length;i++)
		if(radio[i].checked)
			format=radio[i].value;

	var from=document.oneseq.elements['sfrom'];
	var to=document.oneseq.elements['sto'];
	var seg='';
	if(document.oneseq.segment.checked){
		if(from.value=='' || parseInt(from.value)<1)
			from.value=1;
		if(to.value=='' || parseInt(to.value)>length)
			to.value=length;
		if(parseInt(from.value)<parseInt(to.value))
			seg=seq.substring(from.value-1,to.value);	
	}
	else
		seg=seq;
	
	if(document.oneseq.rc.checked){
		seg=rcseq(seg);
	}
	
	
	//////////////////////////////////////
	var newContent='';

	if(format=='detail'){
		newContent+="<h2>"+desc+"</h2><br>\n";
		newContent+="<table class='t2'>";
		newContent+=basicInfo;
		newContent+=feature;
		newContent+="<tr><th>Sequence</th><td id='seq'><pre>"+formatSeq2(seg,60)+"</pre></td></tr>";
		newContent+="</table>\n";
	}
	if(format=='fasta'){
		newContent+="<h2>"+desc+"</h2><br>\n";
		newContent+="<pre>\n>"+acc+" "+desc+"\n"+formatSeq(seg,80);+"\n</pre>";	
	}
	document.getElementById('content').innerHTML=newContent;
}

function showSeg(){	
	var from=document.oneseq.elements['sfrom'];
	var to=document.oneseq.elements['sto'];

	if(document.oneseq.segment.checked){
		from.disabled="";
		to.disabled="";
	}
	else{
		from.disabled="true";
		to.disabled="true";
	}
}

function formatSeq(seq,len){
	
	var newSeq='';
	var i;
	for(i=0;i+len<=seq.length;i+=len)
		newSeq+=seq.substring(i,i+len)+"\n";
	if(i<seq.length)
		newSeq+=seq.substring(i,seq.length);

	return newSeq;
}
function formatSeq2(seq,len){
	var newSeq='';
	var fs=formatSeq(seq,len).split("\n");
	for(var i=0;i<fs.length;i++){
		start=i*len+1;
		newSeq+="<span class='pos'>"+start+"</span>";
		l=formatSeq(fs[i],10).split("\n").join(" ");
		newSeq+=l+"\n";
	}
	return newSeq;
}
function rcseq(seq){
	var newSeq='';
	var bases=seq.split("");
	var b;
	for(var i=bases.length-1;i>=0;i--){
		switch(bases[i]){
			case 'A':
				b='T';
				break;
			case 'T':
				b='A';
				break;
			case 'G':
				b='C';
				break;
			case 'C':
				b='G';
				break;
			default:
				b=bases[i];
		}
		newSeq+=b;
	}
	return newSeq;
}

function toBlast(){
	var qseq=">"+acc+" "+desc+"\n"+formatSeq(seq,80);
	document.toblast.elements['qs'].value=qseq;
	document.toblast.elements['qtitle'].value=acc;
	document.toblast.submit();
}	
