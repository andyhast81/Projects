<?php
	require_once('inc/class.user.php');
	if(session_id() == '') {
	    session_start();
	}
	$user_logout = new USER();
	
	if(!$user_logout->is_logged_in){
		$user_logout->redirect('login.php');
	}
	if(isset($_GET['logout']) && $_GET['logout']=="true"){
		$user_logout->doLogout();
		$user_logout->redirect('index.php');
	}