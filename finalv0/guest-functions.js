// JavaScript Document
function updateName()
{
	var form = document.forms[1]; // The form we are looking at
	if( typeof localStorage.fName != "undefined" ){ // If false then there is a name set if true there is not
		delete localStorage.fName; // Log out
		// Instead of coping and pasting we can just call the checkuser functuon since it doesnt actually log in or out
		checkUser();
	}
	else{
		// This is logging in code
		localStorage["fName"] = form.elements["fName"].value; // From the elements of the form find txtFirstName and get the value
		
		// Set the new customer's name into local storage and
		// Redisplay the guest area using "set Guest" function
		setGuest( form.elements["fName"].value );
		// Should do it here too
		checkUser();
	}

	// Set the new customer's name into local storage and
 	// redisplay the guest area using "set Guest" function
}

function clearName()
{
	localStorage.setItem("fName", fName);
	setGuest();
	
 // reset the customer's name to nothing and
 // redisplay the guest area using "set Guest" function		
}

function setGuest()
{
	name = localStorage.getItem("fName");
	var guestArea = document.getElementById("divGuestArea"); // Think ths was the wrong DOM element
	
	if(localStorage.getItem("fName") == null || localStorage.getItem("fName") == "")
	{
		document.getElementById("divGuestArea").innerHTML='Guest';
	}
	else
	{
		guestArea.innerHTML = name;
	} 
	
	// if the customer has not signed in, then call them "guest" 
	// and give them a text box and button to sign in with.
	
	// if the customer already signed in, welcome them by name 
	// and give them a buttom to sign out with. 
	
	// Place all of these items in the given "guest area" div.
}

/**********Add checkUser()**********/
function checkUser(){
	if( typeof localStorage.fName == "undefined" ){ //if false then there is a name set if true there is not
		//so true if they are not logged in
		//so no we need to setName with what is stored in localstorage
		setGuest( "Guest" ); //sets the name in the welcome message
		document.getElementById("loginInput").style.display = "initial";//that would probably be better if you change the css somehwere down the line it will go back to what its suppose to be
		document.getElementById("signInButton").value = "Sign-In";
	} else{
		//we can use a function u already made
		setGuest( localStorage.fName ); //sets the name in the welcom message
		//after we set the name we have to change the button text and remove the input
		document.getElementById("loginInput").style.display = "none"; //will hid the input 
		//when we hide stuff we also have to unhide when we are not logged in
		
		//since we are logged in we dont want any of that input or the prompt that goes with it
		//so by grouping it into a div we can remove both at once
		
		//so when we are logged in we need to change the text
		document.getElementById("signInButton").value = "Sign-Out"; //also have to undo that when logged out
	}
}

/**********PrintCart**************/
function PrintCart() {
	var Div = document.getElementById('CartContents');
	Div.style.border="2px solid black";
	
	out("<table>");
	showCart();
	out("</table>");
}

/**********Submit**************/

