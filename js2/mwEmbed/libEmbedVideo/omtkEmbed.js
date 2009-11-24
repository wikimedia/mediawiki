var omtkEmbed = {
	instanceOf:'omtkEmbed',
	supports: {
		'pause':true,
			'time_display':true
	},
	getEmbedHTML : function () {
		var embed_code =  this.getEmbedObj();
		// Need omtk to fire an onReady event.
		setTimeout( '$j(\'#' + this.id + '\').get(0).postEmbedJS()', 2000 );
		return this.wrapEmebedContainer( embed_code );
	},
	getEmbedObj:function() {
		var player_path = mv_embed_path + 'libEmbedVideo/binPlayers/omtk-fx/omtkp.swf';
		// player_path = 'omtkp.swf';
		js_log( "player path: " + player_path );
		return  '<object id="' + this.pid + '" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="1" height="1">' +
					'<param name="movie" value="' + player_path + '" />' + "\n" +
					'<!--[if !IE]>-->' + "\n" +
						'<object id="' + this.pid + '_ie" type="application/x-shockwave-flash" data="' + player_path + '" width="1" height="1">' + "\n" +
					'<!--<![endif]-->' + "\n" +
						  '<p>Error with Display of Flash Plugin</p>' + "\n" +
					'<!--[if !IE]>-->' + "\n" +
						'</object>' + "\n" +
					'<!--<![endif]-->' + "\n" +
				  '</object>';
	},
	postEmbedJS:function() {
		this.getOMTK();
		// play the url: 
		js_log( "play: pid:" + this.pid + ' src:' + this.src );
				
		this.omtk.play( this.src );
		
		this.monitor();
		// $j('#omtk_player').get(0).play(this.src);
		// $j('#'+this.pid).get(0).play( this.src );
	},
	pause:function() {
		this.stop();
	},
	monitor:function() {
		if ( this.omtk.getPosition )
			this.currentTime = this.omtk.getPosition() / 1000;
		
		this.parent_monitor();
	},
	getOMTK : function () {
		this.omtk = $j( '#' + this.pid ).get( 0 );
		if ( !this.omtk.play )
			this.omtk = $j( '#' + this.pid + '_ie' ).get( 0 );
		
		if ( this.omtk.play ) {
			// js_log('omtk obj is missing .play (probably not omtk obj)');
		}
	},
}

function OMTK_P_complete() {
	js_log( 'OMTK_P_complete' );
}

function OMTK_P_metadataUpdate() {
	js_log( 'OMTK_P_metadataUpdate' );
}
