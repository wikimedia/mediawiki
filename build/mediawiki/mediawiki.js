var mw,
	log = require( './log' ),
	errorLogger = require( './errorLogger' ),
	slice = Array.prototype.slice,
	html = require( './html' ),
	track = require( './track' ),
	Map = require( './Map' ),
	now = require( './now' ),
	hook = require( './hook' ),
	requestIdleCallbackInternal = require( './requestIdleCallbackInternal' ),
	format = require( './format' ),
	Message = require( './Message' );

/**
 * @class mw
 */
mw = {
	errorLogger: errorLogger,
	/**
	 * Get the current time, measured in milliseconds since January 1, 1970 (UTC).
	 *
	 * On browsers that implement the Navigation Timing API, this function will produce floating-point
	 * values with microsecond precision that are guaranteed to be monotonic. On all other browsers,
	 * it will fall back to using `Date`.
	 *
	 * @return {number} Current time
	 */
	now: now,

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
	format: format,

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
	track: track.track,

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
	trackSubscribe: track.trackSubscribe,

	/**
	 * Stop handling events for a particular handler
	 *
	 * @param {Function} callback
	 */
	trackUnsubscribe: track.trackUnsubscribe,

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
	config: new Map(),

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
	// Will be set later
	loader: null,

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
	html: html,

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
	hook: hook,
	requestIdleCallback: requestIdleCallbackInternal
};

module.exports = mw;
