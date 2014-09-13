/**
 * Base library for MediaWiki.
 *
 * Exposed as globally as `mediaWiki` with `mw` as shortcut.
 *
 * @class mw
 * @alternateClassName mediaWiki
 * @singleton
 */

var mw = ( function ( $, undefined ) {
	'use strict';

	/* Private Members */

	var hasOwn = Object.prototype.hasOwnProperty,
		slice = Array.prototype.slice,
		trackCallbacks = $.Callbacks( 'memory' ),
		trackQueue = [];

	/**
	 * Log a message to window.console, if possible. Useful to force logging of some
	 * errors that are otherwise hard to detect (I.e., this logs also in production mode).
	 * Gets console references in each invocation, so that delayed debugging tools work
	 * fine. No need for optimization here, which would only result in losing logs.
	 *
	 * @private
	 * @method log_
	 * @param {string} msg text for the log entry.
	 * @param {Error} [e]
	 */
	function log( msg, e ) {
		var console = window.console;
		if ( console && console.log ) {
			console.log( msg );
			// If we have an exception object, log it through .error() to trigger
			// proper stacktraces in browsers that support it. There are no (known)
			// browsers that don't support .error(), that do support .log() and
			// have useful exception handling through .log().
			if ( e && console.error ) {
				console.error( String( e ), e );
			}
		}
	}

	/* Object constructors */

	/**
	 * Creates an object that can be read from or written to from prototype functions
	 * that allow both single and multiple variables at once.
	 *
	 *     @example
	 *
	 *     var addies, wanted, results;
	 *
	 *     // Create your address book
	 *     addies = new mw.Map();
	 *
	 *     // This data could be coming from an external source (eg. API/AJAX)
	 *     addies.set( {
	 *         'John Doe' : '10 Wall Street, New York, USA',
	 *         'Jane Jackson' : '21 Oxford St, London, UK',
	 *         'Dominique van Halen' : 'Kalverstraat 7, Amsterdam, NL'
	 *     } );
	 *
	 *     wanted = ['Dominique van Halen', 'George Johnson', 'Jane Jackson'];
	 *
	 *     // You can detect missing keys first
	 *     if ( !addies.exists( wanted ) ) {
	 *         // One or more are missing (in this case: "George Johnson")
	 *         mw.log( 'One or more names were not found in your address book' );
	 *     }
	 *
	 *     // Or just let it give you what it can
	 *     results = addies.get( wanted, 'Middle of Nowhere, Alaska, US' );
	 *     mw.log( results['Jane Jackson'] ); // "21 Oxford St, London, UK"
	 *     mw.log( results['George Johnson'] ); // "Middle of Nowhere, Alaska, US"
	 *
	 * @class mw.Map
	 *
	 * @constructor
	 * @param {Object|boolean} [values] Value-bearing object to map, or boolean
	 *  true to map over the global object. Defaults to an empty object.
	 */
	function Map( values ) {
		this.values = values === true ? window : ( values || {} );
		return this;
	}

	Map.prototype = {
		/**
		 * Get the value of one or multiple a keys.
		 *
		 * If called with no arguments, all values will be returned.
		 *
		 * @param {string|Array} selection String key or array of keys to get values for.
		 * @param {Mixed} [fallback] Value to use in case key(s) do not exist.
		 * @return mixed If selection was a string returns the value or null,
		 *  If selection was an array, returns an object of key/values (value is null if not found),
		 *  If selection was not passed or invalid, will return the 'values' object member (be careful as
		 *  objects are always passed by reference in JavaScript!).
		 * @return {string|Object|null} Values as a string or object, null if invalid/inexistant.
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
					results[selection[i]] = this.get( selection[i], fallback );
				}
				return results;
			}

			if ( typeof selection === 'string' ) {
				if ( !hasOwn.call( this.values, selection ) ) {
					return fallback;
				}
				return this.values[selection];
			}

			if ( selection === undefined ) {
				return this.values;
			}

			// invalid selection key
			return null;
		},

		/**
		 * Sets one or multiple key/value pairs.
		 *
		 * @param {string|Object} selection String key to set value for, or object mapping keys to values.
		 * @param {Mixed} [value] Value to set (optional, only in use when key is a string)
		 * @return {Boolean} This returns true on success, false on failure.
		 */
		set: function ( selection, value ) {
			var s;

			if ( $.isPlainObject( selection ) ) {
				for ( s in selection ) {
					this.values[s] = selection[s];
				}
				return true;
			}
			if ( typeof selection === 'string' && arguments.length > 1 ) {
				this.values[selection] = value;
				return true;
			}
			return false;
		},

		/**
		 * Checks if one or multiple keys exist.
		 *
		 * @param {Mixed} selection String key or array of keys to check
		 * @return {boolean} Existence of key(s)
		 */
		exists: function ( selection ) {
			var s;

			if ( $.isArray( selection ) ) {
				for ( s = 0; s < selection.length; s++ ) {
					if ( typeof selection[s] !== 'string' || !hasOwn.call( this.values, selection[s] ) ) {
						return false;
					}
				}
				return true;
			}
			return typeof selection === 'string' && hasOwn.call( this.values, selection );
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
	 * @param {mw.Map} map Message storage
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
		 * Simple message parser, does $N replacement and nothing else.
		 *
		 * This may be overridden to provide a more complex message parser.
		 *
		 * The primary override is in mediawiki.jqueryMsg.
		 *
		 * This function will not be called for nonexistent messages.
		 */
		parser: function () {
			var parameters = this.parameters;
			return this.map.get( this.key ).replace( /\$(\d+)/g, function ( str, match ) {
				var index = parseInt( match, 10 ) - 1;
				return parameters[index] !== undefined ? parameters[index] : '$' + match;
			} );
		},

		/**
		 * Appends (does not replace) parameters for replacement to the .parameters property.
		 *
		 * @param {Array} parameters
		 * @chainable
		 */
		params: function ( parameters ) {
			var i;
			for ( i = 0; i < parameters.length; i += 1 ) {
				this.parameters.push( parameters[i] );
			}
			return this;
		},

		/**
		 * Converts message object to its string form based on the state of format.
		 *
		 * @return {string} Message as a string in the current form or `<key>` if key does not exist.
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
		 * Changes format to 'parse' and converts message to string
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
		 * Changes format to 'plain' and converts message to string
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
		 * Changes format to 'text' and converts message to string
		 *
		 * If jqueryMsg is loaded, {{-transformation is done where supported
		 * (such as {{plural:}}, {{gender:}}, {{int:}}).
		 *
		 * Otherwise, it is equivalent to plain.
		 */
		text: function () {
			this.format = 'text';
			return this.toString();
		},

		/**
		 * Changes the format to 'escaped' and converts message to string
		 *
		 * This is equivalent to using the 'text' format (see text method), then
		 * HTML-escaping the output.
		 *
		 * @return {string} String form of html escaped message
		 */
		escaped: function () {
			this.format = 'escaped';
			return this.toString();
		},

		/**
		 * Checks if message exists
		 *
		 * @see mw.Map#exists
		 * @return {boolean}
		 */
		exists: function () {
			return this.map.exists( this.key );
		}
	};

	/**
	 * @class mw
	 */
	return {
		/* Public Members */

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
		 * Register a handler for subset of analytic events, specified by topic
		 *
		 * Handlers will be called once for each tracked event, including any events that fired before the
		 * handler was registered; 'this' is set to a plain object with a 'timeStamp' property indicating
		 * the exact time at which the event fired, a string 'topic' property naming the event, and a
		 * 'data' property which is an object of event-specific data. The event topic and event data are
		 * also passed to the callback as the first and second arguments, respectively.
		 *
		 * @param {string} topic Handle events whose name starts with this string prefix
		 * @param {Function} callback Handler to call for each matching tracked event
		 */
		trackSubscribe: function ( topic, callback ) {
			var seen = 0;

			trackCallbacks.add( function ( trackQueue ) {
				var event;
				for ( ; seen < trackQueue.length; seen++ ) {
					event = trackQueue[ seen ];
					if ( event.topic.indexOf( topic ) === 0 ) {
						callback.call( event, event.topic, event.data );
					}
				}
			} );
		},

		// Make the Map constructor publicly available.
		Map: Map,

		// Make the Message constructor publicly available.
		Message: Message,

		/**
		 * Map of configuration values
		 *
		 * Check out [the complete list of configuration values](https://www.mediawiki.org/wiki/Manual:Interface/JavaScript#mw.config)
		 * on mediawiki.org.
		 *
		 * If `$wgLegacyJavaScriptGlobals` is true, this Map will add its values to the
		 * global `window` object.
		 *
		 * @property {mw.Map} config
		 */
		// Dummy placeholder. Re-assigned in ResourceLoaderStartupModule to an instance of `mw.Map`.
		config: null,

		/**
		 * Empty object that plugins can be installed in.
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
		 * @deprecated since 1.22: Let deprecated identifiers keep their original name
		 *  and use mw.log#deprecate to create an access container for tracking.
		 * @property
		 */
		legacy: {},

		/**
		 * Localization system
		 * @property {mw.Map}
		 */
		messages: new Map(),

		/* Public Methods */

		/**
		 * Get a message object.
		 *
		 * Shorcut for `new mw.Message( mw.messages, key, parameters )`.
		 *
		 * @see mw.Message
		 * @param {string} key Key of message to get
		 * @param {Mixed...} parameters Parameters for the $N replacements in messages.
		 * @return {mw.Message}
		 */
		message: function ( key ) {
			// Variadic arguments
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
		 * @param {Mixed...} parameters Parameters for the $N replacements in messages.
		 * @return {string}
		 */
		msg: function () {
			return mw.message.apply( mw.message, arguments ).toString();
		},

		/**
		 * Dummy placeholder for {@link mw.log}
		 * @method
		 */
		log: ( function () {
			// Also update the restoration of methods in mediawiki.log.js
			// when adding or removing methods here.
			var log = function () {};

			/**
			 * @class mw.log
			 * @singleton
			 */

			/**
			 * Write a message the console's warning channel.
			 * Also logs a stacktrace for easier debugging.
			 * Each action is silently ignored if the browser doesn't support it.
			 *
			 * @param {string...} msg Messages to output to console
			 */
			log.warn = function () {
				var console = window.console;
				if ( console && console.warn && console.warn.apply ) {
					console.warn.apply( console, arguments );
					if ( console.trace ) {
						console.trace();
					}
				}
			};

			/**
			 * Create a property in a host object that, when accessed, will produce
			 * a deprecation warning in the console with backtrace.
			 *
			 * @param {Object} obj Host object of deprecated property
			 * @param {string} key Name of property to create in `obj`
			 * @param {Mixed} val The value this property should return when accessed
			 * @param {string} [msg] Optional text to include in the deprecation message.
			 */
			log.deprecate = !Object.defineProperty ? function ( obj, key, val ) {
				obj[key] = val;
			} : function ( obj, key, val, msg ) {
				msg = 'Use of "' + key + '" is deprecated.' + ( msg ? ( ' ' + msg ) : '' );
				try {
					Object.defineProperty( obj, key, {
						configurable: true,
						enumerable: true,
						get: function () {
							mw.track( 'mw.deprecate', key );
							mw.log.warn( msg );
							return val;
						},
						set: function ( newVal ) {
							mw.track( 'mw.deprecate', key );
							mw.log.warn( msg );
							val = newVal;
						}
					} );
				} catch ( err ) {
					// IE8 can throw on Object.defineProperty
					obj[key] = val;
				}
			};

			return log;
		}() ),

		/**
		 * Client-side module loader which integrates with the MediaWiki ResourceLoader
		 * @class mw.loader
		 * @singleton
		 */
		loader: ( function () {

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
			 *     {
			 *         'moduleName': {
			 *             'version': ############## (unix timestamp),
			 *             'dependencies': ['required.foo', 'bar.also', ...], (or) function () {}
			 *             'group': 'somegroup', (or) null,
			 *             'source': 'local', 'someforeignwiki', (or) null
			 *             'state': 'registered', 'loaded', 'loading', 'ready', 'error' or 'missing'
			 *             'script': ...,
			 *             'style': ...,
			 *             'messages': { 'key': 'value' },
			 *         }
			 *     }
			 *
			 * @property
			 * @private
			 */
			var registry = {},
				//
				// Mapping of sources, keyed by source-id, values are objects.
				// Format:
				//	{
				//		'sourceId': {
				//			'loadScript': 'http://foo.bar/w/load.php'
				//		}
				//	}
				//
				sources = {},
				// List of modules which will be loaded as when ready
				batch = [],
				// List of modules to be loaded
				queue = [],
				// List of callback functions waiting for modules to be ready to be called
				jobs = [],
				// Selector cache for the marker element. Use getMarker() to get/use the marker!
				$marker = null,
				// Buffer for addEmbeddedCSS.
				cssBuffer = '',
				// Callbacks for addEmbeddedCSS.
				cssCallbacks = $.Callbacks();

			/* Private methods */

			function getMarker() {
				// Cached ?
				if ( $marker ) {
					return $marker;
				}

				$marker = $( 'meta[name="ResourceLoaderDynamicStyles"]' );
				if ( $marker.length ) {
					return $marker;
				}
				mw.log( 'getMarker> No <meta name="ResourceLoaderDynamicStyles"> found, inserting dynamically.' );
				$marker = $( '<meta>' ).attr( 'name', 'ResourceLoaderDynamicStyles' ).appendTo( 'head' );

				return $marker;
			}

			/**
			 * Create a new style tag and add it to the DOM.
			 *
			 * @private
			 * @param {string} text CSS text
			 * @param {HTMLElement|jQuery} [nextnode=document.head] The element where the style tag should be
			 *  inserted before. Otherwise it will be appended to `<head>`.
			 * @return {HTMLElement} Reference to the created `<style>` element.
			 */
			function newStyleTag( text, nextnode ) {
				var s = document.createElement( 'style' );
				// Insert into document before setting cssText (bug 33305)
				if ( nextnode ) {
					// Must be inserted with native insertBefore, not $.fn.before.
					// When using jQuery to insert it, like $nextnode.before( s ),
					// then IE6 will throw "Access is denied" when trying to append
					// to .cssText later. Some kind of weird security measure.
					// http://stackoverflow.com/q/12586482/319266
					// Works: jsfiddle.net/zJzMy/1
					// Fails: jsfiddle.net/uJTQz
					// Works again: http://jsfiddle.net/Azr4w/ (diff: the next 3 lines)
					if ( nextnode.jquery ) {
						nextnode = nextnode.get( 0 );
					}
					nextnode.parentNode.insertBefore( s, nextnode );
				} else {
					document.getElementsByTagName( 'head' )[0].appendChild( s );
				}
				if ( s.styleSheet ) {
					// IE
					s.styleSheet.cssText = text;
				} else {
					// Other browsers.
					// (Safari sometimes borks on non-string values,
					// play safe by casting to a string, just in case.)
					s.appendChild( document.createTextNode( String( text ) ) );
				}
				return s;
			}

			/**
			 * Checks whether it is safe to add this css to a stylesheet.
			 *
			 * @private
			 * @param {string} cssText
			 * @return {boolean} False if a new one must be created.
			 */
			function canExpandStylesheetWith( cssText ) {
				// Makes sure that cssText containing `@import`
				// rules will end up in a new stylesheet (as those only work when
				// placed at the start of a stylesheet; bug 35562).
				return cssText.indexOf( '@import' ) === -1;
			}

			/**
			 * Add a bit of CSS text to the current browser page.
			 *
			 * The CSS will be appended to an existing ResourceLoader-created `<style>` tag
			 * or create a new one based on whether the given `cssText` is safe for extension.
			 *
			 * @param {string} [cssText=cssBuffer] If called without cssText,
			 *  the internal buffer will be inserted instead.
			 * @param {Function} [callback]
			 */
			function addEmbeddedCSS( cssText, callback ) {
				var $style, styleEl;

				if ( callback ) {
					cssCallbacks.add( callback );
				}

				// Yield once before inserting the <style> tag. There are likely
				// more calls coming up which we can combine this way.
				// Appending a stylesheet and waiting for the browser to repaint
				// is fairly expensive, this reduces it (bug 45810)
				if ( cssText ) {
					// Be careful not to extend the buffer with css that needs a new stylesheet
					if ( !cssBuffer || canExpandStylesheetWith( cssText ) ) {
						// Linebreak for somewhat distinguishable sections
						// (the rl-cachekey comment separating each)
						cssBuffer += '\n' + cssText;
						// TODO: Use requestAnimationFrame in the future which will
						// perform even better by not injecting styles while the browser
						// is paiting.
						setTimeout( function () {
							// Can't pass addEmbeddedCSS to setTimeout directly because Firefox
							// (below version 13) has the non-standard behaviour of passing a
							// numerical "lateness" value as first argument to this callback
							// http://benalman.com/news/2009/07/the-mysterious-firefox-settime/
							addEmbeddedCSS();
						} );
						return;
					}

				// This is a delayed call and we got a buffer still
				} else if ( cssBuffer ) {
					cssText = cssBuffer;
					cssBuffer = '';
				} else {
					// This is a delayed call, but buffer is already cleared by
					// another delayed call.
					return;
				}

				// By default, always create a new <style>. Appending text to a <style>
				// tag is bad as it means the contents have to be re-parsed (bug 45810).
				//
				// Except, of course, in IE 9 and below. In there we default to re-using and
				// appending to a <style> tag due to the IE stylesheet limit (bug 31676).
				if ( 'documentMode' in document && document.documentMode <= 9 ) {

					$style = getMarker().prev();
					// Verify that the the element before Marker actually is a
					// <style> tag and one that came from ResourceLoader
					// (not some other style tag or even a `<meta>` or `<script>`).
					if ( $style.data( 'ResourceLoaderDynamicStyleTag' ) === true ) {
						// There's already a dynamic <style> tag present and
						// canExpandStylesheetWith() gave a green light to append more to it.
						styleEl = $style.get( 0 );
						if ( styleEl.styleSheet ) {
							try {
								styleEl.styleSheet.cssText += cssText; // IE
							} catch ( e ) {
								log( 'addEmbeddedCSS fail', e );
							}
						} else {
							styleEl.appendChild( document.createTextNode( String( cssText ) ) );
						}
						cssCallbacks.fire().empty();
						return;
					}
				}

				$( newStyleTag( cssText, getMarker() ) ).data( 'ResourceLoaderDynamicStyleTag', true );

				cssCallbacks.fire().empty();
			}

			/**
			 * Generates an ISO8601 "basic" string from a UNIX timestamp
			 * @private
			 */
			function formatVersionNumber( timestamp ) {
				var	d = new Date();
				function pad( a, b, c ) {
					return [a < 10 ? '0' + a : a, b < 10 ? '0' + b : b, c < 10 ? '0' + c : c].join( '' );
				}
				d.setTime( timestamp * 1000 );
				return [
					pad( d.getUTCFullYear(), d.getUTCMonth() + 1, d.getUTCDate() ), 'T',
					pad( d.getUTCHours(), d.getUTCMinutes(), d.getUTCSeconds() ), 'Z'
				].join( '' );
			}

			/**
			 * Resolves dependencies and detects circular references.
			 *
			 * @private
			 * @param {string} module Name of the top-level module whose dependencies shall be
			 *   resolved and sorted.
			 * @param {Array} resolved Returns a topological sort of the given module and its
			 *   dependencies, such that later modules depend on earlier modules. The array
			 *   contains the module names. If the array contains already some module names,
			 *   this function appends its result to the pre-existing array.
			 * @param {Object} [unresolved] Hash used to track the current dependency
			 *   chain; used to report loops in the dependency graph.
			 * @throws {Error} If any unregistered module or a dependency loop is encountered
			 */
			function sortDependencies( module, resolved, unresolved ) {
				var n, deps, len;

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
				if ( $.inArray( module, resolved ) !== -1 ) {
					// Module already resolved; nothing to do.
					return;
				}
				// unresolved is optional, supply it if not passed in
				if ( !unresolved ) {
					unresolved = {};
				}
				// Tracks down dependencies
				deps = registry[module].dependencies;
				len = deps.length;
				for ( n = 0; n < len; n += 1 ) {
					if ( $.inArray( deps[n], resolved ) === -1 ) {
						if ( unresolved[deps[n]] ) {
							throw new Error(
								'Circular reference detected: ' + module +
								' -> ' + deps[n]
							);
						}

						// Add to unresolved
						unresolved[module] = true;
						sortDependencies( deps[n], resolved, unresolved );
						delete unresolved[module];
					}
				}
				resolved[resolved.length] = module;
			}

			/**
			 * Gets a list of module names that a module depends on in their proper dependency
			 * order.
			 *
			 * @private
			 * @param {string} module Module name or array of string module names
			 * @return {Array} list of dependencies, including 'module'.
			 * @throws {Error} If circular reference is detected
			 */
			function resolve( module ) {
				var m, resolved;

				// Allow calling with an array of module names
				if ( $.isArray( module ) ) {
					resolved = [];
					for ( m = 0; m < module.length; m += 1 ) {
						sortDependencies( module[m], resolved );
					}
					return resolved;
				}

				if ( typeof module === 'string' ) {
					resolved = [];
					sortDependencies( module, resolved );
					return resolved;
				}

				throw new Error( 'Invalid module argument: ' + module );
			}

			/**
			 * Narrows a list of module names down to those matching a specific
			 * state (see comment on top of this scope for a list of valid states).
			 * One can also filter for 'unregistered', which will return the
			 * modules names that don't have a registry entry.
			 *
			 * @private
			 * @param {string|string[]} states Module states to filter by
			 * @param {Array} [modules] List of module names to filter (optional, by default the entire
			 * registry is used)
			 * @return {Array} List of filtered module names
			 */
			function filter( states, modules ) {
				var list, module, s, m;

				// Allow states to be given as a string
				if ( typeof states === 'string' ) {
					states = [states];
				}
				// If called without a list of modules, build and use a list of all modules
				list = [];
				if ( modules === undefined ) {
					modules = [];
					for ( module in registry ) {
						modules[modules.length] = module;
					}
				}
				// Build a list of modules which are in one of the specified states
				for ( s = 0; s < states.length; s += 1 ) {
					for ( m = 0; m < modules.length; m += 1 ) {
						if ( registry[modules[m]] === undefined ) {
							// Module does not exist
							if ( states[s] === 'unregistered' ) {
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
			 * Determine whether all dependencies are in state 'ready', which means we may
			 * execute the module or job now.
			 *
			 * @private
			 * @param {Array} dependencies Dependencies (module names) to be checked.
			 * @return {boolean} True if all dependencies are in state 'ready', false otherwise
			 */
			function allReady( dependencies ) {
				return filter( 'ready', dependencies ).length === dependencies.length;
			}

			/**
			 * A module has entered state 'ready', 'error', or 'missing'. Automatically update pending jobs
			 * and modules that depend upon this module. if the given module failed, propagate the 'error'
			 * state up the dependency tree; otherwise, execute all jobs/modules that now have all their
			 * dependencies satisfied. On jobs depending on a failed module, run the error callback, if any.
			 *
			 * @private
			 * @param {string} module Name of module that entered one of the states 'ready', 'error', or 'missing'.
			 */
			function handlePending( module ) {
				var j, job, hasErrors, m, stateChange;

				// Modules.
				if ( $.inArray( registry[module].state, ['error', 'missing'] ) !== -1 ) {
					// If the current module failed, mark all dependent modules also as failed.
					// Iterate until steady-state to propagate the error state upwards in the
					// dependency tree.
					do {
						stateChange = false;
						for ( m in registry ) {
							if ( $.inArray( registry[m].state, ['error', 'missing'] ) === -1 ) {
								if ( filter( ['error', 'missing'], registry[m].dependencies ).length > 0 ) {
									registry[m].state = 'error';
									stateChange = true;
								}
							}
						}
					} while ( stateChange );
				}

				// Execute all jobs whose dependencies are either all satisfied or contain at least one failed module.
				for ( j = 0; j < jobs.length; j += 1 ) {
					hasErrors = filter( ['error', 'missing'], jobs[j].dependencies ).length > 0;
					if ( hasErrors || allReady( jobs[j].dependencies ) ) {
						// All dependencies satisfied, or some have errors
						job = jobs[j];
						jobs.splice( j, 1 );
						j -= 1;
						try {
							if ( hasErrors ) {
								if ( $.isFunction( job.error ) ) {
									job.error( new Error( 'Module ' + module + ' has failed dependencies' ), [module] );
								}
							} else {
								if ( $.isFunction( job.ready ) ) {
									job.ready();
								}
							}
						} catch ( e ) {
							// A user-defined callback raised an exception.
							// Swallow it to protect our state machine!
							log( 'Exception thrown by job.error', e );
						}
					}
				}

				if ( registry[module].state === 'ready' ) {
					// The current module became 'ready'. Set it in the module store, and recursively execute all
					// dependent modules that are loaded and now have all dependencies satisfied.
					mw.loader.store.set( module, registry[module] );
					for ( m in registry ) {
						if ( registry[m].state === 'loaded' && allReady( registry[m].dependencies ) ) {
							execute( m );
						}
					}
				}
			}

			/**
			 * Adds a script tag to the DOM, either using document.write or low-level DOM manipulation,
			 * depending on whether document-ready has occurred yet and whether we are in async mode.
			 *
			 * @private
			 * @param {string} src URL to script, will be used as the src attribute in the script tag
			 * @param {Function} [callback] Callback which will be run when the script is done
			 * @param {boolean} [async=false] Whether to load modules asynchronously.
			 *  Ignored (and defaulted to `true`) if the document-ready event has already occurred.
			 */
			function addScript( src, callback, async ) {
				/*jshint evil:true */
				var script, head, done;

				// Using isReady directly instead of storing it locally from
				// a $.fn.ready callback (bug 31895).
				if ( $.isReady || async ) {
					// Can't use jQuery.getScript because that only uses <script> for cross-domain,
					// it uses XHR and eval for same-domain scripts, which we don't want because it
					// messes up line numbers.
					// The below is based on jQuery ([jquery@1.9.1]/src/ajax/script.js)

					// IE-safe way of getting an append target. In old IE document.head isn't supported
					// and its getElementsByTagName can't find <head> until </head> is parsed.
					done = false;
					head = document.head || document.getElementsByTagName( 'head' )[0] || document.documentElement;

					script = document.createElement( 'script' );
					script.async = true;
					script.src = src;
					if ( $.isFunction( callback ) ) {
						script.onload = script.onreadystatechange = function () {
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

								// Detach the element from the document
								if ( script.parentNode ) {
									script.parentNode.removeChild( script );
								}

								// Dereference the element from javascript
								script = undefined;

								callback();
							}
						};
					}

					if ( window.opera ) {
						// Appending to the <head> blocks rendering completely in Opera,
						// so append to the <body> after document ready. This means the
						// scripts only start loading after the document has been rendered,
						// but so be it. Opera users don't deserve faster web pages if their
						// browser makes it impossible.
						$( function () {
							document.body.appendChild( script );
						} );
					} else {
						// Circumvent IE6 bugs with base elements (jqbug.com/2709, jqbug.com/4378)
						// by prepending instead of appending.
						head.insertBefore( script, head.firstChild );
					}
				} else {
					document.write( mw.html.element( 'script', { 'src': src }, '' ) );
					if ( $.isFunction( callback ) ) {
						// Document.write is synchronous, so this is called when it's done
						// FIXME: that's a lie. doc.write isn't actually synchronous
						callback();
					}
				}
			}

			/**
			 * Executes a loaded module, making it ready to use
			 *
			 * @private
			 * @param {string} module Module name to execute
			 */
			function execute( module ) {
				var key, value, media, i, urls, cssHandle, checkCssHandles,
					cssHandlesRegistered = false;

				if ( registry[module] === undefined ) {
					throw new Error( 'Module has not been registered yet: ' + module );
				} else if ( registry[module].state === 'registered' ) {
					throw new Error( 'Module has not been requested from the server yet: ' + module );
				} else if ( registry[module].state === 'loading' ) {
					throw new Error( 'Module has not completed loading yet: ' + module );
				} else if ( registry[module].state === 'ready' ) {
					throw new Error( 'Module has already been executed: ' + module );
				}

				/**
				 * Define loop-function here for efficiency
				 * and to avoid re-using badly scoped variables.
				 * @ignore
				 */
				function addLink( media, url ) {
					var el = document.createElement( 'link' );
					// For IE: Insert in document *before* setting href
					getMarker().before( el );
					el.rel = 'stylesheet';
					if ( media && media !== 'all' ) {
						el.media = media;
					}
					// If you end up here from an IE exception "SCRIPT: Invalid property value.",
					// see #addEmbeddedCSS, bug 31676, and bug 47277 for details.
					el.href = url;
				}

				function runScript() {
					var script, markModuleReady, nestedAddScript;
					try {
						script = registry[module].script;
						markModuleReady = function () {
							registry[module].state = 'ready';
							handlePending( module );
						};
						nestedAddScript = function ( arr, callback, async, i ) {
							// Recursively call addScript() in its own callback
							// for each element of arr.
							if ( i >= arr.length ) {
								// We're at the end of the array
								callback();
								return;
							}

							addScript( arr[i], function () {
								nestedAddScript( arr, callback, async, i + 1 );
							}, async );
						};

						if ( $.isArray( script ) ) {
							nestedAddScript( script, markModuleReady, registry[module].async, 0 );
						} else if ( $.isFunction( script ) ) {
							registry[module].state = 'ready';
							// Pass jQuery twice so that the signature of the closure which wraps
							// the script can bind both '$' and 'jQuery'.
							script( $, $ );
							handlePending( module );
						}
					} catch ( e ) {
						// This needs to NOT use mw.log because these errors are common in production mode
						// and not in debug mode, such as when a symbol that should be global isn't exported
						log( 'Exception thrown by ' + module, e );
						registry[module].state = 'error';
						handlePending( module );
					}
				}

				// This used to be inside runScript, but since that is now fired asychronously
				// (after CSS is loaded) we need to set it here right away. It is crucial that
				// when execute() is called this is set synchronously, otherwise modules will get
				// executed multiple times as the registry will state that it isn't loading yet.
				registry[module].state = 'loading';

				// Add localizations to message system
				if ( $.isPlainObject( registry[module].messages ) ) {
					mw.messages.set( registry[module].messages );
				}

				if ( $.isReady || registry[module].async ) {
					// Make sure we don't run the scripts until all (potentially asynchronous)
					// stylesheet insertions have completed.
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
								if (check) {
									pending--;
									check();
									check = undefined; // Revoke
								}
							};
						};
					}() );
				} else {
					// We are in blocking mode, and so we can't afford to wait for CSS
					cssHandle = function () {};
					// Run immediately
					checkCssHandles = runScript;
				}

				// Process styles (see also mw.loader.implement)
				// * back-compat: { <media>: css }
				// * back-compat: { <media>: [url, ..] }
				// * { "css": [css, ..] }
				// * { "url": { <media>: [url, ..] } }
				if ( $.isPlainObject( registry[module].style ) ) {
					for ( key in registry[module].style ) {
						value = registry[module].style[key];
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
							for ( i = 0; i < value.length; i += 1 ) {
								if ( key === 'bc-url' ) {
									// back-compat: { <media>: [url, ..] }
									addLink( media, value[i] );
								} else if ( key === 'css' ) {
									// { "css": [css, ..] }
									addEmbeddedCSS( value[i], cssHandle() );
								}
							}
						// Not an array, but a regular object
						// Array of urls inside media-type key
						} else if ( typeof value === 'object' ) {
							// { "url": { <media>: [url, ..] } }
							for ( media in value ) {
								urls = value[media];
								for ( i = 0; i < urls.length; i += 1 ) {
									addLink( media, urls[i] );
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
			 * Adds a dependencies to the queue with optional callbacks to be run
			 * when the dependencies are ready or fail
			 *
			 * @private
			 * @param {string|string[]} dependencies Module name or array of string module names
			 * @param {Function} [ready] Callback to execute when all dependencies are ready
			 * @param {Function} [error] Callback to execute when any dependency fails
			 * @param {boolean} [async=false] Whether to load modules asynchronously.
			 *  Ignored (and defaulted to `true`) if the document-ready event has already occurred.
			 */
			function request( dependencies, ready, error, async ) {
				var n;

				// Allow calling by single module name
				if ( typeof dependencies === 'string' ) {
					dependencies = [dependencies];
				}

				// Add ready and error callbacks if they were given
				if ( ready !== undefined || error !== undefined ) {
					jobs[jobs.length] = {
						'dependencies': filter(
							['registered', 'loading', 'loaded'],
							dependencies
						),
						'ready': ready,
						'error': error
					};
				}

				// Queue up any dependencies that are registered
				dependencies = filter( ['registered'], dependencies );
				for ( n = 0; n < dependencies.length; n += 1 ) {
					if ( $.inArray( dependencies[n], queue ) === -1 ) {
						queue[queue.length] = dependencies[n];
						if ( async ) {
							// Mark this module as async in the registry
							registry[dependencies[n]].async = true;
						}
					}
				}

				// Work the queue
				mw.loader.work();
			}

			function sortQuery( o ) {
				var sorted = {}, key, a = [];
				for ( key in o ) {
					if ( hasOwn.call( o, key ) ) {
						a.push( key );
					}
				}
				a.sort();
				for ( key = 0; key < a.length; key += 1 ) {
					sorted[a[key]] = o[a[key]];
				}
				return sorted;
			}

			/**
			 * Converts a module map of the form { foo: [ 'bar', 'baz' ], bar: [ 'baz, 'quux' ] }
			 * to a query string of the form foo.bar,baz|bar.baz,quux
			 * @private
			 */
			function buildModulesString( moduleMap ) {
				var arr = [], p, prefix;
				for ( prefix in moduleMap ) {
					p = prefix === '' ? '' : prefix + '.';
					arr.push( p + moduleMap[prefix].join( ',' ) );
				}
				return arr.join( '|' );
			}

			/**
			 * Asynchronously append a script tag to the end of the body
			 * that invokes load.php
			 * @private
			 * @param {Object} moduleMap Module map, see #buildModulesString
			 * @param {Object} currReqBase Object with other parameters (other than 'modules') to use in the request
			 * @param {string} sourceLoadScript URL of load.php
			 * @param {boolean} async Whether to load modules asynchronously.
			 *  Ignored (and defaulted to `true`) if the document-ready event has already occurred.
			 */
			function doRequest( moduleMap, currReqBase, sourceLoadScript, async ) {
				var request = $.extend(
					{ modules: buildModulesString( moduleMap ) },
					currReqBase
				);
				request = sortQuery( request );
				// Append &* to avoid triggering the IE6 extension check
				addScript( sourceLoadScript + '?' + $.param( request ) + '&*', null, async );
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
				 * Batch-request queued dependencies from the server.
				 */
				work: function () {
					var	reqBase, splits, maxQueryLength, q, b, bSource, bGroup, bSourceGroup,
						source, concatSource, origBatch, group, g, i, modules, maxVersion, sourceLoadScript,
						currReqBase, currReqBaseLength, moduleMap, l,
						lastDotIndex, prefix, suffix, bytesAdded, async;

					// Build a list of request parameters common to all requests.
					reqBase = {
						skin: mw.config.get( 'skin' ),
						lang: mw.config.get( 'wgUserLanguage' ),
						debug: mw.config.get( 'debug' )
					};
					// Split module batch by source and by group.
					splits = {};
					maxQueryLength = mw.config.get( 'wgResourceLoaderMaxQueryLength', -1 );

					// Appends a list of modules from the queue to the batch
					for ( q = 0; q < queue.length; q += 1 ) {
						// Only request modules which are registered
						if ( registry[queue[q]] !== undefined && registry[queue[q]].state === 'registered' ) {
							// Prevent duplicate entries
							if ( $.inArray( queue[q], batch ) === -1 ) {
								batch[batch.length] = queue[q];
								// Mark registered modules as loading
								registry[queue[q]].state = 'loading';
							}
						}
					}

					mw.loader.store.init();
					if ( mw.loader.store.enabled ) {
						concatSource = [];
						origBatch = batch;
						batch = $.grep( batch, function ( module ) {
							var source = mw.loader.store.get( module );
							if ( source ) {
								concatSource.push( source );
								return false;
							}
							return true;
						} );
						try {
							$.globalEval( concatSource.join( ';' ) );
						} catch ( err ) {
							// Not good, the cached mw.loader.implement calls failed! This should
							// never happen, barring ResourceLoader bugs, browser bugs and PEBKACs.
							// Depending on how corrupt the string is, it is likely that some
							// modules' implement() succeeded while the ones after the error will
							// never run and leave their modules in the 'loading' state forever.

							// Since this is an error not caused by an individual module but by
							// something that infected the implement call itself, don't take any
							// risks and clear everything in this cache.
							mw.loader.store.clear();
							// Re-add the ones still pending back to the batch and let the server
							// repopulate these modules to the cache.
							// This means that at most one module will be useless (the one that had
							// the error) instead of all of them.
							log( 'Error while evaluating data from mw.loader.store', err );
							origBatch = $.grep( origBatch, function ( module ) {
								return registry[module].state === 'loading';
							} );
							batch = batch.concat( origBatch );
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
					for ( b = 0; b < batch.length; b += 1 ) {
						bSource = registry[batch[b]].source;
						bGroup = registry[batch[b]].group;
						if ( splits[bSource] === undefined ) {
							splits[bSource] = {};
						}
						if ( splits[bSource][bGroup] === undefined ) {
							splits[bSource][bGroup] = [];
						}
						bSourceGroup = splits[bSource][bGroup];
						bSourceGroup[bSourceGroup.length] = batch[b];
					}

					// Clear the batch - this MUST happen before we append any
					// script elements to the body or it's possible that a script
					// will be locally cached, instantly load, and work the batch
					// again, all before we've cleared it causing each request to
					// include modules which are already loaded.
					batch = [];

					for ( source in splits ) {

						sourceLoadScript = sources[source].loadScript;

						for ( group in splits[source] ) {

							// Cache access to currently selected list of
							// modules for this group from this source.
							modules = splits[source][group];

							// Calculate the highest timestamp
							maxVersion = 0;
							for ( g = 0; g < modules.length; g += 1 ) {
								if ( registry[modules[g]].version > maxVersion ) {
									maxVersion = registry[modules[g]].version;
								}
							}

							currReqBase = $.extend( { version: formatVersionNumber( maxVersion ) }, reqBase );
							// For user modules append a user name to the request.
							if ( group === 'user' && mw.config.get( 'wgUserName' ) !== null ) {
								currReqBase.user = mw.config.get( 'wgUserName' );
							}
							currReqBaseLength = $.param( currReqBase ).length;
							async = true;
							// We may need to split up the request to honor the query string length limit,
							// so build it piece by piece.
							l = currReqBaseLength + 9; // '&modules='.length == 9

							moduleMap = {}; // { prefix: [ suffixes ] }

							for ( i = 0; i < modules.length; i += 1 ) {
								// Determine how many bytes this module would add to the query string
								lastDotIndex = modules[i].lastIndexOf( '.' );
								// Note that these substr() calls work even if lastDotIndex == -1
								prefix = modules[i].substr( 0, lastDotIndex );
								suffix = modules[i].substr( lastDotIndex + 1 );
								bytesAdded = moduleMap[prefix] !== undefined
									? suffix.length + 3 // '%2C'.length == 3
									: modules[i].length + 3; // '%7C'.length == 3

								// If the request would become too long, create a new one,
								// but don't create empty requests
								if ( maxQueryLength > 0 && !$.isEmptyObject( moduleMap ) && l + bytesAdded > maxQueryLength ) {
									// This request would become too long, create a new one
									// and fire off the old one
									doRequest( moduleMap, currReqBase, sourceLoadScript, async );
									moduleMap = {};
									async = true;
									l = currReqBaseLength + 9;
								}
								if ( moduleMap[prefix] === undefined ) {
									moduleMap[prefix] = [];
								}
								moduleMap[prefix].push( suffix );
								if ( !registry[modules[i]].async ) {
									// If this module is blocking, make the entire request blocking
									// This is slightly suboptimal, but in practice mixing of blocking
									// and async modules will only occur in debug mode.
									async = false;
								}
								l += bytesAdded;
							}
							// If there's anything left in moduleMap, request that too
							if ( !$.isEmptyObject( moduleMap ) ) {
								doRequest( moduleMap, currReqBase, sourceLoadScript, async );
							}
						}
					}
				},

				/**
				 * Register a source.
				 *
				 * The #work method will use this information to split up requests by source.
				 *
				 *     mw.loader.addSource( 'mediawikiwiki', { loadScript: '//www.mediawiki.org/w/load.php' } );
				 *
				 * @param {string} id Short string representing a source wiki, used internally for
				 *  registered modules to indicate where they should be loaded from (usually lowercase a-z).
				 * @param {Object} props
				 * @param {string} props.loadScript Url to the load.php entry point of the source wiki.
				 * @return {boolean}
				 */
				addSource: function ( id, props ) {
					var source;
					// Allow multiple additions
					if ( typeof id === 'object' ) {
						for ( source in id ) {
							mw.loader.addSource( source, id[source] );
						}
						return true;
					}

					if ( sources[id] !== undefined ) {
						throw new Error( 'source already registered: ' + id );
					}

					sources[id] = props;

					return true;
				},

				/**
				 * Register a module, letting the system know about it and its
				 * properties. Startup modules contain calls to this function.
				 *
				 * @param {string} module Module name
				 * @param {number} version Module version number as a timestamp (falls backs to 0)
				 * @param {string|Array|Function} dependencies One string or array of strings of module
				 *  names on which this module depends, or a function that returns that array.
				 * @param {string} [group=null] Group which the module is in
				 * @param {string} [source='local'] Name of the source
				 */
				register: function ( module, version, dependencies, group, source ) {
					var m;
					// Allow multiple registration
					if ( typeof module === 'object' ) {
						for ( m = 0; m < module.length; m += 1 ) {
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
						throw new Error( 'module already registered: ' + module );
					}
					// List the module as registered
					registry[module] = {
						version: version !== undefined ? parseInt( version, 10 ) : 0,
						dependencies: [],
						group: typeof group === 'string' ? group : null,
						source: typeof source === 'string' ? source: 'local',
						state: 'registered'
					};
					if ( typeof dependencies === 'string' ) {
						// Allow dependencies to be given as a single module name
						registry[module].dependencies = [ dependencies ];
					} else if ( typeof dependencies === 'object' || $.isFunction( dependencies ) ) {
						// Allow dependencies to be given as an array of module names
						// or a function which returns an array
						registry[module].dependencies = dependencies;
					}
				},

				/**
				 * Implement a module given the components that make up the module.
				 *
				 * When #load or #using requests one or more modules, the server
				 * response contain calls to this function.
				 *
				 * All arguments are required.
				 *
				 * @param {string} module Name of module
				 * @param {Function|Array} script Function with module code or Array of URLs to
				 *  be used as the src attribute of a new `<script>` tag.
				 * @param {Object} style Should follow one of the following patterns:
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
				 * whether it's safe to extend the stylesheet (see #canExpandStylesheetWith).
				 *
				 * @param {Object} msgs List of key/value pairs to be added to mw#messages.
				 */
				implement: function ( module, script, style, msgs ) {
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
						throw new Error( 'module already implemented: ' + module );
					}
					// Attach components
					registry[module].script = script;
					registry[module].style = style;
					registry[module].messages = msgs;
					// The module may already have been marked as erroneous
					if ( $.inArray( registry[module].state, ['error', 'missing'] ) === -1 ) {
						registry[module].state = 'loaded';
						if ( allReady( registry[module].dependencies ) ) {
							execute( module );
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
				 * @param {string|Array} dependencies Module name or array of modules names the callback
				 *  dependends on to be ready before executing
				 * @param {Function} [ready] Callback to execute when all dependencies are ready
				 * @param {Function} [error] Callback to execute if one or more dependencies failed
				 * @return {jQuery.Promise}
				 */
				using: function ( dependencies, ready, error ) {
					var deferred = $.Deferred();

					// Allow calling with a single dependency as a string
					if ( typeof dependencies === 'string' ) {
						dependencies = [ dependencies ];
					} else if ( !$.isArray( dependencies ) ) {
						// Invalid input
						throw new Error( 'Dependencies must be a string or an array' );
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
						deferred.resolve();
					} else if ( filter( ['error', 'missing'], dependencies ).length ) {
						// Execute error immediately if any dependencies have errors
						deferred.reject(
							new Error( 'One or more dependencies failed to load' ),
							dependencies
						);
					} else {
						// Not all dependencies are ready: queue up a request
						request( dependencies, deferred.resolve, deferred.reject );
					}

					return deferred.promise();
				},

				/**
				 * Load an external script or one or more modules.
				 *
				 * @param {string|Array} modules Either the name of a module, array of modules,
				 *  or a URL of an external script or style
				 * @param {string} [type='text/javascript'] mime-type to use if calling with a URL of an
				 *  external script or style; acceptable values are "text/css" and
				 *  "text/javascript"; if no type is provided, text/javascript is assumed.
				 * @param {boolean} [async] Whether to load modules asynchronously.
				 *  Ignored (and defaulted to `true`) if the document-ready event has already occurred.
				 *  Defaults to `true` if loading a URL, `false` otherwise.
				 */
				load: function ( modules, type, async ) {
					var filtered, m, module, l;

					// Validate input
					if ( typeof modules !== 'object' && typeof modules !== 'string' ) {
						throw new Error( 'modules must be a string or an array, not a ' + typeof modules );
					}
					// Allow calling with an external url or single dependency as a string
					if ( typeof modules === 'string' ) {
						// Support adding arbitrary external scripts
						if ( /^(https?:)?\/\//.test( modules ) ) {
							if ( async === undefined ) {
								// Assume async for bug 34542
								async = true;
							}
							if ( type === 'text/css' ) {
								// IE7-8 throws security warnings when inserting a <link> tag
								// with a protocol-relative URL set though attributes (instead of
								// properties) - when on HTTPS. See also bug 41331.
								l = document.createElement( 'link' );
								l.rel = 'stylesheet';
								l.href = modules;
								$( 'head' ).append( l );
								return;
							}
							if ( type === 'text/javascript' || type === undefined ) {
								addScript( modules, null, async );
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
					for ( filtered = [], m = 0; m < modules.length; m += 1 ) {
						module = registry[modules[m]];
						if ( module !== undefined ) {
							if ( $.inArray( module.state, ['error', 'missing'] ) === -1 ) {
								filtered[filtered.length] = modules[m];
							}
						}
					}

					if ( filtered.length === 0 ) {
						return;
					}
					// Resolve entire dependency map
					filtered = resolve( filtered );
					// If all modules are ready, nothing to be done
					if ( allReady( filtered ) ) {
						return;
					}
					// If any modules have errors: also quit.
					if ( filter( ['error', 'missing'], filtered ).length ) {
						return;
					}
					// Since some modules are not yet ready, queue up a request.
					request( filtered, undefined, undefined, async );
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
							mw.loader.state( m, module[m] );
						}
						return;
					}
					if ( registry[module] === undefined ) {
						mw.loader.register( module );
					}
					if ( $.inArray( state, ['ready', 'error', 'missing'] ) !== -1
						&& registry[module].state !== state ) {
						// Make sure pending modules depending on this one get executed if their
						// dependencies are now fulfilled!
						registry[module].state = state;
						handlePending( module );
					} else {
						registry[module].state = state;
					}
				},

				/**
				 * Get the version of a module.
				 *
				 * @param {string} module Name of module to get version for
				 * @return {string|null} The version, or null if the module (or its version) is not
				 *  in the registry.
				 */
				getVersion: function ( module ) {
					if ( registry[module] !== undefined && registry[module].version !== undefined ) {
						return formatVersionNumber( registry[module].version );
					}
					return null;
				},

				/**
				 * Get the state of a module.
				 *
				 * @param {string} module Name of module to get state for
				 */
				getState: function ( module ) {
					if ( registry[module] !== undefined && registry[module].state !== undefined ) {
						return registry[module].state;
					}
					return null;
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

					// The contents of the store, mapping '[module name]@[version]' keys
					// to module implementations.
					items: {},

					// Cache hit stats
					stats: { hits: 0, misses: 0, expired: 0 },

					/**
					 * Construct a JSON-serializable object representing the content of the store.
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
					 * Get a string key on which to vary the module cache.
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
					 * Get a string key for a specific module. The key format is '[name]@[version]'.
					 *
					 * @param {string} module Module name
					 * @return {string|null} Module key or null if module does not exist
					 */
					getModuleKey: function ( module ) {
						return typeof registry[module] === 'object' ?
							( module + '@' + registry[module].version ) : null;
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

						if ( !mw.config.get( 'wgResourceLoaderStorageEnabled' ) || mw.config.get( 'debug' ) ) {
							// Disabled by configuration, or because debug mode is set
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
						} catch ( e ) {}

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

						key = mw.loader.store.getModuleKey( module );
						if ( key in mw.loader.store.items ) {
							mw.loader.store.stats.hits++;
							return mw.loader.store.items[key];
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
						var args, key;

						if ( !mw.loader.store.enabled ) {
							return false;
						}

						key = mw.loader.store.getModuleKey( module );

						if (
							// Already stored a copy of this exact version
							key in mw.loader.store.items ||
							// Module failed to load
							descriptor.state !== 'ready' ||
							// Unversioned, private, or site-/user-specific
							( !descriptor.version || $.inArray( descriptor.group, [ 'private', 'user', 'site' ] ) !== -1 ) ||
							// Partial descriptor
							$.inArray( undefined, [ descriptor.script, descriptor.style, descriptor.messages ] ) !== -1
						) {
							// Decline to store
							return false;
						}

						try {
							args = [
								JSON.stringify( module ),
								typeof descriptor.script === 'function' ?
									String( descriptor.script ) :
									JSON.stringify( descriptor.script ),
								JSON.stringify( descriptor.style ),
								JSON.stringify( descriptor.messages )
							];
							// Attempted workaround for a possible Opera bug (bug 57567).
							// This regex should never match under sane conditions.
							if ( /^\s*\(/.test( args[1] ) ) {
								args[1] = 'function' + args[1];
								log( 'Detected malformed function stringification (bug 57567)' );
							}
						} catch ( e ) {
							return;
						}

						mw.loader.store.items[key] = 'mw.loader.implement(' + args.join( ',' ) + ');';
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
							module = key.substring( 0, key.indexOf( '@' ) );
							if ( mw.loader.store.getModuleKey( module ) !== key ) {
								mw.loader.store.stats.expired++;
								delete mw.loader.store.items[key];
							}
						}
					},

					/**
					 * Clear the entire module store right now.
					 */
					clear: function () {
						mw.loader.store.items = {};
						localStorage.removeItem( mw.loader.store.getStoreKey() );
					},

					/**
					 * Sync modules to localStorage.
					 *
					 * This function debounces localStorage updates. When called multiple times in
					 * quick succession, the calls are coalesced into a single update operation.
					 * This allows us to call #update without having to consider the module load
					 * queue; the call to localStorage.setItem will be naturally deferred until the
					 * page is quiescent.
					 *
					 * Because localStorage is shared by all pages with the same origin, if multiple
					 * pages are loaded with different module sets, the possibility exists that
					 * modules saved by one page will be clobbered by another. But the impact would
					 * be minor and the problem would be corrected by subsequent page views.
					 *
					 * @method
					 */
					update: ( function () {
						var timer;

						function flush() {
							var data,
								key = mw.loader.store.getStoreKey();

							if ( !mw.loader.store.enabled ) {
								return false;
							}
							mw.loader.store.prune();
							try {
								// Replacing the content of the module store might fail if the new
								// contents would exceed the browser's localStorage size limit. To
								// avoid clogging the browser with stale data, always remove the old
								// value before attempting to set the new one.
								localStorage.removeItem( key );
								data = JSON.stringify( mw.loader.store );
								localStorage.setItem( key, data );
							} catch ( e ) {}
						}

						return function () {
							clearTimeout( timer );
							timer = setTimeout( flush, 2000 );
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
				 * @param {Object} attrs An object with members mapping element names to values
				 * @param {Mixed} contents The contents of the element. May be either:
				 *
				 *  - string: The string is escaped.
				 *  - null or undefined: The short closing form is used, e.g. `<br/>`.
				 *  - this.Raw: The value attribute is included without escaping.
				 *  - this.Cdata: The value attribute is included, and an exception is
				 *   thrown if it contains an illegal ETAGO delimiter.
				 *   See <http://www.w3.org/TR/1999/REC-html401-19991224/appendix/notes.html#h-B.3.2>.
				 * @return {string} HTML
				 */
				element: function ( name, attrs, contents ) {
					var v, attrName, s = '<' + name;

					for ( attrName in attrs ) {
						v = attrs[attrName];
						// Convert name=true, to name=name
						if ( v === true ) {
							v = attrName;
						// Skip name=false
						} else if ( v === false ) {
							continue;
						}
						s += ' ' + attrName + '="' + this.escape( String( v ) ) + '"';
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
				 * @class mw.html.Raw
				 */
				Raw: function ( value ) {
					this.value = value;
				},

				/**
				 * Wrapper object for CDATA element contents passed to mw.html.element()
				 * @class mw.html.Cdata
				 */
				Cdata: function ( value ) {
					this.value = value;
				}
			};
		}() ),

		// Skeleton user object. mediawiki.user.js extends this
		user: {
			options: new Map(),
			tokens: new Map()
		},

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
					lists[name] :
					lists[name] = $.Callbacks( 'memory' );

				return {
					/**
					 * Register a hook handler
					 * @param {Function...} handler Function to bind.
					 * @chainable
					 */
					add: list.add,

					/**
					 * Unregister a hook handler
					 * @param {Function...} handler Function to unbind.
					 * @chainable
					 */
					remove: list.remove,

					/**
					 * Run a hook.
					 * @param {Mixed...} data
					 * @chainable
					 */
					fire: function () {
						return list.fireWith.call( this, null, slice.call( arguments ) );
					}
				};
			};
		}() )
	};

}( jQuery ) );

// Alias $j to jQuery for backwards compatibility
// @deprecated since 1.23 Use $ or jQuery instead
mw.log.deprecate( window, '$j', jQuery, 'Use $ or jQuery instead.' );

// Attach to window and globally alias
window.mw = window.mediaWiki = mw;

// Auto-register from pre-loaded startup scripts
if ( jQuery.isFunction( window.startUp ) ) {
	window.startUp();
	window.startUp = undefined;
}
