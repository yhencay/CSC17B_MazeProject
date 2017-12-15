<!DOCTYPE html>
<html lang="en"><!-- InstanceBegin template="/Templates/final-project.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <?php   include './Maze_gen.php';   ?>
<script type="text/javascript" src="client_model.js"></script>
<script type="text/javascript" src='item_class.js'></script>
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
class="navlink"><a href="login.php">Login</a></span>
</div>
</nav>
<article class="row">
  <section class="col-sm-10"><!-- InstanceBeginEditable name="Page Heading" -->
    <!-- InstanceEndEditable --><!-- InstanceBeginEditable name="Main Page Content" -->
	  
<!-- InstanceEndEditable --></section>
</article>
    <center><h2 id="endgame_congrats"></h2></center>
    
<?php
            //if(isset($_GET['mode'])=='multi'||isset($_GET['mode'])=='single'){//game start parameters
                $disp_size=49;//
                //set up grid and input buttons
                $disp_width=7;//5
                echo('<center id="disp_grid">');
                for($i=0;$i<$disp_size;$i++){
                    echo('<span id="disp_grid_'.$i.'">.</span>');
                    if($i%$disp_width==$disp_width-1){//right edge
                        echo('</br>');
                    }
                }
                echo('</center>');
                echo('<center id="inputs">');
                echo('<button id="button_left" onclick="mleft()">Left</button>');
                echo('<button id="button_up" onclick="mup()">up</button>');
                echo('<button id="button_down" onclick="mdown()">down</button>');
                echo('<button id="button_right" onclick="mright()">right</button>');
                echo('</center>');
                
            //}
?>
    <center id="item_inventory"></center>
        
    <center><h3>Use "wasd" or the arrow keys to move</h3></center>
        
        <script type='text/javascript'>//controls and refrieval
            var play=new client_model();
            play.request_map_ajax();
            
            play.debug_set();
            console.log("starting cookie, wait");
            play.map_cookie();
            //play.endgame();
            
            mleft=function(){
                play.make_move("left");
            };
            mup=function(){
                play.make_move("up");
            };
            mdown=function(){
                play.make_move("down");
            };
            mright=function(){
                play.make_move("right");
            };
            
            function keys(e){
                e = e|| window.event;
                switch (e.keyCode){
                    case 38://arrow up
                    case 87://w
                        mup();
                        break;
                    case 37://right
                    case 65://s
                        mleft();
                        break;
                    case 40://left
                    case 83://s
                        mdown();
                        break;
                    case 39://up
                    case 68://w
                        mright();
                        break;
                    default: return;
                }
            }
            window.addEventListener("keydown",keys, true);
            //console.log(play.modify_view(15,2,2));
            //play.write_map();
        </script>
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