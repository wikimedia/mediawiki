/**
 * Show gallery captions when focused. Copied directly from jquery.mw-jump.js
 */
jQuery( function ( $ ) {
	$( 'ul.mw-gallery-height-constrained-overlay li.gallerybox' ).on( 'focus blur', 'div.thumb a.image', function ( e ) {
		// Confusingly jQuery leaves e.type as focusout for delegated blur events
		if ( e.type === 'blur' || e.type === 'focusout' ) {
			$( this ).closest( 'li.gallerybox' ).removeClass( 'mw-gallery-focused' );
		} else {
			$( this ).closest( 'li.gallerybox' ).addClass( 'mw-gallery-focused' );
		}
	} );
} );
