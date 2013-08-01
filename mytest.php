<html>
<head>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/d3.js"></script>
<script type="text/javascript" src="js/d3.geom.js"></script>
<script type="text/javascript" src="js/d3.layout.js"></script>

<style type="text/css">
@import url("js/jquery-ui/jquery-ui.css");
.ui-widget {
                        font: 14px Helvetica Neue;
                } 

#blast_plot{
	position:relative;
}
.chart{
	font: 10px sans-serif;
	border:1px solid #888;
}

#info{
	display:none;
	position:absolute;
	z-index:100;
	border:1px solid #555;
	background-color:#eee;
	padding:2px 5px;
	font:12px sans-serif;
}

circle.node {
  			stroke: #fff;
  			stroke-width: 1.5px;
  			fill:blue;
  			cursor: pointer;
		}
		line.link {
  			stroke: #999;
  			stroke-opacity: .6;
		}
		.slide{
			width: 960px;
			margin-bottom: 10px;
		}
</style>

</head>
<body>

              <script type="text/javascript">
//================================================================
var w = 960,
    h = 500,
    fill = d3.scale.category20();
    
var sNodes=[];
    
var vis = d3.select("#chart").append("svg")
    .attr("width", w)
    .attr("height", h);

d3.json("blast2.json", function(json) {

//query seq lies in the center
	json.nodes[0].x=w/2;
	json.nodes[0].y=h/2;
	json.nodes[0].fixed=true;
	
 var force = d3.layout.force()
      .charge(-120)
      .linkDistance(function(d){return 30*json.maxScore/d.score;})
      .linkStrength(function(d){return d.score/json.maxScore;})
      .nodes(json.nodes)
      .links(json.links)
      .size([w, h])
      .start();
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
      .attr("r", function(d,i){return i!=0?5*d.score/json.maxScore:10;})
      //.style("fill", function(d) { return fill(d.group); })
      .on("mouseover",showInfo)
      .on("mouseout",hideInfo)
      .on("click",select)
      .call(force.drag);


  force.on("tick", function() {
    link.attr("x1", function(d) { return d.source.x; })
        .attr("y1", function(d) { return d.source.y; })
        .attr("x2", function(d) { return d.target.x; })
        .attr("y2", function(d) { return d.target.y; });

    node.attr("cx", function(d) { return d.x; })
        .attr("cy", function(d) { return d.y; });
  });


//--------------------------------------------------------------
$("#score").slider({
	min:json.minScore-100,
	max:json.maxScore+100,
	value:json.maxScore,
	slide:function(event,ui){
		d3.select("#score-pan span").text(ui.value);
		node.style("fill",function(d){return d.score>ui.value?"red":"blue";});
		sNodes=[];
		node.each(function(d){if(d.score>ui.value)sNodes.push(d.acc)});
		d3.select("#acc").text(sNodes.toString());
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
		sNodes=[];
		node.each(function(d){if(d.e<ui.value)sNodes.push(d.acc)});
		d3.select("#acc").text(sNodes.toString());
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
		sNodes=[];
		node.each(function(d){if(d.coverage>ui.value)sNodes.push(d.acc)});
		d3.select("#acc").text(sNodes.toString());
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

//===========================================================================


                </script>
                <div id="score-pan" class="slide">score: <span></span><div id="score"></div></div>
                <div id="e-pan" class="slide">e-value: <span></span><div id="e"></div></div>
                <div id="coverage-pan" class="slide">coverage: <span></span><div id="coverage"></div></div>
                <div id="acc"></div>
<body>
</html>
