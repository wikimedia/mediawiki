/*!
 * jQuery confirmable plugin customization for MediaWiki
 *
 * This file serves to inject our localised messages into it.
 */

( function ( mw, $ ) {
	$.fn.confirmable.defaultOptions.i18n = {
		space: mw.message( 'word-separator' ).text(),
		confirm: mw.message( 'confirmable-confirm', mw.user ).text(),
		yes: mw.message( 'confirmable-yes' ).text(),
		no: mw.message( 'confirmable-no' ).text(),
		yesTitle: undefined,
		noTitle: undefined
	};
}( mediaWiki, jQuery ) );
