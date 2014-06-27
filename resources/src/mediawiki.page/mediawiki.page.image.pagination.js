/**
 * Change multi-page image navigation so that the current page display can be changed
 * without a page reload. Currently, the only image formats that can be multi-page images are
 * PDF and DjVu files
 */
( function ( mw, $ ) {

	// Initialize ajax request variable
	var xhr;

	// Use jQuery's load function to specifically select and replace table.multipageimage's child
	// tr with the new page's table.multipageimage's tr element.
	// table.multipageimage always has only one row.
	function loadPage( page, hist ) {
		if ( xhr ) {
			// Abort previous requests to prevent backlog created by
			// repeatedly pressing back/forwards buttons
			xhr.abort();
		}

		var $multipageimage = $( 'table.multipageimage' ),
			$spinner;

		// Add a new spinner if one doesn't already exist
		if ( !$multipageimage.find( '.mw-spinner' ).length ) {

			$spinner = $.createSpinner( {
				size: 'large',
				type: 'block'
			} )
				// Set the spinner's dimensions equal to the table's dimensions so that
				// the current scroll position is not lost after the table is emptied prior to
				// its contents being updated
				.css( {
					height: $multipageimage.find( 'tr' ).height(),
					width: $multipageimage.find( 'tr' ).width()
				} );

			$multipageimage.empty().append( $spinner );
		}

		xhr = $.ajax( {
			url: page,
			success: function ( data ) {
				// Load the page
				$multipageimage.empty().append( $( data ).find( 'table.multipageimage tr' ) );
				// Fire hook because the page's content has changed
				mw.hook( 'wikipage.content' ).fire( $multipageimage );
				// Set up the new page for pagination
				ajaxifyPageNavigation();
				// Add new page of image to history.  To preserve the back-forwards chain in the browser,
				// if the user gets here via the back/forward button, don't update the history.
				if ( window.history && history.pushState && !hist ) {
					history.pushState( { url: page }, document.title, page );
				}
			}
		} );
	}

	function ajaxifyPageNavigation() {
		// Intercept the default action of the links in the thumbnail navigation
		$( '.multipageimagenavbox' ).one( 'click', 'a', function ( e ) {
			var page, uri;

			// Generate the same URL on client side as the one generated in ImagePage::openShowImage.
			// We avoid using the URL in the link directly since it could have been manipulated (bug 66608)
			page = Number( mw.util.getParamValue( 'page', this.href ) );
			uri = new mw.Uri( mw.util.wikiScript() )
				.extend( { title: mw.config.get( 'wgPageName' ), page: page } )
				.toString();

			loadPage( uri );
			e.preventDefault();
		} );

		// Prevent the submission of the page select form and instead call loadPage
		$( 'form[name="pageselector"]' ).one( 'change submit', function ( e ) {
			loadPage( this.action + '?' + $( this ).serialize() );
			e.preventDefault();
		} );
	}

	$( document ).ready( function () {
		// The presence of table.multipageimage signifies that this file is a multi-page image
		if ( mw.config.get( 'wgNamespaceNumber' ) === 6 && $( 'table.multipageimage' ).length !== 0 ) {
			ajaxifyPageNavigation();

			// Set up history.pushState (if available), so that when the user browses to a new page of
			// the same file, the browser's history is updated. If the user clicks the back/forward button
			// in the midst of navigating a file's pages, load the page inline.
			if ( window.history && history.pushState && history.replaceState ) {
				history.replaceState( { url: window.location.href }, '' );
				$( window ).on( 'popstate', function ( e ) {
					var state = e.originalEvent.state;
					if ( state ) {
						loadPage( state.url, true );
					}
				} );
			}
		}
	} );
}( mediaWiki, jQuery ) );
