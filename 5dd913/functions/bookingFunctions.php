<?php
// BOOKING FUNCTIONS
// Booking function. It does a transaction in which first it checks if the booking can take place
function book($username, $seats){
	$conn=connectDB();	
	$list = explode( "," , $seats);
	$status = 2;
	$s=1;
	try {
		// Disable autocommit
		mysqli_autocommit($conn, false);
						
		
		//Check if someone has booked a seat among those chosen
		$c = checkSeat($conn, $list, $username,$status);
		//deleteBookingRows($conn, $username,$s);
		deleteBookingRow($conn, $list);
		//deleteBookingRows($conn, $username, $s);
		//return $c;
		/*if($c==1){
			deleteBookingRow($conn, $list);
		} else 
		if ($c==2){
			deleteBookingRows($conn, $username,$s);
			mysqli_close($conn); 
			return "One of your seat has been reveserved in the meanwhile";
		}//*/
		// Add the booking in the booking table
		addBooking($conn, $username, $list, $status);
		
		// All ok commit	
		mysqli_commit($conn);
		} catch (Exception $e) {
			// Rollback and give back the exception message
			//deleteBookingRows($conn, $username,$s);
			mysqli_rollback($conn);
			mysqli_close($conn);
			return $e->getMessage();
		}
		return "Your booking has been done successfully";
		mysqli_close($conn); 
}

//equal to book but for reservations
function reserve($username, $seats){
	$conn=connectDB();	
	$list = Array();
	$list[0] = $seats;
	$status = 1;
	try {
		// Disable autocommit
		mysqli_autocommit($conn, false);
		if(checkSeat($conn, $list, $username,$status)!= 0)
			deleteBookingRow($conn, $list);
		
		// Add the booking in the booking table
		addBooking($conn, $username, $list, $status);
		
		// All ok commit	
		mysqli_commit($conn);
		
		} catch (Exception $e) {
			// Rollback and give back the exception message
			//delete all row in the database with username and status=1
			mysqli_rollback($conn);
			mysqli_close($conn);
			return $e->getMessage();
		}
		return "Your reservation has been done successfully";
		mysqli_close($conn); 
}
//check if a seat is already booked, if not, return 0 if all seats were available or 1 if at least one seat was reserved (pratically, 0 on click and always 1 on booking)
function checkSeat($conn, $seats, $currentUser,$stat){
	$flag=0;
	$query = "SELECT * FROM seat WHERE pos=? FOR UPDATE";
	if(!$stmt = mysqli_prepare($conn, $query)){
		throw new Exception("Error 3 in the booking process, try again");
	}
	
	for($i=0;$i<count($seats);$i++){
		mysqli_stmt_bind_param($stmt, "i", $seats[$i]);

		if(!mysqli_stmt_execute($stmt)){
			throw new Exception("Error 4 in the booking process, try again");
		}

		mysqli_stmt_store_result($stmt);
		if(mysqli_stmt_num_rows($stmt)!=0){
			mysqli_stmt_bind_result($stmt, $result['pos'], $result['username'], $result['status']);		
			mysqli_stmt_fetch($stmt);
			if( $result['status']==2){
				echo  "0,";
				//delete yellow seat of the curretUser(?) //deleteBookingRows($conn, $currentUser, $result['status']);
				throw new Exception("Someone has booked one of your seat in the meanwhile, delete it to make a new one");
			}
			else if ($result['status']== 1){
				if($result['username']!= $currentUser && $stat==2){
					//deleteBookingRow($conn, $seats);
					$s=1;
					//deleteBookingRows($conn, $currentUser, $s); //perchè non cancella niente?
					//deleteBookingRows($conn, $username,$stat);
					//throw new Exception("One of your seat has been reveserved in the meanwhile");
					//throw new Exception("Oarrivo qua in the meanwhile" . " " .  $currentUser . " " . $result['status']. " " . $flag));
					echo  "0,";
					$flag=2;
					throw new Exception("One of your seat has been reveserved in the meanwhile");
					//throw new Exception("Oarrivo qua in the meanwhile" . " " .  $currentUser . " " . $result['status']. " " . $flag);

				} else
					$flag=1;
			}
		}
		mysqli_stmt_free_result($stmt);	
		echo  "1,";
	}
	mysqli_stmt_close($stmt);
	return $flag;
}
// Add the booking into the booking table
function addBooking($conn, $username, $seats, $status){
		$query = "INSERT INTO seat(pos, username, status) VALUES (?,?,?)";
		//$status=2;
		if(!$stmt = mysqli_prepare($conn, $query)){
			throw new Exception("Error 5in the booking process, try again");
		}
		for($i=0;$i<count($seats);$i++){
			mysqli_stmt_bind_param($stmt, "isi", $seats[$i], $username, $status);
			if(!mysqli_stmt_execute($stmt)){
				throw new Exception("Error 6  in the booking process, try again");
			}
			mysqli_stmt_store_result($stmt);
			if(mysqli_stmt_affected_rows($stmt)!=1){
				throw new Exception("Error 7  in the booking process, try again");
			}
			mysqli_stmt_free_result($stmt);	
		}
		mysqli_stmt_close($stmt);
	
}

