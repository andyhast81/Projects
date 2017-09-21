<?php 
include_once 'class.projects.php';
$project = new PROJECT();
// if(!$user->is_loggedin()){
//   $user->redirect('login.php');
// }

 	$note = $_POST['note'];
 	$userId = $_POST['userId'];
 	$assignedTo = $_POST['assignedTo'];
 	$projectId = $_POST['projectId'];
 	date_default_timezone_set('America/Chicago');
 	$date = date('Y-m-d H:i:s');
 	$date2 = date('g:ia');

 	$cleanNote = nl2br(htmlspecialchars($note));
	$userId = filter_var($userId,FILTER_SANITIZE_NUMBER_INT);
	$assignedTo = filter_var($assignedTo,FILTER_SANITIZE_NUMBER_INT);
	$projectId = filter_var($projectId,FILTER_SANITIZE_NUMBER_INT);

	$project->AddNote($date,$userId,$assignedTo,$projectId,$cleanNote);

	$noteArray = array($date2,$cleanNote);

 echo json_encode($noteArray); 
 // echo $date2; 