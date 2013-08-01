<?php

###### template ######
## by weibo at 2011-12-23 ##
#usage:
#      
#	include("template.php");
#	echoHtmlHead("page title");
//	custom css and js script
#	echoPageHeader();
//	main content
#	echoPageFooter();
#
/////////////////////////////////////////
 

function echoHtmlHead($title){
	echo <<< EOF
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/style.css" media="all" />
	<!--[if IE 7]>
	<link rel="stylesheet" type="text/css" href="css/style-ie.css" />
	<![endif]-->
    

<title>Simon Lab -- $title</title>

<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>

EOF;

}
function echoPageHeader(){
	echo <<< EOF
</head>

<body>

<div id="header">
	<div id="social">
  		<span>[<a href="#">中文</a>|<a href="#">English</a>]</span>
  		<span><a href="#">Help</a></span>
        <span><a href="#">Login</a></span>
  		<!--<a href="#"><img src="images/email.png" width="26" height="26" alt="email" /></a>-->
	</div>
    <form action="search2.php" method="get" id="search" name="quickQ">
			<div class="field-holder">
				<input name="keyword" type="text" class="field" value="search"  title="Quick search" onfocus="this.select()" />
			</div>
			<input type="button" class="button" value="Search" onclick="loading();this.form.submit()" />
			<div class="cl">&nbsp;</div>
	</form>
</div>



<ul class="dropdown"><!-- menu -->

  <li><a href="index.php">Home</a></li>
    
	<li><a href="#">Search</a>
		<ul class="sub_menu">
    		<li><a href="seq-search.php">Sequence</a></li>
    	</ul>
	</li>
    
	<li><a href="#">Analysis</a>
		<ul class="sub_menu">
    		<li><a href="blast.php">BLAST</a></li>
        	<li><a href="#">Alignment</a></li>
    	</ul>
	</li>
    
	<li><a href="#">Contact Us</a></li>
    
</ul><!-- close menu -->

EOF;
}

function echoPageFooter(){
	echo <<< EOF
<div id="top" class="topdiv">
<!-- <img src="images/loading.gif" alt="loading..." > -->
</div>
<div id="below" class="belowdiv"></div>

<div id="footer">
Organizer:bureau of Life Science and Biotechnology Chinese Academy of Sciences<br />
<a href="http://www.whiov.ac.cn">Wuhan Institute of Virology Chinese Academy of Sciences</a><br />
Address: Hongshan, Wuchang, Wuhan 430071, China Tel:027-87197000<br />

</div>

</body>
</html>
EOF;
}



?>
