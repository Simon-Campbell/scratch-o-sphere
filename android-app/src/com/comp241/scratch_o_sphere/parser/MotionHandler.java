package com.comp241.scratch_o_sphere.parser;

public class MotionHandler {

	public MotionHandler(Parser p) {
		p.functionHive.put("movedistance", new ICommandHandler() {
			@Override
			public void HandleCommand(Parser p, String[] c) {
				int distance = Integer.parseInt(c[1]);
				int speed = Integer.parseInt(p.variableHive.get("SPEED").toString());
				// Sphero API call
			}			
		});
		
		p.functionHive.put("turnleft", new ICommandHandler() {
			@Override
			public void HandleCommand(Parser p, String[] c) {
				int heading = Integer.parseInt((String) p.variableHive.get("HEADING"));
				heading = -90;
				
				// Sphero call roll(0, heading);
			}			
		});
	}
}
