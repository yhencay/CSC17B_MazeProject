/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function item_class(){
    //holds the image paths
    this.paths=["./images/crystal_ball.jpg","./images/apple.png","./images/book.jpg","./images/ice_cube.jpg","./images/milk.jpg","./images/pencil.jpg"];
    this.maze_inventory=[];
    this.player_inventory=[];
    this.pos=[];//array of item positions
    var limit=6;
    do{
        this.maze_inventory.push(this.paths[Math.floor(Math.random()*(limit-0+1)+0)]);//randomly insert into the maze
        limit--;
    }while(limit>-1);//passed zero?
    
    this.get_item_maze=function(i){//returns image path 
        console.log('searching item index'+i);
        return this.maze_inventory[this.maze_inventory.length-1];
        if(i===null){
        }
        else{
            return this.maze_inventory[i];
        }
    };
    this.get_item_player=function(i){//returns image path 
        if(i===null){
            return this.player_inventory[0];
        }
        else{
            return this.player_inventory[i];
        }
    };
    this.item_grab=function(pos){//push last item into
        console.log('taking item at '+pos);//not actually ordered
        this.player_inventory.push(this.maze_inventory.pop());
        this.display();
    };
    this.display=function(){
        var out='';
        var width=75;
        var height=75;
        for(var item in this.player_inventory){
            out+='<img src="'+this.player_inventory[item]+'"alt=item_'+item+'" height="'+height+'" width="'+width+'">';
        }
        document.getElementById('item_inventory').innerHTML=out;
    };
    this.debug=function(){
        var out='';
        var width=75;
        var height=75;
        for(var item in this.paths){
            out+='<img src="'+this.paths[item]+'"alt=item_'+item+'" height="'+height+'" width="'+width+'">';
        }
        document.getElementById('item_inventory').innerHTML=out;
    };
    this.loadpos=function(arry){//given that the items have not been collected already
        for(var a in arry){//no error checking for more/less 6 items
            this.pos.push(arry[0]);
        }
    };
    this.getpos=function(pos){
        return this.maze_inventory[this.pos.indexOf(pos)];//returns path based on position
    };
    this.getcollected=function(){
        return this.player_inventory.length;
    };
    this.getremaining=function(){
        return this.maze_inventory.length;
    };
}
