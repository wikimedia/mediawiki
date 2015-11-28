/*!
 * JavaScript for Special:Watchlist
 *
 * This script is only loaded, if the user opt-in a setting in Special:Preferences,
 * that the watchlist should be automatically reloaded, when a filter option is
 * changed in the header form.
 */
jQuery( function ( $ ) {
	// add a listener on all form elements in the header form
	$( '#mw-watchlist-form input, #mw-watchlist-form select' ).on( 'change', function () {
		// submit the form, when one of the input fields was changed
		$( '#mw-watchlist-form' ).submit();
	} );

} );
