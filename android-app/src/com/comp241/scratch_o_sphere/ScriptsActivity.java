package com.comp241.scratch_o_sphere;

import java.io.IOException;
import java.util.ArrayList;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import com.comp241.scratch_o_sphere.parser.Parser;

import android.annotation.TargetApi;
import android.app.Activity;
import android.content.Intent;
import android.os.Build;
import android.os.Bundle;
import android.os.StrictMode;
import android.support.v4.app.NavUtils;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.ArrayAdapter;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

public class ScriptsActivity extends Activity {

	String token;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_scripts);
		
		if (android.os.Build.VERSION.SDK_INT > 9) {
			StrictMode.ThreadPolicy policy = new StrictMode.ThreadPolicy.Builder().permitAll().build();
			StrictMode.setThreadPolicy(policy);
		}
		
		token = this.getIntent().getStringExtra("token");
		
		String url = "http://54.252.102.19/api/" + token + "/getscript";
		
		JSONObject scriptRequest = null;
		JSONArray scripts = new JSONArray();
		
		try {
			scriptRequest = JSONWebRequest.getJsonObject(url);
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
		
		ArrayList<String> lst = new ArrayList<String>();
		
		for(int i = 0; i< scripts.length(); i++) {
			JSONObject obj;
			int id;
			String name, data;
			try {
				obj = scripts.getJSONObject(i);			
				id = obj.getInt("ID");
				name = obj.getString("NAME");
				data = obj.getString("DATA");
				lst.add(id + ": " + name);
			} catch (JSONException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
			
		}		

		ArrayAdapter<String> adapter = new ArrayAdapter<String>(this, android.R.layout.simple_list_item_1, lst);
		ListView listView  = (ListView) findViewById(R.id.scripts);
		listView.setAdapter(adapter);
		
		listView.setOnItemClickListener(new OnItemClickListener() {
			@Override
			public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
				TextView tView = (TextView) view;
				String text = (String) tView.getText();
				
				Toast.makeText(parent.getContext(), "Selected script: " + text, Toast.LENGTH_SHORT).show();
				
				String scriptID = text.split(":")[0];
				
				Intent intent = new Intent(parent.getContext(), ScriptRunActivity.class);
				intent.putExtra("token", token);
				intent.putExtra("scriptID", scriptID);
				parent.getContext().startActivity(intent);
			}			
		});
		
		// Show the Up button in the action bar.
		setupActionBar();
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
		getMenuInflater().inflate(R.menu.scripts, menu);
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
