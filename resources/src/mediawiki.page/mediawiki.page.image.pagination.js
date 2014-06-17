/*!
 * Change multi-page image navigation so that the current page display can be changed
 * without a page reload. Currently, the only image formats that can be multi-page images are
 * PDF and DjVu files.
 */
( function ( mw, $ ) {
	var jqXhr, $multipageimage, $spinner;

	/**
	 * Fetch the next page and use jQuery to swap the table.multipageimage contents.
	 * @param {string} url
	 * @param {boolean} [hist=false] Whether this is a load triggered by history
	 *  navigation (if true, this function won't push a new history state, for the
	 *  browser did that already).
	 */
	function loadPage( url, hist ) {
		var $tr;
		if ( jqXhr ) {
			// Prevent race conditions and piling up pending requests
			jqXhr.abort();
			jqXhr = undefined;
		}

		// Add a new spinner if one doesn't already exist
		if ( !$spinner ) {

			$tr = $multipageimage.find( 'tr' );
			$spinner = $.createSpinner( {
				size: 'large',
				type: 'block'
			} )
				// Set the spinner's dimensions equal to the table's dimensions so that
				// the current scroll position is not lost after the table is emptied prior to
				// its contents being updated
				.css( {
					height: $tr.outerHeight(),
					width: $tr.outerWidth()
				} );

			$multipageimage.empty().append( $spinner );
		}

		// @todo We shouldn't fetch the entire page. Ideally we'd either fetch
		// only the content portion or fetch only the data (thumbnail urls) and
		// update the interface manually.
		jqXhr = $.ajax( url ).done( function ( data ) {
			jqXhr = $spinner = undefined;

			// Replace table contents
			$multipageimage.empty().append( $( data ).find( 'table.multipageimage' ).contents() );

			bindPageNavigation( $multipageimage );

			// Fire hook because the page's content has changed
			mw.hook( 'wikipage.content' ).fire( $multipageimage );

			// Add new page of image to history.  To preserve the back-forwards chain in the browser,
			// if the user gets here via the back/forward button, don't update the history.
			if ( history.pushState && !hist ) {
				history.pushState( { tag: 'mw-pagination' }, document.title, url );
			}
		} );
	}

	function bindPageNavigation( $container ) {
		// Intercept the default action of the links in the thumbnail navigation
		$container.find( '.multipageimagenavbox' ).one( 'click', 'a', function ( e ) {
			loadPage( this.href );
			e.preventDefault();
		} );

		// Prevent the submission of the page select form and instead call loadPage
		$container.find( 'form[name="pageselector"]' ).one( 'change submit', function ( e ) {
			loadPage( this.action + '?' + $( this ).serialize() );
			e.preventDefault();
		} );
	}

	$( document ).ready( function () {
		if ( mw.config.get( 'wgNamespaceNumber' ) !== 6 ) {
			return;
		}

		// The presence of table.multipageimage signifies that this file is a multi-page image
		$multipageimage = $( 'table.multipageimage' );
		if ( !$multipageimage.length ) {
			return;
		}
		bindPageNavigation( $multipageimage );

		// Set up history.pushState (if available), so that when the user browses to a new page of
		// the same file, the browser's history is updated. If the user clicks the back/forward button
		// in the midst of navigating a file's pages, load the page inline.
		if ( history.pushState && history.replaceState ) {
			history.replaceState( { tag: 'mw-pagination' }, '' );
			$( window ).on( 'popstate', function ( e ) {
				var state = e.originalEvent.state;
				if ( state && state.tag === 'mw-pagination' ) {
					loadPage( location.href, true );
				}
			} );
		}
	} );
}( mediaWiki, jQuery ) );
