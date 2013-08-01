<?php
//parse blast results
//by weibo 2012-1-17

function blast2json($blast){
	$json="{\"qseq\":\"".$blast['query_seq_name']."\",".
		"\"length\":".$blast['query_seq_length'].",".
		"\"dbname\":\"".$blast['db_name']."\",".
		"\"dbdesc\":\"".$blast['db_desc']."\",".
		"\"hit\":[";

	$hits=$blast['hit'];
	foreach($hits as $hit){
		$json.="{\"acc\":\"".$hit['acc']."\",".
			"\"desc\":\"".$hit['desc']."\",".
			"\"length\":".$hit['length'].",".
			"\"hsp\":[";

		$hsps=$hit["hsp"];
		foreach($hsps as $hsp){
			$json.="{\"score\":".$hsp['score'].",".
				"\"e\":".$hsp['e'].",".
				"\"identity\":\"".$hsp['identity']."\",".
				"\"strand\":\"".$hsp['strand']."\",".
				"\"qstart\":".$hsp['qstart'].",".
				"\"sstart\":".$hsp['sstart'].",".
				"\"qend\":".$hsp['qend'].",".
				"\"send\":".$hsp['send']."},";
		}
		$json=rtrim($json,",");
		$json.="]},";
	}
	$json=rtrim($json,",");
	$json.="]}";
	return $json;
}

function plotdata($blast,$num){

	$json='[';

	$hits=$blast['hit'];

	$i=0;

	foreach($hits as $hit){

		if(++$i>$num) break;

		$json.="{\"acc\":\"".$hit['acc']."\",".

			"\"desc\":\"".$hit['desc']."\",".

			"\"length\":".$hit['length'].",";

		//--------------------------------------------------

			$json.="\"hsp\":[";

			$seeds=array();

			foreach($hit['hsp'] as $hsp){

				array_push($seeds,array($hsp['qstart'],$hsp['qend']));

			}

			$cover=tile($seeds);

//print_r($cover);



			foreach($cover as $s){

				foreach($hit['hsp'] as $hsp){

					if($hsp['qstart']>=$s[0] && $hsp['qend']<=$s[1]){

						$json.="{\"score\":".$hsp['score'].",".

							"\"e\":".$hsp['e'].",".

							"\"identity\":\"".$hsp['identity']."\",".

							"\"strand\":\"".$hsp['strand']."\",".

							"\"qstart\":".$s[0].",".

							"\"sstart\":".$hsp['sstart'].",".

							"\"qend\":".$s[1].",".

							"\"send\":".$hsp['send']."},";

						break;

					}

				}

			}

			$json=rtrim($json,","); 

			$json.="]";

		//-----------------------------------------------

		$json.="},";

	}

	$json=rtrim($json,",");

	$json.="]";

	return $json;

}		

function plotdata2($blast,$num){

	$json='{"nodes":[{"acc":"query sequence","desc":"'.$blast['query_seq_name'].'","length":'.$blast['query_seq_length'].'},';

	$hits=$blast['hit'];

	$i=0;

	foreach($hits as $hit){

		if(++$i>$num) break;

		$json.="{\"acc\":\"".$hit['acc']."\",".

			"\"desc\":\"".$hit['desc']."\",".

			"\"length\":".$hit['length'].",";
//-------------------------------------------------------
		
		$coverage=round(coverage($hit)/$blast['query_seq_length']*100,2);


		$json.="\"score\":".$hit['hsp'][0]['score'].",".

			"\"e\":".$hit['hsp'][0]['e'].",".

			"\"coverage\":".$coverage."},";
//---------------------------------------------------------

	}

	$json=rtrim($json,",");

	$json.="],";

	$json.='"links":[';

	$i=1;

	foreach($hits as $hit){

		if($i>$num) break;

		$hsp=$hit['hsp'][0];

		$json.="{\"source\":$i,\"target\":0,\"score\":".$hsp['score']."},";

		$i++;

	}

	$json=rtrim($json,",");

	$json.="],";
	$last=$num<count($hits)?$num:count($hits);

	$json.="\"maxScore\":".$hits[0]['hsp'][0]['score'].",";

	$json.="\"minScore\":".$hits[$last-1]['hsp'][0]['score'].",";
	$json.="\"maxE\":".$hits[$last-1]['hsp'][0]['e']."}";

	

	return $json;

}

