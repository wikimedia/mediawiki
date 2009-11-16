/*
 * This file exposes some of the functionality of mwEmbed to wikis
 * that do not yet have js2 enabled
 */
 
var urlparts = getRemoteEmbedPath();
var mwEmbedHostPath = urlparts[0];
var mwRemoteVersion = '1.09';
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
		var jsSetEdit = [ 'remoteSearchDriver', '$j.fn.textSelection', '$j.ui', '$j.ui.sortable' ]
		mwr_load_mv_embed( jsSetEdit, function() {
			loadExternalJs( mwEmbedHostPath + '/editPage.js?' + mwGetReqArgs() );
		} );
	}
	
	// Timed text display:
	if ( wgPageName.indexOf( "TimedText" ) === 0 ) {
		mwr_load_mv_embed( function() {
			// Load with mw loader to get localized interface:
			mw.load( ['mvTimeTextEdit'], function() {
				// could run init here (but mvTimeTextEdit already included onLoad actions)
			} );
		} );
	}
	
	// Firefogg integration
	if ( wgPageName == "Special:Upload" ) {	
		var jsSetUpload = [ 'mvBaseUploadInterface', 'mvFirefogg' , '$j.ui',
							'$j.ui.progressbar', '$j.ui.dialog', '$j.ui.draggable' ]; 
		mwr_load_mv_embed( jsSetUpload, function() {
			loadExternalJs( mwEmbedHostPath + '/uploadPage.js?' + mwGetReqArgs() );
		} );
	}
	
	// Special api proxy page
	if ( wgPageName == 'MediaWiki:ApiProxy' ) {
		var wgEnableIframeApiProxy = true;
		mwr_load_mv_embed( [ 'mw.proxy' ], function() {			
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
	
		var jsSetVideo = [ 'embedVideo', '$j.ui', 'ctrlBuilder', '$j.cookie', '$j.ui.slider' ];
		
		// Quick sniff use java if IE and native if firefox 
		// ( other browsers will run detect and get on-demand ) 	
		if (navigator.userAgent.indexOf("MSIE") != -1)
			jsSetVideo.push( 'javaEmbed' );
			
		if ( navigator.userAgent &&  navigator.userAgent.indexOf("Firefox") != -1 )
			jsSetVideo.push( 'nativeEmbed' );
	
		mwr_load_mv_embed( jsSetVideo, function() {
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
		var pheight = $j( '#' + vidId + ' img :first' ).attr( 'height' );
		if(!pheight)
			pheight = parseInt( pwidth * .75 );
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
/**
* @param {mixed} function or classSet to preload
* classSet saves round trips to the server by grabbing things we will likely need in the first request. 
* ( this is essentially a shortcut to mv_jqueryBindings in mv_embed.js )   
* @param {callback} function callback to be called once mv_embed is ready
*/
function mwr_load_mv_embed( classSet, callback ) {
	if( typeof classSet == 'function')
		callback = classSet;
	// Inject mv_embed if needed
	if ( typeof mw == 'undefined' ) {
		if ( ( mwReqParam['uselang'] || mwReqParam['useloader'] ) && mwUseScriptLoader ) {
			var rurl = mwEmbedHostPath + '/mwEmbed/jsScriptLoader.php?class=mv_embed';
			
			// Add jQuery too if we need it: 
			if ( typeof window.jQuery == 'undefined' ) {
				rurl += ',window.jQuery';
			}	
								
			// Add requested classSet
			for( var i=0; i < classSet.length; i++ ){
				var cName =  classSet[i];
				if( !mwr_check_obj_path( cName ) ){
					rurl +=  ',' + cName;
				}
			}
			
			// Add the remaining arguments
			rurl += '&' + mwGetReqArgs();
							
			importScriptURI( rurl );
		} else { 
			// Ingore classSet (will be loaded onDemand )
			importScriptURI( mwEmbedHostPath + '/mwEmbed/mv_embed.js?' + mwGetReqArgs() );
		}
	}
	mwr_check_for_mv_embed( callback );
}

function mwr_check_for_mv_embed( callback ) {
	if ( typeof mw == 'undefined' ) {
		setTimeout( function() {
			mwr_check_for_mv_embed( callback );
		}, 25 );
	} else {
		callback();
	}
}

function mwr_check_obj_path ( libVar ) {
	if ( !libVar )
		return false;
	var objPath = libVar.split( '.' )
	var cur_path = '';
	for ( var p = 0; p < objPath.length; p++ ) {
		cur_path = ( cur_path == '' ) ? cur_path + objPath[p] : cur_path + '.' + objPath[p];
		eval( 'var ptest = typeof ( ' + cur_path + ' ); ' );
		if ( ptest == 'undefined' ) {
			this.missing_path = cur_path;
			return false;
		}
	}
	this.cur_path = cur_path;
	return true;
};
