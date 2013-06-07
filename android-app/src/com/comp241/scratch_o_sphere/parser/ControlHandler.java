package com.comp241.scratch_o_sphere.parser;

public class ControlHandler {
	
	public ControlHandler(Parser p) {
		p.functionHive.put("set", new ICommandHandler() {
			@Override
			public long HandleCommand(Parser p, String[] c) {
				p.variableHive.put(c[1], c[2]);
				return 0;
			}			
		});
		p.functionHive.put("add", new ICommandHandler() {
			@Override
			public long HandleCommand(Parser p, String[] c) {
				float add = Float.parseFloat(c[2]);
				Float value;
				if((value = Float.parseFloat(p.variableHive.get(c[1]).toString())) == null)
					value = (float) 0;				
				value += add;					
				p.variableHive.put(c[1], value);
				return 0;
			}			
		});
		p.functionHive.put("minus", new ICommandHandler() {
			@Override
			public long HandleCommand(Parser p, String[] c) {
				float sub = Float.parseFloat(c[2]);
				Float value;
				if((value = Float.parseFloat(p.variableHive.get(c[1]).toString())) == null)
					value = (float) 0;				
				value -= sub;					
				p.variableHive.put(c[1], value);
				return 0;
			}			
		});
		p.functionHive.put("multiply", new ICommandHandler() {
			@Override
			public long HandleCommand(Parser p, String[] c) {
				float mult = Float.parseFloat(c[2]);
				Float value;
				if((value = Float.parseFloat(p.variableHive.get(c[1]).toString())) == null)
					value = (float) 0;				
				value *= mult;					
				p.variableHive.put(c[1], value);
				return 0;
			}			
		});
		p.functionHive.put("divide", new ICommandHandler() {
			@Override
			public long HandleCommand(Parser p, String[] c) {
				float div = Float.parseFloat(c[2]);
				Float value;
				if((value = Float.parseFloat(p.variableHive.get(c[1]).toString())) == null)
					value = (float) 0;				
				value /= div;					
				p.variableHive.put(c[1], value);
				return 0;
			}			
		});
		p.functionHive.put("mod", new ICommandHandler() {
			@Override
			public long HandleCommand(Parser p, String[] c) {
				float mod = Float.parseFloat(c[2]);
				Float value;
				if((value = Float.parseFloat(p.variableHive.get(c[1]).toString())) == null)
					value = (float) 0;				
				value %= mod;					
				p.variableHive.put(c[1], value);
				return 0;
			}			
		});
		p.functionHive.put("wait", new ICommandHandler() {
			@Override
			public long HandleCommand(Parser p, String[] c) {
				float secs = Float.parseFloat(c[1]);
				long time  = (long) (secs * 1000);
				try {
					Thread.sleep(time);
				} catch (InterruptedException e) {
					e.printStackTrace();
				}
				return 0;
			}
		});
		p.functionHive.put("loopfor", new ICommandHandler() {
			@Override
			public long HandleCommand(Parser p, String[] c) {
				int count = Integer.parseInt(c[1]);
				p.loopStack.addLoop(new LoopStack(count, p.commandPointer));
				return 0;
			}
		});
		p.functionHive.put("endloop", new ICommandHandler() {
			@Override
			public long HandleCommand(Parser p, String[] c) {
				LoopStack lowest = p.loopStack.getLowestLoop();
				lowest.decrementLoop();
				if(!lowest.isLoopFinished())
					p.commandPointer = lowest.pointer;
				else
					p.loopStack.removeLowestLoop();
				return 0;
			}
		});
		p.functionHive.put("write", new ICommandHandler() {
			@Override
			public long HandleCommand(Parser p, String[] c) {
				System.out.println(c[1]);
				return 0;
			}
		});
	}
}