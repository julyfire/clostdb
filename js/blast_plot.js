function showPlot(jfile,canvas){
	var request = false;
   try {
     request = new XMLHttpRequest();
   } catch (trymicrosoft) {
     try {
       request = new ActiveXObject("Msxml2.XMLHTTP");
     } catch (othermicrosoft) {
       try {
         request = new ActiveXObject("Microsoft.XMLHTTP");
       } catch (failed) {
         request = false;
       }  
     }
   }
   if (!request)
     alert("Error initializing XMLHttpRequest!");
       
   var url = "getjson.php?json=" + jfile;
   request.open("GET", url, true);
   request.onreadystatechange = function(){
   	if (request.readyState == 4)
         if (request.status == 200){ 
         	 //document.write(request.responseText);      	
         	var data = json_parse(request.responseText);
         	draw(data,canvas);
         }
       	else if (request.status == 404)
         	alert("Request URL does not exist");
       	else
         	alert("Error: status code is " + request.status);
	};       	
   request.send(null);
}

function showPlot2(jfile,canvas){
	var w = 900,
   	    h = 500,
            fill = d3.scale.category20();
    
	//var sNodes=[];
    
	var vis = d3.select(canvas).append("svg")
    		.attr("width", w)
    		.attr("height", h);

	d3.json(jfile, function(json) {

		//query seq lies in the center
			json.nodes[0].x=w/2;
			json.nodes[0].y=h/2;
			json.nodes[0].fixed=true;
	
 		var force = d3.layout.force()
      			.charge(-120)
      			.linkDistance(function(d){return 30*json.maxScore/d.score;})
      			.linkStrength(function(d){return d.score/json.maxScore;})
      			.size([w, h]);
		/*
  		var queryNode=vis.selectAll("circle.query")
  			.data(json.query)
  			.enter().append("circle")
  			.attr("class","query")
  			.attr("cx",w/2)
  			.attr("cy",h/2)
  			.attr("r",10);
		*/
  		var link = vis.selectAll("line.link")
      			.data(json.links)
    			.enter().append("line")
      			.attr("class", "link")
      			.style("stroke-width", function(d) { return 3*d.score/json.maxScore; })
      			.attr("x1", function(d) { return d.source.x; })
      			.attr("y1", function(d) { return d.source.y; })
      			.attr("x2", function(d) { return d.target.x; })
      			.attr("y2", function(d) { return d.target.y; });

  		var node = vis.selectAll("circle.node")
      			.data(json.nodes)
    			.enter().append("circle")
      			.attr("class", "node")
      			.attr("cx", function(d) { return d.x; })
      			.attr("cy", function(d) { return d.y; })
      			.attr("r", function(d){return 5*d.score/json.maxScore;})
      			//.style("fill", function(d) { return fill(d.group); })
      			.on("mouseover",showInfo)
      			.on("mouseout",hideInfo)
      			.on("click",function(d){window.location.hash=d.acc})
      			.call(force.drag);
		vis.select("circle.node").style("fill","red").attr("r",10);

		force.nodes(json.nodes)
			.links(json.links);

  		force.on("tick", function() {
			node.attr("cx", function(d) { return d.x=Math.max(10,Math.min(w-10,d.x)); })
        		.attr("cy", function(d) { return d.y=Math.max(10,Math.min(h-10,d.y)); });
			
    			link.attr("x1", function(d) { return d.source.x; })
        		.attr("y1", function(d) { return d.source.y; })
        		.attr("x2", function(d) { return d.target.x; })
        		.attr("y2", function(d) { return d.target.y; });

    			
  		});

		force.start();


		//--------------------------------------------------------------
		$("#score").slider({
			min:json.minScore-100,
			max:json.maxScore+100,
			value:json.maxScore,
			slide:function(event,ui){
				d3.select("#score-pan span").text(ui.value);
				node.style("fill",function(d){return d.score>ui.value?"red":"blue";});
				vis.select("circle.node").style("fill","red");
				//sNodes=[];
				node.each(function(d){
					var oo=document.getElementById(d.acc);
					if(d.score>ui.value) oo.checked=true;
					else if(oo) oo.checked=false;
					//d.score>ui.value?d3.select("#"+d.acc).property(checked,true):d3.select("#"+d.acc).property(checked,false);
				});
				//d3.select("#acc").text(sNodes.toString());
			}
		});
		$("#e").slider({
			min:-1,
			max:json.maxE+1,
			step:(json.maxE+2)/1000,
			value:0,
			slide:function(event,ui){
				d3.select("#e-pan span").text(ui.value);
				node.style("fill",function(d){return d.e<ui.value?"red":"blue";});
				vis.select("circle.node").style("fill","red");
				//sNodes=[];
				node.each(function(d){
					var oo=document.getElementById(d.acc);
					if(d.e<ui.value)
						//sNodes.push(d.acc);
						oo.checked=true;
					else if(oo) oo.checked=false;});
				//d3.select("#acc").text(sNodes.toString());
			}
		});
		$("#coverage").slider({
			min:0,
			max:100,
			step:0.1,
			value:100,
			slide:function(event,ui){
				d3.select("#coverage-pan span").text(ui.value+"%");
				node.style("fill",function(d){return d.coverage>ui.value?"red":"blue";});
				vis.select("circle.node").style("fill","red");
				//sNodes=[];
				node.each(function(d){
					var oo=document.getElementById(d.acc);
					if(d.coverage>ui.value) oo.checked=true;
					else if(oo) oo.checked=false;});
			}
		});
		//-----------------------------------------------------------

	});



	var showInfo=function(d){		
		var infobox=document.getElementById("info");
		infobox.style.display="block";
		infobox.style.left=(d3.event.pageX+5)+"px";
		infobox.style.top=(d3.event.pageY+5)+"px";
		infobox.innerHTML=d.acc+" "+d.desc+" S="+d.score+" E="+d.e+" C="+d.coverage+"%";		
	}
	var hideInfo=function(d){
		var infobox=document.getElementById("info");
		infobox.style.display="none";
	}
	var select=function(d){
		d3.select(this).style("fill","yellow");
	}	
}

