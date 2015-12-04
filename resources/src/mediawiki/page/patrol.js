/*!
 * @since 1.27
 * @author Florian Schmidt <florian.schmidt.welzow@t-online.de>
 * @author Timo Tijhof <krinklemail@gmail.com>
 */
( function ( mw, OO ) {

	/**
	 * Bind click handlers to patrol links.
	 *
	 * @class mw.page.patrol
	 * @singleton
	 */
	mw.page.patrol = {
		/**
		 * Add click handlers to $patrolLinks that patrol a revision via the API.
		 *
		 * A {@link #event-patrol patrol event} is emitted on the returned OO.EventEmitter
		 * object to allow for a UI to track or represent the state of the request.
		 *
		 * @param {jQuery} $patrolLinks jQuery object representing one or more patrol links
		 * @return {OO.EventEmitter}
		 */
		setup: function ( $patrolLinks ) {
			var emitter = new OO.EventEmitter();

			// Verify the current user has the patrol right
			if ( mw.user.tokens.exists( 'patrolToken' ) ) {
				// Attach listeners to the given links
				$patrolLinks.on( 'click', function ( e ) {
					var api, promise;

					// Perform the patrol action using the API
					api = new mw.Api();
					promise = api.postWithToken( 'patrol', {
						action: 'patrol',
						rcid: mw.util.getParamValue( 'rcid', this.href )
					} );

					/**
					 * @event patrol
					 * @param {jQuery.Promise} promise API request
					 * @param {HTMLElement} node Patrol link
					 */
					emitter.emit( 'patrol', promise, this );

					// Prevent URL navigation, the action is being performed via AJAX
					e.preventDefault();
				} );
			}

			return emitter;
		}
	};
}( mediaWiki, OO ) );
