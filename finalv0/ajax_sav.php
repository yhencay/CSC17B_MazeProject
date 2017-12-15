<?php
include './generic_query.php';

//check cookie
if(isset($_GET['maze'])){//save the game to the server
    //insert is active0, id_player, gen_map, stop_time, total_score
    if(isset($_GET['id_game'])&&isset($_GET['is_active'])&&isset($_GET['id_player'])&&isset($_GET['gen_map'])&&isset($_GET['stop_time'])&&isset($_GET['total_score'])){
        $conn=new server_query();
        $conn->setup_connection();
        $dt=new DateTime();
        $row=['',0,$id,$map,$dt,100];
        $conn->insert_row('m_entity_game', ['id_game','is_active','id_player','gen_map','stop_time','total_score'], $row);
        $conn->close_connection();
    }
    
}
echo 'end of ajax';