( function ( mw, $ ) {
	'use strict';

	var config = mw.config.get( [ 'wgAction', 'wgCookiePrefix', 'wgCurRevisionId' ] ),
		// This should match EditPage::POST_EDIT_COOKIE_KEY_PREFIX:
		cookieKey = config.wgCookiePrefix + 'PostEditRevision' + config.wgCurRevisionId,
		div, id;

	function removeConfirmation() {
		div.parentNode.removeChild( div );
		mw.hook( 'postEdit.afterRemoval' ).fire();
	}

	function fadeOutConfirmation() {
		clearTimeout( id );
		div.firstChild.className = 'postedit postedit-faded';
		setTimeout( removeConfirmation, 500 );
		return false;
	}

	function showConfirmation() {
		div = document.createElement( 'div' );
		div.className = 'postedit-container';
		div.innerHTML =
			'<div class="postedit">' +
				'<div class="postedit-icon postedit-icon-checkmark">' +
					mw.message( 'postedit-confirmation', mw.user ).escaped() +
				'</div>' +
				'<a href="#" class="postedit-close">&times;</a>' +
			'</div>';
		id = setTimeout( fadeOutConfirmation, 3000 );
		div.firstChild.lastChild.onclick = fadeOutConfirmation;
		document.body.insertBefore( div, document.body.firstChild );
	}

	// Always register showConfirmation incase an asynchronous edit occurs, e.g. VisualEditor
	mw.hook( 'postEdit' ).add( showConfirmation );

	if ( config.wgAction === 'view' && $.cookie( cookieKey ) === '1' ) {
		$.cookie( cookieKey, null, { path: '/' } );
		mw.config.set( 'wgPostEdit', true );
		mw.hook( 'postEdit' ).fire();
	}

} ( mediaWiki, jQuery ) );
