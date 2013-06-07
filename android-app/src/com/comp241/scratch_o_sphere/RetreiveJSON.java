package com.comp241.scratch_o_sphere;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.URL;
import java.net.URLConnection;

import org.json.JSONException;
import org.json.JSONObject;

import android.content.Context;
import android.os.AsyncTask;

public class RetreiveJSON {

	public RetreiveJSON(final Context context, final String url, final JSONThread jsonThread) {
		Thread thread = new Thread(new Runnable(){
		    @Override
		    public void run() {
	    		try {
					JSONObject obj = JSONWebRequest(url);
					jsonThread.obj = obj;
					jsonThread.context = context;
					Thread finished = new Thread(jsonThread);
					finished.start();
				} catch (IOException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				} catch (JSONException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
		    }
		});
		thread.start();
	}

	protected JSONObject JSONWebRequest(String url) throws IOException, JSONException {
		URL ip = new URL(url);
        URLConnection yc = ip.openConnection();
        BufferedReader in = new BufferedReader(
                                new InputStreamReader(
                                yc.getInputStream()));
        StringBuilder sb = new StringBuilder();
        String line;		
        while ((line = in.readLine()) != null)
        {
            sb.append(line + "\n");
        }
        in.close();
        
        // Response from server after login process will be stored in response variable.                
        String result = sb.toString();		
		JSONObject jObject = new JSONObject(result);
		return jObject;
	}
}