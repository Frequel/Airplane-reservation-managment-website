<?php
$dbhost  = 'localhost';    
$dbname  = 's257563'; 
$dbuser  = 's257563';     
$dbpass  = 'gsbached';     
$appname = "MyAirplane"; 
$capacity = 4;

// DB CONNECTION
// Connects to the DB
function connectDB(){
	global $dbhost, $dbname, $dbuser, $dbpass;	
	$conn = mysqli_connect($dbhost, $dbuser, $dbpass,$dbname);
	if (!$conn) {
		die('Connect error ('. mysqli_connect_errno() . ') '. mysqli_connect_error());
	}
	return $conn;
}

// LOGIN AND LOGOUT
// See the username and the password are in the db and they match 
function login($username, $psw){
	$conn=connectDB();
	$query = "SELECT password FROM user where username=?";
	if ($stmt = mysqli_prepare($conn, $query)) {
		mysqli_stmt_bind_param($stmt, "s", $username);
		if(!mysqli_stmt_execute($stmt)){
			return false;
		}
		mysqli_stmt_bind_result($stmt, $stored);
	    mysqli_stmt_fetch($stmt);	
	    $res=password_verify($psw, $stored);
		mysqli_stmt_close($stmt);
	}else {
		return false;
	}
	mysqli_close($conn);
	return $res;
}

// Does the logout, destroying cookies and session
function logout(){
	if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 3600*24,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
	session_destroy();
}


// REDIRECTION FUNCTIONS
// Redirection to $location
function redirect($location){
	header('Location: '.$location);
	exit();	
}

// Redirect using HTTPS
function httpsRedirect(){
	if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS']==='off'){
		$redirect_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	    header("Location: $redirect_url");
	    exit();
	}	
}


// COOKIES
// Test if cookies are enabled. It sets a cookie and redirect to itself
function testCookie(){
	if(!isset($_GET['cookie'])){
		setcookie('test', 1, time()+3600);
		if(isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING']!=""){
			header('Location:'.$_SERVER['PHP_SELF'].'?cookie=true&'.$_SERVER['QUERY_STRING']);
		}else{
			header('Location:'.$_SERVER['PHP_SELF'].'?cookie=true');
		}
		
	}else{
		if(count($_COOKIE) <= 0){
	    	header('Location: blockNavigation.php');
		}
	}	
}


// AUTHENTICATION
// Checks if the user authentication is still valid.
// An authentication is valid if it has last less than 2 
// minutes since the last page load
function checkTime(){
	$diff=time()-$_SESSION['time'];
	if($diff>2*60){
		logout();	
		redirect('login.php?msg=timeout');
	}
	$_SESSION['time']=time();	
}

?>