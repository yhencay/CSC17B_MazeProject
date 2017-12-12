<!DOCTYPE html>
<?php
    session_start();
    $_SESSION['name']="Cheryllynn";
    echo 'The name I stored on the server is: '.$_SESSION['name'];
?>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Cheryllynn Walsh Final Project Checkout</title>
	<link href="layout.css" type="text/css" rel="stylesheet">
	
<script type="text/javascript" src="guest-functions.js"></script>

<style>
 	body {background-color: #f2f2f2;}
</style>

<script>
window.onload=checkUser; //setGuest; // Look, checkUser() is called upon window load. 
// BUT all the code in <head>.....</head> outside any function 

/**********Complete Payment**************/
function CompletePayment() {
	//utility function to remove element from arrays
	function arrRemove( array, element ){
		var index = array.indexOf( element );
		if (index >= 0) {
		  array.splice( index, 1 );
		}
	}
	// Once the user clicks 'Complete Payment', the page can change to a 'Thanks for your purchase!' page.
	// You can put order info on that page. I think you would use localStorage to do this.
	
	//Save shipping info.
	var shipping_string = "";  // Initialize empty string
	
    //Get shipping info from form here & add to shipping_string.
	//Add HTML or formatting to shipping_string.
	var shippingMethod = document.getElementsByName('shipping');
	shipping_string += "<h3 style='text-decoration:underline;margin:0 0 0 0;'>";
	if( shippingMethod.item(0).checked ) {
		shipping_string += "Standard Shipping";
		shipping_cost = 2;
	}
	else if( shippingMethod.item(1).checked ) {
		shipping_string += "2-Day Shipping";
		shipping_cost = 5;
	}
	else if( shippingMethod.item(2).checked ) {
		shipping_string += "Overnight Shipping";
		shipping_cost = 10;
	}
	shipping_string += "</h3>";
	
	var first_name = document.getElementById("FirstName");
	var last_name = document.getElementById("LastName");
	shipping_string += "Name: <span style='font-weight:bold;'>" + first_name.value + " " + last_name.value + "</span><br />";

	//Create a div for formatting shipping info.
//	 var shippingAddress = "<table><tr><td>Shipping Address: </td><td><span style='font-weight:bold;'>";
	 var shippingAddress = "<div style='margin-top:1em;'><h3 style='text-decoration:underline;margin:0 0 0 0;'>Shipping Address:</h3>";
	     shippingAddress += document.getElementById('Address').value + "<br />"
                         +  document.getElementById('City').value + ", "
                    	 +  document.getElementById('State2').value  + " " 
                    	 +  document.getElementById('Zip2').value + "<br />"  
                    	 +  document.getElementById('email').value + "<br />"  
                    	 +  document.getElementById('phoneNote').value + "<br />";

     shippingAddress += "</div>";
	 shipping_string += shippingAddress;
					   
/***********paymentMethod***********/ 
// Starting from here, you have a code OUTSIDE functions. 
// This means that it will run BEFORE the page is loaded. So getElementById("payment") will NOT work because browser doesn't see it yet.
// If you placed it before </body> tag OR in window.onload block, it would run after the page is loaded. 
	var paymentMethod = document.getElementById("payment").value;
  	var ExpMon= document.getElementById("ExpMon").value;
  	var ExpYear= document.getElementById("ExpYear").value;
  	var cardNumber= document.getElementById("cardNumber");
  	var date = new Date ();
  	var month = date.getMonth();
  	var year = date.getFullYear();

	 //Get payment method & save to localStorage (like shipping address).
	 //Probably don't want to print card number.
	 var payment_string = "Payment method: <span style='font-weight:bold;'>"
	 payment_string += paymentMethod;
	 payment_string += "</span>";
	 
	//make an errors array
	var errors = [];

//If there is nothing in .innerHTML (i.e. it is null), put an empty  string in here so '+=' works.
//if( ! document.getElementById('receipt').innerHTML ) document.getElementById('receipt').innerHTML = "";
	
/*******First Name*******/
   if( document.getElementById( "FirstName" ).value.length == 0 ) //don't want to be empty. the string, then its empty
   {
   	//so if an error with the first
		document.getElementById("FirstName").style.color='red';
		document.getElementById("FirstName").style.border='1px solid red';
		alert("Empty First Name");
		errors.push( "Empty First Name" );
		//return;
	} 
	else 
	{
		document.getElementById("FirstName").style.color='black'; // if its matchs we want to
		document.getElementById("FirstName").style.border=''; // if its matchs we want to
	}

/*******Last name*******/
   if( document.getElementById( "LastName" ).value.length == 0 )
   {
   	//so if an error with the last
		document.getElementById("LastName").style.color='red';
		document.getElementById("LastName").style.border='1px solid red';
		alert("Empty Last Name");
		errors.push( "Empty Last Name" );
		//return;
	} 
	else 
	{
		document.getElementById("LastName").style.color='black'; // if its matchs we want to
		document.getElementById("LastName").style.border=''; // if its matchs we want to
	}

/*******Street Address*******/
   if( !StreetAddress( document.getElementById( "Address" ).value ) )
   {
   	//so if an erro with the phone
		document.getElementById("Address").style.color='red';
		document.getElementById("Address").style.border='1px solid red';
		alert("Invalid Address format");
		errors.push( "Invalid Address format" );
		//return;
	} 
	else 
	{
		document.getElementById("Address").style.color='black'; // if its matchs we want to
		document.getElementById("Address").style.border=''; // if its matchs we want to
	}
	
	if( !StreetAddress( document.getElementById( "City" ).value ) )
   {
   	//so if an erro with the phone
		document.getElementById("City").style.color='red';
		document.getElementById("City").style.border='1px solid red';
		alert("Invalid city format");
		errors.push( "Invalid city format" );
		//return;
	} 
	else 
	{
		document.getElementById("City").style.color='black'; // if its matchs we want to
		document.getElementById("City").style.border=''; // if its matchs we want to
	}

	if( !validateZip( document.getElementById ( "Zip2" ).value ) )
   // Since the isValidAccountNo( txtaccount.value ); does the testing of the regex and returns true if it matches
	{
		//so if an erro with the phone
		document.getElementById("Zip2").style.color='red';
		document.getElementById("Zip2").style.border='1px solid red';
		alert("Not a valid zip");
		errors.push( "Not a valid zip" );
		//return;
	} //thanks again for the candy.. hope you have a great christmas if i dont see you again next week
	else 
	{
		document.getElementById("Zip2").style.color='black'; // if its matchs we want to
		document.getElementById("Zip2").style.border=''; // if its matchs we want to
	}
   
   /*******E-mail*******/
   if( !validateForm( document.getElementById ( "email" ).value ) )
   // Since the isValidAccountNo( txtaccount.value ); does the testing of the regex and returns true if it matches
	{
		//so if an erro with the phone
		document.getElementById("email").style.color='red';
		document.getElementById("email").style.border='1px solid red';
		alert("Not a valid e-mail address");
		errors.push( "Not a valid e-mail address" );
		//return;
	} 
	else 
	{
		document.getElementById("email").style.color='black'; // if its matchs we want to
		document.getElementById("email").style.border=''; // if its matchs we want to
	}

   
  //Make the 'thank you' & save to localStorage.
	if (ExpMon.selectedIndex === 0){
		//instead of just alerting lets add this to the array
    	alert("Please select the month");
		errors.push( "Please select the month" );
	    //return false; // return to WHERE?
	}/// nice catch you grrr
	if (ExpYear.selectedIndex === 0){
    	alert("Please select the year");
	    //return false;
	}
	if (year> ExpYear || (year === ExpYear && month >= ExpMon)){
    	alert("The expiry date is before today's date. Please select a valid expiry date");
	    // return false;
	}
  	//cardNumber is now the entire input field , not just the value.
	if (cardNumber.value.length!=16  || isNaN(cardNumber.value)){
    	alert("Please enter 16 numbers for your credit card");
		errors.push( "Please enter 16 numbers for your credit card" );
        cardNumber.focus();
		cardNumber.style.color='red';
		cardNumber.style.border='1px solid red';
        //      return false;
     }else{
		cardNumber.style.color='black'; // if its matchs we want to
		cardNumber.style.border=''; // if its matchs we want to 
	 }
	 
	 if( !isValidPhoneNo( document.getElementById ( "phoneNote" ).value ) )
	// Since the isValidAccountNo( txtaccount.value ); does the testing of the regex and returns true if it matches
	{
		//so if an erro with the phone
		document.getElementById("phoneNote").style.color='red';
		document.getElementById("phoneNote").style.border='1px solid red';
		alert("Invalid Phone format");
		errors.push( "Invalid Phone format" );
		//return;
	} 
	else 
	{
		document.getElementById("phoneNote").style.color='black'; // if its matchs we want to
		document.getElementById("phoneNote").style.border=''; // if its matchs we want to
	}
	//Add other info to shipping_string here.
	
	
	// This should only run when you have no error right
	if( errors.length == 0 ){
		//Store to localStorage.
		localStorage.setItem( 'Shipping', shipping_string );
		localStorage.setItem( 'ShippingCost', shipping_cost );
		//localStorage.setItem( 'Shipping', shipping_string );
		localStorage.setItem( 'Payment', payment_string );  //Save to localStorage called a nonbreaking space
		var thanks_string ="<br/><strong>Thank you for paying with " + paymentMethod + ",&nbsp;" + first_name.value + "!</strong><br />";
		localStorage.setItem( 'Thanks',  thanks_string );
	  	document.getElementById('receipt').innerHTML +=  thanks_string;
		
		
		localStorage.setItem( 'state', document.getElementById('State2').value );
		// what 
		//would want to clear the cart here
		EmptytheCart_empty();
		window.location.href="thanks.php";
	}
	
	// alert( JSON.stringify( errors ) );

// May be, all this should go inside completePayment??
// it's just it says "Please enter 16 digits..." as soon as the page is loaded, before I ever entered anything
// And if all this is in CompletePayment(), nothing will happen until I press button!
	
}

/*************Add EmptytheCart_empty()*************/
function EmptytheCart_empty() //emptys all wnat to just do one
{
	if(localStorage.getItem( "cart" ) !== null)
	{
		var cart = {};
		cart = JSON.stringify( cart );
		localStorage.setItem( "cart", cart );
	}

	document.getElementById('q1').value = 0;
	document.getElementById('q2').value = 0;
	document.getElementById('q3').value = 0;
	document.getElementById('q4').value = 0;
	localStorage.setItem( "cart", null );
	
	document.getElementById("cartTable").innerHTML = "<tr><td>No Items In Cart</td></tr>";
}

/**********Street Address**************/
function StreetAddress(checked) {
// looks like its going to repalce the value of one input with another but these don't exist 
	/*if (checked) {  
      document.getElementById('Address').value = document.getElementById('ShippingAddress').value;   
      document.getElementById('City').value = document.getElementById('ShippingCity').value;   
      document.getElementById('State').value = document.getElementById('ShippingState').value;   
      document.getElementById('Zip2').value = document.getElementById('ShippingZip').value;   
      document.getElementById('email').value = document.getElementById('ShippingEmail').value;
	  document.getElementById('phoneNote').value = document.getElementById('ShippingPhoneNote').value;    
   } else {  
      document.getElementById('Address').value = '';   
      document.getElementById('City').value = '';   
      document.getElementById('State').value = '';   
      document.getElementById('Zip2').value = '';   
      document.getElementById('email').value = '';
	  document.getElementById('phoneNote').value = '';    
  }
  return address; */
  
  if( checked.length != 0) { 
  	return true; 
  } else {
	  return false;
  }
}

/**************validateZip******************/
function validateZip(x){
	var regex = /[0-9]{5}(-[0-9]{4})?/
	if(x.match(regex)) 
	{
	  return true;
	} 
  	else return false;
  }

/**************validateForm******************/
function validateForm(x) {
	//gonna pass it in
    //var x = document.forms["myForm"]["email"].value;
    var atpos = x.indexOf("@");
    var dotpos = x.lastIndexOf(".");
    if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length) {
        return false;
    }
	return true;
}

