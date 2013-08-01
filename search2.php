<?php
session_start();
include("parseini.php");
include("DB.php");
include("SubPages.php");
include("template.php");
#################prepare SQL statements###################################
//start a new query
if(isset($_POST['fromsearch'])){
	unset($_SESSION['sql']);
	unset($_SESSION['total']);

	isset($_POST['acc'])?$acc=$_POST['acc']:$acc='';
	isset($_POST['countryhead'])?$country=$_POST['countryhead']:$country='';
	isset($_POST['from'])?$timefrom=$_POST['from']:$timefrom='';
	isset($_POST['to'])?$timeto=$_POST['to']:$timeto='';
	isset($_POST['specieshead']) && $_POST['specieshead']?$species=$_POST['specieshead']:$species='';
	isset($_POST['pubmed'])?$pubmed=$_POST['pubmed']:$pubmed='';
	isset($_POST['hosthead'])?$host=$_POST['hosthead']:$host='';
	isset($_POST['genehead'])?$gene=$_POST['genehead']:$gene='';
	isset($_POST['location'])?$location=$_POST['location']:$location='';

	isset($_POST['logicAcc'])?$conAcc=$_POST['logicAcc']:$conAcc='';
	isset($_POST['logicRegion'])?$conRegion=$_POST['logicRegion']:$conRegion='';
	//isset($_POST['logicTime'])?$conTime=$_POST['logicTime']:$conTime='';
	isset($_POST['logicSpecies'])?$conSpecies=$_POST['logicSpecies']:$conSpecies='';
	isset($_POST['logicPubmed'])?$conPubmed=$_POST['logicPubmed']:$conPubmed='';
	isset($_POST['logicHost'])?$conHost=$_POST['logicHost']:$conHost='';
	isset($_POST['logicGene'])?$conGene=$_POST['logicGene']:$conGene='';
	//isset($_POST['logicLocation'])?$conLocation=$_POST['logicLocation']:$conLocation='';

	if(!get_magic_quotes_gpc()){
		$acc=addslashes($acc);
		$country=addslashes($country);
		$species=addslashes($species);
		$pubmed=addslashes($pubmed);
		$host=addslashes($host);
		$gene=addslashes($gene);
	}
	$country?$country=explode("||",$country):$country=array();	
	$species?$species=explode("||",$species):$species=array();
	$host?$host=explode("||",$host):$host=array();
	$gene?$gene=explode("||",$gene):$gene=array();
	
	$acc && $acc="and feature.name ".$conAcc." ilike '%".$acc."%'";
	$pubmed && $pubmed="and pubmed ".$conPubmed." ilike '".$pubmed."'";
	$location && $location="and location = '".$location."'";
	$timefrom && $timefrom="and collection_date >= '".$timefrom."'";
	$timeto && $timeto="and collection_date <= '".$timeto."'";
	count($country)?$country="and country ".$conRegion." in ('".join("','",$country)."')":$country='';
	count($species)?$species="and organism ".$conSpecies." in ('".join("','",$species)."')":$species='';
	count($host)?$host="and host ".$conHost." in ('".join("','",$host)."')":$host='';
	count($gene)?$gene=" gene ".$conGene." in ('".join("','",$gene)."')":$gene='';

	if($gene)
		$gene= "and v.feature_id in (select distinct srcfeature_id  from v_seq_feature, children  where ".$gene." and v_seq_feature.feature_id = children.feature_id)";

	$_SESSION['sql']="$acc $pubmed $location $country $species $host $gene $timefrom $timeto";
	$_SESSION['sql']=substr(trim($_SESSION['sql']),3);
}

if(isset($_POST['fromblast'])){
	unset($_SESSION['sql']);
	unset($_SESSION['total']);
	
	$_SESSION['total']=count($_POST['accs']);
	$_SESSION['sql']="feature.name in ('".implode("','",$_POST['accs'])."')";
}

