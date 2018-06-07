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
		mwLoaderTrack = mw.track,
		trackCallbacks = $.Callbacks( 'memory' ),
		trackHandlers = [];

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

	// Subscribe to error streams
	mw.trackSubscribe( 'resourceloader.exception', mw.logError );
	mw.trackSubscribe( 'resourceloader.assert', mw.logError );

	// Fire events from before track() triggred fire()
	trackCallbacks.fire( mw.trackQueue );
}() );
