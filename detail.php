<?php
session_start();
header("Content-type: text/html; charset=utf-8"); 
include("parseini.php");
include("DB.php");
include("template.php");

$parseini = new parseini("clost.ini");
$parseini->setini();

$username = $parseini->getvalue("username");
$ip = $parseini->getvalue("ip");
$pass = $parseini->getvalue("password");
$dbname = $parseini->getvalue("dbname");
 
$db = new DB($ip, $username, $pass, $dbname);

$db->conn();

//select feature_id from children where srcfeature_id=3;

if(isset($_GET['id'])) $id=$_GET['id'];
if(isset($_GET['acc'])){
	$acc=$_GET['acc'];
	$sql="select feature_id from feature where name ='".$acc."'";
	$result=$db->query_withResult($sql);
	$arr=@pg_fetch_array($result,0,PGSQL_ASSOC);
	$id=$arr['feature_id'];
}	

$sql="select * from v_seq_feature vsf, children_loc cl where vsf.feature_id=cl.feature_id and vsf.feature_id in (select feature_id from children_loc where srcfeature_id=".$id.")  order by cl.feature_id";
$result = $db->query_withResult($sql);
$r=array();
for($i=0; $i < pg_num_rows($result);$i++){
    $arr=@pg_fetch_array($result,$i,PGSQL_ASSOC);
    //print_r($arr);
    $id=$arr['feature_id'];
    
    //remove odd data
    if($arr['parent_name']!=$arr['children_name'])
    	$arr['fmin']+=1;
    foreach($arr as $key=>$value){
    	if(strstr($value,"GenBank:") || strstr($value,"-auto") || strstr($value,"inferred from GFF3"))
    		$arr[$key]='';
    	if(!$arr['fmax'])
    		$arr['fmin']='';
    }    
    
    if(array_key_exists($id,$r)){
    	foreach($arr as $key=>$value){
	    	if($r[$id][$key]!=$value && $value && $key!='fmax')
	    		$r[$id][$key].=";".$value;
	   }
    }
    else{
    	$r[$id]=$arr;
    }
 }

//print_r($r); 

$p=array_shift($r);

$acc=$p['parent_name'];
$desc=$p['definition'];
$seq=$p['residues'];
$length=$p['fmax'];
$host=$p['host'];
$organism=$p['organism'];
$strain=$p['strain'];
$isolate=$p['isolate'];
$clone=$p['clone'];
$date=$p['collection_date'];
$country=$p['country'];
$type=$p['mol_type'];
$comment=$p['comment'];

$title=array_shift(explode(";",$desc));
$seq=str_split($seq,60);
//$seq=join("\n",str_split($seq,100));
$subsp=array("Strain"=>$strain,"Isolate"=>$isolate,"Clone"=>$clone);



?>



<?php echoHtmlHead("Detail information"); ?>

