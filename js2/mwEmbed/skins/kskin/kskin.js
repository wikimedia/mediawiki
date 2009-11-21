/*
* skin js allows you to override contrlBuilder html/class output
*/

loadGM( {
	"mwe-credit-title" : "Title: $1"
} );

var kskinConfig = {
	pClass: 'k-player',
	// display time progress
	long_time_disp: false,
	body_options: false,
	volume_layout: 'horizontal',
	menu_items:[
		'playerselect',
		'download',
		'share',
		'credits',
	],
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
						_this.showMenuItem(	mk );        								
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
	   			// Stop the player if it does not support overlays:
				if ( !embedObj.supports['overlay'] )
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
	
	}, 
	showMenuItem:function( menu_item ) {
		//handle special k-skin specific display; 
		if( menu_item == 'credits'){
			this.showCredits(); 
		}else{
			//call the base embedObj "show{Item}"
			this.embedObj['show' + menu_item.charAt( 0 ).toUpperCase() + menu_item.substring( 1 )](
				$j( '#' + this.embedObj.id + ' .menu-' + menu_item )
			);
		}
	},	
	// Do the credit screen (presently specific to kaltura skin:)  
	showCredits:function() {
		//set up the shortcuts:	
		embedObj = this.embedObj;
		var _this = this;	
		$target = $j( '#' + embedObj.id + ' .menu-credits' );
		$target.html( '<h2>' + gM( 'mwe-credits' ) + '</h2>'  +
			'<div class="credits_box ui-corner-all">' +
				mv_get_loading_img() + 
			'</div>'								
		);
		
		if( mw.conf.k_attribution == true ){
			$target.append( 
				$j('<div/>').addClass( 'k-attribution')
			);
		}
		
		if( !embedObj.wikiTitleKey ){
			$target.find('.credits_box').text(
				'Error: no title key to grab credits with' 
			);
			return ;
		}		
		// Do the api request to populate the credits via the wikiTitleKey ( tied to "commons" )
		var reqObj = {
			'action' : 'query',
			// Normalize the File NS (ie sometimes its present in wikiTitleKey other times not
			'titles' : 'File:' + embedObj.wikiTitleKey.replace(/File:|Image:/, '' ),
		    'prop' : 'revisions',
		    'rvprop' : 'content'
		};
		var req_categories = new Array();
	    do_api_req( {
	    	'url'	: mw.commons_api_url,
			'data'	: reqObj			
	    }, function( data ) {
			if( !data || !data.query || !data.query.pages ){
				$target.find('.credits_box').text(
					'Error: title key: ' + embedObj.wikiTitleKey + ' not found' 
				);
				return false;
			}
			var pages = data.query.pages;			
			for(var i in pages){
				page = pages[ i ];
				if( page[ 'revisions' ] && page[ 'revisions' ][0]['*'] ){
					$target.find('.credits_box').html(
						_this.doCreditLineFromWikiText( page[ 'revisions' ][0]['*'] )
					);
				}
			}
	    } );
	},
	doCreditLineFromWikiText:function ( wikiText ){
		var embedObj = this.embedObj;
		
		// Get the title str 
		var titleStr = embedObj.wikiTitleKey.replace(/_/g, ' ');
		var titleLink = 'http://commons.wikimedia.org/wiki/File:' + embedObj.wikiTitleKey;
		
		// @@FIXME Do a quick check for source line:
		return $j( '<div/>' ).addClass( 'creditline' )
			.append(
				$j('<a/>').attr({
					'href' : titleLink,
					'title' :  titleStr
				}).html( 
					$j('<img/>').attr( {
						'border': 0, 
						'src' : embedObj.thumbnail, 
					} )
				)
			)
			.append(			
				$j('<span>').html( 
					gM( 'mwe-credit-title' ,  
						// We use a div container to easialy get at the built out link
						$j('<div>').html( 
							$j('<a/>').attr({
								'href' : titleLink,
								'title' :  titleStr
							}).text( titleStr )
						).html()
					)
				)
			);
	}
}