package com.comp241.scratch_o_sphere.parser;

import java.util.Random;

import android.os.Handler;

import com.comp241.scratch_o_sphere.ScriptRunActivity;

import orbotix.robot.base.PollPacketTimesCommand;
import orbotix.robot.base.RGBLEDOutputCommand;

public class VisualHandler {

	boolean isFlashing = false;
	boolean lightsOn = false;
	Random rand;
	
	public VisualHandler(Parser p) {
		rand = new Random();
		p.functionHive.put("flashfor", new ICommandHandler() {
			@Override
			public long HandleCommand(Parser p, String[] c) {
				//isFlashing = Toolbox.parseBoolean(c[1]);				
				int time = Integer.parseInt(c[1]);
				int r = Integer.parseInt(p.variableHive.get("RED").toString());
				int g = Integer.parseInt(p.variableHive.get("GREEN").toString());
				int b = Integer.parseInt(p.variableHive.get("BLUE").toString());
				flash(p, false, r, g, b, time, false);
				return 0;
			}
		});	
		p.functionHive.put("flashrandomfor", new ICommandHandler() {
			@Override
			public long HandleCommand(Parser p, String[] c) {			
				int time = Integer.parseInt(c[1]);
				flash(p, false, 0, 0, 0, time, true);
				return 0;
			}
		});
		p.functionHive.put("randomizecolour", new ICommandHandler() {
			@Override
			public long HandleCommand(Parser p, String[] c) {
				int r = rand.nextInt(255);
				int g = rand.nextInt(255);
				int b = rand.nextInt(255);
				p.variableHive.put("RED", r);
				p.variableHive.put("GREEN", g);
				p.variableHive.put("BLUE", b);
				if(lightsOn) {
					RGBLEDOutputCommand.sendCommand(ScriptRunActivity.robot, r, g, b);
					return 100;
				}
				return 0;
			}
		});
		p.functionHive.put("setcolour", new ICommandHandler() {
			@Override
			public long HandleCommand(Parser p, String[] c) {
				int r = Integer.parseInt(c[1].trim());
				int g = Integer.parseInt(c[2].trim());
				int b = Integer.parseInt(c[3].trim());
				p.variableHive.put("RED", r);
				p.variableHive.put("GREEN", g);
				p.variableHive.put("BLUE", b);
				if(lightsOn) {
					RGBLEDOutputCommand.sendCommand(ScriptRunActivity.robot, r, g, b);
					return 100;
				}
				return 0;
			}
		});
		p.functionHive.put("turnonfor", new ICommandHandler() {
			@Override
			public long HandleCommand(Parser p, String[] c) {	
				isFlashing = false;
				lightsOn = true;
				int time = Integer.parseInt(c[1]);
				int r = Integer.parseInt(p.variableHive.get("RED").toString());
				int g = Integer.parseInt(p.variableHive.get("GREEN").toString());
				int b = Integer.parseInt(p.variableHive.get("BLUE").toString());
				RGBLEDOutputCommand.sendCommand(ScriptRunActivity.robot, r, g, b);
				final Handler handler = new Handler();
				handler.postDelayed(new Runnable() {
					public void run() {
						lightsOn = false;
						Toolbox.wait50();
						RGBLEDOutputCommand.sendCommand(ScriptRunActivity.robot, 0, 0, 0);
						Toolbox.wait50();
					}
				}, time * 1000);
				return 100;
			}
		});
		p.functionHive.put("turnledon", new ICommandHandler() {
			@Override
			public long HandleCommand(Parser p, String[] c) {
				isFlashing = false;
				lightsOn = true;
				int r = Integer.parseInt(p.variableHive.get("RED").toString());
				int g = Integer.parseInt(p.variableHive.get("GREEN").toString());
				int b = Integer.parseInt(p.variableHive.get("BLUE").toString());
				RGBLEDOutputCommand.sendCommand(ScriptRunActivity.robot, r, g, b);
				return 100;
			}
		});
		p.functionHive.put("turnledoff", new ICommandHandler() {
			@Override
			public long HandleCommand(Parser p, String[] c) {
				isFlashing = false;
				lightsOn = false;
				RGBLEDOutputCommand.sendCommand(ScriptRunActivity.robot, 0, 0, 0);
				return 100;
			}
		});
	}
	
	private void flash(final Parser p, final boolean lit, final int r, final int g, final int b, final long time, final boolean random){
		lightsOn = true;
		long flashSpeed = Long.parseLong(p.variableHive.get("FLASHSPEED").toString());
		if(flashSpeed == 0) {
			flashSpeed = 1;
			p.variableHive.put("FLASHSPEED", flashSpeed);
		}
		flashSpeed = 1000 / flashSpeed;
		if(ScriptRunActivity.robot != null && isFlashing){
			if(lit) {				
				RGBLEDOutputCommand.sendCommand(ScriptRunActivity.robot, 0, 0, 0);
			}
			else{
				if(random)
					RGBLEDOutputCommand.sendCommand(ScriptRunActivity.robot, rand.nextInt(255), rand.nextInt(255), rand.nextInt(255));
				else
					RGBLEDOutputCommand.sendCommand(ScriptRunActivity.robot, r, g, b);
			}
			final Long newTime = time - flashSpeed;
			//Send delayed message on a handler to run flash again
			final Handler handler = new Handler();
			handler.postDelayed(new Runnable() {
				public void run() {
					flash(p, !lit, r, g, b, newTime, random);
				}
			}, flashSpeed);
		}
		lightsOn = false;
	}
}
