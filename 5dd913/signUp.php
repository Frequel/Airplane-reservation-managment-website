<?php //signUp.php
	include 'header.php';
	
	// Redirect using https
	httpsRedirect();
	
	// If logged user redirect
	if($loggedin){
		redirect("home.php");
	}
	// If there has been an error
	if (isset($_GET['msg']) && $_GET['msg']=='error'){
		$errorText="Error during signing up, try again";
	}else{
		$errorText="";
	}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Sign Up</title>
		<link href="css/home.css" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="js/jquery-3.3.1.min.js">
		</script>
		<script type="text/javascript" src="js/signUp.js">
		</script>
	</head>
	<body>
	<div id="main">
		<h1>Sign up to MyShuttle</h1>
		<p class="errorMsg"><?php echo $errorText;?></p>
		<form method="POST" action="signUpPost.php">			
			<label>Insert a username (a valid email address)<br><br><input 
									id="username" 
									type="email" 
									name="username"
									placeholder="something@domain.com" 
									required></label><br>
			<p id="errorMsg3" class="errorMsg"></p>
			<label>Insert a password (at least 2 characters, a lowercase character and an uppercase chararcter or a digit)<br><br><input 
									id="psw1"
									type="password" 
									name="psw1"
									placeholder="***************"
									required></label><br>
			<p id="errorMsg" class="errorMsg"></p>
			<label>Repeat the password<br><br><input 
									id="psw2"
									type="password" 
									name="psw2"
									placeholder="***************"
									required></label><br>
			<p id="errorMsg2" class="errorMsg"></p>
			<input class="button" type="submit" id="submit" value="Sign up" disabled>			
		</form>
	</div>
	</body>
</html>