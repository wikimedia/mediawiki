/* 
 * the mvPlayList object code 
 * only included if playlist object found
 * 
 * part of mwEmbed media projects see:  
 * http://www.mediawiki.org/wiki/Media_Projects_Overview
 * 
 * @author: Michael Dale  mdale@wikimedia.org
 * @license GPL2
 */
var mv_default_playlist_attributes = {
	// playlist attributes :
	"id":null,
	"title":null,
	"width":400,
	"height":300,
	"desc":'',
	"controls":true,
	// playlist user controlled features
	"linkback":null,
	"src":null,
	"embed_link":true,
	
	// enable sequencer? (only display top frame no navigation or accompanying text
	"sequencer":false
}
// The call back rate for animations and internal timers in ms: 33 is about 30 frames a second: 
var MV_ANIMATION_CB_RATE = 33;

// globals:
// 10 possible colors for clips: (can be in hexadecimal)
var mv_clip_colors = new Array( 'aqua', 'blue', 'fuchsia', 'green', 'lime', 'maroon', 'navy', 'olive', 'purple', 'red' );

// The base url for requesting stream metadata 
if ( typeof wgServer == 'undefined' ) {
	var defaultMetaDataProvider = 'http://metavid.org/overlay/archive_browser/export_cmml?stream_name=';
} else {
	var defaultMetaDataProvider = wgServer + wgScript + '?title=Special:MvExportStream&feed_format=roe&stream_name=';
}
/*
 * The playlist Object implements ~most~ of embedVideo but we don't inherit (other than to use the control builder)  
 * because pretty much every function has to be changed for the playlist context
 */
