( function () {
	'use strict';

	var contLangMessages = require( './contLangMessages.json' );

	/**
	 * Respond to the creation of a temporary user.
	 *
	 * @namespace mw.tempUserCreated
	 */
	mw.tempUserCreated = {};

	/**
	 * Show popup after creation of a temporary user.
	 */
	mw.tempUserCreated.showPopup = function () {
		var title = mw.message( 'postedit-temp-created-label' ).text();
		var $content = mw.message(
			'postedit-temp-created',
			mw.util.getUrl( 'Special:CreateAccount' ),
			contLangMessages[ 'tempuser-helppage' ]
		).parseDom();

		var $username = $( '.mw-temp-user-banner-username' );
		if ( $username.length ) {
			// If supported by the skin, display a popup anchored to the username in the banner
			var popup = new OO.ui.PopupWidget( {
				padded: true,
				head: true,
				label: title,
				$content: $content,
				$floatableContainer: $username,
				classes: [ 'postedit-tempuserpopup' ],
				// Work around T307062
				position: 'below',
				autoFlip: false
			} );
			$( OO.ui.getTeleportTarget() ).append( popup.$element );
			popup.toggle( true );
		} else {
			// Otherwise display a mw.notify message
			mw.notify( $content, {
				title: title,
				classes: [ 'postedit-tempuserpopup' ],
				autoHide: false
			} );
		}
	};

}() );