function draw(json,canvas){
	var mom;
	var p=[40,70,20,40],
		 barh=10,
		 w=860-p[1]-p[3],
		 h=json.length*barh;
		 
	//create canvas
	var c=box();
		setAttr(c,"class","chart");
		setStyle(c,"width",(w+p[1]+p[3])+"px");
		setStyle(c,"height",(h+p[0]+p[2])+"px");
		setStyle(canvas,"height",(h+p[0]+p[2])+"px");
		canvas.appendChild(c);
	var chart=box();
		setStyle(chart,"left",p[3]+"px");
		setStyle(chart,"top",p[0]+"px");
		c.appendChild(chart);
		
	var space=10;
	
	var x=scale({min:1,max:seqlen},{min:space,max:w});

	var drawBar=function(d,h){
		var bar=box();
			setStyle(bar,"width",(x(h.qend)-x(h.qstart))+"px");
			setStyle(bar,"height",(barh-3)+"px");
			setStyle(bar,"left",x(h.qstart)+"px");
			setStyle(bar,"top",(i*barh+space)+"px");
			setStyle(bar,"backgroundColor",barColor(h));
			setStyle(bar,"border","1px solid white");
			setStyle(bar,"cursor","pointer");
			addEvent(bar,"mouseover",function(e){
				mom=getStyle(bar,"backgroundColor");//save the current fill style with mom
				setStyle(bar,"backgroundColor","yellow");//highlight the current bar
				var infobox=document.getElementById("info");
				infobox.style.display="block";
				pos=getMousePos(e);
				infobox.style.left=(pos.x+5)+"px";
				infobox.style.top=(pos.y+5)+"px";
				infobox.innerHTML=d.acc+": "+d.desc+". S="+h.score+", E="+h.e;	
			});
			addEvent(bar,"mouseout",function(e){
				setStyle(bar,"backgroundColor",mom);
				var infobox=document.getElementById("info");
				infobox.style.display="none";
			});
			addEvent(bar,"click",function(){window.location.hash=d.acc});
		
		return bar;
	};
	for(var i=0;i<json.length;i++){
		for(var j=0;j<json[i].hsp.length;j++)
			chart.appendChild(drawBar(json[i],json[i].hsp[j]));
		
		
	}
	
	//scale
	var xaxis=box();
		setStyle(xaxis,"width",(w-space)+"px");
		setStyle(xaxis,"height","10px");
		setStyle(xaxis,"left",space+"px");
		setStyle(xaxis,"top","-10px");
		setStyle(xaxis,"backgroundColor","red");
		chart.appendChild(xaxis);
		
	var tk=tick(seqlen,5);
	var drawTick=function(t){
		var m=text(String(t));
			setStyle(m,"width","30px");
			//setStyle(m,"height","2px");
			setStyle(m,"left",(x(t)-15)+"px");
			setStyle(m,"top","-25px");
			setStyle(m,"textAlign","center");
			//setStyle(m,"backgroundColor","blue");
			chart.appendChild(m);
		var r=box()
			setStyle(r,"width","1px");
			setStyle(r,"height","5px");
			setStyle(r,"left",x(t)+"px");
			setStyle(r,"top","-15px");
			setStyle(r,"backgroundColor","black");
			chart.appendChild(r);	
	};
	for(var i=0;i<tk.length;i++){
		drawTick(tk[i]);
	}
	
	//colorbar
	var colorSet=["red","pink","green","blue","black"];
	var drawColorbar=function(t){
		var c=box();
			setStyle(c,"width","20px");
			setStyle(c,"height",(h/5)+"px");
			setStyle(c,"left",(w+space)+"px");
			setStyle(c,"top",(i*h/5+space)+"px");
			setStyle(c,"backgroundColor",t);
			chart.appendChild(c);
	}
	var scoreSet=[200,80,50,40,0];
	var drawColorLable=function(s){
		var c=text(String(s));
			setStyle(c,"left",(w+space+20)+"px");
			setStyle(c,"top",((i+1)*h/5+space-5)+"px");
			chart.appendChild(c);
	}
	for(var i=0;i<colorSet.length;i++){
		drawColorbar(colorSet[i]);
		drawColorLable(scoreSet[i]);
	}
	
	//lable
	var l=text("Query");
		setStyle(l,"left","-25px");
		setStyle(l,"top","-12px");
		chart.appendChild(l);
		l=text("Score");
		setStyle(l,"left",(w+space)+"px");
		setStyle(l,"top","-12px");
		chart.appendChild(l);
	
}

