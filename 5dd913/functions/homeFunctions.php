<?php // HOME FUNCTIONS

//gets all the seat not availabe
function getSeats(){
	$conn=connectDB();
	$result=array();

	try {
		// Disable autocommit
		mysqli_autocommit($conn, false);
		
		// Get all the seat
		$result=getList($conn);
				
		// All ok commit	
		mysqli_commit($conn);
		} catch (Exception $e) {
			// Rollback and give back the exception message
			mysqli_rollback($conn);
			mysqli_close($conn);
			redirect("error.php");
		}
		
		mysqli_close($conn); 
		return $result;
	
}

// Returns all the addresses
function getList($conn){
	$result=array();
	$i=0;
	if ($res = mysqli_query($conn, "SELECT * FROM seat")){
		while ($row = mysqli_fetch_array($res)) {
			$result[$i++]=$row;
    	}		
		mysqli_free_result($res); 
	}else{
		throw new Exception("Exp retriving seats");
	}
	return $result;
}




// Returns the bookings in the segment $from-$to
function getBookings($conn, $from, $to){
	$list=array();
	$i=0;
	$query = "SELECT username, nPeople FROM BOOKING WHERE departure<=? AND arrival>=?";
	
	if ($stmt = mysqli_prepare($conn, $query)) {
		
		mysqli_stmt_bind_param($stmt, "ss", $from, $to);
		if(!mysqli_stmt_execute($stmt)){
			throw new Exception("Exp getBookings");
		}
		mysqli_stmt_store_result($stmt);
		$rows=mysqli_stmt_num_rows($stmt);		
		mysqli_stmt_bind_result($stmt, $username, $people);
		while (mysqli_stmt_fetch($stmt)) {
			$list[$username]=$people;
    	}
    	mysqli_stmt_free_result($stmt);	
		mysqli_stmt_close($stmt);
		
		$counter=0;
		foreach ($list as $key=>$value){
			$passengers=($value==1)?"passenger":"passengers";
			echo "user ".htmlentities($key)." (".htmlentities($value)." ".$passengers.")";
			if($counter++!=$rows-1){
				echo ", ";
			}
		}
		if($counter==0){
			echo "empty";
		}
	}
	else {
		throw new Exception("Exp getBookings");;
	}
	
}

// Opens and closes the connection and checks if the user has active bookings
function checkUserBooking($username){
	$conn=connectDB();
	$result=getUserBooking($conn, $username);
	mysqli_close($conn);
	return $result;
}

// Gets the reservation of the user, if any
function getUserBooking($conn, $username){
	$query = "SELECT pos FROM seat WHERE username=? AND status = 2";
	if(!$stmt = mysqli_prepare($conn, $query)){
		throw new Exception("Exp getUserBooking");
	}
	mysqli_stmt_bind_param($stmt, "s", $username);
	if(!mysqli_stmt_execute($stmt)){
		throw new Exception("Exp getUserBooking");
	}
	mysqli_stmt_store_result($stmt);
	mysqli_stmt_bind_result($stmt, $result['pos']);
	mysqli_stmt_fetch($stmt);
	if(mysqli_stmt_num_rows($stmt)==0){
		return false;
	}
    mysqli_stmt_free_result($stmt);	
	mysqli_stmt_close($stmt);
	return $result;
}

function checkSeats($M,$N){
	$conn=connectDB();
	$query = "SELECT r, c FROM dimension";
	if(!$stmt = mysqli_prepare($conn, $query)){
		throw new Exception("Exp checkSeats");
	}
	if(!mysqli_stmt_execute($stmt)){
		throw new Exception("Exp checkSeats");
	}
	mysqli_stmt_store_result($stmt);
	mysqli_stmt_bind_result($stmt, $result['r'],$result['c']);
	mysqli_stmt_fetch($stmt);
	if(mysqli_stmt_num_rows($stmt)==0){
		//echo "mannaggia la madonna";
		return false;
	}
    mysqli_stmt_free_result($stmt);	
	mysqli_stmt_close($stmt);
	if($result['r'] != $N || $result['c']!= $M){
		$query = "DELETE FROM dimension";
		if(!$stmt = mysqli_prepare($conn, $query)){
			throw new Exception("Exp deleting dimension tuple");
		}
		if(!mysqli_stmt_execute($stmt)){
			throw new Exception("Exp  deleting dimension tuple");
		}
		$query = "DELETE FROM seat";
		if(!$stmt = mysqli_prepare($conn, $query)){
			throw new Exception("Exp deleting seats tuple");
		}
		if(!mysqli_stmt_execute($stmt)){
			throw new Exception("Exp deleting seats tuple");
		}
		$query="INSERT INTO dimension( r , c ) VALUES (?,?)";
		if($stmt = mysqli_prepare($conn, $query)){
		
			mysqli_stmt_bind_param($stmt, "ss", $N, $M);
				if(!mysqli_stmt_execute($stmt)){
					mysqli_close($conn);
					return false;
			}
			mysqli_stmt_store_result($stmt);
			$res=mysqli_stmt_affected_rows($stmt)==1;
			mysqli_stmt_free_result($stmt);
			mysqli_close($conn);
			return $res;
		}else{
			mysqli_close($conn);
			return false;			
		}
		
	}
	mysqli_close($conn);
	return true;	
		
}

function countRed(){
	$conn=connectDB();
	$result;
	$status =2;
	try {
		// Disable autocommit
		mysqli_autocommit($conn, false);
		
		$query = "SELECT COUNT(*) FROM seat WHERE status=?";
		if(!$stmt = mysqli_prepare($conn, $query)){
			throw new Exception("Exp getRedSeat");
		}
		mysqli_stmt_bind_param($stmt, "i", $status);
		if(!mysqli_stmt_execute($stmt)){
			throw new Exception("Exp getRedSeat");
		}
		mysqli_stmt_store_result($stmt);
		mysqli_stmt_bind_result($stmt, $result);
		mysqli_stmt_fetch($stmt);
		if(mysqli_stmt_num_rows($stmt)==0){
			return false;
		}
		mysqli_stmt_free_result($stmt);	
		mysqli_stmt_close($stmt);
		
		// All ok commit	
		mysqli_commit($conn);
		} catch (Exception $e) {
			// Rollback and give back the exception message
			mysqli_rollback($conn);
			mysqli_close($conn);
			redirect("error.php");
		}
		
		mysqli_close($conn); 
		return $result;
}

function countOrange(){
	$conn=connectDB();
	$result;
	$status =1;
	try {
		// Disable autocommit
		mysqli_autocommit($conn, false);
		
		$query = "SELECT COUNT(*) FROM seat WHERE status=?";
		if(!$stmt = mysqli_prepare($conn, $query)){
			throw new Exception("Exp getOrangeSeat");
		}
		mysqli_stmt_bind_param($stmt, "s", $status);
		if(!mysqli_stmt_execute($stmt)){
			throw new Exception("Exp getOrangeSeat");
		}
		mysqli_stmt_store_result($stmt);
		mysqli_stmt_bind_result($stmt, $result);
		mysqli_stmt_fetch($stmt);
		if(mysqli_stmt_num_rows($stmt)==0){
			return false;
		}
		mysqli_stmt_free_result($stmt);	
		mysqli_stmt_close($stmt);
		
		// All ok commit	
		mysqli_commit($conn);
		} catch (Exception $e) {
			// Rollback and give back the exception message
			mysqli_rollback($conn);
			mysqli_close($conn);
			redirect("error.php");
		}
		
		mysqli_close($conn); 
		return $result;
	
}

?>