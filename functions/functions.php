<?php
function PreDump($object){
	echo "<pre>";
	echo var_dump($object);
	echo "</pre>";
}

function get_day_name($current) {

	$date=date_create($current);
	$time = date_format($date, 'h:ia');
	
	$current = (strtotime($current));
	$today = strtotime(date('M j, Y'));
	 
	$reldays = ($current - $today)/86400;
	 
	if ($reldays >= 0 && $reldays < 1) {
	 
		return 'today at '.$time;
	 
	} else if ($reldays >= -1 && $reldays < 0) {
	 
		return 'yesterday at '.$time;
	 
	}else{
		return date('l, F j'). ' at '.$time;
	}
 
	// if (abs($reldays) < 7) {
	 
	// 	if ($reldays > 0) {
		 
	// 		$reldays = floor($reldays);
		 
	// 		return 'In ' . $reldays . ' day' . ($reldays != 1 ? 's' : '');
		 
	// 	} else {
		 
	// 		$reldays = abs(floor($reldays));
		 
	// 		return $reldays . ' day' . ($reldays != 1 ? 's' : '') . ' ago';
		 
	// 	}
 
	// }
 
	if (abs($reldays) < 182) {
	 
		return date('l, j F',$current ? $current : time());
	 
	} else {
	 
		return date('l, j F, Y',$current ? $current : time());
	 
	}
}