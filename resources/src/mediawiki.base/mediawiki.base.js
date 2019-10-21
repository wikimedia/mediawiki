/*!
 * This file is currently loaded as part of the 'mediawiki' module and therefore
 * concatenated to mediawiki.js and executed at the same time. This file exists
 * to help prepare for splitting up the 'mediawiki' module.
 * This effort is tracked at https://phabricator.wikimedia.org/T192623
 *
 * In short:
 *
 * - mediawiki.js will be reduced to the minimum needed to define mw.loader and
 *   mw.config, and then moved to its own private "mediawiki.loader" module that
 *   can be embedded within the StartupModule response.
 *
 * - mediawiki.base.js and other files in this directory will remain part of the
 *   "mediawiki" module, and will remain a default/implicit dependency for all
 *   regular modules, just like jquery and wikibits already are.
 */
( function () {
	'use strict';

	var slice = Array.prototype.slice,
		mwLoaderTrack = mw.track,
		trackCallbacks = $.Callbacks( 'memory' ),
		trackHandlers = [],
		queue;

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
			var text = this.map.get( this.key );
			if (
				mw.config.get( 'wgUserLanguage' ) === 'qqx' &&
				( !text || text === '(' + this.key + ')' )
			) {
				text = '(' + this.key + '$*)';
			}
			return mw.format.apply( null, [ text ].concat( this.parameters ) );
		},

		/**
		 * Add (does not replace) parameters for `$N` placeholder values.
		 *
		 * @param {Array} parameters
		 * @return {mw.Message}
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
				// Use ⧼key⧽ as text if key does not exist
				// Err on the side of safety, ensure that the output
				// is always html safe in the event the message key is
				// missing, since in that case its highly likely the
				// message key is user-controlled.
				// '⧼' is used instead of '<' to side-step any
				// double-escaping issues.
				// (Keep synchronised with Message::toString() in PHP.)
				return '⧼' + mw.html.escape( this.key ) + '⧽';
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
			if ( mw.config.get( 'wgUserLanguage' ) === 'qqx' ) {
				return true;
			}
			return this.map.exists( this.key );
		}
	};

	/**
	 * @class mw
	 * @singleton
	 */

	/**
	 * Empty object for third-party libraries, for cases where you don't
	 * want to add a new global, or the global is bad and needs containment
	 * or wrapping.
	 *
	 * @property
	 */
	mw.libs = {};

	// OOUI widgets specific to MediaWiki
	mw.widgets = {};

	/**
	 * @inheritdoc mw.inspect#runReports
	 * @method
	 */
	mw.inspect = function () {
		var args = arguments;
		// Lazy-load
		mw.loader.using( 'mediawiki.inspect', function () {
			mw.inspect.runReports.apply( mw.inspect, args );
		} );
	};

	/**
	 * Replace $* with a list of parameters for &uselang=qqx.
	 *
	 * @private
	 * @since 1.33
	 * @param {string} formatString Format string
	 * @param {Array} parameters Values for $N replacements
	 * @return {string} Transformed format string
	 */
	mw.internalDoTransformFormatForQqx = function ( formatString, parameters ) {
		var parametersString;
		if ( formatString.indexOf( '$*' ) !== -1 ) {
			parametersString = '';
			if ( parameters.length ) {
				parametersString = ': ' + parameters.map( function ( _, i ) {
					return '$' + ( i + 1 );
				} ).join( ', ' );
			}
			return formatString.replace( '$*', parametersString );
		}
		return formatString;
	};

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
	mw.format = function ( formatString ) {
		var parameters = slice.call( arguments, 1 );
		formatString = mw.internalDoTransformFormatForQqx( formatString, parameters );
		return formatString.replace( /\$(\d+)/g, function ( str, match ) {
			var index = parseInt( match, 10 ) - 1;
			return parameters[ index ] !== undefined ? parameters[ index ] : '$' + match;
		} );
	};

	// Expose Message constructor
	mw.Message = Message;

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
	mw.message = function ( key ) {
		var parameters = slice.call( arguments, 1 );
		return new Message( mw.messages, key, parameters );
	};

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
	mw.msg = function () {
		return mw.message.apply( mw.message, arguments ).toString();
	};

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
	mw.track = function ( topic, data ) {
		mwLoaderTrack( topic, data );
		trackCallbacks.fire( mw.trackQueue );
	};

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
	mw.trackSubscribe = function ( topic, callback ) {
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
	};

	/**
	 * Stop handling events for a particular handler
	 *
	 * @param {Function} callback
	 */
	mw.trackUnsubscribe = function ( callback ) {
		trackHandlers = trackHandlers.filter( function ( fns ) {
			if ( fns[ 1 ] === callback ) {
				trackCallbacks.remove( fns[ 0 ] );
				// Ensure the tuple is removed to avoid holding on to closures
				return false;
			}
			return true;
		} );
	};

	// Fire events from before track() triggered fire()
	trackCallbacks.fire( mw.trackQueue );

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
	mw.hook = ( function () {
		var lists = Object.create( null );

		/**
		 * Create an instance of mw.hook.
		 *
		 * @method hook
		 * @member mw
		 * @param {string} name Name of hook.
		 * @return {mw.hook}
		 */
		return function ( name ) {
			var list = lists[ name ] || ( lists[ name ] = $.Callbacks( 'memory' ) );

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
				 * @return {mw.hook}
				 * @chainable
				 */
				fire: function () {
					return list.fireWith.call( this, null, slice.call( arguments ) );
				}
			};
		};
	}() );

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
	mw.html = ( function () {
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
			 *    See <https://www.w3.org/TR/html401/appendix/notes.html#h-B.3.2>.
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
								throw new Error( 'Illegal end tag found in CDATA' );
							}
							s += contents.value;
						} else {
							throw new Error( 'Invalid type of contents' );
						}
				}
				s += '</' + name + '>';
				return s;
			},

			/**
			 * Wrapper object for raw HTML passed to mw.html.element().
			 *
			 * @class mw.html.Raw
			 * @constructor
			 * @param {string} value
			 */
			Raw: function ( value ) {
				this.value = value;
			},

			/**
			 * Wrapper object for CDATA element contents passed to mw.html.element()
			 *
			 * @class mw.html.Cdata
			 * @constructor
			 * @param {string} value
			 */
			Cdata: function ( value ) {
				this.value = value;
			}
		};
	}() );

	/**
	 * Execute a function as soon as one or more required modules are ready.
	 *
	 * Example of inline dependency on OOjs:
	 *
	 *     mw.loader.using( 'oojs', function () {
	 *         OO.compare( [ 1 ], [ 1 ] );
	 *     } );
	 *
	 * Example of inline dependency obtained via `require()`:
	 *
	 *     mw.loader.using( [ 'mediawiki.util' ], function ( require ) {
	 *         var util = require( 'mediawiki.util' );
	 *     } );
	 *
	 * Since MediaWiki 1.23 this also returns a promise.
	 *
	 * Since MediaWiki 1.28 the promise is resolved with a `require` function.
	 *
	 * @member mw.loader
	 * @param {string|Array} dependencies Module name or array of modules names the
	 *  callback depends on to be ready before executing
	 * @param {Function} [ready] Callback to execute when all dependencies are ready
	 * @param {Function} [error] Callback to execute if one or more dependencies failed
	 * @return {jQuery.Promise} With a `require` function
	 */
	mw.loader.using = function ( dependencies, ready, error ) {
		var deferred = $.Deferred();

		// Allow calling with a single dependency as a string
		if ( !Array.isArray( dependencies ) ) {
			dependencies = [ dependencies ];
		}

		if ( ready ) {
			deferred.done( ready );
		}
		if ( error ) {
			deferred.fail( error );
		}

		try {
			// Resolve entire dependency map
			dependencies = mw.loader.resolve( dependencies );
		} catch ( e ) {
			return deferred.reject( e ).promise();
		}

		mw.loader.enqueue(
			dependencies,
			function () { deferred.resolve( mw.loader.require ); },
			deferred.reject
		);

		return deferred.promise();
	};

	/**
	 * Load a script by URL.
	 *
	 * Example:
	 *
	 *     mw.loader.getScript(
	 *         'https://example.org/x-1.0.0.js'
	 *     )
	 *         .then( function () {
	 *             // Script succeeded. You can use X now.
	 *         }, function ( e ) {
	 *             // Script failed. X is not avaiable
	 *             mw.log.error( e.message ); // => "Failed to load script"
	 *         } );
	 *     } );
	 *
	 * @member mw.loader
	 * @param {string} url Script URL
	 * @return {jQuery.Promise} Resolved when the script is loaded
	 */
	mw.loader.getScript = function ( url ) {
		return $.ajax( url, { dataType: 'script', cache: true } )
			.catch( function () {
				throw new Error( 'Failed to load script' );
			} );
	};

	// Skeleton user object, extended by the 'mediawiki.user' module.
	/**
	 * @class mw.user
	 * @singleton
	 */
	mw.user = {
		/**
		 * @property {mw.Map}
		 */
		options: new mw.Map(),
		/**
		 * @property {mw.Map}
		 */
		tokens: new mw.Map()
	};

	// Alias $j to jQuery for backwards compatibility
	// @deprecated since 1.23 Use $ or jQuery instead
	mw.log.deprecate( window, '$j', $, 'Use $ or jQuery instead.' );

	// Process callbacks for Grade A that require modules.
	queue = window.RLQ;
	// Replace temporary RLQ implementation from startup.js with the
	// final implementation that also processes callbacks that can
	// require modules. It must also support late arrivals of
	// plain callbacks. (T208093)
	window.RLQ = {
		push: function ( entry ) {
			if ( typeof entry === 'function' ) {
				entry();
			} else {
				mw.loader.using( entry[ 0 ], entry[ 1 ] );
			}
		}
	};
	while ( queue[ 0 ] ) {
		window.RLQ.push( queue.shift() );
	}
}() );
