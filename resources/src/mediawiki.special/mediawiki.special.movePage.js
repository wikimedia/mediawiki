/*!
 * JavaScript for Special:MovePage
 */
( function( $, mw ) {
	$( function ( $ ) {
		$( '#wpNewTitleMain' ).byteLimit();
		$( '#wpReason' ).byteLimit( {
			'byte': 767,
			codepoint: mw.config.get( 'wgMaxEditSummaryLength' )
		} );
	} );
})( jQuery, mediaWiki );
