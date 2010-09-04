/*
 * Legacy emulation for the now depricated skins/common/ajax.js
 * 
 * Original licensing information:
 * 		Remote Scripting Library
 * 		(c) Copyright 2005 ModernMethod, Inc.
 */

( function( $, mw ) {

/* Extension */

$.extend( true, mw.legacy, {

	/* Global Variables */
	
	'sajax_debug_mode': false,
	'sajax_debug_mode': 'GET',
	
	/* Functions */
	
	/**
	* If sajax_debug_mode is true, this function outputs given the message into the element with id = sajax_debug; if no
	* such element exists in the document, it is injected
	* 
	* @param string text debug message to append to log
	* @return boolean true when in debug mode, false when not
	*/
	'sajax_debug': function( text ) {
		if ( mw.legacy.sajax_debug_mode ) {
			var $e = $( '#sajax_debug' );
			if ( !$e.length ) {
				$e = $( '<p class="sajax_debug" id="sajax_debug"></p>' ).prependTo( $( 'body' ) );
			}
			$e.append( $( '<div></div>' ).text( text ) );		
			return true;
		}
		return false;
	},
	/**
	 * Gets an XMLHttpRequest or equivilant ActiveXObject
	 * 
	 * @reuturn mixed request object on success, boolean false on failure
	 */
	'sajax_init_object': function() {
		mw.legacy.sajax_debug( 'sajax_init_object() called..' );
		var request = false;
		try {
			// Try the 'new' style before ActiveX so we don't unnecessarily trigger warnings in IE 7 when the user's
			// security settings are set to prompt about ActiveX usage
			request = new XMLHttpRequest();
		} catch ( e ) {
			try {
				request = new ActiveXObject( 'Msxml2.XMLHTTP' );
			} catch ( e ) {
				try {
					request = new ActiveXObject( 'Microsoft.XMLHTTP' );
				} catch ( oc ) {
					request = null;
				}
			}
		}
		if ( !request ) {
			mw.legacy.sajax_debug( 'Could not create connection object.' );
		}
		return request;
	},
	/**
	 * Performs an ajax call to mediawiki. Calls are handeled by AjaxDispatcher.php
	 * 
	 * @param string method name of the function to call. Must be registered in $wgAjaxExportList
	 * @param array arguments arguments to that function
	 * @param mixed target the target that will handle the result of the call. If this is a function, if will be called
	 * with the XMLHttpRequest as a parameter; if it's an input element, its value will be set to the resultText; if
	 * it's another type of element, its innerHTML will be set to the resultText.
	 *
	 * @example
	 * 		// This will call the doFoo function via MediaWiki's AjaxDispatcher, with (1, 2, 3) as the parameter list,
	 * 		// and will show the result in the element with id = showFoo
	 * 		sajax_do_call( 'doFoo', [1, 2, 3], document.getElementById( 'showFoo' ) );
	 */
	'sajax_do_call': function( method, arguments, target ) {
		var post_data;
		var uri = mw.legacy.wgServer + 
			( ( mw.legacy.wgScript == null ) ? ( mw.legacy.wgScriptPath + '/index.php' ) : mw.legacy.wgScript ) +
			'?action=ajax';
		if ( mw.legacy.sajax_request_type == 'GET' ) {
			if ( uri.indexOf( '?' ) == -1 ) {
				uri = uri + '?rs=' + encodeURIComponent( method );
			} else {
				uri = uri + '&rs=' + encodeURIComponent( method );
			}
			for ( var i = 0; i < arguments.length; i++ ) {
				uri = uri + '&rsargs[]=' + encodeURIComponent( arguments[i] );
			}
			post_data = null;
		} else {
			post_data = 'rs=' + encodeURIComponent( method );
			for ( var i = 0; i < arguments.length; i++ ) {
				post_data = post_data + '&rsargs[]=' + encodeURIComponent( arguments[i] );
			}
		}
		var request = mw.legacy.sajax_init_object();
		if ( !request ) {
			alert( 'AJAX not supported' );
			return false;
		}
		try {
			request.open( mw.legacy.sajax_request_type, uri, true );
		} catch ( e ) {
			if ( window.location.hostname == 'localhost' ) {
				alert(
					'Your browser blocks XMLHttpRequest to \'localhost\', ' +
					'try using a real hostname for development/testing.'
				);
			}
			throw e;
		}
		if ( mw.legacy.sajax_request_type == 'POST' ) {
			request.setRequestHeader( 'Method', 'POST ' + uri + ' HTTP/1.1' );
			request.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' );
		}
		request.setRequestHeader( 'Pragma', 'cache=yes' );
		request.setRequestHeader( 'Cache-Control', 'no-transform' );
		request.onreadystatechange = function() {
			if ( request.readyState != 4 ) {
				return;
			}
			mw.legacy.sajax_debug(
				'received (' + request.status + ' ' + request.statusText + ') ' + request.responseText
			);
			if ( typeof( target ) == 'function' ) {
				target( request );
			} else if ( typeof( target ) == 'object' ) {
				$target = $( target );
				if ( $target.is( 'input' ) ) {
					if ( request.status == 200 ) {
						$target.val();
					}
				} else {
					if ( request.status == 200 ) {
						$target.html( request.responseText );
					} else {
						$target.html(
							'<div class="error">' +
								'Error: ' + request.status + ' ' + request.statusText +
								' (' + request.responseText + ')' +
							'</div>'
						);
					}
				}
			} else {
				alert( 'Bad target for sajax_do_call: not a function or object: ' + target );
			}
			return;
		}
		mw.legacy.sajax_debug( method + ' uri = ' + uri + ' / post = ' + post_data );
		request.send( post_data );
		mw.legacy.sajax_debug( method + ' waiting..' );
		delete x;
		return true;
	},
	/**
	 * Ajax compatibility test
	 * 
	 * @return boolean whether the browser supports XMLHttpRequest
	 */
	'wfSupportsAjax': function() {
		var request = mw.legacy.sajax_init_object();
		var result = request ? true : false;
		delete request;
		return result;
	}
} );

} )( jQuery, mediaWiki );