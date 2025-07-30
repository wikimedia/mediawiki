'use strict';

const slice = Array.prototype.slice;

// Apply site-level data
mw.config.set( require( './config.json' ) );

require( './log.js' );

/**
 * @class mw.Message
 * @classdesc Describes a translateable text or HTML string. Similar to the Message class in MediaWiki PHP.
 *
 * @example
 * var obj, str;
 * mw.messages.set( {
 *     'hello': 'Hello world',
 *     'hello-user': 'Hello, $1!',
 *     'welcome-user': 'Welcome back to $2, $1! Last visit by $1: $3',
 *     'so-unusual': 'You will find: $1'
 * } );
 *
 * obj = mw.message( 'hello' );
 * mw.log( obj.text() );
 * // Hello world
 *
 * obj = mw.message( 'hello-user', 'John Doe' );
 * mw.log( obj.text() );
 * // Hello, John Doe!
 *
 * obj = mw.message( 'welcome-user', 'John Doe', 'Wikipedia', '2 hours ago' );
 * mw.log( obj.text() );
 * // Welcome back to Wikipedia, John Doe! Last visit by John Doe: 2 hours ago
 *
 * // Using mw.msg shortcut, always in "text' format.
 * str = mw.msg( 'hello-user', 'John Doe' );
 * mw.log( str );
 * // Hello, John Doe!
 *
 * // Different formats
 * obj = mw.message( 'so-unusual', 'Time "after" <time>' );
 *
 * mw.log( obj.text() );
 * // You will find: Time "after" <time>
 *
 * mw.log( obj.escaped() );
 * // You will find: Time &quot;after&quot; &lt;time&gt;
 *
 * @constructor
 * @description Object constructor for messages. The constructor is not publicly accessible;
 * use {@link mw.message} instead.
 * @param {mw.Map} map Message store
 * @param {string} key
 * @param {Array} [parameters]
 */
function Message( map, key, parameters ) {
	this.map = map;
	this.key = key;
	this.parameters = parameters || [];
}

