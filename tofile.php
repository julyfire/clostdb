<?php
session_start();
############################connect database ####################################3
include("parseini.php");
include("DB.php");
$parseini = new parseini("clost.ini");
$parseini->setini();

$username = $parseini->getvalue("username");
$ip = $parseini->getvalue("ip");
$pass = $parseini->getvalue("password");
$dbname = $parseini->getvalue("dbname");

$db = new DB($ip, $username, $pass, $dbname);

$db->conn();

#################################### produce results ##########################################

$fields=$_POST['field'];
$item=array();
foreach($fields as $field){
	$p=explode(":",$field);
	$item[$p[0]]=$p[1];
}
$format=$_POST['format'];
$filename=$_POST['filename'];
if(!$filename) $filename="sequence";
$ids=$_POST['ids'];
if(!$ids)
	$sql = "select * from v_seq_feature v ,feature where feature.feature_id=v.feature_id and feature.seqlen is not null and feature.type_id=114 and ".$_SESSION['sql'];
else
	$sql="select * from v_seq_feature v ,feature where feature.feature_id=v.feature_id and feature.seqlen is not null and feature.type_id=114 and ".$_SESSION['sql']." and v.feature_id in (".$ids.")";

$result = $db->query_withResult($sql);

PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
$tempfile="./temp/".date("YmdHis").mt_rand(1000,9999);
$fp=fopen($tempfile,'w');

if($format=='fasta'){
	for($i=0; $i < pg_num_rows($result);$i++){
			$title='>';
			$seq='';
    		$arr=@pg_fetch_array($result,$i,PGSQL_ASSOC);
			foreach($item as $key=>$value){
				if($value && $value!='residues') 
					$title.=$arr[$value]."|";
			}
			$title=rtrim($title,"|");
			$seq=$arr['residues'];
			fwrite($fp,"$title\n$seq\n");
	}
}
elseif($format=='tab'){
	$title='';
	foreach($item as $key=>$value)
		$title.=$key."\t";		
	$title=rtrim($title,"\t");
	fwrite($fp,$title."\n");
	
	for($i=0; $i < pg_num_rows($result);$i++){
		$arr=@pg_fetch_array($result,$i,PGSQL_ASSOC);
		$line='';
		foreach($item as $key=>$value){
			$arr[$value]=preg_replace("/\t/"," ",$arr[$value]);
			$line.=$arr[$value]."\t";
		}
		$line=rtrim($line,"\t");
		fwrite($fp, $line."\n");
	}
}
fclose($fp);

############################# download file #####################3

    header('Content-Type: application/octetstream');   
    header('Expires: '.gmdate('D, d M Y H:i:s').' GMT');   
    header('Content-Transfer-Encoding: binary');   
    header('Content-Length: '.filesize($tempfile));      
    header('Content-Disposition: attachment; filename="'.$filename.'.'.$format.'"');  
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');   
    header('Pragma: public');   

    @readfile($tempfile);  
    
    unlink($tempfile);

?>
