<?php
isset($_POST['qtitle']) && $_POST['qtitle']?$qtitle=$_POST['qtitle']:$qtitle='';
isset($_POST['qs']) && $_POST['qs']?$qs=$_POST['qs']:$qs='';
?>

<?php
include("template.php");
echoHtmlHead("Blast submition");
?>


<link href="css/blast.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="js/blast.js"></script>

<?php echoPageHeader(); ?>
<!-- ================================== main content start ===================================== -->
<div id="main">
<form action='remote.php' method="post" name="blast" id="blast" class="blast" enctype="multipart/form-data">

<table width="960" border="0">

  <tr>
    <th scope="col">Enter Query Sequence<em>FASTA format</em></th>
    <td><textarea id="query" class="reset" rows="5" cols="80" name="QUERY" onblur="checkSeqFormat(this)"><?php echo $qs; ?></textarea><span class="button"><a href="#" onclick="clearSeq('query')">clear</a></span></td>
  </tr>
  <tr>
    <th scope="row">Or, upload file</th>
    <td><input id="upl" name="QUERYFILE" type="file" size="70"></td>
  </tr>
  <tr>
    <th scope="row">Job Title</th>
    <td><?php echo "<input name=\"JOB_TITLE\" id=\"qtitle\" class=\"reset\" size=\"80\" value=\"$qtitle\">"; ?></td>
  </tr>
  <tr>
    <td><input name="BL2SEQ" value="y" id="bl2seq" type="checkbox" onclick="toggle(['sseq','db']);">
               <label>Align two sequences</label></td>
<td></td>
  </tr> 
  </table> 

<div id="sseq" class="collapse">
  <table width="960" border="0" >
  <tr>
    <th scope="row">Enter Subject Sequence<em>FASTA format</em></th>
    <td><textarea id="subject" class="reset" rows="5" cols="80" name="SUBJECT"  onblur="checkSeqFormat(this)"></textarea><span class="button"><a href="#" onclick="clearSeq('subject')">clear</a></span></td>
  </tr>
  <tr>
    <th scope="row">Or, upload file</th>
    <td><input id="up2" name="SUBJECTFILE" type="file" size="70"></td>
  </tr>
  </table>
</div>
  <table width="960" border="0">
  <tr>
    <th scope="row">Program</th>
    <td><select name="PROGRAM" id="program" class="reset checkDef" onchange="setPara(this)">
					<option id="Ohc"  value="blastn" selected="selected">blastn</option>
					<option id="Omc" value="blastp">blastp</option>  
                	<option id="Omc" value="blastx">blastx</option>
                	<option id="Omc" value="tblastn">tblastn</option>
               	 	<option id="Omc" value="tblastx">tblastx</option>    
	  	</select> 
    </td>
  </tr>
  
  <tr id="db" class="expand">
    <th scope="row">Database</th>
    <td><select name="DATABASE" id="database" class="reset checkDef">
					<option id="Ohc"  value="clostridium" selected="selected">clostridium</option>
					<option id="Omc" value="nr">nr</option>      
	  	</select> 
    </td>
  </tr>
  
  </table>
  <div class="button toggle"><a href="#" onclick="toggle(['para'])">Parameters Setting</a></div>