/*************isValidPhoneNO****************/
function isValidPhoneNo(PhoneNo) {
	document.getElementById("phoneNote").style.color='black';
  var regExp = /^\(?\d{3}\)?-?\s?\d{3}-\d{4}$/;
  // I would do this way:
  if(PhoneNo.match(regExp)) 
	{
	  return true;
	} 
  	else return false;
  }

/**********var products**********/
var products=[];
products["q1"] = {name: "Peyote Fan Earrings", description : "Earrings", price : "80.00"};
products["q2"] = {name: "Blue and Bringht pink Hugs and Kisses ring", description : "Rings", price : "25.00"};
products["q3"] = {name: "Crystal Lace", description : "Necklace", price : "120.00"};
products["q4"] = {name: "Bracelet Monaco", description : "Bracelet", price : "60.00"};

/************Add out************/
function out( x ){ document.write( x ); } //cause that is 2 much typing
function showCart()
{
	//so know we need to print out the cart
	//lets get teh cart
	if( localStorage.getItem( 'cart' ) !== null ){
		//print the cart	
		var cart = JSON.parse( localStorage.getItem("cart") );
		var overall_total_price = 0;
		document.write('<tr><th>Quantity</th><th>Update</th><th>Description</th><th>Price</th><th>Delete</th>');
		for(item in cart ){
			// So now we can easly get the names and prices without having to search through the whole array
			
			var quantity = cart[item];
			var price_per_unit = products[item].price;
			var total_price = quantity * price_per_unit;   // Total price of one particular item
			overall_total_price += total_price;   // Add total_price to overall_total_price while it is still a number
			total_price = total_price.toFixed(2);  // This turns total_price from a number into a string
			while( total_price.length < 15 ) { // Right-align total_price
			  total_price = " " + total_price;
			}

			var taxes = overall_total_price * .0885;
			var total_with_tax = overall_total_price + taxes;

			// So lets make it into a table
			out( "<tr><td>" );// Already have total price so just need to add the unit price
			out( "<input name='" + item + "' value='" + cart[item] + "' style='width:30px;'/></td><td>" + "<input type='image' src='button_update_cart.gif' onclick=\"updateItem('" + item + "')\"></td><td>" + products[item]["name"] + "</td><td> $" + total_price + "</td><td><input type='image' src='small_delete.gif' onclick=\"emptyItem('" + item + "')\">"); // to get the number stored 
			out( "</td></tr>" );
		}
		
	     overall_total_price = overall_total_price.toFixed(2); //Turn overall_total_price into a string with 2 decimals
		document.write('<tr><td colspan="4" style="border-width:0px; text-align: right;"><strong>Sub-Total: $</strong><strong>' + overall_total_price + '</strong></td></tr>');
	}else{
		document.write('<tr><td>No Items In Cart</td></tr>');
}
	//window.location.href = "thanks.html";  //This doesn't exist yet.
}

