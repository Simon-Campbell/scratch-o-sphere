package com.comp241.scratch_o_sphere.parser;

public class LoopStack {
		int count;
		int pointer;
		LoopStack nextLoop;
		
		LoopStack(int c, int p) {
			count = c;
			pointer = p;
		}
		
		LoopStack getLowestLoop() {
			return (nextLoop != null) ? nextLoop.getLowestLoop() : this;
		}
		
		void addLoop(LoopStack stack) {
			if(nextLoop != null)
				nextLoop.addLoop(stack);
			else if(count == 0) {
				count = stack.count;
				pointer = stack.pointer;
				nextLoop = stack.nextLoop;
			}
			else
				nextLoop = stack;
		}
		
		void removeLowestLoop() {
			if(nextLoop == null)
				count = 0;
			else if(nextLoop == getLowestLoop())
				nextLoop = null;
			else
				nextLoop.removeLowestLoop();
		}
		
		boolean isLoopFinished() {
			return (count == 0);
		}
		
		void decrementLoop() {
			count--;
		}
	}