<?php
class parseini{
var $ini;
var $filename;

function parseini($filename){
    $this->filename = $filename;
}


function setini(){
   return  $this->ini = parse_ini_file($this->filename);
}

function getvalue($key){
    return $this->ini[$key];
}

function is_key($key){
    
    $keys = array_keys($this->ini);
    $flag = 0;
    foreach($keys as $tmp){
        if($tmp == $key){
            $flag = 1;
            break;
        }   
    }
    
    return $flag;
}
}
?>
