<?php
	include 'functions/common.php';
	include 'functions/signUpFunctions.php';
	session_start();
	
	// If logged user rediret
	if(isset($_SESSION['user'])){
		redirect("home.php");
	}
	
	// Retrieve parameters
	$username=$_POST['username'];
	$psw1=$_POST['psw1'];
	$psw2=$_POST['psw2'];
	
	// If the fields of the form are correct
	if(checkEmail($username) && checkPsw($psw1, $psw2) && checkUsername($username)==0) {
		// If the user is inserted into the db
		if(insertUser($username, $psw1)){
    		$_SESSION=array();
			$_SESSION['user']=$username;	
			$_SESSION['time']=time();
    		redirect("home.php");    		
    	}	
    	
	}
	// Error
	redirect("signUp.php?msg=error");
	
	
	
?>