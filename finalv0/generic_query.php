<?php

/* 
 * Author :Shane Hazelquist
 * Objective :create a better method of interfacing with an sql server
 * NOTE: this does not handle information in an absoulutely secure manner. Is very succeptible to sql injection
 */

class server_query{
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "maze_dat";

    private $columns=[];//array format, can stringify later
    private $conn;//connection variable
    function __destruct(){//apparently not needed?
        //var_dump($this->conn);
        if (!$this->conn) {
            //echo"closed";
            //die("Connection failed: " . mysqli_connect_error());
        }
        else{
            //echo"closed2";
            //mysqli_close($this->conn);//close the connection on command
        }
    }
    public function update_conn_info($name,$user,$pass,$db){//(name,user,pass,dbname)if null does not update
        if($name!=null){
            $this->servername=$name;
        }
        if($user!=null){
            $this->username=$user;
        }
        if($pass!=null){
            $this->password=$pass;
        }
        if($db!=null){
            $this->dbname=$db;
        }
    }
    public function setup_connection(){
        $this->conn = mysqli_connect($this->servername, $this->username, $this->password, $this->dbname);
        if (!$this->conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }
    public function close_connection(){
        mysqli_close($this->conn);//close the connection on command
    }
    public function get_num_row($table,$fields,$condition){//(table name,field values, condition?)Get number of rows//false or name value limitor
        //Note if you use the "all" char of '*' for fields, this returns field names as well
        //They are seperated by a null element
        if (!$this->conn) {
            die("Connection failed: " . mysqli_connect_error());
            return;
        }
        if($fields!='*'){
            $sfields="";//combine into a single string format
            foreach($fields as $key =>$val){
                $sfields.=$val;
                if($key+1!= count($fields)){
                    $sfields.=", ";
                }
            }
            $sfields.="";//finish the fields string 
        }
        if($fields=='*'){//get all
            $sql = "SELECT * FROM ".$table;
        }
        else if(!is_string($condition)){
            $sql = "SELECT ".$sfields." FROM ".$table;
        }
        else{
            $sql = "SELECT ".$sfields." FROM ".$table." WHERE ".$condition;//"SELECT * FROM " to get all
        }
        //echo '</br>[generated sql statement] '.$sql;
        $result = mysqli_query($this->conn, $sql);
        //echo'</br>result ';
        //var_dump($result);
        $out=[];//array holding values
        //handle sql query results
        //echo"</br>Fields ";
        //var_dump($fields);
        //echo"</br>";
        return mysqli_num_rows($result);
    }
    public function raw_query($sql){
        if (!$this->conn) {
            die("Connection failed: " . mysqli_connect_error());
            return null;
        }
        else{
            return mysqli_query($this->conn, $sql);
        }
    }
    public function convert_raw($result,$need_fields){//take a set of rows and convert to array
        //var_dump($result);
        
        if($result==null){
            return null;//no results
        }
        $out=[];
        if(mysqli_num_rows($result)>0){
            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {
                //echo"ROW: ";
                //var_dump($row);
                //echo"</br>";
                    $field_count=[];
                    foreach($row as $field=>$val){
                        array_push($out, $val);//array_push($out, $row["'".$val."'"]);
                        if($need_fields){
                            //echo 'header '.$field.'</br>';
                            array_push($field_count,$field);
                        }
                    }
                    if($need_fields){//preend the headers seperated by null
                        array_push($field_count,null);
                        //echo count($field_count);
                        foreach ($out as $item){//append actual row
                            array_push($field_count,$item);
                        }
                        $out=$field_count;
                        $need_fields=false;
                    }
            }
        }
        else {
            echo null;
        }
        return $out;
    }
    public function getlast_id(){
        return $this->conn->insert_id;//not protected, careful
    }
    public function get_row($table,$fields,$condition){//(table name,field values, condition?)//false or name value limitor
        //echo'Condition '.$condition;        var_dump($fields);
        //Note if you use the "all" char of '*' for fields, this returns field names as well
        //They are seperated by a null element
        if (!$this->conn) {
            die("Connection failed: " . mysqli_connect_error());
            return null;
        }
        if($fields!='*'){
            $sfields="";//combine into a single string format
            foreach($fields as $key =>$val){
                $sfields.=$val;
                if($key+1!= count($fields)){
                    $sfields.=", ";
                }
            }
            $sfields.="";//finish the fields string 
        }
        if($fields=='*'){//get all
            if($table=='*'){
                $sql=$condition;
            }
            else{
                $sql = "SELECT * FROM ".$table;//." WHERE ".$condition;
                if($condition!=null){
                    $sql.=" WHERE ".$condition;
                }
            }
        }
        else if(!is_string($condition)){//simple select from
            $sql = "SELECT ".$sfields." FROM ".$table;
        }
        else if(!is_string($table)){//complex sql statement = open for anything
            $sql=$condition;
        }
        else{
            $sql = "SELECT ".$sfields." FROM ".$table." WHERE ".$condition;//"SELECT * FROM " to get all
        }
        //echo '</br>[generated sql statement] '.$sql.'</br>';
        $result = mysqli_query($this->conn, $sql);
        //echo'</br>result ';
        //var_dump($result);
        $out=[];//array holding values
        //handle sql query results
        //echo"</br>Fields ";
        //var_dump($fields);
        //echo"</br>";
        if($result==null){
            return null;//no results
        }
        if(mysqli_num_rows($result)>0){
            // output data of each row
            $need_fields=true;
            while($row = mysqli_fetch_assoc($result)) {
                //echo"ROW: ";
                //var_dump($row);
                //echo"</br>";
                if($fields=='*'){
                    $field_count=[];
                    foreach($row as $field=>$val){
                        array_push($out, $val);//array_push($out, $row["'".$val."'"]);
                        if($need_fields){
                            //echo 'header '.$field.'</br>';
                            array_push($field_count,$field);
                        }
                    }
                    if($need_fields){//preend the headers seperated by null
                            array_push($field_count,null);
                            //echo count($field_count);
                            foreach ($out as $item){//append actual row
                                array_push($field_count,$item);
                            }
                            $out=$field_count;
                            $need_fields=false;
                    }
                }
                else{//restricted fields
                    foreach($fields as $val){
                        array_push($out, $row[$val]);//array_push($out, $row["'".$val."'"]);
                    }
                }
            }
        } else {
            echo null;
        }
        return $out;
    }
    public function insert_row($table,$fields,$values){//(table name, field names, values to insert)//arrays please
        if (!$this->conn) {
            die("Connection failed: " . mysqli_connect_error());
            return;
        }
        $sfields="(";//combine into a single string format
        foreach($fields as $key =>$val){
            $sfields.=$val;
            if($key+1!= count($fields)){
                $sfields.=", ";
            }
        }
        $sfields.=")";//finish the fields string 
        
        $sql = '';//set up sql statement
        $sql.='INSERT INTO '.$table.' '.$sfields.' VALUES ';
        $field_val='(';
        $index=0;
        foreach($fields as $key=>$val){
            $field_val.=$values[$index++];
            if($key+1<count($fields)){
                $field_val.=', ';
            }
        }
        $field_val.=');';
        $sql.=$field_val;
        //submit insertion
        echo'</br>INSERT-> '.$sql.'</br>';
        if ($this->conn->query($sql) === TRUE) {
            //echo "New records created successfully";
        }else {
            echo "Error: " . $sql . "<br>" . $this->conn->error;
        }
        
    }
    public function update_row($table,$update,$condition){//(table name,change,condition)
        if (!$this->conn) {
            die("Connection failed: " . mysqli_connect_error());
            return;
        }
        //$sql = "UPDATE MyGuests SET lastname='Doe' WHERE id=2";
        $sql = "UPDATE ".$table." SET ".$update.' WHERE '.$condition.';';
        echo'</br>UPDATING: '.$sql.'</br>';
        if (mysqli_query($this->conn, $sql)) {
            //echo "Record updated successfully";
        } else {
            echo "Error updating record: " . $this->conn->error;
        }
    }
}
