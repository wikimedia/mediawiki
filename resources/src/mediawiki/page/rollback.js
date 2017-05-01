/*!
 * Enhance rollback links by using asynchronous API requests,
 * rather than navigating to an action page.
 *
 * @since 1.28
 * @author Timo Tijhof
 */
( function ( mw, $ ) {

	$( function () {
		$( '.mw-rollback-link' ).on( 'click', 'a[data-mw="interface"]', function ( e ) {
			var api, $spinner,
				$link = $( this ),
				url = this.href,
				page = mw.util.getParamValue( 'title', url ),
				user = mw.util.getParamValue( 'from', url );

			if ( !page || user === null ) {
				// Let native browsing handle the link
				return true;
			}

			// Preload the notification module for mw.notify
			mw.loader.load( 'mediawiki.notification' );

			// Remove event handler so that next click (re-try) uses server action
			$( e.delegateTarget ).off( 'click' );

			// Hide the link and create a spinner to show it inside the brackets.
			$spinner = $.createSpinner( { size: 'small', type: 'inline' } );
			$link.hide().after( $spinner );

			// @todo: data.messageHtml is no more. Convert to using errorformat=html.
			api = new mw.Api();
			api.rollback( page, user )
				.then( function ( data ) {
					mw.notify( $.parseHTML( data.messageHtml ), {
						title: mw.msg( 'actioncomplete' )
					} );

					// Remove link container and the subsequent text node containing " | ".
					if ( e.delegateTarget.nextSibling && e.delegateTarget.nextSibling.nodeType === Node.TEXT_NODE ) {
						$( e.delegateTarget.nextSibling ).remove();
					}
					$( e.delegateTarget ).remove();
				}, function ( errorCode, data ) {
					var message = data && data.error && data.error.messageHtml ?
						$.parseHTML( data.error.messageHtml ) :
						mw.msg( 'rollbackfailed' ),
						type = errorCode === 'alreadyrolled' ? 'warn' : 'error';

					mw.notify( message, {
						type: type,
						title: mw.msg( 'rollbackfailed' ),
						autoHide: false
					} );

					// Restore the link (enables user to try again)
					$spinner.remove();
					$link.show();
				} );

			e.preventDefault();
		} );
	} );

}( mediaWiki, jQuery ) );
