<?php
function PreDump($object){
	echo "<pre>";
	echo var_dump($object);
	echo "</pre>";
}

function get_day_name($current) {

    $current = strtotime(date("Y-m-d"));
 $date    = strtotime("2014-09-05");

 $datediff = $date - $current;
 $difference = floor($datediff/(60*60*24));
 if($difference==0){
    $day = 'today';
 }else if($difference == -1){
    $day = 'yesterday';
 }else{
    $day = 'later';
 }  
 return $day;
}