/*!
 * jQuery confirmable plugin customization for MediaWiki
 *
 * This file serves to inject our localised messages into it.
 */

( function ( mw, $ ) {
	$.fn.confirmable.defaultOptions.i18n = {
		confirm: mw.message( 'confirmable-confirm', mw.user ).text() + '\u200c',
		yes: mw.message( 'confirmable-yes' ).text() + '\u200c',
		no: mw.message( 'confirmable-no' ).text() + '\u200c'
	};
}( mediaWiki, jQuery ) );
