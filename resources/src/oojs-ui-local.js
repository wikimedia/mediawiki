( function ( mw ) {
	var isMobile;
	// Connect OOjs UI to MediaWiki's localisation system
	OO.ui.getUserLanguages = mw.language.getFallbackLanguageChain;
	OO.ui.msg = mw.msg;
	// Connect OOjs UI's deprecation warnings to MediaWiki's logging system
	OO.ui.warnDeprecation = function ( message ) {
		mw.track( 'mw.deprecate', 'oojs-ui' );
		mw.log.warn( message );
	};
	OO.ui.isMobile = function () {
		if ( isMobile === undefined ) {
			isMobile = mw.config.get( 'skin' ) === 'minerva';
		}
		return isMobile;
	};
}( mediaWiki ) );
