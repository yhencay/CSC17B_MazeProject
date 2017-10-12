<!DOCTYPE html>
<!--
    Author:Shane Hazelquist
-->
<html>
    <head>
        <meta charset="UTF-8">
        <?php   include './Maze_mod.php';   ?>
        <title>Debug maze model</title>
    </head>
    <body>
        <?php
            //x size y size open closed
            $maze= new Maze_mod(15,15,'_','x');
            $maze->set_walls();
            $maze->print_board(false);
            $maze->debug();
        ?>
    </body>
</html>


