var kplayerEmbed = {
	instanceOf:'kflashEmbed',
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
		// setTimeout('$j(\'#' + this.id + '\').get(0).postEmbedJS()', 2000);
		js_log( "return embed html" );
		return this.wrapEmebedContainer( embed_code );
	},
	getEmbedObj:function() {
		var player_path = mv_embed_path + 'binPlayers/kaltura-player/player.swf';
		return '<object id="' + this.pid + '" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" ' +
				'width="' + this.width + '" height="' + this.height + '">' +
					'<param name="movie" value="' + player_path + '" />' +
					'<param name="allowfullscreen" value="true" />' +
					'<param name="allowscriptaccess" value="always" />' +
					'<param name="flashvars" value="file=video.flv&image=preview.jpg" />' +
				'<embed ' +
					'type="application/x-shockwave-flash" ' +
					'id="' + this.pid + '" ' +
					'src="' + player_path + '" ' +
					'width="' + this.width + '" ' +
					'height="' + this.height + '" ' +
					'allowscriptaccess="always" ' +
					'allowfullscreen="true" ' +
					'flashvars="autostart=true&file=' + escape(  this.getSrc() ) + '" ' +
				'/>' +
			'</object>';
		/*return  '<object id="'+this.pid+'" type="application/x-shockwave-flash"'+ 
				'allowScriptAccess="always" allowNetworking="all" allowFullScreen="true" '+
				'height="'+  parseInt(this.height) + '" ' +
				'width="' + parseInt(this.width) + '" ' +
				'data="http://www.kaltura.com/index.php/kwidget/wid/_309/uiconf_id/1000106">'+
					'<param name="allowScriptAccess" value="always"/>'+
					'<param name="allowNetworking" value="all"/>'+
					'<param name="allowFullScreen" value="true"/>'+
					'<param name="bgcolor" value="#000000"/>'+
					'<param name="movie" value="http://www.kaltura.com/index.php/kwidget/wid/_0/uiconf_id/1000106"/>'+
					'<param name="flashVars" value="emptyF=onKdpEmpty&readyF=onKdpReady&'+
						//set the url: 
						'entryId=' + escape(  this.getSrc() ) + '"/>'+
					'<param name="wmode" value="opaque"/>'+
				'</object>';	*/
	},
	postEmbedJS:function() {
		this.monitor();
	},
	pause:function() {
		this.stop();
	},
	monitor:function() {
		// this.parent_monitor();	
	}
}

function onKdpReady( playerId ) {
 	 js_log( "IN THEORY PLAYER IS READY" );
 	 /*
	 window.myKdp=get(playerId);
	 get("Player_State").innerHTML="<br>&nbsp; READY (Id=" + playerId + ")";
	 get("nowPlaying").innerHTML=(myKdp.evaluate('{entryId}'));
	 getDuration();
	 attachKdpEvents();
	 addKdpListners();
	 */
 }