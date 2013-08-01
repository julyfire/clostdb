<?php
/*=================================================
<!--webpage template 3.0
by weibo-->

<?php 
include("template.php");
startPageHeader(pagetitle); ?>

<!--insert css and javascript here -->

<?php endPageHeader(); ?>

<!--page content here-->

<?php pageFooter(); ?>		

==================================================*/

function startPageHeader($title){
	echo <<< EOD
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../css/style.css" media="all" />
	<!--[if IE 7]>
	<link rel="stylesheet" type="text/css" href="../css/style-ie.css" />
	<![endif]-->
<title>Simon Lab -$title</title>

<script type="text/javascript" src="../js/jquery.js"></script>

<!-- Begin DropDown -->
      
<script type="text/javascript">
$(function(){
   $("ul.dropdown li").hover(function(){
        $(this).addClass("hover");
        $('ul:first',this).css('visibility', 'visible');
    
    }, function(){
   $(this).removeClass("hover");
   $('ul:first',this).css('visibility', 'hidden');
});
$("ul.dropdown li ul li:has(ul)").find("a:first").append(" &raquo; ");
});
</script>
EOD;
}


function endPageHeader(){
	echo <<< EOD
</head>

<body>

<div id="header">
	<div id="social">
  		<span>[<a href="#">中文</a>|<a href="#">English</a>]</span>
  		<span><a href="#">Help</a></span>
        <span><a href="#">Login</a></span>
  		<!--<a href="#"><img src="images/email.png" width="26" height="26" alt="email" /></a>-->
	</div>
    <form action="" method="post" id="search">
			<div class="field-holder">
				<input type="text" class="field" value="Search" title="Search" />
			</div>
			<input type="submit" class="button" value="Search" />
			<div class="cl">&nbsp;</div>
	</form>
</div>



<ul class="dropdown"><!-- menu -->

  <li><a href="index.html">Home</a></li>
    
	<li><a href="#">Databases</a>
		<ul class="sub_menu">
    		<li><a href="#">Rabies</a></li>
        	<li><a href="#">Flavivirus</a></li>
            <li><a href="#">Influenza</a></li>
            <li><a href="#">Baculovirus</a></li>
    	</ul>
	</li>
    
	<li><a href="#">Tools</a>
		<ul class="sub_menu">
    		<li><a href="#">BLAST</a></li>
        	<li><a href="#">Alignment</a></li>
    	</ul>
	</li>
    
	<li><a href="page.html">Blog</a></li>
    
	<li><a href="#">About</a></li>
    
</ul><!-- close menu -->

<!-- ================================== main content start ===================================== -->

EOD;
}

function pageFooter(){
	echo <<< EOD
<!-- =================================== main content end ======================================== -->

<div id="footer">
Organizer:bureau of Life Science and Biotechnology Chinese Academy of Sciences<br />
<a href="http://www.whiov.ac.cn">Wuhan Institute of Virology Chinese Academy of Sciences</a><br />
Address: Hongshan, Wuchang, Wuhan 430071, China Tel:027-87197000<br />

</div>

</body>
</html>
EOD;
}
