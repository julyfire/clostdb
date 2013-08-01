<?php
include("template.php");
echoHtmlHead("Search Clostridium");
?>

<!-- ##############custom style and script################# -->

<link rel="stylesheet" type="text/css" href="css/searchpage.css" media="all" />
<script type="text/javascript" src="js/formdata.js"></script>



<!-- ####################################################### -->
<?php echoPageHeader(); ?>

<!-- ================================== main content start ===================================== -->

<div id="main">
	<!--<div id="map">map</div>-->
    <div id="search_form">
    	<form action="search2.php" method="post" name="search_list" id="search_list" onsubmit="javascript:return checkform();" onclick="isfilled()">
        	<table>
  <tr class="odd">
    <th scope="col">Accession Number</th>
    <td><input name="acc" type="text" class="text" id="acc" /></td>
    <td>
    <!--
      <label>
        <input type="radio" name="logicAcc" value="and" id="logicAcc_and" checked="checked" />
        and</label>
      <label>
        <input type="radio" name="logicAcc" value="or" id="logicAcc_or" />
        or</label>-->
      <label>
        <input type="radio" name="logicAcc" value="not" id="logicAcc_not" />
        not</label>
    </td>
  </tr>
  <tr>
    <th scope="row">Region</th>
    <td><div id="country" class="selectbox">
		<input id="countryhead" class="selecthead" name="countryhead" onkeyup="SelectTip('country')" />
    	<span id="countrybody" class="selectbody">
		<!--<select id="select-country" name="select-country" class="select" size=5 onchange="selected('country')">
		</select>-->
		</span></div>
    </td>
    <td>
     <!-- <label>
        <input type="radio" name="logicRegion" value="and" id="logicRegion_and" checked="checked" />
        and</label>
      <label>
        <input type="radio" name="logicRegion" value="or" id="logicRegion_or" />
        or</label>
      <label>-->
        <input type="radio" name="logicRegion" value="not" id="logicRegion_not" />
        not</label>
    </td>
  </tr>
  <tr class="odd">
    <th scope="row">Time</th>
    <td>from <input name="from" type="text" class="text" id="from" title="from" />
    	&nbsp;to <input name="to" type="text" class="text" id="to" title="to" />
    	&nbsp;(YYYY-MM-DD)    
    </td>
    <td>
      <!--<label>
        <input type="radio" name="logicTime" value="and" id="logicTime_and" checked="checked" />
        and</label>
      <label>
        <input type="radio" name="logicTime" value="or" id="logicTime_or" />
        or</label>
      <label>
        <input type="radio" name="logicTime" value="not" id="logicTime_not" />
        not</label>-->
    </td>
  </tr>
  <tr>
    <th scope="row">Species</th>
    <td><div id="species" class="selectbox">
		<input id="specieshead" class="selecthead" name="specieshead" onkeyup="SelectTip('species')" /> 
    	<span id="speciesbody" class="selectbody">
		<!--<select id="select-species" name="select-species" class="select" size=5 onchange="selected('species')">
		</select>-->
		</span></div>
    </td>
    <td>
      <!--<label>
        <input type="radio" name="logicSpecies" value="and" id="logicSpecies_and" checked="checked" />
        and</label>
      <label>
        <input type="radio" name="logicSpecies" value="or" id="logicSpecies_or" />
        or</label>-->
      <label>
        <input type="radio" name="logicSpecies" value="not" id="logicSpecies_not" />
        not</label>
    </td>
  </tr>
  <tr class="odd">
    <th scope="row">Pubmed ID</th>
    <td><input name="pubmed" type="text" class="text" id="pubmed" disabled /></td>
    <td>
      <!--<label>
        <input type="radio" name="logicPubmed" value="and" id="logicPubmed_and" checked="checked" />
        and</label>
      <label>
        <input type="radio" name="logicPubmed" value="or" id="logicPubmed_or" />
        or</label>-->
      <label>
        <input type="radio" name="logicPubmed" value="not" id="logicPubmed_not" />
        not</label>
    </td>
  </tr>
  <tr >
    <th scope="row">Host</th>
    <td><div id="host" class="selectbox">
		<input id="hosthead" class="selecthead" name="hosthead" onkeyup="SelectTip('host')" />
    	<span id="hostbody" class="selectbody">
		<!--<select id="select-host" name="select-host" class="select" size=5 onchange="selected('host')">
		</select>-->
		</span></div>
    </td>
    <td>
      <!--<label>
        <input type="radio" name="logicHost" value="and" id="logicHost_and" checked="checked" />
        and</label>
      <label>
        <input type="radio" name="logicHost" value="or" id="logicHost_or" />
        or</label>-->
      <label>
        <input type="radio" name="logicHost" value="not" id="logicHost_not" />
        not</label>
    </td>
  </tr>
  <tr class="odd">
    <th scope="row">Gene lies in</th>
    <td>
      <label>
        <input type="radio" name="location" value="genome" id="location_0" disabled />
        genome</label>
      <label>
        <input type="radio" name="location" value="plasmid" id="location_1" disabled />
        plasmid</label>
   	</td>
    <td>
      <!--<label>
        <input type="radio" name="logicLocation" value="and" id="logicLocation_and" checked="checked" />
        and</label>
      <label>
        <input type="radio" name="logicLocation" value="or" id="logicLocation_or" />
        or</label>
      <label>
        <input type="radio" name="logicLocation" value="not" id="logicLocation_not" />
        not</label>-->
    </td>
  </tr>
  <tr>
    <th scope="row">Gene</th>
    <td><div id="gene" class="selectbox">
		<input id="genehead" class="selecthead" name="genehead" onkeyup="SelectTip('gene')" />
    	<span id="genebody" class="selectbody">
		<!--<select id="select-gene" name="select-gene" class="select" size=5 onchange="selected('gene')">
		</select>-->
		</span></div>
    </td>
    <td>
      <!--<label>
        <input type="radio" name="logicGene" value="and" id="logicGene_and" checked="checked" />
        and</label>
      <label>
        <input type="radio" name="logicGene" value="or" id="logicGene_or" />
        or</label>-->
      <label>
        <input type="radio" name="logicGene" value="not" id="logicGene_not" />
        not</label>
    </td>
  </tr>
  <tr>
    <th scope="row">&nbsp;</th>
    <td></td>
    <td><div id="do"><input name="Search" type="submit" id="submit" class="button" value="Search" /> <input name="Reset" type="reset" class="button" value="Reset" /></div></td>
  </tr>
</table>
			<input type="hidden" name="fromsearch" value="fromsearch">
        </form>
        <div class="errors"></div>
		<div class="messages"></div>
    </div>
</div>


<!-- =================================== main content end ======================================== -->

<?php echoPageFooter(); ?>
