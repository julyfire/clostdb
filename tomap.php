<?php
session_start();
header('Content-Type:text/xml');

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

$ids=$_GET['id'];
if(!$ids)
	$sql = "select * from v_seq_feature v ,feature where feature.feature_id=v.feature_id and feature.seqlen is not null and feature.type_id=114 and ".$_SESSION['sql'];
else
	$sql="select * from v_seq_feature v ,feature where feature.feature_id=v.feature_id and feature.seqlen is not null and feature.type_id=114 and ".$_SESSION['sql']." and v.feature_id in (".$ids.")";

$result = $db->query_withResult($sql);

$count=pg_num_rows($result);

echo '<?xml version="1.0" encoding="utf-8"?>';
echo '<entries ';

//check the number of result
if($count==0){
        echo 'state="0">';
}else{
        echo 'state="'.$count.'">';
}


$locations=array();
for($i=0;$i<$count;$i++){
        $arr=@pg_fetch_array($result,$i,PGSQL_ASSOC);
        $id=$arr['feature_id'];
        $key=$arr['location'];
        $keys=array_keys($locations); //fetch all the keys of hash $locations
        if(in_array($key,$keys)){ //check if current key has existed in the hash 
                $locations[$key].=",".$id;
		continue;
        }else{
                $locations[$key]=$id;
                
                $strain=$arr['strain'];
                $host=$arr['host'];
                $country=$arr['country'];
                $date=$arr['collect_date'];
                if(preg_match("/POINT\((-?\d+\.?\d*) (-?\d+\.?\d*)\)/",$key,$latlng)){

                    $longitude=$latlng[1];

                    $latitude=$latlng[2];

                }
        
       		 echo '<entry>';

       		 echo '<longitude>'.$longitude.'</longitude>';
       		 echo '<latitude>'.$latitude.'</latitude>';
       		 echo '<strain>'.$strain.'</strain>';
       		 echo '<host>'.$host.'</host>';
       		 echo '<country>'.$country.'</country>';
       		 echo '<date>'.$date.'</date>';
       		 echo '<link>'.$key.'</link>';

       		 echo '</entry>';
	     }
}
echo '</entries>';

?>
