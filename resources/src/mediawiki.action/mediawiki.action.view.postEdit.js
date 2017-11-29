( function ( mw, $ ) {
	'use strict';

	/**
	 * Fired after an edit was successfully saved.
	 *
	 * Does not fire for null edits.
	 *
	 * @event postEdit
	 * @member mw.hook
	 * @param {Object} [data] Optional data
	 * @param {string|jQuery|Array} [data.message] Message that listeners
	 *  should use when displaying notifications. String for plain text,
	 *  use array or jQuery object to pass actual nodes.
	 * @param {string|mw.user} [data.user=mw.user] User that made the edit.
	 */

	var postEdit = mw.config.get( 'wgPostEdit' );

	function showConfirmation( data ) {
		data = data || {};
		mw.notify( mw.msg( 'postedit-confirmation-saved', data.user || mw.user ) );

		if ( data.message === undefined ) {
			data.message = $.parseHTML( mw.message( 'postedit-confirmation-saved', data.user || mw.user ).escaped() );
		}
	}

	mw.hook( 'postEdit' ).add( showConfirmation );

	if ( postEdit ) {
		mw.hook( 'postEdit' ).fire( {
			// The following messages can be used here:
			// postedit-confirmation-saved
			// postedit-confirmation-created
			// postedit-confirmation-restored
			message: mw.msg(
				'postedit-confirmation-' + postEdit,
				mw.user
			)
		} );
	}

}( mediaWiki, jQuery ) );
