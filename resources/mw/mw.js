/*
 * JavaScript Backwards Compatibility
 */

// Make calling .indexOf() on an array work on older browsers
if ( typeof Array.prototype.indexOf === 'undefined' ) { 
	Array.prototype.indexOf = function( needle ) {
		for ( var i = 0; i < this.length; i++ ) {
			if ( this[i] === needle ) {
				return i;
			}
		}
		return -1;
	};
}

/*
 * Core MediaWiki JavaScript Library
 */
( function() {
	
	/* Constants */
	
	// This will not change until we are 100% ready to turn off legacy globals
	const LEGACY_GLOBALS = true;
	
	/* Members */
	
	this.legacy = LEGACY_GLOBALS ? window : {};
	
	/* Methods */
	
	/**
	 * Log a string msg to the console
	 * 
	 * All mw.log statements will be removed on minification so lots of mw.log calls will not impact performance in non-debug
	 * mode. This is done using simple regular expressions, so the input of this function needs to not contain things like a
	 * self-executing closure. In the case that the browser does not have a console available, one is created by appending a
	 * <div> element to the bottom of the body and then appending a <div> element to that for each message. In the case that
	 * the browser does have a console available 
	 *
	 * @author Michael Dale <mdale@wikimedia.org>, Trevor Parscal <tparscal@wikimedia.org>
	 * @param {String} string String to output to console
	 */
	this.log = function( string ) {
		// Allow log messages to use a configured prefix		
		if ( mw.config.exists( 'mw.log.prefix' ) ) {
			string = mw.config.get( 'mw.log.prefix' ) + string;		
		}
		// Try to use an existing console
		if ( typeof window.console !== 'undefined' && typeof window.console.log == 'function' ) {
			window.console.log( string );
		} else {
			// Show a log box for console-less browsers
			var $log = $( '#mw_log_console' );
			if ( !$log.length ) {
				$log = $( '<div id="mw_log_console"></div>' )
					.css( {
						'position': 'absolute',
						'overflow': 'auto',
						'z-index': 500,
						'bottom': '0px',
						'left': '0px',
						'right': '0px',
						'height': '150px',
						'background-color': 'white',
						'border-top': 'solid 1px #DDDDDD'
					} )
					.appendTo( $( 'body' ) );
			}
			if ( $log.length ) {
				$log.append(
					$( '<div>' + string + '</div>' )
						.css( {
							'border-bottom': 'solid 1px #DDDDDD',
							'font-size': 'small',
							'font-family': 'monospace',
							'padding': '0.125em 0.25em'
						} )
				);
			}
		}
	};
	/*
	 * An object which allows single and multiple existence, setting and getting on a list of key / value pairs
	 */
	this.config = new ( function() {
		
		/* Private Members */
		
		var that = this;
		// List of configuration values - in legacy mode these configurations were ALL in the global space
		var values = LEGACY_GLOBALS ? window : {};
		
		/* Public Methods */
		
		/**
		 * Sets one or multiple configuration values using a key and a value or an object of keys and values
		 */
		this.set = function( keys, value ) {
			if ( typeof keys === 'object' ) {
				for ( var key in keys ) {
					values[key] = keys[key];
				}
			} else if ( typeof keys === 'string' && typeof value !== 'undefined' ) {
				values[keys] = value;
			}
		};
		/**
		 * Gets one or multiple configuration values using a key and an optional fallback or an array of keys
		 */
		this.get = function( keys, fallback ) {
			if ( typeof keys === 'object' ) {
				var result = {};
				for ( var k = 0; k < keys.length; k++ ) {
					if ( typeof values[keys[k]] !== 'undefined' ) {
						result[keys[k]] = values[keys[k]];
					}
				}
				return result;
			} else if ( typeof values[keys] === 'undefined' ) {
				return typeof fallback !== 'undefined' ? fallback : null;
			} else {
				return values[keys];
			}
		};
		/**
		 * Checks if one or multiple configuration fields exist
		 */
		this.exists = function( keys ) {
			if ( typeof keys === 'object' ) {
				for ( var k = 0; k < keys.length; k++ ) {
					if ( !( keys[k] in values ) ) {
						return false;
					}
				}
				return true;
			} else {
				return keys in values;
			}
		};
	} )();
	/*
	 * Localization system
	 */
	this.msg = new ( function() {
		
		/* Private Members */
		
		var that = this;
		// List of localized messages
		var messages = {};
		
		/* Public Methods */
		
		this.set = function( keys, value ) {
			if ( typeof keys === 'object' ) {
				for ( var key in keys ) {
					messages[key] = keys[key];
				}
			} else if ( typeof keys === 'string' && typeof value !== 'undefined' ) {
				messages[keys] = value;
			}
		};
		this.get = function( key, args ) {
			if ( !( key in messages ) ) {
				return '<' + key + '>';
			}
			var msg = messages[key];
			if ( typeof args == 'object' || typeof args == 'array' ) {
				for ( var argKey in args ) {
					msg = msg.replace( '\$' + ( parseInt( argKey ) + 1 ), args[argKey] );
				}
			} else if ( typeof args == 'string' || typeof args == 'number' ) {
				msg = msg.replace( '$1', args );
			}
			return msg;
		};
	} )();
	/*
	 * Client-side module loader which integrates with the MediaWiki ResourceLoader
	 */
	this.loader = new ( function() {
		
		/* Private Members */
		
		var that = this;
		var server = 'load.php';
		/*
		 * Mapping of registered modules
		 * 
		 * Format:
		 * 	{
		 * 		'moduleName': {
		 * 			'needs': ['required module', 'required module', ...],
		 * 			'state': 'registered, loading, loaded, or ready',
		 * 			'script': function() {},
		 * 			'style': 'css code string',
		 * 			'localization': { 'key': 'value' }
		 * 		}
		 * 	}
		 */
		var registry = {};
		// List of callbacks waiting on dependent modules to be loaded so they can be executed
		var queue = [];
		// Until document ready, load requests will be collected in a batch queue
		var batch = [];
		// True after document ready occurs
		var ready = false;
		
		/* Private Methods */
		
		/**
		 * Gets a list of modules names that a module needs in their proper dependency order
		 * 
		 * @param string module name
		 * @return 
		 * @throws Error if circular reference is detected
		 */
		function needs( module ) {
			if ( !( module in registry ) ) {
				// Undefined modules have no needs
				return [];
			}
			var resolved = [];
			var unresolved = [];
			if ( arguments.length === 3 ) {
				// Use arguemnts on inner call
				resolved = arguments[1];
				unresolved = arguments[2];
			}
			unresolved[unresolved.length] = module;
		    for ( n in registry[module].needs ) {
		        if ( resolved.indexOf( registry[module].needs[n] ) === -1 ) {
		            if ( unresolved.indexOf( registry[module].needs[n] ) !== -1 ) {
		                throw new Error(
		                	'Circular reference detected: ' + module + ' -> ' + registry[module].needs[n]
		                );
		            }
		            needs( registry[module].needs[n], resolved, unresolved );
		        }
		    }
		    resolved[resolved.length] = module;
		    unresolved.slice( unresolved.indexOf( module ), 1 );
			if ( arguments.length === 1 ) {
			    // Return resolved list on outer call
				return resolved;
			}
		};
		/**
		 * Narrows a list of module names down to those matching a specific state. Possible states are 'undefined',
		 * 'registered', 'loading', 'loaded', or 'ready'
		 * 
		 * @param mixed string or array of strings of module states to filter by
		 * @param array list of module names to filter (optional, all modules will be used by default)
		 * @return array list of filtered module names
		 */
		function filter( states, modules ) {
			var list = [];
			if ( typeof modules === 'undefined' ) {
				modules = [];
				for ( module in registry ) {
					modules[modules.length] = module;
				}
			}
			for ( var s in states ) {
				for ( var m in modules ) {
					if (
						( states[s] == 'undefined' && typeof registry[modules[m]] === 'undefined' ) ||
						( typeof registry[modules[m]] === 'object' && registry[modules[m]].state === states[s] )
					) {
						list[list.length] = modules[m];
					}
				}
			}
			return list;
		}
		/**
		 * Executes a loaded module, making it ready to use
		 * 
		 * @param string module name to execute
		 */
		function execute( module ) {
			if ( typeof registry[module] === 'undefined' ) {
				throw new Error( 'module has not been registered: ' + module );
			}
			switch ( registry[module].state ) {
				case 'registered':
					throw new Error( 'module has not completed loading: ' + module );
					break;
				case 'loading':
					throw new Error( 'module has not completed loading: ' + module );
					break;
				case 'ready':
					throw new Error( 'module has already been loaded: ' + module );
					break;
			}
			// Add style sheet to document
			if ( typeof registry[module].style === 'string' && registry[module].style.length ) {
				$( 'head' ).append( '<style type="text/css">' + registry[module].style + '</style>' );
			}
			// Add localizations to message system
			if ( typeof registry[module].localization === 'object' ) {
				mw.msg.set( registry[module].localization );
			}
			// Execute script
			try {
				registry[module].script();
			} catch( e ) {
				mw.log( 'Exception thrown by ' + module + ': ' + e.message );
			}
			// Change state
			registry[module].state = 'ready';
			
			// Execute all modules which were waiting for this to be ready
			for ( r in registry ) {
				if ( registry[r].state == 'loaded' ) {
					if ( filter( ['ready'], registry[r].needs ).length == registry[r].needs.length ) {
						execute( r );
					}
				}
			}
		}
		/**
		 * Adds a callback and it's needs to the queue
		 * 
		 * @param array list of module names the callback needs to be ready before being executed
		 * @param function callback to execute when needs are met
		 */
		function request( needs, callback ) {
			queue[queue.length] = { 'needs': filter( ['undefined', 'registered'], needs ), 'callback': callback };
		}
		
		/* Public Methods */
		
		/**
		 * Processes the queue, loading and executing when things when ready.
		 */
		this.work = function() {
			// Appends a list of modules to the batch
			function append( modules ) {
				for ( m in modules ) {
					// Prevent requesting modules which are loading, loaded or ready
					if ( modules[m] in registry && registry[modules[m]].state == 'registered' ) {
						// Since the batch can live between calls to work until document ready, we need to make sure
						// we aren't making a duplicate entry
						if ( batch.indexOf( modules[m] ) == -1 ) {
							batch[batch.length] = modules[m];
							registry[modules[m]].state = 'loading';
						}
					}
				}
			}
			// Fill batch with modules that need to be loaded
			for ( var q in queue ) {
				append( queue[q].needs );
				for ( n in queue[q].needs ) {
					append( needs( queue[q].needs[n] ) );
				}
			}
			// After document ready, handle the batch
			if ( ready && batch.length ) {
				// Always order modules alphabetically to help reduce cache misses for otherwise identical content
				batch.sort();
				
				var base = $.extend( {},
					// Pass configuration values through the URL
					mw.config.get( [ 'user', 'skin', 'space', 'view', 'language' ] ),
					// Ensure request comes back in the proper mode (debug or not)
					{ 'debug': typeof mw.debug !== 'undefined' ? '1' : '0' }
				);
				var requests = [];
				if ( base.debug == '1' ) {
					for ( b in batch ) {
						requests[requests.length] = $.extend( { 'modules': batch[b] }, base );
					}
				} else {
					requests[requests.length] = $.extend( { 'modules': batch.join( '|' ) }, base );
				}
				// It may be more performant to do this with an Ajax call, but that's limited to same-domain, so we
				// can either auto-detect (if there really is any benefit) or just use this method, which is safe
				setTimeout(  function() {
					// Clear the batch - this MUST happen before we append the script element to the body or it's
					// possible that the script will be locally cached, instantly load, and work the batch again,
					// all before we've cleared it causing each request to include modules which are already loaded
					batch = [];
					var html = '';
					for ( r in requests ) {
						// Build out the HTML
						var src = mw.util.buildUrlString( {
							'path': mw.config.get( 'wgScriptPath' ) + '/load.php',
							'query': requests[r]
						} );
						html += '<script type="text/javascript" src="' + src + '"></script>';
					}
					// Append script to head
					$( 'head' ).append( html );
				}, 0 )
			}
		};
		/**
		 * Registers a module, letting the system know about it and it's dependencies. loader.js files contain calls
		 * to this function.
		 */
		this.register = function( name, needs ) {
			// Validate input
			if ( typeof name !== 'string' ) {
				throw new Error( 'name must be a string, not a ' + typeof name );
			}
			if ( typeof registry[name] !== 'undefined' ) {
				throw new Error( 'module already implemeneted: ' + name );
			}
			// List the module as registered
			registry[name] = { 'state': 'registered', 'needs': [] };
			// Allow needs to be given as a function which returns a string or array
			if ( typeof needs === 'function' ) {
				needs = needs();
			}
			if ( typeof needs === 'string' ) {
				// Allow needs to be given as a single module name
				registry[name].needs = [needs];
			} else if ( typeof needs === 'object' ) {
				// Allow needs to be given as an array of module names
				registry[name].needs = needs;
			}
		};
		/**
		 * Implements a module, giving the system a course of action to take upon loading. Results of a request for
		 * one or more modules contain calls to this function.
		 */
		this.implement = function( name, script, style, localization ) {
			// Automaically register module
			if ( typeof registry[name] === 'undefined' ) {
				that.register( name, needs );
			}
			// Validate input
			if ( typeof script !== 'function' ) {
				throw new Error( 'script must be a function, not a ' + typeof script );
			}
			if ( typeof style !== 'undefined' && typeof style !== 'string' ) {
				throw new Error( 'style must be a string, not a ' + typeof style );
			}
			if ( typeof localization !== 'undefined' && typeof localization !== 'object' ) {
				throw new Error( 'localization must be an object, not a ' + typeof localization );
			}
			if ( typeof registry[name] !== 'undefined' && typeof registry[name].script !== 'undefined' ) {
				throw new Error( 'module already implemeneted: ' + name );
			}
			// Mark module as loaded
			registry[name].state = 'loaded';
			// Attach components
			registry[name].script = script;
			if ( typeof style === 'string' ) {
				registry[name].style = style;
			}
			if ( typeof localization === 'object' ) {
				registry[name].localization = localization;
			}
			// Execute or queue callback
			if ( filter( ['ready'], registry[name].needs ).length == registry[name].needs.length ) {
				execute( name );
			} else {
				request( registry[name].needs, function() { execute( name ); } );
			}
		};
		/**
		 * Executes a function as soon as one or more required modules are ready
		 * 
		 * @param mixed string or array of strings of modules names the callback needs to be ready before executing
		 * @param function callback to execute when all needs are met
		 */
		this.using = function( needs, callback ) {
			// Validate input
			if ( typeof needs !== 'object' && typeof needs !== 'string' ) {
				throw new Error( 'needs must be a string or an array, not a ' + typeof needs )
			}
			if ( typeof callback !== 'function' ) {
				throw new Error( 'callback must be a function, not a ' + typeof callback )
			}
			if ( typeof needs === 'string' ) {
				needs = [needs];
			}
			// Execute or queue callback
			if ( filter( ['ready'], needs ).length == needs.length ) {
				callback();
			} else {
				request( needs, callback );
			}
		};
		
		/* Event Bindings */
		
		$( document ).ready( function() {
			ready = true;
			that.work();
		} );
	} )();
	/**
	 * General purpose utilities
	 */
	this.util = new ( function() {
		
		/* Private Members */
		
		var that = this;
		// Decoded user agent string cache
		var client = null;
		
		/* Public Methods */
		
		/**
		 * Builds a url string from an object containing any of the following components:
		 * 
		 * Component	Example
		 * scheme		"http"
		 * server		"www.domain.com"
		 * path			"path/to/my/file.html"
		 * query		"this=thåt" or { 'this': 'thåt' }
		 * fragment		"place_on_the_page"
		 * 
		 * Results in: "http://www.domain.com/path/to/my/file.html?this=th%C3%A5t#place_on_the_page"
		 * 
		 * All arguments to this function are assumed to be URL-encoded already, except for the
		 * query parameter if provided in object form.
		 */
		this.buildUrlString = function( components ) {
			var url = '';
			if ( typeof components.scheme === 'string' ) {
				url += components.scheme + '://';
			}
			if ( typeof components.server === 'string' ) {
				url += components.server + '/';
			}
			if ( typeof components.path === 'string' ) {
				url += components.path;
			}
			if ( typeof components.query === 'string' ) {
				url += '?' + components.query;
			} else if ( typeof components.query === 'object' ) {
				url += '?' + that.buildQueryString( components.query );
			}
			if ( typeof components.fragment === 'string' ) {
				url += '#' + components.fragment;
			}
			return url;
		};
		/**
		 * RFC 3986 compliant URI component encoder - with identical behavior as PHP's urlencode function. Note: PHP's
		 * urlencode function prior to version 5.3 also escapes tildes, this does not. The naming here is not the same
		 * as PHP because PHP can't decide out to name things (underscores sometimes?), much less set a reasonable
		 * precedence for how things should be named in other environments. We use camelCase and action-subject here.
		 */
		this.encodeUrlComponent = function( string ) {  
			return encodeURIComponent( new String( string ) )
				.replace(/!/g, '%21')
				.replace(/'/g, '%27')
				.replace(/\(/g, '%28')
				.replace(/\)/g, '%29')
				.replace(/\*/g, '%2A')
				.replace(/%20/g, '+');
		};
		/**
		 * Builds a query string from an object with key and values
		 */
		this.buildQueryString = function( parameters ) {
			if ( typeof parameters === 'object' ) {
				var parts = [];
				for ( var p in parameters ) {
					parts[parts.length] = that.encodeUrlComponent( p ) + '=' + that.encodeUrlComponent( parameters[p] );
				}
				return parts.join( '&' );
			}
			return '';
		};
		/**
		 * Returns an object containing information about the browser
		 * 
		 * The resulting client object will be in the following format:
		 *  {
		 * 		'name': 'firefox',
		 * 		'layout': 'gecko',
		 * 		'os': 'linux'
		 * 		'version': '3.5.1',
		 * 		'versionBase': '3',
		 * 		'versionNumber': 3.5,
		 * 	}
		 */
		this.client = function() {
			// Use the cached version if possible
			if ( client === null ) {
				
				/* Configuration */
				
				// Name of browsers or layout engines we don't recognize
				var uk = 'unknown';
				// Generic version digit
				var x = 'x';
				// Strings found in user agent strings that need to be conformed
				var wildUserAgents = [ 'Opera', 'Navigator', 'Minefield', 'KHTML', 'Chrome', 'PLAYSTATION 3'];
				// Translations for conforming user agent strings
				var userAgentTranslations = [
				    // Tons of browsers lie about being something they are not
					[/(Firefox|MSIE|KHTML,\slike\sGecko|Konqueror)/, ''],
					// Chrome lives in the shadow of Safari still
					['Chrome Safari', 'Chrome'],
					// KHTML is the layout engine not the browser - LIES!
					['KHTML', 'Konqueror'],
					// Firefox nightly builds
					['Minefield', 'Firefox'],
					// This helps keep differnt versions consistent
					['Navigator', 'Netscape'],
					// This prevents version extraction issues, otherwise translation would happen later
					['PLAYSTATION 3', 'PS3'],
				];
				// Strings which precede a version number in a user agent string - combined and used as match 1 in
				// version detectection
				var versionPrefixes = [
					'camino', 'chrome', 'firefox', 'netscape', 'netscape6', 'opera', 'version', 'konqueror', 'lynx',
					'msie', 'safari', 'ps3'
				];
				// Used as matches 2, 3 and 4 in version extraction - 3 is used as actual version number
				var versionSuffix = '(\/|\;?\s|)([a-z0-9\.\+]*?)(\;|dev|rel|\\)|\s|$)';
				// Names of known browsers
				var browserNames = [
				 	'camino', 'chrome', 'firefox', 'netscape', 'konqueror', 'lynx', 'msie', 'opera', 'safari', 'ipod',
				 	'iphone', 'blackberry', 'ps3'
				];
				// Tanslations for conforming browser names
				var browserTranslations = [];
				// Names of known layout engines
				var layoutNames = ['gecko', 'konqueror', 'msie', 'opera', 'webkit'];
				// Translations for conforming layout names
				var layoutTranslations = [['konqueror', 'khtml'], ['msie', 'trident'], ['opera', 'presto']];
				// Names of known operating systems
				var osNames = ['win', 'mac', 'linux', 'sunos', 'solaris', 'iphone'];
				// Translations for conforming operating system names
				var osTranslations = [['sunos', 'solaris']];
				
				/* Methods */
				
				// Performs multiple replacements on a string
				function translate( source, translations ) {
					for ( var i = 0; i < translations.length; i++ ) {
						source = source.replace( translations[i][0], translations[i][1] );
					}
					return source;
				};
				
				/* Pre-processing  */
				
				var userAgent = navigator.userAgent, match, browser = uk, layout = uk, os = uk, version = x;
				if ( match = new RegExp( '(' + wildUserAgents.join( '|' ) + ')' ).exec( userAgent ) ) {
					// Takes a userAgent string and translates given text into something we can more easily work with
					userAgent = translate( userAgent, userAgentTranslations );
				}
				// Everything will be in lowercase from now on
				userAgent = userAgent.toLowerCase();
				
				/* Extraction */
				
				if ( match = new RegExp( '(' + browserNames.join( '|' ) + ')' ).exec( userAgent ) ) {
					browser = translate( match[1], browserTranslations );
				}
				if ( match = new RegExp( '(' + layoutNames.join( '|' ) + ')' ).exec( userAgent ) ) {
					layout = translate( match[1], layoutTranslations );
				}
				if ( match = new RegExp( '(' + osNames.join( '|' ) + ')' ).exec( navigator.platform.toLowerCase() ) ) {
					var os = translate( match[1], osTranslations );
				}
				if ( match = new RegExp( '(' + versionPrefixes.join( '|' ) + ')' + versionSuffix ).exec( userAgent ) ) {
					version = match[3];
				}
				
				/* Edge Cases -- did I mention about how user agent string lie? */
				
				// Decode Safari's crazy 400+ version numbers
				if ( name.match( /safari/ ) && version > 400 ) {
					version = '2.0';
				}
				// Expose Opera 10's lies about being Opera 9.8
				if ( name === 'opera' && version >= 9.8) {
					version = userAgent.match( /version\/([0-9\.]*)/i )[1] || 10;
				}
				
				/* Caching */
				
				client = {
					'browser': browser,
					'layout': layout,
					'os': os,
					'version': version,
					'versionBase': ( version !== x ? new String( version ).substr( 0, 1 ) : x ),
					'versionNumber': ( parseFloat( version, 10 ) || 0.0 )
				};
			}
			return client;
		};
		/**
		 * Checks the current browser against a support map object to determine if the browser has been black-listed or
		 * not. If the browser was not configured specifically it is assumed to work. It is assumed that the body
		 * element is classified as either "ltr" or "rtl". If neither is set, "ltr" is assumed.
		 * 
		 * A browser map is in the following format:
		 *	{
		 * 		'ltr': {
		 * 			// Multiple rules with configurable operators
		 * 			'msie': [['>=', 7], ['!=', 9]],
		 *			// Blocked entirely
		 * 			'iphone': false
		 * 		},
		 * 		'rtl': {
		 * 			// Test against a string
		 * 			'msie': [['!==', '8.1.2.3']],
		 * 			// RTL rules do not fall through to LTR rules, you must explicity set each of them
		 * 			'iphone': false
		 * 		}
		 *	}
		 * 
		 * @param map Object of browser support map
		 * 
		 * @return Boolean true if browser known or assumed to be supported, false if blacklisted
		 */
		this.testClient = function( map ) {
			var client = this.client();
			// Check over each browser condition to determine if we are running in a compatible client
			var browser = map[$( 'body' ).is( '.rtl' ) ? 'rtl' : 'ltr'][client.browser];
			if ( typeof browser !== 'object' ) {
				// Unknown, so we assume it's working
				return true;
			}
			for ( var condition in browser ) {
				var op = browser[condition][0];
				var val = browser[condition][1];
				if ( val === false ) {
					return false;
				} else if ( typeof val == 'string' ) {
					if ( !( eval( 'client.version' + op + '"' + val + '"' ) ) ) {
						return false;
					}
				} else if ( typeof val == 'number' ) {
					if ( !( eval( 'client.versionNumber' + op + val ) ) ) {
						return false;
					}
				}
			}
			return true;
		};
	} )();
	// Attach to window
	window.mw = $.extend( 'mw' in window ? window.mw : {}, this );
} )();