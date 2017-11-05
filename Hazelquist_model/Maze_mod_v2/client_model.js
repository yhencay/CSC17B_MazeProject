/* 
 *                            <file:client_model.js>
 * Requirements:
 * -none
 * Functionality:
 * -client side class for controller actions in a game session
 * -calls Maze_gen.php via AJAX to generate maze
 *                            
 *                            
 */


client_model=function(){
    this.X_size;//x dimention
    this.Y_size;//y dimention
    this.time;//
    this.map;//the generated string from server side
    this.items=[];//items collected or not
    this.cursor;//player position
    this.single;//single player?
    //this.load_cookie();
    
    
    //in game functions
    this.set_items=function(num){//set number of items, enum specifics elsewhere
        var pos;
        this.items.splice(0,this.items.length);//empty array
        do{
            pos=Math.floor((Math.random*(this.X_size*(this.Y_size-1)))+this.X_size);
            if(this.map[pos]==='_'){
                this.items.push(pos);//add the new item location
                this.items[pos]='I';//item for display into map
            }
        }while(this.items.length<num);
    };
    this.make_move=function(move){
        //attempt to move in one direction
        var reprint=false;
        switch(move){
            case 'up':
                if((this.cursor-this.X_size)>0&&this.map[this.cursor-this.X_size]==='_'){//valid move
                    this.cursor-=this.X_size;
                    reprint=true;
                }
                break;
            case 'down':
                if((this.cursor+this.X_size)<(this.X_size*this.Y_size)&&this.map[this.cursor+this.X_size]==='_'){//valid move
                    this.cursor+=this.X_size;
                    reprint=true;
                }
                break;
            case 'right':
                if((this.cursor+this.X_size)<(this.X_size*this.Y_size)&&this.map[this.cursor+1]==='_'){//valid move
                    this.cursor++;
                    reprint=true;
                }
                break;
            case 'left':
                if((this.cursor-this.X_size)>0&&this.map[this.cursor-1]==='_'){//valid move
                    this.cursor--;
                    reprint=true;
                }
                break;
            default:
                //invalid movement direction
        }
        if(reprint){//valid entry means the cursor moved, redisplay
            this.modify_view(this.cursor,);
        }
    };
    this.item_pass=function(){
        //if all items are collected, open exit
        
        if(false){//if multiplayer, pass information to server
            
        }
    };
    this.modify_view=function(cursor,viewh,vieww){//(player location, radius up/down, radius left/right)
        //note: total view dimentions view*2+1;
        var output='';
        var pos=cursor-(viewh*this.X_size)-vieww;//start upper left corner of mod view
        for(var y=0;y<(viewh*2+1);y++){
            var empt='';//empty for out bounds
            var set='';//grab data
            
            if(pos<0||pos>this.X_size*this.Y_size){//above or below total
                for(var x=0;x<vieww*2+1;x++){
                    set+='x';
                    pos++;
                }
                output+=set+'</br>';//add row to total
                pos-=(vieww*2+1);
                pos+=this.X_size;
            }
            else{//iterate through
                var hright=false;//all following should be artificial
                var passv=false;//passed cursor column?
                var tempc=pos+(vieww+1);//cursor column
                for(var x=0;x<(vieww*2+1);x++){//go left
                    if(pos===tempc){//passed cursor column
                        passv=true;
                        if(pos%(vieww*2+1)===vieww+1){
                            //cursor positionshould probably mark that
                        }
                    }
                    if(hright){
                        set+='x';// __X->
                        empt+='x';
                    }
                    else{//
                        if(!passv&&(pos%this.X_size===0)){
                            // hit left wall before cursor col
                            empt+='x';
                            set=empt;
                        }
                        else if(passv&&(pos%this.X_size===this.X_size-1)){
                            //hit right wall after cursor col
                            hright=true;//skip checks
                            set+='x';
                        }
                        else{//no problem yet
                            set+='_';//add open character(read from data)
                            empt+='x';//in case before left wall
                        }
                    }
                    pos++;
                }
                //add work to total new display
                output+=set+'</br>';//add row to total
                //go to next row
                pos-=(vieww*2+1);
                pos+=this.X_size;
            }
        }
        return output;
    };
    
    //ajax functions
    this.item_pass_ajax=function(){
        
    };
    this.request_map_ajax=function(){
        //pass difficulty
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //cookie set
                var gen=this.responseText;
                var temp=gen.split(',');
                this.X_size=temp[0];
                this.Y_size=temp[1];
                //temp[2]//start
                //temp[3]//finish
                this.map=temp[4];
                console.log('Recieved:'+this.map);//response text gives 
                //xhttp.close();
            }
        };
        xhttp.open("GET", "Maze_gen.php?maze_dif=easy", true);//in this case it just inserts the new page into the old one
        xhttp.send();
    };
    
    //cookie functions
    this.load_cookie=function(){
        /*this.X_size;//x dimention
        this.Y_size;//y dimention
        this.time;//
        this.map;//the generated string from server side
        this.items=[];//items collected or not
        this.ppos;//player position
        this.single;//single player?
        */
    };
    this.map_cookie=function(){//read map from cookie
        
    };
    this.update_cookie=function(){//update serialized version
        var string=JSON.stringify(this);
        console.log(string);
    };
};




/*
 * {GENERAL PLAN OF ATTACK}
 * Notes:
 * minimum display dimentions 3x3, reccomended 5x5, < than entire dimentions
 * for mod display val*2+1=width/height
 * 
 * General plan is to try to load serialized version by default, unless specified
 * for new game session. If fails could not connect to game, start a new one/log in again
 * 
 * Cookies:
 * 1)map cookie
 * 2)player cookie (probably handled elsewhere)
 * 3)serialized game session (removed in sync with game timer)
 * 
 * 
 * 
 */

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function checkCookie(cname) {
    var value = getCookie(cname);
    if (value != "") {
        alert("Cookie Name = " + cname + "Value = " + value);
    } else {
        value = prompt("Please enter your cookie :"+cname+" Value", "");
        if (value != "" && value != null) {
            setCookie(cname, value, 365);
        }
    }
}