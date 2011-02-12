/*
 * JavaScript backwards-compatibility alternatives and other convenience functions
 */

jQuery.extend({
	trimLeft : function( str ) {
		return str == null ? '' : str.toString().replace( /^\s+/, '' );
	},
	trimRight : function( str ) {
		return str == null ?
				'' : str.toString().replace( /\s+$/, '' );
	},
	ucFirst : function( str ) {
		return str.substr( 0, 1 ).toUpperCase() + str.substr( 1, str.length );
	},
	escapeRE : function( str ) {
		return str.replace ( /([\\{}()|.?*+^$\[\]])/g, "\\$1" );
	},
	// $.isDomElement( document.getElementById('content') ) === true
	// $.isDomElement( document.getElementsByClassName('portal') ) === false (array)
	// $.isDomElement( document.getElementsByClassName('portal')[0] ) === true
	// $.isDomElement( $('#content') ) === false (jQuery object)
	// $.isDomElement( $('#content').get(0) ) === true
	// $.isDomElement( 'hello world' ) === false
	isDomElement : function( el ) {
		return !!el.nodeType;
	},
	isEmpty : function( v ) {
		var key;
		if ( v === "" || v === 0 || v === "0" || v === null
			|| v === false || typeof v === 'undefined' )
		{
			return true;
		}
		// the for-loop could potentially contain prototypes
		// to avoid that we check it's length first
		if ( v.length === 0 ) {
			return true;
		}
		if ( typeof v === 'object' ) {
			for ( key in v ) {
				return false;
			}
			return true;
		}
		return false;
	},
	compareArray : function( arrThis, arrAgainst ) {
		if ( arrThis.length != arrAgainst.length ) {
			return false;
		}
		for ( var i = 0; i < arrThis.length; i++ ) {
			if ( arrThis[i] instanceof Array ) {
				if ( !$.compareArray( arrThis[i], arrAgainst[i] ) ) {
					return false;
				}
			} else if ( arrThis[i] !== arrAgainst[i] ) {
				return false;
			}
		}
		return true;
	},
	compareObject : function( objectA, objectB ) {
	
		// Do a simple check if the types match
		if ( typeof( objectA ) == typeof( objectB ) ) {
	
			// Only loop over the contents if it really is an object
			if ( typeof( objectA ) == 'object' ) {
				// If they are aliases of the same object (ie. mw and mediaWiki) return now
				if ( objectA === objectB ) {
					return true;
				} else {
					// Iterate over each property
					for ( var prop in objectA ) {
						// Check if this property is also present in the other object
						if ( prop in objectB ) {
							// Compare the types of the properties
							var type = typeof( objectA[prop] );
							if ( type == typeof( objectB[prop] ) ) {
								// Recursively check objects inside this one
								switch ( type ) {
									case 'object' :
										if ( !$.compareObject( objectA[prop], objectB[prop] ) ) {
											return false;
										}
										break;
									case 'function' :
										// Functions need to be strings to compare them properly
										if ( objectA[prop].toString() !== objectB[prop].toString() ) {
											return false;
										}
										break;
									default:
										// Strings, numbers
										if ( objectA[prop] !== objectB[prop] ) {
											return false;
										}
										break;
								}
							} else {
								return false;
							}
						} else {
							return false;
						}
					}
					// Check for properties in B but not in A
					// This is about 15% faster (tested in Safari 5 and Firefox 3.6)
					// ...than incrementing a count variable in the above and below loops
					// See also: http://www.mediawiki.org/wiki/ResourceLoader/Default_modules/compareObject_test#Results
					for ( var prop in objectB ) {
						if ( !( prop in objectA ) ) {
							return false;
						}
					}
				}
			}
		} else {
			return false;
		}
		return true;
	}
});

/*
 * Core MediaWiki JavaScript Library
 */

