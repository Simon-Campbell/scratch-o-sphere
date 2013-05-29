package com.comp241.scratch_o_sphere.parser;
import java.io.*;
import java.util.*;

import orbotix.robot.base.Robot;

public class Parser {
	Map<String, Object> variableHive;
	Map<String, ICommandHandler> functionHive;
	LoopStack loopStack = new LoopStack(0, 0);
	
	private ControlHandler controlHandler;
	private MotionHandler motionHandler;
	private VisualHandler visualHandler;
	
	int commandPointer = 0;
	
	ArrayList<String> parsedCommands;
	
	Robot robot;
	
	public Parser(Robot r) {
		robot = r;
		
		parsedCommands = new ArrayList<String>();
		
		// Set up variable hive and define default values
		variableHive = new HashMap<String, Object>();		
		variableHive.put("SPEED", 0);				
		variableHive.put("HEADING", 0);	
		variableHive.put("FLASHSPEED", 0);

		// Set up command handlers
		functionHive = new HashMap<String, ICommandHandler>();
		controlHandler = new ControlHandler(this);
		motionHandler = new MotionHandler(this);
		visualHandler = new VisualHandler(this);
	}
	
	public void parseString(String str) {
		parsedCommands.clear();
		String[] lines = str.split("\n");
		
		for(String line : lines) {
			line = line.trim();
			String newLine = "";
			for(char c : line.toCharArray()) {
				if(c != '#' && c != '\t' && c != ' ') {
					newLine += c;					
				}
				else {
					break;
				}
			}
			parsedCommands.add(newLine);
		}
	}
	
	public void parseFile(String file) throws IOException {		
		parsedCommands.clear();
		File f = new File("C:\\script.sos");
		FileReader fr = new FileReader(f);
		BufferedReader reader = new BufferedReader(fr);
		String line;
		while ( (line = reader.readLine()) != null ) {
			line = line.trim();
			String newLine = "";
			for(char c : line.toCharArray()) {
				if(c != '#' && c != '\t' && c != ' ') {
					newLine += c;					
				}
				else {
					break;
				}
			}
			parsedCommands.add(newLine);
		}		
	}
	
	public void run() {
		commandPointer = 0;
		while(commandPointer < parsedCommands.size()) {
			String command = parsedCommands.get(commandPointer).toLowerCase();
			String[] commandSplit = command.split(",");
			
			if(commandSplit.length > 0 && functionHive.containsKey(commandSplit[0])) {
				ICommandHandler handler = functionHive.get(commandSplit[0]);
				handler.HandleCommand(robot, this, commandSplit);
			}
			commandPointer++;
		}		
	}
}
