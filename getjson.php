<?php
if($_GET['json']){
	echo file_get_contents($_GET['json']);
}	


?>