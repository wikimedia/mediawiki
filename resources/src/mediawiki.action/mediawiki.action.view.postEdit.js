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
	 * @event postEdit_afterRemoval
	 * @member mw.hook
	 */

	var postEdit = mw.config.get( 'wgPostEdit' );

	function showConfirmation( data ) {
		var $container, $popup, $content, timeoutId;

		function fadeOutConfirmation() {
			$popup.addClass( 'postedit-faded' );
			setTimeout( function () {
				$container.remove();
				mw.hook( 'postEdit.afterRemoval' ).fire();
			}, 250 );
		}

		data = data || {};

		if ( data.message === undefined ) {
			data.message = $.parseHTML( mw.message(
				mw.config.get( 'wgEditSubmitButtonLabelPublish' ) ?
					'postedit-confirmation-published' :
					'postedit-confirmation-saved',
				data.user || mw.user
			).escaped() );
		}

		$content = $( '<div>' ).addClass( 'postedit-icon postedit-icon-checkmark postedit-content' );
		if ( typeof data.message === 'string' ) {
			$content.text( data.message );
		} else if ( typeof data.message === 'object' ) {
			$content.append( data.message );
		}

		$popup = $( '<div>' ).addClass( 'postedit mw-notification' ).append( $content )
			.on( 'click', function () {
				clearTimeout( timeoutId );
				fadeOutConfirmation();
			} );

		$container = $( '<div>' ).addClass( 'postedit-container' ).append( $popup );
		timeoutId = setTimeout( fadeOutConfirmation, 3000 );

		$( document.body ).prepend( $container );
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
