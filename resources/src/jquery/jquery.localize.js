/**
 * @class jQuery.plugin.localize
 */
( function ( $, mw ) {

	/**
	 * Gets a localized message, using parameters from options if present.
	 *
	 * @ignore
	 * @param {Object} options
	 * @param {string} key
	 * @return {string} Localized message
	 */
	function msg( options, key ) {
		var args = options.params[ key ] || [];
		// Format: mw.msg( key [, p1, p2, ...] )
		args.unshift( options.prefix + ( options.keys[ key ] || key ) );
		return mw.msg.apply( mw, args );
	}

	/**
	 * Localizes a DOM selection by replacing <html:msg /> elements with localized text and adding
	 * localized title and alt attributes to elements with title-msg and alt-msg attributes
	 * respectively.
	 *
	 * Call on a selection of HTML which contains `<html:msg key="message-key" />` elements or elements
	 * with title-msg="message-key", alt-msg="message-key" or placeholder-msg="message-key" attributes.
	 * `<html:msg />` elements will be replaced with localized text, *-msg attributes will be replaced
	 * with attributes that do not have the "-msg" suffix and contain a localized message.
	 *
	 * Example:
	 *
	 *     // Messages: { 'title': 'Awesome', 'desc': 'Cat doing backflip' 'search' contains 'Search' }
	 *     var html = '\
	 *         <p>\
	 *             <html:msg key="title" />\
	 *             <img src="something.jpg" title-msg="title" alt-msg="desc" />\
	 *             <input type="text" placeholder-msg="search" />\
	 *         </p>';
	 *     $( 'body' ).append( $( html ).localize() );
	 *
	 * Appends something like this to the body...
	 *
	 *     <p>
	 *         Awesome
	 *         <img src="something.jpg" title="Awesome" alt="Cat doing backflip" />
	 *         <input type="text" placeholder="Search" />
	 *     </p>
	 *
	 * Arguments can be passed into uses of a message using the params property of the options object
	 * given to .localize(). Multiple messages can be given parameters, because the params property is
	 * an object keyed by the message key to apply the parameters to, each containing an array of
	 * parameters to use. The limitation is that you can not use different parameters to individual uses
	 * of a message in the same selection being localized - they will all recieve the same parameters.
	 *
	 * Example:
	 *
	 *     // Messages: { 'easy-as': 'Easy as $1 $2 $3.' }
	 *     var html = '<p><html:msg key="easy-as" /></p>';
	 *     $( 'body' ).append( $( html ).localize( { 'params': { 'easy-as': ['a', 'b', 'c'] } } ) );
	 *
	 * Appends something like this to the body...
	 *
	 *     <p>Easy as a, b, c</p>
	 *
	 * Raw HTML content can be used, instead of it being escaped as text. To do this, just use the raw
	 * attribute on a msg element.
	 *
	 * Example:
	 *
	 *     // Messages: { 'hello': '<b><i>Hello</i> $1!</b>' }
	 *     var html = '\
	 *         <p>\
	 *             <!-- escaped: --><html:msg key="hello" />\
	 *             <!-- raw: --><html:msg key="hello" raw />\
	 *         </p>';
	 *     $( 'body' ).append( $( html ).localize( { 'params': { 'hello': ['world'] } } ) );
	 *
	 * Appends something like this to the body...
	 *
	 *     <p>
	 *         <!-- escaped: -->&lt;b&gt;&lt;i&gt;Hello&lt;/i&gt; world!&lt;/b&gt;
	 *         <!-- raw: --><b><i>Hello</i> world!</b>
	 *     </p>
	 *
	 * Message keys can also be remapped, allowing the same generic template to be used with a variety
	 * of messages. This is important for improving re-usability of templates.
	 *
	 * Example:
	 *
	 *     // Messages: { 'good-afternoon': 'Good afternoon' }
	 *     var html = '<p><html:msg key="greeting" /></p>';
	 *     $( 'body' ).append( $( html ).localize( { 'keys': { 'greeting': 'good-afternoon' } } ) );
	 *
	 * Appends something like this to the body...
	 *
	 *     <p>Good afternoon</p>
	 *
	 * Message keys can also be prefixed globally, which is handy when writing extensions, where by
	 * convention all messages are prefixed with the extension's name.
	 *
	 * Example:
	 *
	 *     // Messages: { 'teleportation-warning': 'You may not get there all in one piece.' }
	 *     var html = '<p><html:msg key="warning" /></p>';
	 *     $( 'body' ).append( $( html ).localize( { 'prefix': 'teleportation-' } ) );
	 *
	 * Appends something like this to the body...
	 *
	 *     <p>You may not get there all in one piece.</p>
	 *
	 * @param {Object} options Map of options to be used while localizing
	 * @param {string} options.prefix String to prepend to all message keys
	 * @param {Object} options.keys Message key aliases, used for remapping keys to a template
	 * @param {Object} options.params Lists of parameters to use with certain message keys
	 * @return {jQuery}
	 * @chainable
	 */
	$.fn.localize = function ( options ) {
		var $target = this,
			attributes = [ 'title', 'alt', 'placeholder' ];

		// Extend options
		options = $.extend( {
			prefix: '',
			keys: {},
			params: {}
		}, options );

		// Elements
		// Ok, so here's the story on this selector. In IE 6/7, searching for 'msg' turns up the
		// 'html:msg', but searching for 'html:msg' doesn't. In later IE and other browsers, searching
		// for 'html:msg' turns up the 'html:msg', but searching for 'msg' doesn't. So searching for
		// both 'msg' and 'html:msg' seems to get the job done. This feels pretty icky, though.
		$target.find( 'msg,html\\:msg' ).each( function () {
			var $el = $( this );
			// Escape by default
			if ( $el.attr( 'raw' ) ) {
				$el.html( msg( options, $el.attr( 'key' ) ) );
			} else {
				$el.text( msg( options, $el.attr( 'key' ) ) );
			}
			// Remove wrapper
			$el.replaceWith( $el.html() );
		} );

		// Attributes
		// Note: there's no way to prevent escaping of values being injected into attributes, this is
		// on purpose, not a design flaw.
		attributes.forEach( function ( attr ) {
			var msgAttr = attr + '-msg';
			$target.find( '[' + msgAttr + ']' ).each( function () {
				var $el = $( this );
				$el.attr( attr, msg( options, $el.attr( msgAttr ) ) ).removeAttr( msgAttr );
			} );
		} );

		// HTML, Text for elements which cannot have children e.g. OPTION
		$target.find( '[data-msg-text]' ).each( function () {
			var $el = $( this );
			$el.text( msg( options, $el.attr( 'data-msg-text' ) ) );
		} );

		$target.find( '[data-msg-html]' ).each( function () {
			var $el = $( this );
			$el.html( msg( options, $el.attr( 'data-msg-html' ) ) );
		} );

		return $target;
	};

	// Let IE know about the msg tag before it's used...
	document.createElement( 'msg' );

	/**
	 * @class jQuery
	 * @mixins jQuery.plugin.localize
	 */

}( jQuery, mediaWiki ) );
