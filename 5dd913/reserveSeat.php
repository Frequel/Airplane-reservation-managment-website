<?php
	include 'functions/common.php'; 
	include 'functions/bookingFunctions.php';
	
	session_start();
	
	if(!isset($_POST['pos']) || !isset($_SESSION['user'])){
		echo "porco dio";
		die();
	}
	echo reserve($_SESSION['user'],$_POST['pos']);

?>