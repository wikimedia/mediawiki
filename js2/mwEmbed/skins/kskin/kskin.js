/*
* skin js allows you to override contrlBuilder html/class output
*/

var kskinConfig = {
	pClass: 'k-player',
	// display time progress
	long_time_disp: false,
	body_options: false,
	volume_layout: 'horizontal',
	components: {
		'play-btn-large' : {
			'h' : 55
		},
		'options': {
			'w':50,
			'o':function() {
				return '<div class="ui-state-default ui-corner-bl rButton k-options" title="' + gM( 'mwe-player_options' ) + '" >' +
							'<span>' + gM( 'mwe-menu_btn' ) + '</span>' +
						'</div>'
			}
		},
		'time_display': {
			'w':70
		},
		'mv_embedded_options': {
			'w':0,
			'o':function( ctrlObj ) {
				var embedObj = ctrlObj.embedObj;
				var o = '' +
				'<div class="k-menu ui-widget-content" ' +
					'style="width:' + embedObj.playerPixelWidth() + 'px; height:' + embedObj.playerPixelHeight() + 'px;">' +
						'<ul class="k-menu-bar">';
							// output menu item containers: 
							for ( i = 0; i < ctrlObj.menu_items.length; i++ ) {
								var mk = ctrlObj.menu_items[i];
								o += '<li class="k-' + mk + '-btn" rel="' + mk + '">' +
										'<a href="#" title="' + gM( 'mwe-' + mk ) + '">' + gM( 'mwe-' + mk ) + '</a></li>';
							}
						o += '</ul>' +
						// We have to subtract the width of the k-menu-bar
						'<div class="k-menu-screens" style="width:' + ( embedObj.playerPixelWidth() - 75 ) +
							'px; height:' + ( embedObj.playerPixelHeight() - ctrlBuilder.height ) + 'px;">';
						
						// Output menu item containers: 
						for ( i = 0; i < ctrlObj.menu_items.length; i++ ) {
							o += '<div class="menu-screen menu-' + ctrlObj.menu_items[i] + '"></div>';
						}
						'</div>' +
					'</div>';
				return o;
			}
		}
	},
	addSkinControlHooks: function() {
		var embedObj = this.embedObj;
		var _this = this;
		var $tp = $j( '#' + embedObj.id );
		
		// Adds options and bindings: (we do this onClick )  
		var addMvOptions = function() {
			if ( $j( '#' + embedObj.id + ' .k-menu' ).length != 0 )
				return false;
				
			$j( '#' + embedObj.id + ' .' + _this.pClass ).prepend(
				_this.components['mv_embedded_options'].o( $tp.get( 0 ).ctrlBuilder )
			);
			
			// By default its hidden:
   			$tp.find( '.k-menu' ).hide();
   			
   			// Output menu-items: 
   			for ( i = 0; i < _this.menu_items.length ; i++ ) {
		        $tp.find( '.k-' +  _this.menu_items[i] + '-btn' ).click( function() {
		        	var mk = $j( this ).attr( 'rel' );
		        	$target = $j( '#' + embedObj.id  + ' .menu-' + mk ).hide();
		        	// Generate the menu html not already done:
		        	if ( $target.children().length == 0 ) {
						// call the function show{Menuitem} with target:	        		
						embedObj['show' + mk.charAt( 0 ).toUpperCase() + mk.substring( 1 )](
							$j( '#' + embedObj.id + ' .menu-' + mk )
						);
		        	}
		        	// Slide out the others 
		        	 $j( '#' + embedObj.id  + ' .menu-screen' ).hide();
		        	 $target.fadeIn( "fast" );
					// don't follow the # link								
		            return false;
				} );
   			}
		}
		 		
   		// Options menu display:			
   		$tp.find( '.k-options' ).click( function() {
			if ( $j( '#' + embedObj.id + ' .k-menu' ).length == 0 ) {
	   			// stop the player if it does not support overlays: 
				if ( !embedObj.supports['overlays'] )
					$tp.get( 0 ).stop();
			// Add the options       				
				addMvOptions();
			}
	   		// Set up the text and menu:       			 					
			var $ktxt = $j( this );
			var $kmenu = $tp.find( '.k-menu' );
			if ( $kmenu.is( ':visible' ) ) {
				$kmenu.fadeOut( "fast", function() {
					$ktxt.find( 'span' ).html ( gM( 'mwe-menu_btn' ) );
				} );
				$tp.find( '.play-btn-large' ).fadeIn( 'fast' );
			} else {
				$kmenu.fadeIn( "fast", function() {
					$ktxt.find( 'span' ).html ( gM( 'mwe-close_btn' ) );
				} );
				$tp.find( '.play-btn-large' ).fadeOut( 'fast' );
			}
		} );
	
	}
}