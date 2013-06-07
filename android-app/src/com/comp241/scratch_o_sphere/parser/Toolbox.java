package com.comp241.scratch_o_sphere.parser;

public class Toolbox {
	public static boolean parseBoolean(String val) {
		return (val == "1") ? true : false;		
	}
	
	public static void wait500() {
		try {
			Thread.sleep(500);
		} catch (InterruptedException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}		
	}
	
	public static void wait50() {
		try {
			Thread.sleep(50);
		} catch (InterruptedException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}		
	}
}
