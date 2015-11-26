( function ( mw, $ ) {
	'use strict';

	/**
	 * @event postEdit
	 * @member mw.hook
	 * @param {Object} [data] Optional data
	 * @param {string|jQuery|Array} [data.message] Message that listeners
	 *  should use when displaying notifications. String for plain text,
	 *  use array or jQuery object to pass actual nodes.
	 * @param {string|mw.user} [data.user=mw.user] User that made the edit.
	 */

	/**
	 * After the listener for #postEdit removes the notification.
	 *
	 * @event postEdit_afterRemoval
	 * @member mw.hook
	 */

	var config = mw.config.get( [ 'wgAction', 'wgCurRevisionId' ] ),
		// This should match EditPage::POST_EDIT_COOKIE_KEY_PREFIX:
		cookieKey = 'PostEditRevision' + config.wgCurRevisionId,
		cookieVal = mw.cookie.get( cookieKey ),
		$div, id;

	function removeConfirmation() {
		$div.remove();
		mw.hook( 'postEdit.afterRemoval' ).fire();
	}

	function fadeOutConfirmation() {
		clearTimeout( id );
		$div.find( '.postedit' ).addClass( 'postedit postedit-faded' );
		setTimeout( removeConfirmation, 500 );

		return false;
	}

	function showConfirmation( data ) {
		data = data || {};
		if ( data.message === undefined ) {
			data.message = $.parseHTML( mw.message( 'postedit-confirmation-saved', data.user || mw.user ).escaped() );
		}

		$div = mw.template.get( 'mediawiki.action.view.postEdit', 'postEdit.html' ).render();

		if ( typeof data.message === 'string' ) {
			$div.find( '.postedit-content' ).text( data.message );
		} else if ( typeof data.message === 'object' ) {
			$div.find( '.postedit-content' ).append( data.message );
		}

		$div
			.click( fadeOutConfirmation )
			.prependTo( 'body' );

		id = setTimeout( fadeOutConfirmation, 3000 );
	}

	mw.hook( 'postEdit' ).add( showConfirmation );

	if ( config.wgAction === 'view' && cookieVal ) {
		mw.config.set( 'wgPostEdit', true );

		mw.hook( 'postEdit' ).fire( {
			// The following messages can be used here:
			// postedit-confirmation-saved
			// postedit-confirmation-created
			// postedit-confirmation-restored
			message: mw.msg(
				'postedit-confirmation-' + cookieVal,
				mw.user
			)
		} );
		mw.cookie.set( cookieKey, null );
	}

}( mediaWiki, jQuery ) );