function parseBlast($blastfile){
	$f=fopen($blastfile,"r");

	$hits=array();
	$blast=array();

	while(!feof($f)){
		$line=fgets($f);
	
		//query seq name
		if(strpos($line,"Query=")===0){
			$blast['query_seq_name']=trim(substr($line,6));
		}
		//seq length
		elseif(preg_match("/^ +\((\d+) letters\)/",$line,$m)) {
			$blast['query_seq_length']=$m[1];
		}
		//database info
		elseif(strpos($line,"Database")===0){
			$blast['db_name']=trim(substr($line,10),"\n");
			$line=rtrim(fgets($f));
			$blast['db_desc']=trim($line);
		}
		
		
		if(substr($line,0,1)==">"){
			$result=$line;
			$j=0;
			$line=fgets($f);
			while(strpos($line,"Database")<=0){						
				if(substr($line,0,1)==">"){				
					$hits[$j]=$result;
					$result='';
					$j++;
				}			
				$result.=$line;
				$line=fgets($f);
			}
			$hits[$j]=$result;
		}
	
	}
	fclose($f);
	
	$blast['hit']=array();
	$blast['result']=array();
	foreach($hits as $hit){
		array_push($blast['hit'],parseHit($hit));
		array_push($blast['result'],$hit);
	}
	return $blast;
}


function parseHit($hit){
	preg_match("/^>.+?\|(.+)\.\d\|(.+?)Length\s+=\s+(\d+)\n/ms",substr($hit,0,1000),$m);	
	$entry=array();
	$entry['acc']=$m[1];
	$entry['desc']=trim(preg_replace("/\s+|\n|\r/"," ",$m[2]));
	$entry['length']=$m[3];
	$entry['hsp']=array();

	$hsps=explode("Score",$hit);
	for($h=1;$h<count($hsps);$h++){
		$hsp=array();
		preg_match("/^\s+=\s+([^\s]+)\s+bits.+?Expect\s+=\s+([^\s]+).+Identities[^\(]+\(([^\)]+)\).+?Strand\s+=\s+(.*?)\n/ms",$hsps[$h],$m);
		$hsp['score']=$m[1];
		$hsp['e']=$m[2];
		if($hsp['e']{0}=='e') $hsp['e']="1".$hsp['e'];
		$hsp['identity']=$m[3];
		$hsp['strand']=$m[4];

		$s=explode("Query: ",$hsps[$h]);
		preg_match("/^(\d+).*Sbjct: (\d+).*/ms",$s[1],$m);
		$hsp['qstart']=$m[1];
		$hsp['sstart']=$m[2];
		preg_match("/.+?(\d+)\n.+?(\d+)\n.*/ms",$s[count($s)-1],$m);
		$hsp['qend']=$m[1];
		$hsp['send']=$m[2];
		array_push($entry['hsp'],$hsp);
	}
	return $entry;
}	

function coverage($hit){
	$seeds=array();
	foreach($hit['hsp'] as $hsp){
		array_push($seeds,array($hsp['qstart'],$hsp['qend']));
        }
	$coverage=0;
	foreach(tile($seeds) as $seed){
		$coverage+=$seed[1]-$seed[0]+1;
	}
	return $coverage;
}

function tile($seeds){

	$cover=array();

	foreach(qsort($seeds,0) as $seed){	

        	if(count($cover)==0){array_push($cover,$seed);continue;}

		$a=$seed[0];

		$b=$seed[1];

		$isnew=0;

        	for($i=0;$i<count($cover);$i++){

                	if($a<$cover[$i][0] && $cover[$i][0]<$b){ $cover[$i][0]=$a;}

                	if($a<$cover[$i][1] && $cover[$i][1]<$b){ $cover[$i][1]=$b;}

                	if($a>$cover[$i][1] || $cover[$i][0]>$b){ $isnew=1;}

			else $isnew=0;

        	}

		if($isnew==1)array_push($cover,$seed);

	}

	return $cover;

}



function qsort($arr,$att)

       {

           $len= count($arr);

           if($len <= 1) {

               return $arr;

           }

           $key = $arr[0];

           $left_arr= array();

           $right_arr= array();

           

           for($i=1; $i<$len; $i++){

               if($arr[$i][$att] <= $key[$att]){

                   $left_arr[] = $arr[$i];

               } else {

                   $right_arr[] = $arr[$i];

               }

           }

           

           $left_arr= qsort($left_arr,$att);

           $right_arr= qsort($right_arr,$att);

           return array_merge($left_arr, array($key), $right_arr);

       }	
?>

