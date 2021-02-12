$(document).ready(function(){
// FUNCTION DEFINITION
	
// Checks if the passwords are equivalent	
	function checkMatching(){
		var psw1=$("#psw1").val();
		var psw2=$("#psw2").val();
		return psw1===psw2;	
	}

// Checks if the username is a valid email address and its length is in the limit of the db
	function checkEmail(){
		var pattern=/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return pattern.test($("#username").val()) && $("#username").val().length<=255;
	}

/*Checks if the password is valid:
* Valid password:
* - at least a lowercase char and an uppercase char or a digit
* - 2<=length<=255 (db limit)
*/
	function checkPsw(){		
		var pattern=/.*[a-z].*[A-Z0-9].*|.*[A-Z0-9].*[a-z].*/;
		
		// Check password length
		if($("#psw1").val().length<2){				
			return 1;
		}else if($("#psw1").val().length>255){
			return 3;
		// Check password format
		}else if(!pattern.test($("#psw1").val())){
			return 2;
		}else{
			return 0;
		}		
	}
	
// Checks, through an AJAX request, if the username has already been used
	function checkUsername(){
		$.post("checkUsername.php",
			    {
			        username: $("#username").val()
			    },
			    function(data, status){
			    	$("#errorMsg3").text("");
			    	if(parseInt(data)!=0){
			    		$("#errorMsg3").text("Username already used");	
			    	}
			    });
	}

// If all the fields of the form are correctly filled enables the submit button
	function tryEnable(){
		if(checkEmail() && checkPsw()==0 && checkMatching() && $("#errorMsg3").val()===""){
			$("#submit").removeAttr("disabled");			
		}else{
			$("#submit").attr("disabled","disabled");
		}
		
	}

// ACTIONS
	
// Check username field
	$("#username").change(function(){
		$("#errorMsg3").text("");
		if(!checkEmail()){
			$("#errorMsg3").text("Insert a valid email address");			
		}else {
			checkUsername();			
		}
		tryEnable();
	});
	
// Check first password field
	$("#psw1").change(function(){
		// Reset error message
		
		switch(checkPsw()){
			case 1:
				$("#errorMsg").text("Invalid password: enter at least 2 characters");
				break;
			case 2:
				$("#errorMsg").text("Invalid password: enter at least a lowercase character and a uppercase character or digit");		
				break;
			case 3:
				$("#errorMsg").text("Invalid password: too many characters");		
				break;
			default:
				$("#errorMsg").text("");
				break;
		}
		
		// Check matching with second password
		$("#errorMsg2").text("");
		if(!checkMatching()){
			$("#errorMsg2").text("Passwords don't match");
		}
		
		tryEnable();
	});
	
// Check second password field
	$("#psw2").change(function(){
		// Check matching with first password
		$("#errorMsg2").text("");
		if(!checkMatching()){
			$("#errorMsg2").text("Passwords don't match");
		}
		tryEnable();
	});
});