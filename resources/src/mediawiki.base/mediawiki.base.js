'use strict';

var slice = Array.prototype.slice;

// Apply site-level data
mw.config.set( require( './config.json' ) );

// Load other files in the package
require( './log.js' );
require( './errorLogger.js' );
require( './legacy.wikibits.js' );

/**
 * Object constructor for messages.
 *
 * Similar to the Message class in MediaWiki PHP.
 *
 *     @example
 *
 *     var obj, str;
 *     mw.messages.set( {
 *         'hello': 'Hello world',
 *         'hello-user': 'Hello, $1!',
 *         'welcome-user': 'Welcome back to $2, $1! Last visit by $1: $3',
 *         'so-unusual': 'You will find: $1'
 *     } );
 *
 *     obj = mw.message( 'hello' );
 *     mw.log( obj.text() );
 *     // Hello world
 *
 *     obj = mw.message( 'hello-user', 'John Doe' );
 *     mw.log( obj.text() );
 *     // Hello, John Doe!
 *
 *     obj = mw.message( 'welcome-user', 'John Doe', 'Wikipedia', '2 hours ago' );
 *     mw.log( obj.text() );
 *     // Welcome back to Wikipedia, John Doe! Last visit by John Doe: 2 hours ago
 *
 *     // Using mw.msg shortcut, always in "text' format.
 *     str = mw.msg( 'hello-user', 'John Doe' );
 *     mw.log( str );
 *     // Hello, John Doe!
 *
 *     // Different formats
 *     obj = mw.message( 'so-unusual', 'Time "after" <time>' );
 *
 *     mw.log( obj.text() );
 *     // You will find: Time "after" <time>
 *
 *     mw.log( obj.escaped() );
 *     // You will find: Time &quot;after&quot; &lt;time&gt;
 *
 * @class mw.Message
 *
 * @constructor
 * @param {mw.Map} map Message store
 * @param {string} key
 * @param {Array} [parameters]
 */
