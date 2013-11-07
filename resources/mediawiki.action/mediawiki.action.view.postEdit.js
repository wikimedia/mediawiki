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

	var config = mw.config.get( [ 'wgAction', 'wgCookiePrefix', 'wgCurRevisionId' ] ),
		// This should match EditPage::POST_EDIT_COOKIE_KEY_PREFIX:
		cookieKey = config.wgCookiePrefix + 'PostEditRevision' + config.wgCurRevisionId,
		$div, id;

	function showConfirmation( data ) {
		data = data || {};
		if ( data.message === undefined ) {
			data.message = $.parseHTML( mw.message( 'postedit-confirmation', data.user || mw.user ).escaped() );
		}

		$div = $(
			'<div class="postedit-container">' +
				'<div class="postedit">' +
					'<div class="postedit-icon postedit-icon-checkmark postedit-content"></div>' +
					'<a href="#" class="postedit-close">&times;</a>' +
				'</div>' +
			'</div>'
		);

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

	function fadeOutConfirmation() {
		clearTimeout( id );
		$div.find( '.postedit' ).addClass( 'postedit postedit-faded' );
		setTimeout( removeConfirmation, 500 );

		return false;
	}

	function removeConfirmation() {
		$div.remove();
		mw.hook( 'postEdit.afterRemoval' ).fire();
	}

	mw.hook( 'postEdit' ).add( showConfirmation );

	if ( config.wgAction === 'view' && $.cookie( cookieKey ) === '1' ) {
		$.cookie( cookieKey, null, { path: '/' } );
		mw.config.set( 'wgPostEdit', true );

		mw.hook( 'postEdit' ).fire();
	}

} ( mediaWiki, jQuery ) );
