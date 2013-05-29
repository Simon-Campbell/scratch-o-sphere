package com.comp241.scratch_o_sphere;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.MalformedURLException;
import java.net.URL;
import java.net.URLConnection;

import org.json.JSONException;
import org.json.JSONObject;

public class JSONWebRequest {
	public final static JSONObject getJsonObject(String url) throws IOException, JSONException {
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
