<?php
require("MySSH.php");

$errorInfo='';
$blastout='';

/*######################### prepare parameters ##########################*/
isset($_POST['PROGRAM'])?$program=$_POST['PROGRAM']:$program='';
isset($_POST['DATABASE'])?$database=$_POST['DATABASE']:$database='';
isset($_POST['QUERY'])?$querySeq=$_POST['QUERY']:$querySeq='';
isset($_POST['SUBJECT'])?$subjectSeq=$_POST['SUBJECT']:$subjectSeq='';
isset($_POST['JOB_TITLE'])?$jobtitle=$_POST['JOB_TITLE']:$jobtitle='';
isset($_POST['MAX_NUM_SEQ'])?$hitNumber=$_POST['MAX_NUM_SEQ']:$hitNumber=0;
isset($_POST['EXPECT'])?$evalue=$_POST['EXPECT']:$evalue='';
isset($_POST['GAPCOSTS'])?$gap=explode(" ",$_POST['GAPCOSTS']):$gap=array();
$gapOpen=$gap[0];
$gapExtend=$gap[1];
isset($_POST['MATCH_SCORES'])?$mismat=explode(",",$_POST['MATCH_SCORES']):$mismat=array();
$mismatch=$mismat[0];
$match=$mismat[1];
isset($_POST['WORD_SIZE'])?$wordsize=$_POST['WORD_SIZE']:$wordsize=0;
isset($_POST['MATRIX_NAME'])?$matrix=$_POST['MATRIX_NAME']:$matrix='';
isset($_POST['GENETIC_CODE'])?$code=$_POST['GENETIC_CODE']:$code=0;
isset($_POST['COMPOSITION_BASED_STATISTICS'])?$compo=$_POST['COMPOSITION_BASED_STATISTICS']:$compo=0;
isset($_POST['FILTER'])?$filter=$_POST['FILTER']:$filter='F';
isset($_POST['MASK'])?$mask=$_POST['MASK']:$mask='';
isset($_POST['BL2SEQ'])?$bl2seq=$_POST['BL2SEQ']:$bl2seq='';

// check uploaded seqs file
$title='';
$seq='';
if(!$querySeq){
	$qfile=$_FILES['QUERYFILE'];
	if(!$qfile['name'])
		$errorInfo.="Error: can not find query sequence!";
	else{
		$errorInfo.=checkFasta($qfile,&$title,&$seq);	
	}	
	if(!$errorInfo){
		if(!$jobtitle) $jobtitle=$title;
		$querySeq=$seq;
	}
}

if($bl2seq && !$subjectSeq){
	$qfile=$_FILES['SUBJECTFILE'];
	if(!$qfile['name'])
		$errorInfo.="Error: can not find subject sequence!";
	else{
		$errorInfo.=checkFasta($qfile,&$title,&$seq);
	}	
	if(!$errorInfo)
		$subjectSeq=$seq;
}

$jobtitle=preg_replace("/\s+/","",$jobtitle);

/*################################# run BLAST #################################3*/

$hostname="159.226.126.178";
$port=22;
$username="clost";
$password="c1o5t";


$dir="cluster/jobs";
PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
$basename=$jobtitle."_".date("YmdHis").mt_rand(1000,9999);

$qfile=$dir."/".$basename."_q.fasta";
$sfile=$dir."/".$basename."_s.fasta";
$outfile=$dir."/".$basename.".out";

if(!$errorInfo){
	try{
		$ssh=new MySSH($hostname,$port,$username,$password);
		#$ssh->uploadData($querySeq,$localQueryFile);
      		file_put_contents($qfile,$querySeq);
		
		if(!$bl2seq){
			if($program=="blastn")
				$blast="mpirun -np 4 mpiblast -p $program -i $qfile -o $outfile -d $database ";
			elseif($program=="blastp")
				$blast="mpirun -np 4 mpiblast -p $program -i $qfile -o $outfile -d $database -e $evalue -v $hitNumber -b $hitNumber -W $wordsize -G $gapOpen -E $gapExtend -M $matrix -C $compo -F $filter";
			elseif($program=="blastx") 
				$blast="mpirun -np 4 mpiblast -p $program -i $qfile -o $outfile -d $database -e $evalue -v $hitNumber -b $hitNumber -W $wordsize -G $gapOpen -E $gapExtend -M $matrix -Q $code -F $filter";
			elseif($program=="tblastn") 
				$blast="mpirun -np 4 mpiblast -p $program -i $qfile -o $outfile -d $database -e $evalue -v $hitNumber -b $hitNumber -W $wordsize -G $gapOpen -E $gapExtend -M $matrix -C $compo -F $filter";
			elseif($program=="tblastx") 			
				$blast="mpirun -np 4 mpiblast -p $program -i $qfile -o $outfile -d $database -e $evalue -v $hitNumber -b $hitNumber -W $wordsize -M $matrix -Q $code -F $filter";	
		}
		else {
			#$ssh->uploadData($subjectSeq,$localSubjectFile);
			file_put_contents($sfile,$subjectSeq);
			$blast="bl2seq -i $qfile -j $sfile -o $outfile -p $program -G $gapOpen -E $gapExtend -W $wordsize -M $matrix  -r $match -q $mismatch -F $filter -e $evalue";  
		}
		
		//echo $blast."<br>";

		$blastout=$ssh->exeCommand("mpdboot;cd ..;".$blast.";mpdallexit");
                #system($blast);
		//echo $blastout;
		
		//$ssh->receiveFile($localResultFile,$localResultFile);
		
	}catch(Exception $e){
		$errorInfo.=$e->getMessage();
	}
	
}

################################ output BLAST results ##################################

if(!$errorInfo){
	header("Location: readblast.php?JOBTITLE=$jobtitle&RESULT=$outfile");
}
else {
	echo $errorInfo;
}

if(file_exists($qfile)) unlink($qfile);
if(file_exists($sfile)) unlink($sfile);

//echo $blast;


######################### functions ############################################

function checkFasta($qfile,&$title,&$seq) {
	$error='';
	$qseq=@file_get_contents($qfile['tmp_name']);
	$qseq=str_replace("\r\n","\n",$qseq);
	$l=strpos($qseq,"\n");
	$g=strpos($qseq,">");
	if($g!==0 || $l==0 || $l>=strlen($qseq))
		$error.="Error: invalid FASTA file!<br>";
	else{
		$seq=substr($qseq,$l+1);
		if(!preg_match_all("/^[A-IK-NP-Z]+$/im",$seq,$ms))
			$error.="Error: invalid sequence!<br>";
	}
	
	if(!$error){
		$title=substr($qseq,1,$l);
		$seq=$qseq;
	}
	
	return $error;
}



?>
