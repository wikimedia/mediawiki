/*
 * HTMLForm enhancements:
 * Add/remove cloner clones without having to resubmit the form.
 */
( function ( mw, $ ) {

	var cloneCounter = 0;

	mw.hook( 'htmlform.enhance' ).add( function ( $root ) {
		$root.find( '.mw-htmlform-cloner-delete-button' ).filter( ':input' ).click( function ( ev ) {
			ev.preventDefault();
			$( this ).closest( 'li.mw-htmlform-cloner-li' ).remove();
		} );

		$root.find( '.mw-htmlform-cloner-create-button' ).filter( ':input' ).click( function ( ev ) {
			var $ul, $li, html;

			ev.preventDefault();

			$ul = $( this ).prev( 'ul.mw-htmlform-cloner-ul' );

			html = $ul.data( 'template' ).replace(
				new RegExp( mw.RegExp.escape( $ul.data( 'uniqueId' ) ), 'g' ),
				'clone' + ( ++cloneCounter )
			);

			$li = $( '<li>' )
				.addClass( 'mw-htmlform-cloner-li' )
				.html( html )
				.appendTo( $ul );

			mw.hook( 'htmlform.enhance' ).fire( $li );
		} );
	} );

}( mediaWiki, jQuery ) );
