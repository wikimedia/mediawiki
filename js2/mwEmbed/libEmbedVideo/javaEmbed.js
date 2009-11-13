window.cortadoDomainLocations = {
	'upload.wikimedia.org'  : 'http://upload.wikimedia.org/jars/cortado.jar',
	'tinyvid.tv'			: 'http://tinyvid.tv/static/cortado.jar',
	'media.tinyvid.tv'		: 'http://media.tinyvid.tv/cortado.jar'
}

var javaEmbed = {
	instanceOf:'javaEmbed',
	iframe_src:'',
	logged_domain_error:false,
	supports: {
		'play_head':true,
		'pause':true,
		'stop':true,
		'fullscreen':false,
		'time_display':true,
		'volume_control':false
	},
	getEmbedHTML : function () {
		// big delay on embed html cuz its just for status updates and ie6 is crazy. 
		if ( this.controls )
			setTimeout( 'document.getElementById(\'' + this.id + '\').postEmbedJS()', 500 );
		// set a default duration of 30 seconds: cortao should detect duration.
		return this.wrapEmebedContainer( this.getEmbedObj() );
	},
	getEmbedObj:function() {
		js_log( "java play url:" + this.getURI( this.seek_time_sec ) );
		// get the duration
		this.getDuration();
		// if still unset set to an arbitrary time 60 seconds: 
		if ( !this.duration )this.duration = 60;
		// @@todo we should have src property in our base embed object
		var mediaSrc = this.getSrc();
		
		if ( mediaSrc.indexOf( '://' ) != - 1 & mw.parseUri( document.URL ).host !=  mw.parseUri( mediaSrc ).host ) {
			if ( window.cortadoDomainLocations[mw.parseUri( mediaSrc ).host] ) {
				applet_loc =  window.cortadoDomainLocations[mw.parseUri( mediaSrc ).host];
			} else {
				applet_loc  = 'http://theora.org/cortado.jar';
			}
		} else {
			// should be identical to cortado.jar
			applet_loc = mv_embed_path + 'binPlayers/cortado/cortado-ovt-stripped-0.5.0.jar';
		}
			// load directly in the page..
			// (media must be on the same server or applet must be signed)
			var appplet_code = '' +
			'<applet id="' + this.pid + '" code="com.fluendo.player.Cortado.class" archive="' + applet_loc + '" width="' + this.width + '" height="' + this.height + '">	' + "\n" +
				'<param name="url" value="' + mediaSrc + '" /> ' + "\n" +
				'<param name="local" value="false"/>' + "\n" +
				'<param name="keepaspect" value="true" />' + "\n" +
				'<param name="video" value="true" />' + "\n" +
				'<param name="showStatus" value="hide" />' + "\n" +
				'<param name="audio" value="true" />' + "\n" +
				'<param name="seekable" value="true" />' + "\n" +
				'<param name="duration" value="' + this.duration + '" />' + "\n" +
				'<param name="bufferSize" value="4096" />' + "\n" +
			'</applet>';
									
			// Wrap it in an iframe to avoid hanging the event thread in FF 2/3 and similar
			// Doesn't work in MSIE or Safari/Mac or Opera 9.5
			if ( embedTypes.mozilla ) {
				var iframe = document.createElement( 'iframe' );
				iframe.setAttribute( 'width', params.width );
				iframe.setAttribute( 'height', playerHeight );
				iframe.setAttribute( 'scrolling', 'no' );
				iframe.setAttribute( 'frameborder', 0 );
				iframe.setAttribute( 'marginWidth', 0 );
				iframe.setAttribute( 'marginHeight', 0 );
				iframe.setAttribute( 'id', 'cframe_' + this.id )
				elt.appendChild( iframe );
				var newDoc = iframe.contentDocument;
				newDoc.open();
				newDoc.write( '<html><body>' + appplet_code + '</body></html>' );
				newDoc.close(); // spurious error in some versions of FF, no workaround known
			} else {
				return appplet_code;
			}
	},
	postEmbedJS:function() {
		// reset logged domain error flag:
		this.logged_domain_error = false;
		// start monitor: 
		this.monitor();
	},
	monitor:function() {
		this.getJCE();
		if ( this.isPlaying() ) {
			if ( this.jce && this.jce.getPlayPosition ) {
				try {
				   // java reads ogg media time.. so no need to add the start or seek offset:
				   // js_log(' ct: ' + this.jce.getPlayPosition() + ' ' +  this.supportsURLTimeEncoding());												   
				   this.currentTime = this.jce.getPlayPosition();
				   if ( this.jce.getPlayPosition() < 0 ) {
				   		js_log( 'pp:' + this.jce.getPlayPosition() );
						// probably reached clip end					
						this.onClipDone();
				   }
				} catch ( e ) {
				   js_log( 'could not get time from jPlayer: ' + e );
				}
			}
		}
		// once currentTime is updated call parent_monitor 
		this.parent_monitor();
	},
   /* 
	* (local cortado seek does not seem to work very well)  
	*/
	doSeek:function( perc ) {
		js_log( 'java:seek:p: ' + perc + ' : '  + this.supportsURLTimeEncoding() + ' dur: ' + this.getDuration() + ' sts:' + this.seek_time_sec );
		this.getJCE();
		
		if ( this.supportsURLTimeEncoding() ) {
			this.parent_doSeek( perc );
			// this.seek_time_sec = npt2seconds( this.start_ntp ) + parseFloat( perc * this.getDuration() );						
		   // this.jce.setParam('url', this.getURI( this.seek_time_sec ))
			// this.jce.restart();
		} else if ( this.jce ) {
		   // do a (genneraly broken) local seek:   
		   js_log( "cortado javascript seems to always fail ... but here we go... doSeek(" + ( perc * parseFloat( this.getDuration() ) ) );
		   this.jce.doSeek( perc * parseFloat( this.getDuration() )  );
		} else {
			this.doPlayThenSeek( perc );
		}
	},
	doPlayThenSeek:function( perc ) {
		js_log( 'doPlayThenSeek Hack' );
		var _this = this;
		this.play();
		var rfsCount = 0;
		var readyForSeek = function() {
			_this.getJCE();
			// if we have .jre ~in theory~ we can seek (but probably not) 
			if ( _this.jce ) {
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
	// get java cortado embed object
	getJCE:function() {
		if ( embedTypes.mozilla ) {
			this.jce = window.frames['cframe_' + this.id ].document.getElementById( this.pid );
		} else {
			this.jce = $j( '#' + this.pid ).get( 0 );
		}
	},
	doThumbnailHTML:function() {
		// empty out player html (jquery with java applets does mix) :			
		var pelm = document.getElementById( 'dc_' + this.id );
		if ( pelm ) {
			pelm.innerHTML = '';
		}
		this.parent_doThumbnailHTML();
	},
	play:function() {
		this.getJCE();
		this.parent_play();
		if ( this.jce )
			this.jce.doPlay();
	},
	pause:function() {
		this.getJCE();
		this.parent_pause();
		if ( this.jce )
			this.jce.doPause();
	}
};
