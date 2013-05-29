package com.comp241.scratch_o_sphere.parser;

import orbotix.robot.base.Robot;

public class MotionHandler {

	public MotionHandler(Parser p) {
		p.functionHive.put("movedistance", 
				
			new ICommandHandler() {
				@Override
				public void HandleCommand(Robot r, Parser p, String[] c) {
					int distance = Integer.parseInt(c[1]);
					float speed = Float.parseFloat(p.variableHive.get("SPEED").toString());
					float heading = Float.parseFloat(p.variableHive.get("HEADING").toString());
					
					speed = speed / 100;					
					float metresSec = (float) (speed * 0.9144);					
					float time = distance / metresSec;

					orbotix.robot.base.RollCommand.sendCommand(r, heading, speed);
					
					try {
						Thread.sleep((long) time);
					} catch (InterruptedException e) {
						e.printStackTrace();
					}
					orbotix.robot.base.RollCommand.sendStop(r);
				}			
		});
		
		p.functionHive.put("turnleft", new ICommandHandler() {
			@Override
			public void HandleCommand(Robot r, Parser p, String[] c) {
				int heading = Integer.parseInt((String) p.variableHive.get("HEADING"));
				heading = -90;
				
				// Sphero call roll(0, heading);
			}			
		});
	}
}
