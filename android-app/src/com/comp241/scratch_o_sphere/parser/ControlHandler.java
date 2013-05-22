package com.comp241.scratch_o_sphere.parser;


public class ControlHandler {
	
	public ControlHandler(Parser p) {
		p.functionHive.put("set", new ICommandHandler() {
			@Override
			public void HandleCommand(Parser p, String[] c) {
				p.variableHive.put(c[1], c[2]);				
			}			
		});
		p.functionHive.put("loopfor", new ICommandHandler() {
			@Override
			public void HandleCommand(Parser p, String[] c) {
				int count = Integer.parseInt(c[1]);
				p.loopStack.addLoop(new LoopStack(count, p.commandPointer));
			}
		});
		p.functionHive.put("endloopfor", new ICommandHandler() {
			@Override
			public void HandleCommand(Parser p, String[] c) {
				LoopStack lowest = p.loopStack.getLowestLoop();
				lowest.decrementLoop();
				if(!lowest.isLoopFinished())
					p.commandPointer = lowest.pointer + 1;
				else
					p.loopStack.removeLowestLoop();
			}
		});
		p.functionHive.put("write", new ICommandHandler() {
			@Override
			public void HandleCommand(Parser p, String[] c) {
				System.out.println(c[1]);
			}
		});
	}
}