/**
 * This script provides a function which is run to evaluate whether or not to
 * continue loading jQuery and the MediaWiki modules. This code should work on
 * even the most ancient of browsers, so be very careful when editing.
 */
/*jshint unused: false, evil: true */
/*globals mw, RLQ: true, $VARS, $CODE, performance */

var mediaWikiLoadStart = ( new Date() ).getTime(),

	mwPerformance = ( window.performance && performance.mark ) ? performance : {
		mark: function () {}
	};

mwPerformance.mark( 'mwLoadStart' );

/**
 * Returns false for Grade C supported browsers.
 *
 * This function should only be used by the Startup module, do not expand it to
 * be generally useful beyond startup.
 *
 * See also:
 * - https://www.mediawiki.org/wiki/Compatibility#Browsers
 * - https://jquery.com/browser-support/
 */
function isCompatible( ua ) {
	if ( ua === undefined ) {
		ua = navigator.userAgent;
	}

	// Browsers with outdated or limited JavaScript engines get the no-JS experience
	return !(
		// Internet Explorer < 8
		( ua.indexOf( 'MSIE' ) !== -1 && parseFloat( ua.split( 'MSIE' )[ 1 ] ) < 8 ) ||
		// Firefox < 3
		( ua.indexOf( 'Firefox/' ) !== -1 && parseFloat( ua.split( 'Firefox/' )[ 1 ] ) < 3 ) ||
		// Opera < 12
		( ua.indexOf( 'Opera/' ) !== -1 && ( ua.indexOf( 'Version/' ) === -1 ?
			// "Opera/x.y"
			parseFloat( ua.split( 'Opera/' )[ 1 ] ) < 10 :
			// "Opera/9.80 ... Version/x.y"
			parseFloat( ua.split( 'Version/' )[ 1 ] ) < 12
		) ) ||
		// "Mozilla/0.0 ... Opera x.y"
		( ua.indexOf( 'Opera ' ) !== -1 && parseFloat( ua.split( ' Opera ' )[ 1 ] ) < 10 ) ||
		// BlackBerry < 6
		ua.match( /BlackBerry[^\/]*\/[1-5]\./ ) ||
		// Open WebOS < 1.5
		ua.match( /webOS\/1\.[0-4]/ ) ||
		// Anything PlayStation based.
		ua.match( /PlayStation/i ) ||
		// Any Symbian based browsers
		ua.match( /SymbianOS|Series60/ ) ||
		// Any NetFront based browser
		ua.match( /NetFront/ ) ||
		// Opera Mini, all versions
		ua.match( /Opera Mini/ ) ||
		// Nokia's Ovi Browser
		ua.match( /S40OviBrowser/ ) ||
		// MeeGo's browser
		ua.match( /MeeGo/ ) ||
		// Google Glass browser groks JS but UI is too limited
		( ua.match( /Glass/ ) && ua.match( /Android/ ) )
	);
}

// Conditional script injection
( function () {
	if ( !isCompatible() ) {
		// Undo class swapping in case of an unsupported browser.
		// See OutputPage::getHeadScripts().
		document.documentElement.className = document.documentElement.className
			.replace( /(^|\s)client-js(\s|$)/, '$1client-nojs$2' );
		return;
	}

	/**
	 * The $CODE and $VARS placeholders are substituted in ResourceLoaderStartUpModule.php.
	 */
	function startUp() {
		mw.config = new mw.Map( $VARS.wgLegacyJavaScriptGlobals );

		$CODE.registrations();

		mw.config.set( $VARS.configuration );

		// Must be after mw.config.set because these callbacks may use mw.loader which
		// needs to have values 'skin', 'debug' etc. from mw.config.
		window.RLQ = window.RLQ || [];
		while ( RLQ.length ) {
			RLQ.shift()();
		}
		window.RLQ = {
			push: function ( fn ) {
				fn();
			}
		};
	}

	var script = document.createElement( 'script' );
	script.src = $VARS.baseModulesUri;
	script.onload = script.onreadystatechange = function () {
		if ( !script.readyState || /loaded|complete/.test( script.readyState ) ) {
			// Clean up
			script.onload = script.onreadystatechange = null;
			script = null;
			// Callback
			startUp();
		}
	};
	document.getElementsByTagName( 'head' )[ 0 ].appendChild( script );
}() );
