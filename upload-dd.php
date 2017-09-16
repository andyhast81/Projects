<?php
header('Content-Type: application/json');
$dir    = 'uploads';
$existingfiles = scandir($dir, 1);
$uploaded = array();
$nf = $_FILES['files'];

if(!empty($_FILES['files']['name'][0])){
	
	foreach ($nf['name'] as $position => $name) {
		foreach ($existingfiles as $exfile) {
			if($name == $exfile){
				$extension_pos = strrpos($name, '.');
				$name = substr($name, 0, $extension_pos) . date('m-d-Y_his') . substr($name, $extension_pos);
				
			}
		}
		if(move_uploaded_file($_FILES['files']['tmp_name'][$position], 'uploads/'. $name)){
			$uploaded[] = array(
				'name' => $name,
				'file' => 'uploads/' . $name
			); 
		}
	}
}

 echo json_encode($uploaded);