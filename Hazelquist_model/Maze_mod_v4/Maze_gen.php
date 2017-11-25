<?php
/*
 *                            <file:Maze_gen.php>
 * Requirements:
 * GET('maze_dif=easy/medium/hard');
 * Functionality:
 * -Automatically assigns values and generates maze based on difficulty
 * -Returns plain text of generated maze
 * 
 * Notes:
 * not yet protected from non-get calls
 * 
 */
//$dif_set='';
//$dif_set=$_GET['maze_dif'];
$dif_set='easy';
if($dif_set!=''){//write to database instead?
    
    switch($dif_set){
        case 'easy':
            $gen=new Maze_mod(25, 25, '_', 'X');//625
            $gen->set_SF(12,612);//fixed, random possible
            $game_time=300;//in seconds
            $gen->set_items(5, 'i');//randomly set items//5
            break;
        case 'medium':
            $gen=new Maze_mod(50, 50, '_', 'X');//2500
            $gen->set_SF(24,2474);//fixed, random possible
            $game_time=1200;//in seconds
            $gen->set_items(5, 'i');//randomly set items
            break;
        case 'hard':
            $gen=new Maze_mod(75, 75, '_', 'X');//5625
            $gen->set_SF(36,5586);//fixed, random possible
            $game_time=2550;//in seconds
            $gen->set_items(15, 'i');//randomly set items
            break;
        default:
            $gen=new Maze_mod(50, 50, '_', 'X');//2500
            $gen->set_SF(24,2474);//fixed, random possible
            $game_time=1200;//in seconds
            $gen->set_items(5, 'i');//randomly set items
    }
    $gen->set_walls();
    
    //echo $gen->get_diment().','.$gen->get_board().','.$game_time;//should pass dimentions too, possibly s/f
    setrawcookie('maze_gen', $gen->get_diment('~').'~'.$gen->get_board(), time()+($game_time));//define path please expiration with cookie
}
else{
    echo 'failed get';
}



/*
 *                      <Maze generation class>
 * Notes:
 * calls should be done in order!
 * constructor(width,height,open char, closed char)//absoulute values
 * set_SF(start,finish)//relative to dimentions
 * set_walls()//sets walls automatically 
 * ====>Everything is ready, calls avaliable:
 * 
 * -set_items(int,char)//sets items 
 * -get_diment()//returns string with dimentions
 * -get_board()//returns plain text string of maze
 * -print_board(bool)//(print with debug?)//prints with </br>
 * -is_open(int)//returns the pos an open char?
 * -is_blist(int)//returns the pos black listed?
 */
