/*
 * Handles driving the firefogg render system 
*/
var mvFirefoggRender = function( iObj ) {
	return this.init( iObj );
};
var default_render_options = {
	"videoQuality" : 10,
	"framerate"	: 30
}
var default_FirefoggRender_options = {
	start_time:0,
	// if we should save to disk (if false setup upload stuff below) 
	save_to_disk:true
}
// set up the mvPlaylist object
mvFirefoggRender.prototype = {
	// default empty render options: 
	renderOptions: { },
	continue_rendering:false,
	init:function( iObj ) {
		var _this = this;
		
		// grab the mvFirefogg object to do basic tests
		this.myFogg = new mvFirefogg( {
			'only_fogg':true
		} );
		
		// check for firefogg:
		if ( this.myFogg.firefoggCheck() ) {
			this.enabled = true;
		} else {
			this.enabled = false;
			return this;
		}
		
		// set up local fogg pointer: 
		this.fogg = this.myFogg.fogg;
		
		// setup player instance
		this.player = $j( iObj.player_target ).get( 0 );
		this.player_target = iObj.player_target;
		
		// Extend the render options with any provided details
		if( iObj['render_options'] )
			$j.extend(this.renderOptions, iObj['render_options']);
		
		// If no height width provided use target DOM width/height
		if( !this.renderOptions.width && !this.renderOptions.height ){
			this.renderOptions.width = $j(this.player_target).width();
			this.renderOptions.height = $j(this.player_target).height();	
		}
		
		
		// Setup the application options (with defaults) 
		for ( var i in default_FirefoggRender_options ) {
			if ( iObj[ i ] ) {
				this[ i ] = iObj[ i ];
			} else {
				this[ i ] = default_FirefoggRender_options[i];
			}
		}
		// Should be exteranlly controlled		
		if ( iObj.target_startRender ) {
			$j( iObj.target_startRender ).click( function() {
				js_log( "Start render" );
				_this.startRender();
			} )
			this.target_startRender = iObj.target_startRender;
		}
		if ( iObj.target_stopRender ) {
			$j( iObj.target_stopRender ).click( function() {
				_this.stopRender();
			} )
			this.target_stopRender = iObj.target_stopRender;
		}
		if ( iObj.target_timeStatus ) {
			this.target_timeStatus = iObj.target_timeStatus;
		}
	},
	startRender:function() {
		var _this = this;
		var t = this.start_time;
		// get the interval from renderOptions framerate
		var interval =  1 / this.renderOptions.framerate
		
		// issue a load request on the player:
		//this.player.load();
		
		// init the Render
		this.fogg.initRender(  JSON.stringify( _this.renderOptions ), 'foggRender' );
		
		// add audio if we had any:

		// request a target (should support rendering to temp location too) 
		//this.fogg.saveVideoAs();
		
		// set the continue rendering flag to true:
		this.continue_rendering = true;
		
		// internal function to hanndle updates:				
		var doNextFrame = function() {
			$j( _this.target_timeStatus ).val( " on " + ( Math.round( t * 10 ) / 10 ) + " of " +
				( Math.round( _this.player.getDuration() * 10 ) / 10 ) );
			_this.player.setCurrentTime( t, function() {								
				//_this.fogg.addFrame( $j( _this.player_target ).attr( 'id' ) );
				t += interval;				
				if ( t >= _this.player.getDuration() ) {
					_this.doFinalRender();
				} else {
					if ( _this.continue_rendering ) {
						doNextFrame();
					} else {
						js_log('done with render');
						// else quit:
						//_this.doFinalRender();
					}
				}
			} );
		}
		doNextFrame();
	},
	stopRender:function() {
		this.continue_rendering = false;
	},
	doFinalRender:function() {
		$j( this.target_timeStatus ).val( "doing final render" );
		this.fogg.render();
		this.updateStatus();
	},
	updateStatus:function() {
		var _this = this;
		var doUpdateStatus = function() {
			var rstatus = _this.fogg.renderstatus()
		    $j( _this.target_timeStatus ).val( rstatus );
		    if ( rstatus != 'done' && rstatus != 'rendering failed' ) {
		        setTimeout( doUpdateStatus, 100 );
		    } else {
		        $j( _this.target_startRender ).attr( "disabled", false );
		    }
		}
		doUpdateStatus();
	}

	
}