function barColor(d){
		if(d.score>=200) return "red";
		else if(d.score<200 && d.score>=80) return "pink";
		else if(d.score<80 && d.score>=50) return "green";
		else if(d.score<50 && d.score>=40) return "blue";
		else return "black";
}

/////////////////////////////////////////////
/* draw box
	create by wb
	2012-1-18
*/ 
function box(){
	var b=document.createElement("div");
	setStyle(b,"position","absolute");
	return b;
}

function text(s){
	var c=box();
	c.appendChild(document.createTextNode(s));
	return c;
}

//linear scale
function scale(s,o){
	return function(v){return (o.max-o.min)*(v-s.min)/(s.max-s.min)+o.min;};
}

function setStyle(o,name,value){
	//o.style.setProperty(name,value,"") || eval("o.style."+name+"=\""+value+"\"");
	eval("o.style."+name+"=\""+value+"\"");
}
function getStyle(o,name){
	//return (o.style.getPropertyValue(name) || eval("o.style."+name));
	return eval("o.style."+name);
}
function setAttr(o,name,value){
	o.setAttribute(name,value);
}
function getAttr(o,name){
	return o.getAttribute(name);
}	
function addEvent(o,name,action){
	if(o.addEventListener){
		o.addEventListener(name,action,false);
	}
	else if(o.attachEvent){
		o.attachEvent("on"+name,action);
	}
	else{
		o["on"+name]=action;
	}
}
function tick(range,tickNum){
	var a=Math.floor(range/tickNum);
	//return a/Math.pow(10,String(a).length)
	var u=Math.round(a/10)*10;
	var t=[];
	for(var i=0;i<=range;i+=u)
		t.push(i);
	t[0]+=1;
	//t.push(range);
	return t;
}
function getMousePos(e){
	var posx = 0, posy = 0,
   e = e || window.event;
           
   if (e.pageX || e.pageY) {
      posx = e.pageX;
      posy = e.pageY;
   } 
   else if (e.clientX || e.clientY) {
      posx = e.clientX + document.documentElement.scrollLeft + document.body.scrollLeft;
      posy = e.clientY + document.documentElement.scrollTop + document.body.scrollTop;
   }
	
	return {x:posx, y:posy};
	
}