class Maze_mod{
    private $X_size;//width dimention
    private $Y_size;//height dimention
    private $C_hall;//holds "empty space" char
    private $C_wall;//holds "filled space" char
    private $Start;//starting position
    private $Finish;//finish position
    private $Items;//number of items added
    //utility variables
    private $gen_cycles;
    //main storage
    private $maize="";//array holding the map
    private $b_list = array();//I guess map of bool
    public function Maze_mod($x,$y,$open,$closed){//(width,height,open char, closed char); initialize maze values
        $this->gen_cycles=0;
        $this->X_size=$x;
        $this->Y_size=$y;
        $this->C_hall=$open;
        $this->C_wall=$closed;
        //$this->maize=$X_size*$Y_size;//create space
        for($i=0;$i<$this->X_size*$this->Y_size;$i++){
            if($i<$this->X_size||$i>$this->X_size*($this->Y_size-1)){
                $this->maize.=$this->C_wall;//top bottom boarder
            }
            else if($i%($this->X_size)==0||$i%($this->X_size)==$this->X_size-1){
                $this->maize.=$this->C_wall;//set left right
            }
            else{
                $this->maize.=$this->C_hall;
            }
        }
    }
    private function _rec_gen_wall($tl,$tr,$bl,$br){//recursively generate walls
            //end conditions
        //echo $tl.' '.$tr.' '.$bl.' '.$br.'</br>';
        if(($tr-$tl)<=3){return;}
        if(intdiv(($bl-$tr),($this->X_size))<=2){return;}
        //decide a division
        $vert=2+rand()%(($tr-$tl)-3);//decision
        $hori=2+rand()%(intdiv(($br-$tl),($this->X_size))-3);//decision 
        //get some information based on the division
        $north=$tl+$vert;
        $cross=$north+($hori)*$this->X_size;
        $west=$cross-$vert;
        $east=$cross+($tr-$tl)-$vert;
        $south=$bl+($cross-$west);
        //store decision
        $w_list=[];//write list
        for($i=1;$i<($tr-$tl);$i++){//iterate across left right place horizontal
            array_push($w_list,($tl+($hori*$this->X_size)+$i));
        }
        for($i=1;$i<intdiv(($br-$tl),$this->X_size);$i++){//iterate top down place vertical
            array_push($w_list,(($tl)+$vert+$i*$this->X_size));
        }
        //find holes
        $holes=[0,0,0,-1];//max four, typically three
        $hole_dir=[true,true,true,true];//(north,south,east,west)know which arm we've drilled 
        $h_index=0;//know how many more to add
        if($this->is_blist($north)){//check edges for path blocking, handle with holes
            $holes[$h_index++]=$north+$this->X_size;
            $hole_dir[0]=false;
        }
        if($this->is_blist($south)){
            $holes[$h_index++]=$south-$this->X_size;
            $hole_dir[1]=false;
        }
        if($this->is_blist($east)){
            $holes[$h_index++]=$east-1;
            $hole_dir[2]=false;
        }
        if($this->is_blist($west)){
            $holes[$h_index++]=$west+1;
            $hole_dir[3]=false;
        }
        while($h_index<3){//not super optimized but meh
            switch(rand()%4){
                case 0:
                    if($hole_dir[0]==true){
                        $holes[$h_index++]=$this->set_hole($north,$cross,$cross,false);
                        $hole_dir[0]=false;
                    }
                    break;
                case 1:
                    if($hole_dir[1]==true){
                        $holes[$h_index++]=$this->set_hole($cross,$south,$cross,false);
                        $hole_dir[1]=false;
                    }
                    break;
                case 2:
                    if($hole_dir[2]==true){
                        $holes[$h_index++]=$this->set_hole($cross,$east,$cross,true);
                        $hole_dir[2]=false;
                    }
                    break;
                case 3:
                    if($hole_dir[3]==true){
                        $holes[$h_index++]=$this->set_hole($west,$cross,$cross,true);
                        $hole_dir[3]=false;
                    }
                    break;
            }
        }
        //updated blacklist
        for($i=0;$i<4;$i++){
            $this->add_blist($holes[$i],false);
        }
        //push stored values into the maize
        for($i=count($w_list)-1;$i>-1;$i--){//transfer list data to maize
            $tpass=$w_list[$i];
            if(!$this->is_blist($tpass)){//not blacklisted? add to maze
                $this->maize[$w_list[$i]]=$this->C_wall;
            }
            unset($w_list[$i]);
        }
        $this->gen_cycles++;
        //recursively call new quadrants
        $this->_rec_gen_wall($tl,$north,$west,$cross);//q1-top left
        $this->_rec_gen_wall($north,$tr,$cross,$east);//q2-top right
        $this->_rec_gen_wall($west,$cross,$bl,$south);//q3-bot left
        $this->_rec_gen_wall($cross,$east,$south,$br);//q4-bot right
        }
    public function set_walls(){//call recursive funtion to generate walls
        $this->_rec_gen_wall(0,$this->X_size-1,$this->X_size*($this->Y_size-1),$this->X_size*$this->Y_size-1);
    }
    public function set_SF($start, $finish){//set the start and finish positions
        $this->add_blist($start);
        $this->add_blist($finish);
        $this->maize[$start]='s';
        $this->maize[$finish]='f';
        $this->Start=$start;
        $this->Finish=$finish;
    }
    public function set_items($number, $icon){//set items randomly
        $this->Items=$number;
        $items=0;
        while($items<$number){
            $spot=rand($this->X_size,($this->X_size*$this->Y_size)-$this->X_size);//choose a top<spot<bot
            if($this->is_open($spot)){//if open add icon
                $this->maize[$spot]=$icon;
                $items++;
            }
        }
    }
    public function get_board(){//return the current board
        return $this->maize;
    }
    public function get_diment($split){//return some of the maze definitions
        $str=$this->X_size.$split.$this->Y_size.$split.$this->Start.$split.$this->Finish.$split.$this->Items;
        return $str;
    }
    public function debug(){//print blacklist
        foreach($this->b_list as &$value){
            echo $value.' ';
        }
    }
    public function print_board($extra){//(debug mode?)print board with breaks
        if($extra){
            for($i=0;$i<$this->X_size*$this->Y_size;$i++){
                if($i%($this->X_size)==$this->X_size-1){
                    echo($this->maize[$i].$i.'</br>');
                }
                else{
                    echo($this->maize[$i].' ');
                }
            }
        }
        else{
            echo'<p id="board" style="line-height:11px">';
            for($i=0;$i<$this->X_size*$this->Y_size;$i++){
                if($i%($this->X_size)==$this->X_size-1){
                    echo($this->maize[$i].'</br>');
                }
                else{
                    echo($this->maize[$i]);
                }
            }
            echo'</p>';
        }
    }
    public function xy_convert($x,$y){//convert pos to array subscript
        return $y*$this->X_size+$x;
    }
    public function save_cookie(){
        //$_COOKIE[]
        setcookie("maze_gen_return", $this->maize, time()+36000, "./");
    }
    private function set_hole($min,$max,$dead,$horiz){//choose a spot
        $temp;
        do{
            if($horiz){
                $temp= $min+1+rand()%($max-$min-1);// not edges
            }
            else{
                $temp=($min)+($this->X_size)*(1+rand()%(intdiv(($max-$min),($this->X_size))-1));//not edges
            }
        }while($temp==$dead||$temp<$min||$temp>$max);//remove center generations
        return $temp;
    }
    private function add_blist($pos){//add a value to the black list as to not be overwritten
        array_push($this->b_list, $pos);
    }
    public function is_blist($pos){//if black listed return true
        foreach($this->b_list as &$value){
            if($value==$pos){
                return true;
            }
        }
        return false;
    }
    public function is_open($pos){//if open char return true
        if($this->maize[$pos]==$this->C_hall){
            return true;
        }
        return false;
    }
}