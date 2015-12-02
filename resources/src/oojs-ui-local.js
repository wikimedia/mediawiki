// Connect OOjs UI to MediaWiki's localisation system
( function ( mw ) {
	OO.ui.getUserLanguages = mw.language.getFallbackLanguageChain;
	OO.ui.msg = mw.msg;
}( mediaWiki ) );
