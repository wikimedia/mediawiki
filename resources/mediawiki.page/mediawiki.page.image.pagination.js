/**
 * Change multi-page image navigation so that the current page display can be changed
 * without a page reload. Currently, the only image formats that can be multi-page images are
 * PDF and DjVu files
 */
( function (mw, $) {
	// Use jQuery's load function to specifically select and replace table.multipageimage's child
	// tr with the new page's table.multipageimage's tr element.
	// table.multipageimage always has only one row.
	function loadPage( page ) {
		var $multipageimage = $( 'table.multipageimage' ),
			$spinner = $.createSpinner( {
				size: 'large',
				type: 'block'
			} );

		// Set the spinner's dimensions equal to the table's dimensions so that
		// the current scroll position is not lost after the table is emptied prior to
		// its contents being updated
		$spinner.css( {
			height: $multipageimage.find( 'tr' ).height(),
			width: $multipageimage.find( 'tr' ).width()
		} );

		$multipageimage.empty().append( $spinner ).load(
			page + ' table.multipageimage tr',
			ajaxifyPageNavigation
		);
	}

	function ajaxifyPageNavigation() {
		// Intercept the default action of the links in the thumbnail navigation
		$( '.multipageimagenavbox' ).one( 'click', 'a', function ( e ) {
			loadPage( this.href );
			e.preventDefault();
		} );

		// Prevent the submission of the page select form and instead call loadPage
		$( 'form[name="pageselector"]' ).one( 'change submit', function ( e ) {
			loadPage( this.action + '?' + $( this ).serialize() );
			e.preventDefault();
		} );
	}

	$( document ).ready( function() {
		// The presence of table.multipageimage signifies that this file is a multi-page image
		if( mw.config.get( 'wgNamespaceNumber' ) === 6 && $( 'table.multipageimage' ).length !== 0 ) {
			ajaxifyPageNavigation();
		}
	} );
}( mediaWiki, jQuery ) );
