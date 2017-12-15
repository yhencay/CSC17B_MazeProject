<?php
//include './sha1.php';
//require '../../protected/generic_query.php';
include './generic_query.php';

function try_login(){
    $verified=false;
    if(isset($_POST["maze_login_name"])&&isset($_POST["maze_login_pass"])){
        $usr_check=$_POST["maze_login_name"];
        $encryp= sha1($_POST["maze_login_pass"],false);
        //echo $usr_check.' '.$encryp;
        //$pass_check=$encryp->encrypted();
        //return $pass_check;
        //entity_client
        //echo $encryp;
        $conn= new server_query();
        $conn->setup_connection();
        $q=$conn->get_row('m_entity_player', ['player_name','id_player'], "player_name='".$usr_check."' AND "."hashpass='".$encryp."'");
        //var_dump($q);
        $nope=[0];
        if($q!=null){
            $conn->close_connection();
            $id=$q[0];
            $name=$q[1];
            $verified=true;
        }
    }
    //second round of regular expressions if nessasary
    //check database for login values
    if($verified){
        //echo"retrieve user id and store in cookie";//maybe hashed for security
        //any account steps will use user id and verify the user name/hash key kept in cookie
        setcookie('maze_user', $name.'='.$id, time()+3600);
        return $id;
    }
    
}
function new_account(){
    if(isset($_POST["maze_login_name"])&&isset($_POST["maze_login_pass"])&&isset($_POST["icon"])){
        $usr_check=$_POST["maze_login_name"];
        $encryp= sha1($_POST["maze_login_pass"],false);
        //$pass_check=$encryp->encrypted();
        //return $pass_check;
        //entity_client
        //echo $encryp;
        $dt=getdate();//new DateTime('Y-m-d');
        
        $conn= new server_query();
        $conn->setup_connection();
        
        $conn->insert_row('m_entity_player', ['id_player','player_name','hash_pass','date_joined','player_icon','total_points'], ["''","'".$usr_check."'","'".$encryp."'","'".$dt."'",$_POST["icon"],0]);
        $conn->close_connection();
        return true;
    }
}
