( function ( mw, $ ) {
	// Matches EditPage::POST_EDIT_COOKIE_KEY_PREFIX
	var cookieKey = mw.config.get( 'wgCookiePrefix' ) + 'PostEditRevision' + mw.config.get( 'wgCurRevisionId' );

	if ( $.cookie( cookieKey ) === '1' ) {
		// We just saved this page
		$.cookie( cookieKey, null, { path: '/' } );
		mw.config.set( 'wgPostEdit', true );
	}
} ( mediaWiki, jQuery ) );
