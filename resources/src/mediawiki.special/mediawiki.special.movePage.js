/*!
 * JavaScript for Special:MovePage
 */
jQuery( function ( $ ) {
	$( '#wpNewTitleMain' ).byteLimit();
	$( '#wpReason' ).byteLimit( {
		byte: 767,
		codepoint: mw.config.get( 'wgMaxEditSummaryLength' )
	} );
} );
