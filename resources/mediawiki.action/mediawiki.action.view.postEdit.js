( function ( mw, $ ) {
	'use strict';

	var config = mw.config.get( [ 'wgAction', 'wgCookiePrefix', 'wgCurRevisionId' ] ),
		// This should match EditPage::POST_EDIT_COOKIE_KEY_PREFIX:
		cookieKey = config.wgCookiePrefix + 'PostEditRevision' + config.wgCurRevisionId,
		div, id;

	function removeConfirmation() {
		div.remove();
		mw.hook( 'postEdit.afterRemoval' ).fire();
	}

	function fadeOutConfirmation() {
		clearTimeout( id );
		div.find( '.postedit' ).addClass( 'postedit postedit-faded' );
		setTimeout( removeConfirmation, 500 );
		return false;
	}

	function showConfirmation( message ) {
		if ( message === undefined ) {
			message = mw.message( 'postedit-confirmation', mw.user ).escaped();
		}
		div = $(
			'<div class="postedit-container">' +
				'<div class="postedit">' +
					'<div class="postedit-icon postedit-icon-checkmark postedit-contents">' +
					'</div>' +
					'<a href="#" class="postedit-close">&times;</a>' +
				'</div>' +
			'</div>'
		);
		if ( typeof message === 'string' ) {
			div.find( '.postedit-contents' ).text( message );
		} else if ( typeof message === 'object' ) {
			div.find( '.postedit-contents' ).append( message );
		}
		id = setTimeout( fadeOutConfirmation, 3000 );
		div.click( fadeOutConfirmation );
		div.insertBefore( $( 'body' ).children().first() );
	}

	// Expose showConfirmation to other processes, e.g. VisualEditor
	mw.showPostEditConfirmation = showConfirmation;

	// postEdit hook calls showConfirmation without any arguments
	mw.hook( 'postEdit' ).add( function() {
		mw.showPostEditConfirmation();
	} );

	if ( config.wgAction === 'view' && $.cookie( cookieKey ) === '1' ) {
		$.cookie( cookieKey, null, { path: '/' } );
		mw.config.set( 'wgPostEdit', true );
		mw.hook( 'postEdit' ).fire();
	}

} ( mediaWiki, jQuery ) );