function Message( map, key, parameters ) {
	this.map = map;
	this.key = key;
	this.parameters = parameters || [];
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
	 * @private For internal use by mediawiki.jqueryMsg only
	 * @param {string} format
	 * @return {string} Parsed message
	 */
	parser: function ( format ) {
		var text = this.map.get( this.key );
		if (
			mw.config.get( 'wgUserLanguage' ) === 'qqx' &&
			text === '(' + this.key + ')'
		) {
			text = '(' + this.key + '$*)';
		}
		text = mw.format.apply( null, [ text ].concat( this.parameters ) );
		if ( format === 'parse' ) {
			// We don't know how to parse anything, so escape it all
			text = mw.html.escape( text );
		}
		return text;
	},

	/**
	 * Add (does not replace) parameters for `$N` placeholder values.
	 *
	 * @param {Array} parameters
	 * @return {mw.Message}
	 * @chainable
	 */
	params: function ( parameters ) {
		// Optimization: push all parameter arguments at once. Can't use spread operator
		// `this.parameters.push( ...parameters );` yet, but apply() does the same thing.
		Array.prototype.push.apply( this.parameters, parameters );
		return this;
	},

	/**
	 * Convert message object to a string using the "text"-format .
	 *
	 * This exists for implicit string type casting only.
	 * Do not call this directly. Use mw.Message#text() instead, one of the
	 * other format methods.
	 *
	 * @private
	 * @param {string} [format="text"] Internal parameter. Uses "text" if called
	 *  implicitly through string casting.
	 * @return {string} Message in the given format, or `⧼key⧽` if the key
	 *  does not exist.
	 */
	toString: function ( format ) {
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

		if ( !format ) {
			format = 'text';
		}

		if ( format === 'plain' || format === 'text' || format === 'parse' ) {
			return this.parser( format );
		}

		// Format: 'escaped' (including for any invalid format, default to safe escape)
		return mw.html.escape( this.parser( 'escaped' ) );
	},

	/**
	 * Parse message as wikitext and return HTML.
	 *
	 * If jqueryMsg is loaded, this transforms text and parses a subset of supported wikitext
	 * into HTML. Without jqueryMsg, it is equivalent to #escaped.
	 *
	 * @return {string} String form of parsed message
	 */
	parse: function () {
		return this.toString( 'parse' );
	},

	/**
	 * Return message plainly.
	 *
	 * This substitutes parameters, but otherwise does not transform the
	 * message content.
	 *
	 * @return {string} String form of plain message
	 */
	plain: function () {
		return this.toString( 'plain' );
	},

	/**
	 * Format message with text transformations applied.
	 *
	 * If jqueryMsg is loaded, `{{`-transformation is done for supported
	 * magic words such as `{{plural:}}`, `{{gender:}}`, and `{{int:}}`.
	 * Without jqueryMsg, it is equivalent to #plain.
	 *
	 * @return {string} String form of text message
	 */
	text: function () {
		return this.toString( 'text' );
	},

	/**
	 * Format message and return as escaped text in HTML.
	 *
	 * This is equivalent to the #text format, which is then HTML-escaped.
	 *
	 * @return {string} String form of html escaped message
	 */
	escaped: function () {
		return this.toString( 'escaped' );
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

/**
 * @class mw
 * @singleton
 */

/**
 * Empty object for third-party libraries, for cases where you don't
 * want to add a new global, or the global is bad and needs containment
 * or wrapping.
 *
 * @property {Object}
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
 * Replace `$*` with a list of parameters for `uselang=qqx` support.
 *
 * @private
 * @since 1.33
 * @param {string} formatString Format string
 * @param {Array} parameters Values for $N replacements
 * @return {string} Transformed format string
 */
mw.internalDoTransformFormatForQqx = function ( formatString, parameters ) {
	if ( formatString.indexOf( '$*' ) !== -1 ) {
		var replacement = '';
		if ( parameters.length ) {
			replacement = ': ' + parameters.map( function ( _, i ) {
				return '$' + ( i + 1 );
			} ).join( ', ' );
		}
		return formatString.replace( '$*', replacement );
	}
	return formatString;
};

/**
 * Encode page titles in a way that matches `wfUrlencode` in PHP.
 *
 * @see mw.util#wikiUrlencode
 * @private
 * @param {string} str
 * @return {string}
 */
mw.internalWikiUrlencode = function ( str ) {
	return encodeURIComponent( String( str ) )
		.replace( /'/g, '%27' )
		.replace( /%20/g, '_' )
		.replace( /%3B/g, ';' )
		.replace( /%40/g, '@' )
		.replace( /%24/g, '$' )
		.replace( /%2C/g, ',' )
		.replace( /%2F/g, '/' )
		.replace( /%3A/g, ':' );
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
	// Shortcut must process text transformations by default
	// if mediawiki.jqueryMsg is loaded. (T46459)
	return mw.message.apply( mw, arguments ).text();
};

/**
 * @see mw.notification#notify
 * @param {HTMLElement|HTMLElement[]|jQuery|mw.Message|string} message
 * @param {Object} [options] See mw.notification#defaults for the defaults.
 * @return {jQuery.Promise}
 */
mw.notify = function ( message, options ) {
	// Lazy load
	return mw.loader.using( 'mediawiki.notification', function () {
		return mw.notification.notify( message, options );
	} );
};

var mwLoaderTrack = mw.track;
var trackCallbacks = $.Callbacks( 'memory' );
var trackHandlers = [];

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
 * events that match their subscription, including buffered events that fired before the handler
 * was subscribed.
 *
 * @param {string} topic Topic name
 * @param {Object|number|string} [data] Data describing the event.
 */
mw.track = function ( topic, data ) {
	mwLoaderTrack( topic, data );
	trackCallbacks.fire( mw.trackQueue );
};

/**
 * Register a handler for subset of analytic events, specified by topic.
 *
 * Handlers will be called once for each tracked event, including for any buffered events that
 * fired before the handler was subscribed. The callback is passed a `topic` string, and optional
 * `data` event object. The `this` value for the callback is a plain object with `topic` and
 * `data` properties set to those same values.
 *
 * Example to monitor all topics for debugging:
 *
 *     mw.trackSubscribe( '', console.log );
 *
 * Example to subscribe to any of `foo.*`, e.g. both `foo.bar` and `foo.quux`:
 *
 *     mw.trackSubscribe( 'foo.', console.log );
 *
 * @param {string} topic Handle events whose name starts with this string prefix
 * @param {Function} callback Handler to call for each matching tracked event
 * @param {string} callback.topic
 * @param {Object} [callback.data]
 */
mw.trackSubscribe = function ( topic, callback ) {
	var seen = 0;
	function handler( trackQueue ) {
		for ( ; seen < trackQueue.length; seen++ ) {
			var event = trackQueue[ seen ];
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

var hooks = Object.create( null );

/**
 * Create an instance of mw.hook.
 *
 * @method hook
 * @member mw
 * @param {string} name Name of hook.
 * @return {mw.hook}
 */
mw.hook = function ( name ) {
	return hooks[ name ] || ( hooks[ name ] = ( function () {
		var memory;
		var fns = [];
		function rethrow( e ) {
			setTimeout( function () {
				throw e;
			} );
		}
		return {
			/**
			 * Register a hook handler
			 *
			 * @param {...Function} handler Function to bind.
			 * @chainable
			 */
			add: function () {
				for ( var i = 0; i < arguments.length; i++ ) {
					if ( memory ) {
						try {
							arguments[ i ].apply( null, memory );
						} catch ( e ) {
							rethrow( e );
						}
					}
					fns.push( arguments[ i ] );
				}
				return this;
			},
			/**
			 * Unregister a hook handler
			 *
			 * @param {...Function} handler Function to unbind.
			 * @chainable
			 */
			remove: function () {
				for ( var i = 0; i < arguments.length; i++ ) {
					var j;
					while ( ( j = fns.indexOf( arguments[ i ] ) ) !== -1 ) {
						fns.splice( j, 1 );
					}
				}
				return this;
			},
			/**
			 * Call hook handlers with data.
			 *
			 * @param {...Mixed} data
			 * @return {mw.hook}
			 * @chainable
			 */
			fire: function () {
				for ( var i = 0; i < fns.length; i++ ) {
					try {
						fns[ i ].apply( null, arguments );
					} catch ( e ) {
						rethrow( e );
					}
				}
				memory = slice.call( arguments );
				return this;
			}
		};
	}() ) );
};

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
mw.html = {
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
	 * @param {string|mw.html.Raw|null} [contents=null] The contents of the element.
	 *
	 *  - string: Text to be escaped.
	 *  - null: The element is treated as void with short closing form, e.g. `<br/>`.
	 *  - this.Raw: The raw value is directly included.
	 * @return {string} HTML
	 */
	element: function ( name, attrs, contents ) {
		var s = '<' + name;

		if ( attrs ) {
			for ( var attrName in attrs ) {
				var v = attrs[ attrName ];
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
		if ( typeof contents === 'string' ) {
			// Escaped
			s += this.escape( contents );
		} else if ( typeof contents === 'number' || typeof contents === 'boolean' ) {
			// Convert to string
			s += String( contents );
		} else if ( contents instanceof this.Raw ) {
			// Raw HTML inclusion
			s += contents.value;
		} else {
			throw new Error( 'Invalid content type' );
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
	}
};

/**
 * Import a local JS content page, for use by user scripts and site-wide scripts.
 *
 * @since 1.12
 * @param {string} title
 */
window.importScript = function ( title ) {
	mw.loader.load(
		mw.config.get( 'wgScript' ) + '?title=' + mw.internalWikiUrlencode( title ) +
			'&action=raw&ctype=text/javascript'
	);
};

/**
 * Import a local CSS content page, for use by user scripts and site-wide scripts.
 *
 * @since 1.12
 * @param {string} title
 */
window.importStylesheet = function ( title ) {
	mw.loader.load(
		mw.config.get( 'wgScript' ) + '?title=' + mw.internalWikiUrlencode( title ) +
			'&action=raw&ctype=text/css',
		'text/css'
	);
};

/**
 * Get the names of all registered ResourceLoader modules.
 *
 * @member mw.loader
 * @return {string[]}
 */
mw.loader.getModuleNames = function () {
	return Object.keys( mw.loader.moduleRegistry );
};

/**
 * Execute a function after one or more modules are ready.
 *
 * Use this method if you need to dynamically control which modules are loaded
 * and/or when they loaded (instead of declaring them as dependencies directly
 * on your module.)
 *
 * This uses the same loader as for regular module dependencies. This means
 * ResourceLoader will not re-download or re-execute a module for the second
 * time if something else already needed it. And the same browser HTTP cache,
 * and localStorage are checked before considering to fetch from the network.
 * And any on-going requests from other dependencies or using() calls are also
 * automatically re-used.
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
 * Since MediaWiki 1.23 this returns a promise.
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

mw.user.options.set( require( './user.json' ) );

// Process callbacks for modern browsers (Grade A) that require modules.
var queue = window.RLQ;
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