var mvPlayList = function( element ) {
	return this.init( element );
};
// set up the mvPlaylist object
mvPlayList.prototype = {
	instanceOf:'mvPlayList',
	pl_duration:null,
	update_tl_hook:null,
	clip_ready_count:0,
	cur_clip:null,
	start_clip:null,
	start_clip_src:null,
	disp_play_head:null,
	userSlide:false,
	loading:true,
	loading_external_data:true, // if we are loading external data (set to loading by default)
	//set initial state to "paused"
	paused:true,

	activeClipList:null,
	playlist_buffer_time: 20, // how many seconds of future clips we should buffer

	interface_url:null, // the interface url 
	tracks: { },
	default_track:null, // the default track to add clips to.
	// the layout for the playlist object
	pl_layout : {
		seq_title:.1,
		clip_desc:.63, // displays the clip description
		clip_aspect:1.33,  // 4/3 video aspect ratio
		seq:.25,				 // display clip thumbnails 
		seq_thumb:.25,	 // size for thumbnails (same as seq by default) 
		seq_nav:0,	// for a nav bar at the base (currently disabled)
		// some pl_layout info:
		title_bar_height:17,
		control_height:29
	},
	// embed object type support system; 
	supports: {
		'play_head':true,
		'pause':true,
		'fullscreen':false,
		'time_display':true,
		'volume_control':true,
		
		'overlays':true,
		'playlist_swap_loader':true // if the object supports playlist functions		
  	},
	init : function( element ) {
		js_log( 'mvPlayList:init:' );
		this.tracks = { };
		this.default_track = null;
		
		this.activeClipList = new activeClipList();
		// add default track & default track pointer: 
		this.tracks[0] = new trackObj( { 'inx':0 } );
		this.default_track = this.tracks[0];
		
		// get all the attributes:
		 for ( var attr in mv_default_playlist_attributes ) {
			if ( element.getAttribute( attr ) ) {
				this[attr] = element.getAttribute( attr );
				// js_log('attr:' + attr + ' val: ' + video_attributes[attr] +" "+'elm_val:' + element.getAttribute(attr) + "\n (set by elm)");  
			} else {
				this[attr] = mv_default_playlist_attributes[attr];
				// js_log('attr:' + attr + ' val: ' + video_attributes[attr] +" "+ 'elm_val:' + element.getAttribute(attr) + "\n (set by attr)");  
			}
		}
		// make sure height and width are int:
		this.width = parseInt( this.width );
		this.height = parseInt( this.height );
		
		// if style is set override width and height
		if ( element.style.width )this.width = parseInt( element.style.width.replace( 'px', '' ) );
		if ( element.style.height )this.height = parseInt( element.style.height.replace( 'px', '' ) );
				
		// if controls=false hide the title and the controls:
		if ( this.controls === false ) {
			this.pl_layout.control_height = 0;
			this.pl_layout.title_bar_height = 0;
		} else {
			// setup the controlBuilder object:
			this.ctrlBuilder = new ctrlBuilder( this );
		}
	},
	// the element has now been swapped into the dom: 
	on_dom_swap:function() {
		js_log( 'pl: dom swap' );
		// get and load the html:
		this.getHTML();
	},
	// run inheritEmbedObj on every clip (we have changed the playback method) 
	inheritEmbedObj:function() {
		$j.each( this.tracks, function( i, track ) {
			track.inheritEmbedObj();
		} );
	},
	doOptionsHTML:function() {
		// grab "options" use current clip:
		this.cur_clip.embed.doOptionsHTML();
	},
	// pulls up the video editor inline
	doEditor:function() {
		// black out the page: 
		// $j('body').append('<div id="ui-widget-overlay"/> <div id="modalbox" class="ui-widget ui-widget-content ui-corner-all modal_editor">' );
		$j( 'body' ).append( '<div class="ui-widget-overlay" style="width: 100%; height: 100%px; z-index: 10;"></div>' );
		$j( 'body' ).append( '<div id="sequencer_target" style="z-index:11;position:fixed;top:10px;left:10px;right:10px;bottom:10px;" ' +
				'class="ui-widget ui-widget-content ui-corner-all"></div>' );
					
		// @@todo clone the playlist (for faster startup)
		/*
		 * var this_plObj_Clone = $j('#'+this.id).get(0).cloneNode(true);
		 *	this_plObj_Clone.sequencer=true;
		 *	this_plObj_Clone.id= 'seq_plobj';
		 *	debugger;
		 */
		 
		// load sequencer: 
		$j( "#sequencer_target" ).sequencer( {
			"mv_pl_src" : this.src
		} );
					
	},
	showPlayerselect:function() {
		this.cur_clip.embed.showPlayerselect();
	},
	closeDisplayedHTML:function() {
		this.cur_clip.embed.closeDisplayedHTML();
	},
	showDownload:function() {
		this.cur_clip.embed.showDownload();
	},
	showShare:function() {
		var embed_code = '&lt;script type=&quot;text/javascript&quot; ' +
						'src=&quot;' + mv_embed_path + 'mv_embed.js&quot;&gt;&lt;/script&gt ' + "\n" +
						'&lt;playlist id=&quot;' + this.id + '&quot; ';
						if ( this.src ) {
							embed_code += 'src=&quot;' + this.src + '&quot; /&gt;';
						} else {
							embed_code += '&gt;' + "\n";
							embed_code += escape( this.data );
							embed_code += '&lt;playlist/&gt;';
						}
		this.cur_clip.embed.showShare( embed_code );
	},
	timedTextSources:function() {
		return false;
	},
	getPlaylist:function() {
		js_log( "f:getPlaylist: " + this.srcType );
		// @@todo lazy load plLib
		eval( 'var plObj = ' + this.srcType + 'Playlist;' );
		// import methods from the plObj to this
		for ( var method in plObj ) {
			// js parent preservation for local overwritten methods
			if ( this[method] )this['parent_' + method] = this[method];
			this[method] = plObj[method];
			js_log( 'inherit:' + method );
		}
			
		if ( typeof this.doParse != 'function' ) {
			js_log( 'error: method doParse not found in plObj' + this.srcType );
			return false;
		}
						 
		if ( typeof this.doParse == 'function' ) {
				 if ( this.doParse() ) {
					 this.doWhenParseDone();
				 } else {
					 js_log( "error: failed to parse playlist" );
					 return false;
					 // error or parse needs to do ajax requests	
				 }
		}
	},
	doNativeWarningCheck:function() {
		var clip =	 this.default_track.clips[0];
		if ( clip ) {
			return clip.embed.doNativeWarningCheck();
		}
	},
	doWhenParseDone:function() {
		js_log( 'f:doWhenParseDone' );
		// do additional init for clips: 
		var _this = this;
		var error = false;
		_this.clip_ready_count = 0;
		for ( var i in this.default_track.clips ) {
			var clip =	 this.default_track.clips[i];
			if ( clip.embed.load_error ) {
				var error = clip.embed.load_error;
				// break on any clip we can't playback:
				break;
			}
			if ( clip.embed.ready_to_play ) {
				_this.clip_ready_count++;
				continue;
			}
			// js_log('clip sources count: '+ clip.embed.media_element.sources.length);		
			clip.embed.on_dom_swap();
			if ( clip.embed.loading_external_data == false &&
				   clip.embed.init_with_sources_loadedDone == false ) {
					clip.embed.init_with_sources_loaded();
			}
		}
		
		// @@todo for some plugins we have to conform types of clips
		// ie vlc can play flash _followed_by_ ogg _followed_by_ whatever 
		//		 but
		// native ff 3.1a2 can only play ogg 
		if ( error ) {
			this.load_error = error;
			this.is_ready = false;
		} else if ( _this.clip_ready_count == _this.getClipCount() ) {
			js_log( "done init all clips: " +  _this.clip_ready_count + ' = ' + _this.getClipCount() );
			this.doWhenClipLoadDone();
		} else {
			js_log( "only " + _this.clip_ready_count + " clips done, scheduling callback:" );
			if ( !mvJsLoader.load_error )	// re-issue request if no load error:
				setTimeout( function(){
					_this.doWhenParseDone();
					//debugger;
				}, 100 );
		}
	},
	doWhenClipLoadDone:function() {
		js_log( 'mvPlaylist:doWhenClipLoadDone' );
		this.ready_to_play = true;
		this.loading = false;
		this.getHTML();
	},
	getDuration:function( regen ) {
		// js_log("GET PL DURRATION for : "+ this.tracks[this.default_track_id].clips.length + 'clips');
		if ( !regen && this.pl_duration )
			return this.pl_duration;
						
		var durSum = 0;
		$j.each( this.default_track.clips, function( i, clip ) {
			if ( clip.embed ) {
				clip.dur_offset = durSum;
				// only calculate the solo Duration if a smil clip that could contain a transition: 
				if ( clip.instanceOf == 'mvSMILClip' ) {
					// don't include transition time (for playlist_swap_loader compatible clips)				
					durSum += clip.getSoloDuration();
				} else {
					durSum += clip.getDuration();
				}
			} else {
				js_log( "ERROR: clip " + clip.id + " not ready" );
			}
		} );
		this.pl_duration = durSum;
		// js_log("return dur: " + this.pl_duration);
		return this.pl_duration;
	},
	getTimeReq:function() {
		// playlist does not really support time request atm ( in theory in the future we could embed playlists with temporal urls)
		return '0:0:0/' +  seconds2npt( this.getDuration() );
	},
	getDataSource:function() {
		js_log( "f:getDataSource " + this.src );
		// determine the type / first is it m3u or xml?	 
		var pl_parent = this;
		this.makeURLAbsolute();
		if ( this.src != null ) {
			do_request( this.src, function( data ) {
				pl_parent.data = data;
				pl_parent.getSourceType();
			} );
		}
	},
	getSrc: function(){
		return this.src;
	},
	getSourceType:function() {
		js_log( 'data type of: ' + this.src + ' = ' + typeof ( this.data ) + "\n" + this.data );
		this.srcType = null;
		// if not external use different detection matrix
		if ( this.loading_external_data ) {
			if ( typeof this.data == 'object' ) {
				js_log( 'object' );
				// object assume xml (either xspf or rss) 
				plElm = this.data.getElementsByTagName( 'playlist' )[0];
				if ( plElm ) {
					if ( plElm.getAttribute( 'xmlns' ) == 'http://xspf.org/ns/0/' ) {
						this.srcType = 'xspf';
					}
				}
				// check itunes style rss "items" 
				rssElm = this.data.getElementsByTagName( 'rss' )[0];
				if ( rssElm ) {
					if ( rssElm.getAttribute( 'xmlns:itunes' ) == 'http://www.itunes.com/dtds/podcast-1.0.dtd' ) {
						this.srcType = 'itunes';
					}
				}
				// check for smil tag: 
				smilElm = this.data.getElementsByTagName( 'smil' )[0];
				if ( smilElm ) {
					// don't check dtd yet.. (have not defined the smil subset) 
					this.srcType = 'smil';
				}
			} else if ( typeof this.data == 'string' ) {
				js_log( 'String' );
				// look at the first line: 
				var first_line = this.data.substring( 0, this.data.indexOf( "\n" ) );
				js_log( 'first line: ' + first_line );
				// string
				if ( first_line.indexOf( '#EXTM3U' ) != -1 ) {
					this.srcType = 'm3u';
				} else if ( first_line.indexOf( '<smil' ) != -1 ) {
					// @@todo parse string
					this.srcType = 'smil';
				}
			}
		}
		if ( this.srcType ) {
			js_log( 'is of type:' + this.srcType );
			this.getPlaylist();
		} else {
			// unknown playlist type
			js_log( 'unknown playlist type?' );
			if ( this.src ) {
				this.innerHTML = 'error: unknown playlist type at url:<br> ' + this.src;
			} else {
				this.innerHTML = 'error: unset src or unknown inline playlist data<br>';
			}
		}
	},
	// simple function to make a path into an absolute url if its not already
	makeURLAbsolute:function() {
		if ( this.src ) {
			if ( this.src.indexOf( '://' ) == -1 ) {
				var purl = mw.parseUri( document.URL );
				if ( this.src.charAt( 0 ) == '/' ) {
					this.src = purl.protocol + '://' + purl.host + this.src;
				} else {
					this.src = purl.protocol + '://' + purl.host + purl.directory + this.src;
				}
			}
		}
	},
	// set up minimal media_element emulation:	 
	media_element: {
		selected_source: {
			supports_url_time_encoding:true
		}
	},
	// @@todo needs to update for multi-track clip counts
	getClipCount:function() {
		return this.default_track.clips.length;
	},
	// },
	// takes in the playlist 
	// inherits all the properties 
	// swaps in the playlist object html/interface div	
	getHTML:function() {
		js_log( 'mvPlaylist:getHTML:  loading:' + this.loading );
		if ( this.loading ) {
			$j( '#' + this.id ).html( 'loading playlist...' );
			if ( this.loading_external_data ) {
				// load the data source chain of functions (to update the innerHTML)			   
				this.getDataSource();
			} else {
				// detect datatype and parse directly: 
				this.getSourceType();
			}
		} else {
			// check for empty playlist otherwise renderDisplay:		
			if ( this.default_track.getClipCount() == 0 ) {
				$j( this ).html( 'empty playlist' );
				return ;
			} else {
				this.renderDisplay();
			}
		}
	},
	renderDisplay:function() {
		js_log( 'mvPlaylist:renderDisplay:: track length: ' + this.default_track.getClipCount() );
		var _this = this;
							
		// append container and videoPlayer; 
		$j( this ).html( '<div id="dc_' + this.id + '" style="width:' + this.width + 'px;' +
				'height:' + ( this.height + this.pl_layout.title_bar_height +
				this.pl_layout.control_height ) + 'px;position:relative;">' +
			'</div>' );
		if ( this.controls == true ) {
			var cpos = _this.height + _this.pl_layout.title_bar_height;
			// give more space if not in sequence:
			cpos += ( this.sequencer ) ? 2:5;
			// append title:
			$j( '#dc_' + _this.id ).append(
				'<div style="font-size:13px;border:solid thin;width:' + this.width + 'px;" id="ptitle_' + this.id + '"></div>' +
				'<div class="' + this.ctrlBuilder.pClass + '" style="position:absolute;top:' + cpos + 'px">' +
				'<div class="ui-widget-header ui-helper-clearfix control-bar" ' +
					'style="width:' + _this.width + 'px" >' +
						 _this.getControlsHTML() +
					'</div>' +
				'</div>'
			);
								
	        // once the controls are in the DOM add hooks:	        
			this.ctrlBuilder.addControlHooks( );
		} else {
			// just append the video: 
			$j( '#dc_' + _this.id ).append(
				'<div class="' + this.ctrlBuilder.pClass + '" style="position:absolute;top:' + ( _this.height + _this.pl_layout.title_bar_height + 4 ) + 'px"></div>'
			);
		}
		this.setupClipDisplay();
						
		// update the title and status bar
		this.updateBaseStatus();
		this.doSmilActions();
	},
	setupClipDisplay:function() {
		js_log( 'mvPlaylist:setupClipDisplay:: clip len:' + this.default_track.clips.length );
		var _this = this;
		$j.each( this.default_track.clips, function( i, clip ) {
			var cout = '<div class="clip_container cc_" id="clipDesc_' + clip.id + '" ' +
				'style="display:none;position:absolute;text-align: center;width:' + _this.width + 'px;' +
				'height:' + ( _this.height ) + 'px;' +
				'top:' + this.title_bar_height + 'px;left:0px;';
			if ( _this.controls ) {
				cout += 'border:solid thin black;';
			}
			cout += '"></div>';
			$j( '#dc_' + _this.id ).append( cout );
			// update the embed html:					 
			clip.embed.height = _this.height;
			clip.embed.width = _this.width;
			clip.embed.play_button = false;
			clip.embed.controls = false;
			
			clip.embed.getHTML();// get the thubnails for everything			

			$j( clip.embed ).css( {
				'position':"absolute",
				'top':"0px",
				'left':"0px"
			} );
			if ( $j( '#clipDesc_' + clip.id ).length != 0 ) {
				js_log( "should set: #clipDesc_" + clip.id + ' to: ' + $j( clip.embed ).html() )
				$j( '#clipDesc_' + clip.id ).append( clip.embed );
			} else {
				js_log( 'cound not find: clipDesc_' + clip.id );
			}
		} );
		if ( this.cur_clip )
			$j( '#clipDesc_' + this.cur_clip.id ).css( { display:'inline' } );
	},
	updateThumbPerc:function( perc ) {
		// get float seconds:
		var float_sec =  ( this.getDuration() * perc );
		this.updateThumbTime( float_sec );
	},
	updateThumbTime:function( float_sec ) {
		// update display & cur_clip:
		var pl_sum_time = 0;
		var clip_float_sec = 0;
		// js_log('seeking clip: ');
		for ( var i in this.default_track.clips ) {
			var clip = this.default_track.clips[i];
			if ( ( clip.getDuration() + pl_sum_time ) >= float_sec ) {
				if ( this.cur_clip.id != clip.id ) {
					$j( '#clipDesc_' + this.cur_clip.id ).hide();
					this.cur_clip = clip;
					$j( '#clipDesc_' + this.cur_clip.id ).show();
				}
				break;
			}
			pl_sum_time += clip.getDuration();
		}
		
		// issue thumbnail update request: (if plugin supports it will render out frame 
		// if not then we do a call to the server to get a new jpeg thumbnail  
		this.cur_clip.embed.updateThumbTime( float_sec - pl_sum_time );
		
		this.cur_clip.embed.currentTime = ( float_sec - pl_sum_time ) + this.cur_clip.embed.start_offset ;
		this.cur_clip.embed.seek_time_sec = ( float_sec - pl_sum_time );
		
		// render effects ontop: (handled by doSmilActions)		
		this.doSmilActions();
	},
	updateBaseStatus:function() {
		var _this = this;
		js_log( 'Playlist:updateBaseStatus' );
		
		$j( '#ptitle_' + this.id ).html( '' +
			'<b>' + this.title + '</b> ' +
			this.getClipCount() + ' clips, <i>' +
			seconds2npt( this.getDuration() ) + '</i>' );
		
		// should probably be based on if we have a provider api url
		if ( typeof wgEnableWriteAPI != 'undefined' && !this.sequencer ) {
			$j( $j.btnHtml( 'edit', 'editBtn_' + this.id, 'pencil',
				{ 'style':'position:absolute;right:0;;font-size:x-small;height:10px;margin-bottom:0;padding-bottom:7px;padding-top:0;' } )
    			).click( function() {
    				_this.stop();
					_this.doEditor();
					return false;
    			} ).appendTo( '#ptitle_' + this.id );
    		$j( '.editBtn_' + this.id ).btnBind();
		}
		// render out the dividers on the timeline: 
		this.colorPlayHead();
		// update status:
		this.setStatus( '0:0:00/' + seconds2npt( this.getDuration() ) );
	},
	/*setStatus override (could call the jquery directly) */
	setStatus:function( value ) {
		$j( '#' + this.id + ' .time-disp' ).text( value );
	},
	setSliderValue:function( value ) {		
		// slider is on 1000 scale: 
		var val = parseInt( value * 1000 );
		//js_log( 'update slider: #' + this.id + ' .play_head to ' + val );
		$j( '#' + this.id + ' .play_head' ).slider( 'value', val );
	},
	getPlayHeadPos: function( prec_done ) {
		var	_this = this;
		if ( $j( '#mv_seeker_' + this.id ).length == 0 ) {
			js_log( 'no playhead so we can\'t get playhead pos' );
			return 0;
		}
		var track_len = $j( '#mv_seeker_' + this.id ).css( 'width' ).replace( /px/ , '' );
		// assume the duration is static and present at .duration during playback
		var clip_perc = this.cur_clip.embed.duration / this.getDuration();
		var perc_offset = time_offset = 0;
		for ( var i in this.default_track.clips ) {
			var clip = this.default_track.clips[i];
			if ( this.cur_clip.id == clip.id )break;
			perc_offset += ( clip.embed.duration /  _this.getDuration() );
			time_offset += clip.embed.duration;
		}
		// run any update time line hooks:		
		if ( this.update_tl_hook ) {
			var cur_time_ms = time_offset + Math.round( this.cur_clip.embed.duration * prec_done );
			if ( typeof update_tl_hook == 'function' ) {
				this.update_tl_hook( cur_time_ms );
			} else {
				// string type passed use eval: 
				eval( this.update_tl_hook + '(' + cur_time_ms + ');' );
			}
		}
		
		// handle offset hack @@todo fix so this is not needed:
		if ( perc_offset > .66 )
			perc_offset += ( 8 / track_len );
		// js_log('perc:'+ perc_offset +' c:'+ clip_perc + '*' + prec_done + ' v:'+(clip_perc*prec_done));
		return perc_offset + ( clip_perc * prec_done );
	},
	// attempts to load the embed object with the playlist
	loadEmbedPlaylist: function() {
		// js_log('load playlist');
	},
	/** mannages the loading of future clips
	 * called regurally while we are playing clips
	 * 
	 * load works like so: 
	 * if the current clip is full loaded 
	 *		 load clips untill buffredEndTime < playlist_buffer_time load next
	 * 
	 * this won't work so well with time range loading for smil (need to work on that)   
	 */
	loadFutureClips:function() {
		/*if( this.cur_clip.embed.bufferedPercent == 1){
			//set the buffer to the currentTime - duration 
			var curBuffredTime = this.cur_clip.getDuration() - this.cur_clip.embed.currentTime;		
			
			if(curBuffredTime < 0)
				curBuffredTime = 0;
				
			js_log( "curBuffredTime:: " + curBuffredTime );			
			if( curBuffredTime <  this.playlist_buffer_time ){
				js_log(" we only have " + curBuffredTime + ' buffed but we need: ' +  this.playlist_buffer_time);
						
				for(var inx = this.cur_clip.order + 1; inx < this.default_track.clips.length; inx++ ){					
					var cClip = this.default_track.getClip( inx );					
				
					//check if the clip is already loaded (add its duration)  
					if( cClip.embed.bufferedPercent == 1){
						curBuffredTime += cClip.embed.getDuration();
					}								
					//check if we still have to load a resource:		
					if( curBuffredTime < this.playlist_buffer_time ){
						//issue the load request				
						if( cClip.embed.networkState==0 ){
							cClip.embed.load();
						}
						break; //check back next time
					}																				 
				}		
			}	
		}*/
	},
	// called to play the next clip if done call onClipDone 
	playNext: function() {
		// advance the playhead to the next clip			
		var next_clip = this.getNextClip();
		
		if ( !next_clip ) {
			js_log( 'play next with no next clip... must be done:' );
			this.onClipDone();
			return ;
		}
		// @@todo where the plugin supports pre_loading future clips and manage that in javascript
		// stop current clip
		this.cur_clip.embed.stop();
		
		this.updateCurrentClip( next_clip );
		//if part of a transition should continue playing where it left off	
		this.cur_clip.embed.play();
	},
	onClipDone:function() {
		js_log( "pl onClipDone" );
		this.cur_clip.embed.stop();
	},
	updateCurrentClip : function( new_clip , into_perc) {
		js_log( 'f:updateCurrentClip:' + new_clip.id );
			
		// keep the active play clip in sync (stop the other clip) 
		if ( this.cur_clip ) {
			// make sure we are not switching to the current
			if ( this.cur_clip.id == new_clip.id ) {
				js_log( 'trying to updateCurrentClip to same clip' );
				return false;
			}
			
			if ( !this.cur_clip.embed.isStoped() )
				 this.cur_clip.embed.stop();
			this.activeClipList.remove( this.cur_clip )
			
			//hide the current clip
			$j( '#clipDesc_' + this.cur_clip.id ).hide();
		}						
		this.activeClipList.add( new_clip );				
		// do swap:		
		this.cur_clip = new_clip;
		$j( '#clipDesc_' + this.cur_clip.id ).show();
		
		// Update the playhead:
		if( this.controls ){
			// Check if we have into_perc 
			if( into_perc ){
				var clip_time =  this.cur_clip.dur_offset + ( into_perc * this.cur_clip.getDuration() );
			}else{
				var clip_time =  this.cur_clip.dur_offset;
			}
				
			this.setSliderValue( clip_time / this.getDuration() );
		}
	},
	playPrev: function() {
		// advance the playhead to the previous clip			
		var prev_clip = this.getPrevClip();
		if ( !prev_clip ) {
			js_log( "tried to play PrevClip with no prev Clip.. setting prev_clip to start clip" );
			prev_clip = this.start_clip;
		}
		// @@todo we could do something fancy like use playlist for sets of clips where supported. 
		// or in cases where the player nativly supports the playlist format we can just pass it in (ie m3u or xspf)
		if ( this.cur_clip.embed.supports['playlist_swap_loader'] ) {
			// where the plugin supports pre_loading future clips and manage that in javascript
			// pause current clip
			this.cur_clip.embed.pause();
			// do swap:
			this.updateCurrentClip( prev_clip );
			this.cur_clip.embed.play();
		} else {
			js_log( 'do prev hard embed swap' );
			this.switchPlayingClip( prev_clip );
		}
	},
	switchPlayingClip:function( new_clip ) {
		// swap out the existing embed code for next clip embed code
		$j( '#mv_ebct_' + this.id ).empty();
		new_clip.embed.width = this.width;
		new_clip.embed.height = this.height;
		// js_log('set embed to: '+ new_clip.embed.getEmbedObj());
		$j( '#mv_ebct_' + this.id ).html( new_clip.embed.getEmbedObj() );
		this.cur_clip = new_clip;
		// run js code: 
		this.cur_clip.embed.pe_postEmbedJS();
	},
	// playlist play
	play: function() {
		var _this = this;
		js_log( 'pl play' );
		// hide the playlist play button: 
		$j( this.id + ' .play-btn-large' ).hide();
		
		// un-pause if paused:
		if ( this.paused )
			this.paused = false;
		
		// update the control:		 
		this.start_clip = this.cur_clip;
		this.start_clip_src = this.cur_clip.src;
		 
		if ( this.cur_clip.embed.supports['playlist_swap_loader'] ) {
			// set the cur_clip to active
			this.activeClipList.add( this.cur_clip );
			
			// navtive support:
			// * pre-loads clips
			// * mv_playlist smil extension, manages transitions animations overlays etc.			 
			// js_log('clip obj supports playlist swap_loader (ie playlist controlled playback)');							
			// @@todo pre-load each clip:
			// play all active clips (playlist_swap_loader can have more than one clip active)		 
			$j.each( this.activeClipList.getClipList(), function( inx, clip ) {
				clip.embed.play();
			} );
		} else if ( this.cur_clip.embed.supports['playlist_driver'] ) {
			// js_log('playlist_driver');
			// embedObject is feed the playlist info directly and manages next/prev
			this.cur_clip.embed.playMovieAt( this.cur_clip.order );
		} else {
			// not much playlist support just play the first clip:
			// js_log('basic play');
			// play cur_clip			
			this.cur_clip.embed.play();
		}
		// start up the playlist monitor			
		this.monitor();
	},
	/*
	 * the load function loads all the clips in order 
	 */
	load:function() {
		// do nothing right now)
		alert('load pl');		
	},
	toggleMute:function() {
		this.cur_clip.embed.toggleMute();
	},
	pause:function() {
		// js_log('f:pause: playlist');
		var ct = new Date();
		this.pauseTime = this.currentTime;
		this.paused = true;
		// js_log('pause time: '+ this.pauseTime + ' call embed pause:');

		// pause all the active clips:
		$j.each( this.activeClipList.getClipList(), function( inx, clip ) {
			clip.embed.pause();
		} );
	},
	// @@todo mute across all child clips: 
	toggleMute:function() {
		var this_id = ( this.pc != null ) ? this.pc.pp.id:this.id;
		if ( this.muted ) {
			this.muted = false;
			$j( '#volume_control_' + this_id + ' span' ).removeClass( 'ui-icon-volume-off' ).addClass( 'ui-icon-volume-on' );
			$j( '#volume_bar_' + this_id ).slider( 'value', 100 );
			this.updateVolumen( 1 );
		} else {
			this.muted = true;
			$j( '#volume_control_' + this_id + ' span' ).removeClass( 'ui-icon-volume-on' ).addClass( 'ui-icon-volume-off' );
			$j( '#volume_bar_' + this_id ).slider( 'value', 0 );
			this.updateVolumen( 0 );
		}
		js_log( 'f:toggleMute::' + this.muted );
	},
	updateVolumen:function( perc ) {
		js_log( 'update volume not supported with current playback type' );
	},
	fullscreen:function() {
		this.cur_clip.embed.fullscreen();
	},
	// playlist stops playback for the current clip (and resets state for start clips)
	stop:function() {
		var _this = this;
		/*js_log("pl stop:"+ this.start_clip.id + ' c:'+this.cur_clip.id);
		//if start clip 
		if(this.start_clip.id!=this.cur_clip.id){
			//restore clipDesc visibility & hide desc for start clip: 
			$j('#clipDesc_'+this.start_clip.id).html('');
			this.start_clip.getDetail();
			$j('#clipDesc_'+this.start_clip.id).css({display:'none'});
			this.start_clip.setBaseEmbedDim(this.start_clip.embed);
			//equivalent of base stop
			$j('#'+this.start_clip.embed.id).html(this.start_clip.embed.getThumbnailHTML());
			this.start_clip.embed.thumbnail_disp=true;
		}
		//empty the play-back container
		$j('#mv_ebct_'+this.id).empty();*/
		
		// stop all the clips: monitor: 
		window.clearInterval( this.smil_monitorTimerId );
		/*for (var i=0;i<this.clips.length;i++){
			var clip = this.clips[i];
			if(clip){
				clip.embed.stop();
				$j('#clipDesc_'+clip.id).hide();
			}
		}*/
		// stop, hide and remove all active clips:
		$j.each( this.activeClipList.getClipList(), function( inx, clip ) {
			if ( clip ) {
				clip.embed.stop();
				$j( '#clipDesc_' + clip.id ).hide();
				_this.activeClipList.remove( clip );
			}
		} );
		// set the current clip to the first clip: 
		if ( this.start_clip ) {
			this.cur_clip = this.start_clip;
			// display the first clip thumb: 
			this.cur_clip.embed.stop();
			// hide other clips:
			$j( '#' + this.id + ' .clip_container' ).hide();
			// show the first/current clip:
			$j( '#clipDesc_' + this.cur_clip.id ).show();
		}
		// reset the currentTime: 
		this.currentTime = 0;
		// rest the sldier
		this.setSliderValue( 0 );
		// FIXME still some issues with "stoping" and reseting the playlist	
	},
	doSeek:function( v ) {
		js_log( 'pl:doSeek:' + v + ' sts:' + this.seek_time_sec );
		var _this = this;
		
		var time = v * this.getDuration()
		_this.currentTime = time;
		var relative_perc = _this.updateClipByTime();
		
		// Update the clip relative seek_time_sec
		_this.cur_clip.embed.doSeek( relative_perc );
		_this.monitor();
		
		return '';
	},
	setCurrentTime: function( time, callback ) {
		js_log( 'pl:setCurrentTime:' + time );
		var _this = this;
		_this.currentTime = time;
		
		var pl_perc =  time / this.getDuration();
		var relative_perc = _this.updateClipByTime();	
		var clip_time = relative_perc * _this.cur_clip.embed.getDuration();		
		_this.cur_clip.embed.setCurrentTime( clip_time, function() {
			//update the smil actions now that the seek is done 
			_this.doSmilActions();
			//say we are "ready"
			if ( callback )
				callback();
		} );					
	},
	/*
	* updateClipByTime::
	*
	* @returns the relative offsets of the current clip (given the playlist time) 
 	*/
	updateClipByTime: function(){
		var _this = this;
		var prevClip = null;
		//set the current percent done: 
		var pt = this.currentTime / _this.getDuration();
		// jump to the clip in the current percent. 
		var perc_offset = 0;
		var next_perc_offset = 0;
		for ( var i in _this.default_track.clips ) {
			var clip = _this.default_track.clips[i];
			next_perc_offset += ( clip.getDuration() /  _this.getDuration() ) ;
			// js_log('on ' + clip.getDuration() +' next_perc_offset:'+ next_perc_offset);
			if ( next_perc_offset > pt ) {		 
				// js_log('seek:'+ pt +' - '+perc_offset + ') /  (' + next_perc_offset +' - '+ perc_offset);
				var relative_perc =  ( pt - perc_offset ) /  ( next_perc_offset - perc_offset );
				// update the current clip:								 
				_this.updateCurrentClip( clip, relative_perc );
				return relative_perc;
			}
			perc_offset = next_perc_offset;
		}
		return 0;
	},
	// gets playlist controls large control height for sporting 
	// next prev button and more status display
	getControlsHTML:function() {
		// get controls from current clip  (add some playlist specific controls:		  			
		return this.ctrlBuilder.getControls( this );
	},
	// ads colors/dividers between tracks
	colorPlayHead: function() {
		var _this = this;
		
		if ( !_this.mv_seeker_width )
			_this.mv_seeker_width = $j( '#' + _this.id + ' .play_head' ).width();
	
		if ( !_this.track_len )
			_this.track_len = $j( '#' + _this.id + ' .play_head' ).width();
			
		// total duration:		
		var pl_duration = _this.getDuration();
		
		var cur_pixle = 0;
		// set up _this

		// js_log("do play head total dur: "+pl_duration );
		$j.each( this.default_track.clips, function( i, clip ) {
			// (use getSoloDuration to not include transitions and such)	 
			var perc = ( clip.getSoloDuration() / pl_duration );
			var pwidth = Math.round( perc * _this.track_len );
			// js_log('pstatus:c:'+ clip.getDuration() + ' of '+ pl_duration+' %:' + perc + ' width: '+ pwidth + ' of total: ' + _this.track_len);
			// var pwidth = Math.round( perc  * _this.track_len - (_this.mv_seeker_width*perc) );

			// add the buffer child indicator:						 
			var barHtml = '<div id="cl_status_' + clip.embed.id + '" class="cl_status"  style="' +
					'left:' + cur_pixle + 'px;' +
					'width:' + pwidth + 'px;';
			// set left or right border based on track pos 
			barHtml += ( i == _this.default_track.getClipCount() - 1 ) ?
				 'border-left:solid thin black;':
				 'border-right:solid thin black;';
			barHtml += 'filter:alpha(opacity=40);' +
					'-moz-opacity:.40;">';
			
			barHtml += _this.ctrlBuilder.getMvBufferHtml();
			
			barHtml += '</div>';
			
			// background:#DDD +clip.getColor();

			$j( '#' + _this.id + ' .play_head' ).append( barHtml );
																										
			// js_log('offset:' + cur_pixle +' width:'+pwidth+' add clip'+ clip.id + ' is '+clip.embed.getDuration() +' = ' + perc +' of ' + _this.track_len);
			cur_pixle += pwidth;
		} );
	},
	// @@todo currently not really in use
	setUpHover:function() {
		js_log( 'Setup Hover' );
		// set up hover for prev,next 
		var th = 50;
		var tw = th * this.pl_layout.clip_aspect;
		var _this = this;
		$j( '#mv_prev_link_' + _this.id + ',#mv_next_link_' + _this.id ).hover( function() {
			  var clip = ( this.id == 'mv_prev_link_' + _this.id ) ? _this.getPrevClip() : _this.getNextClip();
			  if ( !clip )
				  return js_log( 'missing clip for Hover' );
			  // get the position of #mv_perv|next_link:
			  var loc = getAbsolutePos( this.id );
			  // js_log('Hover: x:'+loc.x + ' y:' + loc.y + ' :'+clip.img);
			   $j( "body" ).append( '<div id="mv_Athub" style="position:absolute;' +
				   'top:' + loc.y + 'px;left:' + loc.x + 'px;width:' + tw + 'px;height:' + th + 'px;">' +
				'<img style="border:solid 2px ' + clip.getColor() + ';position:absolute;top:0px;left:0px;" width="' + tw + '" height="' + th + '" src="' + clip.img + '"/>' +
			'</div>' );
	  }, function() {
			  $j( '#mv_Athub' ).remove();
	  } );
	},
	// @@todo we need to move a lot of this track logic like "cur_clip" to the track Obj
	// and have the playlist just drive the tracks. 
	getNextClip:function( track ) {
		if ( !track )
			track = this.default_track;
		var tc = parseInt( this.cur_clip.order ) + 1;
		var cat = track;
		if ( tc > track.getClipCount() - 1 )
			return false; // out of range

		return	 track.getClip( tc );
	},
	getPrevClip:function( track ) {
		if ( !track )
			track = this.default_track;
		var tc = parseInt( this.cur_clip.order ) - 1;
		if ( tc < 0 )
			return false;
		return track.getClip( tc );
	},
	/* 
	 * generic add Clip to ~default~ track
	 */
	addCliptoTrack: function( clipObj, pos ) {
		if ( typeof clipObj['track_id'] == 'undefined' ) {
			var track = this.default_track;
		} else {
			var track = this.tracks[ clipObj.track_id ]
		}
		js_log( 'add clip:' + clipObj.id + ' to track: at:' + pos );
		// set the first clip to current (maybe deprecated ) 
		if ( clipObj.order == 0 ) {
			if ( !this.cur_clip )this.cur_clip = clipObj;
		}
		track.addClip( clipObj, pos );
	},
	swapClipDesc: function( req_clipID, callback ) {
		// hide all but the requested
		var _this = this;
		js_log( 'r:' + req_clipID + ' cur:' + _this.id );
		if ( req_clipID == _this.cur_clip.id ) {
			js_log( 'no swap to same clip' );
		} else {
			// fade out clips
			req_clip = null;
			$j.each( this.default_track.clips, function( i, clip ) {
				if ( clip.id != req_clipID ) {
					// fade out if display!=none already
					if ( $j( '#clipDesc_' + clip.id ).css( 'display' ) != 'none' ) {
						$j( '#clipDesc_' + clip.id ).fadeOut( "slow" );
					}
				} else {
					req_clip = clip;
				}
			} );
			// fade in requested clip *and set req_clip to current
			$j( '#clipDesc_' + req_clipID ).fadeIn( "slow", function() {
					_this.cur_clip = req_clip;
					if ( callback )
						callback();
			} );
		}
	},
	// this is pretty outdated:	 
	getPLControls: function() {
		js_log( 'getPL cont' );
		return	 '<a id="mv_prev_link_' + this.id + '" title="Previus Clip" onclick="document.getElementById(\'' + this.id + '\').playPrev();return false;" href="#">' +
					getTransparentPng( { id:'mv_prev_btn_' + this.id, style:'float:left', width:'27', height:'27', border:"0",
						src:mv_skin_img_path + 'vid_prev_sm.png' } ) +
				'</a>' +
				'<a id="mv_next_link_' + this.id + '"  title="Next Clip"  onclick="document.getElementById(\'' + this.id + '\').playNext();return false;" href="#">' +
					getTransparentPng( { id:'mv_next_btn_' + this.id, style:'float:left', width:'27', height:'27', border:"0",
						src:mv_skin_img_path + 'vid_next_sm.png' } ) +
				'</a>';
	},
	run_transition: function( clip_inx, trans_type ) {
		if ( typeof this.default_track.clips[ clip_inx ][ trans_type ] == 'undefined' )
			clearInterval( this.default_track.clips[ clip_inx ].timerId );
		else
			this.default_track.clips[ clip_inx ][ trans_type ].run_transition();
	},
	playerPixelWidth : function()
	{
		var player = $j( '#dc_' + this.id ).get( 0 );
		if ( typeof player != 'undefined' && player['offsetWidth'] )
			return player.offsetWidth;
		else
			return parseInt( this.width );
	},
	playerPixelHeight : function()
	{
		var player = $j( '#dc_' + this.id ).get( 0 );
		if ( typeof player != 'undefined' && player['offsetHeight'] )
			return player.offsetHeight;
		else
			return parseInt( this.height );
	}
}

