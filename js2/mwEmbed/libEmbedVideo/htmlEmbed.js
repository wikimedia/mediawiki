/*
 * used to embed HTML as a movie clip
 * for use with mv_playlist SMIL additions
 * (we make assumptions about this.pc (parent clip) being available)
 */
var pcHtmlEmbedDefaults = {
	'dur':4 // default duration of 4 seconds
}

var htmlEmbed = {
	supports: {
		'play_head':true,
		'pause':true,
		'fullscreen':false,
		'time_display':true,
		'volume_control':true,

		'overlays':true,
		'playlist_swap_loader':true // if the object supports playlist functions
	},
	ready_to_play:true,
	pauseTime:0,
	currentTime:0,
	start_offset:0,
	monitorTimerId:false,
	play:function() {
		// call the parent
		this.parent_play();

		js_log( 'f:play: htmlEmbedWrapper' );
		var ct = new Date();
		this.clockStartTime = ct.getTime();

		// Start up monitor:
		this.monitor();
	},
	stop:function() {
		this.currentTime = 0;
		this.pause();
		// window.clearInterval( this.monitorTimerId );
	},
	pause:function() {
		js_log( 'f:pause: htmlEmbedWrapper' );
		var ct = new Date();
		this.pauseTime = this.currentTime;
		js_log( 'pause time: ' + this.pauseTime );
		
		window.clearInterval( this.monitorTimerId );
	},
	doSeek:function( perc ){
		this.pauseTime = perc * this.getDuration();
		this.play();
	},
	setCurrentTime:function( perc, callback ){
		this.pauseTime = perc * this.getDuration();
		if( callback )
			callback();
	},
	// Monitor just needs to keep track of time 
	monitor:function() {
		//js_log('html:monitor: '+ this.currentTime);		
		var ct = new Date();
		this.currentTime = ( ( ct.getTime() - this.clockStartTime ) / 1000 ) + this.pauseTime;
		var ct = new Date();
		// js_log('mvPlayList:monitor trueTime: '+ this.currentTime);
		
		// Once currentTime is updated call parent_monitor
		this.parent_monitor();
	},
	// set up minimal media_element emulation:	 
	media_element: {
		autoSelectSource:function() {
			return true;
		},
		selectedPlayer: {
			library : "html"
		},
		selected_source: {
			URLTimeEncoding:false
		},
		timedTextSources:function() {
			return false;
		}
	},
	inheritEmbedObj:function() {
		return true;
	},
	renderTimelineThumbnail:function( options ) {
		js_log( "HTMLembed req w, height: " + options.width + ' ' + options.height );
		// generate a scaled down version _that_ we can clone if nessisary
		// add a not vissiable container to the body:
		var do_refresh = ( typeof options['refresh'] != 'undefined' ) ? true:false;

		var thumb_render_id =   this.id + '_thumb_render_' + options.height;
		if ( $j( '#' + thumb_render_id ).length == 0  ||  do_refresh ) {
			// set the font scale down percentage: (kind of arbitrary)
			var scale_perc = options.width / this.pc.pp.width;
			js_log( 'scale_perc:' + options.width + ' / ' + $j( this ).width() + ' = ' + scale_perc );
			// min scale font percent of 70 (overflow is hidden)
			var font_perc  = ( Math.round( scale_perc * 100 ) < 80 ) ? 80 : Math.round( scale_perc * 100 );
			var thumb_class = ( typeof options['thumb_class'] != 'undefined' ) ? options['thumb_class'] : '';
			$j( 'body' ).append( '<div id="' + thumb_render_id + '" style="display:none">' +
									'<div class="' + thumb_class + '" ' +
									'style="width:' + options.width + 'px;height:' + options.height + 'px;" >' +
											this.getThumbnailHTML( {
												'width':  options.width,
												'height': options.height
											} ) +
									'</div>' +
								'</div>'
							  );
			// scale down the fonts:
			$j( '#' + thumb_render_id + ' *' ).filter( 'span,div,p,h,h1,h2,h3,h4,h5,h6' ).css( 'font-size', font_perc + '%' )
			
			// replace out links:
			$j( '#' + thumb_render_id + ' a' ).each( function() {
				$j( this ).replaceWith( "<span>" + $j( this ).html() + "</span>" );
			} );
			
			// scale images that have width or height:
			$j( '#' + thumb_render_id + ' img' ).filter( '[width]' ).each( function() {
				$j( this ).attr( {
						'width': Math.round( $j( this ).attr( 'width' ) * scale_perc ),
						'height': Math.round( $j( this ).attr( 'height' ) * scale_perc )
					 }
				);
			} );
		}
		return $j( '#' + thumb_render_id ).html();
	},
	// nothing to update in static html display: (return a static representation) 
	// @@todo render out a mini text "preview"
	updateThumbTime:function( float_time ) {
		return ;
	},
	getEmbedHTML:function() {
		js_log( 'f:html:getEmbedHTML: ' + this.id );
		// set up the css for our parent div:		 
		$j( this ).css( {
			'width':this.pc.pp.width,
			'height':this.pc.pp.height,
			'overflow':"hidden"
		} );
		// @@todo support more smil animation layout stuff: 

		// wrap output in videoPlayer_ div:
		$j( this ).html( '<div id="videoPlayer_' + this.id + '">' + this.getThumbnailHTML() + '</div>' );
	},
	getThumbnailHTML:function( opt ) {
		var out = '';
		if ( !opt )
			opt = { };
		var height = ( opt.height ) ? opt.height:this.pc.pp.height;
		var width = ( opt.width ) ? opt.width: this.pc.pp.width;
		js_log( '1req ' + opt.height + ' but got: ' + height );
		if ( this.pc.type == 'image/jpeg' ||  this.pc.type == 'image/png' ) {
			js_log( 'should put src: ' + this.pc.src );
			out = '<img style="width:' + width + 'px;height:' + height + 'px" src="' + this.pc.src + '">';
		} else {
			out = this.pc.wholeText;
		}
		// js_log('f:getThumbnailHTML: got thumb: '+out);
		return out;
	},
	doThumbnailHTML:function() {
		js_log( 'htmlEmbed:doThumbnailHTML()' );
		this.getEmbedHTML();
	},
	/* since its just html display get the "embed" right away */
	getHTML:function() {
		js_log( 'htmlEmbed::getHTML() ' + this.id );
		this.getEmbedHTML();
	},
	getDuration:function() {
		if( !this.duration ){
		 	if( this.pc.dur ){
				this.duration = this.pc.dur;
			}else if( pcHtmlEmbedDefaults.dur ){
				this.duration =pcHtmlEmbedDefaults.dur ;
			} 
		}  
		return this.parent_getDuration(); 
	},
	updateVideoTime:function( start_ntp, end_ntp ) {
		// since we don't really have timeline for html elements just take the delta and set it as the duration
		this.pc.dur = npt2seconds( end_ntp ) - npt2seconds( start_ntp );
	},
	// gives a chance to make any nesseary external requests
	// @@todo we can "start loading images" if we want
	on_dom_swap:function() {
		this.loading_external_data = false
		this.ready_to_play = true;
		return ;
	}
};