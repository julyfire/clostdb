// JavaScript Document
//by weibo 2011-12-5

function loading(){
	document.getElementById('top').style.display='block';
	document.getElementById('below').style.display='block';
}

function runblast(){
	loading();
	document.blast.submit();
}

function toggle(oa){
	for(var i=0;i<oa.length;i++){
		var o=oa[i];
		o=document.getElementById(o);
		if("collapse"==o.className) o.className="expand";
		else o.className="collapse";
	}
}

function setPara(o){	
	var program=o.options[o.selectedIndex].text;

	var database=document.blast.elements['DATABASE'];
	var matrix=document.blast.elements['MATRIX_NAME'];
	var gap=document.blast.elements['GAPCOSTS'];
	var word=document.blast.elements['WORD_SIZE'];
	var filter=document.blast.elements['FILTER'];
	var mask=document.blast.elements['MASK'];
	
	var matchPanel=document.getElementById("match");
	var matrixPanel=document.getElementById("matrix");
	var compoPanel=document.getElementById("compo");
	var codePanel=document.getElementById("code");
	var gapPanel=document.getElementById("gap");
	
	if(program=="blastn"){

		updateSelectBox(database,['clostridium'],['clostridium.fasta'],0);
		updateSelectBox(word,[7,11,15],[7,11,15],1);
		matchPanel.className="expand";
		matrixPanel.className="collapse";
		codePanel.className="collapse";
		compoPanel.className="collapse";
		gapPanel.className="expand";
		updateSelectBox(gap,['Existence: 2 Extension: 4','Existence: 0 Extension: 4','Existence: 3 Extension: 3','Existence: 6 Extension: 2','Existence: 5 Extension: 2', 'Existence: 4 Extension: 2','Existence: 2 Extension: 2'],['2 4','0 4','3,3','6 2','5 2','4 2','2 2'],4); 
		filter.checked=false;
		mask.checked=true;
	}
	else if(program=="blastp"){
		updateSelectBox(database,['clostridium','Non-redundant protein sequences (nr)'],['clostridium.fasta','nr'],0);
		updateSelectBox(word,[2,3],[2,3],1);
		matchPanel.className="collapse";
		matrixPanel.className="expand";
		codePanel.className="collapse";
		compoPanel.className="expand";
		gapPanel.className="expand";
		updateSelectBox(gap,['Existence: 9 Extension: 2','Existence: 8 Extension: 2','Existence: 7 Extension: 2','Existence: 12 Extension: 1','Existence: 11 Extension: 1','Existence: 10 Extension 1'],['9 2','8 2','7 2','12 1','11 1','10 1'],4);
		filter.checked=false;
		mask.checked=false;
	}
	else if(program=="blastx"){
		updateSelectBox(database,['clostridium','Non-redundant protein sequences (nr)'],['clostridium.fasta','nr'],0);
		updateSelectBox(word,[2,3],[2,3],1);
		matchPanel.className="collapse";
		matrixPanel.className="expand";
		codePanel.className="expand";
		compoPanel.className="collapse";
		gapPanel.className="expand";
		updateSelectBox(gap,['Existence: 9 Extension: 2','Existence: 8 Extension: 2','Existence: 7 Extension: 2','Existence: 12 Extension: 1','Existence: 11 Extension: 1','Existence: 10 Extension 1'],['9 2','8 2','7 2','12 1','11 1','10 1'],4);
		filter.checked=true;
		mask.checked=false;
	}
	else if(program=="tblastn"){
		updateSelectBox(database,['clostridium'],['clostridium.fasta'],0);
		updateSelectBox(word,[2,3],[2,3],1);
		matchPanel.className="collapse";
		matrixPanel.className="expand";
		codePanel.className="collapse";
		compoPanel.className="expand";
		gapPanel.className="expand";
		updateSelectBox(gap,['Existence: 9 Extension: 2','Existence: 8 Extension: 2','Existence: 7 Extension: 2','Existence: 12 Extension: 1','Existence: 11 Extension: 1','Existence: 10 Extension 1'],['9 2','8 2','7 2','12 1','11 1','10 1'],4);
		filter.checked=true;
		mask.checked=false;
	}
	else if(program=="tblastx"){
		updateSelectBox(database,['clostridium'],['clostridium.fasta'],0);
		updateSelectBox(word,[2,3],[2,3],1);
		matchPanel.className="collapse";
		matrixPanel.className="expand";
		codePanel.className="expand";
		compoPanel.className="collapse";
		gapPanel.className="collapse";
		//updateSelectBox(gap,['Existence: 9 Extension: 2','Existence: 8 Extension: 2','Existence: 7 Extension: 2','Existence: 12 Extension: 1','Existence: 11 Extension: 1','Existence: 10 Extension 1'],['9 2','8 2','7 2','12 1','11 1','10 1'],4);
		filter.checked=true;
		mask.checked=false;
	}
		

}

function updateSelectBox(o,text,value,def){
	o.options.length=0;
	for(var i=0;i<text.length;i++)
		o.options.add(new Option(text[i],value[i]));
	o.selectedIndex=def;
}

function clearSeq(id){
	document.getElementById(id).value='';
}

function checkSeqFormat(o){
	var seq=o.value;
	if(!seq) return;
	seq=seq.replace(/[ \t]+/g,'');//\s includes \n \r in javacript
	var e=seq.indexOf("\n");
	var title='';
	if(e<seq.length){
		title=seq.substring(0, e);
		seqs=seq.substring(e+1,seq.length);

		if(/^>.+/.test(title) && /^[A-IK-NP-Z]+$/gim.test(seqs)){
			var job=document.getElementById('qtitle');
			if(job.value.length==0)
				job.value=title.substring(1);	
			o.value=seq;
		}
		else if(/^[A-IK-NP-Z]+$/gim.test(seq)){
			o.value=">"+o.id+"\n"+seq;	
		}
		else
			alert("invalid sequence");
	}
	
		
	//seq=seq.replace(/\n/g,"");
	//o.value=seq;
}

function init(){
	setPara(document.getElementById("program"));	
}
window.onload=init;
/* init() will run right now, but init is just a function varible assigned to window.onload,
this will be run later after window loading */ 
//window.onload=init();
