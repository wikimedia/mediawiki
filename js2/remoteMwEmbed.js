/*
 * this file exposes some of the functionality of mwEmbed to wikis
 * that are not yet running the new-upload branch
 */


var urlparts = getRemoteEmbedPath();
var mwEmbedHostPath = urlparts[0];
var reqAguments = urlparts[1];

// Check if mvEmbed is already loaded (ie the js2 branch is active) in which case do nothing
if( typeof MV_EMBED_VERSION == 'undefined' ) {
	doPageSpecificRewrite();
}

function doPageSpecificRewrite() {
	// Add media wizard
	if( wgAction == 'edit' || wgAction == 'submit' ) {
		load_mv_embed( function() {
			importScriptURI( mwEmbedHostPath + '/editPage.js' + reqAguments );
		});
	}

	// Firefogg integration
	if( wgPageName == "Special:Upload" ){
		load_mv_embed( function() {
			importScriptURI( mwEmbedHostPath + '/uploadPage.js' + reqAguments );
		});
	}

	// OggHandler rewrite
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
	            // Do utilty rewrite of OggHandler content:
	            rewrite_for_OggHandler( vidIdList );
	        });
	    });
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
        setTimeout( 'check_for_mv_embed( ' + callback + ');', 25 );
    } else {
        callback();
    }
}

