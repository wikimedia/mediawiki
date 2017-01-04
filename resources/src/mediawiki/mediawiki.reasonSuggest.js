/*!
* Add autocomplete suggestions for action forms reasons.
*/
( function ( mw, $ ) {
	$( function () {
		// Convert from string to array, first index is unneeded
		var reasons = mw.config.get( 'reasons' ).split( '\n** ' );
		reasons.splice(0, 1);
		// Add relevant suggestion
		$( '#mwProtect-reason, #wpReason, #mw-input-wpReason-other' ).suggestions( {
			fetch: function () {
				var $this = $( this ), relevantSuggestions;
				relevantSuggestions = $.grep( reasons, function ( reason ) {
					return ( reason.toLowerCase().indexOf( $this.val().toLowerCase() ) > -1 );
				});
				$this.suggestions( 'suggestions', relevantSuggestions );
			},
			delay: 120,
			highlightInput: true
		} );
	} );
}( mediaWiki, jQuery ) );
