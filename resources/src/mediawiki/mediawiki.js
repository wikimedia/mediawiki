/**
 * Base library for MediaWiki.
 *
 * Exposed globally as `mediaWiki` with `mw` as shortcut.
 *
 * @class mw
 * @alternateClassName mediaWiki
 * @singleton
 */
/*jshint latedef:false */
( function ( $ ) {
	'use strict';

	var mw, StringSet, log,
		hasOwn = Object.prototype.hasOwnProperty,
		slice = Array.prototype.slice,
		trackCallbacks = $.Callbacks( 'memory' ),
		trackHandlers = [],
		trackQueue = [];

	/**
	 * FNV132 hash function
	 *
	 * This function implements the 32-bit version of FNV-1.
	 * It is equivalent to hash( 'fnv132', ... ) in PHP, except
	 * its output is base 36 rather than hex.
	 * See <https://en.wikipedia.org/wiki/FNV_hash_function>
	 *
	 * @private
	 * @param {string} str String to hash
	 * @return {string} hash as an seven-character base 36 string
	 */
	function fnv132( str ) {
		/*jshint bitwise:false */
		var hash = 0x811C9DC5,
			i;

		for ( i = 0; i < str.length; i++ ) {
			hash += ( hash << 1 ) + ( hash << 4 ) + ( hash << 7 ) + ( hash << 8 ) + ( hash << 24 );
			hash ^= str.charCodeAt( i );
		}

		hash = ( hash >>> 0 ).toString( 36 );
		while ( hash.length < 7 ) {
			hash = '0' + hash;
		}

		return hash;
	}

	StringSet = window.Set || ( function () {
		/**
		 * @private
		 * @class
		 */
		function StringSet() {
			this.set = {};
		}
		StringSet.prototype.add = function ( value ) {
			this.set[ value ] = true;
		};
		StringSet.prototype.has = function ( value ) {
			return this.set.hasOwnProperty( value );
		};
		return StringSet;
	}() );

	/**
	 * Create an object that can be read from or written to via methods that allow
	 * interaction both with single and multiple properties at once.
	 *
	 * @private
	 * @class mw.Map
	 *
	 * @constructor
	 * @param {boolean} [global=false] Whether to synchronise =values to the global
	 *  window object (for backwards-compatibility with mw.config; T72470). Values are
	 *  copied in one direction only. Changes to globals do not reflect in the map.
	 */
	function Map( global ) {
		this.internalValues = {};
		if ( global === true ) {

			// Override #set to also set the global variable
			this.set = function ( selection, value ) {
				var s;

				if ( $.isPlainObject( selection ) ) {
					for ( s in selection ) {
						setGlobalMapValue( this, s, selection[ s ] );
					}
					return true;
				}
				if ( typeof selection === 'string' && arguments.length ) {
					setGlobalMapValue( this, selection, value );
					return true;
				}
				return false;
			};
		}

		// Deprecated since MediaWiki 1.28
		log.deprecate(
			this,
			'values',
			this.internalValues,
			'mw.Map#values is deprecated. Use mw.Map#get() instead.',
			'Map-values'
		);
	}

	/**
	 * Alias property to the global object.
	 *
	 * @private
	 * @static
	 * @param {mw.Map} map
	 * @param {string} key
	 * @param {Mixed} value
	 */
	function setGlobalMapValue( map, key, value ) {
		map.internalValues[ key ] = value;
		log.deprecate(
				window,
				key,
				value,
				// Deprecation notice for mw.config globals (T58550, T72470)
				map === mw.config && 'Use mw.config instead.'
		);
	}

	Map.prototype = {
		constructor: Map,

		/**
		 * Get the value of one or more keys.
		 *
		 * If called with no arguments, all values are returned.
		 *
		 * @param {string|Array} [selection] Key or array of keys to retrieve values for.
		 * @param {Mixed} [fallback=null] Value for keys that don't exist.
		 * @return {Mixed|Object| null} If selection was a string, returns the value,
		 *  If selection was an array, returns an object of key/values.
		 *  If no selection is passed, the internal container is returned. (Beware that,
		 *  as is the default in JavaScript, the object is returned by reference.)
		 */
		get: function ( selection, fallback ) {
			var results, i;
			// If we only do this in the `return` block, it'll fail for the
			// call to get() from the mutli-selection block.
			fallback = arguments.length > 1 ? fallback : null;

			if ( $.isArray( selection ) ) {
				selection = slice.call( selection );
				results = {};
				for ( i = 0; i < selection.length; i++ ) {
					results[ selection[ i ] ] = this.get( selection[ i ], fallback );
				}
				return results;
			}

			if ( typeof selection === 'string' ) {
				if ( !hasOwn.call( this.internalValues, selection ) ) {
					return fallback;
				}
				return this.internalValues[ selection ];
			}

			if ( selection === undefined ) {
				return this.internalValues;
			}

			// Invalid selection key
			return null;
		},

		/**
		 * Set one or more key/value pairs.
		 *
		 * @param {string|Object} selection Key to set value for, or object mapping keys to values
		 * @param {Mixed} [value] Value to set (optional, only in use when key is a string)
		 * @return {boolean} True on success, false on failure
		 */
		set: function ( selection, value ) {
			var s;

			if ( $.isPlainObject( selection ) ) {
				for ( s in selection ) {
					this.internalValues[ s ] = selection[ s ];
				}
				return true;
			}
			if ( typeof selection === 'string' && arguments.length > 1 ) {
				this.internalValues[ selection ] = value;
				return true;
			}
			return false;
		},

		/**
		 * Check if one or more keys exist.
		 *
		 * @param {Mixed} selection Key or array of keys to check
		 * @return {boolean} True if the key(s) exist
		 */
		exists: function ( selection ) {
			var s;

			if ( $.isArray( selection ) ) {
				for ( s = 0; s < selection.length; s++ ) {
					if ( typeof selection[ s ] !== 'string' || !hasOwn.call( this.internalValues, selection[ s ] ) ) {
						return false;
					}
				}
				return true;
			}
			return typeof selection === 'string' && hasOwn.call( this.internalValues, selection );
		}
	};

	/**
	 * Object constructor for messages.
	 *
	 * Similar to the Message class in MediaWiki PHP.
	 *
	 * Format defaults to 'text'.
	 *
	 *     @example
	 *
	 *     var obj, str;
	 *     mw.messages.set( {
	 *         'hello': 'Hello world',
	 *         'hello-user': 'Hello, $1!',
	 *         'welcome-user': 'Welcome back to $2, $1! Last visit by $1: $3'
	 *     } );
	 *
	 *     obj = new mw.Message( mw.messages, 'hello' );
	 *     mw.log( obj.text() );
	 *     // Hello world
	 *
	 *     obj = new mw.Message( mw.messages, 'hello-user', [ 'John Doe' ] );
	 *     mw.log( obj.text() );
	 *     // Hello, John Doe!
	 *
	 *     obj = new mw.Message( mw.messages, 'welcome-user', [ 'John Doe', 'Wikipedia', '2 hours ago' ] );
	 *     mw.log( obj.text() );
	 *     // Welcome back to Wikipedia, John Doe! Last visit by John Doe: 2 hours ago
	 *
	 *     // Using mw.message shortcut
	 *     obj = mw.message( 'hello-user', 'John Doe' );
	 *     mw.log( obj.text() );
	 *     // Hello, John Doe!
	 *
	 *     // Using mw.msg shortcut
	 *     str = mw.msg( 'hello-user', 'John Doe' );
	 *     mw.log( str );
	 *     // Hello, John Doe!
	 *
	 *     // Different formats
	 *     obj = new mw.Message( mw.messages, 'hello-user', [ 'John "Wiki" <3 Doe' ] );
	 *
	 *     obj.format = 'text';
	 *     str = obj.toString();
	 *     // Same as:
	 *     str = obj.text();
	 *
	 *     mw.log( str );
	 *     // Hello, John "Wiki" <3 Doe!
	 *
	 *     mw.log( obj.escaped() );
	 *     // Hello, John &quot;Wiki&quot; &lt;3 Doe!
	 *
	 * @class mw.Message
	 *
	 * @constructor
	 * @param {mw.Map} map Message store
	 * @param {string} key
	 * @param {Array} [parameters]
	 */
	function Message( map, key, parameters ) {
		this.format = 'text';
		this.map = map;
		this.key = key;
		this.parameters = parameters === undefined ? [] : slice.call( parameters );
		return this;
	}

	Message.prototype = {
		/**
		 * Get parsed contents of the message.
		 *
		 * The default parser does simple $N replacements and nothing else.
		 * This may be overridden to provide a more complex message parser.
		 * The primary override is in the mediawiki.jqueryMsg module.
		 *
		 * This function will not be called for nonexistent messages.
		 *
		 * @return {string} Parsed message
		 */
		parser: function () {
			return mw.format.apply( null, [ this.map.get( this.key ) ].concat( this.parameters ) );
		},

		/**
		 * Add (does not replace) parameters for `$N` placeholder values.
		 *
		 * @param {Array} parameters
		 * @chainable
		 */
		params: function ( parameters ) {
			var i;
			for ( i = 0; i < parameters.length; i++ ) {
				this.parameters.push( parameters[ i ] );
			}
			return this;
		},

		/**
		 * Convert message object to its string form based on current format.
		 *
		 * @return {string} Message as a string in the current form, or `<key>` if key
		 *  does not exist.
		 */
		toString: function () {
			var text;

			if ( !this.exists() ) {
				// Use <key> as text if key does not exist
				if ( this.format === 'escaped' || this.format === 'parse' ) {
					// format 'escaped' and 'parse' need to have the brackets and key html escaped
					return mw.html.escape( '<' + this.key + '>' );
				}
				return '<' + this.key + '>';
			}

			if ( this.format === 'plain' || this.format === 'text' || this.format === 'parse' ) {
				text = this.parser();
			}

			if ( this.format === 'escaped' ) {
				text = this.parser();
				text = mw.html.escape( text );
			}

			return text;
		},

		/**
		 * Change format to 'parse' and convert message to string
		 *
		 * If jqueryMsg is loaded, this parses the message text from wikitext
		 * (where supported) to HTML
		 *
		 * Otherwise, it is equivalent to plain.
		 *
		 * @return {string} String form of parsed message
		 */
		parse: function () {
			this.format = 'parse';
			return this.toString();
		},

		/**
		 * Change format to 'plain' and convert message to string
		 *
		 * This substitutes parameters, but otherwise does not change the
		 * message text.
		 *
		 * @return {string} String form of plain message
		 */
		plain: function () {
			this.format = 'plain';
			return this.toString();
		},

		/**
		 * Change format to 'text' and convert message to string
		 *
		 * If jqueryMsg is loaded, {{-transformation is done where supported
		 * (such as {{plural:}}, {{gender:}}, {{int:}}).
		 *
		 * Otherwise, it is equivalent to plain
		 *
		 * @return {string} String form of text message
		 */
		text: function () {
			this.format = 'text';
			return this.toString();
		},

		/**
		 * Change the format to 'escaped' and convert message to string
		 *
		 * This is equivalent to using the 'text' format (see #text), then
		 * HTML-escaping the output.
		 *
		 * @return {string} String form of html escaped message
		 */
		escaped: function () {
			this.format = 'escaped';
			return this.toString();
		},

		/**
		 * Check if a message exists
		 *
		 * @see mw.Map#exists
		 * @return {boolean}
		 */
		exists: function () {
			return this.map.exists( this.key );
		}
	};

	log = ( function () {
		// Also update the restoration of methods in mediawiki.log.js
		// when adding or removing methods here.
		var log = function () {},
			console = window.console;

		/**
		 * @class mw.log
		 * @singleton
		 */

		/**
		 * Write a message to the console's warning channel.
		 * Actions not supported by the browser console are silently ignored.
		 *
		 * @param {...string} msg Messages to output to console
		 */
		log.warn = console && console.warn && Function.prototype.bind ?
			Function.prototype.bind.call( console.warn, console ) :
			$.noop;

		/**
		 * Write a message to the console's error channel.
		 *
		 * Most browsers provide a stacktrace by default if the argument
		 * is a caught Error object.
		 *
		 * @since 1.26
		 * @param {Error|...string} msg Messages to output to console
		 */
		log.error = console && console.error && Function.prototype.bind ?
			Function.prototype.bind.call( console.error, console ) :
			$.noop;

		/**
		 * Create a property in a host object that, when accessed, will produce
		 * a deprecation warning in the console.
		 *
		 * @param {Object} obj Host object of deprecated property
		 * @param {string} key Name of property to create in `obj`
		 * @param {Mixed} val The value this property should return when accessed
		 * @param {string} [msg] Optional text to include in the deprecation message
		 * @param {string} [logName=key] Optional custom name for the feature.
		 *  This is used instead of `key` in the message and `mw.deprecate` tracking.
		 */
		log.deprecate = !Object.defineProperty ? function ( obj, key, val ) {
			obj[ key ] = val;
		} : function ( obj, key, val, msg, logName ) {
			logName = logName || key;
			msg = 'Use of "' + logName + '" is deprecated.' + ( msg ? ( ' ' + msg ) : '' );
			var logged = new StringSet();
			function uniqueTrace() {
				var trace = new Error().stack;
				if ( logged.has( trace ) ) {
					return false;
				}
				logged.add( trace );
				return true;
			}
			// Support: Safari 5.0
			// Throws "not supported on DOM Objects" for Node or Element objects (incl. document)
			// Safari 4.0 doesn't have this method, and it was fixed in Safari 5.1.
			try {
				Object.defineProperty( obj, key, {
					configurable: true,
					enumerable: true,
					get: function () {
						if ( uniqueTrace() ) {
							mw.track( 'mw.deprecate', logName );
							mw.log.warn( msg );
						}
						return val;
					},
					set: function ( newVal ) {
						if ( uniqueTrace() ) {
							mw.track( 'mw.deprecate', logName );
							mw.log.warn( msg );
						}
						val = newVal;
					}
				} );
			} catch ( err ) {
				obj[ key ] = val;
			}
		};

		return log;
	}() );

	/**
	 * @class mw
	 */
	mw = {

		/**
		 * Get the current time, measured in milliseconds since January 1, 1970 (UTC).
		 *
		 * On browsers that implement the Navigation Timing API, this function will produce floating-point
		 * values with microsecond precision that are guaranteed to be monotonic. On all other browsers,
		 * it will fall back to using `Date`.
		 *
		 * @return {number} Current time
		 */
		now: ( function () {
			var perf = window.performance,
				navStart = perf && perf.timing && perf.timing.navigationStart;
			return navStart && typeof perf.now === 'function' ?
				function () { return navStart + perf.now(); } :
				function () { return +new Date(); };
		}() ),

		/**
		 * Format a string. Replace $1, $2 ... $N with positional arguments.
		 *
		 * Used by Message#parser().
		 *
		 * @since 1.25
		 * @param {string} formatString Format string
		 * @param {...Mixed} parameters Values for $N replacements
		 * @return {string} Formatted string
		 */
		format: function ( formatString ) {
			var parameters = slice.call( arguments, 1 );
			return formatString.replace( /\$(\d+)/g, function ( str, match ) {
				var index = parseInt( match, 10 ) - 1;
				return parameters[ index ] !== undefined ? parameters[ index ] : '$' + match;
			} );
		},

		/**
		 * Track an analytic event.
		 *
		 * This method provides a generic means for MediaWiki JavaScript code to capture state
		 * information for analysis. Each logged event specifies a string topic name that describes
		 * the kind of event that it is. Topic names consist of dot-separated path components,
		 * arranged from most general to most specific. Each path component should have a clear and
		 * well-defined purpose.
		 *
		 * Data handlers are registered via `mw.trackSubscribe`, and receive the full set of
		 * events that match their subcription, including those that fired before the handler was
		 * bound.
		 *
		 * @param {string} topic Topic name
		 * @param {Object} [data] Data describing the event, encoded as an object
		 */
		track: function ( topic, data ) {
			trackQueue.push( { topic: topic, timeStamp: mw.now(), data: data } );
			trackCallbacks.fire( trackQueue );
		},

		/**
		 * Register a handler for subset of analytic events, specified by topic.
		 *
		 * Handlers will be called once for each tracked event, including any events that fired before the
		 * handler was registered; 'this' is set to a plain object with a 'timeStamp' property indicating
		 * the exact time at which the event fired, a string 'topic' property naming the event, and a
		 * 'data' property which is an object of event-specific data. The event topic and event data are
		 * also passed to the callback as the first and second arguments, respectively.
		 *
		 * @param {string} topic Handle events whose name starts with this string prefix
		 * @param {Function} callback Handler to call for each matching tracked event
		 * @param {string} callback.topic
		 * @param {Object} [callback.data]
		 */
		trackSubscribe: function ( topic, callback ) {
			var seen = 0;
			function handler( trackQueue ) {
				var event;
				for ( ; seen < trackQueue.length; seen++ ) {
					event = trackQueue[ seen ];
					if ( event.topic.indexOf( topic ) === 0 ) {
						callback.call( event, event.topic, event.data );
					}
				}
			}

			trackHandlers.push( [ handler, callback ] );

			trackCallbacks.add( handler );
		},

		/**
		 * Stop handling events for a particular handler
		 *
		 * @param {Function} callback
		 */
		trackUnsubscribe: function ( callback ) {
			trackHandlers = $.grep( trackHandlers, function ( fns ) {
				if ( fns[ 1 ] === callback ) {
					trackCallbacks.remove( fns[ 0 ] );
					// Ensure the tuple is removed to avoid holding on to closures
					return false;
				}
				return true;
			} );
		},

		// Expose Map constructor
		Map: Map,

		// Expose Message constructor
		Message: Message,

		/**
		 * Map of configuration values.
		 *
		 * Check out [the complete list of configuration values](https://www.mediawiki.org/wiki/Manual:Interface/JavaScript#mw.config)
		 * on mediawiki.org.
		 *
		 * If `$wgLegacyJavaScriptGlobals` is true, this Map will add its values to the
		 * global `window` object.
		 *
		 * @property {mw.Map} config
		 */
		// Dummy placeholder later assigned in ResourceLoaderStartUpModule
		config: null,

		/**
		 * Empty object for third-party libraries, for cases where you don't
		 * want to add a new global, or the global is bad and needs containment
		 * or wrapping.
		 *
		 * @property
		 */
		libs: {},

		/**
		 * Access container for deprecated functionality that can be moved from
		 * from their legacy location and attached to this object (e.g. a global
		 * function that is deprecated and as stop-gap can be exposed through here).
		 *
		 * This was reserved for future use but never ended up being used.
		 *
		 * @deprecated since 1.22 Let deprecated identifiers keep their original name
		 *  and use mw.log#deprecate to create an access container for tracking.
		 * @property
		 */
		legacy: {},

		/**
		 * Store for messages.
		 *
		 * @property {mw.Map}
		 */
		messages: new Map(),

		/**
		 * Store for templates associated with a module.
		 *
		 * @property {mw.Map}
		 */
		templates: new Map(),

		/**
		 * Get a message object.
		 *
		 * Shortcut for `new mw.Message( mw.messages, key, parameters )`.
		 *
		 * @see mw.Message
		 * @param {string} key Key of message to get
		 * @param {...Mixed} parameters Values for $N replacements
		 * @return {mw.Message}
		 */
		message: function ( key ) {
			var parameters = slice.call( arguments, 1 );
			return new Message( mw.messages, key, parameters );
		},

		/**
		 * Get a message string using the (default) 'text' format.
		 *
		 * Shortcut for `mw.message( key, parameters... ).text()`.
		 *
		 * @see mw.Message
		 * @param {string} key Key of message to get
		 * @param {...Mixed} parameters Values for $N replacements
		 * @return {string}
		 */
		msg: function () {
			return mw.message.apply( mw.message, arguments ).toString();
		},

		/**
		 * No-op dummy placeholder for {@link mw.log} in debug mode.
		 *
		 * @method
		 */
		log: log,

		/**
		 * Client for ResourceLoader server end point.
		 *
		 * This client is in charge of maintaining the module registry and state
		 * machine, initiating network (batch) requests for loading modules, as
		 * well as dependency resolution and execution of source code.
		 *
		 * For more information, refer to
		 * <https://www.mediawiki.org/wiki/ResourceLoader/Features>
		 *
		 * @class mw.loader
		 * @singleton
		 */
		loader: ( function () {

			/**
			 * Fired via mw.track on various resource loading errors.
			 *
			 * @event resourceloader_exception
			 * @param {Error|Mixed} e The error that was thrown. Almost always an Error
			 *   object, but in theory module code could manually throw something else, and that
			 *   might also end up here.
			 * @param {string} [module] Name of the module which caused the error. Omitted if the
			 *   error is not module-related or the module cannot be easily identified due to
			 *   batched handling.
			 * @param {string} source Source of the error. Possible values:
			 *
			 *   - style: stylesheet error (only affects old IE where a special style loading method
			 *     is used)
			 *   - load-callback: exception thrown by user callback
			 *   - module-execute: exception thrown by module code
			 *   - store-eval: could not evaluate module code cached in localStorage
			 *   - store-localstorage-init: localStorage or JSON parse error in mw.loader.store.init
			 *   - store-localstorage-json: JSON conversion error in mw.loader.store.set
			 *   - store-localstorage-update: localStorage or JSON conversion error in mw.loader.store.update
			 */

			/**
			 * Fired via mw.track on resource loading error conditions.
			 *
			 * @event resourceloader_assert
			 * @param {string} source Source of the error. Possible values:
			 *
			 *   - bug-T59567: failed to cache script due to an Opera function -> string conversion
			 *     bug; see <https://phabricator.wikimedia.org/T59567> for details
			 */

			/**
			 * Mapping of registered modules.
			 *
			 * See #implement and #execute for exact details on support for script, style and messages.
			 *
			 * Format:
			 *
			 *     {
			 *         'moduleName': {
			 *             // From mw.loader.register()
			 *             'version': '########' (hash)
			 *             'dependencies': ['required.foo', 'bar.also', ...], (or) function () {}
			 *             'group': 'somegroup', (or) null
			 *             'source': 'local', (or) 'anotherwiki'
			 *             'skip': 'return !!window.Example', (or) null
			 *             'module': export Object
			 *
			 *             // Set from execute() or mw.loader.state()
			 *             'state': 'registered', 'loaded', 'loading', 'ready', 'error', or 'missing'
			 *
			 *             // Optionally added at run-time by mw.loader.implement()
			 *             'skipped': true
			 *             'script': closure, array of urls, or string
			 *             'style': { ... } (see #execute)
			 *             'messages': { 'key': 'value', ... }
			 *         }
			 *     }
			 *
			 * State machine:
			 *
			 * - `registered`:
			 *    The module is known to the system but not yet required.
			 *    Meta data is registered via mw.loader#register. Calls to that method are
			 *    generated server-side by the startup module.
			 * - `loading`:
			 *    The module was required through mw.loader (either directly or as dependency of
			 *    another module). The client will fetch module contents from the server.
			 *    The contents are then stashed in the registry via mw.loader#implement.
			 * - `loaded`:
			 *    The module has been loaded from the server and stashed via mw.loader#implement.
			 *    If the module has no more dependencies in-flight, the module will be executed
			 *    immediately. Otherwise execution is deferred, controlled via #handlePending.
			 * - `executing`:
			 *    The module is being executed.
			 * - `ready`:
			 *    The module has been successfully executed.
			 * - `error`:
			 *    The module (or one of its dependencies) produced an error during execution.
			 * - `missing`:
			 *    The module was registered client-side and requested, but the server denied knowledge
			 *    of the module's existence.
			 *
			 * @property
			 * @private
			 */
			var registry = {},
				// Mapping of sources, keyed by source-id, values are strings.
				//
				// Format:
				//
				//     {
				//         'sourceId': 'http://example.org/w/load.php'
				//     }
				//
				sources = {},

				// For queueModuleScript()
				handlingPendingRequests = false,
				pendingRequests = [],

				// List of modules to be loaded
				queue = [],

				/**
				 * List of callback jobs waiting for modules to be ready.
				 *
				 * Jobs are created by #enqueue() and run by #handlePending().
				 *
				 * Typically when a job is created for a module, the job's dependencies contain
				 * both the required module and all its recursive dependencies.
				 *
				 * Format:
				 *
				 *     {
				 *         'dependencies': [ module names ],
				 *         'ready': Function callback
				 *         'error': Function callback
				 *     }
				 *
				 * @property {Object[]} jobs
				 * @private
				 */
				jobs = [],

				// For getMarker()
				marker = null,

				// For addEmbeddedCSS()
				cssBuffer = '',
				cssBufferTimer = null,
				cssCallbacks = $.Callbacks(),
				isIE9 = document.documentMode === 9,
				rAF = window.requestAnimationFrame || setTimeout;

			function getMarker() {
				if ( !marker ) {
					// Cache
					marker = document.querySelector( 'meta[name="ResourceLoaderDynamicStyles"]' );
					if ( !marker ) {
						mw.log( 'Create <meta name="ResourceLoaderDynamicStyles"> dynamically' );
						marker = $( '<meta>' ).attr( 'name', 'ResourceLoaderDynamicStyles' ).appendTo( 'head' )[ 0 ];
					}
				}
				return marker;
			}

			/**
			 * Create a new style element and add it to the DOM.
			 *
			 * @private
			 * @param {string} text CSS text
			 * @param {Node} [nextNode] The element where the style tag
			 *  should be inserted before
			 * @return {HTMLElement} Reference to the created style element
			 */
			function newStyleTag( text, nextNode ) {
				var s = document.createElement( 'style' );

				s.appendChild( document.createTextNode( text ) );
				if ( nextNode && nextNode.parentNode ) {
					nextNode.parentNode.insertBefore( s, nextNode );
				} else {
					document.getElementsByTagName( 'head' )[ 0 ].appendChild( s );
				}

				return s;
			}

			/**
			 * Add a bit of CSS text to the current browser page.
			 *
			 * The CSS will be appended to an existing ResourceLoader-created `<style>` tag
			 * or create a new one based on whether the given `cssText` is safe for extension.
			 *
			 * @private
			 * @param {string} [cssText=cssBuffer] If called without cssText,
			 *  the internal buffer will be inserted instead.
			 * @param {Function} [callback]
			 */
			function addEmbeddedCSS( cssText, callback ) {
				var $style, styleEl;

				function fireCallbacks() {
					var oldCallbacks = cssCallbacks;
					// Reset cssCallbacks variable so it's not polluted by any calls to
					// addEmbeddedCSS() from one of the callbacks (T105973)
					cssCallbacks = $.Callbacks();
					oldCallbacks.fire().empty();
				}

				if ( callback ) {
					cssCallbacks.add( callback );
				}

				// Yield once before creating the <style> tag. This lets multiple stylesheets
				// accumulate into one buffer, allowing us to reduce how often new stylesheets
				// are inserted in the browser. Appending a stylesheet and waiting for the
				// browser to repaint is fairly expensive. (T47810)
				if ( cssText ) {
					// Don't extend the buffer if the item needs its own stylesheet.
					// Keywords like `@import` are only valid at the start of a stylesheet (T37562).
					if ( !cssBuffer || cssText.slice( 0, '@import'.length ) !== '@import' ) {
						// Linebreak for somewhat distinguishable sections
						cssBuffer += '\n' + cssText;
						if ( !cssBufferTimer ) {
							cssBufferTimer = rAF( function () {
								// Wrap in anonymous function that takes no arguments
								// Support: Firefox < 13
								// Firefox 12 has non-standard behaviour of passing a number
								// as first argument to a setTimeout callback.
								// http://benalman.com/news/2009/07/the-mysterious-firefox-settime/
								addEmbeddedCSS();
							} );
						}
						return;
					}

				// This is a scheduled flush for the buffer
				} else {
					cssBufferTimer = null;
					cssText = cssBuffer;
					cssBuffer = '';
				}

				// By default, always create a new <style>. Appending text to a <style> tag is
				// is a performance anti-pattern as it requires CSS to be reparsed (T47810).
				//
				// Support: IE 6-9
				// Try to re-use existing <style> tags due to the IE stylesheet limit (T33676).
				if ( isIE9 ) {
					$style = $( getMarker() ).prev();
					// Verify that the element before the marker actually is a <style> tag created
					// by mw.loader (not some other style tag, or e.g. a <meta> tag).
					if ( $style.data( 'ResourceLoaderDynamicStyleTag' ) ) {
						styleEl = $style[ 0 ];
						styleEl.appendChild( document.createTextNode( cssText ) );
						fireCallbacks();
						return;
					}
					// Else: No existing tag to reuse. Continue below and create the first one.
				}

				$style = $( newStyleTag( cssText, getMarker() ) );

				if ( isIE9 ) {
					$style.data( 'ResourceLoaderDynamicStyleTag', true );
				}

				fireCallbacks();
			}

			/**
			 * @private
			 * @param {Array} modules List of module names
			 * @return {string} Hash of concatenated version hashes.
			 */
			function getCombinedVersion( modules ) {
				var hashes = $.map( modules, function ( module ) {
					return registry[ module ].version;
				} );
				return fnv132( hashes.join( '' ) );
			}

			/**
			 * Determine whether all dependencies are in state 'ready', which means we may
			 * execute the module or job now.
			 *
			 * @private
			 * @param {Array} modules Names of modules to be checked
			 * @return {boolean} True if all modules are in state 'ready', false otherwise
			 */
			function allReady( modules ) {
				var i;
				for ( i = 0; i < modules.length; i++ ) {
					if ( mw.loader.getState( modules[ i ] ) !== 'ready' ) {
						return false;
					}
				}
				return true;
			}

			/**
			 * Determine whether all dependencies are in state 'ready', which means we may
			 * execute the module or job now.
			 *
			 * @private
			 * @param {Array} modules Names of modules to be checked
			 * @return {boolean} True if no modules are in state 'error' or 'missing', false otherwise
			 */
			function anyFailed( modules ) {
				var i, state;
				for ( i = 0; i < modules.length; i++ ) {
					state = mw.loader.getState( modules[ i ] );
					if ( state === 'error' || state === 'missing' ) {
						return true;
					}
				}
				return false;
			}

			/**
			 * A module has entered state 'ready', 'error', or 'missing'. Automatically update
			 * pending jobs and modules that depend upon this module. If the given module failed,
			 * propagate the 'error' state up the dependency tree. Otherwise, go ahead and execute
			 * all jobs/modules now having their dependencies satisfied.
			 *
			 * Jobs that depend on a failed module, will have their error callback ran (if any).
			 *
			 * @private
			 * @param {string} module Name of module that entered one of the states 'ready', 'error', or 'missing'.
			 */
			function handlePending( module ) {
				var j, job, hasErrors, m, stateChange;

				if ( registry[ module ].state === 'error' || registry[ module ].state === 'missing' ) {
					// If the current module failed, mark all dependent modules also as failed.
					// Iterate until steady-state to propagate the error state upwards in the
					// dependency tree.
					do {
						stateChange = false;
						for ( m in registry ) {
							if ( registry[ m ].state !== 'error' && registry[ m ].state !== 'missing' ) {
								if ( anyFailed( registry[ m ].dependencies ) ) {
									registry[ m ].state = 'error';
									stateChange = true;
								}
							}
						}
					} while ( stateChange );
				}

				// Execute all jobs whose dependencies are either all satisfied or contain at least one failed module.
				for ( j = 0; j < jobs.length; j++ ) {
					hasErrors = anyFailed( jobs[ j ].dependencies );
					if ( hasErrors || allReady( jobs[ j ].dependencies ) ) {
						// All dependencies satisfied, or some have errors
						job = jobs[ j ];
						jobs.splice( j, 1 );
						j -= 1;
						try {
							if ( hasErrors ) {
								if ( typeof job.error === 'function' ) {
									job.error( new Error( 'Module ' + module + ' has failed dependencies' ), [ module ] );
								}
							} else {
								if ( typeof job.ready === 'function' ) {
									job.ready();
								}
							}
						} catch ( e ) {
							// A user-defined callback raised an exception.
							// Swallow it to protect our state machine!
							mw.track( 'resourceloader.exception', { exception: e, module: module, source: 'load-callback' } );
						}
					}
				}

				if ( registry[ module ].state === 'ready' ) {
					// The current module became 'ready'. Set it in the module store, and recursively execute all
					// dependent modules that are loaded and now have all dependencies satisfied.
					mw.loader.store.set( module, registry[ module ] );
					for ( m in registry ) {
						if ( registry[ m ].state === 'loaded' && allReady( registry[ m ].dependencies ) ) {
							execute( m );
						}
					}
				}
			}

			/**
			 * Resolve dependencies and detect circular references.
			 *
			 * @private
			 * @param {string} module Name of the top-level module whose dependencies shall be
			 *  resolved and sorted.
			 * @param {Array} resolved Returns a topological sort of the given module and its
			 *  dependencies, such that later modules depend on earlier modules. The array
			 *  contains the module names. If the array contains already some module names,
			 *  this function appends its result to the pre-existing array.
			 * @param {StringSet} [unresolved] Used to track the current dependency
			 *  chain, and to report loops in the dependency graph.
			 * @throws {Error} If any unregistered module or a dependency loop is encountered
			 */
			function sortDependencies( module, resolved, unresolved ) {
				var i, deps, skip;

				if ( !hasOwn.call( registry, module ) ) {
					throw new Error( 'Unknown dependency: ' + module );
				}

				if ( registry[ module ].skip !== null ) {
					/*jshint evil:true */
					skip = new Function( registry[ module ].skip );
					registry[ module ].skip = null;
					if ( skip() ) {
						registry[ module ].skipped = true;
						registry[ module ].dependencies = [];
						registry[ module ].state = 'ready';
						handlePending( module );
						return;
					}
				}

				// Resolves dynamic loader function and replaces it with its own results
				if ( typeof registry[ module ].dependencies === 'function' ) {
					registry[ module ].dependencies = registry[ module ].dependencies();
					// Ensures the module's dependencies are always in an array
					if ( typeof registry[ module ].dependencies !== 'object' ) {
						registry[ module ].dependencies = [ registry[ module ].dependencies ];
					}
				}
				if ( $.inArray( module, resolved ) !== -1 ) {
					// Module already resolved; nothing to do
					return;
				}
				// Create unresolved if not passed in
				if ( !unresolved ) {
					unresolved = new StringSet();
				}
				// Tracks down dependencies
				deps = registry[ module ].dependencies;
				for ( i = 0; i < deps.length; i++ ) {
					if ( $.inArray( deps[ i ], resolved ) === -1 ) {
						if ( unresolved.has( deps[ i ] ) ) {
							throw new Error( mw.format(
								'Circular reference detected: $1 -> $2',
								module,
								deps[ i ]
							) );
						}

						unresolved.add(  module );
						sortDependencies( deps[ i ], resolved, unresolved );
					}
				}
				resolved.push( module );
			}

			/**
			 * Get names of module that a module depends on, in their proper dependency order.
			 *
			 * @private
			 * @param {string[]} modules Array of string module names
			 * @return {Array} List of dependencies, including 'module'.
			 */
			function resolve( modules ) {
				var resolved = [];
				$.each( modules, function ( idx, module ) {
					sortDependencies( module, resolved );
				} );
				return resolved;
			}

			/**
			 * Load and execute a script.
			 *
			 * @private
			 * @param {string} src URL to script, will be used as the src attribute in the script tag
			 * @return {jQuery.Promise}
			 */
			function addScript( src ) {
				return $.ajax( {
					url: src,
					dataType: 'script',
					// Force jQuery behaviour to be for crossDomain. Otherwise jQuery would use
					// XHR for a same domain request instead of <script>, which changes the request
					// headers (potentially missing a cache hit), and reduces caching in general
					// since browsers cache XHR much less (if at all). And XHR means we retrieve
					// text, so we'd need to $.globalEval, which then messes up line numbers.
					crossDomain: true,
					cache: true
				} );
			}

			/**
			 * Queue the loading and execution of a script for a particular module.
			 *
			 * @private
			 * @param {string} src URL of the script
			 * @param {string} [moduleName] Name of currently executing module
			 * @return {jQuery.Promise}
			 */
			function queueModuleScript( src, moduleName ) {
				var r = $.Deferred();

				pendingRequests.push( function () {
					if ( moduleName && hasOwn.call( registry, moduleName ) ) {
						// Emulate runScript() part of execute()
						window.require = mw.loader.require;
						window.module = registry[ moduleName ].module;
					}
					addScript( src ).always( function () {
						// 'module.exports' should not persist after the file is executed to
						// avoid leakage to unrelated code. 'require' should be kept, however,
						// as asynchronous access to 'require' is allowed and expected. (T144879)
						delete window.module;
						r.resolve();

						// Start the next one (if any)
						if ( pendingRequests[ 0 ] ) {
							pendingRequests.shift()();
						} else {
							handlingPendingRequests = false;
						}
					} );
				} );
				if ( !handlingPendingRequests && pendingRequests[ 0 ] ) {
					handlingPendingRequests = true;
					pendingRequests.shift()();
				}
				return r.promise();
			}

			/**
			 * Utility function for execute()
			 *
			 * @ignore
			 */
			function addLink( media, url ) {
				var el = document.createElement( 'link' );

				el.rel = 'stylesheet';
				if ( media && media !== 'all' ) {
					el.media = media;
				}
				// If you end up here from an IE exception "SCRIPT: Invalid property value.",
				// see #addEmbeddedCSS, bug 31676, and bug 47277 for details.
				el.href = url;

				$( getMarker() ).before( el );
			}

			/**
			 * Executes a loaded module, making it ready to use
			 *
			 * @private
			 * @param {string} module Module name to execute
			 */
			function execute( module ) {
				var key, value, media, i, urls, cssHandle, checkCssHandles, runScript,
					cssHandlesRegistered = false;

				if ( !hasOwn.call( registry, module ) ) {
					throw new Error( 'Module has not been registered yet: ' + module );
				}
				if ( registry[ module ].state !== 'loaded' ) {
					throw new Error( 'Module in state "' + registry[ module ].state + '" may not be executed: ' + module );
				}

				registry[ module ].state = 'executing';

				runScript = function () {
					var script, markModuleReady, nestedAddScript, legacyWait, implicitDependencies,
						// Expand to include dependencies since we have to exclude both legacy modules
						// and their dependencies from the legacyWait (to prevent a circular dependency).
						legacyModules = resolve( mw.config.get( 'wgResourceLoaderLegacyModules', [] ) );

					script = registry[ module ].script;
					markModuleReady = function () {
						registry[ module ].state = 'ready';
						handlePending( module );
					};
					nestedAddScript = function ( arr, callback, i ) {
						// Recursively call queueModuleScript() in its own callback
						// for each element of arr.
						if ( i >= arr.length ) {
							// We're at the end of the array
							callback();
							return;
						}

						queueModuleScript( arr[ i ], module ).always( function () {
							nestedAddScript( arr, callback, i + 1 );
						} );
					};

					implicitDependencies = ( $.inArray( module, legacyModules ) !== -1 )
						? []
						: legacyModules;

					if ( module === 'user' ) {
						// Implicit dependency on the site module. Not real dependency because
						// it should run after 'site' regardless of whether it succeeds or fails.
						implicitDependencies.push( 'site' );
					}

					legacyWait = implicitDependencies.length
						? mw.loader.using( implicitDependencies )
						: $.Deferred().resolve();

					legacyWait.always( function () {
						try {
							if ( $.isArray( script ) ) {
								nestedAddScript( script, markModuleReady, 0 );
							} else if ( typeof script === 'function' ) {
								// Pass jQuery twice so that the signature of the closure which wraps
								// the script can bind both '$' and 'jQuery'.
								script( $, $, mw.loader.require, registry[ module ].module );
								markModuleReady();

							} else if ( typeof script === 'string' ) {
								// Site and user modules are legacy scripts that run in the global scope.
								// This is transported as a string instead of a function to avoid needing
								// to use string manipulation to undo the function wrapper.
								$.globalEval( script );
								markModuleReady();

							} else {
								// Module without script
								markModuleReady();
							}
						} catch ( e ) {
							// Use mw.track instead of mw.log because these errors are common in production mode
							// (e.g. undefined variable), and mw.log is only enabled in debug mode.
							registry[ module ].state = 'error';
							mw.track( 'resourceloader.exception', { exception: e, module: module, source: 'module-execute' } );
							handlePending( module );
						}
					} );
				};

				// Add localizations to message system
				if ( registry[ module ].messages ) {
					mw.messages.set( registry[ module ].messages );
				}

				// Initialise templates
				if ( registry[ module ].templates ) {
					mw.templates.set( module, registry[ module ].templates );
				}

				// Make sure we don't run the scripts until all stylesheet insertions have completed.
				( function () {
					var pending = 0;
					checkCssHandles = function () {
						// cssHandlesRegistered ensures we don't take off too soon, e.g. when
						// one of the cssHandles is fired while we're still creating more handles.
						if ( cssHandlesRegistered && pending === 0 && runScript ) {
							runScript();
							runScript = undefined; // Revoke
						}
					};
					cssHandle = function () {
						var check = checkCssHandles;
						pending++;
						return function () {
							if ( check ) {
								pending--;
								check();
								check = undefined; // Revoke
							}
						};
					};
				}() );

				// Process styles (see also mw.loader.implement)
				// * back-compat: { <media>: css }
				// * back-compat: { <media>: [url, ..] }
				// * { "css": [css, ..] }
				// * { "url": { <media>: [url, ..] } }
				if ( registry[ module ].style ) {
					for ( key in registry[ module ].style ) {
						value = registry[ module ].style[ key ];
						media = undefined;

						if ( key !== 'url' && key !== 'css' ) {
							// Backwards compatibility, key is a media-type
							if ( typeof value === 'string' ) {
								// back-compat: { <media>: css }
								// Ignore 'media' because it isn't supported (nor was it used).
								// Strings are pre-wrapped in "@media". The media-type was just ""
								// (because it had to be set to something).
								// This is one of the reasons why this format is no longer used.
								addEmbeddedCSS( value, cssHandle() );
							} else {
								// back-compat: { <media>: [url, ..] }
								media = key;
								key = 'bc-url';
							}
						}

						// Array of css strings in key 'css',
						// or back-compat array of urls from media-type
						if ( $.isArray( value ) ) {
							for ( i = 0; i < value.length; i++ ) {
								if ( key === 'bc-url' ) {
									// back-compat: { <media>: [url, ..] }
									addLink( media, value[ i ] );
								} else if ( key === 'css' ) {
									// { "css": [css, ..] }
									addEmbeddedCSS( value[ i ], cssHandle() );
								}
							}
						// Not an array, but a regular object
						// Array of urls inside media-type key
						} else if ( typeof value === 'object' ) {
							// { "url": { <media>: [url, ..] } }
							for ( media in value ) {
								urls = value[ media ];
								for ( i = 0; i < urls.length; i++ ) {
									addLink( media, urls[ i ] );
								}
							}
						}
					}
				}

				// Kick off.
				cssHandlesRegistered = true;
				checkCssHandles();
			}

			/**
			 * Add one or more modules to the module load queue.
			 *
			 * See also #work().
			 *
			 * @private
			 * @param {string|string[]} dependencies Module name or array of string module names
			 * @param {Function} [ready] Callback to execute when all dependencies are ready
			 * @param {Function} [error] Callback to execute when any dependency fails
			 */
			function enqueue( dependencies, ready, error ) {
				// Allow calling by single module name
				if ( typeof dependencies === 'string' ) {
					dependencies = [ dependencies ];
				}

				// Add ready and error callbacks if they were given
				if ( ready !== undefined || error !== undefined ) {
					jobs.push( {
						// Narrow down the list to modules that are worth waiting for
						dependencies: $.grep( dependencies, function ( module ) {
							var state = mw.loader.getState( module );
							return state === 'registered' || state === 'loaded' || state === 'loading' || state === 'executing';
						} ),
						ready: ready,
						error: error
					} );
				}

				$.each( dependencies, function ( idx, module ) {
					var state = mw.loader.getState( module );
					// Only queue modules that are still in the initial 'registered' state
					// (not ones already loading, ready or error).
					if ( state === 'registered' && $.inArray( module, queue ) === -1 ) {
						// Private modules must be embedded in the page. Don't bother queuing
						// these as the server will deny them anyway (T101806).
						if ( registry[ module ].group === 'private' ) {
							registry[ module ].state = 'error';
							handlePending( module );
							return;
						}
						queue.push( module );
					}
				} );

				mw.loader.work();
			}

			function sortQuery( o ) {
				var key,
					sorted = {},
					a = [];

				for ( key in o ) {
					if ( hasOwn.call( o, key ) ) {
						a.push( key );
					}
				}
				a.sort();
				for ( key = 0; key < a.length; key++ ) {
					sorted[ a[ key ] ] = o[ a[ key ] ];
				}
				return sorted;
			}

			/**
			 * Converts a module map of the form { foo: [ 'bar', 'baz' ], bar: [ 'baz, 'quux' ] }
			 * to a query string of the form foo.bar,baz|bar.baz,quux
			 *
			 * @private
			 */
			function buildModulesString( moduleMap ) {
				var p, prefix,
					arr = [];

				for ( prefix in moduleMap ) {
					p = prefix === '' ? '' : prefix + '.';
					arr.push( p + moduleMap[ prefix ].join( ',' ) );
				}
				return arr.join( '|' );
			}

			/**
			 * Make a network request to load modules from the server.
			 *
			 * @private
			 * @param {Object} moduleMap Module map, see #buildModulesString
			 * @param {Object} currReqBase Object with other parameters (other than 'modules') to use in the request
			 * @param {string} sourceLoadScript URL of load.php
			 */
			function doRequest( moduleMap, currReqBase, sourceLoadScript ) {
				var query = $.extend(
					{ modules: buildModulesString( moduleMap ) },
					currReqBase
				);
				query = sortQuery( query );
				addScript( sourceLoadScript + '?' + $.param( query ) );
			}

			/**
			 * Resolve indexed dependencies.
			 *
			 * ResourceLoader uses an optimization to save space which replaces module names in
			 * dependency lists with the index of that module within the array of module
			 * registration data if it exists. The benefit is a significant reduction in the data
			 * size of the startup module. This function changes those dependency lists back to
			 * arrays of strings.
			 *
			 * @private
			 * @param {Array} modules Modules array
			 */
			function resolveIndexedDependencies( modules ) {
				var i, j, deps;
				function resolveIndex( dep ) {
					return typeof dep === 'number' ? modules[ dep ][ 0 ] : dep;
				}
				for ( i = 0; i < modules.length; i++ ) {
					deps = modules[ i ][ 2 ];
					if ( deps ) {
						for ( j = 0; j < deps.length; j++ ) {
							deps[ j ] = resolveIndex( deps[ j ] );
						}
					}
				}
			}

			/**
			 * Create network requests for a batch of modules.
			 *
			 * This is an internal method for #work(). This must not be called directly
			 * unless the modules are already registered, and no request is in progress,
			 * and the module state has already been set to `loading`.
			 *
			 * @private
			 * @param {string[]} batch
			 */
			function batchRequest( batch ) {
				var reqBase, splits, maxQueryLength, b, bSource, bGroup, bSourceGroup,
					source, group, i, modules, sourceLoadScript,
					currReqBase, currReqBaseLength, moduleMap, l,
					lastDotIndex, prefix, suffix, bytesAdded;

				if ( !batch.length ) {
					return;
				}

				// Always order modules alphabetically to help reduce cache
				// misses for otherwise identical content.
				batch.sort();

				// Build a list of query parameters common to all requests
				reqBase = {
					skin: mw.config.get( 'skin' ),
					lang: mw.config.get( 'wgUserLanguage' ),
					debug: mw.config.get( 'debug' )
				};
				maxQueryLength = mw.config.get( 'wgResourceLoaderMaxQueryLength', 2000 );

				// Split module list by source and by group.
				splits = {};
				for ( b = 0; b < batch.length; b++ ) {
					bSource = registry[ batch[ b ] ].source;
					bGroup = registry[ batch[ b ] ].group;
					if ( !hasOwn.call( splits, bSource ) ) {
						splits[ bSource ] = {};
					}
					if ( !hasOwn.call( splits[ bSource ], bGroup ) ) {
						splits[ bSource ][ bGroup ] = [];
					}
					bSourceGroup = splits[ bSource ][ bGroup ];
					bSourceGroup.push( batch[ b ] );
				}

				for ( source in splits ) {

					sourceLoadScript = sources[ source ];

					for ( group in splits[ source ] ) {

						// Cache access to currently selected list of
						// modules for this group from this source.
						modules = splits[ source ][ group ];

						currReqBase = $.extend( {
							version: getCombinedVersion( modules )
						}, reqBase );
						// For user modules append a user name to the query string.
						if ( group === 'user' && mw.config.get( 'wgUserName' ) !== null ) {
							currReqBase.user = mw.config.get( 'wgUserName' );
						}
						currReqBaseLength = $.param( currReqBase ).length;
						// We may need to split up the request to honor the query string length limit,
						// so build it piece by piece.
						l = currReqBaseLength + 9; // '&modules='.length == 9

						moduleMap = {}; // { prefix: [ suffixes ] }

						for ( i = 0; i < modules.length; i++ ) {
							// Determine how many bytes this module would add to the query string
							lastDotIndex = modules[ i ].lastIndexOf( '.' );

							// If lastDotIndex is -1, substr() returns an empty string
							prefix = modules[ i ].substr( 0, lastDotIndex );
							suffix = modules[ i ].slice( lastDotIndex + 1 );

							bytesAdded = hasOwn.call( moduleMap, prefix )
								? suffix.length + 3 // '%2C'.length == 3
								: modules[ i ].length + 3; // '%7C'.length == 3

							// If the url would become too long, create a new one,
							// but don't create empty requests
							if ( maxQueryLength > 0 && !$.isEmptyObject( moduleMap ) && l + bytesAdded > maxQueryLength ) {
								// This url would become too long, create a new one, and start the old one
								doRequest( moduleMap, currReqBase, sourceLoadScript );
								moduleMap = {};
								l = currReqBaseLength + 9;
								mw.track( 'resourceloader.splitRequest', { maxQueryLength: maxQueryLength } );
							}
							if ( !hasOwn.call( moduleMap, prefix ) ) {
								moduleMap[ prefix ] = [];
							}
							moduleMap[ prefix ].push( suffix );
							l += bytesAdded;
						}
						// If there's anything left in moduleMap, request that too
						if ( !$.isEmptyObject( moduleMap ) ) {
							doRequest( moduleMap, currReqBase, sourceLoadScript );
						}
					}
				}
			}

			/**
			 * @private
			 * @param {string[]} implementations Array containing pieces of JavaScript code in the
			 *  form of calls to mw.loader#implement().
			 * @param {Function} cb Callback in case of failure
			 * @param {Error} cb.err
			 */
			function asyncEval( implementations, cb ) {
				if ( !implementations.length ) {
					return;
				}
				mw.requestIdleCallback( function () {
					try {
						$.globalEval( implementations.join( ';' ) );
					} catch ( err ) {
						cb( err );
					}
				} );
			}

			/**
			 * Make a versioned key for a specific module.
			 *
			 * @private
			 * @param {string} module Module name
			 * @return {string|null} Module key in format '`[name]@[version]`',
			 *  or null if the module does not exist
			 */
			function getModuleKey( module ) {
				return hasOwn.call( registry, module ) ?
					( module + '@' + registry[ module ].version ) : null;
			}

			/**
			 * @private
			 * @param {string} key Module name or '`[name]@[version]`'
			 * @return {Object}
			 */
			function splitModuleKey( key ) {
				var index = key.indexOf( '@' );
				if ( index === -1 ) {
					return { name: key };
				}
				return {
					name: key.slice( 0, index ),
					version: key.slice( index + 1 )
				};
			}

			/* Public Members */
			return {
				/**
				 * The module registry is exposed as an aid for debugging and inspecting page
				 * state; it is not a public interface for modifying the registry.
				 *
				 * @see #registry
				 * @property
				 * @private
				 */
				moduleRegistry: registry,

				/**
				 * @inheritdoc #newStyleTag
				 * @method
				 */
				addStyleTag: newStyleTag,

				/**
				 * Start loading of all queued module dependencies.
				 *
				 * @protected
				 */
				work: function () {
					var q, batch, implementations, sourceModules;

					batch = [];

					// Appends a list of modules from the queue to the batch
					for ( q = 0; q < queue.length; q++ ) {
						// Only load modules which are registered
						if ( hasOwn.call( registry, queue[ q ] ) && registry[ queue[ q ] ].state === 'registered' ) {
							// Prevent duplicate entries
							if ( $.inArray( queue[ q ], batch ) === -1 ) {
								batch.push( queue[ q ] );
								// Mark registered modules as loading
								registry[ queue[ q ] ].state = 'loading';
							}
						}
					}

					// Now that the queue has been processed into a batch, clear the queue.
					// This MUST happen before we initiate any eval or network request. Otherwise,
					// it is possible for a cached script to instantly trigger the same work queue
					// again; all before we've cleared it causing each request to include modules
					// which are already loaded.
					queue = [];

					if ( !batch.length ) {
						return;
					}

					mw.loader.store.init();
					if ( mw.loader.store.enabled ) {
						implementations = [];
						sourceModules = [];
						batch = $.grep( batch, function ( module ) {
							var implementation = mw.loader.store.get( module );
							if ( implementation ) {
								implementations.push( implementation );
								sourceModules.push( module );
								return false;
							}
							return true;
						} );
						asyncEval( implementations, function ( err ) {
							// Not good, the cached mw.loader.implement calls failed! This should
							// never happen, barring ResourceLoader bugs, browser bugs and PEBKACs.
							// Depending on how corrupt the string is, it is likely that some
							// modules' implement() succeeded while the ones after the error will
							// never run and leave their modules in the 'loading' state forever.

							// Since this is an error not caused by an individual module but by
							// something that infected the implement call itself, don't take any
							// risks and clear everything in this cache.
							mw.loader.store.clear();

							mw.track( 'resourceloader.exception', { exception: err, source: 'store-eval' } );
							// Re-add the failed ones that are still pending back to the batch
							var failed = $.grep( sourceModules, function ( module ) {
								return registry[ module ].state === 'loading';
							} );
							batchRequest( failed );
						} );
					}

					batchRequest( batch );
				},

				/**
				 * Register a source.
				 *
				 * The #work() method will use this information to split up requests by source.
				 *
				 *     mw.loader.addSource( 'mediawikiwiki', '//www.mediawiki.org/w/load.php' );
				 *
				 * @param {string|Object} id Source ID, or object mapping ids to load urls
				 * @param {string} loadUrl Url to a load.php end point
				 * @throws {Error} If source id is already registered
				 */
				addSource: function ( id, loadUrl ) {
					var source;
					// Allow multiple additions
					if ( typeof id === 'object' ) {
						for ( source in id ) {
							mw.loader.addSource( source, id[ source ] );
						}
						return;
					}

					if ( hasOwn.call( sources, id ) ) {
						throw new Error( 'source already registered: ' + id );
					}

					sources[ id ] = loadUrl;
				},

				/**
				 * Register a module, letting the system know about it and its properties.
				 *
				 * The startup modules contain calls to this method.
				 *
				 * When using multiple module registration by passing an array, dependencies that
				 * are specified as references to modules within the array will be resolved before
				 * the modules are registered.
				 *
				 * @param {string|Array} module Module name or array of arrays, each containing
				 *  a list of arguments compatible with this method
				 * @param {string|number} version Module version hash (falls backs to empty string)
				 *  Can also be a number (timestamp) for compatibility with MediaWiki 1.25 and earlier.
				 * @param {string|Array|Function} dependencies One string or array of strings of module
				 *  names on which this module depends, or a function that returns that array.
				 * @param {string} [group=null] Group which the module is in
				 * @param {string} [source='local'] Name of the source
				 * @param {string} [skip=null] Script body of the skip function
				 */
				register: function ( module, version, dependencies, group, source, skip ) {
					var i, deps;
					// Allow multiple registration
					if ( typeof module === 'object' ) {
						resolveIndexedDependencies( module );
						for ( i = 0; i < module.length; i++ ) {
							// module is an array of module names
							if ( typeof module[ i ] === 'string' ) {
								mw.loader.register( module[ i ] );
							// module is an array of arrays
							} else if ( typeof module[ i ] === 'object' ) {
								mw.loader.register.apply( mw.loader, module[ i ] );
							}
						}
						return;
					}
					if ( hasOwn.call( registry, module ) ) {
						throw new Error( 'module already registered: ' + module );
					}
					if ( typeof dependencies === 'string' ) {
						// A single module name
						deps = [ dependencies ];
					} else if ( typeof dependencies === 'object' || typeof dependencies === 'function' ) {
						// Array of module names or a function that returns an array
						deps = dependencies;
					}
					// List the module as registered
					registry[ module ] = {
						// Exposed to execute() for mw.loader.implement() closures.
						// Import happens via require().
						module: {
							exports: {}
						},
						version: version !== undefined ? String( version ) : '',
						dependencies: deps || [],
						group: typeof group === 'string' ? group : null,
						source: typeof source === 'string' ? source : 'local',
						state: 'registered',
						skip: typeof skip === 'string' ? skip : null
					};
				},

				/**
				 * Implement a module given the components that make up the module.
				 *
				 * When #load() or #using() requests one or more modules, the server
				 * response contain calls to this function.
				 *
				 * @param {string} module Name of module and current module version. Formatted
				 *  as '`[name]@[version]`". This version should match the requested version
				 *  (from #batchRequest and #registry). This avoids race conditions (T117587).
				 *  For back-compat with MediaWiki 1.27 and earlier, the version may be omitted.
				 * @param {Function|Array|string} [script] Function with module code, list of URLs
				 *  to load via `<script src>`, or string of module code for `$.globalEval()`.
				 * @param {Object} [style] Should follow one of the following patterns:
				 *
				 *     { "css": [css, ..] }
				 *     { "url": { <media>: [url, ..] } }
				 *
				 * And for backwards compatibility (needs to be supported forever due to caching):
				 *
				 *     { <media>: css }
				 *     { <media>: [url, ..] }
				 *
				 * The reason css strings are not concatenated anymore is bug 31676. We now check
				 * whether it's safe to extend the stylesheet.
				 *
				 * @protected
				 * @param {Object} [messages] List of key/value pairs to be added to mw#messages.
				 * @param {Object} [templates] List of key/value pairs to be added to mw#templates.
				 */
				implement: function ( module, script, style, messages, templates ) {
					var split = splitModuleKey( module ),
						name = split.name,
						version = split.version;
					// Automatically register module
					if ( !hasOwn.call( registry, name ) ) {
						mw.loader.register( name );
					}
					// Check for duplicate implementation
					if ( hasOwn.call( registry, name ) && registry[ name ].script !== undefined ) {
						throw new Error( 'module already implemented: ' + name );
					}
					if ( version ) {
						// Without this reset, if there is a version mismatch between the
						// requested and received module version, then mw.loader.store would
						// cache the response under the requested key. Thus poisoning the cache
						// indefinitely with a stale value. (T117587)
						registry[ name ].version = version;
					}
					// Attach components
					registry[ name ].script = script || null;
					registry[ name ].style = style || null;
					registry[ name ].messages = messages || null;
					registry[ name ].templates = templates || null;
					// The module may already have been marked as erroneous
					if ( $.inArray( registry[ name ].state, [ 'error', 'missing' ] ) === -1 ) {
						registry[ name ].state = 'loaded';
						if ( allReady( registry[ name ].dependencies ) ) {
							execute( name );
						}
					}
				},

				/**
				 * Execute a function as soon as one or more required modules are ready.
				 *
				 * Example of inline dependency on OOjs:
				 *
				 *     mw.loader.using( 'oojs', function () {
				 *         OO.compare( [ 1 ], [ 1 ] );
				 *     } );
				 *
				 * Since MediaWiki 1.23 this also returns a promise.
				 *
				 * Since MediaWiki 1.28 the promise is resolved with a `require` function.
				 *
				 * @param {string|Array} dependencies Module name or array of modules names the
				 *  callback depends on to be ready before executing
				 * @param {Function} [ready] Callback to execute when all dependencies are ready
				 * @param {Function} [error] Callback to execute if one or more dependencies failed
				 * @return {jQuery.Promise} With a `require` function
				 */
				using: function ( dependencies, ready, error ) {
					var deferred = $.Deferred();

					// Allow calling with a single dependency as a string
					if ( typeof dependencies === 'string' ) {
						dependencies = [ dependencies ];
					}

					if ( ready ) {
						deferred.done( ready );
					}
					if ( error ) {
						deferred.fail( error );
					}

					// Resolve entire dependency map
					dependencies = resolve( dependencies );
					if ( allReady( dependencies ) ) {
						// Run ready immediately
						deferred.resolve( mw.loader.require );
					} else if ( anyFailed( dependencies ) ) {
						// Execute error immediately if any dependencies have errors
						deferred.reject(
							new Error( 'One or more dependencies failed to load' ),
							dependencies
						);
					} else {
						// Not all dependencies are ready, add to the load queue
						enqueue( dependencies, function () {
							deferred.resolve( mw.loader.require );
						}, deferred.reject );
					}

					return deferred.promise();
				},

				/**
				 * Load an external script or one or more modules.
				 *
				 * @param {string|Array} modules Either the name of a module, array of modules,
				 *  or a URL of an external script or style
				 * @param {string} [type='text/javascript'] MIME type to use if calling with a URL of an
				 *  external script or style; acceptable values are "text/css" and
				 *  "text/javascript"; if no type is provided, text/javascript is assumed.
				 */
				load: function ( modules, type ) {
					var filtered, l;

					// Allow calling with a url or single dependency as a string
					if ( typeof modules === 'string' ) {
						// "https://example.org/x.js", "http://example.org/x.js", "//example.org/x.js", "/x.js"
						if ( /^(https?:)?\/?\//.test( modules ) ) {
							if ( type === 'text/css' ) {
								// Support: IE 7-8
								// Use properties instead of attributes as IE throws security
								// warnings when inserting a <link> tag with a protocol-relative
								// URL set though attributes - when on HTTPS. See bug 41331.
								l = document.createElement( 'link' );
								l.rel = 'stylesheet';
								l.href = modules;
								$( 'head' ).append( l );
								return;
							}
							if ( type === 'text/javascript' || type === undefined ) {
								addScript( modules );
								return;
							}
							// Unknown type
							throw new Error( 'invalid type for external url, must be text/css or text/javascript. not ' + type );
						}
						// Called with single module
						modules = [ modules ];
					}

					// Filter out undefined modules, otherwise resolve() will throw
					// an exception for trying to load an undefined module.
					// Undefined modules are acceptable here in load(), because load() takes
					// an array of unrelated modules, whereas the modules passed to
					// using() are related and must all be loaded.
					filtered = $.grep( modules, function ( module ) {
						var state = mw.loader.getState( module );
						return state !== null && state !== 'error' && state !== 'missing';
					} );

					if ( filtered.length === 0 ) {
						return;
					}
					// Resolve entire dependency map
					filtered = resolve( filtered );
					// If all modules are ready, or if any modules have errors, nothing to be done.
					if ( allReady( filtered ) || anyFailed( filtered ) ) {
						return;
					}
					// Some modules are not yet ready, add to module load queue.
					enqueue( filtered, undefined, undefined );
				},

				/**
				 * Change the state of one or more modules.
				 *
				 * @param {string|Object} module Module name or object of module name/state pairs
				 * @param {string} state State name
				 */
				state: function ( module, state ) {
					var m;

					if ( typeof module === 'object' ) {
						for ( m in module ) {
							mw.loader.state( m, module[ m ] );
						}
						return;
					}
					if ( !hasOwn.call( registry, module ) ) {
						mw.loader.register( module );
					}
					registry[ module ].state = state;
					if ( $.inArray( state, [ 'ready', 'error', 'missing' ] ) !== -1 ) {
						// Make sure pending modules depending on this one get executed if their
						// dependencies are now fulfilled!
						handlePending( module );
					}
				},

				/**
				 * Get the version of a module.
				 *
				 * @param {string} module Name of module
				 * @return {string|null} The version, or null if the module (or its version) is not
				 *  in the registry.
				 */
				getVersion: function ( module ) {
					if ( !hasOwn.call( registry, module ) || registry[ module ].version === undefined ) {
						return null;
					}
					return registry[ module ].version;
				},

				/**
				 * Get the state of a module.
				 *
				 * @param {string} module Name of module
				 * @return {string|null} The state, or null if the module (or its state) is not
				 *  in the registry.
				 */
				getState: function ( module ) {
					if ( !hasOwn.call( registry, module ) || registry[ module ].state === undefined ) {
						return null;
					}
					return registry[ module ].state;
				},

				/**
				 * Get the names of all registered modules.
				 *
				 * @return {Array}
				 */
				getModuleNames: function () {
					return $.map( registry, function ( i, key ) {
						return key;
					} );
				},

				/**
				 * Get the exported value of a module.
				 *
				 * Modules may provide this via their local `module.exports`.
				 *
				 * @protected
				 * @since 1.27
				 */
				require: function ( moduleName ) {
					var state = mw.loader.getState( moduleName );

					// Only ready modules can be required
					if ( state !== 'ready' ) {
						// Module may've forgotten to declare a dependency
						throw new Error( 'Module "' + moduleName + '" is not loaded.' );
					}

					return registry[ moduleName ].module.exports;
				},

				/**
				 * @inheritdoc mw.inspect#runReports
				 * @method
				 */
				inspect: function () {
					var args = slice.call( arguments );
					mw.loader.using( 'mediawiki.inspect', function () {
						mw.inspect.runReports.apply( mw.inspect, args );
					} );
				},

				/**
				 * On browsers that implement the localStorage API, the module store serves as a
				 * smart complement to the browser cache. Unlike the browser cache, the module store
				 * can slice a concatenated response from ResourceLoader into its constituent
				 * modules and cache each of them separately, using each module's versioning scheme
				 * to determine when the cache should be invalidated.
				 *
				 * @singleton
				 * @class mw.loader.store
				 */
				store: {
					// Whether the store is in use on this page.
					enabled: null,

					MODULE_SIZE_MAX: 100 * 1000,

					// The contents of the store, mapping '[name]@[version]' keys
					// to module implementations.
					items: {},

					// Cache hit stats
					stats: { hits: 0, misses: 0, expired: 0 },

					/**
					 * Construct a JSON-serializable object representing the content of the store.
					 *
					 * @return {Object} Module store contents.
					 */
					toJSON: function () {
						return { items: mw.loader.store.items, vary: mw.loader.store.getVary() };
					},

					/**
					 * Get the localStorage key for the entire module store. The key references
					 * $wgDBname to prevent clashes between wikis which share a common host.
					 *
					 * @return {string} localStorage item key
					 */
					getStoreKey: function () {
						return 'MediaWikiModuleStore:' + mw.config.get( 'wgDBname' );
					},

					/**
					 * Get a key on which to vary the module cache.
					 *
					 * @return {string} String of concatenated vary conditions.
					 */
					getVary: function () {
						return [
							mw.config.get( 'skin' ),
							mw.config.get( 'wgResourceLoaderStorageVersion' ),
							mw.config.get( 'wgUserLanguage' )
						].join( ':' );
					},

					/**
					 * Initialize the store.
					 *
					 * Retrieves store from localStorage and (if successfully retrieved) decoding
					 * the stored JSON value to a plain object.
					 *
					 * The try / catch block is used for JSON & localStorage feature detection.
					 * See the in-line documentation for Modernizr's localStorage feature detection
					 * code for a full account of why we need a try / catch:
					 * <https://github.com/Modernizr/Modernizr/blob/v2.7.1/modernizr.js#L771-L796>.
					 */
					init: function () {
						var raw, data;

						if ( mw.loader.store.enabled !== null ) {
							// Init already ran
							return;
						}

						if (
							// Disabled because localStorage quotas are tight and (in Firefox's case)
							// shared by multiple origins.
							// See T66721, and <https://bugzilla.mozilla.org/show_bug.cgi?id=1064466>.
							/Firefox|Opera/.test( navigator.userAgent ) ||

							// Disabled by configuration.
							!mw.config.get( 'wgResourceLoaderStorageEnabled' )
						) {
							// Clear any previous store to free up space. (T66721)
							mw.loader.store.clear();
							mw.loader.store.enabled = false;
							return;
						}
						if ( mw.config.get( 'debug' ) ) {
							// Disable module store in debug mode
							mw.loader.store.enabled = false;
							return;
						}

						try {
							raw = localStorage.getItem( mw.loader.store.getStoreKey() );
							// If we get here, localStorage is available; mark enabled
							mw.loader.store.enabled = true;
							data = JSON.parse( raw );
							if ( data && typeof data.items === 'object' && data.vary === mw.loader.store.getVary() ) {
								mw.loader.store.items = data.items;
								return;
							}
						} catch ( e ) {
							mw.track( 'resourceloader.exception', { exception: e, source: 'store-localstorage-init' } );
						}

						if ( raw === undefined ) {
							// localStorage failed; disable store
							mw.loader.store.enabled = false;
						} else {
							mw.loader.store.update();
						}
					},

					/**
					 * Retrieve a module from the store and update cache hit stats.
					 *
					 * @param {string} module Module name
					 * @return {string|boolean} Module implementation or false if unavailable
					 */
					get: function ( module ) {
						var key;

						if ( !mw.loader.store.enabled ) {
							return false;
						}

						key = getModuleKey( module );
						if ( key in mw.loader.store.items ) {
							mw.loader.store.stats.hits++;
							return mw.loader.store.items[ key ];
						}
						mw.loader.store.stats.misses++;
						return false;
					},

					/**
					 * Stringify a module and queue it for storage.
					 *
					 * @param {string} module Module name
					 * @param {Object} descriptor The module's descriptor as set in the registry
					 */
					set: function ( module, descriptor ) {
						var args, key, src;

						if ( !mw.loader.store.enabled ) {
							return false;
						}

						key = getModuleKey( module );

						if (
							// Already stored a copy of this exact version
							key in mw.loader.store.items ||
							// Module failed to load
							descriptor.state !== 'ready' ||
							// Unversioned, private, or site-/user-specific
							( !descriptor.version || $.inArray( descriptor.group, [ 'private', 'user' ] ) !== -1 ) ||
							// Partial descriptor
							// (e.g. skipped module, or style module with state=ready)
							$.inArray( undefined, [ descriptor.script, descriptor.style,
									descriptor.messages, descriptor.templates ] ) !== -1
						) {
							// Decline to store
							return false;
						}

						try {
							args = [
								JSON.stringify( key ),
								typeof descriptor.script === 'function' ?
									String( descriptor.script ) :
									JSON.stringify( descriptor.script ),
								JSON.stringify( descriptor.style ),
								JSON.stringify( descriptor.messages ),
								JSON.stringify( descriptor.templates )
							];
							// Attempted workaround for a possible Opera bug (bug T59567).
							// This regex should never match under sane conditions.
							if ( /^\s*\(/.test( args[ 1 ] ) ) {
								args[ 1 ] = 'function' + args[ 1 ];
								mw.track( 'resourceloader.assert', { source: 'bug-T59567' } );
							}
						} catch ( e ) {
							mw.track( 'resourceloader.exception', { exception: e, source: 'store-localstorage-json' } );
							return;
						}

						src = 'mw.loader.implement(' + args.join( ',' ) + ');';
						if ( src.length > mw.loader.store.MODULE_SIZE_MAX ) {
							return false;
						}
						mw.loader.store.items[ key ] = src;
						mw.loader.store.update();
					},

					/**
					 * Iterate through the module store, removing any item that does not correspond
					 * (in name and version) to an item in the module registry.
					 */
					prune: function () {
						var key, module;

						if ( !mw.loader.store.enabled ) {
							return false;
						}

						for ( key in mw.loader.store.items ) {
							module = key.slice( 0, key.indexOf( '@' ) );
							if ( getModuleKey( module ) !== key ) {
								mw.loader.store.stats.expired++;
								delete mw.loader.store.items[ key ];
							} else if ( mw.loader.store.items[ key ].length > mw.loader.store.MODULE_SIZE_MAX ) {
								// This value predates the enforcement of a size limit on cached modules.
								delete mw.loader.store.items[ key ];
							}
						}
					},

					/**
					 * Clear the entire module store right now.
					 */
					clear: function () {
						mw.loader.store.items = {};
						try {
							localStorage.removeItem( mw.loader.store.getStoreKey() );
						} catch ( ignored ) {}
					},

					/**
					 * Sync in-memory store back to localStorage.
					 *
					 * This function debounces updates. When called with a flush already pending,
					 * the call is coalesced into the pending update. The call to
					 * localStorage.setItem will be naturally deferred until the page is quiescent.
					 *
					 * Because localStorage is shared by all pages from the same origin, if multiple
					 * pages are loaded with different module sets, the possibility exists that
					 * modules saved by one page will be clobbered by another. But the impact would
					 * be minor and the problem would be corrected by subsequent page views.
					 *
					 * @method
					 */
					update: ( function () {
						var hasPendingWrite = false;

						function flushWrites() {
							var data, key;
							if ( !hasPendingWrite || !mw.loader.store.enabled ) {
								return;
							}

							mw.loader.store.prune();
							key = mw.loader.store.getStoreKey();
							try {
								// Replacing the content of the module store might fail if the new
								// contents would exceed the browser's localStorage size limit. To
								// avoid clogging the browser with stale data, always remove the old
								// value before attempting to set the new one.
								localStorage.removeItem( key );
								data = JSON.stringify( mw.loader.store );
								localStorage.setItem( key, data );
							} catch ( e ) {
								mw.track( 'resourceloader.exception', { exception: e, source: 'store-localstorage-update' } );
							}

							hasPendingWrite = false;
						}

						return function () {
							if ( !hasPendingWrite ) {
								hasPendingWrite = true;
								mw.requestIdleCallback( flushWrites );
							}
						};
					}() )
				}
			};
		}() ),

		/**
		 * HTML construction helper functions
		 *
		 *     @example
		 *
		 *     var Html, output;
		 *
		 *     Html = mw.html;
		 *     output = Html.element( 'div', {}, new Html.Raw(
		 *         Html.element( 'img', { src: '<' } )
		 *     ) );
		 *     mw.log( output ); // <div><img src="&lt;"/></div>
		 *
		 * @class mw.html
		 * @singleton
		 */
		html: ( function () {
			function escapeCallback( s ) {
				switch ( s ) {
					case '\'':
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

			return {
				/**
				 * Escape a string for HTML.
				 *
				 * Converts special characters to HTML entities.
				 *
				 *     mw.html.escape( '< > \' & "' );
				 *     // Returns &lt; &gt; &#039; &amp; &quot;
				 *
				 * @param {string} s The string to escape
				 * @return {string} HTML
				 */
				escape: function ( s ) {
					return s.replace( /['"<>&]/g, escapeCallback );
				},

				/**
				 * Create an HTML element string, with safe escaping.
				 *
				 * @param {string} name The tag name.
				 * @param {Object} [attrs] An object with members mapping element names to values
				 * @param {string|mw.html.Raw|mw.html.Cdata|null} [contents=null] The contents of the element.
				 *
				 *  - string: Text to be escaped.
				 *  - null: The element is treated as void with short closing form, e.g. `<br/>`.
				 *  - this.Raw: The raw value is directly included.
				 *  - this.Cdata: The raw value is directly included. An exception is
				 *    thrown if it contains any illegal ETAGO delimiter.
				 *    See <http://www.w3.org/TR/html401/appendix/notes.html#h-B.3.2>.
				 * @return {string} HTML
				 */
				element: function ( name, attrs, contents ) {
					var v, attrName, s = '<' + name;

					if ( attrs ) {
						for ( attrName in attrs ) {
							v = attrs[ attrName ];
							// Convert name=true, to name=name
							if ( v === true ) {
								v = attrName;
							// Skip name=false
							} else if ( v === false ) {
								continue;
							}
							s += ' ' + attrName + '="' + this.escape( String( v ) ) + '"';
						}
					}
					if ( contents === undefined || contents === null ) {
						// Self close tag
						s += '/>';
						return s;
					}
					// Regular open tag
					s += '>';
					switch ( typeof contents ) {
						case 'string':
							// Escaped
							s += this.escape( contents );
							break;
						case 'number':
						case 'boolean':
							// Convert to string
							s += String( contents );
							break;
						default:
							if ( contents instanceof this.Raw ) {
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
					}
					s += '</' + name + '>';
					return s;
				},

				/**
				 * Wrapper object for raw HTML passed to mw.html.element().
				 *
				 * @class mw.html.Raw
				 */
				Raw: function ( value ) {
					this.value = value;
				},

				/**
				 * Wrapper object for CDATA element contents passed to mw.html.element()
				 *
				 * @class mw.html.Cdata
				 */
				Cdata: function ( value ) {
					this.value = value;
				}
			};
		}() ),

		// Skeleton user object, extended by the 'mediawiki.user' module.
		/**
		 * @class mw.user
		 * @singleton
		 */
		user: {
			/**
			 * @property {mw.Map}
			 */
			options: new Map(),
			/**
			 * @property {mw.Map}
			 */
			tokens: new Map()
		},

		// OOUI widgets specific to MediaWiki
		widgets: {},

		/**
		 * Registry and firing of events.
		 *
		 * MediaWiki has various interface components that are extended, enhanced
		 * or manipulated in some other way by extensions, gadgets and even
		 * in core itself.
		 *
		 * This framework helps streamlining the timing of when these other
		 * code paths fire their plugins (instead of using document-ready,
		 * which can and should be limited to firing only once).
		 *
		 * Features like navigating to other wiki pages, previewing an edit
		 * and editing itself – without a refresh – can then retrigger these
		 * hooks accordingly to ensure everything still works as expected.
		 *
		 * Example usage:
		 *
		 *     mw.hook( 'wikipage.content' ).add( fn ).remove( fn );
		 *     mw.hook( 'wikipage.content' ).fire( $content );
		 *
		 * Handlers can be added and fired for arbitrary event names at any time. The same
		 * event can be fired multiple times. The last run of an event is memorized
		 * (similar to `$(document).ready` and `$.Deferred().done`).
		 * This means if an event is fired, and a handler added afterwards, the added
		 * function will be fired right away with the last given event data.
		 *
		 * Like Deferreds and Promises, the mw.hook object is both detachable and chainable.
		 * Thus allowing flexible use and optimal maintainability and authority control.
		 * You can pass around the `add` and/or `fire` method to another piece of code
		 * without it having to know the event name (or `mw.hook` for that matter).
		 *
		 *     var h = mw.hook( 'bar.ready' );
		 *     new mw.Foo( .. ).fetch( { callback: h.fire } );
		 *
		 * Note: Events are documented with an underscore instead of a dot in the event
		 * name due to jsduck not supporting dots in that position.
		 *
		 * @class mw.hook
		 */
		hook: ( function () {
			var lists = {};

			/**
			 * Create an instance of mw.hook.
			 *
			 * @method hook
			 * @member mw
			 * @param {string} name Name of hook.
			 * @return {mw.hook}
			 */
			return function ( name ) {
				var list = hasOwn.call( lists, name ) ?
					lists[ name ] :
					lists[ name ] = $.Callbacks( 'memory' );

				return {
					/**
					 * Register a hook handler
					 *
					 * @param {...Function} handler Function to bind.
					 * @chainable
					 */
					add: list.add,

					/**
					 * Unregister a hook handler
					 *
					 * @param {...Function} handler Function to unbind.
					 * @chainable
					 */
					remove: list.remove,

					/**
					 * Run a hook.
					 *
					 * @param {...Mixed} data
					 * @chainable
					 */
					fire: function () {
						return list.fireWith.call( this, null, slice.call( arguments ) );
					}
				};
			};
		}() )
	};

	// Alias $j to jQuery for backwards compatibility
	// @deprecated since 1.23 Use $ or jQuery instead
	mw.log.deprecate( window, '$j', $, 'Use $ or jQuery instead.' );

	/**
	 * Log a message to window.console, if possible.
	 *
	 * Useful to force logging of some errors that are otherwise hard to detect (i.e., this logs
	 * also in production mode). Gets console references in each invocation instead of caching the
	 * reference, so that debugging tools loaded later are supported (e.g. Firebug Lite in IE).
	 *
	 * @private
	 * @param {string} topic Stream name passed by mw.track
	 * @param {Object} data Data passed by mw.track
	 * @param {Error} [data.exception]
	 * @param {string} data.source Error source
	 * @param {string} [data.module] Name of module which caused the error
	 */
	function logError( topic, data ) {
		var msg,
			e = data.exception,
			source = data.source,
			module = data.module,
			console = window.console;

		if ( console && console.log ) {
			msg = ( e ? 'Exception' : 'Error' ) + ' in ' + source;
			if ( module ) {
				msg += ' in module ' + module;
			}
			msg += ( e ? ':' : '.' );
			console.log( msg );

			// If we have an exception object, log it to the error channel to trigger
			// proper stacktraces in browsers that support it. No fallback as we have
			// no browsers that don't support error(), but do support log().
			if ( e && console.error ) {
				console.error( String( e ), e );
			}
		}
	}

	// Subscribe to error streams
	mw.trackSubscribe( 'resourceloader.exception', logError );
	mw.trackSubscribe( 'resourceloader.assert', logError );

	/**
	 * Fired when all modules associated with the page have finished loading.
	 *
	 * @event resourceloader_loadEnd
	 * @member mw.hook
	 */
	$( function () {
		var loading = $.grep( mw.loader.getModuleNames(), function ( module ) {
			return mw.loader.getState( module ) === 'loading';
		} );
		// We only need a callback, not any actual module. First try a single using()
		// for all loading modules. If one fails, fall back to tracking each module
		// separately via $.when(), this is expensive.
		loading = mw.loader.using( loading ).then( null, function () {
			var all = $.map( loading, function ( module ) {
				return mw.loader.using( module ).then( null, function () {
					return $.Deferred().resolve();
				} );
			} );
			return $.when.apply( $, all );
		} );
		loading.then( function () {
			mwPerformance.mark( 'mwLoadEnd' );
			mw.hook( 'resourceloader.loadEnd' ).fire();
		} );
	} );

	// Attach to window and globally alias
	window.mw = window.mediaWiki = mw;
}( jQuery ) );
