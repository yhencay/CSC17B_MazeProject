
/* 
 * File:   Maze_mod.h
 * Author: Shane Hazelquist
 *
 * Created on October 4, 2017, 12:48 PM
 */
#include <iostream>
#include <time.h>
#include <stdlib.h>
#include <set>
#include <queue>
#include <map>

class Maze_mod{
private:
    
protected:
    //standardized variables
    int X_size;
    int Y_size;
    char C_hall;//holds "empty space" char
    char C_wall;//holds "filled space" char
    int Start;//starting position
    int Finish;//finish position
    //utility variables
    int gen_cycles;
    //main storage
    char *maize;//array holding the map
    set<int> b_list;//holds blacklisted indexes to not be overwritten
    //protected functions
    void    _rec_gen_wall(int,int,int,int);
public:
    Maze_mod(int x,int y,char open, char closed){
        gen_cycles=0;
        X_size=x;
        Y_size=y;
        C_hall=open;
        C_wall=closed;
        maize=new char[X_size*Y_size];//create space
        for(int i=0;i<X_size*Y_size;i++){
            if(i<X_size||i>X_size*(Y_size-1)){
                maize[i]=C_wall;//top bottom boarder
            }
            else if(i%(X_size)==0||i%(X_size)==X_size-1){
                maize[i]=C_wall;//set left right
            }
            else{
                maize[i]=C_hall;
            }
        }
    }
    ~Maze_mod(){
        delete []maize;//free memory
        b_list.clear();
    }
    //initialize functions
    void    set_boarder_sf();//set start and finish (note, must be called before walls are gen)
    void    set_walls();//calls recursive function
    //utility functions
    void    print_board(bool);//debug mode?
    int     xy_convert(int,int);//converts a coordinate to value
    int     set_hole(int,int,int,bool);//(low num,high num,is horizonal?) returns a value to hole
    void    add_blist(int,bool);//value, tbd
    bool    is_blist(int);
    //use functions
    bool    is_open(int);//returns true if the space is not occupied
};
//protected funcitons
void    Maze_mod::_rec_gen_wall(int tl,int tr,int bl,int br){
    //end conditions
    if((tr-tl)<=3){return;}
    if(((bl-tr)/(X_size))<=2){return;}
    //decide a division
    int vert=2+rand()%((tr-tl)-3);//decision
    int hori=2+rand()%(((br-tl)/X_size)-3);//decision
    //get some information based on the division
    int north=tl+vert;
    int cross=north+(hori)*X_size;
    int west=cross-vert;
    int east=cross+(tr-tl)-vert;
    int south=bl+(cross-west);
    //store decision
    queue<int> w_list;//write list
    for(int i=1;i<(tr-tl);i++){//iterate across left right place horizontal
        w_list.push(tl+(hori*X_size)+i);
    }
    for(int i=1;i<((br-tl)/X_size);i++){//iterate top down place vertical
        w_list.push((tl)+vert+i*X_size);
    }
    //find holes
    int holes[4]={0,0,0,-1};//max four, typically three
    bool hole_dir[4]={true,true,true,true};//(north,south,east,west)know which arm we've drilled 
    int h_index=0;//know how many more to add
    if(is_blist(north)){//check edges for path blocking, handle with holes
        cout<<"nb"<<endl;
        holes[h_index++]=north+X_size;
        hole_dir[0]=false;
    }
    if(is_blist(south)){
        cout<<"sb"<<endl;
        holes[h_index++]=south-X_size;
        hole_dir[1]=false;
    }
    if(is_blist(east)){
        cout<<"eb"<<endl;
        holes[h_index++]=east-1;
        hole_dir[2]=false;
    }
    if(is_blist(west)){
        cout<<"wb"<<endl;
        holes[h_index++]=west+1;
        hole_dir[3]=false;
    }
    while(h_index<3){//not super optimized but meh
        switch(rand()%4){//225(too small) 345(fine) 309(fine)
            case 0:
                if(hole_dir[0]){
                    holes[h_index++]=set_hole(north,cross,cross,false);
                    hole_dir[0]=false;
                }
                break;
            case 1:
                if(hole_dir[1]){
                    holes[h_index++]=set_hole(cross,south,cross,false);
                    hole_dir[1]=false;
                }
                break;
            case 2:
                if(hole_dir[2]){
                    holes[h_index++]=set_hole(cross,east,cross,true);
                    hole_dir[2]=false;
                }
                break;
            case 3:
                if(hole_dir[3]){
                    holes[h_index++]=set_hole(west,cross,cross,true);
                    hole_dir[3]=false;
                }
                break;
        }
    }
    //updated blacklist
    char conv=48;
    cout<<"("<<static_cast<char>(conv+gen_cycles)<<")HOLES: ";
    for(int i=0;i<4;i++){
        add_blist(holes[i],false);
        cout<<holes[i]<<" ";
    }
    cout<<endl;
    //push stored values into the maize
    while(!w_list.empty()){//transfer list data to maize
        if(!is_blist(w_list.front())){//not blacklisted? add to maze
            maize[w_list.front()]=C_wall;
            //cout<<w_list.front()<<" ";
        }
        w_list.pop();
    }
    maize[cross]=48+gen_cycles;
    gen_cycles++;
    //recursively call new quadrants
    _rec_gen_wall(tl,north,west,cross);//q1-top left
    _rec_gen_wall(north,tr,cross,east);//q2-top right
    _rec_gen_wall(west,cross,bl,south);//q3-bot left
    _rec_gen_wall(cross,east,south,br);//q4-bot right
}
//initialize functions
void    Maze_mod::set_boarder_sf(){
    
}
void    Maze_mod::set_walls(){
    _rec_gen_wall(0,X_size-1,X_size*(Y_size-1),X_size*Y_size-1);
}
//utility functions
void    Maze_mod::print_board(bool extra){
    if(extra){
        for(int i=0;i<X_size*Y_size;i++){
            i%(X_size)==X_size-1?cout<<maize[i]<<i<<"\n":cout<<maize[i]<<" ";
        }
    }
    else{
        for(int i=0;i<X_size*Y_size;i++){
            i%(X_size)==X_size-1?cout<<maize[i]<<"\n":cout<<maize[i];
        }
    }
}
int     Maze_mod::xy_convert(int x,int y){//convert pos
    return y*X_size+x;
}
int     Maze_mod::set_hole(int min, int max,int dead, bool horiz){//choose a spot
    int temp;
    cout<<"("<<min<<","<<max<<")!"<<dead;
    do{
        if(horiz){
            temp= min+1+rand()%(max-min-1);// not edges
        }
        else{
            temp=(min)+(X_size)*(1+rand()%((max-min)/(X_size)-1));//not edges
        }
    }while(temp==dead||temp<min||temp>max);//remove center generations
    cout<<"hole_"<<horiz<<":"<<temp<<endl;
    return temp;
}
void    Maze_mod::add_blist(int pos,bool cross){
    b_list.insert(pos);
    if(cross){//blacklist area around pos
        b_list.insert(pos-1);
        b_list.insert(pos+1);
        b_list.insert(pos-X_size);
        b_list.insert(pos+X_size);
    }
}
bool    Maze_mod::is_blist(int pos){
    set<int>::iterator it;
    it=b_list.find(pos);
    if(it==b_list.end()){
        return false;
    }
    else{
        return true;
    }
}
//use functions
bool    Maze_mod::is_open(int pos){
    if(maize[pos]==C_hall){
        return true;
    }
    return false;
}

