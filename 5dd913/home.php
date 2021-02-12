<?php //home.php
	include 'header.php';
	include 'functions/homeFunctions.php';
	include 'functions/bookingFunctions.php';
	$errorText="";
	if(isset($_GET['msg'])){
		$errorText=$_GET['msg'];		
		if($errorText == "One of your seat has been reveserved in the meanwhile" || $errorText=="Someone has booked one of your seat in the meanwhile, delete it to make a new one")
			deleteBooking($_SESSION['user'],1);
	}
	$success=(isset($_GET['success']) && $_GET['success']);
	//if loggedin check if has a seat booked to create delete bookings button
	if($loggedin){
		$booked=checkUserBooking($_SESSION['user']);
		if($booked!=0)
			$bookedBool=true;
		else
			$bookedBool=false;
	}else{
	$bookedBool=false;
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Frenk Airlines</title>
		<link href="css/home.css" rel="stylesheet" type="text/css">
		<link href="css/mystyle.css" rel="stylesheet" type="text/css"/>
		<?php if($success){?>
		<style>
		#bookingError{
			color: green;
		}
		</style>
		<?php } 
		?>
		<script type="text/javascript" src="js/tableFunctions.js">
		</script>
		<script type="text/javascript" src="js/jquery-3.3.1.min.js">
		</script>
		<script type="text/javascript" src="js/home.js">
		</script>
	</head>
	<body>
	
	<div id="main">
	<?php if($loggedin){?>
		<h2>Hello   &nbsp;</h2>  <h1 id="username"><?php echo htmlentities($user);?></h1>
	<?php }?>
	<p class="errorMsg" id="bookingError"><?php echo htmlentities($errorText);?></p><br>
	
	<h2>Booking Legend</h2>
	<div>
	<table>
	  <tr>
		<th>Color</th>
		<th> Meaning</th>
	  </tr>
	  <tr>
		<td id="green"></td>
		<td>free seat</td>
	  </tr>
	  <tr>
		  <td id="orange"></td>
		  <td>seat reserved by any user (still available)</td>
	  </tr>
	  <tr>
		  <td id="yellow"></td>
		  <td>seat reserved by you (still available)</td>
	  </tr>
	  <tr>
		  <td id="red"></td>
		  <td>seat booked (not available)</td>
	  </tr>
  </table>
  </div>
  
<?php
	$M = 6;
	$N = 10;
	
	if(!checkSeats($M,$N)){
		$errorText="unable to update the database after changing row and column";
		redirect($_SERVER['PHP_SELF'] . "?msg=" . $errorText);
		echo " porco dio";
	}
	$toColor = getSeats();
	//echo count($toColor);
	$nSeat = count($toColor);
	$nRed = countRed();
	$nOrange = countOrange();
?>

	<h2>Frenk Airlines bookings</h2>
	<p id="total"> Total seat: <?php echo $M*$N ?></p>
	<p id="available"> Available seat: <?php echo $M*$N-$nSeat ?> </p> 
	<p id="purchased"> Purchased seat: <?php echo $nRed ?> </p> 
	<p id="reserved"> Reserved seat: <?php echo $nOrange ?> </p> 
	
	<?php if(!$loggedin){?>
		<h2>You must be logged in to select and purchase seat!</h2>
	<?php }?>
	
<div id="mainDiv" class="container">
 
<img id="frank" src="img/wewe2.png" alt="Airplane">
	
</div>

<script type="text/javascript"><!--
	var bool = "<?php echo $loggedin ?>"; 
	var M = "<?php echo $M ?>";
	var N = "<?php echo $N ?>";
	var booked = "<?php echo $bookedBool ?>"; 
    var tf = document.getElementById("mainDiv");
    var parent = document.getElementById("mainDiv");
    var model = new SpreadModel(M,N);
	var view = new SpreadView(tf, parent, "main", model, bool, booked);
	var controller = new SpreadController(model, [view]);
	
//--></script>
<noscript style="color:blue;float:left;font-size: large;font-weight: bold;padding-left: 100px;padding-top: 20x;">Javascript is not enabled on your browser: the application could not work properly
</noscript>



		<!-- BOOKING -->
		<br><br>
			<?php if($loggedin){ ?>	
				<div class="container">		
					
					</form>							
					<h2>Book a seat!</h2>
					
					<form name="mySelectionForm" id="mySelectionForm" method="post">
					<p class="errorMsg" id="errorMsg"></p>
					<!--<input type="hidden" name="row" value="" id="row" />-->
					<input type="hidden" name="nSeats" value="" id="nSeats" />
					
					<input type="hidden" name="seats_numbers" value="" id="seats_numbers" />
					<input class="button" id="bookButton" type="button" value="Book" onClick="myFunction()";>
					</form>
					
					<input class="button" id="UpdateButton" type="button" value="Update" onClick="updateSeats(M,<?php echo "'" . $_SESSION['user'] . "'";?>)";>
					
				
				
				
				<?php
					for($i=0;$i<$nSeat;$i++){
						echo "
							<script type='text/javascript'><!--
							
								colorCell(" . $toColor[$i][0] . " , '" . $toColor[$i][1] . "' , " . $toColor[$i][2] . " , " . $M . " , '" . $_SESSION['user'] ."');
							
							//--></script>
							<noscript style='color:blue;float:left;font-size: large;font-weight: bold;padding-left: 100px;padding-top: 20x;'>Javascript is not enabled on your browser: the application could not work properly
</noscript>
							";
					}
				 if($bookedBool){?>
				 	<!-- DELETE BOOKING -->
							<br><br><input class="button" type="button" id="delete" value="Delete my bookings"> 		
						<?php }?>
						<!-- MODAL -->
						<div id="homeModal" class="modal">
							<div class="modal-content">
								<span id="close">&times;</span>
								<h3 id="modalTitle"></h3>
								<p id="modalText"></p>
								<input class="button" type="button" id="confirm" value="Yes">
								<input class="button" type="button" id="cancel" value="No">
							</div>
						</div>
						</div>
			<?php } else {
				for($i=0;$i<$nSeat;$i++){
					echo "
						<script type='text/javascript'><!--
						
							colorCell(" . $toColor[$i][0] . " , '" . $toColor[$i][1] . "' , " . $toColor[$i][2] . " , " . $M . " , '0');
						
						//--></script>
						<noscript style='color:blue;float:left;font-size: large;font-weight: bold;padding-left: 100px;padding-top: 20x;'>Javascript is not enabled on your browser: the application could not work properly
</noscript>
						";
				}
			}?>
		</div>
	</body>
</html>