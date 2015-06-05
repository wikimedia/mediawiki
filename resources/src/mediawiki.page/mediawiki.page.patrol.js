/*!
 * Library to abstract the visible part of ajax patrolling from
 * the backend one.
 *
 * @since 1.26
 * @author Marius Hoch <hoo@online.de>
 * @author Krinkle <krinklemail@gmail.com>
 * @author Florian Schmidt <florian.schmidt.welzow@t-online.de>
 */
( function ( mw, $ ) {

	/**
	 * Abstraction library for marking revisions as patrolled with JavaScript, using the Api.
	 * @class mw.page.patrol
	 */
	mw.page.patrol = {
		/**
		 * Adding event listeners to $patrollinks and check, if a patrolToken exists.
		 * If not, do nothing. Otherwise emit setup an api request to mark the
		 * revision (the link is intended for) as patrolled and emit a patrol::loading event
		 * on the clicked link with a deferred of the api call.
		 *
		 * @param {jQuery} $patrollinks jQuery object representing all patrollinks to listen on
		 */
		setup: function ( $patrolLinks ) {
			if ( !mw.user.tokens.exists( 'patrolToken' ) ) {
				// Current user has no patrol right, or an old cached version of user.tokens
				// that didn't have patrolToken yet.
				return;
			}

			// setup listeners on the given set of links
			$patrolLinks.on( 'click', function ( ev ) {
				var href, rcid, apiRequest, status;

				// prepare values for the api call
				href = this.href;
				rcid = mw.util.getParamValue( 'rcid', href );
				apiRequest = new mw.Api();

				// mark the given revision as patrolled using the api
				status = apiRequest.postWithToken( 'patrol', {
					action: 'patrol',
					rcid: rcid
				} );

				// emit an event on the clicked link indicating, that patrolling was started
				$( this ).trigger( 'patrol::loading', status );

				// don't redirect to the link's target
				ev.preventDefault();
			} );
		}
	};
}( mediaWiki, jQuery ) );