if(isset($_GET['keyword'])){
	unset($_SESSION['sql']);
        unset($_SESSION['total']);

	$keyword=trim($_GET['keyword']);
	if(!get_magic_quotes_gpc())
		$keyword=addslashes($keyword);
	
	$_SESSION['sql']="(feature.feature_id in (select feature_id from featureprop where value ilike '%".$keyword."%' ) or feature.name ilike '%".$keyword."%' or v.feature_id in ( select srcfeature_id from featureprop,children  where value ilike '%".$keyword."%' and children.feature_id=featureprop.feature_id ))";
}

############################# connect database ####################################3

$parseini = new parseini("clost.ini");
$parseini->setini();

$username = $parseini->getvalue("username");
$ip = $parseini->getvalue("ip");
$pass = $parseini->getvalue("password");
$dbname = $parseini->getvalue("dbname");

$db = new DB($ip, $username, $pass, $dbname);

$db->conn();

########################## query #################################################
$sql=$_SESSION['sql'];

//$_SESSION['total']: total records number
if(!isset($_SESSION['total'])){
	$sql1 = "select count(*) as sum from v_seq_feature v ,feature where feature.feature_id=v.feature_id and feature.seqlen is not null and feature.type_id=114 and ".$sql;
	$result = $db->query_withResult($sql1);
	$arr=@pg_fetch_array($result,0,PGSQL_ASSOC);
	$_SESSION['total']=$arr['sum'];
}
isset($_GET['page'])?$pageNow=$_GET['page']:$pageNow=1;
$totalRecords=$_SESSION['total'];
$recordsEachPage=10;
$offset=($pageNow-1)*$recordsEachPage;

$sql = "select feature.name, definition,mol_type,v.feature_id,seqlen from v_seq_feature v ,feature where feature.feature_id=v.feature_id and feature.seqlen is not null and feature.type_id=114 and ".$sql." limit $recordsEachPage offset $offset";
		//echo $sql;
$result = $db->query_withResult($sql);

############################ display results ######################################

?>

<?php echoHtmlHead("results list"); ?>
<!-- ##############custom style and script################# -->

<link rel="stylesheet" type="text/css" href="css/search_results2.css" />

<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAA4Fjrdh-WyPrBRw4M-XIDihSWPm5vnRWdszAQPNjf-A3BBFrVghRf7oL-cNXGI8QExeYIhLj1TbD75A" type="text/javascript"></script>
<script type="text/javascript" src="js/mf.js"></script>
<?php
	// transfer number of results to javascript
	echo "<script type=\"text/javascript\">\n
				var all=".$_SESSION['total'].";\n
			</script>\n";
?>
<script type="text/javascript" src="js/search.js"></script>
<script type="text/javascript" src="js/pos.js"></script>
<!-- ####################################################### -->

<?php echoPageHeader(); ?>
<!-- ================================== main content start ===================================== -->


<div id="main">

<div class="handle">
	
	<span class="button" onclick="openPopDiv('savePanel1')">Save to File</span>
	<span class="button" onclick="openPopDiv('mapPanel1')">Present on Map</span>
	<div id="savePanel1" class="popPanel savePanel">
	
		<form name="tofile1" action="tofile.php" method="post">
		<table>
			<tr>
				<th>Fields to be saved</th>
				<td>
					<select id="field" name="field[]" class="field" size=3 multiple onchange="">
					<option value="Accession Number:name" selected>Accession Number</option>
					<option value="Description:definition">Description</option>
					<option value="Sequence Length:seqlen">Sequence Length</option>
					<option value="Region:country">Region</option>
					<option value="Date:collection_date">Date</option>
					<option value="Host:host">Host</option>
					<option value="Strain:strain">Strain</option>
					<option value="Isolate:isolate">Isolate</option>
					<option value="Clone:clone">Clone</option>
					<option value="Sequence:residues">Sequence</option>
					</select>
				</td>		
						
			</tr>
			<tr>
				<th>File format</th>
				<td>
					<input type="radio" name="format" value='fasta' checked>FASTA file<br>
					<input type="radio" name="format" value='tab'>TAB file
				</td>		
			</tr>
			<tr>
				<th>File name</th>
				<td><input type="text" id="filename" name="filename"></td>					
			</tr>	
			</table>
			<input type="hidden" name="ids">
			<input type="button" class="button submit" value="Save" onclick="toFile('tofile1')">	
		</form>		
	</div>
	<div id="mapPanel1" class="popPanel mapPanel">
		
		<div id="map1" style="width:960px;height:480px"></div>	
	</div>
