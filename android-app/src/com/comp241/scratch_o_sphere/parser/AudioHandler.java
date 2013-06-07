package com.comp241.scratch_o_sphere.parser;

import com.comp241.scratch_o_sphere.R;

import android.media.MediaPlayer;

public class AudioHandler {

	public AudioHandler(Parser p) {		
		p.functionHive.put("play", new ICommandHandler() {
			@Override
			public long HandleCommand(Parser p, String[] c) {
				MediaPlayer mp = null;
				if(c[1].equals("bang")) {
					mp = MediaPlayer.create(p.main.getApplicationContext(), R.raw.bang);
				}
				else if(c[1].equals("beep")) {
					mp = MediaPlayer.create(p.main.getApplicationContext(), R.raw.beep);
				}
				else if(c[1].equals("clap")) {
					mp = MediaPlayer.create(p.main.getApplicationContext(), R.raw.clap);
				}
				else if(c[1].equals("boing")) {
					mp = MediaPlayer.create(p.main.getApplicationContext(), R.raw.boing);
				}
				if(mp != null) {
					mp.start();
				}
				return 0;
			}			
		});
	}
}
