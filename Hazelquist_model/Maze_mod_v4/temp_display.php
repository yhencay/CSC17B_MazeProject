<!DOCTYPE html>
<!--
    

    Decide on display dimensions and test new display method
    I think I'm going to stray away from sessions and just handle everything via cookie
    perm cookie:SYS ID to match with player name to ensure can't log in as someone else?
    could also just hash with password


    Need to make ajustments for items, an item display
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>temp display</title>
        <?php   include './Maze_gen.php';   ?> <!-- not really needed, but for clarity sake-->
        <script type="text/javascript" src="client_model.js"></script>
        <link rel="stylesheet" type="text/css" href="Style_sheet.css">
    </head>
    <body>
        
        <?php
            $disp_size=25;
            $disp_width=5;
            echo('<div id="disp_grid">');
            for($i=0;$i<$disp_size;$i++){
                echo('<span id="disp_grid_'.$i.'">.</span>');
                if($i%$disp_width==$disp_width-1){//right edge
                    echo('</br>');
                }
            }
            echo('</div>');
            echo('<div id="inputs">');
            echo('<button id="button_left" onclick="mleft()">Left</button>');
            echo('<button id="button_up" onclick="mup()">up</button>');
            echo('<button id="button_down" onclick="mdown()">down</button>');
            echo('<button id="button_right" onclick="mright()">right</button>');
            echo('</div>');
        ?>
        <h3>Use "wasd" or the arrow keys to move</h3>
        <script type='text/javascript'>
            var play=new client_model();
            play.request_map_ajax();
            play.debug_set();
            console.log("starting cookie, wait");
            play.map_cookie();
            
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
    </body>
</html>