/***************Continue Shopping***************/
function ContinueShopping()
{
	var ContinueShopping = "shop.php";

	location.href = ContinueShopping;
}

// currently we would need to search through the array
// does he want it like this however other things
// we can change thing to make our life easier can wich picutre or however ok

/*************Add EmptytheCart_empty()*************/
function EmptytheCart_empty()
{
	if(localStorage.getItem( "cart" ) !== null)
	{
		var cart = {};
		cart = JSON.stringify( cart );
		localStorage.setItem( "cart", cart );
	}

	/*document.getElementById('q1').value = 0;
	document.getElementById('q2').value = 0;
	document.getElementById('q3').value = 0;
	document.getElementById('q4').value = 0;*/
	delete localStorage["cart"];
	
	document.getElementById("cartTable").innerHTML = "<tr><td>No Items In Cart</td></tr>";
}

/***************Add emptyItem***************/
function emptyItem( product ){
	if(localStorage.getItem( "cart" ) !== null)
	{
		var cart = JSON.parse( localStorage.getItem("cart") );//load the cart
		delete cart[product];//remove the item
		cart = JSON.stringify( cart ); //then save
		localStorage.setItem( "cart", cart );
		//chekky way
		location.reload();
	}
}

/**********Add Update**********/
function updateItem (product){
	if(localStorage.getItem( "cart" ) !== null)
	{
		var cart = JSON.parse( localStorage.getItem("cart") );//load the cart
		cart[product] = parseInt( document.getElementsByName(product)[0].value );
		cart = JSON.stringify( cart ); //then save
		localStorage.setItem( "cart", cart );
		//chekky way
		location.reload();
	}
  }