/* Object Stubs: 
 * 
 * @videoTrack ... stores clips and layer info
 * 
 * @clip... each clip segment is a clip object. 
 * */
var mvClip = function( o ) {
	if ( o )
		this.init( o );
	return this;
};
// set up the mvPlaylist object
mvClip.prototype = {
	id:null, // clip id
	pp:null, // parent playlist
	order:null, // the order/array key for the current clip
	src:null,
	info:null,
	title:null,
	mvclip:null,
	type:null,
	img:null,
	duration:null,
	loading:false,
	isAnimating:false,
	init:function( o ) {
		// init object including pointer to parent
		for ( var i in o ) {
			this[i] = o[i];
		};
		js_log( 'id is: ' + this.id );
	},
	// setup the embed object:
	setUpEmbedObj:function() {
		js_log( 'mvClip:setUpEmbedObj()' );
		
		this.embed = null;
		// js_log('setup embed for clip '+ this.id + ':id is a function?'); 
		// set up the pl_mv_embed object:
		var init_pl_embed = { id:'e_' + this.id,
			pc:this, // parent clip
			src:this.src
		};

		this.setBaseEmbedDim( init_pl_embed );

		
		// if in sequence mode hide controls / embed links		 
		//			init_pl_embed.play_button=false;
		// init_pl_embed.controls=true;	
		// if(this.pp.sequencer=='true'){
		init_pl_embed.embed_link = null;
		init_pl_embed.linkback = null;
		
		if( this.durationHint )
			init_pl_embed.durationHint =  this.durationHint;
		
		if ( this.poster )init_pl_embed['thumbnail'] = this.poster;
		
		if ( this.type )init_pl_embed['type'] = this.type;
				
		this.embed = new PlMvEmbed( init_pl_embed );
					
		// js_log('media Duration:' + this.embed.getDuration() );
		// js_log('media element:'+ this.embed.media_element.length);
		// js_log('type of embed:' + typeof(this.embed) + ' seq:' + this.pp.sequencer+' pb:'+ this.embed.play_button);		
	},
	doAdjust:function( side, delta ) {
		js_log( "f:doAdjust: " + side + ' , ' +  delta );
		if ( this.embed ) {
			if ( side == 'start' ) {
				var start_offset = parseInt( this.embed.start_offset ) + parseInt( delta * -1 );
				this.embed.updateVideoTime( seconds2npt( start_offset ), seconds2npt ( this.embed.start_offset + this.embed.getDuration() ) );
			} else if ( side == 'end' ) {
				var end_offset = parseInt( this.embed.start_offset ) + parseInt( this.embed.getDuration() ) + parseInt( delta );
				this.embed.updateVideoTime( seconds2npt( this.embed.start_offset ), seconds2npt( end_offset ) );
			}
			// update everything: 
			this.pp.refresh();
			/*var base_src = this.src.substr(0,this.src.indexOf('?'));
			js_log("delta:"+ delta);
			if(side=='start'){
				//since we adjust start invert the delta: 
				var start_offset =parseInt(this.embed.start_offset/1000)+parseInt(delta*-1);
				this.src = base_src +'?t='+ seconds2npt(start_offset) +'/'+ this.embed.end_ntp;							
			}else if(side=='end'){
				//put back into seconds for adjustment: 
				var end_offset = parseInt(this.embed.start_offset/1000) + parseInt(this.embed.duration/1000) + parseInt(delta);
				this.src = base_src +'?t='+ this.embed.start_ntp +'/'+ seconds2npt(end_offset);
			}				
			this.embed.updateVideoTime( this.src );
			//update values
			this.duration = this.embed.getDuration();
			this.pp.pl_duration=null;
			//update playlist stuff:
			this.pp.updateTitle();*/
		}
	},
	getDuration:function() {
		if ( !this.embed )this.setUpEmbedObj();
		return this.embed.getDuration();
	},
	setBaseEmbedDim:function( o ) {
		if ( !o )o = this;
		// o.height=Math.round(pl_layout.clip_desc*this.pp.height)-2;//give it some padding:
		// o.width=Math.round(o.height*pl_layout.clip_aspect)-2;
		o.height =	this.pp.height;
		o.width =	this.pp.width;
	},
	// output the detail view:
	// @@todo
	/*getDetail:function(){
		//js_log('get detail:' + this.pp.title);
		var th=Math.round( this.pl_layout.clip_desc * this.pp.height );	
		var tw=Math.round( th * this.pl_layout.clip_aspect );		
		
		var twDesc = (this.pp.width-tw)-2;
		
		if(this.title==null)
			this.title='clip ' + this.order + ' ' +this.pp.title;
		if(this.desc==null)
			this.desc=this.pp.desc;
		//update the embed html: 
		this.embed.getHTML();
					
		$j(this.embed).css({ 'position':"absolute",'top':"0px", 'left':"0px"});
		
		//js_log('append child to:#clipDesc_'+this.id);
		if($j('#clipDesc_'+this.id).get(0)){
			$j('#clipDesc_'+this.id).get(0).appendChild(this.embed);
			
			$j('#clipDesc_'+this.id).append(''+
			'<div id="pl_desc_txt_'+this.id+'" class="pl_desc" style="position:absolute;left:'+(tw+2)+'px;width:'+twDesc+'px;height:'+th+'px;overflow:auto;">'+
					'<b>'+this.title+'</b><br>'+			
					this.desc + '<br>' + 
					'<b>clip length:</b> '+ seconds2npt( this.embed.getDuration() ); 
			'</div>');		
		}
	},*/
	getTitle:function() {
		if ( typeof this.title == 'string' )
			return this.title
			
		return 'untitled clip ' + this.order;
	},
	getClipImg:function( start_offset, size ) {
		js_log( 'f:getClipImg ' + start_offset + ' s:' + size );
		if ( !this.img ) {
			return mv_default_thumb_url;
		} else {
			if ( !size && !start_offset ) {
				return this.img;
			} else {
				// if a metavid image (has request parameters) use size and time args
				if ( this.img.indexOf( '?' ) != -1 ) {
					js_log( 'get with offset: ' + start_offset );
					var time = seconds2npt( start_offset + ( this.embed.start_offset / 1000 ) );
					js_log( "time is: " + time );
					this.img = this.img.replace( /t\=[^&]*/gi, "t=" + time );
					if ( this.img.indexOf( '&size=' ) != -1 ) {
						this.img = this.img.replace( /size=[^&]*/gi, "size=" + size );
					} else {
						this.img += '&size=' + size;
					}
				}
				return this.img;
			}
		}
	},
	getColor: function() {
		// js_log('get color:'+ num +' : '+  num.toString().substr(num.length-1, 1) + ' : '+colors[ num.toString().substr(num.length-1, 1)] );
		var num = this.id.substr( this.id.length - 1, 1 );
		if ( !isNaN( num ) ) {
			num = num.charCodeAt( 0 );
		}
		if ( num >= 10 )num = num % 10;
		return mv_clip_colors[num];
	}
}
/* mv_embed extensions for playlists */
var PlMvEmbed = function( vid_init ) {
	// js_log('PlMvEmbed: '+ vid_init.id);	
	// create the div container
	var ve = document.createElement( 'div' );
	// extend ve with all this 
	this.init( vid_init );
	for ( method in this ) {
		if ( method != 'readyState' ) {
			ve[method] = this[method];
		}
	}
	js_log( 've src len:' + ve.media_element.sources.length );
	return ve;
}
// all the overwritten and new methods for playlist extension of baseEmbed
PlMvEmbed.prototype = {
	init:function( vid_init ) {
		// send embed_video a created video element: 
		ve = document.createElement( 'div' );
		for ( var i in vid_init ) {
			// set the parent clip pointer:	 
			if ( i == 'pc' ) {
				this['pc'] = vid_init['pc'];
			} else {
				ve.setAttribute( i, vid_init[i] );
			}
		}
		var videoInterface = new embedVideo( ve );
		// inherit the videoInterface
		for ( method in videoInterface ) {
			if ( method != 'style' ) {
				if ( this[ method ] ) {
					// parent embed method preservation:
					this['pe_' + method] = videoInterface[method];
				} else {
					this[method] = videoInterface[method];
				}
			}
			// string -> boolean:
			if ( this[method] == "false" )this[method] = false;
			if ( this[method] == "true" )this[method] = true;
		}
	},
	onClipDone:function() {
		js_log( 'pl onClipDone (should go to next)' );
		// go to next in playlist: 
		this.pc.pp.playNext();
	},
	stop:function() {
		js_log( 'pl:do stop' );
		// set up convenience pointer to parent playlist
		var _this = this.pc.pp;
					
		var th = Math.round( _this.pl_layout.clip_desc * _this.height );
		var tw = Math.round( th * _this.pl_layout.clip_aspect );
		
		// run the parent stop:
		this.pe_stop();
		var pl_height = ( _this.sequencer == 'true' ) ? _this.height + 27:_this.height;
		
		this.getHTML();
	},
	play:function() {
		// js_log('pl eb play');		
		var _this = this.pc.pp;
		// check if we are already playing
		if ( !this.thumbnail_disp ) {
			this.pe_play();
			return '';
		}
		mv_lock_vid_updates = true;
		this.pe_play();
	},
	// do post interface operations
	postEmbedJS:function() {
		// add playlist clips (if plugin supports it) 
		if ( this.pc.pp.cur_clip.embed.playlistSupport() )
			this.pc.pp.loadEmbedPlaylist();
		// color playlist points (if play_head present)
		if ( this.pc.pp.disp_play_head )
			this.pc.pp.colorPlayHead();
		// setup hover images (for playhead and next/prev buttons)
		this.pc.pp.setUpHover();
		// call the parent postEmbedJS
		this.pe_postEmbedJS();
		mv_lock_vid_updates = false;
	},
	getPlayButton:function() {
		return this.pe_getPlayButton( this.pc.pp.id );
	},
	setStatus:function( value ) {
		// status updates handled by playlist obj
	},
	setSliderValue:function( value ) {
		//js_log( 'PlMvEmbed:setSliderValue:' + value );
		// setSlider value handled by playlist obj		
	}
}

