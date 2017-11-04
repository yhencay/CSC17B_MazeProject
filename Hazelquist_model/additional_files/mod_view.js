/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


modify_view=function(cursor,viewh,vieww){//(player location, radius up/down, radius left/right)
    //note: total view dimentions view*2+1;
    var output='';
    var X_size=10;//dimentions
    var Y_size=10;//dimentions
    var pos=cursor-(viewh*X_size)-vieww;//start upper left corner of mod view
    for(var y=0;y<(viewh*2+1);y++){
        var empt='';//empty for out bounds
        var set='';//grab data
        
        
        if(pos<0||pos>X_size*Y_size){//above or below total
            for(var x=0;x<vieww*2+1;x++){
                set+='x';
                pos++;
            }
            output+=set+'</br>';//add row to total
            pos-=(vieww*2+1);
            pos+=X_size;
        }
        else{//iterate through
            var hright=false;//all following should be artificial
            var passv=false;//passed cursor column?
            var tempc=pos+(vieww+1);//cursor column
            for(var x=0;x<(vieww*2+1);x++){//go left
                if(pos===tempc){//passed cursor column
                    passv=true;
                }
                if(hright){
                    set+='x';// __X->
                    empt+='x';
                }
                else{//
                    if(!passv&&(pos%X_size===0)){
                        // hit left wall before cursor col
                        empt+='x';
                        set=empt;
                    }
                    else if(passv&&(pos%X_size===X_size-1)){
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
            pos+=X_size;
        }
    }
    return output;
};