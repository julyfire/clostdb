<?php
include("template.php");
echoHtmlHead("Home");
?>
<script type="text/javascript" src="js/jcarousellite_1.0.1.js"></script>
<script type="text/javascript" >
$(function() {
    $(".slider").jCarouselLite({
        btnNext: "#next",
        btnPrev: "#prev",
        visible: 4
    });
});
</script>
<link rel="stylesheet" type="text/css" href="css/index.css" />
<?php
echoPageHeader();
?>

<!-- ================================== main content start ===================================== -->


<div id="gallery"><!-- gallery -->
<a href="#" id="prev" class="arrow"><img src="images/l_arrow.png" alt="<<" ></a>

<div class="slider">


<ul>

  <li><a href="#"><img src="images/clo1.png" alt="dengue" /></a></li>

  <li><a href="#"><img src="images/clo2.png" alt="influenza" /></a></li>

  <li><a href="#"><img src="images/clo3.png" alt="rabies" /></a></li>

  <li><a href="#"><img src="images/clo4.png" alt="baculovirus" /></a></li> 

</ul>


</div>
<a href="#" id="next" class="arrow"><img src="images/r_arrow.png" alt=">>" ></a>

</div><!-- close gallery --> 
 
<div id="intro">
<p><span>Welcome to the Rayner Lab.</span> The research of the group is focused on the molecular evolution of viruses and bacteria and the development of new tools for mining large virus datasets.
</p>
</div>


<div id="box_left">
<h3>About</h3>
<p>The Bioinformatics Group focuses on the study of virus and bacteria evolution, we are particularly interested in mosquito borne viruses such as Japanese encephalitis and Dengue, the rabies virus and the <i>Bacillus</i> family of bacteria.</p>
<a href="about.php"><img src="images/more.png" class="more" width="68" height="24" alt="more" /></a>
</div>

<div id="box_right">
<h3>Publications</h3>
<p> Pan XL, Rayner S, Liang GD.(2011)Emergence of Genotype I of Japanese Encephalitis Virus as the Dominant Genotype in Asia. <i>Journal of Virology</i> 85(19):9847-9853.<br>...<br><br></p>
<a href="publication.php"><img src="images/more.png" class="more" width="68" height="24" alt="more" /></a>
</div>

<div id="box_middle">
<h3>Tools</h3>
<p><strong>miRPara</strong>: A SVM-based software tool for prediction of most probable microRNA coding regions in genome scale sequences.<br><strong>Clostdb</strong>: Clostridium Sequence Database<br><br><br></p>
<a href="tools.php"><img src="images/more.png" class="more" width="68" height="24" alt="more" /></a>
</div>

<!-- =================================== main content end ======================================== -->
<?php echoPageFooter(); ?>
