/* 
 * File:   main.cpp
 * Author: Shane Hazelquist
 * Purpose: 
 * Created on October 4, 2017, 12:47 PM
 */
//System Libraries
#include <iostream>

using namespace std;
//User Libraries
#include "Maze_mod.h"
//Global Constants

//Function Prototypes

/* Notes:
 * set holes is blisting garbage sometimes, need to implements rando fill 2 by x spaces
 */
//Execution Start

int main(int argc, char** argv) {
    //Declare Variables
    srand(static_cast<unsigned int>(time(0)));
    Maze_mod maze(30,30,' ','X');//x,y,open,close
    maze.set_walls();
    maze.print_board(true);//is debug print? (adds spaces and line values)
    //Initialize Variables

    //Map in-outputs

    //Display outputs

    //End EXE
    return 0;
}

