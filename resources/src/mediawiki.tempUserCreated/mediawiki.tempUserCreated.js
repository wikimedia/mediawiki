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
		mw.notify( $content, {
			title: title,
			classes: [ 'postedit-tempuserpopup' ],
			autoHide: false
		} );
	};

}() );
