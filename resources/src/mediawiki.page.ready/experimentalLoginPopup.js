module.exports = function experimentalLoginPopup() {
	const modes = {
		popup: 'startPopupWindow',
		newtab: 'startNewTabOrWindow',
		iframe: 'startIframe'
	};

	for ( const mode in modes ) {
		const method = modes[ mode ];

		$( `#pt-login-experimental-${ mode }` ).on( 'mouseenter', () => {
			// Load early
			mw.loader.using( 'mediawiki.authenticationPopup' );
		} );

		$( `#pt-login-experimental-${ mode } a` ).on( 'click', ( e ) => {
			e.preventDefault();
			mw.loader.using( 'mediawiki.authenticationPopup' ).then( ( require ) => {
				const authPopup = require( 'mediawiki.authenticationPopup' );
				authPopup[ method ]().then( ( userinfo ) => {
					if ( userinfo ) {
						mw.notify( 'LOGGED IN', { type: 'success' } );
					} else {
						mw.notify( 'CANCELLED' );
					}
				} ).catch( ( error ) => {
					mw.notify( String( error ), { type: 'error' } );
				} );
			} );
		} );

	}
};
