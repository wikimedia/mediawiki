/*
* 
* Api proxy system
*
* Supports cross domain uploading, and api actions for a approved set of domains.
*
* The framework /will/ support a request approval system for per-user domain approval
* and a central blacklisting of domains controlled by the site 
*
*  Flow outline below:  
* 
* Domain A (lets say en.wiki)  
* 	invokes add-media-wizard and wants to upload to domain B ( commons.wiki )
* 
* 	Domain A loads iframe to domain B ? with request param to to insert from Domain A
*		Domain B checks list of approved domains for (Domain A) & checks the user is logged in ( and if the same account name ) 
*			if user is not logged in 
*				a _link_ to Domain B to new window login page is given
*			if user has not approved domain and (Domain A) is not pre-approved 
*				a new page link is generated with a approve domain request
*					if user approves domain it goes into their User:{username}/apiProxyDomains.js config
*		If Domain A is approved we then: 
* 			loads a "push" and "pull" iframe back to Domain A 
				(Domain B can change the #hash values of these children thereby proxy the data)  
* 	Domain A now gets the iframe "loaded" callback a does a initial echo to confirm cross domain proxy
*		echo sends "echo" to push and (Domain A) js passes the echo onto the "pull"
* 	Domain A now sends api requests to the iframe "push" child and gets results from the iframe "pull"
* 		api actions happen with status updates and everything and we can reuse existing api interface code  
* 
* if the browser supports it we can pass msgs with the postMessage  API
* http://ejohn.org/blog/cross-window-messaging/
*
* @@todo it would be nice if this supported multiple proxy targets (ie to a bright widgets future) 
*
*/

loadGM( {
	"mwe-setting-up-proxy" : "Setting up proxy...",
	"mwe-re-try" : "Retry API request",
	"mwe-re-trying" : "Retrying API request...",
	"mwe-proxy-not-ready" : "Proxy is not configured",
	"mwe-please-login" : "You are not <a target=\"_new\" href=\"$1\">logged in<\/a> on $2 or mwEmbed has not been enabled. Resolve the issue, and then retry the request.",
	"mwe-remember-loging" : "General security reminder: Only login to web sites when your address bar displays that site's address."
} );

