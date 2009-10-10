/*
 * this file exposes some of the functionality of mwEmbed to wikis
 * that do not yet have js2 enabled
 */
 
var urlparts = getRemoteEmbedPath();
var mwEmbedHostPath = urlparts[0];
var reqAguments = urlparts[1];

addOnloadHook( function(){	
	//only do rewrites if MV_EMBED / js2 is "off"
	if( typeof MV_EMBED_VERSION == 'undefined' ) {
		doPageSpecificRewrite();
	}
});

function doPageSpecificRewrite() {	
	// Add media wizard
	if( wgAction == 'edit' || wgAction == 'submit' ) {
		load_mv_embed( function() {
			importScriptURI( mwEmbedHostPath + '/editPage.js' + reqAguments );
		} );
	}

	// Firefogg integration
	if( wgPageName == "Special:Upload" ){		
		load_mv_embed( function() {
			importScriptURI( mwEmbedHostPath + '/uploadPage.js' + reqAguments );
		} );
	}	
	
	// OggHandler rewrite for view pages:
	var vidIdList = [];
	var divs = document.getElementsByTagName( 'div' );
	for( var i = 0; i < divs.length; i++ ) {
		if( divs[i].id && divs[i].id.substring( 0, 11 ) == 'ogg_player_' ) {
			vidIdList.push( divs[i].getAttribute( "id" ) );			
		}
	}	
	if( vidIdList.length > 0 ) {
		load_mv_embed( function() {
			mvJsLoader.embedVideoCheck( function() {				
				// Do utility rewrite of OggHandler content:
				rewrite_for_OggHandler( vidIdList );
			} );
		} );
	}
}
// will be deprecated in favor of updates to OggHandler
function rewrite_for_OggHandler( vidIdList ){
	for( var i = 0; i < vidIdList.length; i++ ) {
		var vidId = vidIdList[i];			
		// Grab the thumbnail and src of the video
		var pimg = $j( '#' + vidId + ' img' );
		var poster_attr = 'poster = "' + pimg.attr( 'src' ) + '" ';
		var pwidth = pimg.attr( 'width' );
		var pheight = pimg.attr( 'height' );

		var type_attr = '';
		// Check for audio
		if( pwidth == '22' && pheight == '22' ) {
			//set width to parent width:
			pwidth = $j( '#' + vidId ).width();
			pheight = '100';
			type_attr = 'type="audio/ogg"';
			poster_attr = '';
		}

		// Parsed values:
		var src = '';
		var duration = '';
	
		var re = new RegExp( /videoUrl(&quot;:?\s*)*([^&]*)/ );
		src = re.exec( $j( '#'+vidId).html() )[2];

		var re = new RegExp( /length(&quot;:?\s*)*([^&]*)/ );
		duration = re.exec( $j( '#'+vidId).html() )[2];

		var re = new RegExp( /offset(&quot;:?\s*)*([^&]*)/ );
		offset = re.exec( $j( '#'+vidId).html() )[2];
		var offset_attr = offset ? 'startOffset="' + offset + '"' : '';

		// Rewrite that video id (do async calls to avoid locking) 		
		if( src ) {				
			// Replace the top div with the mv_embed based player:
			var vid_html = '<video id="vid_' + i +'" '+
					'src="' + src + '" ' +
					poster_attr + ' ' +
					type_attr + ' ' +
					offset_attr + ' ' +
					'duration="' + duration + '" ' +
					'style="width:' + pwidth + 'px;height:' +
						pheight + 'px;"></video>';
			//set the video tag inner html and update the height				
			$j( '#' + vidId ).html( vid_html )
				.css('height', pheight + 30);
			
		}
		
		rewrite_by_id( 'vid_' + i );		
	}
}

function getRemoteEmbedPath() {
	for( var i = 0; i < document.getElementsByTagName( 'script' ).length; i++ ) {
		var s = document.getElementsByTagName( 'script' )[i];
		if( s.src.indexOf( 'remoteMwEmbed.js' ) != -1 ) {
			var reqStr = '';
			var scriptPath = '';
			if( s.src.indexOf( '?' ) != -1) {
				reqStr = s.src.substr( s.src.indexOf( '?' ) );
				scriptPath = s.src.substr( 0,  s.src.indexOf( '?' ) ).replace( 'remoteMwEmbed.js', '' );
			} else {
				scriptPath = s.src.replace( 'remoteMwEmbed.js', '' )
			}
			// Use the external_media_wizard path:
			return [scriptPath, reqStr];
		}
	}
}

function load_mv_embed( callback ) {
	// Inject mv_embed if needed
	if( typeof mvEmbed == 'undefined' ) {
		importScriptURI( mwEmbedHostPath + '/mwEmbed/mv_embed.js' + reqAguments );
		check_for_mv_embed( callback );
	} else {
		check_for_mv_embed( callback );
	}
}

function check_for_mv_embed( callback ) {
	if( typeof MV_EMBED_VERSION == 'undefined' ) {
		setTimeout( function(){
			check_for_mv_embed( callback );
		}, 25 );
	} else {
		callback();
	}
}
