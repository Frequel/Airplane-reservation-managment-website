<?php
	include 'functions/common.php';
	session_start();
		
	// If logged user, rediret to home
	if(!isset($_SESSION['user'])){
		redirect("home.php");
	}
	
	// Check authentication time
	checkTime();
	
	logout();
	redirect("home.php");	 
?>