// Attach to window
window.mediaWiki = new ( function( $ ) {

	/* Constants */

	// This will not change until we are 100% ready to turn off legacy globals
	var LEGACY_GLOBALS = true;

	/* Private Members */

	// List of messages that have been requested to be loaded
	var messageQueue = {};

	/* Prototypes */

	/**
	 * An object which allows single and multiple get/set/exists functionality
	 * on a list of key / value pairs.
	 *
	 * @param {boolean} global Whether to get/set/exists values on the window
	 *   object or a private object
	 */
	function Map( global ) {
		this.values = ( global === true ) ? window : {};
	}

	/**
	 * Gets the value of a key, or a list of key/value pairs for an array of keys.
	 *
	 * If called with no arguments, all values will be returned.
	 *
	 * @param selection mixed Key or array of keys to get values for
	 * @param fallback mixed Value to use in case key(s) do not exist (optional)
	 */
	Map.prototype.get = function( selection, fallback ) {
		if ( typeof selection === 'object' ) {
			selection = $.makeArray( selection );
			var results = {};
			for ( var i = 0; i < selection.length; i++ ) {
				results[selection[i]] = this.get( selection[i], fallback );
			}
			return results;
		} else if ( typeof selection === 'string' ) {
			if ( typeof this.values[selection] === 'undefined' ) {
				if ( typeof fallback !== 'undefined' ) {
					return fallback;
				}
				return null;
			}
			return this.values[selection];
		}
		return this.values;
	};

	/**
	 * Sets one or multiple key/value pairs.
	 *
	 * @param selection mixed Key or object of key/value pairs to set
	 * @param value mixed Value to set (optional, only in use when key is a string)
	 */
	Map.prototype.set = function( selection, value ) {
		if ( typeof selection === 'object' ) {
			for ( var s in selection ) {
				this.values[s] = selection[s];
			}
		} else if ( typeof selection === 'string' && typeof value !== 'undefined' ) {
			this.values[selection] = value;
		}
	};

	/**
	 * Checks if one or multiple keys exist.
	 *
	 * @param selection mixed Key or array of keys to check
	 * @return boolean Existence of key(s)
	 */
	Map.prototype.exists = function( selection ) {
		if ( typeof selection === 'object' ) {
			for ( var s = 0; s < selection.length; s++ ) {
				if ( !( selection[s] in this.values ) ) {
					return false;
				}
			}
			return true;
		} else {
			return selection in this.values;
		}
	};

	/**
	 * Message object, similar to Message in PHP
	 */
	function Message( map, key, parameters ) {
		this.format = 'parse';
		this.map = map;
		this.key = key;
		this.parameters = typeof parameters === 'undefined' ? [] : $.makeArray( parameters );
	}

	/**
	 * Appends parameters for replacement
	 *
	 * @param parameters mixed First in a list of variadic arguments to append as message parameters
	 */
	Message.prototype.params = function( parameters ) {
		for ( var i = 0; i < parameters.length; i++ ) {
			this.parameters[this.parameters.length] = parameters[i];
		}
		return this;
	};

	/**
	 * Converts message object to it's string form based on the state of format
	 *
	 * @return {string} String form of message
	 */
	Message.prototype.toString = function() {
		if ( !this.map.exists( this.key ) ) {
			// Return <key> if key does not exist
			return '<' + this.key + '>';
		}
		var text = this.map.get( this.key );
		var parameters = this.parameters;
		text = text.replace( /\$(\d+)/g, function( string, match ) {
			var index = parseInt( match, 10 ) - 1;
			return index in parameters ? parameters[index] : '$' + match;
		} );
		/* This should be fixed up when we have a parser
		if ( this.format === 'parse' && 'language' in mediaWiki ) {
			text = mediaWiki.language.parse( text );
		}
		*/
		return text;
	};

	/**
	 * Changes format to parse and converts message to string
	 *
	 * @return {string} String form of parsed message
	 */
	Message.prototype.parse = function() {
		this.format = 'parse';
		return this.toString();
	};

	/**
	 * Changes format to plain and converts message to string
	 *
	 * @return {string} String form of plain message
	 */
	Message.prototype.plain = function() {
		this.format = 'plain';
		return this.toString();
	};

	/**
	 * Checks if message exists
	 *
	 * @return {string} String form of parsed message
	 */
	Message.prototype.exists = function() {
		return this.map.exists( this.key );
	};

	/**
	 * User object
	 */
	function User() {

		/* Private Members */

		var that = this;

		/* Public Members */

		this.options = new Map();

		/* Public Methods */

		/**
		 * Generates a random user session ID (32 alpha-numeric characters).
		 * 
		 * This information would potentially be stored in a cookie to identify a user during a
		 * session or series of sessions. It's uniqueness should not be depended on.
		 * 
		 * @return string random set of 32 alpha-numeric characters
		 */
		function generateId() {
			var id = '';
			var seed = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
			for ( var i = 0, r; i < 32; i++ ) {
				r = Math.floor( Math.random() * seed.length );
				id += seed.substring( r, r + 1 );
			}
			return id;
		}

		/**
		 * Gets the current user's name.
		 * 
		 * @return mixed user name string or null if users is anonymous
		 */
		this.name = function() {
			return mediaWiki.config.get( 'wgUserName' );
		};

		/**
		 * Checks if the current user is anonymous.
		 * 
		 * @return boolean
		 */
		this.anonymous = function() {
			return that.name() ? false : true;
		};

		/**
		 * Gets a random session ID automatically generated and kept in a cookie.
		 * 
		 * This ID is ephemeral for everyone, staying in their browser only until they close
		 * their browser.
		 * 
		 * Do not use this method before the first call to mediaWiki.loader.go(), it depends on
		 * jquery.cookie, which is added to the first pay-load just after mediaWiki is defined, but
		 * won't be loaded until the first call to go().
		 * 
		 * @return string user name or random session ID
		 */
		this.sessionId = function () {
			var sessionId = $.cookie( 'mediaWiki.user.sessionId' );
			if ( typeof sessionId == 'undefined' || sessionId == null ) {
				sessionId = generateId();
				$.cookie( 'mediaWiki.user.sessionId', sessionId, { 'expires': null, 'path': '/' } );
			}
			return sessionId;
		};

		/**
		 * Gets the current user's name or a random ID automatically generated and kept in a cookie.
		 * 
		 * This ID is persistent for anonymous users, staying in their browser up to 1 year. The
		 * expiration time is reset each time the ID is queried, so in most cases this ID will
		 * persist until the browser's cookies are cleared or the user doesn't visit for 1 year.
		 * 
		 * Do not use this method before the first call to mediaWiki.loader.go(), it depends on
		 * jquery.cookie, which is added to the first pay-load just after mediaWiki is defined, but
		 * won't be loaded until the first call to go().
		 * 
		 * @return string user name or random session ID
		 */
		this.id = function() {
			var name = that.name();
			if ( name ) {
				return name;
			}
			var id = $.cookie( 'mediaWiki.user.id' );
			if ( typeof id == 'undefined' || id == null ) {
				id = generateId();
			}
			// Set cookie if not set, or renew it if already set
			$.cookie( 'mediaWiki.user.id', id, { 'expires': 365, 'path': '/' } );
			return id;
		};
	}

	/* Public Members */

	/*
	 * Dummy function which in debug mode can be replaced with a function that
	 * does something clever
	 */
	this.log = function() { };

	/*
	 * Make the Map-class publicly available
	 */
	this.Map = Map;

	/*
	 * List of configuration values
	 *
	 * In legacy mode the values this object wraps will be in the global space
	 */
	this.config = new this.Map( LEGACY_GLOBALS );

	/*
	 * Information about the current user
	 */
	this.user = new User();

	/*
	 * Localization system
	 */
	this.messages = new this.Map();

	/* Public Methods */

	/**
	 * Gets a message object, similar to wfMessage()
	 *
	 * @param key string Key of message to get
	 * @param parameters mixed First argument in a list of variadic arguments, each a parameter for $
	 * replacement
	 */
	this.message = function( key, parameters ) {
		// Support variadic arguments
		if ( typeof parameters !== 'undefined' ) {
			parameters = $.makeArray( arguments );
			parameters.shift();
		} else {
			parameters = [];
		}
		return new Message( mediaWiki.messages, key, parameters );
	};

	/**
	 * Gets a message string, similar to wfMsg()
	 *
	 * @param key string Key of message to get
	 * @param parameters mixed First argument in a list of variadic arguments, each a parameter for $
	 * replacement
	 */
	this.msg = function( key, parameters ) {
		return mediaWiki.message.apply( mediaWiki.message, arguments ).toString();
	};

	/**
	 * Client-side module loader which integrates with the MediaWiki ResourceLoader
	 */
	this.loader = new ( function() {

		/* Private Members */

		/**
		 * Mapping of registered modules
		 *
		 * The jquery module is pre-registered, because it must have already
		 * been provided for this object to have been built, and in debug mode
		 * jquery would have been provided through a unique loader request,
		 * making it impossible to hold back registration of jquery until after
		 * mediawiki.
		 *
		 * Format:
		 * 	{
		 * 		'moduleName': {
		 * 			'dependencies': ['required module', 'required module', ...], (or) function() {}
		 * 			'state': 'registered', 'loading', 'loaded', 'ready', or 'error'
		 * 			'script': function() {},
		 * 			'style': 'css code string',
		 * 			'messages': { 'key': 'value' },
		 * 			'version': ############## (unix timestamp)
		 * 		}
		 * 	}
		 */
		var registry = {};
		// List of modules which will be loaded as when ready
		var batch = [];
		// List of modules to be loaded
		var queue = [];
		// List of callback functions waiting for modules to be ready to be called
		var jobs = [];
		// Flag indicating that requests should be suspended
		var suspended = true;
		// Flag inidicating that document ready has occured
		var ready = false;
		// Marker element for adding dynamic styles
		var $marker = $( 'head meta[name=ResourceLoaderDynamicStyles]' );

		/* Private Methods */

		function compare( a, b ) {
			if ( a.length != b.length ) {
				return false;
			}
			for ( var i = 0; i < b.length; i++ ) {
				if ( $.isArray( a[i] ) ) {
					if ( !compare( a[i], b[i] ) ) {
						return false;
					}
				}
				if ( a[i] !== b[i] ) {
					return false;
				}
			}
			return true;
		}

		/**
		 * Generates an ISO8601 "basic" string from a UNIX timestamp
		 */
		function formatVersionNumber( timestamp ) {
			function pad( a, b, c ) {
				return [a < 10 ? '0' + a : a, b < 10 ? '0' + b : b, c < 10 ? '0' + c : c].join( '' );
			}
			var d = new Date();
			d.setTime( timestamp * 1000 );
			return [
				pad( d.getUTCFullYear(), d.getUTCMonth() + 1, d.getUTCDate() ), 'T',
				pad( d.getUTCHours(), d.getUTCMinutes(), d.getUTCSeconds() ), 'Z'
			].join( '' );
		}

		/**
		 * Recursively resolves dependencies and detects circular references
		 */
		function recurse( module, resolved, unresolved ) {
			if ( typeof registry[module] === 'undefined' ) {
				throw new Error( 'Unknown dependency: ' + module );
			}
			// Resolves dynamic loader function and replaces it with its own results
			if ( typeof registry[module].dependencies === 'function' ) {
				registry[module].dependencies = registry[module].dependencies();
				// Ensures the module's dependencies are always in an array
				if ( typeof registry[module].dependencies !== 'object' ) {
					registry[module].dependencies = [registry[module].dependencies];
				}
			}
			// Tracks down dependencies
			for ( var n = 0; n < registry[module].dependencies.length; n++ ) {
				if ( $.inArray( registry[module].dependencies[n], resolved ) === -1 ) {
					if ( $.inArray( registry[module].dependencies[n], unresolved ) !== -1 ) {
						throw new Error(
							'Circular reference detected: ' + module +
							' -> ' + registry[module].dependencies[n]
						);
					}
					recurse( registry[module].dependencies[n], resolved, unresolved );
				}
			}
			resolved[resolved.length] = module;
			unresolved.splice( $.inArray( module, unresolved ), 1 );
		}

		/**
		 * Gets a list of module names that a module depends on in their proper dependency order
		 *
		 * @param module string module name or array of string module names
		 * @return list of dependencies
		 * @throws Error if circular reference is detected
		 */
		function resolve( module, resolved, unresolved ) {
			// Allow calling with an array of module names
			if ( typeof module === 'object' ) {
				var modules = [];
				for ( var m = 0; m < module.length; m++ ) {
					var dependencies = resolve( module[m] );
					for ( var n = 0; n < dependencies.length; n++ ) {
						modules[modules.length] = dependencies[n];
					}
				}
				return modules;
			} else if ( typeof module === 'string' ) {
				// Undefined modules have no dependencies
				if ( !( module in registry ) ) {
					return [];
				}
				var resolved = [];
				recurse( module, resolved, [] );
				return resolved;
			}
			throw new Error( 'Invalid module argument: ' + module );
		}

		/**
		 * Narrows a list of module names down to those matching a specific
		 * state. Possible states are 'undefined', 'registered', 'loading',
		 * 'loaded', or 'ready'
		 *
		 * @param states string or array of strings of module states to filter by
		 * @param modules array list of module names to filter (optional, all modules
		 *   will be used by default)
		 * @return array list of filtered module names
		 */
		function filter( states, modules ) {
			// Allow states to be given as a string
			if ( typeof states === 'string' ) {
				states = [states];
			}
			// If called without a list of modules, build and use a list of all modules
			var list = [];
			if ( typeof modules === 'undefined' ) {
				modules = [];
				for ( module in registry ) {
					modules[modules.length] = module;
				}
			}
			// Build a list of modules which are in one of the specified states
			for ( var s = 0; s < states.length; s++ ) {
				for ( var m = 0; m < modules.length; m++ ) {
					if ( typeof registry[modules[m]] === 'undefined' ) {
						// Module does not exist
						if ( states[s] == 'undefined' ) {
							// OK, undefined
							list[list.length] = modules[m];
						}
					} else {
						// Module exists, check state
						if ( registry[modules[m]].state === states[s] ) {
							// OK, correct state
							list[list.length] = modules[m];
						}
					}
				}
			}
			return list;
		}

		/**
		 * Executes a loaded module, making it ready to use
		 *
		 * @param module string module name to execute
		 */
		function execute( module ) {
			if ( typeof registry[module] === 'undefined' ) {
				throw new Error( 'Module has not been registered yet: ' + module );
			} else if ( registry[module].state === 'registered' ) {
				throw new Error( 'Module has not been requested from the server yet: ' + module );
			} else if ( registry[module].state === 'loading' ) {
				throw new Error( 'Module has not completed loading yet: ' + module );
			} else if ( registry[module].state === 'ready' ) {
				throw new Error( 'Module has already been loaded: ' + module );
			}
			// Add style sheet to document
			if ( typeof registry[module].style === 'string' && registry[module].style.length ) {
				$marker.before( mediaWiki.html.element( 'style',
						{ type: 'text/css' },
						new mediaWiki.html.Cdata( registry[module].style )
					) );
			} else if ( typeof registry[module].style === 'object'
				&& !( registry[module].style instanceof Array ) )
			{
				for ( var media in registry[module].style ) {
					$marker.before( mediaWiki.html.element( 'style',
						{ type: 'text/css', media: media },
						new mediaWiki.html.Cdata( registry[module].style[media] )
					) );
				}
			}
			// Add localizations to message system
			if ( typeof registry[module].messages === 'object' ) {
				mediaWiki.messages.set( registry[module].messages );
			}
			// Execute script
			try {
				registry[module].script( jQuery, mediaWiki );
				registry[module].state = 'ready';
				// Run jobs who's dependencies have just been met
				for ( var j = 0; j < jobs.length; j++ ) {
					if ( compare(
						filter( 'ready', jobs[j].dependencies ),
						jobs[j].dependencies ) )
					{
						if ( typeof jobs[j].ready === 'function' ) {
							jobs[j].ready();
						}
						jobs.splice( j, 1 );
						j--;
					}
				}
				// Execute modules who's dependencies have just been met
				for ( r in registry ) {
					if ( registry[r].state == 'loaded' ) {
						if ( compare(
							filter( ['ready'], registry[r].dependencies ),
							registry[r].dependencies ) )
						{
							execute( r );
						}
					}
				}
			} catch ( e ) {
				mediaWiki.log( 'Exception thrown by ' + module + ': ' + e.message );
				mediaWiki.log( e );
				registry[module].state = 'error';
				// Run error callbacks of jobs affected by this condition
				for ( var j = 0; j < jobs.length; j++ ) {
					if ( $.inArray( module, jobs[j].dependencies ) !== -1 ) {
						if ( typeof jobs[j].error === 'function' ) {
							jobs[j].error();
						}
						jobs.splice( j, 1 );
						j--;
					}
				}
			}
		}

		/**
		 * Adds a dependencies to the queue with optional callbacks to be run
		 * when the dependencies are ready or fail
		 *
		 * @param dependencies string module name or array of string module names
		 * @param ready function callback to execute when all dependencies are ready
		 * @param error function callback to execute when any dependency fails
		 */
		function request( dependencies, ready, error ) {
			// Allow calling by single module name
			if ( typeof dependencies === 'string' ) {
				dependencies = [dependencies];
				if ( dependencies[0] in registry ) {
					for ( var n = 0; n < registry[dependencies[0]].dependencies.length; n++ ) {
						dependencies[dependencies.length] =
							registry[dependencies[0]].dependencies[n];
					}
				}
			}
			// Add ready and error callbacks if they were given
			if ( arguments.length > 1 ) {
				jobs[jobs.length] = {
					'dependencies': filter(
						['undefined', 'registered', 'loading', 'loaded'],
						dependencies ),
					'ready': ready,
					'error': error
				};
			}
			// Queue up any dependencies that are undefined or registered
			dependencies = filter( ['undefined', 'registered'], dependencies );
			for ( var n = 0; n < dependencies.length; n++ ) {
				if ( $.inArray( dependencies[n], queue ) === -1 ) {
					queue[queue.length] = dependencies[n];
				}
			}
			// Work the queue
			mediaWiki.loader.work();
		}

		function sortQuery(o) {
			var sorted = {}, key, a = [];
			for ( key in o ) {
				if ( o.hasOwnProperty( key ) ) {
					a.push( key );
				}
			}
			a.sort();
			for ( key = 0; key < a.length; key++ ) {
				sorted[a[key]] = o[a[key]];
			}
			return sorted;
		}

		/* Public Methods */

		/**
		 * Requests dependencies from server, loading and executing when things when ready.
		 */
		this.work = function() {
			// Appends a list of modules to the batch
			for ( var q = 0; q < queue.length; q++ ) {
				// Only request modules which are undefined or registered
				if ( !( queue[q] in registry ) || registry[queue[q]].state == 'registered' ) {
					// Prevent duplicate entries
					if ( $.inArray( queue[q], batch ) === -1 ) {
						batch[batch.length] = queue[q];
						// Mark registered modules as loading
						if ( queue[q] in registry ) {
							registry[queue[q]].state = 'loading';
						}
					}
				}
			}
			// Clean up the queue
			queue = [];
			// After document ready, handle the batch
			if ( !suspended && batch.length ) {
				// Always order modules alphabetically to help reduce cache
				// misses for otherwise identical content
				batch.sort();
				// Build a list of request parameters
				var base = {
					'skin': mediaWiki.config.get( 'skin' ),
					'lang': mediaWiki.config.get( 'wgUserLanguage' ),
					'debug': mediaWiki.config.get( 'debug' )
				};
				// Extend request parameters with a list of modules in the batch
				var requests = [];
				// Split into groups
				var groups = {};
				for ( var b = 0; b < batch.length; b++ ) {
					var group = registry[batch[b]].group;
					if ( !( group in groups ) ) {
						groups[group] = [];
					}
					groups[group][groups[group].length] = batch[b];
				}
				for ( var group in groups ) {
					// Calculate the highest timestamp
					var version = 0;
					for ( var g = 0; g < groups[group].length; g++ ) {
						if ( registry[groups[group][g]].version > version ) {
							version = registry[groups[group][g]].version;
						}
					}
					requests[requests.length] = $.extend(
						{ 'modules': groups[group].join( '|' ), 'version': formatVersionNumber( version ) }, base
					);
				}
				// Clear the batch - this MUST happen before we append the
				// script element to the body or it's possible that the script
				// will be locally cached, instantly load, and work the batch
				// again, all before we've cleared it causing each request to
				// include modules which are already loaded
				batch = [];
				// Asynchronously append a script tag to the end of the body
				function request() {
					var html = '';
					for ( var r = 0; r < requests.length; r++ ) {
						requests[r] = sortQuery( requests[r] );
						// Build out the HTML
						var src = mediaWiki.config.get( 'wgLoadScript' ) + '?' + $.param( requests[r] );
						html += mediaWiki.html.element( 'script',
							{ type: 'text/javascript', src: src }, '' );
					}
					return html;
				}
				// Load asynchronously after doumument ready
				if ( ready ) {
					setTimeout( function() { $( 'body' ).append( request() ); }, 0 )
				} else {
					document.write( request() );
				}
			}
		};

		/**
		 * Registers a module, letting the system know about it and its
		 * dependencies. loader.js files contain calls to this function.
		 */
		this.register = function( module, version, dependencies, group ) {
			// Allow multiple registration
			if ( typeof module === 'object' ) {
				for ( var m = 0; m < module.length; m++ ) {
					if ( typeof module[m] === 'string' ) {
						mediaWiki.loader.register( module[m] );
					} else if ( typeof module[m] === 'object' ) {
						mediaWiki.loader.register.apply( mediaWiki.loader, module[m] );
					}
				}
				return;
			}
			// Validate input
			if ( typeof module !== 'string' ) {
				throw new Error( 'module must be a string, not a ' + typeof module );
			}
			if ( typeof registry[module] !== 'undefined' ) {
				throw new Error( 'module already implemeneted: ' + module );
			}
			// List the module as registered
			registry[module] = {
				'state': 'registered',
				'group': typeof group === 'string' ? group : null,
				'dependencies': [],
				'version': typeof version !== 'undefined' ? parseInt( version ) : 0
			};
			if ( typeof dependencies === 'string' ) {
				// Allow dependencies to be given as a single module name
				registry[module].dependencies = [dependencies];
			} else if ( typeof dependencies === 'object' || typeof dependencies === 'function' ) {
				// Allow dependencies to be given as an array of module names
				// or a function which returns an array
				registry[module].dependencies = dependencies;
			}
		};

		/**
		 * Implements a module, giving the system a course of action to take
		 * upon loading. Results of a request for one or more modules contain
		 * calls to this function.
		 */
		this.implement = function( module, script, style, localization ) {
			// Automatically register module
			if ( typeof registry[module] === 'undefined' ) {
				mediaWiki.loader.register( module );
			}
			// Validate input
			if ( typeof script !== 'function' ) {
				throw new Error( 'script must be a function, not a ' + typeof script );
			}
			if ( typeof style !== 'undefined'
				&& typeof style !== 'string'
				&& typeof style !== 'object' )
			{
				throw new Error( 'style must be a string or object, not a ' + typeof style );
			}
			if ( typeof localization !== 'undefined'
				&& typeof localization !== 'object' )
			{
				throw new Error( 'localization must be an object, not a ' + typeof localization );
			}
			if ( typeof registry[module] !== 'undefined'
				&& typeof registry[module].script !== 'undefined' )
			{
				throw new Error( 'module already implemeneted: ' + module );
			}
			// Mark module as loaded
			registry[module].state = 'loaded';
			// Attach components
			registry[module].script = script;
			if ( typeof style === 'string'
				|| typeof style === 'object' && !( style instanceof Array ) )
			{
				registry[module].style = style;
			}
			if ( typeof localization === 'object' ) {
				registry[module].messages = localization;
			}
			// Execute or queue callback
			if ( compare(
				filter( ['ready'], registry[module].dependencies ),
				registry[module].dependencies ) )
			{
				execute( module );
			} else {
				request( module );
			}
		};

		/**
		 * Executes a function as soon as one or more required modules are ready
		 *
		 * @param dependencies string or array of strings of modules names the callback
		 *   dependencies to be ready before
		 * executing
		 * @param ready function callback to execute when all dependencies are ready (optional)
		 * @param error function callback to execute when if dependencies have a errors (optional)
		 */
		this.using = function( dependencies, ready, error ) {
			// Validate input
			if ( typeof dependencies !== 'object' && typeof dependencies !== 'string' ) {
				throw new Error( 'dependencies must be a string or an array, not a ' +
					typeof dependencies )
			}
			// Allow calling with a single dependency as a string
			if ( typeof dependencies === 'string' ) {
				dependencies = [dependencies];
			}
			// Resolve entire dependency map
			dependencies = resolve( dependencies );
			// If all dependencies are met, execute ready immediately
			if ( compare( filter( ['ready'], dependencies ), dependencies ) ) {
				if ( typeof ready === 'function' ) {
					ready();
				}
			}
			// If any dependencies have errors execute error immediately
			else if ( filter( ['error'], dependencies ).length ) {
				if ( typeof error === 'function' ) {
					error();
				}
			}
			// Since some dependencies are not yet ready, queue up a request
			else {
				request( dependencies, ready, error );
			}
		};

		/**
		 * Loads an external script or one or more modules for future use
		 *
		 * @param modules mixed either the name of a module, array of modules,
		 *   or a URL of an external script or style
		 * @param type string mime-type to use if calling with a URL of an
		 *   external script or style; acceptable values are "text/css" and
		 *   "text/javascript"; if no type is provided, text/javascript is
		 *   assumed
		 */
		this.load = function( modules, type ) {
			// Validate input
			if ( typeof modules !== 'object' && typeof modules !== 'string' ) {
				throw new Error( 'dependencies must be a string or an array, not a ' +
					typeof dependencies )
			}
			// Allow calling with an external script or single dependency as a string
			if ( typeof modules === 'string' ) {
				// Support adding arbitrary external scripts
				if ( modules.substr( 0, 7 ) == 'http://'
					|| modules.substr( 0, 8 ) == 'https://' )
				{
					if ( type === 'text/css' ) {
						$( 'head' )
							.append( $( '<link rel="stylesheet" type="text/css" />' )
							.attr( 'href', modules ) );
						return true;
					} else if ( type === 'text/javascript' || typeof type === 'undefined' ) {
						var script = mediaWiki.html.element( 'script',
							{ type: 'text/javascript', src: modules }, '' );
						if ( ready ) {
							$( 'body' ).append( script );
						} else {
							document.write( script );
						}
						return true;
					}
					// Unknown type
					return false;
				}
				// Called with single module
				modules = [modules];
			}
			// Resolve entire dependency map
			modules = resolve( modules );
			// If all modules are ready, nothing dependency be done
			if ( compare( filter( ['ready'], modules ), modules ) ) {
				return true;
			}
			// If any modules have errors return false
			else if ( filter( ['error'], modules ).length ) {
				return false;
			}
			// Since some modules are not yet ready, queue up a request
			else {
				request( modules );
				return true;
			}
		};

		/**
		 * Flushes the request queue and begin executing load requests on demand
		 */
		this.go = function() {
			suspended = false;
			mediaWiki.loader.work();
		};

		/**
		 * Changes the state of a module
		 *
		 * @param module string module name or object of module name/state pairs
		 * @param state string state name
		 */
		this.state = function( module, state ) {
			if ( typeof module === 'object' ) {
				for ( var m in module ) {
					mediaWiki.loader.state( m, module[m] );
				}
				return;
			}
			if ( !( module in registry ) ) {
				mediaWiki.loader.register( module );
			}
			registry[module].state = state;
		};

		/**
		 * Gets the version of a module
		 *
		 * @param module string name of module to get version for
		 */
		this.version = function( module ) {
			if ( module in registry && 'version' in registry[module] ) {
				return formatVersionNumber( registry[module].version );
			}
			return null;
		};

		/* Cache document ready status */

		$(document).ready( function() { ready = true; } );
	} )();

	/** HTML construction helper functions */
	this.html = new ( function () {
		function escapeCallback( s ) {
			switch ( s ) {
				case "'":
					return '&#039;';
				case '"':
					return '&quot;';
				case '<':
					return '&lt;';
				case '>':
					return '&gt;';
				case '&':
					return '&amp;';
			}
		}

		/**
		 * Escape a string for HTML. Converts special characters to HTML entities.
		 * @param s The string to escape
		 */
		this.escape = function( s ) {
			return s.replace( /['"<>&]/g, escapeCallback );
		};

		/**
		 * Wrapper object for raw HTML passed to mediaWiki.html.element().
		 */
		this.Raw = function( value ) {
			this.value = value;
		};

		/**
		 * Wrapper object for CDATA element contents passed to mediaWiki.html.element()
		 */
		this.Cdata = function( value ) {
			this.value = value;
		};

		/**
		 * Create an HTML element string, with safe escaping.
		 *
		 * @param name The tag name.
		 * @param attrs An object with members mapping element names to values
		 * @param contents The contents of the element. May be either:
		 *    - string: The string is escaped.
		 *    - null or undefined: The short closing form is used, e.g. <br/>.
		 *    - this.Raw: The value attribute is included without escaping.
		 *    - this.Cdata: The value attribute is included, and an exception is
		 *      thrown if it contains an illegal ETAGO delimiter.
		 *      See http://www.w3.org/TR/1999/REC-html401-19991224/appendix/notes.html#h-B.3.2
		 *
		 * Example:
		 *    var h = mediaWiki.html;
		 *    return h.element( 'div', {},
		 *        new h.Raw( h.element( 'img', {src: '<'} ) ) );
		 * Returns <div><img src="&lt;"/></div>
		 */
		this.element = function( name, attrs, contents ) {
			var s = '<' + name;
			for ( var attrName in attrs ) {
				s += ' ' + attrName + '="' + this.escape( attrs[attrName] ) + '"';
			}
			if ( typeof contents == 'undefined' || contents === null ) {
				// Self close tag
				s += '/>';
				return s;
			}
			// Regular open tag
			s += '>';
			if ( typeof contents === 'string') {
				// Escaped
				s += this.escape( contents );
			} else if ( contents instanceof this.Raw ) {
				// Raw HTML inclusion
				s += contents.value;
			} else if ( contents instanceof this.Cdata ) {
				// CDATA
				if ( /<\/[a-zA-z]/.test( contents.value ) ) {
					throw new Error( 'mw.html.element: Illegal end tag found in CDATA' );
				}
				s += contents.value;
			} else {
				throw new Error( 'mw.html.element: Invalid type of contents' );
			}
			s += '</' + name + '>';
			return s;
		};
	} )();


	/* Extension points */

	this.legacy = {};

} )( jQuery );

/* Auto-register from pre-loaded startup scripts */

if ( typeof startUp === 'function' ) {
	startUp();
	delete startUp;
}

// Add jQuery Cookie to initial payload (used in mediaWiki.user)
mediaWiki.loader.load( 'jquery.cookie' );

// Alias $j to jQuery for backwards compatibility
window.$j = jQuery;
window.mw = mediaWiki;
