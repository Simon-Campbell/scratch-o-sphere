package com.comp241.scratch_o_sphere;

import java.io.IOException;
import java.util.ArrayList;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import com.comp241.scratch_o_sphere.parser.Parser;

import orbotix.robot.base.Robot;
import orbotix.view.connection.SpheroConnectionView;
import orbotix.view.connection.SpheroConnectionView.OnRobotConnectionEventListener;
import android.os.Bundle;
import android.os.Handler;
import android.app.Activity;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.Toast;
import android.support.v4.app.NavUtils;
import android.annotation.TargetApi;
import android.os.Build;

public class ScriptRunActivity extends Activity {

    public static SpheroController spheroController;
    public static String data;

    private SpheroConnectionView mSpheroConnectionView;
    private Handler mHandler = new Handler();
    
    private String token;
    private String scriptID;
    
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_script_run);
        
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
		int id;
		String name = null;
		try {
			obj = scripts.getJSONObject(0);			
			id = obj.getInt("ID");
			name = obj.getString("NAME");
			ScriptRunActivity.data = obj.getString("DATA");
		} 
		catch (JSONException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}

        mSpheroConnectionView = (SpheroConnectionView)findViewById(R.id.sphero_connection_view);
        
        // Set the connection event listener 
        mSpheroConnectionView.setOnRobotConnectionEventListener(new OnRobotConnectionEventListener() {
        	
        	// If the user clicked a Sphero and it failed to connect, this event will be fired
    		@Override
    		public void onRobotConnectionFailed(Robot robot) {
    			Toast.makeText(ScriptRunActivity.this, "Connection to Sphero failed", Toast.LENGTH_LONG).show();
    		}
    		
    		// If there are no Spheros paired to this device, this event will be fired
    		@Override
    		public void onNonePaired() {
    			Toast.makeText(ScriptRunActivity.this, "No Spheros found", Toast.LENGTH_LONG).show();
    		}
    		
    		// The user clicked a Sphero and it successfully paired.
    		@Override
    		public void onRobotConnected(final Robot robot) {
    			ScriptRunActivity.spheroController = new SpheroController(robot);
    			
    			Toast.makeText(ScriptRunActivity.this, "Found and connected to Sphero", Toast.LENGTH_LONG).show();

    			// Skip this next step if you want the user to be able to connect multiple Spheros
    			mSpheroConnectionView.setVisibility(View.GONE);
    		
    			// Calling any commands right after the robot connects, will not work!!!
    			// You need to wait a second for the robot to initialize
    			mHandler.postDelayed(new Runnable() {
                    @Override
                    public void run() {		
						Parser parser = new Parser(robot);
						parser.parseString(ScriptRunActivity.data);
                    	parser.run();
                    }
                }, 1000);
    		}
    		
    		@Override
    		public void onBluetoothNotEnabled() {
    			// See UISample Sample on how to show BT settings screen, for now just notify user
    			Toast.makeText(ScriptRunActivity.this, "Bluetooth Not Enabled", Toast.LENGTH_LONG).show();
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

}