</div>

<div class="r_page">
<?php $subPages=new SubPages($recordsEachPage,$totalRecords,$pageNow,4,"search2.php?page=",2);?>
</div>
<div id="r_title"><h3>Results: 
	<?php 
		if($offset+$recordsEachPage<$totalRecords)
			echo ($offset+1)." to ".($offset+$recordsEachPage)." of ".$totalRecords;
		else
			echo ($offset+1)." to ".$totalRecords." of ".$totalRecords;			
	?> 
</h3>
</div>

<div id="r_main">

<form name="rl" method="post">
<table id="case_info" class="t1">
	<tr><td colspan="2"><input type="checkbox" onclick="selectBox(this,'rl')">Select all</td></tr>
  <?php
  					

		for($i=0; $i < pg_num_rows($result);$i++){
    		$arr=@pg_fetch_array($result,$i,PGSQL_ASSOC);

    		echo "<tr>
           	<td><input type='checkbox' id='id' name='id[]' value='".$arr["feature_id"]."'><span class='cb'>".($i+$offset+1)."</span></td>
          	<td><a href='detail.php?id=".$arr['feature_id']."' onclick='loading()'>".$arr["definition"]."</a><br>".$arr['seqlen']."bp ".$arr['mol_type'].", Accession number: ".$arr['name']."</td></tr>\n";
 		}
  		
  ?>

</table>
</form>
</div>
<div class="handle">
	<span class="button" onclick="openPopDiv('savePanel2')">Save to File</span>
	<span class="button" onclick="openPopDiv('mapPanel2')">Present on Map</span>
	<div id="savePanel2" class="popPanel savePanel">
		<form name="tofile2" action="tofile.php" method="post">
		<table>
			<tr>
				<th>Fields to be saved</th>
				<td>
					<select id="field" name="field[]" class="field" size=3 multiple onchange="">
					<option value="Accession Number:name" selected>Accession Number</option>
					<option value="Description:definition">Description</option>
					<option value="Sequence Length:seqlen">Sequence Length</option>
					<option value="Region:country">Region</option>
					<option value="Date:collection_date">Date</option>
					<option value="Host:host">Host</option>
					<option value="Strain:strain">Strain</option>
					<option value="Isolate:isolate">Isolate</option>
					<option value="Clone:clone">Clone</option>
					<option value="Sequence:residues">Sequence</option>
					</select>
				</td>		
						
			</tr>
			<tr>
				<th>File format</th>
				<td>
					<input type="radio" name="format" value='fasta' checked>FASTA file<br>
					<input type="radio" name="format" value='tab'>TAB file
				</td>		
			</tr>
			<tr>
				<th>File name</th>
				<td><input type="text" id="filename" name="filename"></td>					
			</tr>	
			</table>
			<input type="hidden" name="ids">
			<input type="button" class="button submit" value="Save" onclick="toFile('tofile2')">	
		</form>		
	</div>
	<div id="mapPanel2" class="popPanel mapPanel">
		<div id="map2" style="width:960px;height:480px"></div>	
	</div>
</div>
<div class="r_page">
<?php $subPages=new SubPages($recordsEachPage,$totalRecords,$pageNow,4,"search2.php?page=",2);?>
</div>
<div class="clr"> </div>
 </div>
<!-- =================================== main content end ======================================== -->

<?php echoPageFooter(); ?>

