/*!
 * jQuery confirmable plugin customization for MediaWiki
 *
 * This file serves to inject our localised messages into it.
 */

( function () {
	$.fn.confirmable.defaultOptions.i18n = {
		space: mw.msg( 'word-separator' ),
		confirm: mw.msg( 'confirmable-confirm', mw.user ),
		yes: mw.msg( 'confirmable-yes' ),
		no: mw.msg( 'confirmable-no' ),
		yesTitle: undefined,
		noTitle: undefined
	};
}() );
