package com.comp241.scratch_o_sphere.parser;

import com.comp241.scratch_o_sphere.ScriptRunActivity;

public class MotionHandler {

	public MotionHandler(Parser p) {
		p.functionHive.put("goforward", new ICommandHandler() {
			@Override
			public long HandleCommand(Parser p, String[] c) {
				float distance = Float.parseFloat(c[1]);
				float speed = Float.parseFloat(p.variableHive.get("SPEED").toString());
				float heading = Float.parseFloat(p.variableHive.get("HEADING").toString());
				
				speed = speed / 100;
				float metresSec = (float) (speed * 0.9144);
				long time = (long) ((distance / metresSec) * 1000);

				ScriptRunActivity.robotControl.roll(heading, speed);
				try {
					Thread.sleep(time);
				} catch (InterruptedException e) {
					e.printStackTrace();
				}
				ScriptRunActivity.robotControl.stopMotors();
				return 100;
			}
		});	
		p.functionHive.put("gobackward", new ICommandHandler() {
			@Override
			public long HandleCommand(Parser p, String[] c) {
				float distance = Float.parseFloat(c[1]);
				float speed = Float.parseFloat(p.variableHive.get("SPEED").toString());
				float heading = Float.parseFloat(p.variableHive.get("HEADING").toString());
				
				speed = speed / 100;
				float metresSec = (float) (speed * 0.9144);
				long time = (long) ((distance / metresSec) * 1000);
				
				heading = ((heading - 180) + 360) % 360;

				ScriptRunActivity.robotControl.roll(heading, speed);
				try {
					Thread.sleep(time);
				} catch (InterruptedException e) {
					e.printStackTrace();
				}
				ScriptRunActivity.robotControl.stopMotors();
				return 100;
			}
		});
		p.functionHive.put("goforwardsfor", new ICommandHandler() {
			@Override
			public long HandleCommand(Parser p, String[] c) {
				long time = (long) (Float.parseFloat(c[1]) * 1000);
				float speed = Float.parseFloat(p.variableHive.get("SPEED").toString());
				float heading = Float.parseFloat(p.variableHive.get("HEADING").toString());
				
				speed = speed / 100;

				ScriptRunActivity.robotControl.roll(heading, speed);
				try {
					Thread.sleep(time);
				} catch (InterruptedException e) {
					e.printStackTrace();
				}
				ScriptRunActivity.robotControl.stopMotors();
				return 100;
			}
		});
		p.functionHive.put("gobackwardsfor", new ICommandHandler() {
			@Override
			public long HandleCommand(Parser p, String[] c) {
				long time = (long) (Float.parseFloat(c[1]) * 1000);
				float speed = Float.parseFloat(p.variableHive.get("SPEED").toString());
				float heading = Float.parseFloat(p.variableHive.get("HEADING").toString());
				
				speed = speed / 100;
				heading = ((heading - 180) + 360) % 360;

				ScriptRunActivity.robotControl.roll(heading, speed);
				try {
					Thread.sleep(time);
				} catch (InterruptedException e) {
					e.printStackTrace();
				}
				ScriptRunActivity.robotControl.stopMotors();
				return 100;
			}
		});
		p.functionHive.put("turnright", new ICommandHandler() {
			@Override
			public long HandleCommand(Parser p, String[] c) {
				int heading = Integer.parseInt(p.variableHive.get("HEADING").toString());
				heading += 90;
				p.variableHive.put("HEADING", heading);
				ScriptRunActivity.robotControl.rotate(heading);
				return 1000;
			}			
		});
		p.functionHive.put("turnleft", new ICommandHandler() {
			@Override
			public long HandleCommand(Parser p, String[] c) {
				int heading = Integer.parseInt(p.variableHive.get("HEADING").toString());
				heading -= 90;
				p.variableHive.put("HEADING", heading);
				ScriptRunActivity.robotControl.rotate(heading);
				return 1000;
			}			
		});
		p.functionHive.put("turnclockwise", new ICommandHandler() {
			@Override
			public long HandleCommand(Parser p, String[] c) {
				int heading = Integer.parseInt(p.variableHive.get("HEADING").toString());
				heading += Integer.parseInt(c[1]);
				p.variableHive.put("HEADING", heading);
				ScriptRunActivity.robotControl.rotate(heading);
				return 1000;
			}			
		});
		p.functionHive.put("turnanticlockwise", new ICommandHandler() {
			@Override
			public long HandleCommand(Parser p, String[] c) {
				int heading = Integer.parseInt(p.variableHive.get("HEADING").toString());
				heading -= Integer.parseInt(c[1]);
				p.variableHive.put("HEADING", heading);
				ScriptRunActivity.robotControl.rotate(heading);
				return 1000;
			}			
		});
	}
}
