/* 
 * File:   main.cpp
 * Author: Shane Hazelquist
 * Purpose: Randomly generate
 * Created on September 23, 2017, 1:38 PM
 */
//System Libraries
#include <iostream>
#include <time.h>
#include <stdlib.h>
#include <set>
#include <queue>
#include <map>
using namespace std;
//User Libraries

//Global Constants
const int X_size=20;//even number?
const int Y_size=20;
const char Open_space=' ';//I added this to change the "empty spaces so it's easier for you gues to see"
map<int,char> b_list;//contains the non walled slots


int disp=0;
//Function Prototypes
char* get_b_chunk(int ,int );//x,y returns blank array
void    set_close_walls(char* ,int ,int ,char);//(array, x, y, set to)sets walls
void    del_arry(char*);//nah
void    set_opens_walls(char* ,int ,int ,int);//(array, x, y, num opens)sets walls
void    print_board(char*,bool);//show markings?
bool    is_free(char*,int);//arry, pos
bool    is_border(int);//is pos on outer edge?
int     conv_pos(int,int);//converts intx inty to 2d array pos
bool    is_b_list(int,char);//is the element in the blacklist
void    set_b_list(int,char);//add to blacklist
void    dbg_b_list();//print list


void    _rec_gen_wall1(char*,int,int,int,int);//(array, x_min, y_min, x_max,y_max)
void    _rec_gen_wall2(char*,int,int,int,int);//mid have
void    _rec_gen_wall3(char*,int,int,int,int);//div (best one so far, but ignores quadrents)
void    _rec_gen_wall4(char*,int,int,int,int);//4 point div, winner. q3 problems but no sigf
void    _rec_gen_wall4_fill(char*,int,int,int,int,bool);//one dimentional fill? (not yet implemented)
/* Notes:
 * try a recursive function that pushes a four point section? add the walls then, no extra thicc
 * once you get the border under control, eventlually when you add walls, push literal value into the stack and poke holes there.
 * then you can save the hole value and check against new wall fills.. possible hole slider for short values?
 * Boarder function is not adept at solving for unmatched x/y sizes
 * 
 * For the one dimentional fill include that for 3x3 center blocked segments randomly place non-obstacles
 * 
 * For blocked hole segments you can move the hole for that cell to the last spot keep it open
 */
//Execution Start

int main(int argc, char** argv) {
    //Declare Variables
    srand(static_cast<unsigned int>(time(0)));
    int openings=2;//number of gaps in the outer walls
    //Initialize Variables
    char *map=get_b_chunk(X_size,Y_size);
    //Map in-outputs
    set_close_walls(map,X_size,Y_size,'X');
    set_opens_walls(map,X_size,Y_size,3);
    //Display outputs
    for(int i=0;i<X_size*Y_size;i++){
        i%(X_size)==X_size-1?cout<<map[i]<<"\n":cout<<map[i]<<" ";
    }
    //End EXE
    //_rec_gen_wall1(map,0,0,X_size,Y_size);
    //_rec_gen_wall2(map,1,1,X_size,Y_size);
    //_rec_gen_wall3(map,1,1,X_size,Y_size);//(ysize-1)-1
    _rec_gen_wall4(map,0,X_size-1,X_size*(Y_size-1),X_size*Y_size-1);//minus one in(map,0+X_size+1,X_size+X_size-2,X_size*(Y_size-1)-X_size+1,(X_size*Y_size)-X_size-2);
    print_board(map,true);
    for(int i=0;i<10;i++){
        int tempy=rand()%(X_size*Y_size);
        if(is_free(map,tempy)){cout<<"IS free:"<<tempy<<endl;}
        else{cout<<"NOT free:"<<tempy<<endl;}
    }
    dbg_b_list();
    //del_arry(map);
    delete [] map;
    return 0;
}
bool    is_b_list(int pos,char orient){//check for value in the 
    map<int,char>::iterator it;
    it=b_list.find(pos);
    if(it!=b_list.end()){
        return true;
    }
    return false;
    
}

