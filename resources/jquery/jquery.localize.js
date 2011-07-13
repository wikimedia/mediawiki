/**
 * Simple Placeholder-based Localization
 *
 * Call on a selection of HTML which contains <msg key="message-key" /> elements or elements with
 * title-msg="message-key" or alt-msg="message-key" attributes. <msg /> elements will be replaced
 * with localized text, elements with title-msg and alt-msg attributes will receive localized title
 * and alt attributes.
 *
 * Note that "msg" elements must have html namespacing such as "<html:msg />" to be compatible with
 * Internet Explorer.
 *
 * Example:
 *		<p class="somethingCool">
 *			<html:msg key="my-message" />
 *			<img src="something.jpg" title-msg="my-title-message" alt-msg="my-alt-message" />
 *		</p>
 *
 * Localizes to...
 *		<p class="somethingCool">
 *			My Message
 *			<img src="something.jpg" title="My Title Message" alt="My Alt Message" />
 *		</p>
 */
( function( $ ) {
/**
 * Localizes a DOM selection by replacing <msg /> elements with localized text and adding
 * localized title and alt attributes to elements with title-msg and alt-msg attributes
 * respectively.
 *
 * @param Object: options Map of options
 *  * prefix: Message prefix to use when localizing elements and attributes
 */

$.fn.localize = function( options ) {
	options = $.extend( { 'prefix': '', 'keys': {}, 'params': {} }, options );
	function msg( key ) {
		var args = key in options.params ? options.params[key] : [];
		// Format: mw.msg( key [, p1, p2, ...] )
		args.unshift( options.prefix + ( key in options.keys ? options.keys[key] : key ) );
		return mw.msg.apply( mw, args );
	};
	return $(this)
		.find( 'msg,html\\:msg' )
			.each( function() {
				var $el = $(this);
				$el
					.text( msg( $el.attr( 'key' ) ) )
					.replaceWith( $el.html() );
			} )
			.end()
		.find( '[title-msg]' )
			.each( function() {
				var $el = $(this);
				$el
					.attr( 'title', msg( $el.attr( 'title-msg' ) ) )
					.removeAttr( 'title-msg' );
			} )
			.end()
		.find( '[alt-msg]' )
			.each( function() {
				var $el = $(this);
				$el
					.attr( 'alt', msg( $el.attr( 'alt-msg' ) ) )
					.removeAttr( 'alt-msg' );
			} )
			.end();
};

// Let IE know about the msg tag before it's used...
document.createElement( 'msg' );
} )( jQuery );
