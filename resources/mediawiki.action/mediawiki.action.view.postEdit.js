( function ( mw, $ ) {
	'use strict';
	// Only a view can be a post-edit.
	if ( mw.config.get( 'wgAction' ) !== 'view' ) {
		return;
	}

	// Matches EditPage::POST_EDIT_COOKIE_KEY_PREFIX
	var cookieKey = mw.config.get( 'wgCookiePrefix' ) + 'PostEditRevision' + mw.config.get( 'wgCurRevisionId' );

	if ( $.cookie( cookieKey ) === '1' ) {
		// We just saved this page
		$.cookie( cookieKey, null, { path: '/' } );

		/** @deprecated 1.22 Use {@link mediaWiki.hook} instead. **/
		mw.config.set( 'wgPostEdit', true );
		mw.hook( 'postEdit' ).fire();
	}

	mw.hook( 'postEdit' ).add( function () {
		var div, id, removeConfirmation;

		div = document.createElement( 'div' );
		div.className = 'postedit-container';
		div.innerHTML =
			'<div class="postedit">' +
				'<div class="postedit-icon postedit-icon-checkmark">' +
					mw.message( 'postedit-confirmation', mw.user ).escaped() +
				'</div>' +
				'<a href="#" class="postedit-close">&times;</a>' +
			'</div>';
		removeConfirmation = function () {
			clearTimeout( id );
			div.firstChild.className = 'postedit postedit-faded';
			setTimeout( function () {
				div.parentNode.removeChild( div );
			}, 500 );
			return false;
		};
		id = setTimeout( removeConfirmation, 3000 );
		div.firstChild.lastChild.onclick = removeConfirmation;
		document.body.insertBefore( div, document.body.firstChild );
	} );

} ( mediaWiki, jQuery ) );
