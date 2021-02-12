$(document).ready(function(){
	$booking=true; // To decide if the modal is called for deleting/booking confirmation
	
	// FUNCTIONS
	 
	// If so it opens the modal and ask confirmation for the booking
	function checkForBooking(){		
			if($("#nSeats").val() != 0){
				return 0;				
			}else{
				return 1;				
			}			
		
	}
	
	
	// When clicking the booking button it checks if the booking can go on, if 
	// not prints an error message
	$("#bookButton").click(function(){
		$("#errorMsg").text("");
		switch(checkForBooking()){
		case 0:
				//CHECK if seat isn't booked in the meanwhile
				$("#modalTitle").text("Booking confirmation");
				$("#modalText").text("Are you sure to book ");
				var seat = $(seats_numbers).val().split(",");
				//var seat = $(seats_numbers).val();
				for(k=0;k<$(nSeats).val();k++){
					var num = seat[k] -1;
					i = parseInt(num/M); //riga
					j = num%M; //colonna
					i++; j+=65;
					//console.log(seat[k] + " " + M + " " + i + " " + j + " " + String.fromCharCode(j));
					$("#modalText").append(i + String.fromCharCode(j) + " ");
				}
				$("#homeModal").css({'display':'block'});
				break;
		case 1:
				$("#errorMsg").text("Select at least one seat!");
				break;
		case 2:
				$("#errorMsg").text("The departure address has to be before the arrival one");
				break;
				
		
		}
		
	});
	
	// When clicking on delete button, it opens the modal to ask confirmation for the action
	$("#delete").click(function(){
		$booking=false;
		$("#modalTitle").text("Delete confirmation");
		$("#modalText").text("Are you sure to delete your booking?");
		$("#homeModal").css({'display':'block'});
	});
	
	// MODAL
	// When clicking on X the modal disappeares
	$("#close").click(function(){
		$("#homeModal").css({'display':'none'});
	});
	
	// When clicking on No the modal disappeares
	$("#cancel").click(function(){
		$("#homeModal").css({'display':'none'});		
	});
	
	// Depending if the modal has been opened for booking or deleting, when clicking Yes
	// it continues the execution
	$("#confirm").click(function(){
		if($booking){
			$("#mySelectionForm").submit();
		}else{
			window.location.href = "delete.php?status=2";
		}
	});
	
	// Clicking outside the modal, it is closed
	$(window).click(function(event){
		if (event.target.id =="homeModal") {
	    	$("#homeModal").css({'display':'none'});
	    }
	});
});