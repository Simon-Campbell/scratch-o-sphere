package com.comp241.scratch_o_sphere;

import java.io.IOException;

import org.apache.http.client.ClientProtocolException;
import org.json.JSONException;

import android.os.Bundle;
import android.os.StrictMode;
import android.app.Activity;
import android.content.Intent;
import android.view.Menu;
import android.view.View;
import android.widget.EditText;
import android.widget.Toast;

public class LoginActivity extends Activity {

	EditText mUsername;
	EditText mPassword;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_login);
		
		if (android.os.Build.VERSION.SDK_INT > 9) {
			StrictMode.ThreadPolicy policy = new StrictMode.ThreadPolicy.Builder().permitAll().build();
			StrictMode.setThreadPolicy(policy);
		}
		
		mUsername = (EditText)findViewById(R.id.username);
		mPassword = (EditText)findViewById(R.id.password);
		/*
		try {
			this.login(this.getCurrentFocus());
		} catch (ClientProtocolException e) {
			e.printStackTrace();
		} catch (IOException e) {
			e.printStackTrace();
		} catch (JSONException e) {
			e.printStackTrace();
		}*/
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.login, menu);
		return true;
	}	
	
	public void login(View view) throws ClientProtocolException, IOException, JSONException
	{
		String username = mUsername.getText().toString();
		String password = mPassword.getText().toString();
        String url = "http://54.252.102.19/api/login/" + username + "/" + password + "/";
        
		new RetreiveJSON(this, url, new JSONThread() {			
			public void run() {
				try {
					boolean status = this.obj.getBoolean("status");
					
					if(!status) {
						Toast.makeText(this.context, "Failed to login", Toast.LENGTH_LONG).show();
						return;
					}		
					
					Intent intent = new Intent(this.context, ScriptsActivity.class);
					intent.putExtra("token", this.obj.getString("token"));
					startActivity(intent);
				} catch(Exception e) {
					Toast.makeText(this.context, "Failed to login", Toast.LENGTH_LONG).show();
					e.printStackTrace();
				}
			}
		});
	}
}
