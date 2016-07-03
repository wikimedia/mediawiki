/*!
 * Implement AJAX navigation for multi-page images so the user may browse without a full page reload.
 */
( function ( mw, $ ) {
	/*jshint latedef:false */
	var jqXhr, $multipageimage, $spinner,
		cache = {},
		cacheOrder = [];

	/* Fetch the next page, caching up to 10 last-loaded pages.
	 * @param {string} url
	 * @return {jQuery.Promise}
	 */
	function fetchPageData( url ) {
		if ( jqXhr && jqXhr.abort ) {
			// Prevent race conditions and piling up pending requests
			jqXhr.abort();
		}
		jqXhr = undefined;

		// Try the cache
		if ( cache[ url ] ) {
			// Update access freshness
			cacheOrder.splice( $.inArray( url, cacheOrder ), 1 );
			cacheOrder.push( url );
			return $.Deferred().resolve( cache[ url ] ).promise();
		}

		// TODO Don't fetch the entire page. Ideally we'd only fetch the content portion or the data
		// (thumbnail urls) and update the interface manually.
		jqXhr = $.ajax( url ).then( function ( data ) {
			return $( data ).find( 'table.multipageimage' ).contents();
		} );

		// Handle cache updates
		jqXhr.done( function ( $contents ) {
			jqXhr = undefined;

			// Cache the newly loaded page
			cache[ url ] = $contents;
			cacheOrder.push( url );

			// Remove the oldest entry if we're over the limit
			if ( cacheOrder.length > 10 ) {
				delete cache[ cacheOrder[ 0 ] ];
				cacheOrder = cacheOrder.slice( 1 );
			}
		} );

		return jqXhr.promise();
	}

	/* Fetch the next page and use jQuery to swap the table.multipageimage contents.
	 * @param {string} url
	 * @param {boolean} [hist=false] Whether this is a load triggered by history navigation (if
	 *   true, this function won't push a new history state, for the browser did so already).
	 */
	function switchPage( url, hist ) {
		var $tr, promise;

		// Start fetching data (might be cached)
		promise = fetchPageData( url );

		// Add a new spinner if one doesn't already exist and the data is not already ready
		if ( !$spinner && promise.state() !== 'resolved' ) {
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

		promise.done( function ( $contents ) {
			$spinner = undefined;

			// Replace table contents
			$multipageimage.empty().append( $contents.clone() );

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
			var page, url;

			// Generate the same URL on client side as the one generated in ImagePage::openShowImage.
			// We avoid using the URL in the link directly since it could have been manipulated (bug 66608)
			page = Number( mw.util.getParamValue( 'page', this.href ) );
			url = mw.util.getUrl( mw.config.get( 'wgPageName' ), { page: page } );

			switchPage( url );
			e.preventDefault();
		} );

		$container.find( 'form[name="pageselector"]' ).one( 'change submit', function ( e ) {
			switchPage( this.action + '?' + $( this ).serialize() );
			e.preventDefault();
		} );
	}

	$( function () {
		if ( mw.config.get( 'wgCanonicalNamespace' ) !== 'File' ) {
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
					switchPage( location.href, true );
				}
			} );
		}
	} );
}( mediaWiki, jQuery ) );