/* 
 *  m3u parse
 */
var m3uPlaylist = {
	doParse:function() {
		// for each line not # add as clip 
		var inx = 0;
		var this_pl = this;
		// js_log('data:'+ this.data.toString());
		$j.each( this.data.split( "\n" ), function( i, n ) {
			// js_log('on line '+i+' val:'+n+' len:'+n.length);
			if ( n.charAt( 0 ) != '#' ) {
				if ( n.length > 3 ) {
					// @@todo make sure its a valid url
					// js_log('add url: '+i + ' '+ n);
					var cur_clip = new mvClip( { type:'srcClip', id:'p_' + this_pl.id + '_c_' + inx, pp:this_pl, src:n, order:inx } );
					// setup the embed object 
					cur_clip.setUpEmbedObj();
					js_log( 'm3uPlaylist len:' + thisClip.embed.media_element.sources.length );
					this_pl.addCliptoTrack( cur_clip );
					inx++;
				}
			}
		} );
		return true;
	}
}

var itunesPlaylist = {
	doParse:function() {
		var properties = { title:'title', linkback:'link',
						   author:'itunes:author', desc:'description',
						   date:'pubDate' };
		var tmpElm = null;
		for ( i in properties ) {
			tmpElm = this.data.getElementsByTagName( properties[i] )[0];
			if ( tmpElm ) {
				this[i] = tmpElm.childNodes[0].nodeValue;
				// js_log('set '+i+' to '+this[i]);
			}
		}
		// image src is nested in itunes rss:
		tmpElm = this.data.getElementsByTagName( 'image' )[0];
		if ( tmpElm ) {
			imgElm = tmpElm.getElementsByTagName( 'url' )[0];
				if ( imgElm ) {
					this.img = imgElm.childNodes[0].nodeValue;
				}
		}
		// get the clips: 
		var clips = this.data.getElementsByTagName( "item" );
		properties.src = 'guid';
		for ( var i = 0; i < clips.length; i++ ) {
			var cur_clip = new mvClip( { type:'srcClip', id:'p_' + this.id + '_c_' + i, pp:this, order:i } );
			for ( var j in properties ) {
				tmpElm = clips[i].getElementsByTagName( properties[j] )[0];
				if ( tmpElm != null ) {
					cur_clip[j] = tmpElm.childNodes[0].nodeValue;
					// js_log('set clip property: ' + j+' to '+cur_clip[j]);
				}
			}
			// image is nested
			tmpElm = clips[i].getElementsByTagName( 'image' )[0];
			if ( tmpElm ) {
				imgElm = tmpElm.getElementsByTagName( 'url' )[0];
					if ( imgElm ) {
						cur_clip.img = imgElm.childNodes[0].nodeValue;
					}
			}
			// set up the embed object now that all the values have been set
			cur_clip.setUpEmbedObj();
			
			// add the current clip to the clip list
			this.addCliptoTrack( cur_clip );
		}
		return true;
	}
}

