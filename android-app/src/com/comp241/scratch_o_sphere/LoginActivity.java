package com.comp241.scratch_o_sphere;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStreamWriter;
import java.net.HttpURLConnection;
import java.net.URL;
import java.net.URLConnection;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.params.BasicHttpParams;
import org.json.JSONException;
import org.json.JSONObject;

import android.os.Bundle;
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
		
		mUsername = (EditText)findViewById(R.id.username);
		mPassword = (EditText)findViewById(R.id.password);
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.login, menu);
		return true;
	}	
	
	public void login(View view) throws ClientProtocolException, IOException, JSONException
	{
		String username = "admin"; //mUsername.getText().toString();
		String password = "testpw"; //mPassword.getText().toString();
        
        JSONObject login = JSONWebRequest.getJsonObject("http://54.252.102.19/api/login/" + username + "/" + password + "/");
		
		boolean status = login.getBoolean("status");
		
		if(!status) {
			Toast.makeText(this, "Failed to login", Toast.LENGTH_LONG).show();
			return;
		}		
		
		Intent intent = new Intent(this, ScriptsActivity.class);
		intent.putExtra("token", login.getString("token"));
		startActivity(intent);
	}
}
