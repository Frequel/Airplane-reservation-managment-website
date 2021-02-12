<?php
// SIGNING UP FUNCTIONS
// Check the correctness of the password in the registration form
function checkPsw($psw1, $psw2){
	return strlen($psw1)>=2 && strlen($psw1)<=255 && checkPswContent($psw1) && $psw1==$psw2;	
}

// Checks that the password content is correct (at least one lower case char and an upper case char or a digit)
function checkPswContent($psw){
	$pattern='/.*[a-z].*[A-Z0-9].*|.*[A-Z0-9].*[a-z].*/';
	return preg_match($pattern, $psw);	
}

// Check correctness of the username (a valid email address)
function checkEmail($username){
	return filter_var($username, FILTER_VALIDATE_EMAIL) && strlen($username)<=255 && htmlentities($username)==$username;
}

// Checks if the username is already in the db
function checkUsername($username){
	$conn=connectDB();	
	$res=null;
	$query = "SELECT * FROM user where username=?";
	if ($stmt = mysqli_prepare($conn, $query)) {
		mysqli_stmt_bind_param($stmt, "s", $username);
		if(!mysqli_stmt_execute($stmt)){
			return $res;
		}
		mysqli_stmt_store_result($stmt);
		$res=mysqli_stmt_num_rows($stmt);
		mysqli_stmt_free_result($stmt);
		mysqli_stmt_close($stmt);
	}
	else {
		return $res;
	}
	mysqli_close($conn);
	return $res;
	
}

// Inserts a new user
function insertUser($username, $psw){
	$conn=connectDB();
	$query="INSERT user(username, password) VALUES (?,?)";
	if($stmt = mysqli_prepare($conn, $query)){
		if(!$hash=password_hash($psw, PASSWORD_DEFAULT)){
			mysqli_close($conn);
			return false;
		}
		mysqli_stmt_bind_param($stmt, "ss", $username, $hash);
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

?>