package com.comp241.scratch_o_sphere;

import orbotix.robot.base.Robot;
import orbotix.view.connection.SpheroConnectionView;
import orbotix.view.connection.SpheroConnectionView.OnRobotConnectionEventListener;
import android.os.Bundle;
import android.os.Handler;
import android.app.Activity;
import android.view.Menu;
import android.view.View;
import android.widget.Toast;

public class MainActivity extends Activity {

    public SpheroController spheroController;

    private SpheroConnectionView mSpheroConnectionView;
    private Handler mHandler = new Handler();
	
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        
        mSpheroConnectionView = (SpheroConnectionView)findViewById(R.id.sphero_connection_view);
        
        // Set the connection event listener 
        mSpheroConnectionView.setOnRobotConnectionEventListener(new OnRobotConnectionEventListener() {
        	
        	// If the user clicked a Sphero and it failed to connect, this event will be fired
    		@Override
    		public void onRobotConnectionFailed(Robot robot) {}
    		
    		// If there are no Spheros paired to this device, this event will be fired
    		@Override
    		public void onNonePaired() {}
    		
    		// The user clicked a Sphero and it successfully paired.
    		@Override
    		public void onRobotConnected(Robot robot) {
    			spheroController = new SpheroController(robot);
    			
    			Toast.makeText(MainActivity.this, "Found and connect to Sphero", Toast.LENGTH_LONG).show();

    			// Skip this next step if you want the user to be able to connect multiple Spheros
    			mSpheroConnectionView.setVisibility(View.GONE);
    		
    			// Calling any commands right after the robot connects, will not work!!!
    			// You need to wait a second for the robot to initialize
    			mHandler.postDelayed(new Runnable() {
                    @Override
                    public void run() {
                    	spheroController.setBackLED(1.0f);
                    }
                }, 1000);
    		}
    		
    		@Override
    		public void onBluetoothNotEnabled() {
    			// See UISample Sample on how to show BT settings screen, for now just notify user
    			Toast.makeText(MainActivity.this, "Bluetooth Not Enabled", Toast.LENGTH_LONG).show();
    		}
    	});
    }
    
    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.main, menu);
        return true;
    }
}