</script>

</head>

<body>
<header><center><img src="beedsmore3_thumb900.jpg" width="678" height="151" alt="My Company"/></center></header>
<nav>
<form id="form1" name="form1" method="post">
<p>Choose a page to visit:<br>
  <select name="select" id="select" onchange="window.location.href=this.value;">
    <option value="index.php">Home</option>
	<option value="survey.php">Survey</option>
    <option value="shop.php">Shop</option>
    <option value="cart.php">Cart</option>
 	<option value="checkout.php" selected="selected">Checkout</option>
  </select>
</p>
<p><strong>Welcome <span id="divGuestArea">Guest</span>!</strong></p>
</form>
<form method="GET" action="LoginServlet.php">
  <div id="loginInput">
    <p>Enter your name:<input type="text" name="fName" size="20"></p>
    </div>
  <p><input type="button" value="Sign-In" id="signInButton" onclick="updateName()"/></p>
  
</form>
</nav>

<div id="main">
<h1>Checkout</h1>
<p>
	<script>	
	if( localStorage.getItem( 'cart' ) === "null" ){
		document.write('<fieldset><tr><td><strong>Your Shopping cart is currently empty!</strong><br /> <br /><strong>We recommend you go straught to our <a href="shop.html">Shopping Page</a> to fill it up now!</strong></td></tr></fieldset>');
	}
	</script>
