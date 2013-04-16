package com.comp241.scratch_o_sphere;

//import orbotix.robot.base.Robot;
import orbotix.robot.base.*;

public class SpheroController {
	
	private Robot mRobot;
	private int mHeading;
	
	public SpheroController(Robot robot) {
		mRobot = robot;
	}
	
	///Getter; Setter;///
	
	public int getHeading() {
		return mHeading;
	}
	
	public void setHeading(int value) {
		mHeading = Math.min(Math.max(value, 0), 356);
	}
	
	///Sphero control methods////
	
	public void setBackLED(float brightness) {
		BackLEDOutputCommand.sendCommand(mRobot, brightness);
	}
	
	public void moveVelocity(float velocity) {
		RollCommand.sendCommand(mRobot, getHeading(), velocity, false);
	}
	
	public void moveVelocity(float velocity, int heading) {
		setHeading(heading);
		RollCommand.sendCommand(mRobot, getHeading(), velocity, false);
	}
	
	public void moveVelocityTime(float velocity, float time) {
		RollCommand.sendCommand(mRobot, getHeading(), velocity, false);		
	}
	
	public void moveVelocityTime(float velocity, float time, int heading) {
		setHeading(heading);
		RollCommand.sendCommand(mRobot, getHeading(), velocity, false);		
	}
}
