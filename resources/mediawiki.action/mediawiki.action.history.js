/*
 * JavaScript for History action
 */
jQuery( function( $ ) {
	// Replaces histrowinit
	$( '#pagehistory li input[name="diff"], #pagehistory li input[name="oldid"]' ).click( diffcheck );
	diffcheck();
});