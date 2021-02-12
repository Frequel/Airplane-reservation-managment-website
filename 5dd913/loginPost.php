<?php
	include 'functions/common.php';
	session_start();
	
	// If logged user rediret
	if(isset($_SESSION['user'])){
		redirect("home.php");
	}
		
	// Retrieve parameters
	$username=$_POST['username'];
	$psw=$_POST['psw'];
	
	// Check if the password matches the username
	if(login($username, $psw)){
		$_SESSION=array();
		$_SESSION['user']=$username;	
		$_SESSION['time']=time();
    	redirect("home.php");
	}
	// Error
	redirect("login.php?msg=error");
?>