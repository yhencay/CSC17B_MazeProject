<?php
/*
 * Note: start and finish funtion must be called before walls are generated
 * 
 */

class Maze_mod{
    private $X_size;
    private $Y_size;
    private $C_hall;//holds "empty space" char
    private $C_wall;//holds "filled space" char
    private $Start;//starting position
    private $Finish;//finish position
    //utility variables
    private $gen_cycles;
    //main storage
    private $maize="";//array holding the map
    private $b_list = array();//holds blacklisted indexes to not be overwritten// I guess map of bool?
    public function Maze_mod($x,$y,$open,$closed){
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
    private function _rec_gen_wall($tl,$tr,$bl,$br){//recursively generate
            //end conditions
        //echo $tl.' '.$tr.' '.$bl.' '.$br.'</br>';
        if(($tr-$tl)<=3){return;}
        if(intdiv(($bl-$tr),($this->X_size))<=2){return;}
        //decide a division
        $vert=2+rand()%(($tr-$tl)-3);//decision
        $hori=2+rand()%(intdiv(($br-$tl),($this->X_size))-3);//decision //should be 3
        echo 'horizonal:'.intdiv(($br-$tl),($this->X_size)-3).' '.$hori.'</br>';
        //get some information based on the division
        $north=$tl+$vert;
        $cross=$north+($hori)*$this->X_size;
        $west=$cross-$vert;
        $east=$cross+($tr-$tl)-$vert;
        $south=$bl+($cross-$west);
        //store decision
        $w_list=[];//unique_array();//write list
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
            switch(rand()%4){//225(too small) 345(fine) 309(fine)
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
        //echo 'B_list:';
        for($i=0;$i<4;$i++){
            //echo $holes[$i].' ';
            $this->add_blist($holes[$i],false);
        }
        //echo '</br>';
        //push stored values into the maize
        for($i=count($w_list)-1;$i>-1;$i--){//transfer list data to maize
            $tpass=$w_list[$i];
            if(!$this->is_blist($tpass)){//not blacklisted? add to maze
                $this->maize[$w_list[$i]]=$this->C_wall;
            }
            unset($w_list[$i]);
        }
        //$this->maize[$cross]=48+$this->gen_cycles;
        $this->gen_cycles++;
        //recursively call new quadrants
        $this->_rec_gen_wall($tl,$north,$west,$cross);//q1-top left
        $this->_rec_gen_wall($north,$tr,$cross,$east);//q2-top right
        $this->_rec_gen_wall($west,$cross,$bl,$south);//q3-bot left
        $this->_rec_gen_wall($cross,$east,$south,$br);//q4-bot right
        }
    public function set_walls(){
        $this->_rec_gen_wall(0,$this->X_size-1,$this->X_size*($this->Y_size-1),$this->X_size*$this->Y_size-1);
        //$this->_rec_gen_wall(0,24,600,624);
    }
    public function set_SF($start, $finish){
        echo $start;
        echo $finish;
    }
    public function get_board(){
        return $this->maize;
    }
    public function debug(){
        echo'Cycles: '.$this->gen_cycles.'</br>';
        echo'B_list: '.count($this->b_list).'</br> Values: ';
        foreach($this->b_list as &$value){
            echo $value.' ';
        }
    }
    public function print_board($extra){
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
    public function xy_convert($x,$y){//convert pos
        return $y*$this->X_size+$x;
    }
    public function set_hole($min,$max,$dead,$horiz){//choose a spot
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
    public function add_blist($pos){
        array_push($this->b_list, $pos);
    }
    public function is_blist($pos){
        foreach($this->b_list as &$value){
            if($value==$pos){//$this->blist[$pos]//might need to be itterative
                echo 'true '.$pos;
                return true;
            }
        }
        return false;
    }
    public function is_open($pos){
        if($this->maize[$pos]==$this->C_hall){
            return true;
        }
        return false;
    }
}