</p>

<div id="CartContents"></div>
<p>

<table id="cartTable" border="1" cellpadding="8">
<script>showCart();</script>
</table>
 <p>
   <input type="button" value="Back To Shopping" id="ContinueShopping" onClick="ContinueShopping()"/>
  </p>
 <p><br />
  <strong>Display Total Amount</strong></p>
 <fieldset id="step_1">
        <legend><strong><p>Shipping Method</strong></p></legend>
        <div id="attendee_1_wrap" class="name_wrap push">

        <input type="radio" id="Standard Shipping" name="shipping" value="Standard Shipping" checked />
        Standard Shipping ($2)
        
        <input type="radio" id="2-DayShipping" name="shipping" value="2-DayShipping" />
		2-DayShipping ($5)
        
		<input type="radio" id="Overnight Shipping" name="shipping" value="Overnight Shipping" />
		Overnight Shipping ($10)</p>
</div>
</fieldset>
    
	<fieldset id="step_2">
    <div align="right" class="alert red forward">* Required information *</div>
    <legend><strong>Shipping Address</strong><br /></legend>
    <div id= "attendee_2_wrap" class="name_wrap_pusch">
    
    <p>
    <label for="FirstName">
           First Name: 
    </label>
    <input type="text" id="FirstName" class="name_input">
    </p>
    <p>            <!-- for things that are in the same line you have to seperate them with a close p tag and opening since their are in p tag-->
    <label for="LastName">
           Last Name:
    </label>
        <input type="text" id="LastName" class="name_input" ></input>
    </p>
  
<p>
<label for="Address">Street Address: </label><input name="Address" id="Address" type="text" size="20" maxlength="75" /><span class="alert red">*</span>  
</p>

