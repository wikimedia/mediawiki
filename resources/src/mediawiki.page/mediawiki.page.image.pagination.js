/*!
 * Implement AJAX navigation for multi-page images so the user may browse without a full page reload.
 */
( function ( mw, $ ) {
	var jqXhr, $multipageimage, $spinner;

	/* Fetch the next page and use jQuery to swap the table.multipageimage contents.
	 * @param {string} url
	 * @param {boolean} [hist=false] Whether this is a load triggered by history navigation (if
	 *   true, this function won't push a new history state, for the browser did so already).
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
				// Copy the old content dimensions equal so that the current scroll position is not
				// lost between emptying the table is and receiving the new contents.
				.css( {
					height: $tr.outerHeight(),
					width: $tr.outerWidth()
				} );

			$multipageimage.empty().append( $spinner );
		}

		// @todo Don't fetch the entire page. Ideally we'd only fetch the content portion or the data
		// (thumbnail urls) and update the interface manually.
		jqXhr = $.ajax( url ).done( function ( data ) {
			jqXhr = $spinner = undefined;

			// Replace table contents
			$multipageimage.empty().append( $( data ).find( 'table.multipageimage' ).contents() );

			bindPageNavigation( $multipageimage );

			// Fire hook because the page's content has changed
			mw.hook( 'wikipage.content' ).fire( $multipageimage );

			// Update browser history and address bar. But not if we came here from a history
			// event, in which case the url is already updated by the browser.
			if ( history.pushState && !hist ) {
				history.pushState( { tag: 'mw-pagination' }, document.title, url );
			}
		} );
	}

	function bindPageNavigation( $container ) {
		$container.find( '.multipageimagenavbox' ).one( 'click', 'a', function ( e ) {
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

		$container.find( 'form[name="pageselector"]' ).one( 'change submit', function ( e ) {
			loadPage( this.action + '?' + $( this ).serialize() );
			e.preventDefault();
		} );
	}

	$( function () {
		if ( mw.config.get( 'wgNamespaceNumber' ) !== 6 ) {
			return;
		}
		$multipageimage = $( 'table.multipageimage' );
		if ( !$multipageimage.length ) {
			return;
		}

		bindPageNavigation( $multipageimage );

		// Update the url using the History API (if available)
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