/* 
 * parse xsfp: 
 * http://www.xspf.org/xspf-v1.html
 */
var xspfPlaylist = {
	doParse:function() {
		// js_log('do xsfp parse: '+ this.data.innerHTML);
		var properties = { title:'title', linkback:'info',
						   author:'creator', desc:'annotation',
						   poster:'image', date:'date' };
		var tmpElm = null;
		// get the first instance of any of the meta tags (ok that may be the meta on the first clip)
		// js_log('do loop on properties:' + properties);
		for ( i in properties ) {
			js_log( 'on property: ' + i );
			tmpElm = this.data.getElementsByTagName( properties[i] )[0];
			if ( tmpElm ) {
				if ( tmpElm.childNodes[0] ) {
					this[i] = tmpElm.childNodes[0].nodeValue;
					js_log( 'set pl property: ' + i + ' to ' + this[i] );
				}
			}
		}
		var clips = this.data.getElementsByTagName( "track" );
		js_log( 'found clips:' + clips.length );
		// add any clip specific properties 
		properties.src = 'location';
		for ( var i = 0; i < clips.length; i++ ) {
			var cur_clip = new mvClip( { id:'p_' + this.id + '_c_' + i, pp:this, order:i } );
			// js_log('cur clip:'+ cur_clip.id);
			for ( var j in properties ) {
				tmpElm = clips[i].getElementsByTagName( properties[j] )[0];
				if ( tmpElm != null ) {
					if ( tmpElm.childNodes.length != 0 ) {
						cur_clip[j] = tmpElm.childNodes[0].nodeValue;
						js_log( 'set clip property: ' + j + ' to ' + cur_clip[j] );
					}
				}
			}
			// add mvClip ref from info link: 
			if ( cur_clip.linkback ) {
				// if mv linkback
				mvInx = 'Stream:';
				mvclippos = cur_clip.linkback.indexOf( mvInx );
				if ( mvclippos !== false ) {
					cur_clip.mvclip = cur_clip.linkback.substr( mvclippos + mvInx.length );
				}
			}
			// set up the embed object now that all the values have been set
			cur_clip.setUpEmbedObj();
			// add the current clip to the clip list
			this.addCliptoTrack( cur_clip );
		}
		// js_log('done with parse');
		return true;
	}
}
/*****************************
 * SMIL CODE (could be put into another js file / lazy_loaded for improved basic playlist performance / modularity)
 *****************************/