<link rel="stylesheet" type="text/css" href="css/detailpage.css" />
<?php
	echo "<script type=\"text/javascript\">
				var id=$id;
				var acc='".$acc."';
				var desc=\"".$title."\";
				var length=$length;
				var seq='".$p['residues']."';\n";
			
			
	$basicInfo='';
		$basicInfo.="<tr class='odd'><th >Accession Number</th><td>$acc</td></tr>";
		$basicInfo.="<tr><th>Description</th><td>$desc</td></tr>";
		$basicInfo.="<tr class='odd'><th>Molecular type</th><td>$type</td></tr>";
		$basicInfo.="<tr><th>Length</th><td>$length</td></tr>";
		$basicInfo.="<tr class='odd'><th>Organism</th><td>$organism</td></tr>";
		$basicInfo.="<tr><th>Collection date</th><td>$date</td></tr>";
		$basicInfo.="<tr class='odd'><th>Region</th><td>$country</td></tr>";
		$basicInfo.="<tr><th>Host</th><td>$host</td></tr>";
		$i=1;
		foreach($subsp as $key=>$value){
			if($value) {
				if($i % 2==1)
					$basicInfo.="<tr class='odd'><th>$key</th><td>$value</td></tr>";
				else
					$basicInfo.="<tr><th>$key</th><td>$value</td></tr>";
				$i++;
			}
		}
		if($comment){
			if($i % 2==1)
				$basicInfo.="<tr class='odd'><th>Comment</th><td>$comment</td></tr>";
			else	$basicInfo.="<tr><th>Comment</th><td>$comment</td></tr>";
			$i++;
		}
	
	$feature="<tr ><th>Structure</th><td id='feat'>";
	  	foreach($r as $c){
			if(!$c['gene'] && !$c['product'] && !$c['definition']) continue;
	  		if($i % 2==1)
	  			$feature.="<div class='odd feat'>";
	  		else
	  			$feature.="<div class='feat'>";
	  		$feature.="<span class='pos'>Location:</span>".$c['fmin']."..".$c['fmax']."<br>";
	  		if($c['gene'])
	  			$feature.="<span class='pos'>Gene:</span>".$c['gene']."<br>";	
	  		elseif($c['children_name'])
	  			$feature.="<span class='pos'>Gene:</span>".$c['children_name']."<br>";
	  		if($c['product'])
	  			$feature.="<span class='pos'>Product:</span>".$c['product']."<br>";
			if($c['definition'])
				$feature.="<span class='pos'>Note:</span>".$c['definition'];
	  		$feature.="</div>";
	  		$i++;
	  }
	  $feature.="</td></tr>";
	 echo "var basicInfo=\"".$basicInfo."\";\n";
	 echo "var feature=\"".$feature."\";";
	echo "</script>\n";
?>
<script type="text/javascript" src="js/detail.js"></script>
<?php echoPageHeader(); ?>

<!-- ================================== main content start ===================================== -->
<div id="content">
	
	<?php
		echo "<h2>$title</h2>\n";
		echo "<table class='t2'>\n";
		echo $basicInfo;
		echo $feature;
	  	echo "<tr><th>Sequence</th><td id='seq'><pre>\n";
		for($i=0;$i<count($seq);$i++){
			$start=$i*60+1;
			echo "<span class='pos'>$start</span>";
			$l=join(" ",str_split($seq[$i],10));
			echo $l."\n";
		}	  
	  echo "</pre></td></tr>\n";
	  echo "</table>\n";
	?>
	

</div>
<div id="sidebar"> 
<h3>Display Options</h3>
	<div class="navcontainer sidebar_box">
	<form name="oneseq" method="post">
	<ul>
	<li>
	<input name="view" id="detail" value="detail" type="radio" checked>
	<label>Detail information</label>	
	<br>
    <input name="view" id="fasta" value="fasta" type="radio">
    <label>FASTA format</label>
    <br>
    <input name="view" id="graph" value="graph" type="radio" disabled>
    <labe>Graph</label>
    <br>
    </li>
    <li>
    <input type="checkbox" name="segment" id="segment" onchange="showSeg()">Show sequence<br>
    <label> from: </label>
    <input type="text" name="sfrom" id="sfrom" value="" class="text" size="5" disabled>
    <label> to: </label>
    <input type="text" name="sto" id="sto" value="" class="text" size="5" disabled>
    </li>
    <li>
		<input type="checkbox" name="rc" id="rc">Show reverse complement    
    </li>
    </ul>
    <input type="button" id="cview" class="button submit" value="Update" onclick="changeView()">
    </form>
    <div class="clr"></div>
	</div>
<h3>Analyze this sequence</h3>

    <div class="navcontainer">
    <ul class="link">
    <li><a onclick="toBlast()">Run BLAST</a></li>
    </ul>
    <form name="toblast" method="post" action="blast.php">
 	<input type="hidden" name="qtitle">   	
	<input type="hidden" name="qs">
    </form>
    </div>
 
</div>


<!-- =================================== main content end ======================================== -->

<?php echoPageFooter(); ?>
