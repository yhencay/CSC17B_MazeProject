<!DOCTYPE html>
<html lang="en"><!-- InstanceBegin template="/Templates/final-project.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <?php include'./login_proccess.php'?>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- InstanceBeginEditable name="doctitle" -->
  <title>Untitled Document</title>
  <!-- InstanceEndEditable -->
  <!-- Bootstrap -->
  <link href="css/bootstrap.css" rel="stylesheet">
  <link href="css/final_styles.css" rel="stylesheet">
  <link href="my_styles.css" rel="stylesheet">

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
<!--The following script tag downloads a font from the Adobe Edge Web Fonts server for use within the web page. We recommend that you do not modify it.--><script>var __adobewebfontsappname__="dreamweaver"</script><script src="http://use.edgefonts.net/source-sans-pro:n2,n4:default;patua-one:n4:default.js" type="text/javascript"></script>
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
</head>
  <body>
<div class="container">
<header class="row">
<div class="col-sm-12">
  <h1><img src="images/bluelg.jpg" width="968" height="460" alt=""/><span class="logowhite"></span></h1>
</div>
</header>
<nav class="row">
<div id="links"><span class="navlink"><a href="index.html">Home</a></span><span class="navlink"><a href="characters.html">Characters</a></span><span class="navlink"><a href="game.php">Game</a></span><span
class="navlink"><a href="login.php">Login</a></span><span 
</div>
</nav>
<center>
<article class="row">
  <section class="col-sm-10"><!-- InstanceBeginEditable name="Page Heading" --><!-- InstanceEndEditable --><!-- InstanceBeginEditable name="Main Page Content" -->
	  <center><h2>Login</h2></center>
<center><p><strong>Welcome <span id="divGuestArea">Guest</span>!</strong></p>
<script type="text/javascript">//functions
        new_setup=function(){
            document.getElementById('mode').innerHTML='new';
            console.log('start new user form');
            var new_form=document.getElementById('login_station').innerHTML+'</br><span>Icon: </span><input type="radio" id="user_contact" name="icon" value="0">Male</input> <input type="radio" id="user_contact" name="icon" value="1">Female</input></br>';
           
            document.getElementById('login_station').innerHTML=new_form;
        };
        
        verify_one=function(){//should have some method of protection
            var name=document.getElementById("user_name").value;
            var pass=document.getElementById("user_pass").value;
            //document.getElementById("feedback").innerHTML="Recieved "+name+" "+pass;
            //check inputs via regular expression
            //regular expression variables
            var reg_user=/[a-z]{3,30}/gi;
            var reg_pass=/[]/g;
            //RFC 5322
            var reg_cont='';
            
            var reg_veri=true;
            switch(document.getElementById('mode').innerHTML){//based on mode verification check
                case 'newpass':
                    document.getElementById("login_station").action='./login.php?mode=newpass';//change mode
                    
                    break;
                case 'login':
                    document.getElementById("login_station").action='./login.php?mode=login';//change mode
                    console.log('regex login');
                    console.log(name.search(reg_user));
                    console.log(pass.search(reg_pass));
                    break;
                default:
                    reg_veri=false;
            }
            if(reg_veri){
                document.getElementById("login_station").submit();
            }
            console.log('redirected');
        };
        error_feedback=function(){
            
        };
        </script>
        <h2>Please enter account information</h2>
        <?php
            if(isset($_GET['mode'])&&$_GET['mode']=='newpass'){//new user login
                //echo 'newpass';
                echo '<span id="mode" style="display:none">newpass</span>';
                //verify credentials with the server
                $return= new_pass();
                if($return==null){//incorrect login
                    echo '<font id="login_return" color="red">INVALID ENTRY</font>';
                }
                else{
                    echo '<font id="login_return" color="blue">WELCOME BACK!</font>';
                    //set cookie/session
                    //redirect to main or admin page
                }
            }
            else if(isset($_GET['mode'])&&$_GET['mode']=='new'){//new user login
                echo '<span id="mode" style="display:none">new</span>';
                //verify credentials with the server
                $return= new_account();
                if($return==null){//incorrect login
                    echo '<font id="login_return" color="red">INVALID ENTRY</font>';
                }
                else{
                    echo '<font id="login_return" color="blue">WELCOME BACK!</font>';
                    //set cookie/session
                    //redirect to main or admin page
                }
            }
            else if(isset($_GET['mode'])&&$_GET['mode']=='login'){//check login, parameters
                echo '<span id="mode" style="display:none">login</span>';
                //verify credentials with the server
                $return= try_login();
                if($return==null){//incorrect login
                    echo '<font id="login_return" color="red">INVALID ENTRY</font>';
                }
                else{
                    echo '<font id="login_return" color="blue">WELCOME BACK!</font>';
                    //set cookie/session
                    //redirect to main or admin page
                }
            }
            else{
                echo '<span id="mode" style="display:none">login</span>';//normal login//broke login, check ajax
            }
        ?>
        
        <div id='log_in_chunk'>
            <form id="login_station" method="post" action="./login.php">
                <span>Username </span><input type="text" id="user_name" name="maze_login_name" value=""></input></br>
                <span>Password </span><input type="password" id="user_pass" name="maze_login_pass" value=""></input>
            </form>
        </div>   
        
        
        
        
        <button id="try_input" onclick="verify_one()">Submit</button>
        <button id="try_input" onclick="window.location='./login.php'">Back to main</button></br>
        <button onclick="new_setup()">New Account</button>
        <span id="feedback"></span>
        <script type="text/javascript">
            console.log('form'+document.getElementById('login_station').innerHTML);
            switch(document.getElementById('mode').innerHTML){
                case 'new':
                        new_setup();
                    break;
                case 'newpass':
                        new_pass();
                    break;
                case 'login':
                        
                    break;
                default:
                    console.log("defaulted mode?");
            }
        
    </script>
    </center>
<p>&nbsp;</p>
<!-- InstanceEndEditable --></section>
</article>
<footer class="row">
<div class="col-sm-12">
  <p>Produced by: Cheryllynn, Colleen, and Shane</p>
</div>
</footer>
</div>

	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
	<script src="js/jquery-1.11.3.min.js"></script>

	<!-- Include all compiled plugins (below), or include individual files as needed --> 
	<script src="js/bootstrap.js"></script>
  </body>
<!-- InstanceEnd --></html>