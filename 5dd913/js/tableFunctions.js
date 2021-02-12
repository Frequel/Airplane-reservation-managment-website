/*
 * A simple spreadsheet. Library of functions.
 * Object-oriented MVC version
 */
posti = new Array();
/* The Model */
function SpreadModel(M,N) {
	this.N = parseInt(N); 	// the number of rows
	this.M = parseInt(M);		// the number of columns
	this.selectedCell = new Object();	// the model of the currently selected cell
	this.cellObjects = new Array(this.N);		// the model of the cells
	this.pos = null;

	// filling cellObjects with objects that represent cells
	for (var i=0; i<this.N; i++) {
		this.cellObjects[i] = new Array(this.M);
		for (var j=0; j<this.M; j++)
			this.cellObjects[i][j] = new Object();
	}

	// function that evaluates the expression of a cell (and triggers view refreshing)
	SpreadModel.prototype.evaluate = function(i,j) {
		// for the moment this function just takes value = expression
	    // TODO: interpretation of expression has to be computed here.
	    this.cellObjects[i][j].value = this.cellObjects[i][j].expr;
	};

	// function that updates cell selection (and triggers view refreshing)
	SpreadModel.prototype.updateSelectedCell = function (i,j) {
	    this.selectedCell.i=i;
	    this.selectedCell.j=j;

	};

	//function that updates the selected cell expression (and triggers view refreshing)
	SpreadModel.prototype.updateCell = function (i,j,expr) {
		this.cellObjects[i][j].expr = expr;
		this.evaluate(i,j);
	};
}



/* The View */
function SpreadView(txtfld, parent, id, model, logged, booked) {
	this.txtfld = txtfld;	// The textfield node of the HTML DOM tree
	this.parent = parent;	// The parent node where the HTML table has to be placed
	this.name = id;			// the id to be used for the view
	this.model = model;		// The model that stores data
	this.selectedHTMLCell = undefined;	// The selected cell node of the HTML DOM tree
	this.table = undefined;				// the table node of the HTML DOM tree
	this.logged = logged;
	this.booked = booked;

	// function that sets the class attribute of a cell (HTML)
	SpreadView.prototype.setClass = function (cell, c) {
	    if (cell != undefined)
	        cell.setAttribute("class", c);
	};

	// function that unsets the class attribute of a cell (HTML)
	SpreadView.prototype.unsetClass = function (cell) {
		if (cell != undefined)
			cell.removeAttribute("class");
	};

	// function that gets cell id from cell coordinates
	SpreadView.prototype.getCellId = function (i,j) {
	    return this.name+"_c_"+i+"_"+j;
	};

	// function that gets header id from header type and position
	SpreadView.prototype.getHeadId = function (type,i) {
	    return this.name+"_"+type+"_"+i;
	};

	// function that gets cell coordinates from a HTML cell (returns array of two coordinates)
	SpreadView.prototype.getCoordinates = function (cell) {
		if (cell != undefined)
			return cell.getAttribute("id").split("_").slice(2,4);
		else
			return undefined;
	};

	// function that creates the HTML view
	SpreadView.prototype.initHTML = function () {
	    // create table element within parent element with id including this view's id
    	this.table = document.createElement('table');
	    this.table.setAttribute("id", this.name+"table");
	    this.parent.appendChild(this.table);

	    // create single cells and initialize them
	    for (var i=0; i<(this.model.N)+1; i++) {
	    	// create ith row
	    	var row=this.table.insertRow(i);
	    	for (var j=0; j<(this.model.M)+1; j++) {
	    		// create jth cell in ith row
	    		var cell=row.insertCell(j);
	    		if (i==0 && j>0) {	// first row is header row
	                // each header cell is labeled by a letter starting from A
	                cell.innerHTML=String.fromCharCode("A".charCodeAt(0)+j-1);
	                // each cell is assigned an id computed from the cell position
	                cell.setAttribute("id", this.getHeadId("ch",j-1));
	            }
	            else if (i>0 && j==0) {	// first column is header column
	                // each header cell is labeled by an integer starting at 0
	                cell.innerHTML=i;
	                // each cell is assigned an id computed from the cell position
	                cell.setAttribute("id", this.getHeadId("rh",i-1));
	            }
	            else if (!(i==0 && j==0)) { // middle cells
	                // each cell is assigned an id computed from the cell coordinates
	                cell.setAttribute("id", this.getCellId(i-1,j-1));
	            } 
				else if(i==0 && j==0){
					cell.setAttribute("id","to_hide");
				}
	        };
	      };
	};

	// function that adds a listener for the change event
	SpreadView.prototype.addChangeListener = function (listener) {
		this.txtfld.addEventListener('keyup', listener);
	};

	// function that adds a listener for the select event
	SpreadView.prototype.addSelectListener = function (listener) {
		var trlist = this.table.getElementsByTagName("tr");
	    for (var i=0; i<trlist.length; i++) {
	        trlist[i].addEventListener('click', listener);
	    }
	};

	// function that refreshes cell selection in the HTML table
	SpreadView.prototype.refreshSelectedCell = function (oview) {
	    // unset the class of the currently selected cell (if any)
	    this.unsetClass(this.selectedHTMLCell);	// unset class of currently selected element
	    // and of the corresponding headers
	    var coord;
	    if (this.selectedHTMLCell!=undefined) {
	        coord = this.getCoordinates(this.selectedHTMLCell);
	        this.unsetClass(document.getElementById(this.getHeadId("rh",coord[0])));
	        this.unsetClass(document.getElementById(this.getHeadId("ch",coord[1])));
	    }

	    // set the class of the cell to be selected to "selected"
	    i = this.model.selectedCell.i;
	    j = this.model.selectedCell.j;
	    var cell = document.getElementById(this.getCellId(i,j));
	    this.setClass(cell, "selected");
	    this.setClass(document.getElementById(this.getHeadId("rh",i)), "selected");
	    this.setClass(document.getElementById(this.getHeadId("ch",j)), "selected");
		var i = parseInt(this.model.selectedCell.i, 10);
		var j = parseInt(this.model.selectedCell.j, 10);
		var num = i*M+j+1;

	    // update the selected cell
	    this.selectedHTMLCell=cell;
		if(this.logged==true /*&& this.booked==false*/){
			if(cell.style.backgroundColor!="red" && cell.style.backgroundColor!="yellow" /*&&window.confirm("press OK to proceed")*/){
				
				//click a seat to check if a seat is available
				$.post(
					"reserveSeat.php", 
					{ pos: num },
					function(data) { 
						var res = data.split(",");
						$("#bookingError").text(res[1]);
						if(res[0]==1){
							$("#bookingError").css('color', 'yellow');
							cell.style.backgroundColor="yellow";
							posti[num]=1;
						}else if (res[0]==0){
							$("#bookingError").css('color', 'red');
							cell.style.backgroundColor="red";
						}
					}
				);			
				
			}else if(cell.style.backgroundColor=="yellow"){
				$.post(
					"removeSeat.php", 
					{ pos: num },
					function(data) { 
						$("#bookingError").text(data);
						$("#bookingError").css('color', 'black');
					}
				);
				
				cell.style.backgroundColor="green";
				posti[num]=0;
				delete posti[num];
				
			}else
				alert("seat already booked!");

			// set the textfield to the expression associated with the selected cell in the model
			var expression = this.model.cellObjects[i][j].expr;
			this.txtfld.value = (expression==undefined) ? "" : expression;
		} else if(this.logged==false)
			alert("you must be logged in to select seats");//potrei cambiare error message semplicemente
		else if(this.booked==true)
				alert("you have already booked!");//potrei cambiare error message semplicemente
	};

	// function that refreshes a cell of the HTML table with the specified value
	SpreadView.prototype.refreshCell = function (i,j) {
	    var cell = document.getElementById(this.getCellId(i,j));
	    cell.innerHTML = this.model.cellObjects[i][j].value;
	};

	this.initHTML();
}

