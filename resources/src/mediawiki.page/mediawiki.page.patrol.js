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
		 * @param {Object} options Options for setup
		 * @cfg {Object} options Options hash.
		 * @cfg {jQuery} links jQuery object representing all patrollinks on the page
		 * @cfg {jQuery} spinner jQuery object used as the spinner
		 */
		setup: function ( options ) {
			this.$patrolLinks = options.links;
			this.$spinner = options.spinner;

			if ( !mw.user.tokens.exists( 'patrolToken' ) ) {
				// Current user has no patrol right, or an old cached version of user.tokens
				// that didn't have patrolToken yet.
				return;
			}

			this.$patrolLinks.on( 'click', $.proxy( this, 'onPatrolClick' ) );
		},

		/**
		 * Event handler for click on a patrol link. Marks the revision as patrolled using the.
		 *
		 * @param {jQuery.Event} e Click Event object
		 */
		onPatrolClick: function ( e ) {
			var href, rcid, apiRequest, self = this;

			// Start preloading the notification module (normally loaded by mw.notify())
			mw.loader.load( ['mediawiki.notification'], null, true );

			// Hide the link show a spinner instead.
			$( e.target ).hide().after( this.$spinner );

			href = $( e.target ).attr( 'href' );
			rcid = mw.util.getParamValue( 'rcid', href );
			apiRequest = new mw.Api();

			apiRequest.postWithToken( 'patrol', {
				action: 'patrol',
				rcid: rcid
			} )
			.done( function ( data ) {
				// Remove all patrollinks from the page (including any spinners inside).
				self.$patrolLinks.closest( '.patrollink' ).remove();
				if ( data.patrol !== undefined ) {
					// Success
					var title = new mw.Title( data.patrol.title );
					mw.notify( mw.msg( 'markedaspatrollednotify', title.toText() ) );
				} else {
					// This should never happen as errors should trigger fail
					mw.notify( mw.msg( 'markedaspatrollederrornotify' ) );
				}
			} )
			.fail( function ( error ) {
				self.$spinner.remove();
				// Restore the patrol link. This allows the user to try again
				// (or open it in a new window, bypassing this ajax module).
				self.$patrolLinks.show();
				if ( error === 'noautopatrol' ) {
					// Can't patrol own
					mw.notify( mw.msg( 'markedaspatrollederror-noautopatrol' ) );
				} else {
					mw.notify( mw.msg( 'markedaspatrollederrornotify' ) );
				}
			} );

			e.preventDefault();
		}
	};

}( mediaWiki, jQuery ) );
