/*
 * Core MediaWiki JavaScript Library
 */

// Attach to window and globally alias
window.mw = window.mediaWiki = new ( function( $ ) {

	/* Private Members */

	/**
	 * @var object List of messages that have been requested to be loaded.
	 */
	var messageQueue = {};

	/* Object constructors */

	/**
	 * Map
	 *
	 * Creates an object that can be read from or written to from prototype functions
	 * that allow both single and multiple variables at once.
	 *
	 * @param global boolean Whether to store the values in the global window
	 *  object or a exclusively in the object property 'values'.
	 * @return Map
	 */
	function Map( global ) {
		this.values = ( global === true ) ? window : {};
		return this;
	}

	Map.prototype = {
		/**
		 * Get the value of one or multiple a keys.
		 *
		 * If called with no arguments, all values will be returned.
		 *
		 * @param selection mixed String key or array of keys to get values for.
		 * @param fallback mixed Value to use in case key(s) do not exist (optional).
		 * @return mixed If selection was a string returns the value or null,
		 *  If selection was an array, returns an object of key/values (value is null if not found),
		 *  If selection was not passed or invalid, will return the 'values' object member (be careful as
		 *  objects are always passed by reference in JavaScript!).
		 * @return Values as a string or object, null if invalid/inexistant.
		 */
		get: function( selection, fallback ) {
			if ( $.isArray( selection ) ) {
				selection = $.makeArray( selection );
				var results = {};
				for ( var i = 0; i < selection.length; i++ ) {
					results[selection[i]] = this.get( selection[i], fallback );
				}
				return results;
			} else if ( typeof selection === 'string' ) {
				if ( this.values[selection] === undefined ) {
					if ( fallback !== undefined ) {
						return fallback;
					}
					return null;
				}
				return this.values[selection];
			}
			if ( selection === undefined ) {
				return this.values;
			} else {
				return null; // invalid selection key
			}
		},

		/**
		 * Sets one or multiple key/value pairs.
		 *
		 * @param selection mixed String key or array of keys to set values for.
		 * @param value mixed Value to set (optional, only in use when key is a string)
		 * @return bool This returns true on success, false on failure.
		 */
		set: function( selection, value ) {
			if ( $.isPlainObject( selection ) ) {
				for ( var s in selection ) {
					this.values[s] = selection[s];
				}
				return true;
			} else if ( typeof selection === 'string' && value !== undefined ) {
				this.values[selection] = value;
				return true;
			}
			return false;
		},

		/**
		 * Checks if one or multiple keys exist.
		 *
		 * @param selection mixed String key or array of keys to check
		 * @return boolean Existence of key(s)
		 */
		exists: function( selection ) {
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
		}
	};

	/**
	 * Message
	 *
	 * Object constructor for messages,
	 * similar to the Message class in MediaWiki PHP.
	 *
	 * @param map Map Instance of mw.Map
	 * @param key String
	 * @param parameters Array
	 * @return Message
	 */
	function Message( map, key, parameters ) {
		this.format = 'parse';
		this.map = map;
		this.key = key;
		this.parameters = parameters === undefined ? [] : $.makeArray( parameters );
		return this;
	}

	Message.prototype = {
		/**
		 * Appends (does not replace) parameters for replacement to the .parameters property.
		 *
		 * @param parameters Array
		 * @return Message
		 */
		params: function( parameters ) {
			for ( var i = 0; i < parameters.length; i++ ) {
				this.parameters.push( parameters[i] );
			}
			return this;
		},

		/**
		 * Converts message object to it's string form based on the state of format.
		 *
		 * @return string Message as a string in the current form or <key> if key does not exist.
		 */
		toString: function() {
			if ( !this.map.exists( this.key ) ) {
				// Return <key> if key does not exist
				return '<' + this.key + '>';
			}
			var	text = this.map.get( this.key ),
				parameters = this.parameters;

			text = text.replace( /\$(\d+)/g, function( string, match ) {
				var index = parseInt( match, 10 ) - 1;
				return index in parameters ? parameters[index] : '$' + match;
			} );

			if ( this.format === 'plain' ) {
				return text;
			}
			if ( this.format === 'escaped' ) {
				// According to Message.php this needs {{-transformation, which is
				// still todo
				return mw.html.escape( text );
			}

			/* This should be fixed up when we have a parser
			if ( this.format === 'parse' && 'language' in mw ) {
				text = mw.language.parse( text );
			}
			*/
			return text;
		},

		/**
		 * Changes format to parse and converts message to string
		 *
		 * @return {string} String form of parsed message
		 */
		parse: function() {
			this.format = 'parse';
			return this.toString();
		},

		/**
		 * Changes format to plain and converts message to string
		 *
		 * @return {string} String form of plain message
		 */
		plain: function() {
			this.format = 'plain';
			return this.toString();
		},

		/**
		 * Changes the format to html escaped and converts message to string
		 *
		 * @return {string} String form of html escaped message
		 */
		escaped: function() {
			this.format = 'escaped';
			return this.toString();
		},

		/**
		 * Checks if message exists
		 *
		 * @return {string} String form of parsed message
		 */
		exists: function() {
			return this.map.exists( this.key );
		}
	};

	/* Public Members */

	/*
	 * Dummy function which in debug mode can be replaced with a function that
	 * emulates console.log in console-less environments.
	 */
	this.log = function() { };

	/**
	 * @var constructor Make the Map constructor publicly available.
	 */
	this.Map = Map;

	/**
	 * List of configuration values
	 *
	 * Dummy placeholder. Initiated in startUp module as a new instance of mw.Map().
	 * If $wgLegacyJavaScriptGlobals is true, this Map will have its values
	 * in the global window object.
	 */
	this.config = null;

	/**
	 * @var object
	 *
	 * Empty object that plugins can be installed in.
	 */
	this.libs = {};

	/*
	 * Localization system
	 */
	this.messages = new this.Map();

	/* Public Methods */

	/**
	 * Gets a message object, similar to wfMessage()
	 *
	 * @param key string Key of message to get
	 * @param parameter_1 mixed First argument in a list of variadic arguments,
	 *  each a parameter for $N replacement in messages.
	 * @return Message
	 */
	this.message = function( key, parameter_1 /* [, parameter_2] */ ) {
		var parameters;
		// Support variadic arguments
		if ( parameter_1 !== undefined ) {
			parameters = $.makeArray( arguments );
			parameters.shift();
		} else {
			parameters = [];
		}
		return new Message( mw.messages, key, parameters );
	};

	/**
	 * Gets a message string, similar to wfMsg()
	 *
	 * @param key string Key of message to get
	 * @param parameters mixed First argument in a list of variadic arguments,
	 *  each a parameter for $N replacement in messages.
	 * @return String.
	 */
	this.msg = function( key, parameters ) {
		return mw.message.apply( mw.message, arguments ).toString();
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
		 * For exact details on support for script, style and messages, look at
		 * mw.loader.implement.
		 *
		 * Format:
		 *	{
		 *		'moduleName': {
		 *			'version': ############## (unix timestamp),
		 *			'dependencies': ['required.foo', 'bar.also', ...], (or) function() {}
		 *			'group': 'somegroup', (or) null,
		 *			'source': 'local', 'someforeignwiki', (or) null
		 *			'state': 'registered', 'loading', 'loaded', 'ready', or 'error'
		 *			'script': ...,
		 *			'style': ...,
		 *			'messages': { 'key': 'value' },
		 *		}
		 *	}
		 */
		var	registry = {},
			/**
			 * Mapping of sources, keyed by source-id, values are objects.
			 * Format:
			 *	{
			 *		'sourceId': {
			 *			'loadScript': 'http://foo.bar/w/load.php'
			 *		}
			 *	}
			 */
			sources = {},
			// List of modules which will be loaded as when ready
			batch = [],
			// List of modules to be loaded
			queue = [],
			// List of callback functions waiting for modules to be ready to be called
			jobs = [],
			// Flag inidicating that document ready has occured
			ready = false,
			// Selector cache for the marker element. Use getMarker() to get/use the marker!
			$marker = null;

		/* Private Methods */

		function getMarker(){
			// Cached ?
			if ( $marker ) {
				return $marker;
			} else {
				$marker = $( 'meta[name="ResourceLoaderDynamicStyles"]' );
				if ( $marker.length ) {
					return $marker;
				}
				mw.log( 'getMarker> No <meta name="ResourceLoaderDynamicStyles"> found, inserting dynamically.' );
				$marker = $( '<meta>' ).attr( 'name', 'ResourceLoaderDynamicStyles' ).appendTo( 'head' );
				return $marker;
			}
		}

		function compare( a, b ) {
			if ( a.length !== b.length ) {
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
			var	pad = function( a, b, c ) {
					return [a < 10 ? '0' + a : a, b < 10 ? '0' + b : b, c < 10 ? '0' + c : c].join( '' );
				},
				d = new Date();
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
			if ( registry[module] === undefined ) {
				throw new Error( 'Unknown dependency: ' + module );
			}
			// Resolves dynamic loader function and replaces it with its own results
			if ( $.isFunction( registry[module].dependencies ) ) {
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
		function resolve( module ) {
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
		 *  will be used by default)
		 * @return array list of filtered module names
		 */
		function filter( states, modules ) {
			// Allow states to be given as a string
			if ( typeof states === 'string' ) {
				states = [states];
			}
			// If called without a list of modules, build and use a list of all modules
			var list = [], module;
			if ( modules === undefined ) {
				modules = [];
				for ( module in registry ) {
					modules[modules.length] = module;
				}
			}
			// Build a list of modules which are in one of the specified states
			for ( var s = 0; s < states.length; s++ ) {
				for ( var m = 0; m < modules.length; m++ ) {
					if ( registry[modules[m]] === undefined ) {
						// Module does not exist
						if ( states[s] === 'undefined' ) {
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
		function execute( module, callback ) {
			if ( registry[module] === undefined ) {
				throw new Error( 'Module has not been registered yet: ' + module );
			} else if ( registry[module].state === 'registered' ) {
				throw new Error( 'Module has not been requested from the server yet: ' + module );
			} else if ( registry[module].state === 'loading' ) {
				throw new Error( 'Module has not completed loading yet: ' + module );
			} else if ( registry[module].state === 'ready' ) {
				throw new Error( 'Module has already been loaded: ' + module );
			}
			// Add styles
			var style;
			if ( $.isPlainObject( registry[module].style ) ) {
				for ( var media in registry[module].style ) {
					style = registry[module].style[media];
					if ( $.isArray( style ) ) {
						for ( var i = 0; i < style.length; i++ ) {
							getMarker().before( mw.html.element( 'link', {
								'type': 'text/css',
								'rel': 'stylesheet',
								'href': style[i]
							} ) );
						}
					} else if ( typeof style === 'string' ) {
						getMarker().before( mw.html.element( 'style', {
							'type': 'text/css',
							'media': media
						}, new mw.html.Cdata( style ) ) );
					}
				}
			}
			// Add localizations to message system
			if ( $.isPlainObject( registry[module].messages ) ) {
				mw.messages.set( registry[module].messages );
			}
			// Execute script
			try {
				var	script = registry[module].script,
					markModuleReady = function() {
						registry[module].state = 'ready';
						handlePending( module );
						if ( $.isFunction( callback ) ) {
							callback();
						}
					};

				if ( $.isArray( script ) ) {
					var done = 0;
					if ( script.length === 0 ) {
						// No scripts in this module? Let's dive out early.
						markModuleReady();
					}
					for ( var i = 0; i < script.length; i++ ) {
						registry[module].state = 'loading';
						addScript( script[i], function() {
							if ( ++done === script.length ) {
								markModuleReady();
							}
						} );
					}
				} else if ( $.isFunction( script ) ) {
					script( jQuery );
					markModuleReady();
				}
			} catch ( e ) {
				// This needs to NOT use mw.log because these errors are common in production mode
				// and not in debug mode, such as when a symbol that should be global isn't exported
				if ( window.console && typeof window.console.log === 'function' ) {
					console.log( 'mw.loader::execute> Exception thrown by ' + module + ': ' + e.message );
				}
				registry[module].state = 'error';
				throw e;
			}
		}

		/**
		 * Automatically executes jobs and modules which are pending with satistifed dependencies.
		 *
		 * This is used when dependencies are satisfied, such as when a module is executed.
		 */
		function handlePending( module ) {
			try {
				// Run jobs who's dependencies have just been met
				for ( var j = 0; j < jobs.length; j++ ) {
					if ( compare(
						filter( 'ready', jobs[j].dependencies ),
						jobs[j].dependencies ) )
					{
						if ( $.isFunction( jobs[j].ready ) ) {
							jobs[j].ready();
						}
						jobs.splice( j, 1 );
						j--;
					}
				}
				// Execute modules who's dependencies have just been met
				for ( var r in registry ) {
					if ( registry[r].state === 'loaded' ) {
						if ( compare(
							filter( ['ready'], registry[r].dependencies ),
							registry[r].dependencies ) )
						{
							execute( r );
						}
					}
				}
			} catch ( e ) {
				// Run error callbacks of jobs affected by this condition
				for ( var j = 0; j < jobs.length; j++ ) {
					if ( $.inArray( module, jobs[j].dependencies ) !== -1 ) {
						if ( $.isFunction( jobs[j].error ) ) {
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
					// Cache repetitively accessed deep level object member
					var regItemDeps = registry[dependencies[0]].dependencies,
					// Cache to avoid looped access to length property
					regItemDepLen = regItemDeps.length;
					for ( var n = 0; n < regItemDepLen; n++ ) {
						dependencies[dependencies.length] = regItemDeps[n];
					}
				}
			}
			// Add ready and error callbacks if they were given
			if ( arguments.length > 1 ) {
				jobs[jobs.length] = {
					'dependencies': filter(
						['undefined', 'registered', 'loading', 'loaded'],
						dependencies
					),
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
			mw.loader.work();
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

		/**
		 * Converts a module map of the form { foo: [ 'bar', 'baz' ], bar: [ 'baz, 'quux' ] }
		 * to a query string of the form foo.bar,baz|bar.baz,quux
		 */
		function buildModulesString( moduleMap ) {
			var arr = [], p;
			for ( var prefix in moduleMap ) {
				p = prefix === '' ? '' : prefix + '.';
				arr.push( p + moduleMap[prefix].join( ',' ) );
			}
			return arr.join( '|' );
		}

		/**
		 * Adds a script tag to the body, either using document.write or low-level DOM manipulation,
		 * depending on whether document-ready has occured yet.
		 *
		 * @param src String: URL to script, will be used as the src attribute in the script tag
		 * @param callback Function: Optional callback which will be run when the script is done
		 */
		function addScript( src, callback ) {
			var done = false, script;
			if ( ready ) {
				// jQuery's getScript method is NOT better than doing this the old-fassioned way
				// because jQuery will eval the script's code, and errors will not have sane
				// line numbers.
				script = document.createElement( 'script' );
				script.setAttribute( 'src', src );
				script.setAttribute( 'type', 'text/javascript' );
				if ( $.isFunction( callback ) ) {
					// Attach handlers for all browsers -- this is based on jQuery.ajax
					script.onload = script.onreadystatechange = function() {

						if (
							!done
							&& (
								!script.readyState
								|| /loaded|complete/.test( script.readyState )
							)
						) {

							done = true;

							// Handle memory leak in IE
							script.onload = script.onreadystatechange = null;

							callback();

							if ( script.parentNode ) {
								script.parentNode.removeChild( script );
							}

							// Dereference the script
							script = undefined;
						}
					};
				}
				document.body.appendChild( script );
			} else {
				document.write( mw.html.element(
					'script', { 'type': 'text/javascript', 'src': src }, ''
				) );
				if ( $.isFunction( callback ) ) {
					// Document.write is synchronous, so this is called when it's done
					callback();
				}
			}
		}

		/**
		 * Asynchronously append a script tag to the end of the body
		 * that invokes load.php
		 * @param moduleMap {Object}: Module map, see buildModulesString()
		 * @param currReqBase {Object}: Object with other parameters (other than 'modules') to use in the request
		 * @param sourceLoadScript {String}: URL of load.php
		 */
		function doRequest( moduleMap, currReqBase, sourceLoadScript ) {
			var request = $.extend(
				{ 'modules': buildModulesString( moduleMap ) },
				currReqBase
			);
			request = sortQuery( request );
			// Asynchronously append a script tag to the end of the body
			// Append &* to avoid triggering the IE6 extension check
			addScript( sourceLoadScript + '?' + $.param( request ) + '&*' );
		}

		/* Public Methods */

		/**
		 * Requests dependencies from server, loading and executing when things when ready.
		 */
		this.work = function() {
				// Build a list of request parameters common to all requests.
			var	reqBase = {
					skin: mw.config.get( 'skin' ),
					lang: mw.config.get( 'wgUserLanguage' ),
					debug: mw.config.get( 'debug' )
				},
				// Split module batch by source and by group.
				splits = {},
				maxQueryLength = mw.config.get( 'wgResourceLoaderMaxQueryLength', -1 );

			// Appends a list of modules from the queue to the batch
			for ( var q = 0; q < queue.length; q++ ) {
				// Only request modules which are undefined or registered
				if ( !( queue[q] in registry ) || registry[queue[q]].state === 'registered' ) {
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
			// Early exit if there's nothing to load...
			if ( !batch.length ) {
				return;
			}

			// The queue has been processed into the batch, clear up the queue.
			queue = [];

			// Always order modules alphabetically to help reduce cache
			// misses for otherwise identical content.
			batch.sort();

			// Split batch by source and by group.
			for ( var b = 0; b < batch.length; b++ ) {
				var	bSource = registry[batch[b]].source,
					bGroup = registry[batch[b]].group;
				if ( !( bSource in splits ) ) {
					splits[bSource] = {};
				}
				if ( !( bGroup in splits[bSource] ) ) {
					splits[bSource][bGroup] = [];
				}
				var bSourceGroup = splits[bSource][bGroup];
				bSourceGroup[bSourceGroup.length] = batch[b];
			}

			// Clear the batch - this MUST happen before we append any
			// script elements to the body or it's possible that a script
			// will be locally cached, instantly load, and work the batch
			// again, all before we've cleared it causing each request to
			// include modules which are already loaded.
			batch = [];

			var source, group, modules, maxVersion, sourceLoadScript;

			for ( source in splits ) {

				sourceLoadScript = sources[source].loadScript;

				for ( group in splits[source] ) {

					// Cache access to currently selected list of
					// modules for this group from this source.
					modules = splits[source][group];

					// Calculate the highest timestamp
					maxVersion = 0;
					for ( var g = 0; g < modules.length; g++ ) {
						if ( registry[modules[g]].version > maxVersion ) {
							maxVersion = registry[modules[g]].version;
						}
					}

					var	currReqBase = $.extend( { 'version': formatVersionNumber( maxVersion ) }, reqBase ),
						currReqBaseLength = $.param( currReqBase ).length,
						moduleMap = {},
						// We may need to split up the request to honor the query string length limit,
						// so build it piece by piece.
						l = currReqBaseLength + 9; // '&modules='.length == 9

					moduleMap = {}; // { prefix: [ suffixes ] }

					for ( var i = 0; i < modules.length; i++ ) {
							// Determine how many bytes this module would add to the query string
						var	lastDotIndex = modules[i].lastIndexOf( '.' ),
							// Note that these substr() calls work even if lastDotIndex == -1
							prefix = modules[i].substr( 0, lastDotIndex ),
							suffix = modules[i].substr( lastDotIndex + 1 ),
							bytesAdded = prefix in moduleMap
								? suffix.length + 3 // '%2C'.length == 3
								: modules[i].length + 3; // '%7C'.length == 3

						// If the request would become too long, create a new one,
						// but don't create empty requests
						if ( maxQueryLength > 0 && !$.isEmptyObject( moduleMap ) && l + bytesAdded > maxQueryLength ) {
							// This request would become too long, create a new one
							// and fire off the old one
							doRequest( moduleMap, currReqBase, sourceLoadScript );
							moduleMap = {};
							l = currReqBaseLength + 9;
						}
						if ( !( prefix in moduleMap ) ) {
							moduleMap[prefix] = [];
						}
						moduleMap[prefix].push( suffix );
						l += bytesAdded;
					}
					// If there's anything left in moduleMap, request that too
					if ( !$.isEmptyObject( moduleMap ) ) {
						doRequest( moduleMap, currReqBase, sourceLoadScript );
					}
				}
			}
		};

		/**
		 * Register a source.
		 *
		 * @param id {String}: Short lowercase a-Z string representing a source, only used internally.
		 * @param props {Object}: Object containing only the loadScript property which is a url to
		 * the load.php location of the source.
		 * @return {Boolean}
		 */
		this.addSource = function( id, props ) {
			// Allow multiple additions
			if ( typeof id === 'object' ) {
				for ( var source in id ) {
					mw.loader.addSource( source, id[source] );
				}
				return true;
			}

			if ( sources[id] !== undefined ) {
				throw new Error( 'source already registered: ' + id );
			}

			sources[id] = props;

			return true;
		};

		/**
		 * Registers a module, letting the system know about it and its
		 * properties. Startup modules contain calls to this function.
		 *
		 * @param module {String}: Module name
		 * @param version {Number}: Module version number as a timestamp (falls backs to 0)
		 * @param dependencies {String|Array|Function}: One string or array of strings of module
		 *  names on which this module depends, or a function that returns that array.
		 * @param group {String}: Group which the module is in (optional, defaults to null)
		 * @param source {String}: Name of the source. Defaults to local.
		 */
		this.register = function( module, version, dependencies, group, source ) {
			// Allow multiple registration
			if ( typeof module === 'object' ) {
				for ( var m = 0; m < module.length; m++ ) {
					// module is an array of module names
					if ( typeof module[m] === 'string' ) {
						mw.loader.register( module[m] );
					// module is an array of arrays
					} else if ( typeof module[m] === 'object' ) {
						mw.loader.register.apply( mw.loader, module[m] );
					}
				}
				return;
			}
			// Validate input
			if ( typeof module !== 'string' ) {
				throw new Error( 'module must be a string, not a ' + typeof module );
			}
			if ( registry[module] !== undefined ) {
				throw new Error( 'module already implemented: ' + module );
			}
			// List the module as registered
			registry[module] = {
				'version': version !== undefined ? parseInt( version, 10 ) : 0,
				'dependencies': [],
				'group': typeof group === 'string' ? group : null,
				'source': typeof source === 'string' ? source: 'local',
				'state': 'registered'
			};
			if ( typeof dependencies === 'string' ) {
				// Allow dependencies to be given as a single module name
				registry[module].dependencies = [dependencies];
			} else if ( typeof dependencies === 'object' || $.isFunction( dependencies ) ) {
				// Allow dependencies to be given as an array of module names
				// or a function which returns an array
				registry[module].dependencies = dependencies;
			}
		};

		/**
		 * Implements a module, giving the system a course of action to take
		 * upon loading. Results of a request for one or more modules contain
		 * calls to this function.
		 *
		 * All arguments are required.
		 *
		 * @param module String: Name of module
		 * @param script Mixed: Function of module code or String of URL to be used as the src
		 *  attribute when adding a script element to the body
		 * @param style Object: Object of CSS strings keyed by media-type or Object of lists of URLs
		 *  keyed by media-type
		 * @param msgs Object: List of key/value pairs to be passed through mw.messages.set
		 */
		this.implement = function( module, script, style, msgs ) {
			// Validate input
			if ( typeof module !== 'string' ) {
				throw new Error( 'module must be a string, not a ' + typeof module );
			}
			if ( !$.isFunction( script ) && !$.isArray( script ) ) {
				throw new Error( 'script must be a function or an array, not a ' + typeof script );
			}
			if ( !$.isPlainObject( style ) ) {
				throw new Error( 'style must be an object, not a ' + typeof style );
			}
			if ( !$.isPlainObject( msgs ) ) {
				throw new Error( 'msgs must be an object, not a ' + typeof msgs );
			}
			// Automatically register module
			if ( registry[module] === undefined ) {
				mw.loader.register( module );
			}
			// Check for duplicate implementation
			if ( registry[module] !== undefined && registry[module].script !== undefined ) {
				throw new Error( 'module already implemeneted: ' + module );
			}
			// Mark module as loaded
			registry[module].state = 'loaded';
			// Attach components
			registry[module].script = script;
			registry[module].style = style;
			registry[module].messages = msgs;
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
		 *  dependencies to be ready before executing
		 * @param ready function callback to execute when all dependencies are ready (optional)
		 * @param error function callback to execute when if dependencies have a errors (optional)
		 */
		this.using = function( dependencies, ready, error ) {
			var tod = typeof dependencies;
			// Validate input
			if ( tod !== 'object' && tod !== 'string' ) {
				throw new Error( 'dependencies must be a string or an array, not a ' + tod );
			}
			// Allow calling with a single dependency as a string
			if ( tod === 'string' ) {
				dependencies = [dependencies];
			}
			// Resolve entire dependency map
			dependencies = resolve( dependencies );
			// If all dependencies are met, execute ready immediately
			if ( compare( filter( ['ready'], dependencies ), dependencies ) ) {
				if ( $.isFunction( ready ) ) {
					ready();
				}
			}
			// If any dependencies have errors execute error immediately
			else if ( filter( ['error'], dependencies ).length ) {
				if ( $.isFunction( error ) ) {
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
		 *  or a URL of an external script or style
		 * @param type string mime-type to use if calling with a URL of an
		 *  external script or style; acceptable values are "text/css" and
		 *  "text/javascript"; if no type is provided, text/javascript is assumed.
		 */
		this.load = function( modules, type ) {
			// Validate input
			if ( typeof modules !== 'object' && typeof modules !== 'string' ) {
				throw new Error( 'modules must be a string or an array, not a ' + typeof modules );
			}
			// Allow calling with an external script or single dependency as a string
			if ( typeof modules === 'string' ) {
				// Support adding arbitrary external scripts
				if ( modules.substr( 0, 7 ) === 'http://' || modules.substr( 0, 8 ) === 'https://' || modules.substr( 0, 2 ) === '//' ) {
					if ( type === 'text/css' ) {
						$( 'head' ).append( $( '<link/>', {
							rel: 'stylesheet',
							type: 'text/css',
							href: modules
						} ) );
						return true;
					} else if ( type === 'text/javascript' || type === undefined ) {
						addScript( modules );
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
		 * Changes the state of a module
		 *
		 * @param module string module name or object of module name/state pairs
		 * @param state string state name
		 */
		this.state = function( module, state ) {
			if ( typeof module === 'object' ) {
				for ( var m in module ) {
					mw.loader.state( m, module[m] );
				}
				return;
			}
			if ( !( module in registry ) ) {
				mw.loader.register( module );
			}
			registry[module].state = state;
		};

		/**
		 * Gets the version of a module
		 *
		 * @param module string name of module to get version for
		 */
		this.getVersion = function( module ) {
			if ( module in registry && 'version' in registry[module] ) {
				return formatVersionNumber( registry[module].version );
			}
			return null;
		};

		/**
		* @deprecated use mw.loader.getVersion() instead
		*/
		this.version = function() {
			return mw.loader.getVersion.apply( mw.loader, arguments );
		};

		/**
		 * Gets the state of a module
		 *
		 * @param module string name of module to get state for
		 */
		this.getState = function( module ) {
			if ( module in registry && 'state' in registry[module] ) {
				return registry[module].state;
			}
			return null;
		};

		/**
		 * Get names of all registered modules.
		 *
		 * @return {Array}
		 */
		this.getModuleNames = function() {
			var names = $.map( registry, function( i, key ) {
				return key;
			} );
			return names;
		};

		/* Cache document ready status */

		$(document).ready( function() { ready = true; } );
	} )();

	/** HTML construction helper functions */
	this.html = new ( function () {
		var escapeCallback = function( s ) {
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
		};

		/**
		 * Escape a string for HTML. Converts special characters to HTML entities.
		 * @param s The string to escape
		 */
		this.escape = function( s ) {
			return s.replace( /['"<>&]/g, escapeCallback );
		};

		/**
		 * Wrapper object for raw HTML passed to mw.html.element().
		 */
		this.Raw = function( value ) {
			this.value = value;
		};

		/**
		 * Wrapper object for CDATA element contents passed to mw.html.element()
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
		 *  - string: The string is escaped.
		 *  - null or undefined: The short closing form is used, e.g. <br/>.
		 *  - this.Raw: The value attribute is included without escaping.
		 *  - this.Cdata: The value attribute is included, and an exception is
		 *   thrown if it contains an illegal ETAGO delimiter.
		 *   See http://www.w3.org/TR/1999/REC-html401-19991224/appendix/notes.html#h-B.3.2
		 *
		 * Example:
		 *	var h = mw.html;
		 *	return h.element( 'div', {},
		 *		new h.Raw( h.element( 'img', {src: '<'} ) ) );
		 * Returns <div><img src="&lt;"/></div>
		 */
		this.element = function( name, attrs, contents ) {
			var s = '<' + name;
			for ( var attrName in attrs ) {
				s += ' ' + attrName + '="' + this.escape( attrs[attrName] ) + '"';
			}
			if ( contents === undefined || contents === null ) {
				// Self close tag
				s += '/>';
				return s;
			}
			// Regular open tag
			s += '>';
			if ( typeof contents === 'string' ) {
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

// Alias $j to jQuery for backwards compatibility
window.$j = jQuery;

// Auto-register from pre-loaded startup scripts
if ( jQuery.isFunction( startUp ) ) {
	startUp();
	delete startUp;
}
