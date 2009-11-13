/*
 * This file exposes some of the functionality of mwEmbed to wikis
 * that do not yet have js2 enabled
 */
 
var urlparts = getRemoteEmbedPath();
var mwEmbedHostPath = urlparts[0];
var mwRemoteVersion = '1.07';
var mwUseScriptLoader = true;

// setup up request Params: 
var reqParts = urlparts[1].substring( 1 ).split( '&' );
var mwReqParam = { };
for ( var i = 0; i < reqParts.length; i++ ) {
	var p = reqParts[i].split( '=' );
	if ( p.length == 2 )
		mwReqParam[ p[0] ] = p[1];
}

addOnloadHook( function() {
	// Only do rewrites if MV_EMBED / js2 is "off"
	if ( typeof MV_EMBED_VERSION == 'undefined' ) {
		doPageSpecificRewrite();
	}
} );

function doPageSpecificRewrite() {
	// Add media wizard
	if ( wgAction == 'edit' || wgAction == 'submit' ) {
		load_mv_embed( function() {
			loadExternalJs( mwEmbedHostPath + '/editPage.js?' + mwGetReqArgs() );
		} );
	}
	
	// Timed text display:
	if ( wgPageName.indexOf( "TimedText" ) === 0 ) {
		load_mv_embed( function() {
			// Load with mw loader to get localized interface:
			mw.load( ['mvTimeTextEdit'], function() {
				// could run init here (but mvTimeTextEdit included onLoad actions)
			} );
		} );
	}
	
	// Firefogg integration
	if ( wgPageName == "Special:Upload" ) {
		load_mv_embed( function() {
			loadExternalJs( mwEmbedHostPath + '/uploadPage.js?' + mwGetReqArgs() );
		} );
	}
	
	// Special api proxy page
	if ( wgPageName == 'MediaWiki:ApiProxy' ) {
		var wgEnableIframeApiProxy = true;
		load_mv_embed( function() {
			js_log( "Wiki:ApiProxy::" );
			loadExternalJs( mwEmbedHostPath + '/apiProxyPage.js?' + mwGetReqArgs() );
		} );
	}
	
	// OggHandler rewrite for view pages:
	var vidIdList = [];
	var divs = document.getElementsByTagName( 'div' );
	for ( var i = 0; i < divs.length; i++ ) {
		if ( divs[i].id && divs[i].id.substring( 0, 11 ) == 'ogg_player_' ) {
			vidIdList.push( divs[i].getAttribute( "id" ) );
		}
	}
	if ( vidIdList.length > 0 ) {
		load_mv_embed( function() {
			mvJsLoader.embedVideoCheck( function() {
				// Do utility rewrite of OggHandler content:
				rewrite_for_OggHandler( vidIdList );
			} );
		} );
	}
}
// This will be depreciated in favour of updates to OggHandler
function rewrite_for_OggHandler( vidIdList ) {
	function procVidId( vidId ) {
		// don't process empty vids
		if ( !vidId )
			return ;
		js_log( 'vidIdList on: ' + vidId + ' length: ' + vidIdList.length + ' left in the set: ' + vidIdList );
		
		// Grab the thumbnail and src of the video
		var pimg = $j( '#' + vidId + ' img' );
		var poster_attr = 'poster = "' + pimg.attr( 'src' ) + '" ';
		var pwidth = $j( '#' + vidId ).width();
		var pheight = $j( '#' + vidId + ' img' ).height();
		var tag_type = 'video';
				
		// Check for audio
		if ( pheight == '22' || pheight == '52' ) {
			// set width to parent width:			
			tag_type = 'audio';
			poster_attr = '';
		}

		// Parsed values:
		var src = '';
		var duration_attr = '';
		var wikiTitleKey = $j( '#' + vidId + ' img' ).filter( ':first' ).attr( 'src' ).split( '/' );
		wikiTitleKey = unescape( wikiTitleKey[ wikiTitleKey.length - 2 ] );
		var re = new RegExp( /videoUrl(&quot;:?\s*)*([^&]*)/ );
		src = re.exec( $j( '#' + vidId ).html() )[2];

		var re = new RegExp( /length(&quot;:?\s*)*([^,]*)/ );
		var dv = re.exec( $j( '#' + vidId ).html() )[2];
		if ( dv ) {
			duration_attr = 'durationHint="' + dv + '" ';
		}

		var re = new RegExp( /offset(&quot;:?\s*)*([^&]*)/ );
		offset = re.exec( $j( '#' + vidId ).html() )[2];
		var offset_attr = offset ? 'startOffset="' + offset + '"' : '';

		if ( src ) {
			var html_out = '';
			
			var common_attr = ' id="mwe_' + vidId + '" ' +
					'wikiTitleKey="' + wikiTitleKey + '" ' +
					'src="' + src + '" ' +
					duration_attr +
					offset_attr + ' ';
					
			if ( tag_type == 'audio' ) {
				html_out = '<audio' + common_attr + ' style="width:' + pwidth + 'px;"></audio>';
			} else {
				html_out = '<video' + common_attr +
				poster_attr + ' ' +
				'style="width:' + pwidth + 'px;height:' + pheight + 'px;">' +
				'</video>';
			}
			// set the video tag inner html and update the height
			$j( '#' + vidId ).html( html_out )
				.css( 'height', pheight + 30 );

		}
		rewrite_by_id( 'mwe_' + vidId, function() {
			if ( vidIdList.length != 0 ) {
				setTimeout( function() {
					procVidId( vidIdList.pop() )
				}, 10 );
			}
		} );
	};
	// process each item in the vidIdList (with setTimeout to avoid locking)
	procVidId( vidIdList.pop() );
}
function getRemoteEmbedPath() {
	for ( var i = 0; i < document.getElementsByTagName( 'script' ).length; i++ ) {
		var s = document.getElementsByTagName( 'script' )[i];
		if ( s.src.indexOf( '/remoteMwEmbed.js' ) != - 1 ) {
			var reqStr = '';
			var scriptPath = '';
			if ( s.src.indexOf( '?' ) != - 1 ) {
				reqStr = s.src.substr( s.src.indexOf( '?' ) );
				scriptPath = s.src.substr( 0,  s.src.indexOf( '?' ) ).replace( '/remoteMwEmbed.js', '' );
			} else {
				scriptPath = s.src.replace( '/remoteMwEmbed.js', '' )
			}
			// Use the external_media_wizard path:
			return [scriptPath, reqStr];
		}
	}
}
function mwGetReqArgs() {
	var rurl = '';
	if ( mwReqParam['debug'] )
		rurl += 'debug=true&';

	if ( mwReqParam['uselang'] )
		rurl += 'uselang=' + mwReqParam['uselang'] + '&';

	if ( mwReqParam['urid'] ) {
		rurl += 'urid=' + mwReqParam['urid'];
	} else {
		// Make sure to use an urid 
		// This way remoteMwEmbed can control version of code being requested
		rurl += 'urid=' + mwRemoteVersion;
	}
	return rurl;
}
function load_mv_embed( callback ) {
	// Inject mv_embed if needed
	if ( typeof mw == 'undefined' ) {
		if ( ( mwReqParam['uselang'] || mwReqParam['useloader'] ) && mwUseScriptLoader ) {
			var rurl = mwEmbedHostPath + '/mwEmbed/jsScriptLoader.php?class=mv_embed';
			// Add jQuery too if we need it: 
			if ( typeof window.jQuery == 'undefined' ) {
				rurl += ',window.jQuery';
			}
			rurl += '&' + mwGetReqArgs();
							
			importScriptURI( rurl );
		} else {
			importScriptURI( mwEmbedHostPath + '/mwEmbed/mv_embed.js?' + mwGetReqArgs() );
		}
	}
	check_for_mv_embed( callback );
}

function check_for_mv_embed( callback ) {
	if ( typeof mw == 'undefined' ) {
		setTimeout( function() {
			check_for_mv_embed( callback );
		}, 25 );
	} else {
		callback();
	}
}
