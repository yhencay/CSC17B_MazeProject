<!DOCTYPE html>
<!--
    Author:Shane Hazelquist
-->
<html>
    <head>
        <meta charset="UTF-8">
        <?php   include './Maze_mod.php';   ?>
        <title>Debug maze model</title>
        <h3>Maze</h3>
        <h3 style="display:inline" id="pointed">cursor</h3>
        <h3 style="display:inline" id="win">win</h3>
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
                    document.getElementById('grid_'+Cursor+'').innerHTML = '_';
                    Cursor=move;
                    setvalue(Cursor,"0");
                    document.getElementById('grid_'+Cursor+'').innerHTML = "0";
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
        
        <div id="grid" style="line-height:11px">
            Error setting grid
        </div>
        <script type="text/javascript">
            print();
            function print(){var overwrite= "</br>";
                for(var y=0;y<Y_size;y++){
                    for(var x=0;x<X_size;x++){
                        if(x+y*X_size===Cursor){
                            overwrite+='<p style="display:inline" id="grid_'+(x+y*X_size)+'">0</p>';
                        }
                        else{//"line-height:11px"
                            overwrite+='<p style="display:inline" id="grid_'+(x+y*X_size)+'">'+getvalue(y*X_size+x)+'</p>';
                        }
                    }
                    overwrite+='</br>';
                }
                document.getElementById("grid").innerHTML = overwrite;
                document.getElementById("pointed").innerHTML = Cursor;
            }
        </script>
        <div id="next">
            <button type="button" id="press_up" onclick="isWin(Cursor-X_size)">Cursor_up </button></br>
            <button type="button" id="press_left" onclick="isWin(Cursor-1)">Cursor_lft</button>
            <button type="button" id="press_right" onclick="isWin(Cursor+1)">Cursor_rgt</button></br>
            <button type="button" id="press_down" onclick="isWin(Cursor+X_size)">Cursor_dwn</button>
        </div>
    </body>
</html>