void    set_b_list(int pos,char orient){
    b_list.insert(pair<int,char>(pos,orient));
}
void    print_board(char* arry,bool extra){
    if(extra){
        for(int i=0;i<X_size*Y_size;i++){
            i%(X_size)==X_size-1?cout<<arry[i]<<i<<"\n":cout<<arry[i]<<" ";
        }
    }
    else{
        for(int i=0;i<X_size*Y_size;i++){
            i%(X_size)==X_size-1?cout<<arry[i]<<"\n":cout<<arry[i];
        }
    }
}
void    del_arry(char* arry){//reminder
    delete [] arry;
}
char* get_b_chunk(int x,int y){//return a bool array
    char *arry;
    arry=new char [x*y];//=' ';
    for(int i=0;i<x*y;i++){
        arry[i]=Open_space;
    }
    return arry;
}
int     conv_pos(int x,int y){//convert pos
    return y*X_size+x;
}
bool    is_free(char* arry,int pos){
    if(arry[pos]==Open_space){return true;}
    return false;
}
bool    is_border(int pos){
    if(pos<X_size){return true;}//top row
    if(pos>X_size*(Y_size-1)){return true;}//bot row
    if(pos%X_size==0){return true;}
    if(pos%(X_size-1)==0){return true;}
    return false;
}
void set_close_walls(char* arry,int x,int y,char set){
    for(int i=0;i<x;i++){//set x top and bottom
        arry[i]=set;//top
        arry[x*(y-1)+i]=set;//bottom
    }
    for(int i=0;i<y;i++){
        arry[y*i]=set;//left
        arry[y*i+x-1]=set;//right
    }
}
void    dbg_b_list(){
    for(map<int,char>::iterator it=b_list.begin();it!=b_list.end();it++){
        cout<<"pos: "<<it->first<<" orientation: "<<it->second<<endl;
    }
}
void set_opens_walls(char* arry,int x,int y, int num){//no guarantee of num openings, could overlap only limit is not corners
    int temp;
    for(int i=0;i<num;i++){//set x top and bottom
        temp=rand();
        cout<<"rand:("<<temp%(x-2)+1<<" or "<<temp%(y-2)+1<<")";
        switch(rand()%4){
            case 0:
                cout<<"top"<<endl;
                temp=temp%(x-2)+1;
                arry[temp]=Open_space;//top
                break;
            case 1:
                cout<<"bot"<<endl;
                temp=temp%(x-2)+1;
                arry[x*(y-1)+temp]='_';//bottom
                break;
            case 2:
                cout<<"lft"<<endl;
                temp=temp%(y-2)+1;
                arry[y*temp]=Open_space;//left
                break;
            case 3:
                cout<<"rgt"<<endl;
                temp=temp%(y-2)+1;
                arry[y*temp+x-1]=Open_space;//righty*
                break;
        }
    }
}
void _rec_gen_wall1(char* arry,int x,int y,int X,int Y){//min,max //should I separate x and y divisions
    if(X-x<=1||Y-y<=1){return;}
    int xtemp=rand()%((X-x))+x;//2+x+1;//
    int ytemp=rand()%((Y-y))+y;//2+y+1;//
    cout<<"recur_("<<xtemp<<" "<<ytemp<<")"<<endl;
    if(X-x>1){
        for(int i=0;i<X-x;i++){//x wall  y*i+x-1 vertical wall
            cout<<i*Y_size+x+xtemp<<" ";
            arry[i*Y_size+x+xtemp]='X';
        }
        cout<<endl;
    }
    else{
        xtemp=x;
    }
    //arry[]='_';//opening?
    if(Y-y>1){
        for(int i=0;i<Y-y;i++){//y wall  x*(y-1)+i horizontal wall
            cout<<Y_size*ytemp+i<<" ";
            arry[Y_size*ytemp+i]='X';
        }
    }
    else{
        ytemp=y;
    }
    cout<<endl;
    //arry[]='_';//opening?
    //_rec_gen_wall(arry,x,y,x+xtemp,y+ytemp);
    //_rec_gen_wall(arry,x,y+ytemp,X-xtemp,Y);//other side?
    //_rec_gen_wall(arry,x+2,y,X,Y-2);
    //_rec_gen_wall1(arry,xtemp,y,X-xtemp,Y);
    //_rec_gen_wall1(arry,x,ytemp,X,Y-ytemp);
    
    //_rec_gen_wall1(arry,xtemp,y,X,Y);
    //_rec_gen_wall1(arry,x,ytemp,X,Y);
    //_rec_gen_wall1(arry,x,y,X-xtemp,Y);
    //_rec_gen_wall1(arry,x,y,X,Y-ytemp);
    
    
    //_rec_gen_wall(arry,x+2,y+2,X,Y);
}
void _rec_gen_wall2(char* arry,int x,int y,int X,int Y){//min,max //should I separate x and y divisions
    if(X-x<=1||Y-y<=1){return;}
    int xtemp=rand()%((X-x))+x;//2+x+1;//
    int ytemp=rand()%((Y-y))+y;//2+y+1;//
    if(X-x>1){
        for(int i=0;i<X-x;i++){//x wall  y*i+x-1 vertical wall
            //cout<<i*Y_size+x+xtemp<<" ";
            arry[i*Y_size+x+xtemp]='X';
        }
    }
    if(Y-y>1){
        for(int i=0;i<Y-y;i++){//y wall  x*(y-1)+i horizontal wall
            //cout<<Y_size*ytemp+i<<" ";
            arry[Y_size*ytemp+i]='X';
        }
    }
    _rec_gen_wall2(arry,xtemp,ytemp,X,Y);
    _rec_gen_wall2(arry,x,y,xtemp,ytemp);
}
void _rec_gen_wall3(char* arry,int x,int y,int X,int Y){//min,max //should I separate x and y divisions
    if(X-x<=1||Y-y<=1){return;}
    int xtemp=((X-x))/2;//+x;//2+x+1;//
    int ytemp=((Y-y))/2;//+y;//2+y+1;//
    //cout<<"r";
    if(X-x>1){
        for(int i=0;i<X-x;i++){//x wall  y*i+x-1 vertical wall
            //cout<<i*Y_size+x+xtemp<<" ";
            arry[i*Y_size+x+xtemp]='X';
        }
    }
    if(Y-y>1){
        for(int i=0;i<Y-y;i++){//y wall  x*(y-1)+i horizontal wall
            //cout<<Y_size*ytemp+i<<" ";
            arry[Y_size*ytemp+i]='X';
        }
    }
    /*
    _rec_gen_wall3(arry,x,y,X-2,Y);//sub (sig fault)
    _rec_gen_wall3(arry,x,y,X,Y-2);
    _rec_gen_wall3(arry,x+2,y,X,Y);
    _rec_gen_wall3(arry,x,y+2,X,Y);
    */
    _rec_gen_wall3(arry,x,y,X/2,Y);//div
    _rec_gen_wall3(arry,x,y,X,Y/2);
    _rec_gen_wall3(arry,x+X/2,y,X,Y);
    _rec_gen_wall3(arry,x,y+Y/2,X,Y);
}
//gen check for + config blocking entrance?//horizontal check _ space +1 and vert check _space or sometimes _space +1?
//3 counting from 0-> 3counting from first space down
//check for tl zero dependence still bounding //one hole per wall?//guessing 3holeper cross//bot quadrants not extending full length to 18line//
void _rec_gen_wall4(char* arry,int tl,int tr,int bl,int br){// four point system //gap funtion needed, can use that to add points to graph
    cout<<"SET:"<<disp<<" (tl:"<<tl<<", tr:"<<tr<<", bl:"<<bl<<", br:"<<br<<"):check="<<tr-tl<<","<<(bl-tr)/(X_size)<<endl;
    int wcheck=tr-tl;
    int hcheck=(bl-tr)/(X_size);
    if(wcheck<=3){return;}//(it's counted as 1 plus open space, hence the different check) //some of the calc q4 give 99 99 on bot values
    if(hcheck<=2){return;}//3 If you can get this down to 2 and find the sig fault all good bby
    //pick a midpoint vert,horizontal
    int vert=rand()%((tr-tl)-3);//difference+tl
    int hori=rand()%(((br-tl)/X_size)-3);//difference  //2 h check sig fault here <=====================
    hori+=2;//handle one opening spacing
    vert+=2;//check against opening so there are no :- or _|_ collisions 
    cout<<"cross:"<<vert<<"("<<vert+tl<<")"<<" x "<<hori<<" ("<<hori*X_size<<")"<<endl;
    char var=48;
    /* in progress
    do{
        vert=rand()%((tr-tl)-3);
        vert+=2;
    }while(is_b_list(tl+vert,'v')||is_b_list(tl+vert+(br-tl-X_size),'v'));//same as north south def below
    do{
        hori=rand()%(((br-tl)/X_size)-3);
        hori+=2;
    }while(is_b_list(tl+vert,'v')||is_b_list(tl+vert+(br-tl-X_size),'v'));
    */
    int north=tl+vert;
    int cross=north+(hori)*X_size;
    int south=north+(br-tl-X_size);//int south=north+(br-tl-X_size);
    int west=cross-vert;
    int east=cross+(tr-tl)-vert;//cross+vert;//(-1)
    
    cout<<"cross:"<<vert<<"("<<vert+tl<<")"<<" x "<<hori<<" ("<<hori*X_size<<")"<<endl;
    
    
    queue<int> h_list;//temp storage for holes
    queue<int> w_list;//temp storage for
    set<int>    hole_h;
    set<int>    hole_w;//orders
    //set values to open hole in walls, needs a third variable value
    int t_counter=1;
    int t_hole=0;//temp value
    if(false){//find values for holes?]
        bool doub_h;//store 3rd option decision
        rand()%2==0?doub_h=true:doub_h=false;//two horizontals?
        do{
            t_hole=rand()%(east-west);
        }while(t_hole==hori);//&&is_b_list(t_hole,'h')
        h_list.push(t_hole);
        if(doub_h){//find values for holes?
            do{
                t_hole=rand()%(east-west);
            }while(t_hole==hori);
            h_list.push(t_hole);
            t_counter=2;
        }
        do{
            t_hole=rand()%((br-tl)/X_size);
        }while(t_hole==vert);
        h_list.push(t_hole);
        if(!doub_h){//find values for holes?
            do{
                t_hole=rand()%((br-tl)/X_size);
            }while(t_hole==vert);
            h_list.push(t_hole);
        }
    }
    
    if(false){//hole generator version 2
        for(int i=0;i<10;i++){//north
        }
    }
    
    
    for(int i=1;i<(tr-tl);i++){//iterate across left right place horizontal
        if(h_list.front()!=i){   
            //arry[tl+(hori*X_size)+i]=var+disp;//arry[X_size*hori+tl%X_size-i]='o';//X_size*hori+tl%X_size+i
            w_list.push(tl+(hori*X_size)+i);
        }
        else{
            h_list.pop();
        }
        cout<<tl+(hori*X_size)+i<<" ";
    }
    cout<<endl;
    for(int i=1;i<((br-tl)/X_size);i++){//iterate top down place vertical
        if(t_hole!=i){
            //arry[(tl)+vert+i*X_size]=var+disp;//arry[(tr-tl)+vert+i*X_size]='|';
            w_list.push((tl)+vert+i*X_size);
        }
        cout<<(tl)+vert+i*X_size<<" ";
    }
    while(!w_list.empty()){//fill from list
        arry[w_list.front()]=var+disp;
        w_list.pop();
    }
    cout<<"hole="<<t_hole<<" "<<t_hole<<endl;
    disp++;//next call
    //need end point formulas
    print_board(arry,true);
    cout<<"\n\n\n";
    cout<<"points "<<disp<<":(n:"<<north<<", c:"<<cross<<", s:"<<south<<", w:"<<west<<", e:"<<east<<")"<<endl;
    //KEY(array,Top left,Top right,Bot Left,Bot right);
    if(tl>north||west>cross){
        cout<<"error stepping in q2"<<endl;
    }
    if(north>tr||cross>east){
        cout<<"error stepping in q1"<<endl;
    }
    if(west>cross||bl>south+X_size){
        cout<<"error stepping in q3"<<endl;
    }
    if(cross>east||south>br){
        cout<<"error stepping in q4"<<endl;
    }
    
    //black_list fill (don't plug paths)
    set_b_list(north,'v');
    set_b_list(south,'v');
    set_b_list(cross,'b');
    set_b_list(east,'h');
    set_b_list(west,'h');
    
    cout<<"top left"<<endl;
    _rec_gen_wall4(arry,tl,north,west,cross);//q1-top left
    cout<<"top right"<<endl;
    _rec_gen_wall4(arry,north,tr,cross,east);//q2-top right
    cout<<"bot left"<<endl;
    _rec_gen_wall4(arry,west,cross,bl,bl+(cross-west));//q3-bot left//having issues outta bounds south needs augment off line comparison
    //south+X_size?//still occationally overwriting bot border
    cout<<"bot right"<<endl;
    _rec_gen_wall4(arry,cross,east,bl+(cross-west),br);//q4-bot right pass value off?
}
void _rec_gen_wall4_fill(char* arry,int tl,int tr,int bl,int br,bool ori){
    
}
