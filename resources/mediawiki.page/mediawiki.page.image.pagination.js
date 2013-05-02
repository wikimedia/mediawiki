/**
 * Change multi-page image navigation so that the current page display can be changed
 * without a page reload. Currently, the only image formats that can be multi-page images are
 * PDF and DjVu files
 */
( function (mw, $) {

	// Use jQuery's load function to specifically select and replace table.multipageimage
	// with the new page's table.multipageimage
	function loadPage( page ) {
		$( 'table.multipageimage' ).load( page + ' table.multipageimage', ajaxifyPageNavigation );
	}

	function ajaxifyPageNavigation() {

		// Intercept the default action of the links in the thumbnail navigation
		$( '.thumbinner' ).one( 'click', 'a', function( event ) {
			event.stopImmediatePropagation();
			loadPage( this.href );
			return false;
		});

		// Prevent the submission of the page select form and instead call loadPage
		$( 'form[name="pageselector"]' ).removeAttr( 'onchange' ).one( 'change submit', function (event) {
			event.stopImmediatePropagation();
			loadPage( this.action + '?' + $(this).serialize() );
			return false;
		});
	}

	// The presence of table.multipageimage signifies that this file is a multi-page image
	if( mw.config.get( 'wgNamespaceNumber' ) === 6 && $( 'table.multipageimage' ).length !== 0 ) {
		$( ajaxifyPageNavigation );
	}

}( mediaWiki, jQuery ) );