// DELETE BOOKING FUNCTIONS
// Deletes a user booking.
/*
function deleteBooking($username){
	$conn=connectDB();	
	try {
		// Disable autocommit
		mysqli_autocommit($conn, false);
		
		// Delete the row in the booking table
		deleteBookingRows($conn, $username);
		
		// All ok commit	
		mysqli_commit($conn);
		} catch (Exception $e) {
			// Rollback and return the exception message
			mysqli_rollback($conn);
			mysqli_close($conn);
			return $e->getMessage();
		}
	return "You have successfully deleted your booking"; 
	mysqli_close($conn);
}
*/
function deleteBooking($username,$status){
	$conn=connectDB();	
	//$status = 1;//è 2
	try {
		// Disable autocommit
		mysqli_autocommit($conn, false);
		
		// Delete the row in the booking table
		deleteBookingRows($conn, $username,$status);
		
		// All ok commit	
		mysqli_commit($conn);
		} catch (Exception $e) {
			// Rollback and return the exception message
			mysqli_rollback($conn);
			mysqli_close($conn);
			return $e->getMessage();
		}
	return "You have successfully deleted your booking"; 
	mysqli_close($conn);
}

// Deletes all the username row in booking table
/*
function deleteBookingRows($conn, $username){
	$query = "DELETE FROM seat WHERE username=?";
	if(!$stmt = mysqli_prepare($conn, $query)){
		throw new Exception("Error 0 in deleting the record, try again");
	}
	mysqli_stmt_bind_param($stmt, "s", $username);
	if(!mysqli_stmt_execute($stmt)){
		throw new Exception("Error 1 in deleting the record, try again");
	}
	mysqli_stmt_store_result($stmt);
	if(mysqli_stmt_affected_rows($stmt)==0){
		throw new Exception("Error 2 in deleting the record, try again");
	}
    mysqli_stmt_free_result($stmt);	
	mysqli_stmt_close($stmt);	
}
*/
function deleteBookingRows($conn, $username, $status){
	//throw new Exception("Error 2try again" . $username . $status);
	$query = "DELETE FROM seat WHERE username=? AND status=?";
	//$query = "DELETE FROM seat WHERE username='". $username ."' AND status='". $status ."'";
	if(!$stmt = mysqli_prepare($conn, $query)){
		throw new Exception("Error 0 in deleting the record, 0try again");
	}
	mysqli_stmt_bind_param($stmt, "si", $username, $status);
	if(!mysqli_stmt_execute($stmt)){
		throw new Exception("Error 1 in deleting the record, 1try again");
	}
	mysqli_stmt_store_result($stmt);
	if(mysqli_stmt_affected_rows($stmt)==0){
		throw new Exception("Error 2 in deleting the record, 2try again" . $username . $status);
	}
    mysqli_stmt_free_result($stmt);	
	mysqli_stmt_close($stmt);	
}
//delete reserved seat (click on a yellow square)
function deleteBookingS($seats){
	$conn=connectDB();	
	$list = explode( "," , $seats);
	try {
		// Disable autocommit
		mysqli_autocommit($conn, false);

		// Delete the row in the booking table
		deleteBookingRow($conn, $list);
		
		// All ok commit	
		mysqli_commit($conn);
		} catch (Exception $e) {
			// Rollback and return the exception message
			mysqli_rollback($conn);
			mysqli_close($conn);
			return $e->getMessage();
		}
	return "You have successfully deleted your reservation"; 
	mysqli_close($conn);
}

// Deletes the row in seat table
function deleteBookingRow($conn, $seats){
	$query = "DELETE FROM seat WHERE pos=?";
	if(!$stmt = mysqli_prepare($conn, $query)){
		throw new Exception("Error in deleting the record, try again");
	}
	for($i=0;$i<count($seats);$i++){
		mysqli_stmt_bind_param($stmt, "i", $seats[$i]);
		if(!mysqli_stmt_execute($stmt)){
			throw new Exception("Error in deleting the record, try again");
		}
		mysqli_stmt_store_result($stmt);
		if(mysqli_stmt_affected_rows($stmt)!=1){
			throw new Exception("Error in deleting the record, try again");
		}
		mysqli_stmt_free_result($stmt);	
	}
	mysqli_stmt_close($stmt);	
}
?>