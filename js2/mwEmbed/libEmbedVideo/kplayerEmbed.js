var kplayerEmbed = {
	instanceOf:'kplayerEmbed',
	supports: {
		'play_head':true,
		'pause':true,
		'stop':true,
		'time_display':true,
		'volume_control':true,
		'overlay':false,
		'fullscreen':false
	},
	getEmbedHTML : function () {
		var embed_code =  this.getEmbedObj();
		alert
		var _this = this;
		setTimeout(function(){
			_this.postEmbedJS();
		}, 50);
		js_log( "return embed html" );
		return this.wrapEmebedContainer( embed_code );
	},
	getEmbedObj:function() {	
		var player_path = mv_embed_path + 'libEmbedVideo/binPlayers/kaltura-player';
		return '<object width="' + this.width + '" height="' + this.height + '" '+ 
			 'data="' + player_path + '/wrapper.swf" allowfullscreen="true" '+ 
			 'allownetworking="all" allowscriptaccess="always" '+
			 'type="application/x-shockwave-flash" '+ 
			 'id="' + this.pid + '" name="' + this.pid + '">'+
				'<param value="always" name="allowScriptAccess"/>'+
				'<param value="all" name="allowNetworking"/>'+
			  	'<param value="true" name="allowFullScreen"/>'+
			  	'<param value="#000000" name="bgcolor"/>'+
			  	'<param value="wrapper.swf" name="movie"/>'+
			  	'<param value="' + 
			  		'kdpUrl=' + player_path + '/kdp.swf' +
			  		'&ks=dummy&partner_id=0&subp_id=0' +
			  		'&uid=0&emptyF=onKdpEmpty&readyF=onKdpReady' +
			  		'" ' + 
			  		'name="flashVars"/>'+
			  '<param value="opaque" name="wmode"/>'+
			 '</object>';		
	},
	postEmbedJS:function() {
		var _this = this;
		this.getKDP();	
		//alert( 	this.kdp );
		if( this.kdp && this.kdp.insertMedia){
			// Add KDP listeners
			
			//this.kdp.addJsListener("doPlay","kdpDoOnPlay");
			//this.kdp.addJsListener("doStop","kdpDoOnStop");
			//myKdp.addJsListener("fastForward","kdpDoOnFF");
						
			_this.bindKdpFunc( 'doPause', 'kdpPause' );
			_this.bindKdpFunc( 'doPlay', 'play' );
			_this.bindKdpFunc( 'playerPlayEnd', 'onClipDone' );
						
			// KDP player likes an absolute url for the src:
			var src = mw.absoluteUrl( _this.getSrc() );
			js_log('play src: ' + src);
			// Insert the src:	
			this.kdp.insertMedia("-1", src, 'true' );			
			this.kdp.dispatchKdpEvent('doPlay');
			
			// Start the monitor
			this.monitor();
		}else{
			js_log('insert media: not defiend' + typeof this.kdp.insertMedia );
			setTimeout( function(){
				_this.postEmbedJS();
			}, 25);
		}		
	},	
	/**
	* bindKdpFunc
	* 
	* @param {String} flash binding name
	* @param {String} function callback name
	*/
	bindKdpFunc:function( bName, fName ){
		var cbid = fName + '_cb_' + this.id.replace(' ', '_');
		eval( 'window[ \'' + cbid +'\' ] = function(){$j(\'#' + this.id + '\').get(0).'+ fName +'();}' );
		this.kdp.addJsListener( bName , cbid);
	},
	kdpPause:function(){		
		this.parent_pause();
	},
	play:function() {
		if( this.kdp && this.kdp.dispatchKdpEvent )
			this.kdp.dispatchKdpEvent('doPlay');
		this.parent_play();
	},
	pause:function() {
		this.kdp.dispatchKdpEvent('doPause');
		this.parent_pause();
	},
	doSeek:function( prec ){
		var _this = this;
		if( this.kdp ){
			var seek_time = prec * this.getDuration(); 
			this.kdp.dispatchKdpEvent('doSeek',  seek_time);
			// Kdp is missing seek done callback
			setTimeout(function(){
				_this.seeking= false;
			},500);
		}
		this.monitor();
	},
	updateVolumen:function( perc ) {
		if( this.kdp && this.kdp.dispatchKdpEvent )
			this.kdp.dispatchKdpEvent('volumeChange', perc);
	},
	monitor:function() {	
		if( this.kdp && this.kdp.getMediaSeekTime ){
			this.currentTime = this.kdp.getMediaSeekTime();
		}
		this.parent_monitor();
	},
	// get the embed fla object
	getKDP: function () {
		this.kdp = document.getElementById( this.pid );
	}
}

function kdpDoOnPause( player ){
	var cat = player
	debugger;
}

function onKdpReady( playerId ) {
 	 js_log( "IN THEORY PLAYER IS READY:" + playerId);
 	 /*
	 window.myKdp=get(playerId);
	 get("Player_State").innerHTML="<br>&nbsp; READY (Id=" + playerId + ")";
	 get("nowPlaying").innerHTML=(myKdp.evaluate('{entryId}'));
	 getDuration();
	 attachKdpEvents();
	 addKdpListners();
	 */
}
