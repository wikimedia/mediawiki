/*!
* Add autocomplete suggestions for action forms reasons.
*/
( function ( mw, $ ) {
	$( function () {
		var reasons = mw.config.get( 'reasons' );
		
		// Add relevant suggestion
		$( '#mwProtect-reason, #wpReason, #mw-input-wpReason-other' ).suggestions( {
			fetch: function () {
				var $this = $( this ), relevantSuggestions;
				relevantSuggestions = $.grep( reasons, function ( reason ) {
					return ( reason.toLowerCase().indexOf( $this.val().toLowerCase() ) > -1 );
				} );
				$this.suggestions( 'suggestions', relevantSuggestions );
			},
			highlightInput: true
		} );
	} );
}( mediaWiki, jQuery ) );