/* The Controller */
function SpreadController (model, views) {
	this.model = model;
	if (views!=undefined)
		this.views = views;
	else
		this.views = new Array();

	// Add listeners to views
	for (var i=0; i<this.views.length; i++) {
		let view = this.views[i];
		let _this = this;
		view.addSelectListener( function(event){
			_this.selectCell(view.getCoordinates(event.target), view); } );
		view.addChangeListener( function(event){
			_this.updateExpr(event.target.value); } );
	}

	// function that selects a cell
	// (called upon a select event)
	SpreadController.prototype.selectCell = function (coord, oview) {
		this.model.updateSelectedCell(coord[0],coord[1]);
		for (var i=0; i<this.views.length; i++) {
			var view = this.views[i];
			view.refreshSelectedCell();
		}
		oview.selectTextfield();
	};


}

Object.size = function(arr) 
{
    var size = 0;
    for (var key in arr) 
    {
        if (arr.hasOwnProperty(key)) size++;
    }
    return size;
}

function myFunction() {
var form = document.getElementById('mySelectionForm');
form.setAttribute('action', "booking.php");
var i = parseInt(this.model.selectedCell.i, 10);
var j = parseInt(this.model.selectedCell.j, 10);
document.getElementById('nSeats').value = (Object.size(posti));
document.getElementById('seats_numbers').value = (Object.keys(posti));
console.log(Object.keys(posti));
}

function colorCell(id, usr, s, M, currentUser){
	var num = id-1;
	this.i = parseInt(num/M); //riga
	this.j = num%M; //colonna
	this.s = s;
	this.usr = usr;
	
	var name = "main_c_"+i+"_"+j;
	var cell = document.getElementById(name);
	
	if(s==0)
		cell.style.backgroundColor="green"; 
	else if(s==1 && this.usr != currentUser)
		cell.style.backgroundColor="orange";
	else if(s==1 && this.usr == currentUser){
		cell.style.backgroundColor="yellow";
		var pos = this.i*M+this.j+1;
		posti[pos]=1;
	}
	else if(s==2)
		cell.style.backgroundColor="red"; 
	
}

function updateSeats(col,currentUser){
	$.post(
		"updateSeats.php", null, 
		function(data) {
			$("td[id^='main_c_']").css('backgroundColor', 'green');
			//posti = [];
			var array = JSON.parse(data);
			//$("#nSeats").val()=(Object.size(array));
			document.getElementById('nSeats').value = (Object.size(array));
			var a=Object.size(array);
			//console.log(a +" " +array);
			for(i=0;i<(Object.size(array));i++){
				var vett = JSON.parse(array[i]);
				var num = vett[0]-1;
				var k = parseInt(num/M); //riga
				var j = num%M; //colonna
				var s = vett[2]
				var usr = vett[1];
				var name = "main_c_"+k+"_"+j;
				var cell = document.getElementById(name);
				var pos = k*M+j+1;
				console.log(pos);
				if(s==0)
					cell.style.backgroundColor="green"; 
				else if(s==1 && usr != currentUser){
					cell.style.backgroundColor="orange";
					delete posti[pos];
				}
				else if(s==1 && usr == currentUser){
					cell.style.backgroundColor="yellow";
					
					posti[pos]=1;
					
				}
				else if(s==2){
					cell.style.backgroundColor="red"; 
					delete posti[pos];
				}
			}
		}
	);
}



