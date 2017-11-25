/* 
 *                            <file:client_model.js>
 * Requirements:
 * -none
 * Functionality:
 * -client side class for controller actions in a game session
 * -calls Maze_gen.php via AJAX to generate maze
 *                            
 * include sleep function to wait for the ajax_map in case of slow load times                  
 */


client_model=function(){
    this.X_size;//x dimention
    this.Y_size;//y dimention
    this.time;//
    this.map;//the generated string from server side
    this.items=[];//items collected or not
    this.cursor;//player position
    this.single;//single player?
    this.params=2;
    this.printable;
    //this.load_cookie();
    
    this.debug_set=function(){//a temp function to set values in the class
        printable=false;
    };
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
                if((this.cursor-this.X_size)>0&&this.map[this.cursor-this.X_size]!=='X'){//valid move
                    if(this.map[this.cursor-this.X_size]==='i'){//is the movement on an item?
                        console.log('item_get');
                        this.overwrite_item(this.cursor-this.X_size);//remove from map
                    }
                    this.cursor-=this.X_size;
                    reprint=true;
                }
                break;
            case 'down':
                if((this.cursor+this.X_size)<(this.X_size*this.Y_size)&&this.map[this.cursor+this.X_size]!=='X'){//valid move
                    if(this.map[this.cursor+this.X_size]==='i'){//is the movement on an item?
                        console.log('item_get');
                        this.overwrite_item(this.cursor+this.X_size);//remove from map
                    }
                    this.cursor+=this.X_size;
                    reprint=true;
                }
                break;
            case 'right':
                if((this.cursor+this.X_size)<(this.X_size*this.Y_size)&&this.map[this.cursor+1]!=='X'){//valid move
                    if(this.map[this.cursor+1]==='i'){//is the movement on an item?
                        console.log('item_get');
                        this.overwrite_item(this.cursor+1);//remove from map
                    }
                    this.cursor++;
                    reprint=true;
                }
                break;
            case 'left':
                if((this.cursor-this.X_size)>0&&this.map[this.cursor-1]!=='X'){//valid move
                    if(this.map[this.cursor-1]==='i'){//is the movement on an item?
                        console.log('item_get');
                        this.overwrite_item(this.cursor-1);//remove from map
                    }
                    this.cursor--;
                    reprint=true;
                }
                break;
            default:
                //invalid movement direction
        }
        if(reprint){//valid entry means the cursor moved, redisplay
            this.write_map();
        }
    };
    this.item_pass=function(num_items){
        //if all items are collected, open exit
        
        if(false){//if multiplayer, pass information to server
            
        }
    };
    this.modify_view=function(){//(player location, radius up/down, radius left/right)cursor,viewh,vieww
        //return "abc";
        console.log("modifying the view");
        var cursor=this.cursor;//-this.X_size;
        var viewh=this.params;
        var vieww=this.params;
        //note: total view dimentions view*2+1;
        var output='';
        var pos=cursor-(viewh*this.X_size)-vieww;//start upper left corner of mod view
        console.log("ok call to mod_view ("+cursor+" "+viewh+" "+vieww+" "+pos+")"+this.cursor);
        for(var y=0;y<(viewh*2+1);y++){
            var empt='';//empty for out bounds
            var set='';//grab data
            
            if(pos<0||pos>=(this.X_size*this.Y_size)-1){//above or below total
                console.log("vertbounds "+pos);
                for(var x=0;x<vieww*2+1;x++){
                    set+='X';
                    pos++;
                }
                output+=set;//add row to total
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
                            //console.log("inside everything");
                        }
                    }
                    if(pos===this.cursor){
                        set+='0';
                        empt+='X';//shouldn't ever trigger, but just in case
                    }
                    else if(hright){
                        set+='X';// __X->
                        empt+='X';
                    }
                    else{//
                        if(!passv&&(pos%this.X_size===0)){
                            // hit left wall before cursor col
                            empt+='X';
                            set=empt;
                        }
                        else if(passv&&(pos%this.X_size===this.X_size-1)){
                            //hit right wall after cursor col
                            hright=true;//skip checks
                            set+='X';
                        }
                        else{//no problem yet
                            set+=this.map[pos];//add open character(read from data)//problem
                            empt+='X';//in case before left wall
                        }
                    }
                    pos++;
                }
                //add work to total new display
                console.log("inner_mod: "+set);
                output+=set;//add row to total
                //go to next row
                pos-=(vieww*2+1);
                pos+=this.X_size;
            }
        }
        console.log("modifications complete: "+output);
        return output;
    };
    
    //ajax functions
    this.item_pass_ajax=function(){
        
    };
    this.request_map_ajax=function(){
        //pass difficulty
        var goin=false;
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //cookie set
                var gen=this.responseText;
                var temp=gen.split(',');
                for(a in temp){
                    console.log('passed in '+temp[a]);
                }
                //This section, is outdated, currently grabbing info from cookie
                /*this.X_size=temp[0];
                this.Y_size=temp[1];
                this.cursor=temp[2];//start
                //temp[3]//finish
                this.map=temp[4];
                this.printable=true;*/
                console.log("XML request finished");
            }
            //console.log("log change? "+this.X_size);
        };//temp on sync change back to true
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
        //wait for map to set
        var wait=0;//wait counter
        while((document.cookie.indexOf("maze_gen") < 0)||(wait>10)){//check for cookie
            //wait to avoid cpu hog
            this.wait_timer(1);
            console.log("waiting "+wait);
            wait++;
        }
        var holder=getCookie("maze_gen").split('~');//grab generated data
        console.log("holder set");
        //load cookie data in and delete the cookie so you don't replay on refresh
        for(a in holder){
            console.log("holder_ob="+holder[a]);
        }
        this.X_size=holder[0]*1;
        this.Y_size=holder[1]*1;
        this.cursor=holder[2]*1;
        var finish=holder[3]*1;//integrated later
        var nitems=holder[4]*1;//integrated later
        this.map=holder[5];
        this.write_map();
        
    };
    this.overwrite_item=function(pos){//items to be removed
        //console.log("before:"+this.map[pos]);
        //this.map[pos]='_';//not working? kinda odd
        //apparently need to overwrite the entire string
	this.map=this.map.substr(0,pos)+'_'+this.map.substr(pos+1);
        //console.log("after:"+this.map[pos]);
    };
    this.update_cookie=function(){//update serialized version
        var string=JSON.stringify(this);
        console.log(string);
    };
    this.write_map=function(){//write map via dom
        var output=this.modify_view();
        for(var i=0;i<output.length;i++){
            document.getElementById('disp_grid_'+i).innerHTML=output[i];
        }
    };
    this.wait_timer=function(time){
        wait=function(){
            window.clearInterval(pausev);
            console.log("help"+this.modify_view);
        };
        var pausev = setInterval(wait(),1000*time);
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