Message.prototype = /** @lends mw.Message.prototype */ {
	/**
	 * Get parsed contents of the message.
	 *
	 * The default parser does simple $N replacements and nothing else.
	 * This may be overridden to provide a more complex message parser.
	 * The primary override is in the mediawiki.jqueryMsg module.
	 *
	 * This function will not be called for nonexistent messages.
	 * For internal use by mediawiki.jqueryMsg only
	 *
	 * @private
	 * @param {string} format
	 * @return {string} Parsed message
	 */
	parser: function ( format ) {
		let text = this.map.get( this.key );

		// Apply qqx formatting.
		//
		// - Keep this synchronised with LanguageQqx/MessageCache in PHP.
		// - Keep this synchronised with mw.jqueryMsg.Parser#getAst.
		//
		// Unlike LanguageQqx in PHP, this doesn't replace unconditionally.
		// It replaces non-existent messages, and messages that were exported by
		// load.php as "(key)" in qqx formatting. Some extensions export other data
		// via their message blob (T222944).
		if (
			mw.config.get( 'wgUserLanguage' ) === 'qqx' &&
			( !text || text === '(' + this.key + ')' )
		) {
			text = '(' + this.key + '$*)';
		}
		text = mw.format( text, ...this.parameters );
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
		this.parameters.push( ...parameters );
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
			// Make sure qqx works for non-existent messages, see parser() above.
			if ( mw.config.get( 'wgUserLanguage' ) !== 'qqx' ) {
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
	 * into HTML. Without jqueryMsg, it is equivalent to {@link mw.Message#escaped}.
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
	 * Without jqueryMsg, it is equivalent to {@link mw.Message#plain}.
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
	 * Check if a message exists. Equivalent to {@link mw.Map.exists}.
	 *
	 * @return {boolean}
	 */
	exists: function () {
		return this.map.exists( this.key );
	}
};

/**
 * @class mw
 * @singleton
 * @borrows mediawiki.inspect.runReports as inspect
 */

/**
 * Empty object for third-party libraries, for cases where you don't
 * want to add a new global, or the global is bad and needs containment
 * or wrapping.
 *
 * @type {Object}
 */
mw.libs = {};

/**
 * OOUI widgets specific to MediaWiki.
 * Initially empty. To expand the amount of available widgets the `mediawiki.widget` module can be loaded.
 *
 * @namespace mw.widgets
 * @example
 * mw.loader.using('mediawiki.widget').then(() => {
 *   OO.ui.getWindowManager().addWindows( [ new mw.widget.AbandonEditDialog() ] );
 * });
 */
mw.widgets = {};

/**
 * Generates a ResourceLoader report using the
 * {@link mediawiki.inspect.js.html|mediawiki.inspect module}.
 *
 * @ignore
 */
mw.inspect = function ( ...reports ) {
	// Lazy-load
	mw.loader.using( 'mediawiki.inspect', () => {
		mw.inspect.runReports( ...reports );
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
	if ( formatString.includes( '$*' ) ) {
		let replacement = '';
		if ( parameters.length ) {
			replacement = ': ' + parameters.map( ( _, i ) => '$' + ( i + 1 ) ).join( ', ' );
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
 * Used by {@link mw.Message#parse}.
 *
 * @memberof mw
 * @since 1.25
 * @param {string} formatString Format string
 * @param {...Mixed} parameters Values for $N replacements
 * @return {string} Formatted string
 */
mw.format = function ( formatString, ...parameters ) {
	formatString = mw.internalDoTransformFormatForQqx( formatString, parameters );
	return formatString.replace( /\$(\d+)/g, ( str, match ) => {
		const index = parseInt( match, 10 ) - 1;
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
 * @memberof mw
 * @see {@link mw.Message}
 * @param {string} key Key of message to get
 * @param {...Mixed} parameters Values for $N replacements
 * @return {mw.Message}
 */
mw.message = function ( key ) {
	const parameters = slice.call( arguments, 1 );
	return new Message( mw.messages, key, parameters );
};

/**
 * Get a message string using the (default) 'text' format.
 *
 * Shortcut for `mw.message( key, parameters... ).text()`.
 *
 * @memberof mw
 * @see {@link mw.Message}
 * @param {string} key Key of message to get
 * @param {...any} parameters Values for $N replacements
 * @return {string}
 */
mw.msg = function ( key, ...parameters ) {
	// Shortcut must process text transformations by default
	// if mediawiki.jqueryMsg is loaded. (T46459)
	// eslint-disable-next-line mediawiki/msg-doc
	return mw.message( key, ...parameters ).text();
};

/**
 * Convenience method for loading and accessing the
 * {@link mw.notification.notify|mw.notification module}.
 *
 * @memberof mw
 * @param {HTMLElement|HTMLElement[]|jQuery|mw.Message|string} message
 * @param {Object} [options] See mw.notification#defaults for the defaults.
 * @return {jQuery.Promise}
 */
mw.notify = function ( message, options ) {
	// Lazy load
	return mw.loader.using( 'mediawiki.notification' ).then( () => mw.notification.notify( message, options ) );
};

const trackCallbacks = $.Callbacks( 'memory' );
let trackHandlers = [];

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
 * @memberof mw
 * @param {string} topic Topic name
 * @param {...Object|number|string} [data] Data describing the event.
 */
mw.track = function ( topic, ...data ) {
	mw.trackQueue.push( { topic, args: data } );
	trackCallbacks.fire( mw.trackQueue );
};

/**
 * Register a handler for subset of analytic events, specified by topic.
 *
 * Handlers will be called once for each tracked event, including for any buffered events that
 * fired before the handler was subscribed. The callback is passed a `topic` string, and optional
 * `data` argument(s).
 *
 * @example
 * // To monitor all topics for debugging
 * mw.trackSubscribe( '', console.log );
 *
 * @example
 * // To subscribe to any of `foo.*`, e.g. both `foo.bar` and `foo.quux`
 * mw.trackSubscribe( 'foo.', console.log );
 *
 * @memberof mw
 * @param {string} topic Handle events whose name starts with this string prefix
 * @param {Function} callback Handler to call for each matching tracked event
 * @param {string} callback.topic
 * @param {...Object|number|string} [callback.data]
 */
mw.trackSubscribe = function ( topic, callback ) {
	let seen = 0;
	function handler( trackQueue ) {
		for ( ; seen < trackQueue.length; seen++ ) {
			const event = trackQueue[ seen ];
			if ( event.topic.startsWith( topic ) ) {
				callback( event.topic, ...event.args );
			}
		}
	}

	trackHandlers.push( [ handler, callback ] );
	trackCallbacks.add( handler );
};

/**
 * Stop handling events for a particular handler.
 *
 * @memberof mw
 * @param {Function} callback
 */
mw.trackUnsubscribe = function ( callback ) {
	trackHandlers = trackHandlers.filter( ( fns ) => {
		if ( fns[ 1 ] === callback ) {
			trackCallbacks.remove( fns[ 0 ] );
			// Ensure the tuple is removed to avoid holding on to closures
			return false;
		}
		return true;
	} );
};

// Notify subscribers of any mw.trackQueue.push() calls
// from the startup module before mw.track() is defined.
trackCallbacks.fire( mw.trackQueue );

/**
 * @namespace Hooks
 * @description Registry and firing of events.
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
 * See {@link Hook}.
 *
 * Example usage:
 * ```
 * mw.hook( 'wikipage.content' ).add( fn ).remove( fn );
 * mw.hook( 'wikipage.content' ).fire( $content );
 * ```
 *
 * Handlers can be added and fired for arbitrary event names at any time. The same
 * event can be fired multiple times. The last run of an event is memorized
 * (similar to `$(document).ready` and `$.Deferred().done`).
 * This means if an event is fired, and a handler added afterwards, the added
 * function will be fired right away with the last given event data.
 *
 * Like Deferreds and Promises, the {@link mw.hook} object is both detachable and chainable.
 * Thus allowing flexible use and optimal maintainability and authority control.
 * You can pass around the `add` and/or `fire` method to another piece of code
 * without it having to know the event name (or {@link mw.hook} for that matter).
 *
 * ```
 * var h = mw.hook( 'bar.ready' );
 * new mw.Foo( .. ).fetch( { callback: h.fire } );
 * ```
 *
 * The function signature for hooks can be considered [stable](https://www.mediawiki.org/wiki/Special:MyLanguage/Stable_interface_policy/Frontend).
 * See available global events below.
 */

const hooks = Object.create( null );

/**
 * Create an instance of {@link Hook}.
 *
 * @example
 * const hook = mw.hook( 'name' );
 * hook.add( () => alert( 'Hook was fired' ) );
 * hook.fire();
 *
 * @param {string} name Name of hook.
 * @return {Hook}
 */
mw.hook = function ( name ) {
	return hooks[ name ] || ( hooks[ name ] = ( function () {
		let memory;
		let deprecated;
		const fns = [];
		function rethrow( e ) {
			setTimeout( () => {
				throw e;
			} );
		}
		/**
		 * @class Hook
		 * @classdesc An instance of a hook, created via [mw.hook method]{@link mw.hook}.
		 * @global
		 * @hideconstructor
		 */
		return {
			/**
			 * Register a hook handler.
			 *
			 * @param {...Function} handlers Function(s) to bind.
			 * @memberof Hook
			 * @return {Hook}
			 */
			add: function ( ...handlers ) {
				if ( deprecated ) {
					deprecated();
				}
				fns.push( ...handlers );
				if ( memory ) {
					for ( const handler of handlers ) {
						try {
							handler( ...memory );
						} catch ( e ) {
							rethrow( e );
						}
					}
				}
				return this;
			},
			/**
			 * Unregister a hook handler.
			 *
			 * @param {...Function} handlers Function(s) to unbind.
			 * @memberof Hook
			 * @return {Hook}
			 */
			remove: function ( ...handlers ) {
				for ( const handler of handlers ) {
					let j;
					while ( ( j = fns.indexOf( handler ) ) !== -1 ) {
						fns.splice( j, 1 );
					}
				}
				return this;
			},
			/**
			 * Enable a deprecation warning, logged after registering a hook handler.
			 *
			 * @example
			 * mw.hook( 'myhook' ).deprecate().fire( data );
			 *
			 * @example
			 * mw.hook( 'myhook' )
			 *   .deprecate( 'Use the "someother" hook instead.' )
			 *   .fire( data );
			 *
			 * NOTE: This must be called before calling fire(), as otherwise some
			 * hook handlers may be registered and fired without being reported.
			 *
			 * @memberof Hook
			 * @param {string} msg Optional extra text to add to the deprecation warning
			 * @return {Hook}
			 * @chainable
			 */
			deprecate: function ( msg ) {
				deprecated = mw.log.makeDeprecated(
					`hook_${ name }`,
					`mw.hook "${ name }" is deprecated.` + ( msg ? ' ' + msg : '' )
				);
				return this;
			},
			/**
			 * Call hook handlers with data.
			 *
			 * @memberof Hook
			 * @param {...any} data
			 * @return {Hook}
			 * @chainable
			 */
			fire: function ( ...data ) {
				if ( deprecated && fns.length ) {
					deprecated();
				}

				for ( const fn of fns ) {
					try {
						fn.apply( null, arguments );
					} catch ( e ) {
						rethrow( e );
					}
				}
				memory = data;

				return this;
			}
		};
	}() ) );
};

/**
 * HTML construction helper functions.
 *
 * @example
 * var Html, output;
 *
 * Html = mw.html;
 * output = Html.element( 'div', {}, new Html.Raw(
 *     Html.element( 'img', { src: '<' } )
 * ) );
 * mw.log( output ); // <div><img src="&lt;"/></div>
 *
 * @namespace mw.html
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
	 * @example
	 * mw.html.escape( '< > \' & "' );
	 * // Returns &lt; &gt; &#039; &amp; &quot;
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
		let s = '<' + name;

		if ( attrs ) {
			for ( const attrName in attrs ) {
				let v = attrs[ attrName ];
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
	 * @classdesc Wrapper object for raw HTML. Can be used with {@link mw.html.element}.
	 * @class mw.html.Raw
	 * @param {string} value
	 * @example
	 * const raw = new mw.html.Raw( 'Text' );
	 * mw.html.element( 'div', { class: 'html' }, raw );
	 */
	Raw: function ( value ) {
		this.value = value;
	}
};

/**
 * Schedule a function to run once the page is ready (DOM loaded).
 *
 * @since 1.5.8
 * @memberof window
 * @param {Function} fn
 */
window.addOnloadHook = function ( fn ) {
	$( () => {
		fn();
	} );
};

const loadedScripts = {};

/**
 * Import a script using an absolute URI.
 *
 * @since 1.12.2
 * @memberof window
 * @param {string} url
 * @return {HTMLElement|null} Script tag, or null if it was already imported before
 */
window.importScriptURI = function ( url ) {
	if ( loadedScripts[ url ] ) {
		return null;
	}
	loadedScripts[ url ] = true;
	return mw.loader.addScriptTag( url );
};

/**
 * Import a local JS content page, for use by user scripts and site-wide scripts.
 *
 * Note that if the same title is imported multiple times, it will only
 * be loaded and executed once.
 *
 * @since 1.12.2
 * @memberof window
 * @param {string} title
 * @return {HTMLElement|null} Script tag, or null if it was already imported before
 */
window.importScript = function ( title ) {
	return window.importScriptURI(
		mw.config.get( 'wgScript' ) + '?title=' + mw.internalWikiUrlencode( title ) +
			'&action=raw&ctype=text/javascript'
	);
};

/**
 * Import a local CSS content page, for use by user scripts and site-wide scripts.
 *
 * @since 1.12.2
 * @memberof window
 * @param {string} title
 * @return {HTMLElement} Link tag
 */
window.importStylesheet = function ( title ) {
	return mw.loader.addLinkTag(
		mw.config.get( 'wgScript' ) + '?title=' + mw.internalWikiUrlencode( title ) +
			'&action=raw&ctype=text/css'
	);
};

/**
 * Import a stylesheet using an absolute URI.
 *
 * @since 1.12.2
 * @memberof window
 * @param {string} url
 * @param {string} media
 * @return {HTMLElement} Link tag
 */
window.importStylesheetURI = function ( url, media ) {
	return mw.loader.addLinkTag( url, media );
};

/**
 * Get the names of all registered ResourceLoader modules.
 *
 * @memberof mw.loader
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
 * ```
 * mw.loader.using( 'oojs', function () {
 *     OO.compare( [ 1 ], [ 1 ] );
 * } );
 * ```
 *
 * Example of inline dependency obtained via `require()`:
 * ```
 * mw.loader.using( [ 'mediawiki.util' ], function ( require ) {
 *     var util = require( 'mediawiki.util' );
 * } );
 * ```
 *
 * Since MediaWiki 1.23 this returns a promise.
 *
 * Since MediaWiki 1.28 the promise is resolved with a `require` function.
 *
 * @memberof mw.loader
 * @param {string|Array} dependencies Module name or array of modules names the
 *  callback depends on to be ready before executing
 * @param {Function} [ready] Callback to execute when all dependencies are ready
 * @param {Function} [error] Callback to execute if one or more dependencies failed
 * @return {jQuery.Promise} With a `require` function
 */
mw.loader.using = function ( dependencies, ready, error ) {
	const deferred = $.Deferred();

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
		() => {
			deferred.resolve( mw.loader.require );
		},
		deferred.reject
	);

	return deferred.promise();
};

/**
 * Load a script by URL.
 *
 * @example
 * mw.loader.getScript(
 *     'https://example.org/x-1.0.0.js'
 * )
 *     .then( function () {
 *         // Script succeeded. You can use X now.
 *     }, function ( e ) {
 *         // Script failed. X is not avaiable
 *         mw.log.error( e.message ); // => "Failed to load script"
 *     } );
 * } );
 *
 * @memberof mw.loader
 * @param {string} url Script URL
 * @return {jQuery.Promise} Resolved when the script is loaded
 */
mw.loader.getScript = function ( url ) {
	return $.ajax( url, { dataType: 'script', cache: true } )
		.catch( () => {
			throw new Error( 'Failed to load script' );
		} );
};

// Skeleton user object, extended by the 'mediawiki.user' module.
/**
 * @namespace mw.user
 * @ignore
 */
mw.user = {
	/**
	 * Map of user preferences and their values.
	 *
	 * @type {mw.Map}
	 */
	options: new mw.Map(),
	/**
	 * Map of retrieved user tokens.
	 *
	 * @type {mw.Map}
	 */
	tokens: new mw.Map()
};

mw.user.options.set( require( './user.json' ) );

// Process callbacks for modern browsers (Grade A) that require modules.
const queue = window.RLQ;
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

/**
 * Replace document.write/writeln with basic html parsing that appends
 * to the `<body>` to avoid blanking pages. Added JavaScript will not run.
 *
 * @ignore
 * @deprecated since 1.26
 */
[ 'write', 'writeln' ].forEach( ( func ) => {
	mw.log.deprecate( document, func, function () {
		$( document.body ).append( $.parseHTML( slice.call( arguments ).join( '' ) ) );
	}, 'Use jQuery or mw.loader.load instead.', 'document.' + func );
} );

// Load other files in the package
require( './errorLogger.js' );
