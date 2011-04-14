/*
 * JavaScript for History action
 */
jQuery( function( $ ) {
	// Replaces histrowinit
	$( '#pagehistory li input[name="diff"], #pagehistory li input[name="oldid"]' ).click( diffcheck );
	diffcheck();
	mediaWiki.loader.using('jquery.ui.button', function() {
		window.fixCompare();
	});
});