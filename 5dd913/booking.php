<?php
	include 'functions/common.php';	
	include 'functions/bookingFunctions.php';
	include 'functions/homeFunctions.php';
	session_start();
			
	// If not logged user, rediret to home
	if(!isset($_SESSION['user'])){
		redirect("home.php");
	}
	
	// Check authentication time
	checkTime();
		
	$username=$_SESSION['user'];
	$seats=$_POST["seats_numbers"];
	
	// Do the booking
	$status = 2;
	$result=book($username, $seats, $status);
	$success=($result=="Your booking has been done successfully");
	redirect("home.php?msg=".$result."&success=".$success);
	
?>