package com.comp241.scratch_o_sphere.parser;

public class VisualHandler {

	public VisualHandler(Parser p) {
		p.functionHive.put("blink", new ICommandHandler() {
			@Override
			public void HandleCommand(Parser p, String[] c) {
				boolean onoff = Toolbox.parseBoolean(c[1]);
				int time = Integer.parseInt(c[2]);
				int r = Integer.parseInt(c[3]);
				int g = Integer.parseInt(c[4]);
				int b = Integer.parseInt(c[5]);
				
				// Sphero API call
			}
		});		
	}
}
