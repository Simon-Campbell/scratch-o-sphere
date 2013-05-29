package com.comp241.scratch_o_sphere.parser;

import orbotix.robot.base.Robot;

public class VisualHandler {

	public VisualHandler(Parser p) {
		p.functionHive.put("blink", new ICommandHandler() {
			@Override
			public void HandleCommand(Robot r, Parser p, String[] c) {
				boolean onoff = Toolbox.parseBoolean(c[1]);
				int time = Integer.parseInt(c[2]);
				int red = Integer.parseInt(c[3]);
				int green = Integer.parseInt(c[4]);
				int blue = Integer.parseInt(c[5]);
				
				// Sphero API call
			}
		});		
	}
}
