package com.comp241.scratch_o_sphere.parser;
import java.io.*;
import java.util.*;

import com.comp241.scratch_o_sphere.ScriptRunActivity;

import android.widget.ArrayAdapter;
import android.widget.EditText;

public class Parser {
	Map<String, Object> variableHive;
	Map<String, ICommandHandler> functionHive;
	LoopStack loopStack = new LoopStack(0, 0);

	int commandPointer = 0;
	
	ArrayList<String> parsedCommands;
	
	ScriptRunActivity main;
	
	public Parser(ScriptRunActivity s) {
		main = s;
		
		parsedCommands = new ArrayList<String>();
		
		// Set up variable hive and define default values
		variableHive = new HashMap<String, Object>();		
		variableHive.put("SPEED", 100);				
		variableHive.put("HEADING", 0);	
		
		variableHive.put("FLASHSPEED", 1);
		variableHive.put("RED", 255);
		variableHive.put("GREEN", 255);
		variableHive.put("BLUE", 255);

		// Set up command handlers
		functionHive = new HashMap<String, ICommandHandler>();
		new ControlHandler(this);
		new MotionHandler(this);
		new VisualHandler(this);
		new AudioHandler(this);
	}
	
	public void parseString(String str) {
		parsedCommands.clear();
		String[] lines = str.split("\n");
		
		for(String line : lines) {
			line = line.trim();
			String newLine = "";
			for(char c : line.toCharArray()) {
				if(c != '#' && c != '\t') {
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
				if(c != '#' && c != '\t') {
					newLine += c;					
				}
				else {
					break;
				}
			}
			parsedCommands.add(newLine);
		}
		reader.close();
	}
	
	public void run() {
		commandPointer = 0;
		while(commandPointer < parsedCommands.size()) {
			final String command = parsedCommands.get(commandPointer).toLowerCase(Locale.getDefault());
			String[] commandSplit = command.split(",");
			
			if(commandSplit.length > 0 && functionHive.containsKey(commandSplit[0])) {
				main.commandList.add(command);
				ArrayAdapter<String> adapter = new ArrayAdapter<String>(main, android.R.layout.simple_list_item_1, main.commandList);
				main.commands.setAdapter(adapter);
				ICommandHandler handler = functionHive.get(commandSplit[0]);
				long waitTime = handler.HandleCommand(this, commandSplit);
				if(waitTime > 0) {
					try {
						Thread.sleep(waitTime);
					} catch (InterruptedException e) {
						e.printStackTrace();
					}
				}				
			}
			commandPointer++;
		}		
	}
}