( function( $ ) {
	/**
	 * Base API Proxy object
	 * 	 
	 */
	$.proxy = { };
	
	/**
	 * The client setup function: 
	 */
	$.proxy.client = function( pConf, conf ) {
		var _this = this;
		// Do client setup: 
		if ( pConf.server_frame )
			$.proxy.server_frame = pConf.server_frame;
		
		if ( pConf.client_frame_path ) {
			$.proxy.client_frame_path = pConf.client_frame_path;
		} else {
			// Set to default mwEmbed iframe path:
			$.proxy.client_frame_path =  wgScriptPath + '/js2/mwEmbed/libMwApi/NestedCallbackIframe.html';
		}
				
		if ( mw.parseUri( $.proxy.server_frame ).host ==  mw.parseUri( document.URL ).host ) {
			js_log( "Error: trying to proxy local domain? " );
			return false;
		}
		return true;
	}
	// Set the frameProxy Flag: 
	var frameProxyOk = false;
	
	/** 
	* doFrameProxy
	* 	Writes an iframe with a hashed value of the requestQuery
	*/
	$.proxy.doFrameProxy = function( requestQuery ) {
		var hashPack = {
			'cd': mw.parseUri( document.URL ).host,
			'cfp': $.proxy.client_frame_path,
			'req': requestQuery
		}
		js_log( "Do frame proxy request on src: \n" + $.proxy.server_frame + "\n" +
					JSON.stringify(  requestQuery ) );
		// we can't update src's so we have to remove and add all the time :(
		// @@todo we should support frame msg system 
		$j( '#frame_proxy' ).remove();
		$j( 'body' ).append( '<iframe style="display:none" id="frame_proxy" name="frame_proxy" ' +
				'src="' + $.proxy.server_frame +
				 '#' + escape( JSON.stringify( hashPack ) ) +
				 '"></iframe>' );
				 
		// add an onLoad hook: 
		$j( '#frame_proxy' ).get( 0 ).onload = function() {
			// add a 5 second timeout for setting up the nested child callback (after page load) 
			setTimeout( function() {
				if ( !frameProxyOk ) {
					// we timmed out no api proxy (should make sure the user is "logged in")
					js_log( "Error:: api proxy timeout are we logged in? mwEmbed is on?" );
					$.proxy.proxyNotReadyDialog();
				}
			}, 5000 );
		}
	}
	var lastApiReq = { };
	$.proxy.proxyNotReadyDialog = function() {
		var buttons = { };
		buttons[ gM( 'mwe-re-try' ) ] = function() {
			$j.addLoaderDialog( gM( 'mwe-re-trying' ) );
			$.proxy.doFrameProxy( lastApiReq );
		}
		buttons[ gM( 'mwe-cancel' ) ] = function() {
			$j.closeLoaderDialog();
		}
		var pUri =  mw.parseUri( $.proxy.server_frame );
		
		// FIXME we should have a Hosted iframe once we deploy mwEmbed on the servers.
		// A hosted iframe would be much faster since than a normal page view 
		
		var login_url = pUri.protocol + '://' + pUri.host;
		login_url += pUri.path.replace( 'MediaWiki:ApiProxy', 'Special:UserLogin' );
		
		$j.addDialog( 
			gM( 'mwe-proxy-not-ready' ), 
			gM( 'mwe-please-login', [ login_url, pUri.host] ) +
				'<p style="font-size:small">' + 
					gM( 'mwe-remember-loging' ) + 
				'</p>',
			buttons
		)
	}
	/* 
	* doRequest 
	* Takes a requestQuery, executes the query and then calls the callback
	*/
	$.proxy.doRequest = function( requestQuery, callback ) {
		js_log( "doRequest:: " + JSON.stringify( reqObj ) );
		lastApiReq = reqObj;
		// setup the callback:
		$.proxy.callback = callback;
		// do the proxy req:
		$.proxy.doFrameProxy( requestQuery );
	}
	/**
	 * The nested iframe action that passes its result back up to the top frame instance	 
	 */
	$.proxy.nested = function( hashResult ) {
		// Close the loader if present: 
		$j.closeLoaderDialog();
		js_log( '$.proxy.nested callback :: ' + unescape( hashResult ) );
		frameProxyOk = true;
		
		// Try to parse the hash result: 
		try {
			var rObj = JSON.parse( unescape( hashResult ) );
		} catch ( e ) {
			js_log( "Error could not parse hashResult" );
		}
		
		// Special callback to frameProxyOk flag 
		// (only used to test the proxy connection)   
		if ( rObj.state == 'ok' )
			return ;
		
		// Pass the callback:
		$.proxy.callback( rObj );
	}
	/**
	 * The server handles the actual proxy 
	 * it adds child frames pointing to the parent "blank" frames
	 * 
	 * This is (Domain B) in the above described setup
	 */
	$.proxy.server = function( pConf, callback ) {
		/* clear the body of any html */
		$j( 'body' ).html( 'proxy setup' );
		
		// read the anchor action from the requesting url
		var jmsg = unescape( mw.parseUri( document.URL ).anchor );
		try {
			var aObj = JSON.parse( jmsg );
		} catch ( e ) {
			js_log( "ProxyServer:: could not parse anchor" );
		}
		if ( !aObj.cd ) {
			js_log( "Error: no client domain provided " );
			return false;
		}
		
		js_log( "Setup server on: "  + mw.parseUri( document.URL ).host +
			' client from: ' + aObj.cd +
			' to nested target: ' + aObj.cfp );
		
		// Make sure we are logged in 
		// (its a normal mediaWiki page so all site vars should be defined)		
		if ( !wgUserName ) {
			js_log( 'Error Not logged in' );
			return false;
		}
			
		var domain =  aObj.cd;
		var nested_frame_src = 'http://' + aObj.cd + aObj.cfp;
		// Check the master whitelist
		for ( var i in pConf.master_whitelist ) {
			if ( domain ==  pConf.master_whitelist[ i ] ) {
				// Do the request: 			
				return doNestedProxy( aObj.req );
			}
		}
		// Check master blacklist
		for ( var i in pConf.master_blacklist ) {
			if ( domain == pConf.master_blacklist ) {
				js_log( 'domain: ' + domain + ' is blacklisted' );
				return false;
			}
		}
		// FIXME grab the users whitelist for our current domain				
		/*var local_api = wgScriptPath + '/index' + wgScriptExtension + '?title=' +
				'User:' + wgUserName + '/apiProxyDomainList.js' +
				'&action=raw&smaxage=0&gen=js';
		$j.get( local_api, function( data ){
			debugger;
		});*/
		
		// if still not found: 
		js_log( "domain " + domain + " not approved" );
		
		// FIXME :: offer the user the ability to "approve" requested domain save to
		// their user/ apiProxyDomainList.js page

		function doNestedProxy( reqObj ) {
			js_log( "doNestedProxy to: " + nested_frame_src );

			// Do a quick response to establish the proxy is working
			// ( before we actually run the api-request )  
			doNestedFrame ( 'nested_ok' , { 'state':'ok' } );
			
			var outputhash = escape( JSON.stringify( reqObj ) );

			// Add some api stuff: 
			reqObj[ 'format' ] = 'json';

			// Process the api request
			$j.post( wgScriptPath + '/api' + wgScriptExtension,
				reqObj,
				function( data ) {					
					// Put it into the nested frame hash string: 
					doNestedFrame( 'nested_push', JSON.parse( data ) );
				}
			);
		}
		// Add the doNestedFrame iframe: 
		function doNestedFrame( nestname, resultObj ) {
			$j( '#nested_push' ).remove();
			// Setup the nested proxy that points back to top domain:			
			$j( 'body' ).append( '<iframe id="nested_push" name="nested_push" ' +
				'src="' + nested_frame_src +
				'#' + escape( JSON.stringify( resultObj ) ) +
				'"></iframe>' );
		}
	}
	
} )( window.mw );
