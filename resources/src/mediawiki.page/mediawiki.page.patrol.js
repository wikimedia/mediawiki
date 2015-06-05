/*!
 * Animate patrol links to use asynchronous API requests to
 * patrol pages, rather than navigating to a different URI.
 *
 * @since 1.21
 * @author Marius Hoch <hoo@online.de>
 */
( function ( mw, $ ) {

	mw.patrol = {
		/**
		 * Setup mw.patrol, adding event listeners to patrollinks and check, if a patrolToken exists.
		 * If not, do nothing.
		 *
		 * @param {jQuery} $patrollinks jQuery object representing all patrollinks to listen on
		 */
		setup: function ( $patrolLinks ) {
			if ( !mw.user.tokens.exists( 'patrolToken' ) ) {
				// Current user has no patrol right, or an old cached version of user.tokens
				// that didn't have patrolToken yet.
				return;
			}

			$patrolLinks.on( 'click', $.proxy( this, 'onPatrolClick' ) );
		},

		/**
		 * Event handler for click on a patrol link. Marks the revision as patrolled using the.
		 *
		 * @param {jQuery.Event} e Click Event object
		 */
		onPatrolClick: function ( e ) {
			e.preventDefault();
			var href, rcid, apiRequest, status = $.Deferred();
			$( e.target ).trigger( 'patrol::loading', [ e, status ] );

			// Start preloading the notification module (normally loaded by mw.notify())
			mw.loader.load( ['mediawiki.notification'], null, true );

			href = $( e.target ).attr( 'href' );
			rcid = mw.util.getParamValue( 'rcid', href );
			apiRequest = new mw.Api();

			apiRequest.postWithToken( 'patrol', {
				action: 'patrol',
				rcid: rcid
			} )
			.done( function ( data ) {
				status.resolve( data );
			} )
			.fail( function ( error ) {
				status.reject( error );
			} );

			e.preventDefault();
		}
	};

}( mediaWiki, jQuery ) );
