/* jQuery.confirmable itself is independent of MediaWiki.
   This file serves to inject our localised messages into it. */
( function ( mw , $ ) {
	$.fn.confirmable.defaultOptions.i18n = {
		confirm: mw.message( 'confirmable-confirm', mw.user ).text(),
		yes: mw.message( 'confirmable-yes' ).text(),
		no: mw.message( 'confirmable-no' ).text()
	};
}( mediaWiki, jQuery ) );
