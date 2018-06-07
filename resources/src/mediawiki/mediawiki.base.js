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
/* globals mw */
( function () {
	'use strict';

	var slice = Array.prototype.slice,
		hasOwn = Object.prototype.hasOwnProperty;

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
			return this.map.exists( this.key );
		}
	};

	/**
	 * @class mw
	 * @singleton
	 */

	/**
	 * @inheritdoc mw.inspect#runReports
	 * @method
	 */
	mw.inspect = function () {
		var args = arguments;
		mw.loader.using( 'mediawiki.inspect', function () {
			mw.inspect.runReports.apply( mw.inspect, args );
		} );
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
}() );
