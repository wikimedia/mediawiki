/*
* vlc embed based on: http://people.videolan.org/~damienf/plugin-0.8.6.html
* javascript api: http://www.videolan.org/doc/play-howto/en/ch04.html
*  assume version > 0.8.5.1
*/
var vlcEmbed = {
	instanceOf : 'vlcEmbed',
	supports : { 
		'play_head':true,
		'pause':true,
		'stop':true,
		'fullscreen':true,
		'time_display':true,
		'volume_control':true,
		
		'playlist_driver':true, // if the object supports playlist functions
		'overlay':false
	},
	// init vars: 
	monitorTimerId : 0,
	prevState : 0,
	pejs_count:0, // post embed js count

	getEmbedHTML: function() {
		// give VLC 150ms to initialize before we start playback 
		// @@todo should be able to do this as an ready event
		this.pejs_count = 0;
		setTimeout( 'document.getElementById(\'' + this.id + '\').postEmbedJS()', 150 );
		   return this.getEmbedObj();
	},
	getEmbedObj:function() {
		var embed_code = '<object classid="clsid:9BE31822-FDAD-461B-AD51-BE1D1C159921" ' +
			'codebase="http://downloads.videolan.org/pub/videolan/vlc/latest/win32/axvlc.cab#Version=0,8,6,0" ' +
			'id="' + this.pid + '" events="True" height="' + this.height + '" width="' + this.width + '"' +
			'>' +
				'<param name="MRL" value="">' +
				'<param name="ShowDisplay" value="True">' +
				'<param name="AutoLoop" value="False">' +
				'<param name="AutoPlay" value="False">' +
				'<param name="Volume" value="50">' +
				'<param name="StartTime" value="0">' +
				'<embed pluginspage="http://www.videolan.org" type="application/x-vlc-plugin" ' +
					'progid="VideoLAN.VLCPlugin.2" name="' + this.pid + '" ' +
					'height="' + this.height + '" width="' + this.width + '" ' +
					// set the style too 'just to be sure'
					'style="width:' + this.width + 'px;height:' + this.height + 'px;" ' +
				'>' +
			'</object>';
		js_log( 'embed with: ' + embed_code );
		return embed_code;
	},
	
	/*
	* some java script to start vlc playback after the embed:
	*/
	postEmbedJS: function() {
		// load a pointer to the vlc into the object (this.vlc)
		this.getVLC();
		if ( this.vlc.log ) {
			// manipulate the dom object to make sure vlc has the correct size: 
			this.vlc.style.width = this.width;
			this.vlc.style.height = this.height;
			this.vlc.playlist.items.clear();
			var src = mw.absoluteUrl( this.getSrc() ) ;
			// @@todo if client supports seeking no need to send seek_offset to URI
			js_log( 'vlc play::' + src );
			var itemId = this.vlc.playlist.add( src );
			if ( itemId != -1 ) {
				// play
				this.vlc.playlist.playItem( itemId );
			} else {
				js_log( "error:cannot play at the moment !" );
			}
			// if controls enabled start up javascript interface and monitor:
			if ( this.controls ) {
				// activate the slider: scriptaculus based)
				// this.activateSlider();  
				// start doing status updates every 1/10th of a second							   
			}
			setTimeout( '$j(\'#' + this.id + '\').get(0).monitor()', 100 );
		} else {
			js_log( 'postEmbedJS:vlc not ready' );
			this.pejs_count++;
			if ( this.pejs_count < 10 ) {
				setTimeout( 'document.getElementById(\'' + this.id + '\').postEmbedJS()', 100 );
			} else {
				js_log( 'vlc never ready' );
			}
		}
	},
	// disable local seeking (while we don't know what we have avaliable)
	doSeek : function( perc ) {
		this.getVLC();
		if ( this.supportsURLTimeEncoding() ) {
			this.parent_doSeek( perc );
		} else if ( this.vlc ) {
			this.seeking = true;
			js_log( "do vlc http seek to: " + perc )
			if ( ( this.vlc.input.state == 3 ) && ( this.vlc.input.position != perc ) )
			{
				this.vlc.input.position = perc;
				this.setStatus( 'seeking...' );
			}
		} else {
			this.doPlayThenSeek( perc );
		}
		this.parent_monitor();
	},
	doPlayThenSeek:function( perc ) {
		js_log( 'doPlayThenSeekHack' );
		var _this = this;
		this.play();
		var rfsCount = 0;
		var readyForSeek = function() {
			_this.getVLC();
			var newState = _this.vlc.input.state;
			// if playing we are ready to do the 
			if ( newState == 3 ) {
				_this.doSeek( perc );
			} else {
				// try to get player for 10 seconds: 
				if ( rfsCount < 200 ) {
					setTimeout( readyForSeek, 50 );
					rfsCount++;
				} else {
					js_log( 'error:doPlayThenSeek failed' );
				}
			}
		}
		readyForSeek();
	},
	playMovieAt: function ( order ) {
		// @@todo add clips to playlist after (order) and then play
		this.play();
	},
	/* 
	* updates the status time
	*/
	monitor: function() {
		this.getVLC();
		if ( !this.vlc )
			return ;
		if ( this.vlc.log ) {
			// js_log( 'state:' + this.vlc.input.state);
			// js_log('time: ' + this.vlc.input.time);
			// js_log('pos: ' + this.vlc.input.position);
			if ( this.vlc.log.messages.count > 0 ) {
				// there is one or more messages in the log
				var iter = this.vlc.log.messages.iterator();
				while ( iter.hasNext ) {
					var msg = iter.next();
				   var msgtype = msg.type.toString();
				   if ( ( msg.severity == 1 ) && ( msgtype == "input" ) )
				   {
						   js_log( msg.message );
				   }
				}
				// clear the log once finished to avoid clogging
				this.vlc.log.messages.clear();
			}
			var newState = this.vlc.input.state;
			if ( this.prevState != newState ) {
				   if ( newState == 0 )
				{
					// current media has stopped 
					this.onStop();
				}
				else if ( newState == 1 )
				{
					// current media is opening/connecting
					this.onOpen();
				}
				else if ( newState == 2 )
				{
					// current media is buffering data
					this.onBuffer();
				}
				else if ( newState == 3 )
				{
				   // current media is now playing
				   this.onPlay();
				}
				   else if ( this.vlc.input.state == 4 )
				{
					// current media is now paused
					this.onPause();
				}
				this.prevState = newState;
			} else if ( newState == 3 ) {
				// current media is playing
				this.onPlaying();
			}
		}
		// update the status and check timmer via universal parent monitor
		this.parent_monitor();
	},
	/* events */
	onOpen: function() {
		this.setStatus( "Opening..." );
	},
	onBuffer: function() {
		this.setStatus( "Buffering..." );
	},
	onPlay: function() {
		this.onPlaying();
	},
	liveFeedRoll: 0,
	onPlaying: function() {
		this.seeking = false;
		// for now trust the duration from url over vlc input.length
		if ( !this.getDuration() && this.vlc.input.length > 0 )
		{
			// js_log('setting duration to ' + this.vlc.input.length /1000);			
			this.duration = this.vlc.input.length / 1000;
		}
		this.currentTime = this.vlc.input.time / 1000;
	},
	onPause: function() {
		this.parent_pause(); // update the inteface if paused via native control
	},
	onStop: function() {
		js_log( 'vlc:onStop:' );
		if ( !this.seeking )
			this.onClipDone();
	},
	/* js hooks/controls */
	play : function() {
		js_log( 'f:vlcPlay' );
			this.getVLC();
			// call the parent
		this.parent_play();
		if ( this.vlc ) {
			// plugin is already being present send play call: 
			// clear the message log and enable error logging
			if ( this.vlc.log ) {
				this.vlc.log.messages.clear();
			}
			if ( this.vlc.playlist )
				this.vlc.playlist.play();
				
			this.monitor();
			this.paused = false;
		}
	},
	stop : function() {
		if ( this.vlc ) {
			if ( typeof this.vlc != 'undefined' ) {
				if ( typeof this.vlc.playlist != 'undefined' ) {
					// dont' stop (issues all the plugin-stop actions) 
					// this.vlc.playlist.stop();
					if ( this.monitorTimerId != 0 )
					{
						clearInterval( this.monitorTimerId );
						this.monitorTimerId = 0;
					}
				}
			}
		}
		// this.onStop();
		// do parent stop
		this.parent_stop();
	},
	pause : function() {
		this.parent_pause(); // update the interface if paused via native control
		if ( this.vlc ) {
			this.vlc.playlist.togglePause();
		}
	},
	toggleMute:function() {
		this.parent_toggleMute();
		this.getVLC();
		if ( this.vlc )
			this.vlc.audio.toggleMute();
	},
	// @@ Support UpDateVolumen 
	updateVolumen:function( perc ) {
		this.getVLC();
		if ( this.vlc )
			this.vlc.audio.volume = perc * 100;
	},
	// @@ Get Volumen 
	getVolumen:function() {
		this.getVLC();
		if ( this.vlc )
		return this.vlc.audio.volume / 100;
	},
	fullscreen : function() {
		if ( this.vlc ) {
			if ( this.vlc.video )
				this.vlc.video.toggleFullscreen();
		}
	},	
	// get the embed vlc object 
	getVLC : function() {
		this.vlc = this.getPluginEmbed();
	}
};
