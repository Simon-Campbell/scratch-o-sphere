package com.comp241.scratch_o_sphere.parser;

import orbotix.robot.base.Robot;

public interface ICommandHandler {
	public void HandleCommand(Robot r, Parser p, String[] c);
}