/*playlist driver extensions to the playlist object*/
mvPlayList.prototype.monitor = function() {
	// js_log('pl:monitor');			
	// if paused stop updates
	if ( this.paused ) {
		// clearInterval( this.smil_monitorTimerId );
		return ;
	}
	// js_log("pl check: " + this.currentTime + ' > '+this.getDuration());
	// check if we should be done:
	if ( this.currentTime >  this.getDuration() )
		this.stop();
	
	// update the playlist current time: 
	// check for a trsnOut from the previus clip to subtract
	this.currentTime = this.cur_clip.dur_offset + this.cur_clip.embed.relativeCurrentTime();
		
	// update slider: 
	if ( !this.userSlide ) {
		this.setStatus( seconds2npt( this.currentTime ) + '/' + seconds2npt( this.getDuration() ) );
		this.setSliderValue( this.currentTime / this.getDuration() );
	}
	// pre-load any future clips:
	this.loadFutureClips();
	
	
	// status updates are handled by children clips ... playlist mostly manages smil actions
	this.doSmilActions();
	
	if ( ! this.smil_monitorTimerId ) {
		if ( document.getElementById( this.id ) ) {
			this.smil_monitorTimerId = setInterval( '$j(\'#' + this.id + '\').get(0).monitor()', 250 );
		}
	}
}

// handles the rendering of overlays load of future clips (if necessary)
// @@todo could be lazy loaded if necessary 
mvPlayList.prototype.doSmilActions = function( callback ) {
	var _this = this;
	// js_log('f:doSmilActions: ' + this.cur_clip.id + ' tid: ' + this.cur_clip.transOut );
	var offSetTime = 0; // offset time should let us start a transition later on if we have to. 
	var _clip = this.cur_clip;	// setup a local pointer to cur_clip

	
	// do any smil time actions that may change the current clip
	if ( this.userSlide ) {
		// current clip set is updated mannually outside the scope of smil Actions 
	} else {
		// Assume playing and go to next: 
		if ( _clip.dur <= _clip.embed.currentTime
			 && _clip.order != _clip.pp.getClipCount() - 1 ) {
			// force next clip
			js_log( 'order:'  + _clip.order + ' != count:' + ( _clip.pp.getClipCount() - 1 ) +
				' smil dur: ' + _clip.dur + ' <= curTime: ' + _clip.embed.currentTime + ' go to next clip..' );
				// do a _play next:
				_clip.pp.playNext();
		}
	}
	// @@todo could maybe generalize transIn with trasOut into one "flow" with a few scattered if statements	
	// update/setup all transitions (will render current transition state)	

	// process actions per transition types:
	_this.procTranType( 'transIn', callback);
	_this.procTranType( 'transOut', callback);
}

/*
* procTranType
* @param {string} tid the transition type [transIn|transOut]
* @param {function} callback the callback function passed onto doUPdate
*/
mvPlayList.prototype.procTranType = function( tid, callback){
	// Setup local clip pointer:
	var _clip = this.cur_clip;	
	
	eval( 'var tObj =  _clip.' + tid );
	if ( !tObj )
		return;
	// js_log('f:doSmilActions: ' + _clip.id + ' tid:'+tObj.id + ' tclip_id:'+ tObj.pClip.id);					
	// Check if we are in range: 
	if ( tid == 'transIn' )
		in_range = ( _clip.embed.currentTime <= tObj.dur ) ? true : false;
	
	if ( tid == 'transOut' )
		in_range = ( _clip.embed.currentTime >= ( _clip.dur - tObj.dur ) ) ? true : false;
	
	if ( in_range ) {
		if ( this.userSlide || this.paused ) {
			if ( tid == 'transIn' ){
				mvTransLib.doUpdate( tObj, 
					( _clip.embed.currentTime / tObj.dur ), 
					callback );	
			}		
			if ( tid == 'transOut' ){
				mvTransLib.doUpdate( tObj, 
					( ( _clip.embed.currentTime - ( _clip.dur - tObj.dur ) ) / tObj.dur ), 
					callback );
			}
		} else if ( tObj.animation_state == 0 ) {
			js_log( 'init/run_transition ' );
			tObj.run_transition();
		}
	} else {
		// Close up transition if done & still onDispaly
		if ( tObj.overlay_selector_id ) {
			js_log( 'close up transition :' + tObj.overlay_selector_id );
			mvTransLib.doCloseTransition( tObj );
		}
	}
	// Run the callback:: 
	if( callback ) 
		callback();
}

/*
 * mvTransLib library of transitions
 * a single object called to initiate transition effects can easily be extended in separate js file
 * /mvTransLib is a all static object no instances of mvTransLib/
 * (that way a limited feature set "sequence" need not include a _lot_ of js unless necessary )
 * 
 * Smil Transition Effects see:  
 * http://www.w3.org/TR/SMIL3/smil-transitions.html#TransitionEffects-TransitionAttribute
 */
