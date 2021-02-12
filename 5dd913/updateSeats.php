<?php
	include 'functions/common.php'; 
	include 'functions/homeFunctions.php';
	
	session_start();
	
	if(!isset($_SESSION['user'])){
		echo "porco dio";
		die();
	}
	
	$toColor = getSeats();
	
	for($i=0;$i<count($toColor);$i++){
		$vett[$i]=json_encode($toColor[$i]);
	}
	
	$fin = json_encode($vett);
	
	echo $fin;

?>