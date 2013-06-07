package com.comp241.scratch_o_sphere;

import java.io.IOException;
import java.util.ArrayList;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import com.comp241.scratch_o_sphere.parser.Parser;
import orbotix.robot.base.Robot;
import orbotix.robot.base.RobotControl;
import orbotix.robot.base.RobotProvider;
import orbotix.robot.base.RobotProvider.OnRobotConnectedListener;
import orbotix.robot.base.RobotProvider.OnRobotConnectionFailedListener;
import orbotix.robot.base.RobotProvider.OnRobotDisconnectedListener;
import android.os.Bundle;
import android.os.Handler;
import android.os.StrictMode;
import android.app.Activity;
import android.view.Menu;
import android.view.MenuItem;
import android.widget.ArrayAdapter;
import android.widget.EditText;
import android.widget.ListView;
import android.widget.Toast;
import android.support.v4.app.NavUtils;
import android.annotation.TargetApi;
import android.os.Build;

public class ScriptRunActivity extends Activity {

    public static SpheroController spheroController;
    public static String data;

    public static Robot robot;
    public static RobotControl robotControl;
    
    private Handler mHandler = new Handler();
    private Parser parser;
    
    private String token;
    private String scriptID;

	public ArrayList<String> commandList;
    public ListView commands;
    
    private ScriptRunActivity me;
    
    public Handler myThread;
    
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_script_run);
		
		me = this;
		myThread = new Handler();

		commandList = new ArrayList<String>();
		ArrayAdapter<String> adapter = new ArrayAdapter<String>(this, android.R.layout.simple_list_item_1, commandList);
		commands = (ListView)findViewById(R.id.scripts);
		commands.setAdapter(adapter);
		
        if (android.os.Build.VERSION.SDK_INT > 9) {
			StrictMode.ThreadPolicy policy = new StrictMode.ThreadPolicy.Builder().permitAll().build();
			StrictMode.setThreadPolicy(policy);
		}
        
		token = this.getIntent().getStringExtra("token");
		scriptID = this.getIntent().getStringExtra("scriptID");

		JSONObject scriptRequest = null;
		JSONArray scripts = new JSONArray();

		try {
			scriptRequest = JSONWebRequest.getJsonObject("http://54.252.102.19/api/" + token + "/getscript/" + scriptID);
		} catch (IOException e) {
			e.printStackTrace();
		} catch (JSONException e) {
			e.printStackTrace();
		}
		
		try {
			scripts = scriptRequest.getJSONArray("scripts");
		} catch (JSONException e) {
			e.printStackTrace();
		}
		
		JSONObject obj;
		try {
			obj = scripts.getJSONObject(0);		
			ScriptRunActivity.data = obj.getString("DATA");
		} 
		catch (JSONException e) {
			e.printStackTrace();
		}
        
        // Tell the Robot Provider to find all the paired robots
        // Only do this if the bluetooth adapter is enabled
        if( RobotProvider.getDefaultProvider().isAdapterEnabled() ) {
        	RobotProvider.getDefaultProvider().findRobots();
        }
        else {
			Toast.makeText(ScriptRunActivity.this, "Bluetooth not enabled", Toast.LENGTH_SHORT).show();        	
        }
        // Obtain the list of paired Spheros
        ArrayList<Robot> robots = RobotProvider.getDefaultProvider().getRobots();
        // Connect to first available robot (only works if 1 or more robots are paired)
        if( robots.size() > 0 ) {
        	RobotProvider.getDefaultProvider().control(robots.get(0));
        	RobotProvider.getDefaultProvider().connectControlledRobots();
        }
        else {
			Toast.makeText(ScriptRunActivity.this, "No paired Spheros", Toast.LENGTH_SHORT).show();        	
        }
        
        // Set the Listener for when the robot has successfully connected
        RobotProvider.getDefaultProvider().setOnRobotConnectedListener( new OnRobotConnectedListener() {
			@Override
			public void onRobotConnected(Robot robot) {
				Toast.makeText(ScriptRunActivity.this, "Sphero Connected", Toast.LENGTH_SHORT).show();
				// Remember the connected robot reference
				ScriptRunActivity.robot = robot;
				ScriptRunActivity.robotControl = new RobotControl(robot);
				// Blink the robot's LED
				mHandler.postDelayed(new Runnable() {
                    @Override
                    public void run() {		
						parser = new Parser(me);
						parser.parseString(ScriptRunActivity.data);
						Toast.makeText(ScriptRunActivity.this, "Running script", Toast.LENGTH_SHORT).show();
                    	parser.run();
                        RobotProvider.getDefaultProvider().disconnectControlledRobots();
                        //finish();
                    }
                }, 1000);
			}
		});
        
        // Register to be notified when Sphero disconnects (out of range, battery dead, sleep, etc.)
        RobotProvider.getDefaultProvider().setOnRobotDisconnectedListener(new OnRobotDisconnectedListener() {
			@Override
			public void onRobotDisconnected(Robot robot) {
				Toast.makeText(ScriptRunActivity.this, "Sphero Disconnected", Toast.LENGTH_SHORT).show();
			}
		});
        
        RobotProvider.getDefaultProvider().setOnRobotConnectionFailedListener(new OnRobotConnectionFailedListener() {
			@Override
			public void onRobotConnectionFailed(Robot arg0) {
				Toast.makeText(ScriptRunActivity.this, "Failed to connect to Sphero", Toast.LENGTH_SHORT).show();
			}        	
        });
	}

	/**
	 * Set up the {@link android.app.ActionBar}, if the API is available.
	 */
	@TargetApi(Build.VERSION_CODES.HONEYCOMB)
	private void setupActionBar() {
		if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.HONEYCOMB) {
			getActionBar().setDisplayHomeAsUpEnabled(true);
		}
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.script_run, menu);
		return true;
	}

	@Override
	public boolean onOptionsItemSelected(MenuItem item) {
		switch (item.getItemId()) {
		case android.R.id.home:
			// This ID represents the Home or Up button. In the case of this
			// activity, the Up button is shown. Use NavUtils to allow users
			// to navigate up one level in the application structure. For
			// more details, see the Navigation pattern on Android Design:
			//
			// http://developer.android.com/design/patterns/navigation.html#up-vs-back
			//
			NavUtils.navigateUpFromSameTask(this);
			return true;
		}
		return super.onOptionsItemSelected(item);
	}
	
	@Override
    public void onBackPressed() {
        RobotProvider.getDefaultProvider().disconnectControlledRobots();        
        this.finish();
    }
}