var mvTransLib = {
	/*
	 * function doTransition lookups up the transition in the  mvTransLib obj
	 *		 and init the transition if its available 
	 * @param tObj transition attribute object
	 * @param offSetTime default value 0 if we need to start rendering from a given time 
	 */
	doInitTransition:function( tObj ) {
		js_log( 'mvTransLib:f:doInitTransition' );		
		if ( !tObj.type ) {
			js_log( 'transition is missing type attribute' );
			return false;
		}
		
		if ( !tObj.subtype ) {
			js_log( 'transition is missing subtype attribute' );
			return false;
		}
		
		if ( !this['type'][tObj.type] ) {
			js_log( 'mvTransLib does not support type: ' + tObj.type );
			return false;
		}
		
		if ( !this['type'][tObj.type][tObj.subtype] ) {
			js_log( 'mvTransLib does not support subType: ' + tObj.subtype );
			return false;
		}
				
		// setup overlay_selector_id			
		if ( tObj.subtype == 'crossfade' ) {
			if ( tObj.transAttrType == 'transIn' )
				var other_pClip = tObj.pClip.pp.getPrevClip();
			if ( tObj.transAttrType == 'transOut' )
				var other_pClip = tObj.pClip.pp.getNextClip();
				
			if ( typeof( other_pClip ) == 'undefined' || other_pClip === false || other_pClip.id == tObj.pClip.pp.cur_clip.id )
				js_log( 'Error: crossfade without target media asset' );
			// if not sliding start playback: 
			if ( !tObj.pClip.pp.userSlide && !tObj.pClip.pp.paused) {
				other_pClip.embed.play();			
			}else{
				//issue a load request: 
				other_pClip.embed.load(); 
			}
			// manualy ad the extra layer to the activeClipList
			tObj.pClip.pp.activeClipList.add( other_pClip );
			tObj.overlay_selector_id = 'clipDesc_' + other_pClip.id;
		} else {
			tObj.overlay_selector_id = this.getOverlaySelector( tObj );
		}
					
		// all good call function with  tObj param
		js_log( 'should call: ' + tObj.type + ' ' + tObj.subtype );
		this['type'][tObj.type][tObj.subtype].init( tObj );
	},
	doCloseTransition:function( tObj ) {
		if ( tObj.subtype == 'crossfade' ) {
			// close up crossfade
			js_log( "close up crossfade" );
		} else {
			$j( '#' + tObj.overlay_selector_id ).remove();
		}
		// null selector: 
		tObj.overlay_selector_id = null;
	},
	getOverlaySelector:function( tObj ) {
		var overlay_selector_id = tObj.transAttrType + tObj.pClip.id;
		js_log( 'f:getOverlaySelector: ' + overlay_selector_id + ' append to: ' + '#videoPlayer_' + tObj.pClip.embed.id );
		// make sure overlay_selector_id not already here:	
		if ( $j( '#' + overlay_selector_id ).length == 0  ) {
			$j( '#videoPlayer_' + tObj.pClip.embed.id ).prepend( '' +
				'<div id="' + overlay_selector_id + '" ' +
					'style="position:absolute;top:0px;left:0px;' +
					'height:' + parseInt( tObj.pClip.pp.height ) + 'px;' +
					'width:' + parseInt( tObj.pClip.pp.width ) + 'px;' +
					'z-index:2">' +
				'</div>' );
		}
		return overlay_selector_id;
	},
	doUpdate:function( tObj, percent, callback ) {
		// init the transition if necessary:
		if ( !tObj.overlay_selector_id )
			this.doInitTransition( tObj );
		
		// @@todo we should ensure viability outside of doUpate loop			
		if ( !$j( '#' + tObj.overlay_selector_id ).is( ':visible' ) )
			$j( '#' + tObj.overlay_selector_id ).show();
		
		// do update:
		/*	js_log('doing update for: '+ tObj.pClip.id + 
			' type:' + tObj.transAttrType +
			' t_type:'+ tObj.type +
			' subypte:'+ tObj.subtype  + 
			' percent:' + percent);*/
			
		this[ 'type' ][ tObj.type ][ tObj.subtype ].u( tObj, percent, callback);
	},
	getTransitionIcon:function( type, subtype ) {
		return mv_embed_path + '/skins/common/transition_images/' + type + '_' + subtype + '.png';
	},
	/*
	 * mvTransLib: functional library mapping:
	 */
	type: {
		// Types:
		fade: {
			fadeFromColor: {
				'attr' : ['fadeColor'],
				'init' : function( tObj ) {
					// js_log('f:fadeFromColor: '+tObj.overlay_selector_id +' to color: '+ tObj.fadeColor);
					if ( !tObj.fadeColor )
						js_log( 'missing fadeColor' );
					if ( $j( '#' + tObj.overlay_selector_id ).length == 0 ) {
						js_log( "ERROR can't find: " + tObj.overlay_selector_id );
					}
					// set the initial state
					$j( '#' + tObj.overlay_selector_id ).css( {
						'background-color':tObj.fadeColor,
						'opacity':"1"
					} );
				},
				'u' : function( tObj, percent ) {
					// js_log(':fadeFromColor:update: '+ percent);
					// fade from color (invert the percent)
					var percent = 1 - percent;
					$j( '#' + tObj.overlay_selector_id ).css( {
						"opacity" : percent
					} );
				}
			},
			// corssFade
			crossfade: {
				"attr" : [],
				"init" : function( tObj ) {
					js_log( 'f:crossfade: ' + tObj.overlay_selector_id );
					if ( $j( '#' + tObj.overlay_selector_id ).length == 0 )
						js_log( "ERROR overlay selector not found: " + tObj.overlay_selector_id );
					
					// set the initial state show the zero opacity animation 
					$j( '#' + tObj.overlay_selector_id ).css( { 'opacity':0 } ).show();
				},
				'u':function( tObj, percent ) {
					// Do the relative seek:
					$j( '#' + tObj.overlay_selector_id ).css( {
						"opacity" : percent
					} );
				}
			}
		}
	}
}

/* object to manage embedding html with smil timings 
 *  grabs settings from parent clip 
 */
var transitionObj = function( element ) {
	this.init( element );
};
transitionObj.prototype = {
	supported_attributes : new Array(
		'id',
		'type',
		'subtype',
		'fadeColor',
		'dur'
	),
	transAttrType:null, // transIn or transOut
	overlay_selector_id:null,
	pClip:null,
	timerId:null,
	animation_state:0, // can be 0=unset, 1=running, 2=done 
	interValCount:0, // inter-intervalCount for animating between time updates
	dur:2, // default duration of 2	
	init:function( element ) {
		// load supported attributes:	 
		var _this = this;
		$j.each( this.supported_attributes, function( i, attr ) {
			if ( element.getAttribute( attr ) )
				_this[attr] = element.getAttribute( attr );
		} );
		// @@todo process duration (for now just strip s) per: 
		// http://www.w3.org/TR/SMIL3/smil-timing.html#Timing-ClockValueSyntax
		if ( _this.dur )
			_this.dur = smilParseTime( _this.dur );
	},
	/* 
	 * returns a visual representation of the transition
	 */
	getIconSrc:function( opt ) {
		// @@todo support some arguments 
		return mvTransLib.getTransitionIcon( this.type, this.subtype );
	},
	getDuration:function() {
		return this.dur;
	},
	// returns the values of supported_attributes: 
	getAttributeObj:function() {
		var elmObj = { };
		for ( var i in this.supported_attributes ) {
			var attr = this.supported_attributes[i];
			if ( this[ attr ] )
				elmObj[ attr ] = this[ attr ];
		}
		return elmObj;
	},
	/*
	 * the main animation loop called every MV_ANIMATION_CB_RATE or 34ms ~around 30frames per second~
	 */
	run_transition:function() {
		// js_log('f:run_transition:' + this.interValCount);

		// update the time from the video if native:   
		if ( typeof this.pClip.embed.vid != 'undefined' ) {
			this.interValCount = 0;
			this.pClip.embed.currentTime = this.pClip.embed.vid.currentTime;
		}
		
		// }else{
			// relay on currentTime update grabs (every 250ms or so) (ie for images)
		//	if(this.prev_curtime!=this.pClip.embed.currentTime){	
		//		this.prev_curtime =	this.pClip.embed.currentTime;
		//		this.interValCount=0;
		//	}
		// }		
		
		// start_time =assigned by doSmilActions
		// base_cur_time = pClip.embed.currentTime;
		// dur = assigned by attribute		
		if ( this.animation_state == 0 ) {
			mvTransLib.doInitTransition( this );
			this.animation_state = 1;
		}
		// set percentage include diffrence of currentTime to prev_curTime 
		// ie updated in-between currentTime updates) 

		if ( this.transAttrType == 'transIn' )
			var percentage = ( this.pClip.embed.currentTime +
									( ( this.interValCount * MV_ANIMATION_CB_RATE ) / 1000 )
							) / this.dur ;
				
		if ( this.transAttrType == 'transOut' )
			var percentage = ( this.pClip.embed.currentTime  +
									( ( this.interValCount * MV_ANIMATION_CB_RATE ) / 1000 )
									- ( this.pClip.dur - this.dur )
							) / this.dur ;
		
		/*js_log('percentage = ct:'+this.pClip.embed.currentTime + ' + ic:'+this.interValCount +' * cb:'+MV_ANIMATION_CB_RATE +
			  ' / ' + this.dur + ' = ' + percentage );
		*/
		
		// js_log('cur percentage of transition: '+percentage);
		// update state based on current time + cur_time_offset (for now just use pClip.embed.currentTime)
		mvTransLib.doUpdate( this, percentage );
		
		if ( percentage >= 1 ) {
			js_log( "transition done update with percentage " + percentage );
			this.animation_state = 2;
			clearInterval( this.timerId );
			mvTransLib.doCloseTransition( this )
			return true;
		}
						
		this.interValCount++;
		// setInterval in we are still in running state and user is not using the playhead 
		if ( this.animation_state == 1 ) {
			if ( !this.timerId ) {
				this.timerId = setInterval( 'document.getElementById(\'' + this.pClip.pp.id + '\').' +
							'run_transition(\'' + this.pClip.pp.cur_clip.order + '\',' +
								'\'' + this.transAttrType + '\')',
						 MV_ANIMATION_CB_RATE );
			}
		} else {
			clearInterval( this.timerId );
		}
		return true;
	},
	clone :function() {
		var cObj = new this.constructor();
		for ( var i in this )
			cObj[i] = this[i];
		return cObj;
	}
}

