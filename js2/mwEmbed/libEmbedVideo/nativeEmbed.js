// native embed library:
var nativeEmbed = {
	instanceOf:'nativeEmbed',
	canPlayThrough:false,
	grab_try_count:0,
	onlyLoadFlag:false,
	onLoadedCallback : new Array(),
	urlAppend:'',
	prevCurrentTime: -1,
	supports: {
		'play_head':true,
		'pause':true,
		'fullscreen':false,
		'time_display':true,
		'volume_control':true,
		
		'overlays':true,
		'playlist_swap_loader':true // if the object supports playlist functions		
	},
	getEmbedHTML : function () {
		var embed_code =  this.getEmbedObj();
		js_log( "embed code: " + embed_code )
		setTimeout( '$j(\'#' + this.id + '\').get(0).postEmbedJS()', 150 );
		return this.wrapEmebedContainer( embed_code );
	},
	getEmbedObj:function() {
		// we want to let mv_embed handle the controls so notice the absence of control attribute
		// controls=false results in controls being displayed: 
		// http://lists.whatwg.org/pipermail/whatwg-whatwg.org/2008-August/016159.html		
		js_log( "native play url:" + this.getSrc() + ' start_offset: ' + this.start_ntp + ' end: ' + this.end_ntp );
		var eb = '<video ' +
					'id="' + this.pid + '" ' +
					'style="width:' + this.width + 'px;height:' + this.height + 'px;" ' +
					'width="' + this.width + '" height="' + this.height + '" ' +
					   'src="' + this.getSrc() + '" ';
					   
		/*if(!this.onlyLoadFlag)
			eb+='autoplay="true" ';*/
			
		// continue with the other attr:						
		eb += 'oncanplaythrough="$j(\'#' + this.id + '\').get(0).oncanplaythrough();return false;" ' +
			  'onloadedmetadata="$j(\'#' + this.id + '\').get(0).onloadedmetadata();return false;" ' +
			  'loadedmetadata="$j(\'#' + this.id + '\').get(0).onloadedmetadata();return false;" ' +
			  'onprogress="$j(\'#' + this.id + '\').get(0).onprogress( event );return false;" ' +
			  'onended="$j(\'#' + this.id + '\').get(0).onended();return false;" ' +
			  'onseeking="$j(\'#' + this.id + '\').get(0).onseeking();" ' +
       		  'onseeked="$j(\'#' + this.id + '\').get(0).onseeked();" >' +
			'</video>';
		return eb;
	},
	// @@todo : loading progress	
	postEmbedJS:function() {
		var _this = this;
		js_log( "f:native:postEmbedJS:" );
		this.getVID();
		if ( typeof this.vid != 'undefined' ) {
			// always load the media:
			if ( this.onlyLoadFlag ) {
				this.vid.load();
			} else {
				// issue play request				
				this.vid.play();
			}
			setTimeout( '$j(\'#' + this.id + '\').get(0).monitor()', 100 );
		} else {
			js_log( 'could not grab vid obj trying again:' + typeof this.vid );
			this.grab_try_count++;
			if (	this.grab_count == 20 ) {
				js_log( ' could not get vid object after 20 tries re-run: getEmbedObj() ?' ) ;
			} else {
				setTimeout( function(){
					_this.postEmbedJS();
				}, 200 );
			}
		}
	},
	onseeking:function() {
		js_log( "onseeking" );
		this.seeking = true;
		this.setStatus( gM( 'mwe-seeking' ) );
	},
	onseeked: function() {
		js_log("onseeked");
		this.seeking = false;
	},
	doSeek:function( perc ) {
		js_log( 'native:seek:p: ' + perc + ' : '  + this.supportsURLTimeEncoding() + ' dur: ' + this.getDuration() + ' sts:' + this.seek_time_sec );
		// @@todo check if the clip is loaded here (if so we can do a local seek)
		if ( this.supportsURLTimeEncoding() ) {
			// Make sure we could not do a local seek instead:
			if ( perc < this.bufferedPercent && this.vid.duration && !this.didSeekJump ) {
				js_log( "do local seek " + perc + ' is already buffered < ' + this.bufferedPercent );
				this.doNativeSeek( perc );
			} else {
				// We support URLTimeEncoding call parent seek: 
				this.parent_doSeek( perc );
			}
		} else if ( this.vid && this.vid.duration ) {
			// (could also check bufferedPercent > perc seek (and issue oggz_chop request or not) 
			this.doNativeSeek( perc );
		} else {
			// try to do a play then seek: 
			this.doPlayThenSeek( perc )
		}
	},
	doNativeSeek:function( perc ) {
		js_log( 'native::doNativeSeek::' + perc );
		this.seek_time_sec = 0;
		this.vid.currentTime = perc * this.duration;
		this.monitor();
	},
	doPlayThenSeek:function( perc ) {
		js_log( 'native::doPlayThenSeek::' );
		var _this = this;
		this.play();
		var rfsCount = 0;
		var readyForSeek = function() {
			_this.getVID();
			if ( _this.vid )
				js_log( 'readyForSeek looking::' + _this.vid.duration );
			// if we have duration then we are ready to do the seek
			if ( _this.vid && _this.vid.duration ) {
				_this.doNativeSeek( perc );
			} else {
				// Try to get player for 40 seconds: 
				// (it would be nice if the onmetadata type callbacks where fired consistently)
				if ( rfsCount < 800 ) {
					setTimeout( readyForSeek, 50 );
					rfsCount++;
				} else {
					js_log( 'error:doPlayThenSeek failed' );
				}
			}
		}
		readyForSeek();
	},
	setCurrentTime: function( pos, callback ) {	
		var _this = this;
		js_log( 'native:setCurrentTime::: ' + pos + ' :  dur: ' + _this.getDuration() );
		this.getVID();
		if ( !this.vid ) {
			this.load( function() {				
				_this.doSeekedCb( pos, callback );		
			} );
		} else {
			_this.doSeekedCb( pos, callback );		
		}
	},
	doSeekedCb : function( pos, cb ){
		var _this = this;			
		this.getVID();		
		var once = function( event ) {
			js_log("did seek cb");
			cb();
			_this.vid.removeEventListener( 'seeked', once, false );
		};		
		// Assume we will get to add the Listener before the seek is done
		_this.vid.currentTime = pos;
		_this.vid.addEventListener( 'seeked', once, false );						
	},
	monitor : function() {
		this.getVID(); // make sure we have .vid obj
		if ( !this.vid ) {
			js_log( 'could not find video embed: ' + this.id + ' stop monitor' );
			this.stopMonitor();
			return false;
		}
		// don't update status if we are not the current clip 
		// (playlist leakage?) .. should move to playlist overwrite of monitor? 
		if ( this.pc ) {
			if ( this.pc.pp.cur_clip.id != this.pc.id )
				return true;
		}
		
		// Do a seek check (on seeked does not seem fire consistently) 	
		if ( this.prevCurrentTime != -1 && this.prevCurrentTime != 0
			&& this.prevCurrentTime < this.currentTime && this.seeking )
			this.seeking = false;
								
		this.prevCurrentTime =	this.currentTime;
		
		// update currentTime				
		this.currentTime = this.vid.currentTime;
		this.addPresTimeOffset();
				
		// js_log('currentTime:' + this.currentTime);
		// js_log('this.currentTime: ' + this.currentTime );
		// once currentTime is updated call parent_monitor
		this.parent_monitor();
	},
	getSrc:function() {
		var src = this.parent_getSrc();
		if (  this.urlAppend != '' )
			return src + ( ( src.indexOf( '?' ) == -1 ) ? '?':'&' ) + this.urlAppend;
		return src;
	},
	/*
	 * native callbacks for the video tag: 
	 */
	oncanplaythrough : function() {
		js_log('f:oncanplaythrough');
		this.getVID();
		if ( ! this.paused )
			this.vid.play();		
	},
	onloadedmetadata: function() {
		this.getVID();
		js_log( 'f:onloadedmetadata metadata ready (update duration)' );
		// update duration if not set (for now trust the getDuration more than this.vid.duration		
		if ( this.getDuration() == 0  &&  ! isNaN( this.vid.duration ) ) {
			js_log( 'updaed duration via native video duration: ' + this.vid.duration )
			this.duration = this.vid.duration;
		}
		//fire "onLoaded" flags if set
		while( this.onLoadedCallback.length ){
			func = this.onLoadedCallback.pop()
			if( typeof func == 'function' )
				func();
		}
	},
	onprogress: function( e ) {
		this.bufferedPercent =   e.loaded / e.total;
		// js_log("onprogress:" +e.loaded + ' / ' +  (e.total) + ' = ' + this.bufferedPercent);
	},
	onended:function() {
		var _this = this
		this.getVID();
		js_log( 'native:onended:' + this.vid.currentTime + ' real dur:' +  this.getDuration() );
		// if we just started (under 1 second played) & duration is much longer.. don't run onClipDone just yet . (bug in firefox native sending onended event early) 
		if ( this.vid.currentTime  < 1 && this.getDuration() > 1 && this.grab_try_count < 5 ) {
			js_log( 'native on ended called with time:' + this.vid.currentTime + ' of total real dur: ' +  this.getDuration() + ' attempting to reload src...' );
			var doRetry = function() {
				_this.urlAppend = 'retry_src=' + _this.grab_try_count;
				_this.doEmbedHTML();
				_this.grab_try_count++;
			}
			setTimeout( doRetry, 100 );
		} else {
			js_log( 'native onClipDone done call' );
			this.onClipDone();
		}
	},
	pause : function() {
		this.getVID();
		this.parent_pause(); // update interface		
		if ( this.vid ) {
			this.vid.pause();
		}
		// stop updates: 
		this.stopMonitor();
	},
	play:function() {
		this.getVID();
		this.parent_play(); // update interface
		if ( this.vid ) {
			this.vid.play();
			// re-start the monitor: 
			this.monitor();
		}
	},
	toggleMute:function() {
		this.parent_toggleMute();
		this.getVID();
		if ( this.vid )
			this.vid.muted = this.muted;
	},
	updateVolumen:function( perc ) {
		this.getVID();
		if ( this.vid )
			this.vid.volume = perc;
	},
    getVolumen:function() {
		this.getVID();
		if ( this.vid )
			return this.vid.volume;
	},
	getNativeDuration:function() {
		if ( this.vid )
			return this.vid.duration;
	},
	load:function( callback ) {
		this.getVID();		
		if ( !this.vid ) {
			// No vid loaded
			js_log( 'native::load() ... doEmbed' );
			this.onlyLoadFlag = true;
			this.doEmbedHTML();
			this.onLoadedCallback.push( callback );
		} else {
			// Should not happen offten
			this.vid.load();
			if( callback)
				callback();
		}
	},
	// get the embed vlc object 
	getVID : function () {
		this.vid = $j( '#' + this.pid ).get( 0 );
	}
};
