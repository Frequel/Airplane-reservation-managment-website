<?php
	include 'functions/common.php'; 
	include 'functions/bookingFunctions.php';
	
	session_start();
	
	if(!isset($_POST['pos']) || !isset($_SESSION['user'])){
		echo "error";
		die();
	}
	
	echo deleteBookingS($_POST['pos']);

?>