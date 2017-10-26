<!DOCTYPE html>
<!--
    Author:Shane Hazelquist
-->
<html>
    <head>
        <meta charset="UTF-8">
        <?php   include './Maze_mod.php';   ?>
        <link rel="stylesheet" type="text/css" href="game.css">
		<title>THE MAZE GAME</title>
		<?php
            //x size y size open closed
            $maze= new Maze_mod(25,25,'_','x');//
            $maze->set_SF(610, 10);
            $maze->set_walls();
            //$maze->print_board(false);
            echo'<p id="maze_output">'.$maze->get_board().'';
            //$maze->debug();
        ?>
        <script type="text/javascript">
            var X_size=25;
            var Y_size=25;
            var Cursor=610;
            var win=10;
            var array="";
            array=document.getElementById("maze_output").innerHTML;
            document.getElementById("maze_output").style ="display:none";
            function getvalue(pos){
                return array[pos];
            }
            function setvalue(pos,char){
                this.array[pos]=char;
            }
            function Curs_plus(move){
                if(move>-1&&move<(X_size*Y_size)&&array[move]==="_"){
                    setvalue(Cursor,"_");//blank spot
                    document.getElementById("win").innerHTML = '<'+Cursor+' ';
                    document.getElementById('grid_'+Cursor+'').src = "hall_img.png";
                    Cursor=move;
                    setvalue(Cursor,"0");
                    document.getElementById('grid_'+Cursor+'').src = "cursor_img.png";
                    document.getElementById("pointed").innerHTML = Cursor;
                }
            }
            function isWin(pos){
                if(pos===win){
                    document.getElementById("win").innerHTML="YOU WON";
                }
                else{
                    Curs_plus(pos);
                }
            }
        </script>
	</head>
	
    <body>
		<div id="userInfo">
			<center><p><font color="#DCDCDC">Hello, USER!</font></p></center>
		</div>
	
		<div id="main-nav">
			<center>
				<a href="#nwgame"><img src="nwgame.jpg"></img></a>
				<a href="#svgame"><img src="svgame.jpg"></img></a>
				<a href="#instr"><img src="gminstr.jpg"></img></a>
				<a href="#ldrbrds"><img src="gmldrbrds.jpg"></img></a>
			</center>
		</div>
		
		<div id="content">
			<div class="target">
			<center>
				<p id="nwgame">
				<br>
					<h3 align="center" style="display:inline; color:white;" id="pointed">cursor</h3>
					<h3 align="center" style="display:inline; color:white;" id="win">win</h3>
					<div id="grid" style="line-height:11px" align="center">
						Error setting grid
					</div>
					<script type="text/javascript">
						print();
						
						function print(){var overwrite= "</br>";
							for(var y=0;y<Y_size;y++){
								for(var x=0;x<X_size;x++){
									if(x+y*X_size===Cursor){
										overwrite+='<img style="display:inline" width="10px" height="10px" id="grid_'+(x+y*X_size)+'" src="cursor_img.png">';
									}
									else{//"line-height:11px"
										overwrite+='<img style="display:inline" width="10px" height="10px" id="grid_'+(x+y*X_size)+'"src='+change(getvalue(y*X_size+x))+'>';
									}
								}
								overwrite+='</br>';
							}
							document.getElementById("grid").innerHTML = overwrite;
							document.getElementById("pointed").innerHTML = Cursor;
						}
						function change(char, id){
							switch(char){
								case "x":
									return "wall_img.png";
									break;
								case "_":
									return "hall_img.png";
									break;
							}
						}
						function keys(e){
							e = e|| window.event;
							switch (e.keyCode){
								case 38://arrow up
								case 87://w
									isWin(Cursor-X_size);
									break;
								case 37:
								case 65:
									isWin(Cursor-1);
									break;
								case 40:
								case 83:
									isWin(Cursor+X_size);
									break;
								case 39:
								case 68:
									isWin(Cursor+1);
									break;
								default: return;
								}
							}
							window.addEventListener("keydown",keys, true);
					</script>
					<div id="next" align="center">
						<button type="button" id="press_up" onclick="isWin(Cursor-X_size)">Cursor_up </button></br>
						<button type="button"  id="press_left" onclick="isWin(Cursor-1)">Cursor_lft</button>
						<button type="button"  id="press_right" onclick="isWin(Cursor+1)">Cursor_rgt</button></br>
						<button type="button" id="press_down" onclick="isWin(Cursor+X_size)">Cursor_dwn</button>
					</div>
				</p>
				
				<p id="svgame">
			</p>
			
			<p id="instr">
			</p>
			
			<p id="ldrbrds">
			</p>
			
		</center>
	</div>
	</div>
	<div id="footer">
		<div id="altnav">
		<a href="#">About</a> -
		<a href="#">Services</a> -
		<a href="#">Portfolio</a> -
		<a href="#">Contact Us</a> -
		<a href="#">Terms of Trade</a>
	</div>
	Copyright @ The Maze Group<br>
	Powered by <a href="http://www.rcc.edu/">Riverside Community College</a>
	</div>
	</body>
</html>


