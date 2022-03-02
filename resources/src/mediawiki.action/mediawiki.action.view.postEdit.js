( function () {
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

	/**
	 * After the listener for #postEdit removes the notification.
	 *
	 * @deprecated
	 * @event postEdit_afterRemoval
	 * @member mw.hook
	 */

	var postEdit = mw.config.get( 'wgPostEdit' );

	function showConfirmation( data ) {
		var label;

		data = data || {};

		label = data.message || new OO.ui.HtmlSnippet( mw.message(
			mw.config.get( 'wgEditSubmitButtonLabelPublish' ) ?
				'postedit-confirmation-published' :
				'postedit-confirmation-saved',
			data.user || mw.user
		).escaped() );

		data.message = new OO.ui.MessageWidget( {
			type: 'success',
			inline: true,
			label: label
		} ).$element[ 0 ];

		mw.notify( data.message, {
			classes: [ 'postedit' ]
		} );

		// Deprecated - use the 'postEdit' hook, and an additional pause if required
		mw.hook( 'postEdit.afterRemoval' ).fire();
	}

	// JS-only flag that allows another module providing a hook handler to suppress the default one.
	if ( !mw.config.get( 'wgPostEditConfirmationDisabled' ) ) {
		mw.hook( 'postEdit' ).add( showConfirmation );
	}

	if ( postEdit ) {
		mw.hook( 'postEdit' ).fire( {
			// The following messages can be used here:
			// * postedit-confirmation-saved
			// * postedit-confirmation-created
			// * postedit-confirmation-restored
			message: mw.msg(
				'postedit-confirmation-' + postEdit,
				mw.user
			)
		} );
	}

}() );
