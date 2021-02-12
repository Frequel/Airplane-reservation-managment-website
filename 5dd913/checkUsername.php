<?php
	include 'functions/common.php'; 
	include 'functions/signUpFunctions.php';
	
	if(!isset($_POST['username'])){
		echo 1;
		die();
	}
	
	echo checkUsername($_POST['username']);

?>