<p>
<label for="City">City: </label><input name="City" id="City" /><span class="alert red">*</span>
<label for="Zip2">Zip Code: </label><span class="alert red">*</span>
<input name="Zip2" id="Zip2" type="text" value="" size="5" maxlength="10"  />
<label for="State2">State: </label>
<select name="State2" id="State2">
  <option selected value=""></option>
  <option value="AK">AK</option>
  <option value="AL">AL</option>
  <option value="AR">AR</option>
  <option value="AZ">AZ</option>
  <option value="CA">CA</option>
  <option value="CO">CO</option>
  <option value="CT">CT</option>
  <option value="DC">DC</option>
  <option value="DE">DE</option>
  <option value="FL">FL</option>
  <option value="GA">GA</option>
  <option value="HI">HI</option>
  <option value="IA">IA</option>
  <option value="ID">ID</option>
  <option value="IL">IL</option>
  <option value="IN">IN</option>
  <option value="KS">KS</option>
  <option value="KY">KY</option>
  <option value="LA">LA</option>
  <option value="MA">MA</option>
  <option value="MD">MD</option>
  <option value="ME">ME</option>
  <option value="MI">MI</option>
  <option value="MN">MN</option>
  <option value="MO">MO</option>
  <option value="MS">MS</option>
  <option value="MT">MT</option>
  <option value="NC">NC</option>
  <option value="ND">ND</option>
  <option value="NE">NE</option>
  <option value="NH">NH</option>
  <option value="NJ">NJ</option>
  <option value="NM">NM</option>
  <option value="NV">NV</option>
  <option value="NY">NY</option>
  <option value="OH">OH</option>
  <option value="OK">OK</option>
  <option value="OR">OR</option>
  <option value="PA">PA</option>
  <option value="RI">RI</option>
  <option value="SC">SC</option>
  <option value="SD">SD</option>
  <option value="TN">TN</option>
  <option value="TX">TX</option>
  <option value="UT">UT</option>
  <option value="VT">VT</option>
  <option value="VA">VA</option>
  <option value="WA">WA</option>
  <option value="WV">WV</option>
  <option value="WI">WI</option>
  <option value="WY">WY</option>
</select>
</p>

<p><label for="email">E-mail:</label>
   <input type="email" name="email" id="email" placeholder="Your email.."><span class="alert red">*</span>
</p>

<p>
  <label for="Phone">Phone Number: </label><input name="Phone" id="phoneNote" placeholder="Your phone number.." type="text" value="" size="16" maxlength="13" /><span id="phoneNote"> (use "555-555-5555" format)</span></p>
</div>
     </fieldset>

<fieldset id="step_3">

  <legend><strong>Payment Details:</strong></legend>
    <p>
     <label for="txtPaymentDetails">
    <p>Payment Details:
<select id="payment">
  <option value="Visa">Visa Credit</option>
  <option value="MasterCard">MasterCard Credit</option>
  <option value="American Express">American Express Credit</option>
  <option value="Debit Card">Debit Card</option>
  <option value="Pay Pal">Pay Pal</option>
</select>
<p>
  <label for="Card">Credit card number #: </label>
  <input name="Card" type="text" class="name_input" id="cardNumber"><span class="alert red">*</span>
<form id="myform" method="post" action="" onsubmit="return Validate(this);">

<p>Expiration Month:

<select name="ExpMon" id="ExpMon" title="select a month"> 
        <option value="1">January</option> 
        <option value="2">February</option> 
        <option value="3">March</option>
        <option value="4">April</option> 
        <option value="5">May</option> 
        <option value="6">June</option> 
        <option value="7">July</option> 
        <option value="8">August</option> 
        <option value="9">September</option> 
        <option value="10">October</option> 
        <option value="11">Novemebr</option> 
        <option value="12">December</option> 
 </select>      

 Experiation Year:
 <select name="ExpYear" id="ExpYear" title="select a year"> 
     <option value="2016">2016</option> 
     <option value="2017">2017</option> 
     <option value="2018">2018</option> 
     <option value="2019">2019</option>
     <option value="2020">2020</option> 
     <option value="2021">2021</option>
     <option value="2022">2022</option> 
     <option value="2023">2023</option> 
     <option value="2024">2024</option> 
     <option value="2025">2025</option>
     <option value="2026">2026</option> 
     <option value="2027">2027</option>
 </select>
</p>
</form>
<div id="company_name_wrap"></div>

     <div id="special_accommodations_wrap">
       <label for="special_accomodations_text">
</fieldset>

    <p><INPUT onclick="CompletePayment()" type=button value="Complete Payment" name=PaymentButton></p>
    
    <p>&nbsp;</p>

<div id='receipt'></div>

<footer><center>
Copyright © 2016 Beads & More
</center></footer>
</div>
</body>
</html>