// very limited smile feature set more details soon:  
// region="video_region" transIn="fromGreen" begin="2s"
// http://www.w3.org/TR/2007/WD-SMIL3-20070713/smil-extended-media-object.html#edef-ref
var smilPlaylist = {
	transitions: { },
	doParse:function() {
		var _this = this;
		js_log( 'f:doParse smilPlaylist' );
		// @@todo get/parse meta that we are interested in: 
		var meta_tags = this.data.getElementsByTagName( 'meta' );
		var metaNames = {
			'title':'',
			'interface_url':"",
			'linkback':"",
			'mTitle':"",
			'mTalk':"",
			'mTouchedTime':""
		};
		$j.each( meta_tags, function( i, meta_elm ) {
			// js_log( "on META tag: "+ $j(meta_elm).attr('name') );
			if ( $j( meta_elm ).attr( 'name' ) in metaNames ) {
				_this[ $j( meta_elm ).attr( 'name' ) ] = $j( meta_elm ).attr( 'content' );
			}
			// Special check for wikiDesc
			if (  $j( meta_elm ).attr( 'name' ) == 'wikiDesc' ) {
				if ( meta_elm.firstChild )
					_this.wikiDesc  = meta_elm.firstChild.nodeValue;
			}
		} );
		// Add transition objects: 
		var transition_tags = this.data.getElementsByTagName( 'transition' );
		$j.each( transition_tags, function( i, trans_elm ) {
			if ( $j( trans_elm ).attr( "id" ) ) {
				_this.transitions[ $j( trans_elm ).attr( "id" )] = new transitionObj( trans_elm );
			} else {
				js_log( 'skipping transition: (missing id) ' + trans_elm );
			}
		} );
		js_log( 'loaded transitions:' + _this.transitions.length );
		
		// Add seq (latter we will have support more than one seq tag) / more than one "track" 
		var seq_tags = this.data.getElementsByTagName( 'seq' );
		$j.each( seq_tags, function( i, seq_elm ) {
			var inx = 0;
			// get all the clips for the given seq:
			$j.each( seq_elm.childNodes, function( i, mediaElement ) {
				// ~complex~ @@todo to handle a lot like "switch" "region" etc
				// js_log('process: ' + mediaElemnt.tagName); 
				if ( typeof mediaElement.tagName != 'undefined' ) {
					if ( _this.tryAddMedia( mediaElement, inx ) ) {
						inx++;
					}
				}
			} );
		} );
		js_log( "done proc seq tags" );
		return true;
	},
	tryAddMediaObj:function( mConfig, order, track_id ) {
		js_log( 'tryAddMediaObj::' );
		var mediaElement = document.createElement( 'div' );
		for ( var i = 0; i < mv_smil_ref_supported_attributes.length; i++ ) {
			var attr = 	mv_smil_ref_supported_attributes[i];
			if ( mConfig[attr] )
				$j( mediaElement ).attr( attr, mConfig[attr] );
		}
		this.tryAddMedia( mediaElement, order, track_id );
	},
	tryAddMedia:function( mediaElement, order, track_id ) {
		js_log( 'SMIL:tryAddMedia:' + mediaElement );

		var _this = this;
		// Set up basic mvSMILClip send it the mediaElemnt & mvClip init: 
		var clipObj = { };
		var cConfig = {
			"id" : 'p_' + _this.id + '_c_' + order,
			"pp" : this, // set the parent playlist object pointer
			"order" : order
		};
		var clipObj = new mvSMILClip( mediaElement, cConfig );
		
		// set optional params track										 
		if ( typeof track_id != 'undefined' )
			clipObj["track_id"]	= track_id;
			 
		
		if ( clipObj ) {
			// set up embed:						
			clipObj.setUpEmbedObj();
			// inhreit embedObject (only called on "new media" 
			clipObj.embed.init_with_sources_loaded();
			// add clip to track: 
			this.addCliptoTrack( clipObj , order );
			
			return true;
		}
		return false;
	}
}
// http://www.w3.org/TR/2007/WD-SMIL3-20070713/smil-extended-media-object.html#smilMediaNS-BasicMedia
// and added resource description elements
// @@ supporting the "ID" attribute turns out to be kind of tricky since we use it internally 
// (for now don't include) 
var mv_smil_ref_supported_attributes = new Array(
		'src',
		'type',
		'region',
		'transIn',
		'transOut',
		'fill',
		'dur',
		'title',
		// some custom attributes:
		'uri',
		'durationHint',
		'poster'
);
/* extension to mvClip to support smil properties */
var mvSMILClip = function( sClipElm, mvClipInit ) {
	return this.init( sClipElm, mvClipInit );
}
// all the overwritten and new methods for SMIL extension of mv_embed
mvSMILClip.prototype = {
	instanceOf:'mvSMILClip',
	params : { }, // support param as child of ref clips per SMIL spec  
	init:function( sClipElm, mvClipInit ) {
		_this = this;
		this.params	= { };
		// make new mvCLip with ClipInit vals  
		var myMvClip = new mvClip( mvClipInit );
		// inherit mvClip		
		for ( var method in myMvClip ) {
			if ( typeof this[method] != 'undefined' ) {
				this['parent_' + method] = myMvClip[method];
			} else {
				this[method] = myMvClip[method];
			}
		}
		
		// get supported media attr init non-set		
		for ( var i = 0; i < mv_smil_ref_supported_attributes.length; i++ ) {
			var attr = 	mv_smil_ref_supported_attributes[i];
			if ( $j( sClipElm ).attr( attr ) ) {				
				_this[attr] = $j( sClipElm ).attr( attr );
			}
		}
		this['tagName'] = sClipElm.tagName;
		
		// Fix url paths (if needed) 
		if( _this['src'] && _this.src.indexOf('/') != 0 && _this.src.indexOf('://') === -1)
		 	_this['src'] = mw.absoluteUrl(  _this['src'], mvClipInit.pp.getSrc() );				
		
		if ( sClipElm.firstChild ) {
			this['wholeText'] = sClipElm.firstChild.nodeValue;
			js_log( "SET wholeText for: " + this['tagName'] + ' ' + this['wholeText'] );
		}
		// debugger;
		// mv_embed specific property: 
		if ( $j( sClipElm ).attr( 'poster' ) )
			this['img'] = $j( sClipElm ).attr( 'poster' );
		
		// lookup and assign copies of transitions 
		// (since transition needs to hold some per-instance state info)		
		if ( this.transIn && this.pp.transitions[ this.transIn ] ) {
			this.transIn = this.pp.transitions[ this.transIn ]. clone ();
			this.transIn.pClip = _this;
			this.transIn.transAttrType = 'transIn';
		}
		
		if ( this.transOut && this.pp.transitions[ this.transOut ] ) {
			this.transOut = this.pp.transitions[ this.transOut ]. clone ();
			this.transOut.pClip = _this;
			this.transOut.transAttrType = 'transOut';
		}
		// parse duration / begin times: 
		if ( this.dur )
			this.dur = smilParseTime( this.dur );
			
		// parse the media duration hint ( the source media length) 
		if ( this.durationHint )
			this.durationHint = smilParseTime( this.durationHint );
		
		// conform type to vido/ogg:
		if ( this.type == 'application/ogg' )
			this.type = 'video/ogg'; // conform to 'video/ogg' type

		// if unset type and we have innerHTML assume text/html type		
		if ( !this.type  && this.wholeText ) {
			this.type = 'text/html';
		}
		// Also grab any child param elements if present: 
		if ( sClipElm.getElementsByTagName( 'param' )[0] ) {
			for ( var i = 0; i < sClipElm.getElementsByTagName( 'param' ).length; i++ ) {
				this.params[ sClipElm.getElementsByTagName( 'param' )[i].getAttribute( "name" ) ] =
						 sClipElm.getElementsByTagName( 'param' )[i].firstChild.nodeValue;
			}
		}
		return this;
	},
	/**
	* Returns the values of supported_attributes:
	*/ 
	getAttributeObj:function() {
		var elmObj = { };
		for ( var i = 0; i < mv_smil_ref_supported_attributes.length; i++ ) {
			var attr = mv_smil_ref_supported_attributes[i];
			if ( this[attr] )
				elmObj[ attr ] = this[attr];
		}
		return elmObj;
	},
	/*
	 * getDuration
	 * @returns duration in int
	 */
	getDuration:function() {
		// check for smil dur: 
		if ( this.dur )
			return this.dur;
		return this.embed.getDuration();
	},
	// gets the duration of the clip subracting transitions
	getSoloDuration:function() {
		var fulldur = this.getDuration();
		// see if we need to subtract from time eating transitions (transOut)
		if ( this.transOut )
			fulldur -= this.transOut.getDuration();

		// js_log("getSoloDuration:: td: " + this.getDuration() + ' sd:' + fulldur);
		return fulldur;
	},
	// gets the duration of the original media asset (usefull for bounding setting of in-out-points)
	getSourceDuration:function() {
		if ( this.durationHint )
			return this.durationHint;
		// if we have no source duration just return the media dur: 
		return this.getDuration();
	}
}
/*
 * takes an input 
 * @time_str input time string 
 * returns time in seconds 
 * 
 * @@todo process duration (for now just srip s) per: 
 * http://www.w3.org/TR/SMIL3/smil-timing.html#Timing-ClockValueSyntax
 * (probably have to use a Time object to fully support the smil spec
 */
function smilParseTime( time_str ) {
	time_str = time_str + '';
	// first check for hh:mm:ss time: 
	if ( time_str.split( ':' ).length == 3 ) {
		return npt2seconds( time_str );
	} else {
		// assume 34s secconds representation 
		return parseInt( time_str.replace( 's', '' ) );
	}
}
// stores a list pointers to active clips (maybe this should just be a property of clips (but results in lots of seeks) 
var activeClipList = function() {
	return this.init();
}
activeClipList.prototype = {
	init:function() {
		this.clipList = new Array();
	},
	add:function( clip ) {
		// make sure the clip is not already active: 
		for ( var i = 0; i < this.clipList.lenght; i++ ) {
			var active_clip = this.clipList[i];
			if ( clip.id == active_clip.id ) // clip already active: 
				return false;
		}
		this.clipList.push( clip );
		return true;
	},
	remove:function( clip ) {
		for ( var i = 0; i < this.clipList.length; i++ ) {
			var active_clip = this.clipList[i];
			if ( clip.id == active_clip.id ) {
				this.clipList.splice( i, 1 );
				return true;
			}
		}
		return false;
	},
	getClipList:function() {
		return this.clipList;
	}
}
 var trackObj = function( iObj ) {
	 return this.init( iObj );
 }
 var supported_track_attr =
trackObj.prototype = {
	// should be something like "seq" per SMIL spec
	// http://www.w3.org/TR/SMIL3/smil-timing.html#edef-seq
	// but we don't really support anywhere near the full concept of seq containers yet either
	supported_attributes: new Array(
		'title',
		'desc',
		'inx'
	 ),
	disp_mode:'timeline_thumb',
	init : function( iObj ) {
		if ( !iObj )
			iObj = { };
		// make sure clips is new: 
		this.clips = new Array();
				
		var _this = this;
		$j.each( this.supported_attributes, function( i, attr ) {
			if ( iObj[attr] )
				_this[attr] = iObj[attr];
		} );
	},
	// returns the values of supported_attributes: 
	getAttributeObj:function() {
		var elmObj = { };
		for ( var i in this.supported_attributes ) {
			var attr = this.supported_attributes[i];
			if ( this[attr] )
				elmObj[ attr ] = this[attr];
		}
		return elmObj;
	},
	addClip:function( clipObj, pos ) {
		js_log( 'pl_Track: AddClip at:' + pos + ' clen: ' + this.clips.length );
		if ( typeof pos == 'undefined' )
			pos = this.clips.length;
		// get everything after pos	
		this.clips.splice( pos, 0, clipObj );
		// keep the clip order values accurate:
		this.reOrderClips();
		js_log( "did add now cLen: " + this.clips.length );
	},
	getClip:function( inx ) {
		if ( !this.clips[inx] )
			return false;
		return this.clips[inx];
	},
	reOrderClips:function() {
		for ( var k in this.clips ) {
			this.clips[k].order = k;
		}
	},
	getClipCount:function() {
		return this.clips.length;
	},
	inheritEmbedObj: function() {
		$j.each( this.clips, function( i, clip ) {
			clip.embed.inheritEmbedObj();
		} );
	}
};
	
/* utility functions 
 * (could be combined with other stuff) 
*/
function getAbsolutePos( objectId ) {
	// Get an object left position from the upper left viewport corner
	o = document.getElementById( objectId );
	oLeft = o.offsetLeft;			// Get left position from the parent object	
	while ( o.offsetParent != null ) {   // Parse the parent hierarchy up to the document element
		oParent = o.offsetParent	// Get parent object reference
		oLeft += oParent.offsetLeft // Add parent left position
		o = oParent
	}
	o = document.getElementById( objectId );
	oTop = o.offsetTop;
	while ( o.offsetParent != null ) { // Parse the parent hierarchy up to the document element
		oParent = o.offsetParent  // Get parent object reference
		oTop += oParent.offsetTop // Add parent top position
		o = oParent
	}
	return { x:oLeft, y:oTop };
}