<div id="para" class="collapse">
  <table width="960" border="0">
  <tr>
    <th scope="row">Max target sequences</th>
    <td><select name="MAX_NUM_SEQ" class="reset checkDef opts" id="NUM_SEQ" defval="100">               
               <option value="10">10</option>
               <option value="50">50</option>
               <option value="100" selected="selected">100</option>
               <option value="250">250</option>
               <option value="500">500</option>
               <option value="1000">1000</option>
               <option value="5000">5000</option>
               <option value="10000">10000</option>
               <option value="20000">20000</option>
            </select>
    </td>
  </tr>
  <tr>
    <th scope="row">Expect threshold</th>
    <td><input name="EXPECT" id="expect" class="reset checkDef opts" size="10" value="10" defval="10" type="text">
    </td>
  </tr>
  <tr>
    <th scope="row">Word size</th>
    <td><select name="WORD_SIZE" id="wordsize" class="reset checkDef opts" defval="11">
    			<option value="7">7</option>
				<option value="11" class="Deflt" selected="selected">11</option>
				<option value="15">15</option>
			</select>
    </td>
  </tr>
  <tr id="code" class="collapse">
  	<th scope="row">Genetic code</th>
    <td>
    <select name="GENETIC_CODE"  class="reset" id="GENETIC_CODE">
        <option value="1"  >Standard (1)</option>
         <option value="2"  >Vertebrate Mitochondrial (2)</option>
         <option value="3"  >Yeast Mitochondrial (3)</option>
         <option value="4"  >Mold Mitochondrial; ... (4)</option>
         <option value="5"  >Invertebrate Mitochondrial (5)</option>
         <option value="6"  >Ciliate Nuclear; ... (6)</option>
         <option value="9"  >Echinoderm Mitochondrial (9)</option>
         <option value="10"  >Euplotid Nuclear (10)</option>
         <option value="11"  >Bacteria and Archaea (11)</option>
         <option value="12"  >Alternative Yeast Nuclear (12)</option>         
         <option value="13"  >Ascidian Mitochondrial (13)</option>
         <option value="14"  >Flatworm Mitochondrial (14)</option>
         <option value="15"  >Blepharisma Macronuclear (15)</option>
        </select>

    </td>
  </tr>
  <tr id="matrix" class="collapse">
    <th scope="row">Matrix</th>
    <td><select name="MATRIX_NAME" id="matrixName" class="reset checkDef opts" defval="">
                           <option value="PAM30">PAM30</option>
                           <option value="PAM70">PAM70</option>
                           <option value="BLOSUM80">BLOSUM80</option>
                           <option value="BLOSUM62" selected="selected">BLOSUM62</option>
                           <option value="BLOSUM45">BLOSUM45</option>
                        </select>
    </td>
  </tr>
  <tr id="match" class="collapse">
    <th scope="row">Match/Mismatch Scores</th>
    <td><select name="MATCH_SCORES" id="matchscores" class="reset checkDef opts" defval="2,-3"> 
            	<option value="1,-2">1,-2</option>
                <option value="1,-3">1,-3</option>                  
                <option value="1,-4">1,-4</option>                  
                <option value="2,-3" selected="selected">2,-3</option>                  
                <option value="4,-5">4,-5</option>                  
                <option value="1,-1">1,-1</option>    
           </select>
    </td>
  </tr>
  <tr id="gap" class="collapse">
    <th scope="row">Gap Costs</th>
    <td><select name="GAPCOSTS" id="gapcosts" class="reset checkDef opts" defval="5 2">	                                
            	<option value="4 4">Existence: 4 Extension: 4</option>
                <option value="2 4">Existence: 2 Extension: 4</option>
                <option value="0 4">Existence: 0 Extension: 4</option>
                <option value="3 3">Existence: 3 Extension: 3</option>
                <option value="6 2">Existence: 6 Extension: 2</option>
                <option selected="selected" value="5 2">Existence: 5 Extension: 2</option>
                <option value="4 2">Existence: 4 Extension: 2</option>
                <option value="2 2">Existence: 2 Extension: 2</option>
			</select>
    </td>
  </tr>
  <tr id="compo" class="collapse">
		<th scope="row">Compositional adjustments</th>  
		<td><select name="COMPOSITION_BASED_STATISTICS" id="compbasedstat" class="reset checkDef opts" defval="2">
               <option value="0">No adjustment</option>
               <option value="1">Composition-based statistics</option>
               <option value="2" selected="selected">Conditional compositional score matrix adjustment</option>
					<option value="3">Universal compositional score matrix adjustment</option>
          </select> 
		</td>
  </tr>
  <tr>
    <th scope="row">Filter</th>
    <td><input name="FILTER" value="T" id="fil_l" checked="checked" class="reset checkDef opts" defval="checked" type="checkbox">
               <label class="right inlinelabel" for="fil_l">Low complexity regions <span class="acPromt">filter</span></label>   </td>
  </tr>
  <tr>
    <th scope="row">Mask</th>
    <td><input name="MASK" value="m" id="fil_m" checked="checked" class="reset checkDef opts" defval="checked" type="checkbox">
               <label class="right inlinelabel" for="fil_m">Mask for lookup table only</label></td>
  </tr>

</table>
</div>

<input name="" type="button" class="go" value="BLAST" onclick="runblast()" />
</form>
</div>

<!-- =================================== main content end ======================================== -->

<?php echoPageFooter(); ?>
